<?php
date_default_timezone_set('Asia/Bangkok');
require("../core/config.core.php");
include("../core/functions.core.php");
session_start();
if($_SESSION['UserName'] == NULL ){
	echo '<script>window.location="../ceo"</script>';
    exit;
} else {
    $ChkSession = "SELECT T0.ID, TIMESTAMPDIFF(MINUTE, T0.LogDate ,NOW()) AS 'Mins' FROM loglogin T0 WHERE T0.UserLog = '".$_SESSION['ukey']."' ORDER BY T0.ID DESC LIMIT 1";
    $ChkSession = MySQLSelect($ChkSession);
    $TimeDurations = $ChkSession['Mins'];
    if($TimeDurations > 15) {
        echo '<script>window.location="../ceo"</script>';
        exit;
    }
}

$start_year = 2022;
$this_year  = date("Y");
$this_month = date("m");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EUROX Force: Management Report</title>

    <link rel="stylesheet" href="../css/main/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link href="../image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <script src="../js/jquery-min.js" type="text/javascript"></script>
    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
    <style>
        body {
            overflow-x: none;
        }
        @media only screen and (max-width:425px) {
            #ManuPC { display: none; }
            .footer-style {
                font-size: 13px;
                text-align: center;
                padding-top: 10px;
                /* position: fixed;   */
                bottom: 10px;
                width: 100%;
            }
            .box-content {
                display: flex;
                justify-content: center;
                padding-top: 7px;
                padding-left: 5px;
                padding-right: 5px;
            }
            .content-btn {
                width: 100%;
                margin-left: 5px;
                margin-right: 5px;
                margin-bottom: 4px;
            }
            .content-img {
                width: 65%;
                /* width: 75px; */
            }
            .content-font {
                font-size: 13px;
                font-weight: bold;
            }
        }
        @media (min-width:426px) and (max-width:850px) {
            #ManuPC { display: none; }
            .footer-style {
                font-size: 13px;
                text-align: center;
                padding-top: 10px;
                /* position: fixed;   */
                bottom: 10px;
                width: 100%;
            }
            .box-content {
                display: inline;
            }
            .content-btn {
                width: 30%;
                margin-left: 4px;
                margin-right: 4px;
                margin-bottom: 8px;
            }
            .content-img {
                width: 65%;
            }
            .content-font {
                font-size: 13px;
                font-weight: bold;
            }
        }
        @media (min-width:851px) {
            #ManuMB { display: none; }
            .footer-style {
                font-size: 13px;
                text-align: center;
                padding-top: 10px;
                position: fixed;  
                bottom: 10px;
                width: 100%;
            }
            .box-content {
                display: inline;
            }
            .content-btn {
                width: 20%;
                margin-left: 4px;
                margin-right: 4px;
                margin-bottom: 8px;
            }
            .content-img {
                width: 80%;
            }
            .content-font {
                font-size: 15px;
                font-weight: bold;
            }
        }

        .hrprice {
            border: none;
            border-top: 3px double #333;
            color: #333;
            overflow: visible;
            text-align: center;
            height: 5px;
        }

        .hrprice:after {
            background: #fff;
            content: '$';
            padding: 0 4px;
            position: relative;
            top: -11px;
        }
    </style>
</head>
<body style="bg-light">
    <div class="overlay text-center" style="color: #151515;">
        <div>
            <i class="fas fa-spinner fa-pulse fa-fw fa-4x"></i><br/><br/>กำลังโหลด...
        </div>
    </div>
    <!-- Alert Remark -->
    <div class="modal fade" id="ModalAlertRemark" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h1 class="modal-title text-center" id="HeaderModalAlertRemark"></h1>
                    <p id="DetailModalAlertRemark" class="my-3 text-primary"></p>
                    <button type="button" class="btn btn-sm btn-secondary w-25 mt-4" data-bs-dismiss="modal">ตกลง</button>
                </div>
            </div>
        </div>
    </div>
    <div class="sticky-top">
        <div class="d-flex align-items-center justify-content-between" style='background-color: #880000;'>
            <div class='d-flex align-items-center'>
                <a href="main.php"><img src="../image/logo/eurox_force_2.png" class='p-2' style='width: 150px;'></a>
            </div>
            <div id='ManuMB'>
                <button class='btn pe-3' data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bars text-light" style='font-size: 17.5px;'></i></button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="main.php"><span><i class="fas fa-home fa-fw fa-1x"></i> หน้าแรก</span></a></li>
                    <li><a class="dropdown-item" href="?p=salekpi"><span><i class="fas fa-dollar-sign fa-fw fa-1x"></i> รายงานยอดขาย</a></li>
                    <li><a class="dropdown-item" href="?p=receipt"><span><i class="fas fa-money-bill-alt fa-fw fa-1x"></i> รายงานเก็บเงิน</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item" href="javascript:void(0);"><span><i class="fas fa-user fa-fw fa-1x"></i> <?php echo $_SESSION['uName']." ".$_SESSION['uLastName']; ?></span></a></li>
                    <li><a class="dropdown-item" href="?p=repassword"><span><i class="fas fa-key fa-fw fa-1x"></i> เปลี่ยนรหัสผ่าน</span></a></li>
                    <li><a class="dropdown-item" href="../kbi/main.php"><span><i class="fas fa-external-link-alt fa-fw fa-1x"></i> กลับไประบบหลัก</span></a></li>
                    <li><a class="dropdown-item" href="../core/logout.core.php?type=ceo"><span><i class="fas fa-power-off fa-fw fa-1x"></i> ออกจากระบบ</span></a></li>
                </ul>
            </div>
            <div id='ManuPC'>
                <div class='pe-3'>
                    <!-- <a href="javascript:void(0);" class='text-light'>
                        <i class="fas fa-user"></i>&nbsp;
                        <span id=''><?php echo $_SESSION['uName']." ".$_SESSION['uLastName']; ?></span>
                    </a> -->
                    &nbsp;&nbsp;&nbsp;
                    <a href="main.php" class='text-light'>
                        <i class="fas fa-home fa-fw fa-1x"></i>&nbsp;
                        <span id=''>หน้าแรก</span>
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="?p=salekpi" class='text-light'>
                        <i class="fas fa-dollar-sign fa-fw fa-1x"></i>&nbsp;
                        <span id=''>รายงานยอดขาย</span>
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="../../../kbi/main.php" class='text-light'>
                        <i class="fas fa-external-link-alt"></i>&nbsp;
                        <span id=''>กลับไประบบหลัก</span>
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="../core/logout.core.php?type=ceo" class='text-light'><i class="fas fa-power-off"></i>&nbsp;
                        <span id='' class='taxt-center'>ออกจากระบบ</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php 
if(isset($_GET['p'])) {
    switch($_GET['p']) {
        case "salekpi": $filename = "content/ceo_salekpi.php"; break;
        case "receipt": $filename = "content/ceo_receipt.php"; break;
        case "repassword": $filename = "content/ceo_repassword.php"; break;
        default: "content/ceo_index.php"; break;
    }
} else {
    $filename = "content/ceo_index.php";
}
    
    require($filename);
?>
</body>
</html>