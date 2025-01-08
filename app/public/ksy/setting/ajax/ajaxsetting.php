<?php
include('../../../core/config.core.php');
include('../../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();
$output = "";
if($_SESSION['UserName']==NULL ){
	echo '<script>window.location="../../../"</script>';
}

if($_GET['a'] == 'ClearB1P0') {
    $SQL = "DELETE FROM oitw WHERE LocRack LIKE '%B1-P0-00%'";
    MySQLDelete($SQL);
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>