<?php
date_default_timezone_set('Asia/Bangkok');

//fUNCTION Database
function MySQLSelect($SqlStatement){
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$query = mysqli_query($connectF,$SqlStatement);
	$row = mysqli_fetch_array($query);
	// mysqli_close($connectF);
	return $row;
}
function MySQLSelectX($SqlStatement){
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$query = mysqli_query($connectF,$SqlStatement);
	return $query;
}

function MySQLUpdate($SqlStatement){
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$result = mysqli_query($connectF,$SqlStatement);
	mysqli_close($connectF);
}

function MySQLInsert($SqlStatement){
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$query = mysqli_query($connectF,$SqlStatement);
	$LastID = mysqli_insert_id($connectF);
	mysqli_close($connectF);
	return $LastID;
}

function CHKRowDB($SqlStatement){
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$query = mysqli_query($connectF,$SqlStatement);
	$chk = mysqli_num_rows($query);
	mysqli_close($connectF);
	return $chk;
}

function MySQLDelete($SqlStatement){
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$query = mysqli_query($connectF,$SqlStatement);
	mysqli_close($connectF);
}


function SAPSelect($SqlStatement){
	$sap_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF8;SERVER=".SAP_HOST.";DATABASE=".SAP_NAME ,SAP_USERNAME, SAP_PASSWORD) or die ("Cannot Connect to SAP Database!");
	$sap_qury = odbc_exec($sap_conn, $SqlStatement);
	return $sap_qury;
}

function ChkRowSAP($SqlStatement) {
	$sap_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF8;SERVER=".SAP_HOST.";DATABASE=".SAP_NAME ,SAP_USERNAME, SAP_PASSWORD) or die ("Cannot Connect to SAP Database!");
	$sap_qury = odbc_exec($sap_conn, $SqlStatement);
	$row = odbc_num_rows($sap_qury);
	return $row;
}

function PITASelect($SqlStatement){
	$sap_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF8;SERVER=".PITA_HOST.";DATABASE=".PITA_NAME ,PITA_USERNAME, PITA_PASSWORD) or die ("Cannot Connect to SAP Database!");
	$sap_qury = odbc_exec($sap_conn, $SqlStatement);
	return $sap_qury;
}

function ChkRowPITA($SqlStatement) {
	$sap_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF8;SERVER=".SAP_HOST.";DATABASE=".PITA_NAME ,PITA_USERNAME, PITA_PASSWORD) or die ("Cannot Connect to SAP Database!");
	$sap_qury = odbc_exec($sap_conn, $SqlStatement);
	$row = odbc_num_rows($sap_qury);
	return $row;
}

function conSAP8($SqlStatement){
	$Old_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF8;SERVER=".OLD_HOST.";DATABASE=".OLD_NAME ,OLD_USERNAME, OLD_PASSWORD) or die ("Cannot Connect to OLD Database!");
	$Old_qury = odbc_exec($Old_conn, $SqlStatement);
	return $Old_qury;
}

function ChkRowSAP8($SqlStatement) {
	$Old_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF8;SERVER=".OLD_HOST.";DATABASE=".OLD_NAME ,OLD_USERNAME, OLD_PASSWORD) or die ("Cannot Connect to OLD Database!");
	$Old_qury = odbc_exec($Old_conn, $SqlStatement);
	$row = odbc_num_rows($Old_qury);
	return $row;
}

function HRMISelect($SqlStatement){
	$hrmi_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF8;SERVER=".HRMI_HOST.";DATABASE=".HRMI_NAME ,HRMI_USERNAME, HRMI_PASSWORD) or die ("Cannot Connect to HRMI Database!");
	$hrmi_qury = odbc_exec($hrmi_conn, $SqlStatement);
	return $hrmi_qury;

}

function ChkRowHRMI($SqlStatement) {
	$hrmi_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF8;SERVER=".HRMI_HOST.";DATABASE=".HRMI_NAME ,HRMI_USERNAME, HRMI_PASSWORD) or die ("Cannot Connect to HRMI Database!");
	$hrmi_qury = odbc_exec($hrmi_conn, $SqlStatement);
	$row = odbc_num_rows($hrmi_qury);
	return $row;
}

function SATeamName($mainteam) {
	switch($mainteam) {
		case "MT1": $DeptCode = "DP006"; break;
		case "MT2": $DeptCode = "DP007"; break;
		case "TT2": $DeptCode = "DP005"; break;
		case "OUL": $DeptCode = "DP008"; break;
		case "TT1": $DeptCode = "TT1"; break;
		case "ONL": $DeptCode = "ONL"; break;
		case "EXP": $DeptCode = "EXP"; break;
		case "MKT": $DeptCode = "MKT"; break;
		case "KBI": $DeptCode = "KBI"; break;
		case "DMN": $DeptCode = "DMN"; break;
		case "EI1": $DeptCode = "EI1"; break;
		default: $DeptCode = $_SESSION['DeptCode']; break;
	}

	switch($DeptCode) {
		case "TT1": return "ฝ่ายขายร้านค้ากรุงเทพฯ"; break;
		case "ONL": return "ฝ่ายขายออนไลน์"; break;
		case "EXP": return "ฝ่ายขายต่างประเทศ"; break;
		case "MKT": return "ฝ่ายการตลาด"; break;
		case "KBI": return "ส่วนกลาง"; break;
		case "DMN": return "ฝ่ายเดมอน"; break;
		case "EI1": return "ฝ่ายขายปั๊มลมโรงงาน"; break;
		default:
			$TeamNameSQL = "SELECT T0.DeptName FROM departments T0 WHERE T0.DeptCode = '$DeptCode' LIMIT 1";
			$TeamNameRST = MySQLSelect($TeamNameSQL);
			return $TeamNameRST['DeptName'];
		break;
	}
}

function GroupName_Th($GroupCode) {
    switch($GroupCode) {
        case "G1": $GroupName = "MT"; break;
        case "G2": $GroupName = "SEMI MT"; break;
        case "G3": $GroupName = "T2"; break;
        case "G4": $GroupName = "T3"; break;
        case "G5": $GroupName = "TN"; break;
        case "G6": $GroupName = "ผู้ใช้นิติบุคคล"; break;
        case "G7": $GroupName = "ผู้ใช้บุคคลธรรมดา"; break;
        case "G8": $GroupName = "ต่างประเทศ"; break;
        case "G9": $GroupName = "รายการโทรทัศน์"; break;
        case "G10": $GroupName = "อื่น ๆ"; break;
    }
    return $GroupName;
}
function GroupCodeReturn($GroupCode, $Year) {
	if($Year <= 2022) {
		switch($GroupCode) {
			case "MT": $GroupSQL = "T2.[GroupCode] IN ('106')" ; break;
			case "SEMI MT": $GroupSQL = "T2.[GroupCode] IN ('107')" ; break;
			case "T2": $GroupSQL = "T2.[GroupCode] IN ('102')" ; break;
			case "T3": $GroupSQL = "T2.[GroupCode] IN ('103')" ; break;
			case "TN": $GroupSQL = "T2.[GroupCode] IN ('127')" ; break;
			case "ผู้ใช้นิติบุคคล": $GroupSQL = "T2.[GroupCode] IN ('104')" ; break;
			case "ผู้ใช้บุคคลธรรมดา": $GroupSQL = "T2.[GroupCode] IN ('100')" ; break;
			case "ต่างประเทศ": $GroupSQL = "T2.[GroupCode] IN ('125')" ; break;
			case "รายการโทรทัศน์": $GroupSQL = "T2.[GroupCode] IN ('128')" ; break;
			case "อื่น ๆ": $GroupSQL = "T2.[GroupCode] NOT IN ('106','107','102','103','127','104','100','125','128')" ; break;
		}
	} else {
		switch($GroupCode) {
			case "MT": $GroupSQL = "T2.[GroupCode] IN ('106')" ; break;
			case "SEMI MT": $GroupSQL = "T2.[GroupCode] IN ('107')" ; break;
			case "T2": $GroupSQL = "T2.[GroupCode] IN ('102')" ; break;
			case "T3": $GroupSQL = "T2.[GroupCode] IN ('103')" ; break;
			case "TN": $GroupSQL = "T2.[GroupCode] IN ('122')" ; break;
			case "ผู้ใช้นิติบุคคล": $GroupSQL = "T2.[GroupCode] IN ('104')" ; break;
			case "ผู้ใช้บุคคลธรรมดา": $GroupSQL = "T2.[GroupCode] IN ('100')" ; break;
			case "ต่างประเทศ": $GroupSQL = "T2.[GroupCode] IN ('120')" ; break;
			case "รายการโทรทัศน์": $GroupSQL = "T2.[GroupCode] IN ('123')" ; break;
			case "อื่น ๆ": $GroupSQL = "T2.[GroupCode] NOT IN ('106','107','102','103','122','104','100','120','123')" ; break;
		}
	}
    return $GroupSQL;
}
function ReturnName($ReturnCode) {
    switch($ReturnCode) {
        case "G1": $ReturnName = "1. เซลส์"; break;
        case "G2": $ReturnName = "2. ลูกค้า"; break;
        case "G3": $ReturnName = "3. สินค้า"; break;
        case "G4": $ReturnName = "4. สินค้าไม่เคลื่อนไหว"; break;
        case "G5": $ReturnName = "5. MT/Consign"; break;
        case "G6": $ReturnName = "6. ธุรการคลังสินค้า"; break;
        case "G7": $ReturnName = "7. ธุรการขาย"; break;
        case "G8": $ReturnName = "8. อื่น ๆ"; break;
        case "G9": $ReturnName = "9. แก้ไขบิลภายใน"; break;
    }
    return $ReturnName;
}
function TeamName_Th($TeamCode) {
    switch($TeamCode) {
        case 'MT1': $TeamName = "MT1";break;
		case 'MT2': $TeamName = "MT2";break;
		case 'TT1': $TeamName = "TT กทม.";break;
		case 'TT2': $TeamName = "TT ตจว.";break;
		case 'OUL': $TeamName = "หน้าร้าน";break;
		case 'ONL': $TeamName = "ออนไลน์";break;
		case 'DMN': $TeamName = "เดมอน";break;
		case 'KBI': $TeamName = "ส่วนกลาง";break;
    }
    return $TeamName;
}
function WhsGroupName($WhsGroup) {
    switch($WhsGroup) {
        case "W100": $WhsGroupName = "คลังสินค้าพร้อมขาย KSY > ส่วนกลาง"; break;
        case "W101": $WhsGroupName = "คลังสินค้าพร้อมขาย KSY > ฝ่าย MT1"; break;
        case "W102": $WhsGroupName = "คลังสินค้าพร้อมขาย KSY > ฝ่าย MT2"; break;
        case "W103": $WhsGroupName = "คลังสินค้าพร้อมขาย KSY > ฝ่าย TT"; break;
        case "W104": $WhsGroupName = "คลังสินค้าพร้อมขาย KSY > ฝ่ายหน้าร้าน"; break;
        case "W200": $WhsGroupName = "คลังสินค้าพร้อมขาย KBI > หน้าร้าน"; break;
        case "W300": $WhsGroupName = "คลังสินค้าซัพพลายเออร์"; break;
        case "W400": $WhsGroupName = "คลังสินค้ามือสอง"; break;
        case "W500": $WhsGroupName = "คลังสินค้าอื่น ๆ"; break;
    }
    return $WhsGroupName;
}
function NumToUnit($number,$decimal) {
	if($number >= 1000 AND $number <= 999999) { return number_format($number/1000000,$decimal); }
	elseif($number >= 1000000) { return number_format($number/1000000,$decimal); }
	else { return number_format($number,$decimal);}
}

function RandomString($password_pattern,$password_prefix,$password_length){
	if($password_pattern == 1){
		$characters = '0123456789';
	}else if($password_pattern == 2){
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}else if($password_pattern == 3){
		$characters = 'abcdefghijklmnopqrstuvwxyz';
	}else if($password_pattern == 4){
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}else if($password_pattern == 5){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	}else if($password_pattern == 6){
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}else{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}
    
    $randstring = '';
    for ($i = 0; $i < $password_length; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
	if($randstring < $password_length){
		$randstring = '';
		 for ($i = 0; $i < $password_length; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
		return $password_prefix.$randstring;
	}else{
		return $password_prefix.$randstring;
	}
    
}
function FullMonth($nomount){
  	switch  ($nomount){
		case 1:
			$w = 'มกราคม';	
			break;
		case 2:
			$w = 'กุมภาพันธ์';
			break;
		case 3:
			$w = 'มีนาคม';	
			break;
		case 4:
			$w = 'เมษายน';
			break;
		case 5:
			$w = 'พฤษภาคม';	
			break;
		case 6:
			$w = 'มิถุนายน';	
			break;
		case 7:
			$w = 'กรกฎาคม';	
			break;
		case 8:
			$w = 'สิงหาคม';
			break;
		case 9:
			$w = 'กันยายน';	
			break;
		case 10:
			$w = 'ตุลาคม';	
			break;
		case 11:
			$w = 'พฤศจิกายน';	
			break;
		case 12:
			$w = 'ธันวาคม';	
			break;
	}
	return $w;
}
function txtMonth($numMount){
	switch  ($numMount){
		case 1:
			return 'ม.ค.';	
			break;
		case 2:
			return 'ก.พ.'	;
			break;
		case 3:
			return 'มี.ค.';	
			break;
		case 4:
			return 'เม.ย.';
			break;
		case 5:
			return 'พ.ค.';	
			break;
		case 6:
			return 'มิ.ย.';	
			break;
		case 7:
			return 'ก.ค.';	
			break;
		case 8:
			return 'ส.ค.';
			break;
		case 9:
			return 'ก.ย.';	
			break;
		case 10:
			return 'ต.ค.';	
			break;
		case 11:
			return 'พ.ย.';	
			break;
		case 12:
			return 'ธ.ค.';	
			break;
	}

}

function FullDate($mc){
	$mc_day = date("d",strtotime($mc));
	$mc_year = date("Y",strtotime($mc));
	switch (date("m",strtotime($mc))){
		case '01':
		    $mc_mou = "มกราคม";	
			break;
		case '02':
			$mc_mou = "กุมภาพันธ์";
			break;
		case '03':
			$mc_mou = "มีนาคม";	
			break;
		case '04':
			$mc_mou = "เมษายน";
			break;
		case '05':
			$mc_mou = "พฤษภาคม";	
			break;
		case '06':
			$mc_mou = "มิถุนายน";	
			break;
		case '07':
			$mc_mou = "กรกฎาคม";	
			break;
		case '08':
			$mc_mou = "สิงหาคม";
			break;
		case '09':
			$mc_mou = "กันยายน";	
			break;
		case '10':
			$mc_mou = "ตุลาคม";	
			break;
		case '11':
			$mc_mou = "พฤศจิกายน";	
			break;
		case '12':
			$mc_mou = "ธันวาคม";	
			break;
	}
	$lnw = $mc_day." ".$mc_mou." ".$mc_year;
	return $lnw;
}
function dateSQL($dateCon){
	$dateAdd = str_replace('/', '-',$dateCon);
	$newDate = date("Y-m-d", strtotime($dateAdd));
	return($newDate);
}

function slpCodeTeam($teamCode){
	$connect = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$sql1 = "SELECT T0.slpCode FROM salelog T0 WHERE (T0.Doc_status = '1' OR T0.Resign = '1') and T0.user_team LIKE '".$teamCode."'";
	$result = mysqli_query($connect,$sql1);
	$returnOSLP = "(";
	while ($DataList = mysqli_fetch_array($result)){
		$returnOSLP = $returnOSLP.$DataList['slpCode']." OR ";
	}
	$returnOSLP = substr($returnOSLP,0,($returnOSLP-4)).")";
	mysqli_close($connect);
	return($returnOSLP);
}


function conutf8($tis) {
	$utf8x = "";
	for( $i=0 ; $i< strlen($tis) ; $i++ ){
	$s = substr($tis, $i, 1);
	$val = ord($s);
	if( $val < 0x80 ){
			if ($val == 32){
				$utf8x .= " ";
			}else{
			$utf8x .= $s;
			}
	} elseif ((0xA1 <= $val and $val <= 0xDA) 
				or (0xDF <= $val and $val <= 0xFB))  {
						$unicode = 0x0E00 + $val - 0xA0;
						$utf8x .= chr( 0xE0 | ($unicode >> 12) );
						$utf8x .= chr( 0x80 | (($unicode >> 6) & 0x3F) );
						$utf8x .= chr( 0x80 | ($unicode & 0x3F) );
	}else{
			if ($val == 160){
				$utf8x .= " ";
			}
		}
	}
	$utf8x = str_replace('?','',$utf8x);
  return $utf8x;
} 

function LastDate($xMonth,$xYear){
	$caldate = $xYear."-".$xMonth."-".cal_days_in_month(CAL_GREGORIAN,$xMonth,$xYear);
	return $caldate;
}

function CalPass($uname,$dx,$mx){
	$xd=strtolower(substr($uname,0,3));
	$md=$dx.$mx;
	$allX=$xd.$md;
	$newpass = md5($allX);
	return $newpass;
}

function conY(){
	$YK = date("Y");
	if ($YK >= 2500){
		$YK = substr($YK,2);
	}else{
		$YK = $YK + 543;
		$YK = substr($YK,2);
	}
	return $YK;
}

function CheckBonus($DocDate){
	$mCal = date("m");
	$yCal = date("Y");
	$yCal_Old = $yCal-1;
	switch ($mCal){
		case "01" :
			$dateCHK = $yCal_Old."/9/30";
			break;
		case "02" :
			$dateCHK = $yCal_Old."/10/31";
			break;
		case "03" :
			$dateCHK = $yCal_Old."/11/30";
			break;
		case "04" :
			$dateCHK = $yCal_Old."/12/31";
			break;
		case "05" :
			$dateCHK = $yCal."/1/31";
			break;
		case "06" :
			$dateCHK = $yCal."/2"."/".cal_days_in_month(CAL_GREGORIAN,2,$yCal);
			break;
		case "07" :
			$dateCHK = $yCal."/3/31";
			break;
		case "08" :
			$dateCHK = $yCal."/4/30";
			break;
		case "09" :
			$dateCHK = $yCal."/5/31";
			break;
		case "10" :
			$dateCHK = $yCal."/6/30";
			break;
		case "11" :
			$dateCHK = $yCal."/7/31";
			break;
		case "12" :
			$dateCHK = $yCal."/8/31";
			break;
		default :
			$xxx = "asd";

	}

    if (strtotime($dateCHK) >= strtotime($DocDate)){
		$x5 = TRUE;
	} else {
		$x5 = FALSE;
	}
	return $x5;
}

function CalculateBonus($userkey) {
	
	switch(date("m")) {
		case "01": $y = date("Y")-1; $m = 9;  $mName = "กันยายน"; break;
		case "02": $y = date("Y")-1; $m = 10; $mName = "ตุลาคม"; break;
		case "03": $y = date("Y")-1; $m = 11; $mName = "พฤศจิกายน"; break;
		case "04": $y = date("Y")-1; $m = 12; $mName = "ธันวาคม"; break;
		case "05": $y = date("Y");   $m = 1;  $mName = "มกราคม"; break;
		case "06": $y = date("Y");   $m = 2;  $mName = "กุมภาพันธ์"; break;
		case "07": $y = date("Y");   $m = 3;  $mName = "มีนาคม"; break;
		case "08": $y = date("Y");   $m = 4;  $mName = "เมษายน"; break;
		case "09": $y = date("Y");   $m = 5;  $mName = "พฤษภาคม"; break;
		case "10": $y = date("Y");   $m = 6;  $mName = "มิถุนายน"; break;
		case "11": $y = date("Y");   $m = 7;  $mName = "กรกฎาคม"; break;
		case "12": $y = date("Y");   $m = 8;  $mName = "สิงหาคม"; break;
	}
	$DeptCode = NULL;
	switch($userkey) {
		case "B11": $IVWhr = "(T0.SlpCode = 1)"; break;
		case "B60": $IVWhr = "(T0.SlpCode = 251)"; break;
		case "B98": $IVWhr = "(T0.SlpCode = 296)"; break;
		case "B99": $IVWhr = "(T0.SlpCode = 291)"; break;
		case 'PITA' : $IVWhr = "(T0.SlpCode != 999)"; break;
		default:    $IVWhr = "(T1.Memo = '$userkey')";
					$DeptSQL = "SELECT T1.DeptCode FROM users T0 LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode WHERE T0.ukey = '$userkey' LIMIT 1";
					$DeptRST = MySQLSelect($DeptSQL);
					$DeptCode = $DeptRST['DeptCode'];
					break;
	}

	switch($DeptCode) {
		case "DP005":
		case "DP008": $CallBonus = TRUE; break;
		default:      $CallBonus = FALSE; break;
	}
	if($CallBonus == TRUE) {
		/* หาจำนวนบิล และมูลค่าเงินที่ต้องเก็บให้ได้ */
		$GetIVSQL = "SELECT TOP 1
						COUNT(T0.DocEntry) AS 'CountIV', ISNULL(SUM(T0.DocTotal-T0.PaidToDate),0) AS 'NoPaid'
					FROM OINV T0
					LEFT JOIN KBI_DB2022.dbo.OINV T7 ON T0.NumAtCard = T7.NumAtCard
					LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode 
					WHERE
					(
						(
							MONTH(CASE WHEN T0.DocDate = '2022-12-31' THEN T7.DocDate ELSE T0.DocDate END) <= $m AND 
							YEAR(CASE WHEN T0.DocDate = '2022-12-31' THEN T7.DocDate ELSE T0.DocDate END) = $y
						) OR 
						(
							YEAR(CASE WHEN T0.DocDate = '2022-12-31' THEN T7.DocDate ELSE T0.DocDate END) < $y
						)
					) AND (T0.DocStatus = 'O') AND T0.CANCELED = 'N' AND $IVWhr";
		$GetIVQRY = SAPSelect($GetIVSQL);
		
		$GetIVRST = odbc_fetch_array($GetIVQRY);
		$CountIV  = $GetIVRST['CountIV'];
		$NoPayIV  = $GetIVRST['NoPaid'];
		/* หายอดขายในเดือนที่ตั้งต้นในการคิดโบนัส */
		$GetSASQL = "SELECT TOP 1 SUM(A0.DocTotal) AS 'DocTotal' FROM (
							SELECT
								SUM(T0.DocTotal-T0.VatSum) AS 'DocTotal'
							FROM OINV T0
							LEFT JOIN KBI_DB2022.dbo.OINV T7 ON T0.NumAtCard = T7.NumAtCard
							LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
							WHERE T0.CANCELED = 'N' AND (YEAR(CASE WHEN T0.DocDate = '2022-12-31' THEN T7.DocDate ELSE T0.DocDate END) = $y AND MONTH(CASE WHEN T0.DocDate = '2022-12-31' THEN T7.DocDate ELSE T0.DocDate END) = $m) AND $IVWhr
							UNION ALL
							SELECT
								-SUM(T0.DocTotal-T0.VatSum) AS 'DocTotal'
							FROM ORIN T0
							LEFT JOIN KBI_DB2022.dbo.ORIN T7 ON T0.NumAtCard = T7.NumAtCard
							LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
							LEFT JOIN NNM1 T2 ON T0.Series = T2.Series
							WHERE T0.CANCELED = 'N' AND (YEAR(CASE WHEN T0.DocDate = '2022-12-31' THEN T7.DocDate ELSE T0.DocDate END) = $y AND MONTH(CASE WHEN T0.DocDate = '2022-12-31' THEN T7.DocDate ELSE T0.DocDate END) = $m AND T2.BeginStr IN ('S1-','SR-')) AND $IVWhr
						) A0";
		if($y <= 2022) {
			$GetSAQRY = conSAP8($GetSASQL);
		} else {
			$GetSAQRY = SAPSelect($GetSASQL);
		}
		
		$GetSARST = odbc_fetch_array($GetSAQRY);
		$SaleBNS  = $GetSARST['DocTotal'];
		/* เงื่อนไขโบนัส */
		
		$Bonus = 0;
		// if($CountIV > 0) {
			if($DeptCode == "DP008" || $userkey = "B60") {
				if($SaleBNS > 500000) { $Bonus = 500; }
				if($SaleBNS > 600000) { $Bonus = 500; }
				if($SaleBNS > 700000) { $Bonus = 500; }
			}
			if($SaleBNS > 800000)  { $Bonus = 1000;  }
			if($SaleBNS > 900000)  { $Bonus = 2000;  }
			if($SaleBNS > 1000000) { $Bonus = 4000;  }
			if($SaleBNS > 1100000) { $Bonus = 5000;  }
			if($SaleBNS > 1200000) { $Bonus = 6000;  }
			if($SaleBNS > 1300000) { $Bonus = 8000;  }
			if($SaleBNS > 1400000) { $Bonus = 9000;  }
			if($SaleBNS > 1500000) { $Bonus = 11000; }
			if($SaleBNS > 1600000) { $Bonus = 13000; }
			if($SaleBNS > 1700000) { $Bonus = 17000; }
			if($SaleBNS > 1800000) { $Bonus = 20000; }
			if($SaleBNS > 1900000) { $Bonus = 24000; }
			if($SaleBNS > 2000000) { $Bonus = 28000; }
		// } else {
		// 	$Bonus = 0;
		// }
		/* RETURN ARRAY("จำนวนบิลที่จะได้โบนัส","มูลค่ารอเก็บในบิลที่จะได้โบนัส","มูลค่ายอดขายในเดือนสำหรับการคิดเงื่อนไขโบนัส","โบนัสที่จะได้รับ","ชื่อเดือน ปี"); */
		return array($CountIV,$NoPayIV,$SaleBNS,$Bonus, $mName." ".$y);
	} else {
		return array(0,0,0,0,"");
	}
}

function lastTXTNO($taxtype,$taxMonth) {
	$sql1 = "SELECT T0.ID FROM wht_JAP T0 WHERE T0.TaxCat = '$taxtype' AND T0.VatMonth = '$taxMonth' AND T0.Status = 1";
	$row = ChkRowDB($sql1);
	if ($row != 0){
		$sql1 = "SELECT T0.BookNo FROM wht_JAP T0 WHERE T0.TaxCat = '$taxtype' AND T0.VatMonth = '$taxMonth' AND T0.Status = 1 ORDER BY T0.BookNo DESC LIMIT 1";
		$DataList = MySQLSelect($sql1);
		$cat = substr($DataList['BookNo'],0,8);
		$Run = substr($DataList['BookNo'],-3);
		$Run = intval($Run)+1;
		if ($Run <= 9){
			$newRun = '00'.$Run;
		}else{
			if ($Run <= 99){
				$newRun = '0'.$Run;
			}else{
				$newRun = $Run;
			}
		}
		$newBook = $cat.$newRun;
    
	}else{
		//เริ่มเดือนใหม่
		$cat = str_replace("S","P",$taxtype);
		$mTax = substr($taxMonth,0,2);
		$yTax = substr($taxMonth,-2);
		$newBook = $cat."/".$yTax.$mTax."001";
	}
	return $newBook;
}

function LastWorkDate($today){
	$connect = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	// MySQL_SET_utf8();
	//$result = mysqli_query($connect,$sql1);
	//while ($DataList = mysqli_fetch_array($result)){
	$WeekEND = 1;
	while (($WeekEND == 1) || date('N',strtotime($today)) == '7'){
		$today =  date("Y-m-d",strtotime("-1 days",strtotime($today)));
		$sql1 = "SELECT * FROM holiday_annual WHERE HolidayDate = '".$today."' AND StatusDate = 'A'";
		$result = mysqli_query($connect,$sql1);
		$WeekEND = mysqli_num_rows($result);
	}
	mysqli_close($connect);
	return $today;
}

function numText($number){ 

    $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
    $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
    $number = str_replace(",","",$number); 
    $number = str_replace(" ","",$number); 
    $number = str_replace("บาท","",$number); 
    $number = explode(".",$number); 
    if(sizeof($number)>2){ 
    return 'ทศนิยมหลายตัวนะจ๊ะ'; 
    exit; 
    } 
    $strlen = strlen($number[0]); 
    $convert = ''; 
    for($i=0;$i<$strlen;$i++){ 
        $n = substr($number[0], $i,1); 
        if($n!=0){ 
            if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
            elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; } 
            elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
            else{ $convert .= $txtnum1[$n]; } 
            $convert .= $txtnum2[$strlen-$i-1]; 
        } 
    } 
    
    $convert .= 'บาท'; 
    if($number[1]=='0' OR $number[1]=='00' OR 
    $number[1]==''){ 
    $convert .= 'ถ้วน'; 
    }else{ 
    $strlen = strlen($number[1]); 
    for($i=0;$i<$strlen;$i++){ 
    $n = substr($number[1], $i,1); 
        if($n!=0){ 
        if($i==($strlen-1) AND $n==1){$convert 
        .= 'เอ็ด';} 
        elseif($i==($strlen-2) AND 
        $n==2){$convert .= 'ยี่';} 
        elseif($i==($strlen-2) AND 
        $n==1){$convert .= '';} 
        else{ $convert .= $txtnum1[$n];} 
        $convert .= $txtnum2[$strlen-$i-1]; 
        } 
    } 
    $convert .= 'สตางค์'; 
    } 
    return $convert; 
}

function LineNoti($DocType,$message){

	$sql1 = "SELECT token FROM LineNoti WHERE DocType = '".$DocType."'";
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$query = mysqli_query($connectF,$sql1);
	$row = mysqli_fetch_array($query);
	define('LINE_API',"https://notify-api.line.me/api/notify");
	$message .= "\r"."วันที : ".date("d-M-Y")." เวลา ".date("H:i")." น.";
	date_default_timezone_set('Asia/Bangkok');
    $queryData = array('message' => $message);
    $queryData = http_build_query($queryData,'','&');
    $headerOptions = array( 
            'http'=>array(
               'method'=>'POST',
               'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                         ."Authorization: Bearer ".$row['token']."\r\n"
                         ."Content-Length: ".strlen($queryData)."\r\n",
               'content' => $queryData
            ),
    );
    $context = stream_context_create($headerOptions);
    $result = file_get_contents(LINE_API,FALSE,$context);
	$res = json_decode($result);
	mysqli_close($connectF);
	return ($res);
	
	//return "waiwai";
}

function LineUser($DeptCode,$user,$message){
	//$user = uKey OR LvCode OR uClass 
	//ถ้า $user = uClass ต้องส่งค่า $DeptCode มาด้วย นอกนั้นส่งมาเป็น null ได้
	
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	if (strlen($user) == 32){
		$CaseType = "uKey";
	}else{
		if (substr($user,0,2) == 'LV'){
			$CaseType = 'LvCode';
		}else{
			$CaseType = 'uClass';
		}
	}
	switch ($CaseType){
		case 'uKey' :
			$sql1 = "SELECT LineToken FROM users WHERE uKey = '".$user."'";
			$query = mysqli_query($connectF,$sql1);
			$row = mysqli_fetch_array($query);
			if (strlen($row['LineToken']) > 1){
				$token = $row['LineToken'];
			}else{
				$token = "0";
			}
			break;
		case 'LvCode' :
			$sql1 = "SELECT uKey,LineToken FROM users WHERE LvCode = '".$user."' AND UserStatus = 'A'";
			$i=0;
			$query = mysqli_query($connectF,$sql1);
			while ($row = mysqli_fetch_array($query)){
				$i++;
				$token = $row['LineToken'];
			}
			if ($i == 1 && strlen($token) > 1){
				$Run = "WAIWAI";
			}else{
				$token = "0";
			}
		break;
		case 'uClass' :
			$sql1 = "SELECT T0.uKey,T0.LineToken 
			         FROM users T0
					      LEFT JOIN Positions T1 ON T0.LvCode = T1.LvCode
					 WHERE T1.uClass = '".$user."' AND T1.DeptCode = '".$DeptCode."' AND T0.UserStatus = 'A'";
			$i=0;
			$query = mysqli_query($connectF,$sql1);
			while ($row = mysqli_fetch_array($query)){
				$i++;
				$token = $row['LineToken'];
			}
			if ($i == 1 && strlen($token) > 1){
				$Run = "WAIWAI";
			}else{
				$token = "0";
			}
		break;
	}
	
	if ($token != '0'){
		define('LINE_API',"https://notify-api.line.me/api/notify");
		date_default_timezone_set('Asia/Bangkok');
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
		mysqli_close($connectF);
		return ($res);
	}
}

function Next3Day($today){
	$connect = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$WeekEND = 1;
	$today =  date("Y-m-d",strtotime("+3 days",strtotime($today)));
	while (($WeekEND == 1) || date('N',strtotime($today)) == '7'){
		$today =  date("Y-m-d",strtotime("+1 days",strtotime($today)));
		$sql1 = "SELECT * FROM holiday_annual WHERE HolidayDate = '".$today."' AND StatusDate = 'A'";
		$result = mysqli_query($connect,$sql1);
		$WeekEND = mysqli_num_rows($result);
	}
	mysqli_close($connect);
	return $today;
}

function ConToInt($x){
	return intval(str_replace(",","",$x));
}

function chk0($x,$y){
    if ($x == 0){
        return " - ";
    }else{
        return number_format($x,$y);
    }

}

function slpCodeData($TeamCode,$ukey){
	$connect = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	if ($TeamCode == NULL){
		$WH = " T0.Ukey = '".$ukey."' ";
	}else{
		if ($ukey == NULL){
			if($TeamCode == "OUL") {
				$WH = " T0.TeamCode LIKE 'OUL%' OR T0.TeamCode LIKE 'TT1%' ";
			} elseif($TeamCode == "MT1") {
				$WH = " T0.TeamCode LIKE 'MT1%' OR T0.TeamCode LIKE 'EXP%' ";
			} else {
				$WH = " T0.TeamCode LIKE '".$TeamCode."%'";
			}
			
		}else{
			$WH = " T0.TeamCode LIKE '".$TeamCode."%' AND Ukey = '".$ukey."' ";
			
		}
	}
	$sql1 = "SELECT T0.SlpCode,T0.SlpName
			 FROM oslp T0
			 WHERE ".$WH." 
			 ORDER BY T0.SlpCode";
	// return $sql1;

	$query = mysqli_query($connect,$sql1);
	$SlpCode = "(";
	$i=0;
	while ($row = mysqli_fetch_array($query)){
		$i++;
		$SlpCode .= $row['SlpCode'].",";
		$SlpName = $row['SlpName'];
	}
	if ($i > 0){
		$SlpCode = substr($SlpCode,0,strlen($SlpCode)-1).")";
		$xData['SlpCode'] = $SlpCode;
		$xData['SlpName'] = $SlpName;
	}else{
		$xData['SlpCode'] = "none";
		$xData['SlpName'] = "none";
	}
	mysqli_close($connect);
	return $xData;
}

function AddDecimal($num,$deci) {
	$newx = number_format($num,$deci);
	return str_replace(",","",$newx);
}

function GetPriceList($CardCode,$ItemCode,$Quantity) {
	$GetPriceSQL = "SELECT
						T0.ItemCode, T0.P1, T0.P2, T0.S1Q, T0.S1P, T0.S2Q, T0.S2P, T0.S3Q, T0.S3P, T0.MgrPrice, T0.MTPrice,
						CASE WHEN SUBSTRING(T0.PriceType,1,3) = 'PRO' THEN 1
						     WHEN SUBSTRING(T0.PriceType,1,3) = 'GRP' THEN 2
							 ELSE 3 END AS lnNum
					FROM pricelist T0
					LEFT JOIN groupprice T1 ON T0.PriceType = T1.GroupCode
					WHERE ((T1.CardCode = '$CardCode' AND T1.DocStatus = 'A') OR 
					       (T0.PriceType = 'STD' AND T0.PriceStatus = 'A') OR 
						   (SUBSTRING(T0.PriceType,1,3) = 'PRO' AND (T0.StartDate >= DATE(NOW()) AND T0.EndDate <= DATE(NOW()) AND T0.PriceStatus = 'A'))) AND T0.ItemCode = '$ItemCode'
					ORDER BY lnNum, T0.PriceType ASC, T0.DateUpdate DESC LIMIT 1";
					//echo $GetPriceSQL;

	$GetPriceSQL = "SELECT
					T0.ItemCode, T0.P1, T0.P2, T0.S1Q, T0.S1P, T0.S2Q, T0.S2P, T0.S3Q, T0.S3P, T0.MgrPrice, T0.MTPrice,
					CASE WHEN SUBSTRING(T0.PriceType,1,3) = 'PRO' THEN 1
						 WHEN SUBSTRING(T0.PriceType,1,3) = 'GRP' THEN 2
						 ELSE 3 END AS lnNum
				FROM pricelist T0
				LEFT JOIN groupprice T1 ON T0.PriceType = T1.GroupCode
				WHERE ((T1.CardCode = '$CardCode' AND T1.DocStatus = 'A') OR 
					   (T0.PriceType = 'STD' AND T0.PriceStatus = 'A') OR 
					   (T0.PriceType = 'PRO' AND (T0.StartDate <= DATE(NOW()) AND T0.EndDate >= DATE(NOW()) ) AND T0.PriceStatus = 'A' AND T0.S1Q <= $Quantity)) AND T0.ItemCode = '$ItemCode'
				ORDER BY lnNum, T0.PriceType ASC, T0.DateUpdate DESC LIMIT 1";


	$GetPriceRST = MySQLSelect($GetPriceSQL);

	if(!isset($GetPriceRST['ItemCode'])) {
		/* ไม่เจอราคาใน Price List ส่งค่า Default = 0 */
		$DP = 0;
	} else {
		switch($_SESSION['DeptCode']) {
			case "DP006":
			case "DP007":
				if($GetPriceRST['MTPrice'] > 0) {
					$DP = $GetPriceRST['MTPrice'];
				} else {
					$DP = $GetPriceRST['P1'];
				}
			break;
			default:
				/* เช็ค Level ผจก. */
				if($_SESSION['uClass'] == 18 && $GetPriceRST['MgrPrice'] > 0) {
					$DP = $GetPriceRST['MgrPrice'];
				} else {
					$Quantity = intval($Quantity);
					/* เช็ค Step จากจำนวนสินค้าที่สั่ง */
					if($GetPriceRST['S3P'] != 0 && $GetPriceRST['S3Q'] <= $Quantity) {
						$DP = $GetPriceRST['S3P'];
					} else if($GetPriceRST['S2P'] != 0 && $GetPriceRST['S2Q'] <= $Quantity) {
						$DP = $GetPriceRST['S2P'];
					} else if($GetPriceRST['S1P'] != 0 && $GetPriceRST['S1Q'] <= $Quantity) {
						$DP = $GetPriceRST['S1P'];
					} else if($GetPriceRST['P2'] != 0) {
						$DP = $GetPriceRST['P2'];
					} else {
						$DP = $GetPriceRST['P1'];
					}
				}
			break;
		}
	}
	return $DP;
}

function AppStep($AppCode){
	//$connect = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$sql1 = "SELECT ApproveCode,UserApp,StepApprove,TypeApp FROM approvecenter WHERE ApproveCode = '".$AppCode."' AND StatusDoc = 'A' ORDER BY StepApprove";
	$GetApp = MySQLSelectX($sql1);
	$i=0;
	while ($row = mysqli_fetch_array($GetApp)){
		$stepApp[$i] = $row['StepApprove'];
		$TypeApp[$i] = $row['TypeApp'];
		if (strlen($row['UserApp']) >= 32){
			$UkeyApp[$i] = $row['UserApp'];
		}else{
			if (substr($row['UserApp'],0,2) == 'LV'){
				$sql2 = "SELECT uKey FROM users WHERE LvCode = '".$row['UserApp']."' AND UserStatus = 'A'";
			}else{
				$sql2 = "SELECT T0.uKey 
						 FROM users T0
							  LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode 
						 WHERE T1.DeptCode = '".$_SESSION['DeptCode']."' AND T1.uClass = '".$row['UserApp']."' AND T0.UserStatus = 'A'";
			}
			$GetUkey = MySQLSelect($sql2);
			$UkeyApp[$i] = $GetUkey['uKey'];
		}
		$i++;
	}
	for ($a=0;$a<$i;$a++){
		$DataApp[$a]['StepApprove'] = $stepApp[$a];
		$DataApp[$a]['TypeApp'] = $TypeApp[$a];
		$DataApp[$a]['UkeyApp'] = $UkeyApp[$a];
	}
	return $DataApp;
}

function conData($Cha) {
	$loop = strlen($Cha);
	$reCha = "";
	$NewX = "";
	//$data = explode(null,$a);
	switch (strtoupper($Cha[0])){
		case 'A' :
			for ($i=1;$i<$loop;$i++){
				switch($Cha[$i]){
					case '0' :
						$NewX = 'h';
					break;
					case '1' :
						$NewX = 'q';
					break;
					case '2' :
						$NewX = 'j';
					break;
					case '3' :
						$NewX = 'm';
					break;
					case '4' :
						$NewX = 's';
					break;
					case '5' :
						$NewX = 'R';
					break;
					case '6' :
						$NewX = 'P';
					break;
					case '7' :
						$NewX = 'T';
					break;
					case '8' :
						$NewX = 'U';
					break;
					case '9' :
						$NewX = 'w';
					break;
					case '.' :
						$NewX = 'x';
					break;
				}
				$reCha .= $NewX;
			}
			break;
		case 'B' :
			for ($i=1;$i<$loop;$i++){
				switch($Cha[$i]){
					case 'h' :
						$NewX = '0';
					break;
					case 'q' :
						$NewX = '1';
					break;
					case 'j' :
						$NewX = '2';
					break;
					case 'm' :
						$NewX = '3';
					break;
					case 's' :
						$NewX = '4';
					break;
					case 'R' :
						$NewX = '5';
					break;
					case 'P' :
						$NewX = '6';
					break;
					case 'T' :
						$NewX = '7';
					break;
					case 'U' :
						$NewX = '8';
					break;
					case 'w' :
						$NewX = '9';
					break;
					case 'x' :
						$NewX = '.';
					break;
				}
				$reCha .= $NewX;
			}
			break;
		default :
			$reCha = "-";
			break;
	}
	return $reCha;

}
function GroupCard($CardCode){
	$sql1 = "SELECT T0.CardCode,T0.FatherCard
              FROM OCRD T0
              WHERE   T0.FatherCard = '".$CardCode."' OR T0.CardCode = '".$CardCode."'
			  ORDER BY T0.CardCode DESC";
	$getCard = SAPSelect($sql1);
	$result = "('";
	while ($CardList = odbc_fetch_array($getCard)){
		$result .= $CardList['CardCode']."','";
	}
	$result = substr($result,0,-2).")";
	return $result;
}

function SapTHSearch($UTF8Text) { /* แปลง UTF-8 เป็น ISO-8859-11 เพื่อค้นหาด้วยภาษาไทย */
    return iconv("UTF-8", "ISO-8859-11", $UTF8Text);
}

function PathMenu($MenuCase) {
	$SQL = "
        SELECT
            CASE
                WHEN T0.MenuLv = 2 THEN (SELECT Q0.MenuName FROM menus Q0 WHERE Q0.MenuKey = (SELECT P0.UpKey FROM menus P0 WHERE P0.MenuKey = T0.UpKey))
                WHEN T0.MenuLv = 1 THEN (SELECT P0.MenuName FROM menus P0 WHERE P0.MenuKey = T0.UpKey)
                WHEN T0.MenuLv = 0 THEN T0.MenuName
            ELSE NULL END AS 'Menu0',
            CASE
                WHEN T0.MenuLv = 2 THEN (SELECT P0.MenuName FROM menus P0 WHERE P0.MenuKey = T0.UpKey)
                WHEN T0.MenuLv = 1 THEN T0.MenuName
            ELSE NULL END AS 'Menu1',
            CASE
                WHEN T0.MenuLv = 2 THEN T0.MenuName
            ELSE NULL END AS 'Menu2'
        FROM menus T0
        WHERE T0.MenuCase = '$MenuCase'";
	$RST = MySQLSelect($SQL);
    $PathMenu = "";

    if($RST['Menu1'] != "") {
        if($RST['Menu2'] != "") {
            $PathMenu = $RST['Menu0']." <i class='fas fa-angle-right'></i> ".$RST['Menu1']." <i class='fas fa-angle-right'></i> ".$RST['Menu2'];
        }else{
            $PathMenu = $RST['Menu0']." <i class='fas fa-angle-right'></i> ".$RST['Menu1'];
        }
    }
	return $PathMenu;
}

?>
