<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();

if($_GET['p'] == "GetOwnList") {
    $SQL1 = "SELECT T0.[empID], (T0.[firstName]+' '+T0.[lastName]) AS [EmpName] FROM OHEM T0 WHERE T0.[Active] = 'Y' ORDER BY [EmpName]";
    $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll(PDO::FETCH_ASSOC);
    foreach($RST1 as $key => $data) {
        $inval['OwnList'][$key]['empID']   = $data['empID'];
        $inval['OwnList'][$key]['EmpName'] = SapTH($data['EmpName']);
    }
}

if($_GET['p'] == "GetUserList") {
    $filt_t = $_POST['filt_t'];
    $filt_s = $_POST['filt_s'];

    if(($filt_t != "ALL" && $filt_t != "") || ($filt_s != "ALL" && $filt_s != "")) {
        $SQL1WHR1 = "WHERE";

        if($filt_t != "ALL" && $filt_t != "") { $SQL1WHR1 .= " T1.DeptCode = '$filt_t'"; } else { $SQL1WHR1 .= ""; }
        if(($filt_t != "ALL" && $filt_t != "") && ($filt_s != "ALL" && $filt_s != "")) { $SQL1WHR1 .= " AND"; } else { $SQL1WHR1 .= ""; }
        if($filt_s != "ALL" && $filt_s != "") { $SQL1WHR1 .= " T0.UserStatus = '$filt_s'"; } else { $SQL1WHR1 .= ""; }
    } else {
        $SQL1WHR1 = "";
    }

    $SQL1 = "SELECT T0.*, T1.LvName, T2.DeptName FROM users T0 LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode LEFT JOIN departments T2 ON T1.DeptCode = T2.DeptCode $SQL1WHR1 ORDER BY T1.DeptCode, T1.LvClass, T0.DateCreate ASC";
    // echo $SQL1;
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        foreach($RST1 as $key => $Row) {
            $avatar = (isset(glob("../../images/profile/".$Row['uKey'].".*")[0])) ? "../images/profile/".$Row['uKey'].".jpg?v=".date("YmdHis") : "../images/profile/no_photo.jpg";

            $inval[$key]['No']         = $key+1;
            $inval[$key]['uKey']       = $Row['uKey'];
            $inval[$key]['EmpCode']    = $Row['EmpCode'];
            $inval[$key]['FullName']   = "<img src='$avatar' alt='".$Row['UserName']."' class='theme-color-green-img img-fluid avatar avatar-50 avatar-rounded'> ".$Row['TH_uFirstName']." ".$Row['TH_uLastName'];
            $inval[$key]['LvName']     = $Row['LvName'];
            $inval[$key]['DeptName']   = $Row['DeptName'];
            $inval[$key]['UserName']   = $Row['UserName'];
            $inval[$key]['UserStatus'] = $Row['UserStatus'];

            $inval[$key]['txtStatus']  = ($Row['UserStatus'] == "A") ? "<span class='text-success'><i class='far fa-check-circle fa-fw fa-1x'></i> Active</span>" : "<span class='text-danger'><i class='far fa-times-circle fa-fw fa-1x'></i> Inactive</span>";

            /* Edit Profile */
            if($_SESSION['USERNAME'] == "admin") {
                $inval[$key]['BTN'] = "<button type='button' class='btn btn-info btn-sm btn-edit' title='แก้ไขข้อมูล' onclick=\"EditMember('".$Row['uKey']."');\"><i class='fas fa-user-edit fa-fw fa-1x'></i></button> ";
            } elseif($_SESSION['LVCODE'] == "I0001" && $Row['UserName'] == "admin") {
                $inval[$key]['BTN'] = "<button type='button' class='btn btn-info btn-sm' disabled><i class='fas fa-user-edit fa-fw fa-1x'></i></button> ";
            } elseif($_SESSION['LVCODE'] == "I0001") {
                $inval[$key]['BTN'] = "<button type='button' class='btn btn-info btn-sm btn-edit' title='แก้ไขข้อมูล' onclick=\"EditMember('".$Row['uKey']."');\"><i class='fas fa-user-edit fa-fw fa-1x'></i></button> ";
            } else {
                $inval[$key]['BTN'] = "";
            }

            /* Active Toggle */
            if(($_SESSION['UKEY'] == $Row['uKey']) || ($_SESSION['USERNAME'] != "admin" && $Row['UserName'] == "admin")) {
                $inval[$key]['BTN'] .= "<button type='button' class='btn btn-outline-secondary btn-sm' disabled><i class='fas fa-lock fa-fw fa-1x'></i></button>";
            } else {
                $inval[$key]['BTN'] .= ($Row['UserStatus'] == "A") ? "<button type='button' class='btn btn-outline-secondary btn-sm' title='เปลี่ยนสถานะผู้ใช้' onclick=\"ActiveMember('".$Row['uKey']."',0);\"><i class='fas fa-lock fa-fw fa-1x'></i></button>" : "<button type='button' class='btn btn-outline-secondary btn-sm' onclick=\"ActiveMember('".$Row['uKey']."',1);\"><i class='fas fa-unlock fa-fw fa-1x'></i></button>";
            }
        }
    }
}

if($_GET['p'] == "GetDeptCode") {
    $SQL1 = "SELECT T0.* FROM departments T0 ORDER BY T0.DeptCode";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
        $inval['Row'] = 0;
        foreach($RST1 as $key => $Row) {
            $inval[$key]['DeptCode'] = $Row['DeptCode'];
            $inval[$key]['DeptName'] = $Row['DeptName'];
            $inval['Row']++;
        }

    }
}

if($_GET['p'] == "GetLvCode") {
    $DeptCode = $_POST['DeptCode'];
    $SQL1 = "SELECT T0.* FROM positions T0 WHERE T0.DeptCode = '$DeptCode' ORDER BY T0.LvClass ASC";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
        $inval['Row'] = 0;
        foreach($RST1 as $key => $Row) {
            $inval[$key]['LvCode'] = $Row['LvCode'];
            $inval[$key]['LvName'] = $Row['LvName'];
            $inval['Row']++;
        }
    }
}

if($_GET['p'] == "ChkUsnm") {
    $usnm = $_POST['usnm'];
    $SQL1 = "SELECT COUNT(T0.uKey) AS 'Rows' FROM users T0 WHERE T0.UserName = '$usnm'";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        $Rows = 0;
        foreach($RST1 as $key => $Row) {
            $Rows = $Row['Rows'];
        }
        $inval['Status'] = ($Rows > 0) ? "ERR" : "OK";
    }
}

if($_GET['p'] == "SaveMember") {
    $txt_uKey = ($_POST['txt_uKey'] != "") ? $_POST['txt_uKey'] : "";
    $TH_uFirstName = ($_POST['txt_TH_uFirstName'] != "") ? $_POST['txt_TH_uFirstName'] : "";
    $TH_uLastName = ($_POST['txt_TH_uLastName'] != "") ? $_POST['txt_TH_uLastName'] : "";
    $uNickName = ($_POST['txt_uNickName'] != "") ? $_POST['txt_uNickName'] : "";
    $EN_uFirstName = ($_POST['txt_EN_uFirstName'] != "") ? $_POST['txt_EN_uFirstName'] : "";
    $EN_uLastName = ($_POST['txt_EN_uLastName'] != "") ? $_POST['txt_EN_uLastName'] : "";
    $uWorkStartDate = ($_POST['txt_uWorkStartDate'] != "") ? date("Y-m-d",strtotime($_POST['txt_uWorkStartDate'])) : "";
    $uGender = ($_POST['txt_uGender'] != "") ? $_POST['txt_uGender'] : "";
    $EmpCode = ($_POST['txt_EmpCode'] != "") ? $_POST['txt_EmpCode'] : "";
    // $OwnerCode = ($_POST['txt_OwnerCode'] != "") ? $_POST['txt_OwnerCode'] : "";
    if(!isset($_GET['page'])) {
        $LvCode = ($_POST['txt_LvCode'] != "") ? $_POST['txt_LvCode'] : "";
        $UserName = ($_POST['txt_UserName'] != "") ? $_POST['txt_UserName'] : "";
        if($txt_uKey == "") {
            $UserPswd = ($_POST['txt_UserPswd'] != "") ? password_hash($_POST['txt_UserPswd'], PASSWORD_DEFAULT) : "";
        }else{
            $UserPswd = NULL;
        }
    }else{
        $UserName = NULL;
        $UserPswd = ($_POST['txt_password'] != "") ? password_hash($_POST['txt_password'], PASSWORD_DEFAULT) : NULL;
        $LvCode = $_SESSION['LVCODE'];
    }
    $uMobileNo = ($_POST['txt_uMobileNo'] != "") ? str_replace("-","",$_POST['txt_uMobileNo']) : "";
    $uEmailAddr = ($_POST['txt_uEmailAddr'] != "") ? $_POST['txt_uEmailAddr'] : "";
    $uLineID = ($_POST['txt_uLineID'] != "") ? $_POST['txt_uLineID'] : "";
    $uBirthdate = ($_POST['txt_uBirthdate'] != "") ? date("Y-m-d",strtotime($_POST['txt_uBirthdate'])) : "";
    

    if($txt_uKey == "") {
        $uKey = md5($UserName);

        $SQL1 =
            "INSERT INTO users SET
                uKey = :uKey,
                EmpCode = NULLIF(:EmpCode,''),
                TH_uFirstName = :TH_uFirstName,
                TH_uLastName = :TH_uLastName,
                EN_uFirstName = :EN_uFirstName,
                EN_uLastName = :EN_uLastName,
                uNickName = NULLIF(:uNickName,''),
                uGender = NULLIF(:uGender,''),
                uBirthdate = NULLIF(:uBirthdate,''),
                uWorkStartDate = NULLIF(:uWorkStartDate,''),
                uMobileNo = NULLIF(:uMobileNo,''),
                uEmailAddr = NULLIF(:uEmailAddr,''),
                uLineID = NULLIF(:uLineID,''),
                UserName = :UserName,
                UserPswd = :UserPswd,
                LvCode = :LvCode,
                uKeyCreate = :uKeyCreate";
    } else {
        $SQL_UserPswd = ($UserPswd != NULL) ? "UserPswd = :UserPswd," : "";

        $SQL1 = 
            "UPDATE users SET
                EmpCode = NULLIF(:EmpCode,''),
                TH_uFirstName = :TH_uFirstName,
                TH_uLastName = :TH_uLastName,
                EN_uFirstName = :EN_uFirstName,
                EN_uLastName = :EN_uLastName,
                uNickName = NULLIF(:uNickName,''),
                uGender = NULLIF(:uGender,''),
                uBirthdate = NULLIF(:uBirthdate,''),
                uWorkStartDate = NULLIF(:uWorkStartDate,''),
                uMobileNo = NULLIF(:uMobileNo,''),
                uEmailAddr = NULLIF(:uEmailAddr,''),
                uLineID = NULLIF(:uLineID,''),
                $SQL_UserPswd
                LvCode = :LvCode,
                uKeyUpdate = :uKeyUpdate
            WHERE uKey = :uKey";
    }
//echo $SQL1;
    $QRY1 = DBConnect("APP")->prepare($SQL1);
    ($txt_uKey == "") ? $QRY1->bindparam(":uKey", $uKey) : $QRY1->bindparam(":uKey", $txt_uKey) ;
    ($txt_uKey == "") ? $QRY1->bindparam(":UserName", $UserName) : null ;
    if($txt_uKey == ""){
        $QRY1->bindparam(":UserPswd", $UserPswd);
    }else{
        if($UserPswd != NULL) {
            $QRY1->bindparam(":UserPswd", $UserPswd); 
        }
    }
    ($txt_uKey == "") ? $QRY1->bindparam(":uKeyCreate", $_SESSION['UKEY']) : $QRY1->bindparam(":uKeyUpdate", $_SESSION['UKEY']) ;


    $QRY1->bindparam(":EmpCode", $EmpCode);
    //$QRY1->bindparam(":OwnerCode", $OwnerCode);
    $QRY1->bindparam(":TH_uFirstName", $TH_uFirstName);
    $QRY1->bindparam(":TH_uLastName", $TH_uLastName);
    $QRY1->bindparam(":EN_uFirstName", $EN_uFirstName);
    $QRY1->bindparam(":EN_uLastName", $EN_uLastName);
    $QRY1->bindparam(":uNickName", $uNickName);
    $QRY1->bindparam(":uGender", $uGender);
    $QRY1->bindparam(":uBirthdate", $uBirthdate);
    $QRY1->bindparam(":uWorkStartDate", $uWorkStartDate);
    $QRY1->bindparam(":uMobileNo", $uMobileNo);
    $QRY1->bindparam(":uEmailAddr", $uEmailAddr);
    $QRY1->bindparam(":uLineID", $uLineID);
    $QRY1->bindparam(":LvCode", $LvCode);
    $RST1 = $QRY1->execute();
    $inval['Status'] = (!$RST1) ? "ERR" : "OK";

    if($inval['Status'] == "OK") {
        /* INSERT USER PHOTO */
        if(trim($_FILES['txt_uPhoto']['tmp_name']) != "") {
            $new_img = ($txt_uKey == "") ? $ukey : $txt_uKey;
            $DataImage = $_POST["Image"];
            $image_array_1 = explode(";", $DataImage);
            $image_array_2 = explode(",", $image_array_1[1]);
            $Image_decode = base64_decode($image_array_2[1]);
            $imageName = $new_img.'.jpg';
            if(file_exists("../../images/profile/".$new_img.".jpg")) {
                unlink("../../images/profile/".$new_img.".jpg");
            }
            file_put_contents("../../images/profile/".$imageName, $Image_decode);
        }
        /* INSERT SIGNATURE */
        if(trim($_FILES['txt_uSign']['tmp_name']) != "") {
            $old_img = $_FILES['txt_uSign']['tmp_name'];
            $new_img = ($txt_uKey == "") ? $ukey : $txt_uKey;

            copy($_FILES['txt_uSign']['tmp_name'], "../../images/signature/".$new_img.".jpg");

            $img_width = 128;
            $img_size  = GetimageSize($old_img);

            if($img_size[0] > 128) {
                $img_height = round($img_width * $img_size[1] / $img_size[0]);
            } else {
                $img_height = $img_size[1];
            }

            $img_origin = ImageCreateFromJPEG($old_img);
            $photoX = ImagesX($img_origin);
            $photoY = ImagesY($img_origin);
            $img_final = ImageCreateTrueColor($img_width, $img_height);
            ImageCopyResampled($img_final, $img_origin, 0, 0, 0, 0, $img_width+1, $img_height+1, $photoX, $photoY);
            ImageJPEG($img_final,"../../images/signature/".$new_img.".jpg");
            ImageDestroy($img_origin);
            ImageDestroy($img_final);
        }

        /* rewrite session */
        if($txt_uKey != "") {
            $SQL2 = "SELECT T0.EmpCode, CONCAT(T0.TH_uFirstName,' ',T0.TH_uLastName) AS 'EmpName', T0.uNickName AS 'NickName', T0.LvCode, T1.LvName, T1.DeptCode FROM users T0 LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode WHERE T0.uKey = '$txt_uKey' LIMIT 1";
            $RST2 = DBConnect("APP")->query($SQL2)->fetchAll();
            foreach($RST2 as $RowData) {
                $_SESSION['EMPCODE']  = $RowData['EmpCode'];
                $_SESSION['EMPNAME']  = $RowData['EmpName'];
                $_SESSION['NICKNAME'] = $RowData['NickName'];
                $_SESSION['LVCODE']   = $RowData['LvCode'];
                $_SESSION['LVNAME']   = $RowData['LvName'];
                $_SESSION['DEPTCODE'] = $RowData['DeptCode'];
            }
        }
    }

    
}

if($_GET['p'] == "GetProfile") {
    $uKey = $_POST['u'];
    $SQL1 = "SELECT T0.*, T1.DeptCode FROM users T0 LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode WHERE T0.uKey = '$uKey' LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";

        foreach($RST1 as $Row) {
            $inval['uKey'] = $Row['uKey'];
            $inval['TH_uFirstName'] = $Row['TH_uFirstName'];
            $inval['TH_uLastName'] = $Row['TH_uLastName'];
            $inval['uNickName'] = $Row['uNickName'];
            $inval['EN_uFirstName'] = $Row['EN_uFirstName'];
            $inval['EN_uLastName'] = $Row['EN_uLastName'];
            $inval['uGender'] = $Row['uGender'];
            $inval['EmpCode'] = $Row['EmpCode'];
            // $inval['OwnerCode'] = $Row['OwnerCode'];
            $inval['DeptCode'] = $Row['DeptCode'];
            $inval['LvCode'] = $Row['LvCode'];
            $inval['uMobileNo'] = $Row['uMobileNo'];
            $inval['uEmailAddr'] = $Row['uEmailAddr'];
            $inval['uLineID'] = $Row['uLineID'];
            $inval['uWorkStartDate'] = date("Y-m-d",strtotime($Row['uWorkStartDate']));
            $inval['uBirthdate'] = date("Y-m-d",strtotime($Row['uBirthdate']));
            $inval['UserName'] = $Row['UserName'];
            $inval['UserPswd'] = "**********";
        }
    }
}

if($_GET['p'] == "ActiveMember") {
    /* Type 0 = Toggle to Inactive // 1 = Toggle to Active */
    $uKey = $_POST['u'];
    $Type = ($_POST['t'] == "0") ? "I" : "A";

    $SQL1 = "UPDATE users SET UserStatus = :Type, uKeyUpdate = :uKeyUpdate, DateUpdate = NOW() WHERE uKey = :uKey";
    $QRY1 = DBConnect("APP")->prepare($SQL1);
    $QRY1->bindparam(":Type", $Type);
    $QRY1->bindparam(":uKeyUpdate", $_SESSION['UKEY']);
    $QRY1->bindparam("uKey", $uKey);
    $RST1 = $QRY1->execute();

    $inval['Status'] = (!$RST1) ? "ERR" : "OK";

}


array_push($JSON,$inval);
echo json_encode($JSON);
?>