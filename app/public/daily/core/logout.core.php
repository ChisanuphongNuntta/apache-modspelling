<?php
	session_start();
	require("config.core.php");
	require("connect.core.php");
	require("logs.core.php");
	require("functions.core.php");

	date_default_timezone_set("Asia/Bangkok");
	define('LINE_API',"https://notify-api.line.me/api/notify");
	$getdata = new clear_db();
	$connect = $getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getuser = $getdata->my_sql_select(null,"user","username='".$_SESSION['uname']."'");
	$userlog = mysql_fetch_object($getuser);

	$tokenToAdmin = "okPm42wUsply17yZAE8iEHLndSrzjWGPLS1iAQqlTxq"; // ตัวทดสอบระบบ
	//$token = "DfwW6r1m1AU20Aaz5C6y1KUC2vOIEk5ZraP4gsrCxm2"; // ตัวจริง
	$mm1ToAdmin = "\n ผู้ออกจากระบบ  `SERVER-I` : ";
	$mm2ToAdmin =  "`".$userlog->name." [".$userlog->nickname."] `\n";
	$mm3ToAdmin = "วันที่ : ".date("d-M-Y เวลา H:i");
	
	$strToAdmin = $mm1ToAdmin.$mm2ToAdmin.$mm3ToAdmin;
	$resToAdmin = notify_messageToAdmin($strToAdmin,$tokenToAdmin);
	//$res2 = notify_message("test2",$token);
	
	function notify_messageToAdmin($message,$token){
	 $queryData = array('message' => $message);
	 $queryData = http_build_query($queryData,'','&');
	 $headerOptions = array( 
			 'http'=>array(
				'method'=>'POST',
				'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
						  ."Authorization: Bearer ".$token."\r\n"
						  ."Content-Length: ".strlen($queryData)."\r\n",
				'content' => $queryData
			 ),
	 );
	 $context = stream_context_create($headerOptions);
	 $result = file_get_contents(LINE_API,FALSE,$context);
	 $res = json_decode($result);
	 //$res2 = json_decode($result);
	
	 return ($resToAdmin);
	 
	}
	$getdata->my_sql_close();
	insertLogs($_SESSION['uname']." ออกจากระบบ.",$_SERVER['REMOTE_ADDR'],$_SESSION['ukey']);
	unset( $_SESSION['uname']);
	unset( $_SESSION['thumb']);
	unset( $_SESSION['uid']);
	unset( $_SESSION['uclass']);
	unset($_SESSION['ukey']);

	
	
	echo"<script>window.location.href='../';</script>";
?>
