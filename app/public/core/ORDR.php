<?php
include('config.core.php');
include('functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();
$NewEntry = 0;
$errCode = 1;
$errMsg = "";

if (isset($_SESSION['ukey']) AND isset($_GET['x'])) {
    $url = "http://192.168.1.11/dev/api/Documents/ORDR";
    $DocEntry = $_GET['x'];
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
    "Accept: application/json",
    "Content-Type: application/json",
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $sql1 = "SELECT
                T0.DocEntry,T0.DocType,T0.DocDate,T0.DocDueDate,
                YEAR(T0.DocDate) AS 'DocYear', CASE WHEN MONTH(T0.DocDate) < 10 THEN CONCAT('0',MONTH(T0.DocDate)) ELSE MONTH(T0.DocDate) END AS 'DocMonth',
                CASE WHEN T0.DocDate > NOW() THEN 'NEW' ELSE 'OLD' END AS 'TimePeriod',
                T0.CardCode,T0.CardName,T0.DocNum,T0.SlpCode,T0.TaxType,
                T0.BilltoCode,T0.ShiptoCode,
                T0.Comments,
                T0.ShippingType,T0.U_PONo,T0.CreateUkey,
                T1.MainTeam,T0.ShipCostType
             FROM order_header T0 
                  LEFT JOIN oslp T1 ON T0.SlpCode = T1.SlpCode
             WHERE T0.DocEntry = ".$DocEntry;
    
    $sql2 = "SELECT ItemCode,CodeBars,ItemName,Quantity,WhsCode,UnitPrice,UnitMsr,GrandPrice,LineTotal,
                    CASE WHEN Line_Disc1 > 0 THEN Line_Disc1 ELSE 0 END AS Line_Disc1,
                    CASE WHEN Line_Disc2 > 0 THEN Line_Disc2 ELSE 0 END AS Line_Disc2,
                    CASE WHEN Line_Disc3 > 0 THEN Line_Disc3 ELSE 0 END AS Line_Disc3,
                    CASE WHEN Line_Disc4 > 0 THEN Line_Disc4 ELSE 0 END AS Line_Disc4,
                    CASE WHEN Line_Disc5 > 0 THEN Line_Disc5 ELSE 0 END AS Line_Disc5
            FROM order_detail 
            WHERE DocEntry = ".$DocEntry." AND LineStatus = 'O'";
    $getHeader = MySQLSelectX($sql1);
    $OrderHeader = mysqli_fetch_array($getHeader);

    $PeriodSQL = "SELECT
                    CASE WHEN (A0.DocYm = A0.NowYm) OR (A0.DocYm < A0.NowYm) THEN A0.NowYm ELSE A0.DocYm END AS 'Period',
                    CASE WHEN A0.DocYm < A0.NowYm THEN DATE(NOW()) ELSE A0.DocDate END AS 'DocDate',
                    CASE WHEN A0.DocYm < A0.NowYm THEN DATE(DATE_ADD(NOW(), INTERVAL A0.DIFF DAY)) ELSE A0.DocDueDate END AS 'DocDueDate'
                FROM (
                    SELECT
                        CONCAT(YEAR(T0.DocDate),'-',CASE WHEN MONTH(T0.DocDate) < 10 THEN CONCAT('0',MONTH(T0.DocDate)) ELSE MONTH(T0.DocDate) END) AS 'DocYm',
                        CONCAT(YEAR(NOW()),'-',CASE WHEN MONTH(NOW()) < 10 THEN CONCAT('0',MONTH(NOW())) ELSE MONTH(NOW()) END) AS 'NowYm',
                        T0.DocDate, T0.DocDueDate, DATEDIFF(T0.DocDueDate, T0.DocDate) AS 'DIFF' 
                    FROM order_header T0
                    WHERE T0.DocEntry = $DocEntry
                ) A0 LIMIT 1";
    $PeriodQRY = MySQLSelectX($PeriodSQL);
    $PeriodRST = mysqli_fetch_array($PeriodQRY);

    $Period     = $PeriodRST['Period'];
    $DocDate    = $PeriodRST['DocDate'];
    $DocDueDate = $PeriodRST['DocDueDate'];
    
    $sql4 = "SELECT T0.Series FROM NNM1 T0 WHERE T0.BeginStr LIKE '".$OrderHeader['DocType']."%' AND T0.Indicator = '$Period' AND T0.ObjectCode = 17 AND T0.Locked = 'N'";
    // $sql4 = "SELECT T0.Series FROM NNM1 T0 WHERE T0.BeginStr LIKE '".$OrderHeader['DocType']."%' AND T0.Indicator = '".date("m")."' AND T0.ObjectCode = 17 AND T0.Locked = 'N'";
    // echo $sql4;
    $getCode = SAPSelect($sql4);
    $SeriesData = odbc_fetch_array($getCode);
    
    if ($OrderHeader['U_PONo'] == '' || $OrderHeader['U_PONo'] == NULL){
        $DataCon = $OrderHeader['DocType']."V-".$OrderHeader['DocNum'];
    }else{
       $DataCon = $OrderHeader['U_PONo'];
    }

    switch ($OrderHeader['MainTeam']) {
        case 'TT2' :
            $sql3 = "SELECT OwnerCode FROM users WHERE uKey = '".$OrderHeader['CreateUkey']."' AND OwnerCode > 0 ";
            if (CHKRowDB($sql3) == 0){
                $sql3 = "SELECT T1.OwnerCode 
                         FROM apporder T0
                              LEFT JOIN users T1 ON T0.UkeyApprove = T1.uKey

                         WHERE T0.UkeyReq = 'LV036' AND T0.DocEntry = ".$DocEntry;
            }
        break;
        case 'OUL' :
        case 'TT1' :
            $sql3 = "SELECT OwnerCode FROM users WHERE uKey = '".$OrderHeader['CreateUkey']."' AND OwnerCode > 0 ";
                if (CHKRowDB($sql3) == 0){
                    $sql3 = "SELECT T1.OwnerCode 
                            FROM apporder T0
                                LEFT JOIN users T1 ON T0.UkeyApprove = T1.uKey
                            WHERE T0.AppSO = 1  AND T0.DocEntry = ".$DocEntry." 
                            ORDER BY T0.StepApprove LIMIT 1 ";
                }
        break;
        default :
                $sql3 = "SELECT OwnerCode FROM users WHERE uKey = '".$OrderHeader['CreateUkey']."'";
        break;
    }
    //echo $sql3;
    $userOwner = MySQLSelect($sql3);
    if($OrderHeader['MainTeam'] != "MT1" && $OrderHeader['MainTeam'] != "MT2") {
        switch($OrderHeader['ShipCostType']) {
            case "PRE":  $ShipCostTxt = "\n[จ่ายค่าขนส่งต้นทาง]"; break;
            case "PST":  $ShipCostTxt = "\n[ลูกค้าจ่ายค่าขนส่งปลายทาง]";; break;
            case "COD":  $ShipCostTxt = "\n[COD]"; break;
            case "FREE": $ShipCostTxt = NULL; break;
            default:     $ShipCostTxt = NULL; break;
        }
    } else {
        $ShipCostTxt = NULL;
    }
    //echo  $userOwner['OwnerCode'];
    $SAP[0]['POSNumber']      = $DataCon;
    $SAP[0]['CardCode']       = $OrderHeader['CardCode'];
    $SAP[0]['CardName']       = $OrderHeader['CardName'];
    $SAP[0]['DocDate']        = date("Y-m-d",strtotime($DocDate));
    $SAP[0]['DocDueDate']     = date("Y-m-d",strtotime($DocDueDate));
    $SAP[0]['TaxDate']        = date("Y-m-d",strtotime($DocDueDate));
    $SAP[0]['NumAtCard']      = $OrderHeader['DocNum'];
    $SAP[0]['CntctCode']      = 0;
    $SAP[0]['SalesEmployee']  = $OrderHeader['SlpCode'];
    $SAP[0]['OwnerCode']      = $userOwner['OwnerCode'];
    $SAP[0]['Comments']       = $OrderHeader['Comments']."\n[นำเข้าจากระบบ Eurox Force เลขที่: ".$OrderHeader['DocType']."V-".$OrderHeader['DocNum']."]".$ShipCostTxt;
    $SAP[0]['ShipToCode']     = $OrderHeader['ShiptoCode'];
    $SAP[0]['PayToCode']      = $OrderHeader['BilltoCode'];
    $SAP[0]['U_ShippingType'] = $OrderHeader['ShippingType'];
    $SAP[0]['Series']         = $SeriesData['Series'];
    // $SAP[0]['Series'] = 6306;

    $i=0;
    $getDetail = MySQLSelectX($sql2);

    while ($OrderDetail = mysqli_fetch_array($getDetail)) {

        if($OrderDetail['GrandPrice'] == $OrderDetail['UnitPrice']) {
            $DiscPrcnt = 0;
        } else {
            $GrandPrice = intval($OrderDetail['GrandPrice']);
            $UnitPrice  = intval($OrderDetail['UnitPrice']);
            $DiscPrcnt  = (($GrandPrice - $UnitPrice) / $GrandPrice) * 100;
        }

        $SAP[0]['Lines'][$i]['ItemCode']        = $OrderDetail['ItemCode'];
        $SAP[0]['Lines'][$i]['CodesBars']       = $OrderDetail['CodeBars'];
        $SAP[0]['Lines'][$i]['ItemDescription'] = $OrderDetail['ItemName'];
        $SAP[0]['Lines'][$i]['FreeText']        = "";
        $SAP[0]['Lines'][$i]['Quantity']        = $OrderDetail['Quantity'];
        $SAP[0]['Lines'][$i]['ShipDate']        = date("Y-m-d",strtotime($OrderHeader['DocDueDate']));
        $SAP[0]['Lines'][$i]['WhsCode']         = $OrderDetail['WhsCode'];
        $SAP[0]['Lines'][$i]['UnitPrice']       = $OrderDetail['GrandPrice'];
        $SAP[0]['Lines'][$i]['LineTotal']       = $OrderDetail['LineTotal'];
        // $SAP[0]['Lines'][$i]['VatGroup']        = $OrderHeader['TaxType'];
        $SAP[0]['Lines'][$i]['VatGroup']        = "S07";
        $SAP[0]['Lines'][$i]['UomCode']         = $OrderDetail['UnitMsr'];
        //$SAP[0]['Lines'][$i]['DiscPrcnt']       = $DiscPrcnt;
        $SAP[0]['Lines'][$i]['U_Disct1']        = $OrderDetail['Line_Disc1'];
        $SAP[0]['Lines'][$i]['U_Disct2']        = $OrderDetail['Line_Disc2'];
        $SAP[0]['Lines'][$i]['U_Disct3']        = $OrderDetail['Line_Disc3'];
        $SAP[0]['Lines'][$i]['U_Disct4']        = $OrderDetail['Line_Disc4'];
        // $SAP[0]['Lines'][$i]['U_Disct5']        = $OrderDetail['Line_Disc5'];
        $i++;
    }
    $myJSON = json_encode($SAP);
    $JsonImport = json_encode($SAP, JSON_UNESCAPED_UNICODE);
    //echo $JsonImport;
    curl_setopt($curl, CURLOPT_POSTFIELDS, $myJSON);
    $resp = curl_exec($curl);
    curl_close($curl);
    $dataX = json_decode($resp,true);
    //echo $myJSON;
    $NewEntry = $dataX[0]['DocEntry'];
    $errCode = $dataX[0]['errCode'];
    $errMsg = $dataX[0]['errMsg'];

    // echo $myJSON;

}
// $errCode = 0;
// $NewEntry = 223637;

if ($errCode != 0){
    // echo $errCode."  ".$errMsg;
    $arrCol['Status'] = 'N';
	$arrCol['errMsg'] = $errCode."  ".$errMsg;
    $sms1 = "";
    $sms1 = " เลขที่: ".$OrderHeader['DocType']."V-".$OrderHeader['DocNum']."\n";
    $sms1 .= $errCode."  ".$errMsg."\n";
    LineNoti('IT',$sms1);
}else{
    $UpDateOrder = "UPDATE order_header SET DocStatus = 'C', AppStatus = 'Y', ImportUkey = '".$_SESSION['ukey']."', ImportEntry = $NewEntry WHERE DocEntry = ".$DocEntry;
    MySQLUpdate($UpDateOrder);
    $SQLToday = date("Y-m-d");
    $Date3 =  date("Y-m-d",strtotime("+3 days",strtotime($SQLToday)));
    $MsSQL = "SELECT DISTINCT  T0.[DocEntry],T0.[DocDate],T0.[DocTime],T0.[DocDueDate],T0.[CreateDate],T1.[BeginStr],T0.[DocNum],T0.DocStatus,T0.[CardCode],T0.[CardName],T0.[OwnerCode],
                           T4.U_Dim1 AS 'xCh',
                           T3.[USER_CODE],T4.[SlpName],T0.[AtcEntry],T0.[Comments],T0.[SlpCode],
                           (SELECT COUNT(P0.LineNum) FROM RDR1 P0 WHERE P0.DocEntry = T0.DocEntry) AS xDetail,
                            CASE WHEN UPPER(LEFT(T0.[Comments],2)) IN ('QQ','*QQ')   THEN 'Y' ELSE 'N' END AS QQst
          FROM  ORDR T0 
                LEFT JOIN NNM1 T1 ON T0.[Series] = T1.[Series] 
                LEFT JOIN OUSR T3 ON T0.[UserSign] = T3.[USERID] 
                LEFT JOIN OSLP T4 ON T0.[SlpCode] = T4.[SlpCode] 
                LEFT JOIN RDR1 T5 ON T0.[DocEntry] = T5.[DocEntry] 
          WHERE T5.WhsCode IN ('KSY','KB4','MT','MT2','TT-C','OUL','NST','PM','PM-KSY','PMTT-KSY') AND T0.DocEntry = ".$NewEntry;
    $sapfqry = SAPSelect($MsSQL);
    $xCount=0;
    while ($DataORDR = odbc_fetch_array($sapfqry)){
        $xCount++;
        $time = $DataORDR['DocTime'];
        if ($time > 1300) {
            $TimeType = 'PM';   
        }else{
            $TimeType = 'AM';   
        }
        $NewDocNum = $DataORDR['BeginStr'].$DataORDR['DocNum'];
        $InsertSET = "INSERT INTO picker_soheader SET SODocEntry = '".$DataORDR['DocEntry']."',
                    DocNum =  '".$DataORDR['BeginStr'].$DataORDR['DocNum']."',
                    QQst = '".$DataORDR['QQst']."',
                    DocType = 'ORDR',
                    DocDate = '".date("Y-m-d",strtotime($DataORDR['DocDate']))."',
                    OriDate = '".date("Y-m-d",strtotime($DataORDR['CreateDate']))."',
                    OriTime = '".$DataORDR['DocTime']."',
                    DocDueDate = '".date("Y-m-d",strtotime($DataORDR['DocDueDate']))."',
                    CardCode = '".$DataORDR['CardCode']."',
                    CardName = '".conutf8($DataORDR['CardName'])."',
                    DateCreate = NOW(),
                    TimeType = '".$TimeType."',
                    ItemCount = '".$DataORDR['xDetail']."',
                    TeamCode = '".$DataORDR['xCh']."',
                    SlpCode = '".$DataORDR['SlpCode']."',
                    StatusDoc = '2'";
                    //echo $InsertSET."<br>";
        $ID = MySQLInsert($InsertSET);
        $sql1 = "SELECT T0.ID,T0.TeamCode,
                        CASE WHEN T0.QQst = 'Y' THEN 'A0'
                             WHEN (T0.OriDate <= '".LastWorkDate($SQLToday)."' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A1'
                             WHEN (T0.OriDate = '".$SQLToday."'  AND  T0.OriTime < '1300' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A3'
                             WHEN (T0.TeamCode LIKE 'MT%' AND T0.DocDueDate <= '".$Date3."')  THEN 'A4'
                             WHEN (T0.OriDate = '".$SQLToday."'  AND  T0.OriTime > '1300' AND T0.TeamCode NOT LIKE 'MT%') THEN 'B1'
                             ELSE 'B2' END AS PickDay
                FROM picker_soheader T0
                WHERE T0.ID = ".$ID;
        $PickType = MySQLSelect($sql1);
        // echo $sql1."<br/><br/>";
        $noTime = 0;
        require("AddPicker.php");
        $arrCol['Status'] = 'B';
        $arrCol['errMsg'] = "บันทึกสำเร็จ เลขที่ใบสั่งขาย: ".$NewDocNum;
    }
    
}

array_push($resultArray,$arrCol);
echo json_encode($resultArray);

?>