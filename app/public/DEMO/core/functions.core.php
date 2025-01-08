<?php
date_default_timezone_set('Asia/Bangkok');
/* ============== DATABASES CONNECTION ============== */
function DBConnect($dbsite) {
    // switch($dbsite) {
    //     case "APP":
    //         $HOST  = "localhost";
    //         $DBNM  = "patarapp";
    //         $DBCon = array(
    //             "DS_NAME" => "mysql:host={$HOST};dbname={$DBNM}",
    //             "DB_USNM" => "abn",
    //             "DB_PSWD" => "secure!193"
    //         );
    //     break;
    //     case "SAP":
    //         $DRVR  = "SQL Server Native Client 11.0";
    //         $HOST = "192.168.30.1";
    //         $DBNM = $_SESSION['SITE']['SAP_DBName'];
    //         $DBCon = array(
    //             "DS_NAME" => "odbc:DRIVER={$DRVR};SERVER={$HOST};DATABASE={$DBNM}",
    //             "DB_USNM" => "sa",
    //             "DB_PSWD" => "BXSasl2023"
    //         );
    //     break;
    // }
    /**************** SERVER TEST **********************/
    switch($dbsite) {
        case "APP":
            $HOST  = "localhost";
            $DBNM  = "demoapp";
            $DBCon = array(
                "DS_NAME" => "mysql:host={$HOST};dbname={$DBNM}",
                "DB_USNM" => "root",
                "DB_PSWD" => "s3cur3!KBI"
            );
        break;
        case "SAP":
            $DRVR  = "SQL Server Native Client 11.0";
            $HOST = "SERVER2\SERVER2";
            $DBNM = $_SESSION['SITE']['SAP_DBName'];
            $DBCon = array(
                "DS_NAME" => "odbc:DRIVER={$DRVR};SERVER={$HOST};DATABASE={$DBNM}",
                "DB_USNM" => "sa",
                "DB_PSWD" => "p@ssw0rd"
            );
        break;
    }
    
    /**************** END SERVER TEST **********************/
    try {
        $conn = new PDO($DBCon['DS_NAME'], $DBCon['DB_USNM'], $DBCon['DB_PSWD']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        return "ERR : $e";
    }
}

function SQLtoHANA($SQL) { /* แปลง SQL Statement จาก SQL Server เป็น HANA */
    // $TXT = str_replace(array("[","]"), array("\"","\""), $SQL);
    // return $TXT;
    return $SQL;
}

function SapTH($UTF8Text) { /* แปลง ISO-8859-11 เป็น UTF-8 เพื่อแสดงผลภาษาไทย */
    return iconv("ISO-8859-11", "UTF-8", $UTF8Text);
}

function SapTHSearch($UTF8Text) { /* แปลง UTF-8 เป็น ISO-8859-11 เพื่อค้นหาด้วยภาษาไทย */
    return iconv("UTF-8", "ISO-8859-11", $UTF8Text);
}

function base64_url_encode($input){
    return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input){
    return base64_decode(strtr($input, '-_,', '+/='));
}

function GetOwnCode($EmpCode) {
    if($EmpCode == "" || $EmpCode == null) {
        $OWNCODE = "-1";
    } else {
        $OWNSQL = "SELECT TOP 1 T0.[empID] FROM OHEM T0 WHERE T0.[ExtEmpNo] = '$EmpCode' AND T0.[Active] = 'Y'";
        $OWNRST = DBConnect("SAP")->query(SQLtoHANA($OWNSQL))->fetchAll();
        $OWNCODE = (!$OWNRST) ? "-1" : $OWNRST[0]['empID'];
    }
    return $OWNCODE;
}

function ImportSAP($SiteID, $DocType, $DocEntry) {
    $UKEY    = $_SESSION['UKEY'];
    $SSID    = $_SESSION['SSID'];

    switch($DocType) {
        case "ORDR":
            switch($SiteID) {
                case "0":
                case 0  : $url = "http://192.168.30.1:1800/WEBAPI/api/Documents/ORDR";  $OcrCode = "SP"; break;
                case "1":
                case 1  : $url = "http://192.168.30.1:1800/WEBAPI/SPET/api/Documents/ORDR1"; $OcrCode = "SPET"; break;
            }
            $SQL1 =
                "SELECT
                    T0.DocEntry, T0.DocType, T0.DocNum, T0.DocDate, T0.DocDueDate, T0.CardCode, T0.CardName, T0.LicTradeNum,
                    T0.SlpCode, T0.GroupNum, T0.BilltoCode, T0.ShiptoCode,
                    T0.DiscPcnt, T0.DiscTotal, T0.DocTotal, T0.VatSum, T0.U_PONo, T0.Comments, T0.TaxType, T1.EmpCode, T0.U_SO_Type
                FROM order_header T0
                LEFT JOIN users T1 ON T0.uKeyCreate = T1.uKey AND T1.UserStatus = 'A'
                WHERE T0.DocEntry = $DocEntry LIMIT 1";
            $RST1 = DBConnect("APP")->query($SQL1)->fetchAll()[0];
            $EmpCode = $RST1['EmpCode'];

            $OWNCODE = GetOwnCode($EmpCode);

            $BeginStr = ($RST1['DocType'] == 'SD') ? "SO" : "SO";
            $SQL2 = "SELECT T0.[Series] FROM NNM1 T0 WHERE T0.[BeginStr] LIKE '$BeginStr%' AND T0.[Indicator] = '".date('Y-m',strtotime($RST1['DocDate']))."' AND T0.[IsForCncl] = 'N'";
            $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll()[0];

            $SAP[0]['WebNumber']      = $RST1['DocNum'];
            $SAP[0]['CardCode']       = $RST1['CardCode'];
            $SAP[0]['CardName']       = $RST1['CardName'];
            $SAP[0]['DocStatus']      = "O";
            $SAP[0]['Canceled']       = "N";
            $SAP[0]['DocDate']        = date("Y-m-d",strtotime($RST1['DocDate']));
            $SAP[0]['DocDueDate']     = date("Y-m-d",strtotime($RST1['DocDueDate']));
            $SAP[0]['TaxDate']        = date("Y-m-d",strtotime($RST1['DocDate']));
            $SAP[0]['NumAtCard']      = $RST1['U_PONo'];
            $SAP[0]['LicTradNum']     = $RST1['LicTradeNum'];
            $SAP[0]['CntctCode']      = "0";
            $SAP[0]['Series']         = $RST2['Series'];
            // $SAP[0]['Series']         = "-1";
            $SAP[0]['SalesEmployee']  = $RST1['SlpCode'];
            $SAP[0]['OwnerCode']      = $OWNCODE;
            $SAP[0]['GroupNum']       = $RST1['GroupNum'];
            $SAP[0]['DiscPrcnt']      = intval(number_format($RST1['DiscPcnt'],3)); 
            $SAP[0]['DocTotal']       = $RST1['DocTotal'];
            $SAP[0]['PayToCode']      = $RST1['BilltoCode'];
            $SAP[0]['ShipToCode']     = $RST1['ShiptoCode'];
            $SAP[0]['DiscSum']        = $RST1['DiscTotal'];
            $SAP[0]['Comments']       = $RST1['Comments'];
            $SAP[0]['U_Web_NO']       = $RST1['DocType']."-".$RST1['DocNum'];
            $SAP[0]['U_Web_Entry']    = $RST1['DocEntry'];
            $SAP[0]['U_SO_TYPE']      = $RST1['U_SO_Type'];
            //$SAP[0]['U_SO_TYPE']      = "";

            $SQL3 = 
                "SELECT T0.ItemCode, T0.CodeBars, T0.ItemName, T0.VisOrder, T0.WhsCode, T0.Quantity, T0.GrandPrice,
                    T0.LineTotal, T0.UnitMsr, T0.Line_Disc1, T0.Line_Disc2, T0.Line_Disc3, T0.Line_Disc4, T0.Line_Disc5, T0.Line_Disc0, T0.UnitPrice
                FROM order_detail T0
                WHERE T0.DocEntry = $DocEntry AND T0.LineStatus != 'I'";
            $RST3 = DBConnect("APP")->query($SQL3)->fetchAll();
            foreach($RST3 as $r=>$data) {
                if($data['GrandPrice'] == $data['UnitPrice']) {
                    $DiscPrcnt = 0;
                } else {
                    $GrandPrice = round($data['GrandPrice'],2);
                    $UnitPrice  = round($data['UnitPrice'],2);

                    $DiscPrcnt  = (($GrandPrice - $UnitPrice) / $GrandPrice) * 100;
                }

                $CodeBars = ($data['CodeBars'] == "null") ? "" : $data['CodeBars'];

                $SAP[0]['Lines'][$r]['ItemCode']        = $data['ItemCode'];
                // $SAP[0]['Lines'][$r]['CodesBars']       = $CodeBars;
                $SAP[0]['Lines'][$r]['ItemDescription'] = $data['ItemName'];
                $SAP[0]['Lines'][$r]['LineNum']         = $data['VisOrder'];
                $SAP[0]['Lines'][$r]['WhsCode']         = $data['WhsCode'];
                $SAP[0]['Lines'][$r]['Quantity']        = $data['Quantity'];
                $SAP[0]['Lines'][$r]['FreeText']        = "";
                $SAP[0]['Lines'][$r]['ShipDate']        = date("Y-m-d",strtotime($RST1['DocDueDate']));
               
                switch($RST1['TaxType']) {
                    case "X0":
                    case  "x0" :
                    case  "S00" :
                    case  "s00" :
                        $SAP[0]['Lines'][$r]['UnitPrice']       = $data['GrandPrice'];
                        break;
                    case "S07":
                    case "s07"  :
                        $PriceAfVat =  ($data['GrandPrice'] -  (($data['GrandPrice']*$DiscPrcnt)/100))*1.07;
                        $GrossTotal = $PriceAfVat * $data['Quantity'];
                        $SAP[0]['Lines'][$r]['GPBefDisc']       = $data['GrandPrice'];//
                        $SAP[0]['Lines'][$r]['GrossTotal']       = $data['LineTotal']; //
                        $SAP[0]['Lines'][$r]['PriceAfVat']       = $data['UnitPrice'];
                        break;
                }

                $SAP[0]['Lines'][$r]['VatGroup']        = $RST1['TaxType'];
                $SAP[0]['Lines'][$r]['LineTotal']       = $data['LineTotal'];
                $SAP[0]['Lines'][$r]['UomCode']         = $data['UnitMsr'];
                $SAP[0]['Lines'][$r]['DiscPrcnt']       = number_format($DiscPrcnt,2); 
                $SAP[0]['Lines'][$r]['U_Disct1']        = $data['Line_Disc1'];
                $SAP[0]['Lines'][$r]['U_Disct2']        = $data['Line_Disc2'];
                $SAP[0]['Lines'][$r]['U_Disct3']        = $data['Line_Disc3'];
                $SAP[0]['Lines'][$r]['U_Disct4']        = $data['Line_Disc4'];
                $SAP[0]['Lines'][$r]['U_Disct5']        = $data['Line_Disc5'];
                $SAP[0]['Lines'][$r]['U_DiscAmnt']      = $data['Line_Disc0'];
                $SAP[0]['Lines'][$r]['U_CAMPAIGN']      = "";
                $SAP[0]['Lines'][$r]['OrcCode2']        = "SALE";
                $SAP[0]['Lines'][$r]['OrcCode']         = $OcrCode;
            }
            
        break;
    }
    $myJSON = json_encode($SAP);
    //$myJSON = json_encode($SAP, JSON_UNESCAPED_UNICODE);
    // $curl = curl_init($url);
    //         curl_setopt($curl, CURLOPT_URL, $url);
    //         curl_setopt($curl, CURLOPT_POST, true);
    //         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //         $headers = array(
    //             "Accept: application/json",
    //             "Content-Type: application/json",
    //         );
    //         curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //         curl_setopt($curl, CURLOPT_POSTFIELDS, $myJSON);
    // $resp = curl_exec($curl);
    //         curl_close($curl);
    // $dataX = json_decode($resp, true);

    // if($dataX[0]['errCode'] == 0) {
    //     switch($DocType) {
    //         case "ORDR":
    //             $SQL5 = "SELECT T0.[Beginstr] FROM NNM1 T0 WHERE T0.[Series] = '".$dataX[0]['Series']."'";
    //             $RST5 = DBConnect("SAP")->query(SQLtoHANA($SQL5))->fetchAll();
    //             $NewEntry  = $dataX[0]['DocEntry'];
    //             $NewDocNum = $RST5[0]['Beginstr']."-".$dataX[0]['DocNum'];
                
    //             $SQL4 =
    //                 "UPDATE order_header SET
    //                     DocStatus = 'C',
    //                     AppStatus = 'Y',
    //                     IntStatus = 5,
    //                     uKeyUpdate = :UKEY,
    //                     ssidUpdate = :SSID,
    //                     DateUpdate = NOW(),
    //                     uKeyImport = :UKEY,
    //                     ssidImport = :SSID,
    //                     DateImport = NOW(),
    //                     ImportEntry = :NewEntry,
    //                     ImportDocNum = :NewDocNum
    //                 WHERE DocEntry = :DocEntry";
    //             $QRY4 = DBConnect("APP")->prepare($SQL4);
    //             $QRY4->bindparam(":UKEY",     $UKEY);
    //             $QRY4->bindparam(":SSID",     $SSID);
    //             $QRY4->bindparam(":NewEntry", $NewEntry);
    //             $QRY4->bindparam(":NewDocNum", $NewDocNum);
    //             $QRY4->bindparam(":DocEntry", $DocEntry);
    //             $QRY4->execute();
    //         break;
    //     }
    //     $inval['Status']  = "OK";
    //     $inval['Message'] = "บันทึกข้อมูลสำเร็จ<br/>เลขที่ใบสั่งขาย: ".$NewDocNum;
    // } else {
    //     $inval['Status']  = "ERR";
    //     $inval['Message'] = $dataX[0]['errCode']."<br/>".$dataX[0]['errMsg'];
    // }
    $inval['Status']  = "OK";
    $inval['Message'] = "บันทึกข้อมูลสำเร็จ<br/>เลขที่ใบสั่งขาย: ".$RST1['DocType']."-".$RST1['DocNum'];
    return $inval;
}

function CancelSAP($SiteID, $DocType, $DocEntry) {
    $UKEY    = $_SESSION['UKEY'];
    $SSID    = $_SESSION['SSID'];

    switch($DocType) {
        case "ORDR":
            switch($SiteID) {
                case "0":
                case 0  : $url = "http://192.168.30.1:1800/WEBAPI/api/Documents/CANCEL";  break;
                case "1":
                case 1  : $url = "http://192.168.30.1:1800/WEBAPI/SPET/api/Documents/CANCEL1"; break;
            }
            /* CHECK DELIVERY WAS CREATED? */
            $SQL1 = "SELECT DISTINCT T0.[TrgetEntry] FROM RDR1 T0 WHERE T0.[DocEntry] = $DocEntry AND T0.[TrgetEntry] > 0";
            $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll();
            $API = (!$RST1) ? "N" : "Y" ;
            if ($API == "Y"){
                $SQL6 = "SELECT DISTINCT T0.[DocEntry] FROM RDR1 T0 LEFT JOIN ORDR T1 ON T0.[DocEntry] = T1.[DocEntry] WHERE (T0.[LineStatus] = 'C' OR T0.[OpenQty] != T0.[Quantity]) AND T1.[DocStatus] = 'O' AND T0.[DocEntry] = ".$DocEntry;
                $RST6 = DBConnect("SAP")->query(SQLtoHANA($SQL6))->fetchAll();
                $API = (!$RST6) ? "N" : "Y" ;
            }
        break;
    }
    if($API == "Y") {
        $inval['Status']  = "ERR";
        $inval['Message'] = "ไม่สามารถยกเลิกได้เนื่องจาก<br/>เอกสารนี้ดำเนินการไปเรียบร้อยแล้ว";
    } else {
        switch($DocType) {
            case "ORDR":
                $SQL2 = "SELECT T0.DocType, T0.DocNum FROM order_header T0 WHERE T0.ImportEntry = $DocEntry LIMIT 1";
                $RST2 = DBConnect("APP")->query($SQL2)->fetchAll()[0];

                $SAP['WebNumber']      = $RST2['DocNum'];
                $SAP['DocEntry']       = $DocEntry;
                $SAP['CANCELED']       = "Y";
            break;
        }

        $myJSON = json_encode($SAP);
        $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers = array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $myJSON);
        $resp = curl_exec($curl);
                curl_close($curl);
        $dataX = json_decode($resp, true);

        // echo $myJSON;

        // echo var_dump($dataX)." ".$DocEntry;

        if($dataX[0]['errCode'] == 0) {
            switch($DocType) {
                case "ORDR":
                    $SQL3 = 
                        "UPDATE order_header SET
                            CANCELED  = 'Y',
                            DocStatus = 'C',
                            uKeyCancel = :UKEY,
                            DateCancel = NOW(),
                            ssidCancel = :SSID,
                            uKeyUpdate = :UKEY,
                            DateUpdate = NOW(),
                            ssidUpdate = :SSID,
                            IntStatus  = 0
                        WHERE ImportEntry = :ImportEntry";
                    $QRY3 = DBConnect("APP")->prepare($SQL3);
                    $QRY3->bindparam(":UKEY",     $UKEY);
                    $QRY3->bindparam(":SSID",     $SSID);
                    $QRY3->bindparam(":ImportEntry", $DocEntry);
                    $QRY3->execute();
                break;
            }
            $inval['Status']  = "OK";
            $inval['Message'] = "ยกเลิกใบสั่งขายสำเร็จ";
        } else {
            $inval['Status']  = "ERR";
            $inval['Message'] = $dataX[0]['errCode']."<br/>".$dataX[0]['errMsg'];
        }
    }
    return $inval;
}

function TitlePage($url) {
    $SQL = "SELECT MenuName FROM menulists WHERE MenuLink = '$url' LIMIT 1";
    $RST = DBConnect("APP")->query($SQL)->fetchAll();
    $name = "| ".$RST[0]['MenuName'];
    return $name;
}

function FullMonth($NumMonth){
    switch  ($NumMonth){
      case 1:
          $w = 'มกราคม';	
          break;
      case 2:
          $w = 'กุมภาพันธ์';
          break;
      case 3:
          $w = 'มีนาคม';	
          break;
      case 4:
          $w = 'เมษายน';
          break;
      case 5:
          $w = 'พฤษภาคม';	
          break;
      case 6:
          $w = 'มิถุนายน';	
          break;
      case 7:
          $w = 'กรกฎาคม';	
          break;
      case 8:
          $w = 'สิงหาคม';
          break;
      case 9:
          $w = 'กันยายน';	
          break;
      case 10:
          $w = 'ตุลาคม';	
          break;
      case 11:
          $w = 'พฤศจิกายน';	
          break;
      case 12:
          $w = 'ธันวาคม';	
          break;
  }
  return $w;
}

function txtMonth($NumMonth){
	switch  ($NumMonth){
		case 1:
			return 'ม.ค.';	
			break;
		case 2:
			return 'ก.พ.'	;
			break;
		case 3:
			return 'มี.ค.';	
			break;
		case 4:
			return 'เม.ย.';
			break;
		case 5:
			return 'พ.ค.';	
			break;
		case 6:
			return 'มิ.ย.';	
			break;
		case 7:
			return 'ก.ค.';	
			break;
		case 8:
			return 'ส.ค.';
			break;
		case 9:
			return 'ก.ย.';	
			break;
		case 10:
			return 'ต.ค.';	
			break;
		case 11:
			return 'พ.ย.';	
			break;
		case 12:
			return 'ธ.ค.';	
			break;
	}
}

function GetPrice($CardCode, $ItemCode, $Quantity) {
      $SQL1 = "SELECT TOP 1 T1.[Price] FROM OCRD T0 LEFT JOIN ITM1 T1 ON T0.[ListNum] = T1.[BasePLNum] WHERE T0.[CardCode] = '$CardCode' AND T1.[ItemCode] = '$ItemCode'";
      $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll();
      $DP = ($RST2) ? ($RST2[0]['Price']) : 0;

    // $SQL1 =
    //     "SELECT
    //         T0.ItemCode, T0.Prc_Retail, T0.Prc_Wholesale,
    //         T0.Qty_Step1, T0.Prc_Step1, T0.Qty_Step2, T0.Prc_Step2,
    //         T0.Qty_Step3, T0.Prc_Step3, T0.Qty_Step4, T0.Prc_Step4,
    //         CASE
    //             WHEN SUBSTRING(T0.PriceType,1,3) = 'PRO' THEN 1
    //             WHEN SUBSTRING(T0.PriceType,1,3) = 'GRP' THEN 2
    //         ELSE 3 END AS lnNum
    //     FROM price_detail T0
    //     LEFT JOIN price_header T1 ON T0.PriceType = T1.GroupCode
    //     WHERE
    //         (
    //             (T1.CardCode = '$CardCode' AND T1.GroupPriceStatus	 = 'A') OR
    //             (T0.PriceType = 'STD' AND T0.PriceStatus = 'A') OR
    //             (T0.PriceType = 'PRO' AND (T0.StartDate <= DATE(NOW()) AND T0.EndedDate >= DATE(NOW())) AND T0.PriceStatus = 'A' AND T0.Qty_Step1 <= $Quantity)
    //         ) AND T0.ItemCode = '$ItemCode'
    //     ORDER BY lnNum, T0.PriceType ASC, T0.DateUpdate DESC LIMIT 1";
    // $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    // if(!$RST1) {
    //     $DP = 0;
    // } else {
    //     $Quantity = intval($Quantity);
    //     foreach($RST1 as $value) {
    //         if($value['Prc_Step4'] != 0 && $value['Qty_Step4'] <= $Quantity) {
    //             $DP = $value['Prc_Step4'];
    //         } else if($value['Prc_Step3'] != 0 && $value['Qty_Step3'] <= $Quantity) {
    //             $DP = $value['Prc_Step3'];
    //         } else if($value['Prc_Step2'] != 0 && $value['Qty_Step2'] <= $Quantity) {
    //             $DP = $value['Prc_Step2'];
    //         } else if($value['Prc_Step1'] != 0 && $value['Qty_Step1'] <= $Quantity) {
    //             $DP = $value['Prc_Step1'];
    //         } else if($value['Prc_Wholesale'] != 0) {
    //             $DP = $value['Prc_Wholesale'];
    //         } else {
    //             $DP = $value['Prc_Retail'];
    //         }
    //     }
    // }


    return $DP;
}

function AddDecimal($num,$deci) {
	$newx = number_format($num,$deci);
	return str_replace(",","",$newx);
}

function ConNumText($number){ 
    $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
    $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
    $number = str_replace(",","",$number); 
    $number = str_replace(" ","",$number); 
    $number = str_replace("บาท","",$number); 
    $number = explode(".",$number); 
    if(sizeof($number)>2){ 
    return 'ทศนิยมหลายตัวนะจ๊ะ'; 
    exit; 
    } 
    $strlen = strlen($number[0]); 
    $convert = ''; 
    for($i=0;$i<$strlen;$i++){ 
        $n = substr($number[0], $i,1); 
        if($n!=0){ 
            if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
            elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; } 
            elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
            else{ $convert .= $txtnum1[$n]; } 
            $convert .= $txtnum2[$strlen-$i-1]; 
        } 
    } 
    
    $convert .= 'บาท'; 
    if($number[1]=='0' OR $number[1]=='00' OR 
    $number[1]==''){ 
    $convert .= 'ถ้วน'; 
    }else{ 
    $strlen = strlen($number[1]); 
    for($i=0;$i<$strlen;$i++){ 
    $n = substr($number[1], $i,1); 
        if($n!=0){ 
        if($i==($strlen-1) AND $n==1){$convert 
        .= 'เอ็ด';} 
        elseif($i==($strlen-2) AND 
        $n==2){$convert .= 'ยี่';} 
        elseif($i==($strlen-2) AND 
        $n==1){$convert .= '';} 
        else{ $convert .= $txtnum1[$n];} 
        $convert .= $txtnum2[$strlen-$i-1]; 
        } 
    } 
    $convert .= 'สตางค์'; 
    } 
    return $convert; 
}

function SendTHData($QryArr) {
    $DataArr = array();
    foreach ($QryArr as $k => $data) {
        foreach($data as $key => $value) { $DataArr[$k][$key] = SapTH($QryArr[$k][$key]); }
    }
    return $DataArr;
}

function utf8_strlen($s) {
    $c = strlen($s); $l = 0;
    for ($i = 0; $i < $c; ++$i) if ((ord($s[$i]) & 0xC0) != 0x80) ++$l;
    return $l;
}

?>