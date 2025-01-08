<?php
include('../../core/config.core.php');
include('../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();
$output = '';


// if ($_GET['a'] == 'read') {
//     $thisYear = $_POST['opY'];
//     $thisMonth = $_POST['opM'];
//     $lastMonth = $thisMonth - 1;
//     $BillPA = " ";
//     $BillSR = " ";

    // $sql1 = "SELECT W1.*,W2.[DocNum] AS RefWMS
           
    // FROM
    //  (SELECT DISTINCT 'OINV' AS DocType,T0.[DocEntry],T2.[BeginStr] AS BillPrefix,T0.[DocNum] AS BillNo, T0.[NumAtCard] AS RefBill,T1.[BaseEntry],T0.[DocDate] AS BillDate, T0.[CardCode],T0.[CardName],T3.[SlpName] ,T0.[DocTotal] AS BillTotal,'1' AS lnRun,T0.ShipToCode
    //              FROM OINV T0
    //                  JOIN INV1 T1 ON T0.[DocEntry] = T1.[DocEntry]
    //                  LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
    //                  JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
     
    //              WHERE (MONTH(T0.[DocDate]) = '$thisMonth' AND YEAR(T0.[DocDate]) = '" . $thisYear . "') " . $BillSR . " AND T1.[BaseEntry] IS NOT NULL 
    //  UNION ALL 
    //  SELECT DISTINCT 'ODLN' AS DocType,T0.[DocEntry],T2.[BeginStr] AS BillPrefix,T0.[DocNum] AS BillNo, T0.[NumAtCard] AS RefBill,T1.[BaseEntry],T0.[DocDate] AS BillDate, T0.[CardCode],T0.[CardName],T3.[SlpName] ,T0.[DocTotal] AS BillTotal, '0' AS lnRun,T0.ShipToCode
    //              FROM ODLN T0
    //                  JOIN DLN1 T1 ON T0.[DocEntry] = T1.[DocEntry]
    //                  LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
    //                  JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
     
    //              WHERE (T2.[BeginStr] LIKE 'PA-%' OR T2.[BeginStr] LIKE 'PB-%' OR T2.[BeginStr] LIKE 'PC-%' OR T2.[BeginStr] LIKE 'PD-%') AND (MONTH(T0.[DocDate]) = '" . $thisMonth . "' AND YEAR(T0.[DocDate]) = '" . $thisYear . "') " . $BillPA . " AND T1.[BaseEntry] IS NOT NULL  
    //  ) W1
    //  JOIN ORDR W2 ON W1.BaseEntry = W2.DocEntry
    // ORDER BY W1.lnRun DESC, W1.BillDate";
//     //echo $sql1;
//     $getSAP = SAPSelect($sql1);
//     $cx = 0;
//     $OINV = "(";
//     $ODLN = "(";
//     while ($DataBill = odbc_fetch_array($getSAP)) {
//         $cx++;
//         $Data_DocNo[$cx] = $DataBill['DocEntry'];
//         $Data_DocType[$cx] = $DataBill['DocType'];

//         $datax[$DataBill['DocType']][$DataBill['DocEntry']];
//         $CHKLoop[$cx] = $datax[$DataBill['DocType']][$DataBill['DocEntry']];

//         /*$Data_DataType[$CHKLoop[$cx]] = $DataBill['DocType'];
        
//         $Data_DocEntry[$CHKLoop[$cx]] = $CHKLoop[$cx];
//         $Data_NumAtCard[$CHKLoop[$cx]] = $DataBill['RefBill'];
//         $Data_BeginStr[$CHKLoop[$cx]] = $DataBill['BillPrefix'];
//         $Data_DocNum[$CHKLoop[$cx]] = $DataBill['BillNo'];
//         $Data_BillTotal[$CHKLoop[$cx]] = $DataBill['BillTotal'];
//         $Data_CardCode[$CHKLoop[$cx]] = $DataBill['CardCode'];
//         $Data_CardName[$CHKLoop[$cx]] = $DataBill['CardName']; //
//         $Data_SlpName[$CHKLoop[$cx]] = $DataBill['SlpName']; //
//         $Data_BillDate[$CHKLoop[$cx]] = $DataBill['BillDate'];
//         $Data_SONumber[$CHKLoop[$cx]] = $DataBill['RefWMS']; //
//         $Data_ShipTo[$CHKLoop[$cx]] = $DataBill['ShipToCode']; //
//         $Data_LoadDate[$CHKLoop[$cx]] = '';
//         */
//         $Data_CardCode[$CHKLoop[$cx]] = $DataBill['CardCode'];
//         $Data_CardName[$CHKLoop[$cx]] = $DataBill['CardName'];

//         $Data_DataType[$Data_DocNo[$cx]] = $DataBill['DocType'];
//         $Data_DocEntry[$DataBill['DocEntry']] = $DataBill['DocEntry'];
//         $Data_NumAtCard[$DataBill['DocEntry']] = $DataBill['RefBill'];
//         $Data_BeginStr[$DataBill['DocEntry']] = $DataBill['BillPrefix'];
//         $Data_DocNum[$DataBill['DocEntry']] = $DataBill['BillNo'];
//         $Data_BillTotal[$DataBill['DocEntry']] = $DataBill['BillTotal'];
//         //$Data_CardCode[$DataBill['DocEntry']] = $DataBill['CardCode'];
//         //$Data_CardName[$DataBill['DocEntry']] = $DataBill['CardName']; //
//         $Data_SlpName[$DataBill['DocEntry']] = $DataBill['SlpName']; //
//         $Data_BillDate[$DataBill['DocEntry']] = $DataBill['BillDate'];
//         $Data_SONumber[$DataBill['DocEntry']] = $DataBill['RefWMS']; //
//         $Data_ShipTo[$DataBill['DocEntry']] = $DataBill['ShipToCode']; //
//         $Data_LoadDate[$DataBill['DocEntry']] = '';
        
        
//         if ($DataBill['lnRun'] == 1) {
//             $Data_Table[$DataBill['DocEntry']] = "OINV";
//             $OINV .= $DataBill['DocEntry'].",";
//             $NameLogi[$Data_Table[$DataBill['DocEntry']]][$DataBill['DocEntry']] = '';
            
//         } else {
//             $Data_Table[$DataBill['DocEntry']] = "ODLN";
//             $ODLN .= $DataBill['DocEntry'].",";
//             $NameLogi[$Data_Table[$DataBill['DocEntry']]][$DataBill['DocEntry']] = '';
//         }
//     }
//     $OINV = substr($OINV,0,-1).")";
//     $ODLN = substr($ODLN,0,-1).")";
//     $sqlOINV = "SELECT BillEntry,DATE(OutTime) AS DateLogi FROM logi_detail WHERE BillType = 'OINV' AND BillEntry IN ".$OINV;
//     $sqlODLN = "SELECT BillEntry,DATE(OutTime) AS DateLogi FROM logi_detail WHERE BillType = 'ODLN' AND BillEntry IN ".$ODLN;
//     $sqlINV1 = "SELECT T0.DocEntry,T1.loginame FROM billsr T0 LEFT JOIN logistic T1 ON T1.LogiID = T0.logi_ukey WHERE T0.DocEntry IN ".$OINV; 
//     $sqlDLN1 = "SELECT T0.DocEntry,T1.loginame FROM billpa T0 LEFT JOIN logistic T1 ON T1.LogiID = T0.logi_ukey WHERE T0.DocEntry IN ".$ODLN; 
    

//     ///echo $sqlINV1;
//     $getOINV = MySQLSelectX($sqlOINV);
//     $getODLN = MySQLSelectX($sqlODLN);

//     $getINV1 = MySQLSelectX($sqlINV1);
//     $getDLN1 = MySQLSelectX($sqlDLN1);
//     while ($ListOINV = mysqli_fetch_array($getOINV)){
//         $Data_LoadDate[$ListOINV['BillEntry']] = $ListOINV['DateLogi'];
//     }

//     while ($ListODLN = mysqli_fetch_array($getODLN)){
//         $Data_LoadDate[$ListODLN['BillEntry']] = $ListODLN['DateLogi'];
//     }
//     while ($ListINV1 = mysqli_fetch_array($getINV1)){
//         $NameLogi['OINV'][$ListINV1['DocEntry']] = $ListINV1['loginame'];

   
//     }
        

//     while ($ListDLN1 = mysqli_fetch_array($getDLN1)){
//         $NameLogi['ODLN'][$ListDLN1['DocEntry']] = $ListDLN1['loginame'];
        
//     }



//     $output = "";
//     //echo $cx;
//     for ($i = 1; $i <= $cx; $i++) {

//         // $output .= "<tr>
//         //                 <th scope='row'>
//         //                     <label class='control control--checkbox'>
//         //                         <input type='checkbox'>
//         //                         <div class='control__indicator'></div>
//         //                     </label>
//         //                 </th>
//         //                 <td>1392</td>
//         //                 <td>James Yates</td>
//         //                 <td>Web Designer</td>
//         //                 <td>+63 983 0962 971</td>
//         //                 <td>NY University</td>
//         //                 <td>12/02/2000</td>
//         //                 <td>James Yates</td>
//         //             </tr>";
//         $output .= "<tr>
//         <th scope='row'>
//             <label class='control control--checkbox'>";
//         //$output .= "<td><input type='checkbox' id='" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "' class='chklogi' onclick=\"chkdataX('" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "')\" disabled></td>";

//         "<div class='control__indicator'></div>
//             </label>
//         </th>";
//         if ($Data_NumAtCard[$Data_DocNo[$i]] != '') {
//             $output .= "<td class='txtc'>" . $Data_NumAtCard[$Data_DocNo[$i]] . "</td>";
//         } else {
//             $output .= "<td class='txtc'>" . $Data_BeginStr[$Data_DocNo[$i]] . $Data_DocNum[$Data_DocNo[$i]] . "</td>";
//         }
//         $output .= "<td class='txtc'>" . date('d/m/Y', strtotime($Data_BillDate[$Data_DocNo[$i]])) . "</td>";
//         $output .= "<td class='txtl'>";

//         if (substr($Data_CardCode[$Data_DocNo[$i]], 0, 1) == 'A') {
//             $output .= $Data_CardCode[$CHKLoop[$i]] . " - " . conutf8($Data_CardName[$CHKLoop[$i]]) . " [" . conutf8($Data_ShipTo[$Data_DocNo[$i]]) . "]";
//         } else {
//             $output .= $Data_CardCode[$CHKLoop[$i]] . " - " . conutf8($Data_CardName[$CHKLoop[$i]]);
//         }

//         $output .= "</td>";
//         $output .= "<td class='txtl'>" . conutf8($Data_SlpName[$Data_DocNo[$i]]) . "</td>";
//         $output .= "<td class='txtr'>" . number_format($Data_BillTotal[$Data_DocNo[$i]], 2) . "</td>";
        

        
//         if ($Data_LoadDate[$Data_DocNo[$i]] != '') {
//             //$LogiDate = MySQLSelect($sql1);
//             $output .= "<td class='txtc'>";
//             $output .= "<input type='text' name='logidate_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "' id='logidate_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "'  value='" . date('Y-m-d', strtotime($Data_LoadDate[$Data_DocNo[$i]])) . "' class='form-control' text-align:center;' readonly>";
//             $output .= "</td>";
//         } else {
//             $output .= "<td class='txtc'>";
//             $output .= "<input type='date' name='logidate_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "' id='logidate_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "' value='" . date('Y-m-d') . "' class='dateinput2 form-control' style='width:100px; text-align:center;'>";

//             $output .= "</td>";
//         }
    
//         $output .= "<td width='1'><input id='".$Data_Table[$Data_DocNo[$i]]."_".$Data_DocEntry[$Data_DocNo[$i]]."' type='checkbox' class='' id='date_' value=''></td>";
        
//         $output .= "<td><span id='nameshow_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "'>".$NameLogi[$Data_Table[$Data_DocNo[$i]]][$Data_DocNo[$i]]."</span ></td>
        
        
//     </tr> 
//     ";
//     }
// }

if($_GET['a'] == "read") {
    $thisYear  = $_POST['opY'];
    $thisMonth = $_POST['opM'];
    $lastMonth = $thisMonth-1;

    $SapSQL =
        "SELECT
            W1.*,W2.[DocNum] AS RefWMS
        FROM (
            SELECT DISTINCT
                'OINV' AS DocType,T0.[DocEntry],T2.[BeginStr] AS BillPrefix,T0.[DocNum] AS BillNo, T0.[NumAtCard] AS RefBill,
                T1.[BaseEntry],T0.[DocDate] AS BillDate, T0.[CardCode],T0.[CardName],T3.[SlpName] ,T0.[DocTotal] AS BillTotal,'1' AS lnRun,
                T0.ShipToCode
            FROM OINV T0
            JOIN INV1 T1 ON T0.[DocEntry] = T1.[DocEntry]
            LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
            JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
            WHERE (MONTH(T0.[DocDate]) = $thisMonth AND YEAR(T0.[DocDate]) = $thisYear) AND T1.[BaseEntry] IS NOT NULL 
            UNION ALL 
            SELECT DISTINCT
                'ODLN' AS DocType,T0.[DocEntry],T2.[BeginStr] AS BillPrefix,T0.[DocNum] AS BillNo, T0.[NumAtCard] AS RefBill,
                T1.[BaseEntry],T0.[DocDate] AS BillDate, T0.[CardCode],T0.[CardName],T3.[SlpName] ,T0.[DocTotal] AS BillTotal, '0' AS lnRun,
                T0.ShipToCode
            FROM ODLN T0
            JOIN DLN1 T1 ON T0.[DocEntry] = T1.[DocEntry]
            LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
            JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
            WHERE (MONTH(T0.[DocDate]) = $thisMonth AND YEAR(T0.[DocDate]) = $thisYear) AND T1.[BaseEntry] IS NOT NULL  
        ) W1
        JOIN ORDR W2 ON W1.BaseEntry = W2.DocEntry
        ORDER BY W1.lnRun DESC, W1.BillDate";
    $SapQRY = SAPSelect($SapSQL);
    // echo $SapSQL;
    $cx = 0;
    $nv=0;
    $dl=0;
    $ODLN = "(";
    $OINV = "(";
    while($SapRST = odbc_fetch_array($SapQRY)) {
        $cx++;
        $DocEntry[$cx] = $SapRST['DocEntry'];
        $DocType[$cx]  = $SapRST['DocType'];
        $SAP['NumAtCard'][$DocType[$cx]][$DocEntry[$cx]] = $SapRST['RefBill'];
        $SAP['DocDate'][$DocType[$cx]][$DocEntry[$cx]] = $SapRST['BillDate'];
        $SAP['CardCode'][$DocType[$cx]][$DocEntry[$cx]] = $SapRST['CardCode'];
        $SAP['CardName'][$DocType[$cx]][$DocEntry[$cx]] = $SapRST['CardName'];
        $SAP['SlpName'][$DocType[$cx]][$DocEntry[$cx]] = $SapRST['SlpName'];
        $SAP['DocTotall'][$DocType[$cx]][$DocEntry[$cx]] = $SapRST['BillTotal'];
        $SAP['ShipDate'][$DocType[$cx]][$DocEntry[$cx]] = NULL;
        $SAP['CHKed'][$DocType[$cx]][$DocEntry[$cx]] = " ";
        $SAP['Dis'][$DocType[$cx]][$DocEntry[$cx]] = " disabled ";
        $SAP['NameRetun'][$DocType[$cx]][$DocEntry[$cx]] = "";
        $SAP['DocNum'][$DocType[$cx]][$DocEntry[$cx]] = "ยังไม่ได้จัดส่ง";
        if($SapRST['DocType'] == "OINV") {
            $OINV .= $SapRST['DocEntry'].",";
            $nv++;
        } else {
            $ODLN .= $SapRST['DocEntry'].",";
            $dl++;
        }
    }
    if ($nv>0){
        $OINV = substr($OINV,0,-1).")";
    }else{
        $OINV = "('')";
    }
    if ($dl>0){
        $ODLN = substr($ODLN,0,-1).")";
    }else{
        $ODLN = "('')";
    }
    

    $sqlOINV = "SELECT DISTINCT T0.BillType,T0.BillEntry,DATE(T0.OutTime) AS DateLogi,T1.DocNum 
                FROM logi_detail T0
                     LEFT JOIN pack_header T1 ON T0.BillEntry = T1.BillEntry AND T0.BillType = T1.BillType 
                WHERE T0.BillType = 'OINV' AND T0.BillEntry IN ".$OINV."
                UNION ALL 
                SELECT DISTINCT T0.BillType,T0.BillEntry,DATE(T0.OutTime) AS DateLogi,T1.DocNum 
                FROM logi_detail T0 
                     LEFT JOIN pack_header T1 ON T0.BillEntry = T1.BillEntry AND T0.BillType = T1.BillType 
                WHERE T0.BillType = 'ODLN' AND T0.BillEntry IN ".$ODLN;
    $sqlINV1 = "SELECT 'OINV' AS BillType,T0.DocEntry AS BillEntry,T1.loginame,T0.logi_ukey 
                FROM billsr T0 
                     LEFT JOIN logistic T1 ON T1.LogiID = T0.logi_ukey 
                WHERE T0.DocEntry IN ".$OINV."  
                UNION ALL
                SELECT 'ODLN' AS BillType,T0.DocEntry AS BillEntry,T1.loginame,T0.logi_ukey 
                FROM billpa T0 
                     LEFT JOIN logistic T1 ON T1.LogiID = T0.logi_ukey 
                WHERE T0.DocEntry IN ".$ODLN; 
    $getOINV = MySQLSelectX($sqlOINV);//วันที่ส่งของ
    $getINV1 = MySQLSelectX($sqlINV1);//คนคืนบิล

    // echo $sqlOINV;
    // echo $sqlINV1;
    

    while ($ListOINV = mysqli_fetch_array($getOINV)){
        $SAP['ShipDate'][$ListOINV['BillType']][$ListOINV['BillEntry']] = $ListOINV['DateLogi'];
        $SAP['DocNum'][$ListOINV['BillType']][$ListOINV['BillEntry']] = $ListOINV['DocNum'];
        $SAP['Dis'][$ListOINV['BillType']][$ListOINV['BillEntry']] = " ";
        //$SAP['DocNum'][$ListOINV['BillType']][$ListOINV['BillEntry']] = "IV-6512200001";
    }
    while ($ListINV1 = mysqli_fetch_array($getINV1)){
        $SAP['NameRetun'][$ListINV1['BillType']][$ListINV1['BillEntry']] = $ListINV1['loginame'];
        if ($ListINV1['logi_ukey'] != ''){
            $SAP['CHKed'][$ListINV1['BillType']][$ListINV1['BillEntry']] = " checked ";
            $SAP['Dis'][$ListINV1['BillType']][$ListINV1['BillEntry']] = " disabled ";
        }
        
      
    }
    
    for($i = 1; $i <= $cx; $i++) {
        //$SAP[''][$DocType[$i]][$DocEntry[$i]]
        $output .= "<tr>";
        $output .= "<td class='text-center'>".$SAP['DocNum'][$DocType[$i]][$DocEntry[$i]]."</td>";
        $output .= "<td class='text-center'>" . date('d/m/Y', strtotime($SAP['DocDate'][$DocType[$i]][$DocEntry[$i]])) . "</td>";
        $output .= "<td>".$SAP['CardCode'][$DocType[$i]][$DocEntry[$i]] . " - " . conutf8($SAP['CardName'][$DocType[$i]][$DocEntry[$i]])."</td>";
        $output .= "<td>".conutf8($SAP['SlpName'][$DocType[$i]][$DocEntry[$i]])."</td>";
        $output .= "<td class='text-right'>".number_format($SAP['DocTotall'][$DocType[$i]][$DocEntry[$i]])."</td>";
        if ($SAP['ShipDate'][$DocType[$i]][$DocEntry[$i]] != NULL){
            $output .= "<td class='text-center'><input type='date' name='' id='' value='".date('Y-m-d',strtotime($SAP['ShipDate'][$DocType[$i]][$DocEntry[$i]])) . "' class='dateinput2 form-control' style='width:100px; text-align:center;'></td>";
        }else{
            $output .= "<td class='text-center'></td>";

        }
        $output .= "<td class='text-center' width='1'><input type='checkbox' onclick=\"AddName('".$DocType[$i]."','".$DocEntry[$i]."')\" name='' id='CHK_".$DocType[$i]."_".$DocEntry[$i]."'  class='' ".$SAP['CHKed'][$DocType[$i]][$DocEntry[$i]]." ".$SAP['Dis'][$DocType[$i]][$DocEntry[$i]]."></td>";
        $output .= "<td ><span id='Name_".$DocType[$i]."_".$DocEntry[$i]."'>".$SAP['NameRetun'][$DocType[$i]][$DocEntry[$i]]."</td>";

    }
}
if ($_GET['a'] == 'emp') {
    $sql1 = "SELECT LogiID,loginame,logilastname,loginickname,logiplate FROM logistic WHERE logiID = " . $_POST['ukey'];
    $LogiResult = MySQLSelect($sql1);
    $output = $LogiResult['loginame'] . " " . $LogiResult['logilastname'] . " (" . $LogiResult['loginickname'] . ") " . $LogiResult['logiplate'];
    //$output = "waiwai";

}
if ($_GET['a'] == 'addchk') {
    if ($_POST['CHKPoint'] == "0"){
        $output = "";    
    }else{
        $sql1 = "SELECT loginame,loginickname FROM logistic WHERE logiID = '".$_POST['chkID']."'";
        //echo $sql1;
        $LogiName = MySQLSelect($sql1);
        $output = $LogiName['loginame']." (".$LogiName['loginickname'].")";
        
    }
    $sql1 = "SELECT DISTINCT DATE(OutTime) AS LoadDate 
             FROM logi_detail 
             WHERE BillEntry = '".$_POST['DocEntry']."' AND BillType = '".$_POST['DocType']."' ORDER BY OutTime DESC";
    $DocEntry= $_POST['DocEntry'];
    $LogiDate = MySQLSelect($sql1);
    switch ($_POST['DocType']){
        case 'OINV' :
             $sql1 = "SELECT T0.DocDate 
                      FROM OINV T0
                      WHERE T0.DocEntry = ".$DocEntry;
            $SapQRY = SAPSelect($sql1);
            $SapDate = odbc_fetch_array($SapQRY);
            $sql1 = "SELECT DocEntry FROM billsr WHERE DocEntry = '".$_POST['DocEntry']."'";
            if (CHKRowDB($sql1) == 0){
                $sql1 = "INSERT INTO billsr SET DocEntry='".$_POST['DocEntry']."',DocDate = '".date("Y-m-d",strtotime($SapDate['DocDate']))."',logi_Date=NOW(),logi_ukey='".$_POST['chkID']."',logi_App='".$_POST['CHKPoint']."',Load_Date = '".$LogiDate['LoadDate']."'";
                MySQLInsert($sql1);
            }else{
                $sql1 = "UPDATE billsr SET logi_Date=NOW(),logi_ukey='".$_POST['chkID']."',logi_App='".$_POST['CHKPoint']."',Load_Date = '".$LogiDate['LoadDate']."' WHERE DocEntry = '".$_POST['DocEntry']."'";
                MySQLUpdate($sql1);
            }
            break;
        case 'ODLN' :
            $sql1 = "SELECT T0.DocDate 
                     FROM ODLN T0
                     WHERE T0.DocEntry = ".$DocEntry;
            $SapQRY = SAPSelect($sql1);
            $SapDate = odbc_fetch_array($SapQRY);
            $sql1 = "SELECT DocEntry FROM billpa WHERE DocEntry = '".$_POST['DocEntry']."'";
            if (CHKRowDB($sql1) == 0){
                $sql1 = "INSERT INTO billpa SET DocEntry='".$_POST['DocEntry']."',DocDate = '".date("Y-m-d",strtotime($SapDate['DocDate']))."',logi_Date=NOW(),logi_ukey='".$_POST['chkID']."',logi_App='".$_POST['CHKPoint']."',Load_Date = '".$LogiDate['LoadDate']."'";
                MySQLInsert($sql1);
            }else{
                $sql1 = "UPDATE billpa SET logi_Date=NOW(),logi_ukey='".$_POST['chkID']."',logi_App='".$_POST['CHKPoint']."',Load_Date = '".$LogiDate['LoadDate']."' WHERE DocEntry = '".$_POST['DocEntry']."'";
                MySQLUpdate($sql1);
            }
            break;
            
    }
    $sql1 = "SELECT IDPick FROM pack_header WHERE BillEntry = '".$_POST['DocEntry']."' AND BillType = '".$_POST['DocType']."'";
    $Pick = MySQLSelect($sql1);
    $sql1 = "UPDATE picker_soheader SET StatusDoc = 13 WHERE ID = ".$Pick['IDPick'];
    MySQLUpdate($sql1);

}

if ($_GET['a'] == 'modal') {
    $sql1 = "SELECT LogiID,loginame,logilastname,loginickname,logiplate FROM logistic WHERE logiID = " . $_POST['ukey'];
    $LogiResult = MySQLSelect($sql1);
    $var = $LogiResult['loginame'] . " " . $LogiResult['logilastname'] . " (" . $LogiResult['loginickname'] . ") " . $LogiResult['logiplate'];


    $output =
        "<p>" . $var . "</p><br>
    <table class=" . " table table-striped" . ">
   <thead>
          <tr>
        <th scope=" . "col" . "></th>
             <th scope=" . "col" . ">เลขที่บิล</th>
            <th scope=" . "col" . ">ร้านค้า</th>
            <th scope=" . "col" . ">ผู้ขาย</th>
         <th scope=" . "col" . ">ต้นทาง</th>
             <th scope=" . "col" . ">จำนวนเงิน</th>
             <th scope=" . "col" . ">ชื่อขนส่ง</th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <th scope=" . "row" . ">1</th>
             <td>Mark</td>
            <td>Otto</td>
             <td>@mdo</td>
           </tr>
         </tbody>
      </table>";
}

$arrCol['output'] = $output;
array_push($resultArray, $arrCol);
echo json_encode($resultArray);
