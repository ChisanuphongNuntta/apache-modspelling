<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/k_main.php';

$database = new Database();
$db = $database->getConnection();
$ts_k_main = new K_main($db);

$ts_k_main->group_keyword = $_POST['name'];
// $ts_k_main->count_in_gk = $_POST['count'];
$ts_k_main->remark = $_POST['remark'];
$ts_k_main->tag_color = $_POST['tagColor'];


// echo print_r($ts_k_main);
$lastId = $ts_k_main->insert_k_main();
// echo "nice: ". $lastId;

 //////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////
 
 
 

?>