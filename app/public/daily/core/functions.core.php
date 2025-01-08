<?php
date_default_timezone_set('Asia/Bangkok');
//------------ in use -------------

function updateDateNow(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$gety = $getdata->my_sql_query("month","autonumber",NULL);
	if(date("m") != $gety->month){
		$getdata->my_sql_update("autonumber","item_number='1',finance_number='1',quotation_number='1',invoice_number='1',year='".date("Y")."',month='".date("m")."',day='".date("d")."'",NULL);
	}else{
		$getdata->my_sql_update("autonumber","year='".date("Y")."',month='".date("m")."',day='".date("d")."'",NULL);
	}
	
}
function INumber(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getlynda = $getdata->my_sql_query(NULL,"autonumber",NULL);
	return substr($getlynda->year,2,2).$getlynda->month.$getlynda->item_number;
}
function updateItem(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getdata->my_sql_update("autonumber","`item_number`=`item_number`+1",NULL);
}
function memberNumber(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getlynda = $getdata->my_sql_query(NULL,"autonumber",NULL);
	return ($getlynda->year+543).$getlynda->member_number;
}
function updateMember(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getdata->my_sql_update("autonumber","`member_number`=`member_number`+1",NULL);
}
function financeNumber(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getlynda = $getdata->my_sql_query(NULL,"autonumber",NULL);
	return 'REC'.$getlynda->year.$getlynda->month.$getlynda->finance_number;
}
function updateFinance(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getdata->my_sql_update("autonumber","`finance_number`=`finance_number`+1",NULL);
}
function importNumber(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getlynda = $getdata->my_sql_query(NULL,"autonumber",NULL);
	return 'LOT'.$getlynda->year.$getlynda->month.$getlynda->import_number;
}
function updateImport(){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getdata->my_sql_update("autonumber","`import_number`=`import_number`+1",NULL);
}
function dateConvertor($date){
	$epd = explode("-",$date);
		$Y=$epd[0]+543;
		return $epd[2]."/".$epd[1]."/".$Y;
	
}
function dateConvertorAD($date){
	return date("F d, Y", strtotime($date));
	
}
function dateTimeConvertor($datetime){
	$epd = explode(" ",$datetime);
	$date = new DateTime($epd[0]);
	$exptime = explode(":",$epd[1]);
	$date->setTime($exptime[0],$exptime[1],$exptime[2]);
	$Y=$epd[0]+543;
	return $date->format("d/m/$Y H:i:s");
}
function dateOnlyConvertor($datetime){
	$epd = explode(" ",$datetime);
	$epx = explode("-",$epd[0]);
	return $epx[2].'/'.$epx[1].'/'.$epx[0];
}
function substr_word($body,$maxlength){
    if (strlen($body)<$maxlength) return $body;
    $body = substr($body, 0, $maxlength);
    $rpos = strrpos($body,' ');
    if ($rpos>0) $body = substr($body, 0, $rpos);
    return $body;
}
function convertToLanguage($dis_thai,$dis_eng,$dis_now){
	if($dis_now == "en"){
		if($dis_eng == NULL){
			return $dis_thai;
		}else{
			return $dis_eng;
		}
	}else{
		if($dis_thai == NULL){
			return $dis_eng;
		}else{
			return $dis_thai;
		}
	}
}
function convertPoint($value,$point){
	if($value != NULL){
		return number_format($value,$point, '.', '');
	}else{
		return NULL;
	}
}
function convertPoint2($value,$point){
	if($value != NULL){
		return number_format($value,$point, '.', ',');
	}else{
		return number_format(0,$point, '.', ',');
	}
}
function resizeProductThumb($imgext,$imgname){
	switch($imgext){
		case "jpg" :
		case "jpeg" : 		$images = "../resource/products/images/".$imgname;
							$new_images = "../resource/products/thumbs/".$imgname;
						
					
							$width=400; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromJPEG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageJPEG($images_fin,$new_images);
							ImageDestroy($images_fin);
			break;
			case "png" : 	$images = "../resource/products/images/".$imgname;
							$new_images = "../resource/products/thumbs/".$imgname;
						
					
							$width=400; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromPNG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImagePNG($images_fin,$new_images);
			break;
			case "gif"	:	$images = "../resource/products/images/".$imgname;
							$new_images = "../resource/products/thumbs/".$imgname;
					
					
							$width=400; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromGIF($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageGIF($images_fin,$new_images);
			break;
			default : $images = "../resource/products/images/".$imgname;
							$new_images = "../resource/products/thumbs/".$imgname;
						
					
							$width=400; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromJPEG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageJPEG($images_fin,$new_images);
							ImageDestroy($images_fin);
							
	}
	
}
function resizeMemberThumb($imgext,$imgname){
	switch($imgext){
		case "jpg" :
		case "jpeg" : 		$images = "../resource/members/images/".$imgname;
							$new_images = "../resource/members/thumbs/".$imgname;
						
					
							$width=250; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromJPEG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageJPEG($images_fin,$new_images);
							ImageDestroy($images_fin);
			break;
			case "png" : 	$images = "../resource/members/images/".$imgname;
							$new_images = "../resource/members/thumbs/".$imgname;
						
					
							$width=250; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromPNG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImagePNG($images_fin,$new_images);
			break;
			case "gif"	:	$images = "../resource/members/images/".$imgname;
							$new_images = "../resource/members/thumbs/".$imgname;
					
					
							$width=250; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromGIF($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageGIF($images_fin,$new_images);
			break;
			default : $images = "../resource/members/images/".$imgname;
							$new_images = "../resource/members/thumbs/".$imgname;
						
					
							$width=250; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromJPEG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageJPEG($images_fin,$new_images);
							ImageDestroy($images_fin);
							
	}
	
}
function resizeUserThumb($imgext,$imgname){
	switch($imgext){
		case "jpg" :
		case "jpeg" : 		$images = "../resource/users/images/".$imgname;
							$new_images = "../resource/users/thumbs/".$imgname;
						
					
							$width=250; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromJPEG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageJPEG($images_fin,$new_images);
							ImageDestroy($images_fin);
			break;
			case "png" : 	$images = "../resource/users/images/".$imgname;
							$new_images = "../resource/users/thumbs/".$imgname;
						
					
							$width=250; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromPNG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImagePNG($images_fin,$new_images);
			break;
			case "gif"	:	$images = "../resource/users/images/".$imgname;
							$new_images = "../resource/users/thumbs/".$imgname;
					
					
							$width=250; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromGIF($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageGIF($images_fin,$new_images);
			break;
			default : $images = "../resource/users/images/".$imgname;
							$new_images = "../resource/users/thumbs/".$imgname;
						
					
							$width=250; //*** Fix Width & Heigh (Auto caculate) ***//
							$size=GetimageSize($images);
							$height=round($width*$size[1]/$size[0]);
							$images_orig = ImageCreateFromJPEG($images);
							$photoX = ImagesX($images_orig);
							$photoY = ImagesY($images_orig);
							$images_fin = ImageCreateTrueColor($width, $height);
							ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
							ImageJPEG($images_fin,$new_images);
							ImageDestroy($images_fin);
							
	}
	
}

function accessModule($module_key,$return_value){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getmodule_status = $getdata->my_sql_show_rows("modules","module_key='".$module_key."' AND module_status='1'");
	if($getmodule_status != 1){
		return '';
	}else{
		return $return_value;
	}
}
function updateCenter($user_version,$update_url){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$curlurl = $update_url."check/getversion.php";
	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	$params = "uv=".$user_version; 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST,1); // method ที่เราจะส่ง เป็น get หรือ post
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params); // paremeter สำหรับส่งไปยังไฟล์ ที่กำหนด
	curl_setopt($ch, CURLOPT_URL,$curlurl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

	$result = curl_exec($ch); // ผลการ execute กลับมาเป็น ข้อมูลใน url ที่เรา ส่งคำร้องขอไป
	curl_close ($ch);
	if($result == "update"){
		$getdata->my_sql_update("system_info","system_need_update='1'",NULL);
	}else{
		$getdata->my_sql_update("system_info","system_need_update='0'",NULL);
	}
	return $result;
}
function resizeSlideThumb($imgname){
	$images = "../resource/link/slide/images/".$imgname;
	$new_images = "../resource/link/slide/thumbs/".$imgname;

		$width=240; //*** Fix Width & Heigh (Auto caculate) ***//
		$size=GetimageSize($images);
		$height=round($width*$size[1]/$size[0]);
		$images_orig = ImageCreateFromJPEG($images);
		$photoX = ImagesX($images_orig);
		$photoY = ImagesY($images_orig);
		$images_fin = ImageCreateTrueColor($width, $height);
  		ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
		ImageJPEG($images_fin,$new_images);
		ImageDestroy($images_fin);
}
function ClearProductsCareNumber($serial){
	$set1 = substr($serial, 0, 4);
	$set2 = substr($serial, 4, 4);
	$set3 = substr($serial, 8, 4);
	$set4 = substr($serial, 12, 4);
	return $set1.' &ndash; '.$set2.' &ndash; '.$set3.' &ndash; '.$set4;
}
function getPhotoSize($photo){
	$size=GetimageSize($photo);
	if($size[0] > $size[1]){
		return 'height="50%"';
	}else if($size[0] < $size[1]){
		return 'width="50%"';
	}else{
		return 'height="50%" width="50%"';
	}
}

function RandomString($password_pattern,$password_prefix,$password_length)
{
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
function cardStatus($card_status){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getall_status=$getdata->my_sql_select(NULL,"card_type","ctype_status='1'");
	while($showall_status = mysql_fetch_object($getall_status)){
		if($card_status == $showall_status->ctype_key){
			return '<span class="label" style="background:'.$showall_status->ctype_color.'">'.$showall_status->ctype_name.'</span>';
		}else if($card_status == ''){
			return '<span class="label  label-default" >ข้อมูลไม่สมบูรณ์</span>';
		}else if($card_status == 'hidden'){
			return '<span class="label  label-danger" >ข้อมูลถูกซ่อน</span>';
		}
	}
}
function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
	
    $full = $protocol . "://" . $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
	$cut = str_replace('dashboard/card/print_card.php','card.php?key=',$full);
	return $cut;
}

function sixmount($nomount){
	//$nomount = $nomount -7;
	switch  ($nomount){
		case 1:
			$w = 'มกราคม';	
			break;
		case 2:
			$w = 'กุมภาพันธ์'	;
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

function inwmount($mc){
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

function slpCodeTeam($teamCode){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$getOSLP=$getdata->my_sql_select("T0.slpCode","salelog T0","(T0.Doc_status = '1' OR T0.Resign = '1') and T0.user_team LIKE '".$teamCode."'");
	$returnOSLP = "(";
	while($DataOSLP = mysql_fetch_object($getOSLP)){
		$returnOSLP = $returnOSLP.$DataOSLP->slpCode." OR ";
	}
	$returnOSLP = substr($returnOSLP,0,($returnOSLP-4)).")";
	return($returnOSLP);
}

function WhereIN($data){
	
	$data = str_replace("OSLP.[SlpCode] = ","",$data);
	$data = str_replace("(","",$data);
	$data =  str_replace(")","",$data);
	$data = strtoupper($data);
	$data =  str_replace(" OR ",",",$data);
	$data = "(".$data.")";
	return($data);
}

function dateSQL($dateCon){
	$dateAdd = str_replace('/', '-',$dateCon);
	$newDate = date("Y-m-d", strtotime($dateAdd));
	return($newDate);
}

function textMont($numMount){
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
	
	//$utf8x = $tis;
  return $utf8x;
} 
function aonutf8($tis) {
	
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
	
	//$utf8x = $tis;
  return $utf8x;
} 
function lastdate($xMonth,$xYear){
	$caldate = $xYear."-".$xMonth."-".cal_days_in_month(CAL_GREGORIAN,$xMonth,$xYear);
	return $caldate;
}
function calpass($uname,$dx,$mx){
	$xd=strtolower(substr($uname,0,3));
	//$md=date("dm",strtotime($birth));
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
function calBobus($dateCal){
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

    if (strtotime($dateCHK) >= strtotime($dateCal)){
		$x5 = "<input type='checkbox' disabled checked>";
	}
	return $x5;
}
function fullMonth($mc){
	switch ($mc){
		case '1':
		    $mc_mou = "มกราคม";	
			break;
		case '2':
			$mc_mou = "กุมภาพันธ์";
			break;
		case '3':
			$mc_mou = "มีนาคม";	
			break;
		case '4':
			$mc_mou = "เมษายน";
			break;
		case '5':
			$mc_mou = "พฤษภาคม";	
			break;
		case '6':
			$mc_mou = "มิถุนายน";	
			break;
		case '7':
			$mc_mou = "กรกฎาคม";	
			break;
		case '8':
			$mc_mou = "สิงหาคม";
			break;
		case '9':
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
	return $mc_mou;
}

function lastTXTNO($taxtype,$taxMonth){
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$row = $getdata->my_sql_show_rows("paytax","TaxCat = '".$taxtype."' AND VatMonth = '".$taxMonth."' AND Status = 1");
	if ($row != 0){
		$sql1 = "SELECT BookNo FROM paytax WHERE TaxCat = '".$taxtype."' AND VatMonth = '".$taxMonth."' AND Status = 1 ORDER BY BookNo DESC LIMIT 1";
		$getVAT = $getdata->MySQL_SelectX($sql1);
		$DataList = mysql_fetch_object($getVAT);
		$cat = substr($DataList->BookNo,0,8);
		$Run = substr($DataList->BookNo,-3);
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
	$getdata = new clear_db();
	$getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$getdata->my_sql_set_utf8();
	$WeekEND = 1;
	while (($WeekEND == 1) || date('N',strtotime($today)) == '7'){
		$today =  date("Y-m-d",strtotime("-1 days",strtotime($today)));
		$WeekEND=$getdata->my_sql_show_rows("annual_holiday","Holiday_date = '".$today."'");
	}
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
			if($i==($strlen-1) AND $n==1){$convert .= 'เอ็ด';} 
			elseif($i==($strlen-2) AND $n==2){$convert .= 'ยี่';} 
			elseif($i==($strlen-2) AND $n==1){$convert .= '';} 
			else{ $convert .= $txtnum1[$n];} 
			$convert .= $txtnum2[$strlen-$i-1]; 
        } 
    } 
    $convert .= 'สตางค์'; 
    } 
    return $convert; 
}
function MainPath(){
	return "mk";
}
function ConToInt($x){
	return intval(str_replace(",","",$x));
}
function DivZero($x,$y){
    if ($y == 0){
        return 0;
    }else{
        return $x/$y;
    }
}
function chk0($x,$y){
    if ($x == 0){
        return " - ";
    }else{
        return number_format($x,$y);
    }

}

?>
