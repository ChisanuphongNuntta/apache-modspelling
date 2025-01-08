<?php
require("../core/Main.core.php");
require("../../".MainPathKSY()."/core/config.core.php");
require("../../".MainPathKSY()."/core/connect.core.php");
require("../../".MainPathKSY()."/core/functions.core.php");
require("../../".MainPathKSY()."/core/coresap.php");
date_default_timezone_set('Asia/Bangkok');
session_start();
$getdata = new clear_db();
$connect = $getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
$getdata->my_sql_set_utf8();
$resultArray = array();
$arrCol = array();
$output = "";
$getdata->my_sql_update("allwhs","WhsCode = '".strtoupper($_POST['WhsCode'])."'","LocationRack = '".strtoupper($_POST['LocRack'])."'");
$getdata->my_sql_update("oitw","WhsCode = '".strtoupper($_POST['WhsCode'])."'","LocRack = '".strtoupper($_POST['LocRack'])."'");

$arrCol['output'] = "ย้ายคลังสินค้าชั้น ".strtoupper($_POST['LocRack'])." ไปยังคลัง ".strtoupper($_POST['WhsCode'])." เรียบร้อยแล้ว";

array_push($resultArray,$arrCol);
echo json_encode($resultArray);


