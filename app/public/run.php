<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');

$DocNumOld = 'SO-661201670'; // ใส่DocNum SO เก่า ที่นี้
$DocNumNew = 'SO-670100075'; // ใส่DocNum SO ใหม่ ที่นี้
$sql1 = "SELECT SODocEntry AS DocEntry FROM picker_soheader WHERE DocNum = '".$DocNumOld."' ";
$sql2 = "SELECT SODocEntry AS DocEntry FROM picker_soheader WHERE DocNum = '".$DocNumNew."' ";
$NewSO = MySQLSelect($sql2);
$OldSO = MySQLSelect($sql1);
$sql3 = "DELETE FROM picker_sodetail WHERE DocEntry = '".$NewSO['DocEntry']."'";
//MySQLDelete($sql3);
$sql4 = "SELECT * FROM picker_sodetail WHERE DocEntry = '".$OldSO['DocEntry']."'";
echo $sql4;
$getDetail = MySQLSelectX($sql4);	
$CHKList = 0;
$test = "waiwai";
while ($DetailList = mysqli_fetch_array($getDetail)){
    $sql5= "SELECT * FROM RDR1 WHERE DocEntry = ".$DetailList['DocEntry']." AND LineNum = ".$DetailList['VisOrder']." AND ItemCode = '".$DetailList['ItemCode']."' AND Quantity = ".$DetailList['Qty'];
    $getSAP = SAPSelect($sql5);
	$SAP = odbc_fetch_array($getSAP);
    if ($SAP['ItemCode'] ==  $DetailList['ItemCode'] AND  $SAP['LineNum'] == $DetailList['VisOrder'] AND $SAP['Quantity'] == $DetailList['Qty'] AND $CHKList == 0){
        $SQLint = "INSERT INTO picker_sodetail SET DocEntry = '".$DetailList['DocEntry']."',
                                                    DocType = '".$DetailList['DocType']."',
                                                    VisOrder = ".$DetailList['VisOrder'].",
                                                    ItemCode = '".$DetailList['ItemCode']."',
                                                    BarCode = '".$DetailList['BarCode']."',
                                                    ItemName = '".$DetailList['ItemName']."',
                                                    WhsCode = '".$DetailList['WhsCode']."',
                                                    Qty='".$DetailList['Qty']."',
                                                    OpenQty ='".$DetailList['OpenQty']."',
                                                    Remark = '".$DetailList['Remark']."',
                                                    Status ='".$DetailList['Status']."',
                                                    WaitOP='".$DetailList['WaitOP']."',
                                                    BomItem='".$DetailList['BomItem']."',
                                                    LastRead='".$DetailList['LastRead']."'";
        $test .=  $SQLint."<br>";
    }else{
        $sql3 = "DELETE FROM picker_sodetail WHERE DocEntry = '".$NewSO['DocEntry']."'";
        $CHKList = 1;
        $test = "ข้อมูลรายการไม่ตรงกัน";
    }
    
}
echo $test;







