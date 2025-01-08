<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();

if($_GET['p'] == 'UploadProfil') {
    $DataImage = $_POST["Image"];
    $image_array_1 = explode(";", $DataImage);
    $image_array_2 = explode(",", $image_array_1[1]);
    $Image_decode = base64_decode($image_array_2[1]);
    $imageName = $_SESSION['UKEY'].'.jpg';
    if(file_exists("../../images/profile/".$_SESSION['UKEY'].".jpg")) {
        unlink("../../images/profile/".$_SESSION['UKEY'].".jpg");
    }
    file_put_contents("../../images/profile/".$imageName, $Image_decode);
}

if($_GET['p'] == 'GetProfile') {
    $SQL = 
        "SELECT T0.TH_uFirstName, T0.TH_uLastName, T0.uNickName, T0.EN_uFirstName, T0.EN_uLastName, T0.uGender, T0.uWorkStartDate, 
            T0.uBirthdate, T0.EmpCode, T2.DeptName, T1.LvName, T0.uMobileNo, T0.uEmailAddr, T0.uLineID
        FROM users T0
        LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode
        LEFT JOIN departments T2 ON T1.DeptCode = T2.DeptCode
        WHERE T0.uKey = '".$_SESSION['UKEY']."' LIMIT 1";
    $RST = DBConnect("APP")->query($SQL)->fetchAll();
    foreach($RST as $key=>$data) {
        $inval['TH_uFirstName']  = $data['TH_uFirstName'];
        $inval['TH_uLastName']   = $data['TH_uLastName'];
        $inval['uNickName']      = $data['uNickName'];
        $inval['EN_uFirstName']  = $data['EN_uFirstName'];
        $inval['EN_uLastName']   = $data['EN_uLastName'];
        $inval['uGender']        = $data['uGender'];
        $inval['uWorkStartDate'] = $data['uWorkStartDate'];
        $inval['uBirthdate']     = $data['uBirthdate'];
        $inval['EmpCode']        = $data['EmpCode'];
        $inval['DeptName']       = $data['DeptName'];
        $inval['LvName']         = $data['LvName'];
        $inval['uMobileNo']      = $data['uMobileNo'];
        $inval['uEmailAddr']     = $data['uEmailAddr'];
        $inval['uLineID']        = $data['uLineID'];
    }
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>