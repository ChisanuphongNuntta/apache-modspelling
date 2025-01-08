<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/k_sub_main.php';

$database = new Database();
$db = $database->getConnection();
$ts_k_sub_main = new K_sub_main($db);

// $ts_k_sub_main->ksid = $_POST['name'];
$ts_k_sub_main->kid = $_POST['id'];
$ts_k_sub_main->keyword = $_POST['keyword0'];
// $ts_k_sub_main->tag_color = $_POST['tagColor'];


// echo print_r($ts_k_main);
$lastId = $ts_k_sub_main->insert_k_sub_main();
// echo "nice: ". $lastId;

 //////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////
 
 
 

?>