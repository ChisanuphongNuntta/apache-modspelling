<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/k_main.php';

$database = new Database();
$db = $database->getConnection();
$ts_event = new K_main($db);

// $ts_event->eid = $_POST['eid'];

$stmt = $ts_event->get_all_k_main();
$num = $stmt->rowCount();

if($num > 0){
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		
		$rst_item[] = array(
							"id" => $kid,
							"name" => $group_keyword,
							"count" => $count_in_gk,
							"remark" => $remark,
							"tagColor" => $tag_color,
							"date_create" => $date_create
						  );
		//   array_push($rst_arr, $rst_item);
	}//end while
	http_response_code(200);
	echo json_encode($rst_item);
}//end if check num > 0

 //////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////
 
 
 

?>