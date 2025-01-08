<?php
include('../../../core/config.core.php');
include('../../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();
// if($_SESSION['UserName']==NULL ){
// 	echo '<script>window.location="../../../"</script>';
// }

if($_GET['a'] == 'InserPackerOnline') {
    // $Ukey_Login   = $_SESSION['ukey']; // UserType = 1
    $Ukey_Login   = 'c2d307ec3cc65e02029dd99474441e88'; // UserType = 1
    $Ukey_Checker = $_POST['Checker']; // UserType = 0
    $Table = $_POST['tmpTable'];

    // Insert Ukey_Login
    $Insert_Login = "INSERT packer_online  SET DateOnline = NOW(), ukeyChecker = '$Ukey_Login', TableChecker = $Table, UserType = 1";
    // MySQLInsert($Insert_Login);

    // Insert Ukey_Checker
    $Insert_Checker = "INSERT packer_online  SET DateOnline = NOW(), ukeyChecker = '$Ukey_Checker', TableChecker = $Table, UserType = 0";
    // MySQLInsert($Insert_Checker);
}

if($_GET['a'] == 'GetTable') {
    $SQL = "SELECT * FROM checkertable WHERE IPAddress = '".$_SERVER['REMOTE_ADDR']."'";
    $RST = MySQLSelect($SQL);
    if(isset($RST['TableID'])) {
        $Table = $RST['TableID'];
    }else{
        $Table = 6;
    }
    
    $arrCol['Table'] = $Table;
}

if($_GET['a'] == 'GetChecker') {
    $SQL = "SELECT uKey, CONCAT(uName, ' ', uLastName, ' (', uNickName, ')') AS FullName FROM users WHERE LvCode IN ('LV075','LV073','LV072') AND UserStatus = 'A'";
    $QRY = MySQLSelectX($SQL);
    $Option = "<option value='' selected disabled>เลือกพนักงาน Checker</option>";
    while($RST = mysqli_fetch_array($QRY)) {
        $Option .= "<option value='".$RST['uKey']."'>".$RST['FullName']."</option>";
    }
    $arrCol['Option'] = $Option;
}

if($_GET['a'] == 'GetListSO') {
    $GetDate = date('Y-m-d');
    $YesDay = date('Y-m-d', strtotime($GetDate . ' -1 day'));
    $DayPlus3 = date('Y-m-d', strtotime($GetDate . ' +3 day'));
    $WeekName = array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
    $WeekDate = date("w",strtotime($YesDay));
    
    // $Chk_Holidat = "SELECT COUNT(*) FROM annual_holiday WHERE Holiday_date = '$YesDay'";
    // if(CHKRowDB($Chk_Holidat) > 0) {
    //     $Holiday = 'Y';
    // }else{
    //     if($WeekName[$WeekDate] == 'อาทิตย์') {
    //         $Holiday = 'Y';
    //     }else{
    //         $Holiday = 'N';
    //     }
    // }

    // if($Holiday == 'N') {

    // }

    $GET_TABLE = MySQLSelect("SELECT * FROM checkertable WHERE IPAddress = '".$_SERVER['REMOTE_ADDR']."'");
    if(isset($GET_TABLE['TableID'])) {
        $Table = $GET_TABLE['TableID'];
    }else{
        // $Table = 6;
        $Table = 4;
    }

    $SQL1 = "
    SELECT SODocEntry, TablePacking, PackDate, TimePack, StatusDoc 
    FROM picker_soheader 
    WHERE StatusDoc > 0 AND StatusDoc < 11 AND TablePacking = $Table AND DocType = 'ORDR' 
    ORDER BY SODocEntry DESC";
    $QRY1 = MySQLSelectX($SQL1);
    $ListDocEntry = ""; $r = 0;
    while($RST1 = mysqli_fetch_array($QRY1)) {
        $r++;
        $ListDocEntry .= $RST1['SODocEntry'];
        if($r < CHKRowDB($SQL1)) {
            $ListDocEntry .= ",";
        }
    }
    
    if($ListDocEntry != "") {
        $SQL2 = "
        SELECT X1.* 
        FROM (
            SELECT DISTINCT T5.[U_Dim1], 'OINV' AS 'Type', 
                T0.[DocEntry] AS 'SOEntry', T1.[BeginStr] AS 'SOBeginStr', T0.[DocNum] AS 'SODocNum',
                T3.[DocEntry] AS 'IVEntry', CASE WHEN T4.[BeginStr] != NULL THEN T4.[BeginStr] ELSE 'IV-' END  AS 'IVBeginStr', 
                T3.[DocNum] AS 'IVDocNum', T3.[NumAtCard] AS 'IVNumAtCard',T0.DocDueDate,
                CASE 
                    WHEN(T0.CreateDate < '$YesDay' AND  T5.U_Dim1 NOT LIKE 'MT%') THEN 'A0'
                    WHEN(T0.CreateDate = '$YesDay' AND T0.DocTime <= 1300 AND T5.U_Dim1 NOT LIKE 'MT%') THEN 'A1' 
                    WHEN(T0.CreateDate = '$YesDay' AND T0.DocTime > 1300 AND T5.U_Dim1 NOT LIKE 'MT%') THEN 'A2' 
                    WHEN(T0.CreateDate = '$GetDate'  AND  T0.DocTime <= 1300 AND T5.U_Dim1 NOT LIKE 'MT%') THEN 'A3' 
                    WHEN(T5.U_Dim1 LIKE 'MT%' AND T0.DocDueDate < '$DayPlus3')  THEN 'A4' 
                    WHEN(T0.CreateDate = '$GetDate'  AND  T0.DocTime > 1300 AND T5.U_Dim1 NOT LIKE 'MT%') THEN 'B1'
                ELSE 'B2' END AS PickDAY 
            FROM ORDR T0 
            LEFT JOIN NNM1 T1 ON T0.[Series] = T1.[Series] 
            LEFT JOIN RDR1 T2 ON T0.[DocEntry] = T2.[DocEntry] 
            LEFT JOIN OINV T3 ON T2.[TrgetEntry] = T3.[DocEntry] 
            LEFT JOIN NNM1 T4 ON T3.[Series] = T4.[Series] 
            LEFT JOIN OSLP T5 ON T0.[SlpCode] = T5.[SlpCode] 
            WHERE T0.[DocEntry] IN ($ListDocEntry) AND T2.[TargetType] = 13
            UNION ALL
            SELECT DISTINCT T5.[U_Dim1], 'ODLN' AS 'Type', 
                T0.[DocEntry] AS 'SOEntry', T1.[BeginStr] AS 'SOBeginStr', T0.[DocNum] AS 'SODocNum', 
                T3.[DocEntry] AS 'IVEntry', T4.[BeginStr] AS 'IVBeginStr', T3.[DocNum] AS 'IVDocNum', T3.[NumAtCard] AS 'IVNumAtCard',T0.DocDueDate, 
                CASE 
                    WHEN(T0.CreateDate < '$YesDay' AND  T5.U_Dim1 NOT LIKE 'MT%') THEN 'A0' 
                    WHEN(T0.CreateDate = '$YesDay' AND T0.DocTime <= 1300 AND T5.U_Dim1 NOT LIKE 'MT%') THEN 'A1' 
                    WHEN(T0.CreateDate = '$YesDay' AND T0.DocTime > 1300 AND T5.U_Dim1 NOT LIKE 'MT%') THEN 'A2' 
                    WHEN(T0.CreateDate = '$GetDate'  AND  T0.DocTime <= 1300 AND T5.U_Dim1 NOT LIKE 'MT%') THEN 'A3' 
                    WHEN(T5.U_Dim1 LIKE 'MT%' AND T0.DocDueDate < '$DayPlus3')  THEN 'A4' 
                    WHEN(T0.CreateDate = '$GetDate'  AND  T0.DocTime > 1300 AND T5.U_Dim1 NOT LIKE 'MT%') THEN 'B1' 
                ELSE 'B2' END AS PickDAY 
            FROM ORDR T0 
            LEFT JOIN NNM1 T1 ON T0.[Series] = T1.[Series] 
            LEFT JOIN RDR1 T2 ON T0.[DocEntry] = T2.[DocEntry] 
            LEFT JOIN ODLN T3 ON T2.[TrgetEntry] = T3.[DocEntry] 
            LEFT JOIN NNM1 T4 ON T3.[Series] = T4.[Series] 
            LEFT JOIN OSLP T5 ON T0.[SlpCode] = T5.[SlpCode] 
            WHERE T0.[DocEntry] IN ($ListDocEntry) AND T2.[TargetType] = 15 
        ) X1
        ORDER BY 
            CASE 
                WHEN X1.PickDAY = 'A0' THEN 1  
                WHEN X1.PickDAY = 'A1' THEN 2  
                WHEN X1.PickDAY = 'A2' THEN 3  
                WHEN X1.PickDAY = 'A4' THEN 4  
                WHEN X1.PickDAY = 'A3' THEN 5  
                WHEN X1.PickDAY = 'B1' THEN 6  
                WHEN X1.PickDAY = 'B2' THEN 7  
            END";
        $QRY2 = SAPSelect($SQL2);
        $ListSO = "";
        while($RST2 = odbc_fetch_array($QRY2)) {
            // <a href='javascript:void(0);' class='list-so' onclick='CallSO(\"".$RST2['SOBeginStr'].$RST2['SODocNum']."\",\"".$RST2['Type']."\",".$RST2['SOEntry'].",".$RST2['IVEntry'].");'>
            $ListSO .= "
            <div class='d-flex'>
                <a href='javascript:void(0);' class='list-so' onclick='CallSO(\"".$RST2['SOBeginStr'].$RST2['SODocNum']."\");'>
                    ".$RST2['SOBeginStr'].$RST2['SODocNum']." [".$RST2['U_Dim1']."]
                </a>
            </div>";
        }
        $arrCol['ListSO'] = $ListSO;
    }
    
}

if($_GET['a'] == 'CallSO') {
    $SO = $_POST['SO'];
    $DocHead = substr($SO,0,2);
    $DocNum = substr($SO,3);
    
    switch ($DocHead) {
        case 'PA':
        case 'PB': 
            $TableSearch = "ODLN";
            $SQL1 = "SELECT DocEntry FROM ODLN WHERE DocNum = '$DocNum'"; 
            break;
        case 'IV':
        case 'IC': 
            $TableSearch = "OINV";
            $SQL1 = "SELECT DocEntry FROM OINV WHERE DocNum = '$DocNum'"; 
            break;
        case "SA":
        case "SB": 
            $TableSearch = "ODLN";
            $SQL1 = "
            SELECT DISTINCT T1.[TrgetEntry] AS DocEntry 
            FROM ORDR T0 
            LEFT JOIN RDR1 T1 ON T0.[DocEntry] = T1.[DocEntry] 
            WHERE T1.[TrgetEntry] IS NOT NULL AND T0.[DocNum] = '$DocNum'";
            break;
        case "SN":
        case "SO": 
            $TableSearch = "ORDR";
            $SQL1 = "
            SELECT DISTINCT T2.DocEntry 
            FROM ORDR T0 
            JOIN RDR1 T1 ON T0.DocEntry = T1.DocEntry 
            JOIN INV1 T2 ON T1.DocEntry = T2.BaseEntry 
            WHERE T0.DocNum = '$DocNum' AND T2.BaseType = 17"; 
            break;
        case "WO":
            $TableSearch = "OWAS";
            $SQL1 = "SELECT * FROM owas WHERE DocNum = '$DocNum'"; 
            break;
    }

    $ChkDocEntry = "N";
    if($TableSearch == "OWAS") {
        $RST1 = MySQLSelect($SQL1);
        if(isset($RST1['DocEntry'])) {
            $ChkDocEntry = "Y";
            $DocEntry = $RST1['DocEntry'];
            $OWASDocNum = $RST1['DOCNum'];
            $DocType = "OWA".$RST1['TypeOrder'];
        }
    }else{
        $QRY1 = SAPSelect($SQL1);
        $RST1 = odbc_fetch_array($QRY1);
        if(isset($RST1['DocEntry'])) {
            $ChkDocEntry = "Y";
            $DocEntry = $RST1['DocEntry'];
            if($TableSearch == "OINV" || $TableSearch == "ORDR") {
                $DocType = "OINV";
            }else{
                $DocType = $TableSearch;
            }
        }
    }

    $CRC = "N";
    if($DocType == "OINV") {
        $SQL3 = "
        SELECT DISTINCT 'OINV' AS 'Type', T0.[SlpCode], T0.[DocEntry], 
            CASE WHEN T1.[BeginStr] = NULL THEN 'IV-' ELSE T1.[BeginStr] END AS 'BeginStr', 
            T0.[DocNum], T0.[NumAtCard], T2.[U_Name],T0.[Address2],T0.[CardCode],T0.[CardName],T4.[Comments],T5.[U_Retails]
        FROM OINV T0 
        LEFT JOIN NNM1 T1 ON T0.[Series] = T1.[Series] 
        LEFT JOIN [dbo].[@SHIPPINGTYPE] T2 ON T0.[U_ShippingType] = T2.[Code] 
        LEFT JOIN INV1 T3 ON T3.DocEntry = T0.DocEntry 
        LEFT JOIN ORDR T4 ON T3.BaseEntry = T4.DocEntry 
        LEFT JOIN OCRD T5 ON T0.[CardCode] = T5.[CardCode]
        WHERE T0.[DocEntry] IN ('$DocEntry')";
        $QRY3 = SAPSelect($SQL3);
        $RST3 = odbc_fetch_array($QRY3);
        $arrCol['DocNum'] = $RST3['NumAtCard'];
        $arrCol['CardName'] = $RST3['CardCode']." | ".conutf8($RST3['CardName']);
        $arrCol['Address'] = conutf8($RST3['Address2']);
        $arrCol['Uname'] = conutf8($RST3['U_Name']);
        $arrCol['PackDate'] = date("d/m/Y");
        $arrCol['Remark'] = conutf8($RST3['Comments']);

        if($RST3['U_Retails'] == "CRC") {
            $CRC = "Y";
            $arrCol['CardName'] = "";
            $SQL_CRC = "
            SELECT T0.[CardCode], CASE WHEN T0.[U_ExternalCust] IS NOT NULL THEN  T0.[U_ExternalCust] + ' - ' + T0.[CardName] ELSE T0.[CardName] END AS 'CardName' 
            FROM OCRD T0 
            LEFT JOIN CRD1 T1 ON T0.[CardCode] = T1.[CardCode] AND T1.[AdresType] = 'S' 
            WHERE T0.[U_Retails] = 'CRC'";
            $QRY_CRC = SAPSelect($SQL_CRC);
            $option_crc = "<option value='' selected disabled>เลือกสาขา</option>";
            while($RST_CRC = odbc_fetch_array($QRY_CRC)) {
                $option_crc .= "<option value='".$RST_CRC['CardCode']."'>".conutf8($RST_CRC['CardName'])."</option>";
            }
            $arrCol['option_crc'] = $option_crc;
        }
    }else if($DocType == "OWAS" || $DocType == "OWAR" || $DocType == "OWAB"){

    }else{

    }

    $arrCol['CRC'] = $CRC;
    $Chk1 = "SELECT COUNT(*) FROM pack_header WHERE BillEntry = '$DocEntry' AND BillType = '$DocType'";
    if(CHKRowDB($Chk1) != 0) { // พบข้อมูล
        if($CRC == "Y") {
            $SQL4 = "SELECT ID, CardCode FROM pack_header WHERE BillEntry = '$DocEntry' AND BillType = '$DocType'";
            $RST4 = MySQLSelect($SQL4);
            if ($RST4["CardCode"] != "M-00208") {
                $value_crc = $RST4["CardCode"];
                $arrCol['value_crc'] = $value_crc;
            }
        }

    }
}


array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>