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
    $DocEntry = $_GET['x'];

    $SQLToday = date("Y-m-d");
    $Date3 =  date("Y-m-d",strtotime("+3 days",strtotime($SQLToday)));

    $GetDocSQL = 
        "SELECT
            T0.DocEntry, T0.DocNum, CONCAT('OWA',T0.TypeOrder) AS 'DocType', CASE WHEN UPPER(LEFT(T0.Remark,2)) IN ('QQ','*QQ') THEN 'Y' ELSE 'N' END AS 'QQst',
            T0.DateCreate, DATE(T0.TimeContrac) AS 'DocDueDate',
            CASE WHEN T0.CusCode = '' THEN NULL ELSE T0.CusCode END AS 'CardCode', T0.CusName AS 'CardName', 
            (SELECT COUNT(X0.ID) FROM WAS1 X0 WHERE X0.DocEntry = T0.DocEntry) AS 'ItemCount',
            CASE
                WHEN T2.DeptCode = 'DP005' THEN 'TT2'
                WHEN T2.DeptCode = 'DP006' THEN 'MT1'
                WHEN T2.DeptCode = 'DP007' THEN 'MT2'
                WHEN T2.DeptCode = 'DP008' THEN 'OUL'
                WHEN T2.DeptCode = 'DP003' AND T1.LvCode IN ('LV104','LV105','LV106') THEN 'ONL'
            ELSE 'KBI' END AS 'TeamCode'
        FROM OWAS T0
        LEFT JOIN users T1 ON T0.UserCreate = T1.uKey
        LEFT JOIN positions T2 ON T1.LvCode = T2.LvCode
        WHERE T0.DocEntry = $DocEntry LIMIT 1";
    $Rows = ChkRowDB($GetDocSQL);
    if($Rows > 0) {
        $GetDocRST = MySQLSelect($GetDocSQL);
        $xCount = 1;
        $time = date("Hm",strtotime($GetDocRST['DateCreate']));
        if($time > 1300) {
            $TimeType = 'PM';
        } else {
            $TimeType = 'AM';
        }

        $SODocEntry = $GetDocRST['DocEntry'];
        $DocNum     = $GetDocRST['DocNum'];
        $QQst       = $GetDocRST['QQst'];
        $DocType    = $GetDocRST['DocType'];
        $DocDate    = date("Y-m-d",strtotime($GetDocRST['DateCreate']));
        $OriDate    = date("Y-m-d",strtotime($GetDocRST['DateCreate']));
        $OriTime    = $time;
        $DocDueDate = date("Y-m-d",strtotime($GetDocRST['DocDueDate']));
        $CardCode   = $GetDocRST['CardCode'];
        $CardName   = $GetDocRST['CardName'];
        $TimeType   = $TimeType;
        $ItemCount  = $GetDocRST['ItemCount'];
        $TeamCode   = $GetDocRST['TeamCode'];

        $InsertSQL = 
            "INSERT INTO picker_soheader SET
                SODocEntry = $SODocEntry,
                DocNum = '$DocNum',
                QQst = '$QQst',
                DocType = '$DocType',
                DocDate = '$DocDate',
                OriDate = '$OriDate',
                OriTime = '$OriTime',
                DocDueDate = '$DocDueDate',
                CardCode = '$CardCode',
                CardName = '$CardName',
                DateCreate = NOW(),
                TimeType = '$TimeType',
                ItemCount = '$ItemCount',
                TeamCode = '$TeamCode',
                SlpCode = NULL,
                StatusDoc = '2'
                ";

        $ID = MySQLInsert($InsertSQL);
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

        require("AddPicker.php");
        $arrCol['Status'] = 'B';
        $arrCol['errMsg'] = "บันทึกสำเร็จ เลขที่ใบฝากงาน:".$DocNum;

    }
}

array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>