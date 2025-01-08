<?php
include('config.core.php');
include('functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();

$arrCol['ThanosStatus'] = "Y";
$arrCol['ThanosTxt'] = "SUCCESS";
if(!isset($_POST['pid'])) {
    $DocNum = $_POST['DocNum'];
    $SapSQL =
        "SELECT TOP 1
            T0.DocEntry, (T1.BeginStr+CAST(T0.DocNum AS VARCHAR)) AS 'DocNum',
            CASE WHEN UPPER(T0.Comments) LIKE 'QQ%' OR UPPER(T0.Comments) LIKE '*QQ%' THEN 'Y' ELSE 'N' END AS 'QQst',
            T0.DocDate, T0.CreateDate AS 'OriDate', T0.DocTime AS 'OriTime', T0.DocDueDate,
            T0.CardCode, T0.CardName, CASE WHEN T0.DocTime > 1300 THEN 'PM' ELSE 'AM' END AS 'TimeType',
            (SELECT COUNT(P1.DocEntry) FROM RDR1 P1 WHERE P1.DocEntry = T0.DocEntry) AS 'ItemCount',
            T2.U_Dim1 AS 'TeamCode', T0.SlpCode
        FROM ORDR T0
        LEFT JOIN NNM1 T1 ON T0.Series = T1.Series
        LEFT JOIN OSLP T2 ON T0.SlpCode = T2.SlpCode
        WHERE (T1.BeginStr+CAST(T0.DocNum AS VARCHAR)) = '$DocNum'";
    $Rows = ChkRowSAP($SapSQL);
    if($Rows == 0) {
        $arrCol['ThanosStatus'] = "N";
        $arrCol['ThanosTxt']    = "ERR::NORESULT";
    } else {
        $SapQRY = SAPSelect($SapSQL);
        $SapRST = odbc_fetch_array($SapQRY);

        $SODocEntry = $SapRST['DocEntry'];
        $PickerSQL = "SELECT T0.ID FROM picker_soheader T0 WHERE T0.SODocEntry = '$SODocEntry' AND T0.DocType = 'ORDR'";
        $Rows = ChkRowDB($PickerSQL);
        if($Rows > 0) {
            $arrCol['ThanosStatus'] = "N";
            $arrCol['ThanosTxt']    = "ERR::DUPLICATE";
        } else {
            $InsertSQL =
                "INSERT INTO picker_soheader SET
                    SODocEntry = '$SODocEntry',
                    DocNum = '".$SapRST['DocNum']."',
                    QQst = '".$SapRST['QQst']."',
                    DocType = 'ORDR',
                    DocDate = '".$SapRST['DocDate']."',
                    OriDate = '".$SapRST['OriDate']."',
                    OriTime = '".$SapRST['OriTime']."',
                    DocDueDate = '".$SapRST['DocDueDate']."',
                    CardCode = '".$SapRST['CardCode']."',
                    CardName = '".conutf8($SapRST['CardName'])."',
                    DateCreate = NOW(),
                    TimePick = '".$SapRST['TimeType']."',
                    ItemCount = '".$SapRST['ItemCount']."',
                    TeamCode = '".$SapRST['TeamCode']."',
                    SlpCode = '".$SapRST['SlpCode']."',
                    StatusDoc = 2
                ";
            // echo $InsertSQL;
            $ID = MySQLInsert($InsertSQL);
        }
    }
    
} else {
    $ID = $_POST['pid'];
}

if($arrCol['ThanosStatus'] == "Y") {
    $SQLToday = date("Y-m-d");
    $Date3 =  date("Y-m-d",strtotime("+3 days",strtotime($SQLToday)));
    $sql1 = "SELECT T0.ID,T0.TeamCode,
                    CASE WHEN T0.QQst = 'Y' THEN 'A0'
                        WHEN (T0.OriDate <= '".LastWorkDate($SQLToday)."' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A1'
                        WHEN (T0.OriDate = '".$SQLToday."'  AND  T0.OriTime < '1300' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A3'
                        WHEN (T0.TeamCode LIKE 'MT%' AND T0.DocDueDate <= '".$Date3."')  THEN 'A4'
                        WHEN (T0.OriDate = '".$SQLToday."'  AND  T0.OriTime > '1300' AND T0.TeamCode NOT LIKE 'MT%') THEN 'B1'
                        ELSE 'B2' END AS PickDay
            FROM picker_soheader T0
            WHERE T0.ID = ".$ID;
    $noTime = 1;
    $PickType = MySQLSelect($sql1);
    require("addPicker.php");
}
array_push($resultArray,$arrCol);
echo json_encode($resultArray);





?>