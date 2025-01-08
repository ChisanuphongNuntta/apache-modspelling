<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../objects/events.php';

$database = new Database();
$db = $database->getConnection();
$ts_event = new EVENTS($db);

$ts_event->eid = $_POST['eid'];

$stmt = $ts_event->get_allEvents();
$num = $stmt->rowCount();

if($num > 0){
	
	//  $rst_arr = array();
	//  $rst_arr["records"] = array();
	 
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		
		$rst_item = array(
							"eid" => $eid,
							"egroup_code" => $egroup_code,
							"event_title" => $event_title,
							"event_province_code" => $event_province_code,
							"event_subdistrict_code" => $event_subdistrict_code,
							"event_district_code" => $event_district_code,
							"event_mooban" => $event_mooban,
							"event_moo" => $event_moo,
							"event_addr" => $event_addr,
							"fname_victim" => $fname_victim,
							"lname_victim" => $lname_victim,
							"phone_victim" => $phone_victim,
							"event_info" => $event_info,
							"event_date" => $event_date,
							"status_code" => $status_code,
							"create_by" => $create_by,
							"receive_by" => $receive_by,
							"receive_code" => $receive_code,
							"doing_by" => $doing_by,
							"doing_code" => $doing_code,
							"finish_by" => $finish_by,
							"finish_code" => $finish_code,
							"create_date" => $create_date,
							"last_update" => $last_update
						  );
		//   array_push($rst_arr, $rst_item);
	}//end while
	http_response_code(200);
	echo json_encode($rst_item);
}//end if check num > 0

 //////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////
 
 
 

?>