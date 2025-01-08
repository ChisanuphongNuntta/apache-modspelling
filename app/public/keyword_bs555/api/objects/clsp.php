<?php

class CLSP{
	
private $conn;
	
private $tbl_event_status = "m_event_status";
private $tbl_event_type = "m_event_type";
private $tbl_event = "clsp_event_report";

private $evt_id;
private $evt_desc;
private $evt_status;

private $etype_id;
private $etype_desc;
private $etype_status;

private $last_update;

public function __construct($db){
	$this->conn = $db;
}//end constructor
//////////////////////////////////////////////////////////////////////////////////

function get_allEvents(){

	$sql = "SELECT cer.*, mes.evt_id, mes.evt_desc, mes.evt_status FROM ".$this->tbl_event." as cer
			INNER JOIN ".$this->tbl_event_status." as mes ON cer.status_code = mes.evt_id";
	$stmt = $this->conn->prepare($sql);
	$stmt->execute();
	
	return $stmt;	
}//end function get all Events
//////////////////////////////////////////////////////////////////////////////////

function get_allEvents_statusCode($status_code){

	$sql = "SELECT cer.*, mes.evt_id, mes.evt_desc, mes.evt_status FROM ".$this->tbl_event." as cer
			INNER JOIN ".$this->tbl_event_status." as mes ON cer.status_code = mes.evt_id
			WHERE cer.status_code = ". $status_code;
	$stmt = $this->conn->prepare($sql);
	$stmt->execute();
	
	return $stmt;	
}//end function get all Events status Code
//////////////////////////////////////////////////////////////////////////////////


function get_allEventStatus(){
	
	$sql = "SELECT evt_id,evt_desc,evt_status,last_update FROM ".$this->tbl_event_status." ";
	$stmt = $this->conn->prepare($sql);
	$stmt->execute();
	
	return $stmt;	
}//end function get all user type list
//////////////////////////////////////////////////////////////////////////////////

function get_allEventType(){
	
	$sql = "SELECT etype_id, etype_desc, etype_status, last_update FROM ".$this->tbl_event_type." ";
	$stmt = $this->conn->prepare($sql);
	$stmt->execute();
	
	return $stmt;	
}//end function get all user type list
//////////////////////////////////////////////////////////////////////////////////

//insert
function insert_event(
	$egroup_code,
	$event_title,
	$event_province_code,
	$event_subdistrict_code,
	$event_district_code,
	$event_mooban,
	$event_moo,
	$event_addr,
	$fname_victim,
	$lname_victim,
	$phone_victim,
	$event_info,
	$event_date,
	$status_code,
	$create_by,
	$receive_by,
	$create_date
){
	
	$sql = "INSERT INTO ".$this->tbl_event."
	(
	`egroup_code`,
	`event_title`,
	`event_province_code`,
	`event_subdistrict_code`,
	`event_district_code`,
	`event_mooban`,
	`event_moo`,
	`event_addr`,
	`fname_victim`,
	`lname_victim`,
	`phone_victim`,
	`event_info`,
	`event_date`,
	`status_code`,
	`create_by`,
	`receive_by`,
	`create_date`,
	)
	VALUES
	(
	:egroup_code,
	:event_title,
	:event_province_code,
	:event_subdistrict_code,
	:event_district_code,
	:event_mooban,
	:event_moo,
	:event_addr,
	:fname_victim,
	:lname_victim,
	:phone_victim,
	:event_info,
	:event_date,
	:status_code,
	:create_by,
	:receive_by,
	:create_date,
	)";
	$stmt = $this->conn->prepare($sql);
	$stmt->execute([
		'egroup_code' => $egroup_code,
		'event_title' => $event_title,
		'event_province_code' => $event_province_code,
		'event_subdistrict_code' => $event_subdistrict_code,
		'event_district_code' => $event_district_code,
		'event_mooban' => $event_mooban,
		'event_moo' => $event_moo,
		'event_addr' => $event_addr,
		'fname_victim' => $fname_victim,
		'lname_victim' => $lname_victim,
		'phone_victim' => $phone_victim,
		'event_info' => $event_info,
		'event_date' => $event_date,
		'status_code' => $status_code,
		'create_by' => $create_by,
		'receive_by' => $receive_by,
		'create_date' => $create_date
	]);
	
	return $stmt;	
}//end function get all user type list
//////////////////////////////////////////////////////////////////////////////////
	
}//end class
?>