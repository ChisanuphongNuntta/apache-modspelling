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


if ($_POST['fun'] == 1 ){
    $getdata->my_sql_insert("oitw","ItemCode = '".$_POST['LocRack']."',WhsCode = 'KSM',LocRack='',OnHand = 0 ");
}else{
    $output = "B";
}
/*
$mysql = "SELECT ItemCode FROM oitw WHERE OnHand = 0 ORDER BY ID DESC";
$getRack = $getdata->MySQL_SelectX($mysql);
while ($FreeRack = mysql_fetch_object($getRack)){
    */
    $output .= " <tr>";
    $output .= "    <td>".$_POST['LocRack']."</td>";
    $output .= " </tr>";
//}

$arrCol['output'] = $output;

array_push($resultArray,$arrCol);
echo json_encode($resultArray);


