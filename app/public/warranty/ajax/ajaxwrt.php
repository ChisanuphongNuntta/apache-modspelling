<?php
include('../../core/config.core.php');
include('../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();
$output = '';
// Database
// http://192.168.1.9:8080/phpmyadmin/
// uer: kbi
// pass: p@ssw0rd!
$current_timestamp_by_mktime = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$timestamp = date("Y-m-d h:i:sa", $current_timestamp_by_mktime);
// Page 1
if ($_GET['a'] == 'page1') {
    $stPhone = CheckPhoneNumber($_POST['phone']);
    if ($stPhone != '') {
        $thisName = $_POST['name'];
        $thisEmail = $_POST['email'];
        $thisAge = $_POST['age'];
        $thisPhone =  $stPhone;
        $thisLine = $_POST['line'];
        $thisGender = $_POST['gender'];
        $thisJob = $_POST['job'];
        if ($thisName != '' && $thisAge != '' && $thisPhone) {
            $arrCol['profile_name'] =  $thisName;
            $arrCol['profile_timestamp'] =  $timestamp;

            if (isset($_SESSION['statusPage1'])) {
                // เคย submit แล้ว
                $statusPage1 = $_SESSION['statusPage1'];
                $sql4 = "UPDATE `wrt_profile` SET `profile_name`='$thisName',`profile_email`='$thisEmail',`profile_age`='$thisAge',`profile_phone`='$thisPhone',`profile_lineId`='$thisLine',`profile_gender`='$thisGender',`profile_job`='$thisJob',`profile_timestamp`='$timestamp' WHERE `profile_id` = $statusPage1";
                MySQLUpdate($sql4);
            } else {
                // ยังไม่เคย submit
                $sql1 = "INSERT INTO wrt_profile (profile_id, profile_name, profile_email, profile_age, profile_phone, profile_lineId, profile_gender, profile_job, profile_timestamp)
                    VALUES (NULL, '$thisName', '$thisEmail', '$thisAge', '$thisPhone', '$thisLine', '$thisGender', '$thisJob', '$timestamp')";
                $statusInsert = MySQLInsert($sql1);
                $_SESSION['statusPage1'] = $statusInsert;
            }
        }
        $output = 'success';
    } else {
        $output = '';
    }
}
// Page 2
if ($_GET['a'] == 'page2') {
    $thisDate = $_POST['date'];
    $thisObjective = $_POST['objective'];
    $thisMarket = '';
    $thisBranch = $_POST['branch'];
    if (isset($_SESSION['statusPage1'])) {
        if ($_POST['market'] == 0) {
            $thisMarket = $_POST['otherTxt'];
        } else {
            $thisMarket = $_POST['market'];
        }
        if (isset($_SESSION['statusPage2'])) {
            // เคย submit แล้ว
            $statusPage2 = $_SESSION['statusPage2'];
            $sql2 = "UPDATE `wrt_buyproducts` SET `buy_date`='$thisDate',`buy_objective`='$thisObjective',`buy_market`='$thisMarket',`buy_branch`='$thisBranch',`buy_timestamp`='$timestamp' WHERE `buy_id` = '$statusPage2'";
            MySQLUpdate($sql2);
        } else {
            // ยังไม่เคย submit
            $statusPage1 = $_SESSION['statusPage1'];
            $sql1 = 
                "INSERT INTO `wrt_buyproducts`(
                    `buy_id`, `profile_id`, `buy_date`, `buy_objective`, `buy_market`, `buy_branch`,`buy_timestamp`
                ) VALUES (
                    Null, '$statusPage1','$thisDate','$thisObjective','$thisMarket','$thisBranch','$timestamp'
                )";
            $statusInsert = MySQLInsert($sql1);
            $_SESSION['statusPage2'] = $statusInsert;
        }
        $output = 'success';
    } else {
        $output = '';
    }
}
// Page 3
if ($_GET['a'] == 'page3') {
    $thisNameProduct = $_POST['nameProduct'];
    $thisSerialNumber = $_POST['serialNumber'];
    $thisProduct = $_POST['product'];
    $thisBrand = '';
    if ($_POST['brand'] == 'other') {
        $thisBrand = $_POST['otherTxt'];
    } else {
        $thisBrand = $_POST['brand'];
    }
    if (isset($_SESSION['statusPage2'])) {
        $statusPage2 = $_SESSION['statusPage2'];
        $sql2 = "UPDATE `wrt_buyproducts` SET `buy_brand`='$thisBrand',`buy_product`='$thisProduct',`buy_nameProduct`='$thisNameProduct',`buy_serialNumber`='$thisSerialNumber',`buy_timestamp`='$timestamp' WHERE `buy_id` = '$statusPage2'";
        MySQLUpdate($sql2);
        $_SESSION['statusPage3'] = 'update success';
        $output = 'success';
    } else {
        $output = '';
    }
}
/*  callBranch */
if ($_GET['a'] == 'callBranch') {
    $sql1 = "SELECT CardCode, case when BName = ''  OR BName IS NULL then CardName else BName end as BName FROM `ocrd` WHERE MTGroup = '" . $_POST['branch'] . "' AND CardStatus = 'A'";
    $getName = MySQLSelectX($sql1);
    while ($ShowName = mysqli_fetch_array($getName)) {
        $output .= "<option value='" . $ShowName['CardCode'] . "'>" . $ShowName['BName'] .  " </option>";
    }
}
/*  callProduct*/
if ($_GET['a'] == 'callProduct') {
    $sql = "SELECT T0.Code, T0.Name FROM [dbo].[@ITEMGROUP1] T0 WHERE T0.Code != 'B00002' AND T0.Code NOT LIKE 'B0002%'";
    $SapQRY = SAPSelect($sql);
    while ($SapRST = odbc_fetch_array($SapQRY)) {
        $output .= "<div class='form-check'>" .
                        "<input id=" . $SapRST['Code'] . " name='product' type='radio' class='form-check-input' value=" . $SapRST['Code'] . " required=''>
                        <label class='form-check-label'>" . conutf8($SapRST['Name']) . "</label>
                    </div>";
    }
}
/*  callProvince*/
if ($_GET['a'] == 'callProvince') {
    // $output = 'callProvince';
    $sql1 = "SELECT `code`, `name_th`  FROM `provinces` ";
    $getSQL = MySQLSelectX($sql1);
    while ($getValues = mysqli_fetch_array($getSQL)) {
        $output .= "<option value='" . $getValues['code'] . "'>" . $getValues['name_th'] .  " </option>";
    }
}
/*  callModal*/
if ($_GET['a'] == 'modal') {

    if (isset($_SESSION['statusPage2'])) {
        $statusPage2 = $_SESSION['statusPage2'];
        $status = $_POST['status'];
        $sql = "UPDATE `wrt_buyproducts` SET `status`='$status' WHERE `buy_id` = '$statusPage2'";
        MySQLUpdate($sql);
        $output = $_POST['status'];
        session_destroy();
    }
}
/* Check PhoneNumber */
function CheckPhoneNumber($phone)
{
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    $phone_to_check = str_replace("-", "", $filtered_phone_number);
    if (strlen($phone_to_check) != 10) {
        return '';
    } else {
        return $phone_to_check;
    }
}

$arrCol['output'] = $output;
array_push($resultArray, $arrCol);
echo json_encode($resultArray);
