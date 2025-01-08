<?php
	session_start();
	require("config.core.php");
	require("functions.core.php");
	date_default_timezone_set("Asia/Bangkok");

	$sms1 = "\n ผู้ออกจากระบบ  `SERVER-MAIN [NEW]` : ";
	$sms1 .= "`".$_SESSION['uName']." [".$_SESSION['uNickName']."] `\n";
	$sms1 .= "วันที่ : ".date("d-M-Y เวลา H:i");
	MySQLInsert("INSERT INTO loglogin SET LogDate=NOW(),LogIP='".$_SERVER['REMOTE_ADDR']."',LogText='".$_SESSION['UserName']." ออกจากระบบ', UserLog = '".$_SESSION['ukey']."'");
	LineNoti('LogIN',$sms1);
	unset($_SESSION['UserName']);
	unset($_SESSION['uClass']);
	unset($_SESSION['ukey']);
	unset($_SESSION['LvCode']);
	unset($_SESSION['DeptCode']);
	unset($_SESSION['uName']);
	unset($_SESSION['uLastName']);
	unset($_SESSION['uNickName']);
	unset($_SESSION['EmpCode']);

	
	if(isset($_GET['type'])) {
		echo"<script type='text/javascript'>localStorage.clear(); sessionStorage.clear(); window.location.href='../ceo';</script>";
	} else {
		echo"<script type='text/javascript'>localStorage.clear(); sessionStorage.clear(); window.location.href='../';</script>";
	}
	
?>
