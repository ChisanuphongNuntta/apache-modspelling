<?php
date_default_timezone_set('Asia/Bangkok');
require("../core/config.core.php");
include("../core/functions.core.php");
session_start();
if($_SESSION['UserName']==NULL ){
	echo '<script>window.location="../"</script>';
}
?>
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
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EUROX Force : WMS</title>

    <link rel="stylesheet" href="../css/main/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link href="../image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />
    <script src="../../../js/fontawesome/fontawesome-v6.js" crossorigin="anonymous"></script>
    
    <script src="../js/jquery-min.js" type="text/javascript"></script>
</head>
<body>
    <div class="sticky-top">
        <div class="d-flex align-items-center justify-content-between" style='background-color: #880000;'>
            <div class='d-flex align-items-center'>
                <a href="main.php"><img src="../image/logo/eurox_force_2.png" class='p-2' style='width: 150px;'></a>
            </div>
            <div id='ManuMB'>
                <button class='btn pe-3' data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bars text-light" style='font-size: 17.5px;'></i></button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:void(0);"><span><i class="fas fa-user"></i> <?php echo $_SESSION['uName']." ".$_SESSION['uLastName']; ?></span></a></li>
                    <li><a class="dropdown-item" href="../kbi/main.php"><span><i class="fas fa-external-link-alt"></i> กลับไประบบหลัก</span></a></li>
                    <li><hr class="dropdown-divider m-0"></li>
                    <li><a class="dropdown-item" href="../core/logout.core.php"><span><i class="fas fa-power-off"></i> ออกจากระบบ</span></a></li>
                </ul>
            </div>
            <div id='ManuPC'>
                <div class='pe-3'>
                    <a href="javascript:void(0);" class='text-light'><i class="fas fa-user"></i>&nbsp;
                        <span id=''><?php echo $_SESSION['uName']." ".$_SESSION['uLastName']; ?></span>
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="../../../kbi/main.php" class='text-light'><i class="fas fa-external-link-alt"></i>&nbsp;
                        <span id=''>กลับไประบบหลัก</span>
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="../core/logout.core.php" class='text-light'><i class="fas fa-power-off"></i>&nbsp;
                        <span id='' class='taxt-center'>ออกจากระบบ</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class='text-center pt-2'>
        <div class='box-content'>
            <button class='btn btn-sm content-btn' style='background-color: #fff;' onclick="location.href='picking/picking.php'">
                <img class='content-img' src="../image/img_ksy/menu-icon-01.jpg">
                <div class="">
                    <span class='content-font'>หยิบสินค้า</span>
                </div>
            </button>
            <button class='btn btn-sm content-btn' style='background-color: #fff;' onclick="location.href='restook/restook.php'">
                <img class='content-img' src="../image/img_ksy/menu-icon-02.jpg">
                <div class="">
                    <span class='content-font'>เติมสินค้า</span>
                </div>
            </button>
        </div>
        <div class='box-content'>
            <button class='btn btn-sm content-btn' style='background-color: #fff;' onclick="location.href='transfer/transfer.php'">
                <img class='content-img' src="../image/img_ksy/menu-icon-03.jpg">
                <div class="">
                    <span class='content-font'>โอนสินค้า</span>
                </div>
            </button>
            <button class='btn btn-sm content-btn' style='background-color: #fff;' onclick="location.href='loading/loading.php'">
                <img class='content-img' src="../image/img_ksy/menu-icon-04.jpg">
                <div class="">
                    <span class='content-font'>โหลดสินค้าขึ้น</span>
                </div>
            </button> 
        </div> 
        <div class='box-content'>
            <button class='btn btn-sm content-btn' style='background-color: #fff;'> <!-- onclick="location.href='unloading/unloading.php'" -->
                <img class='content-img' src="../image/img_ksy/menu-icon-08.jpg">
                <div class="">
                    <span class='content-font'>โหลดสินค้าลง</span>
                </div>
            </button>   
            <button class='btn btn-sm content-btn' style='background-color: #fff;' onclick="location.href='received/received.php'">
                <img class='content-img' src="../image/img_ksy/menu-icon-05.jpg">
                <div class="">
                    <span class='content-font'>ตรวจรับสินค้า</span>
                </div>
            </button> 
        </div>   
        <div class='box-content'>
            <button class='btn btn-sm content-btn' style='background-color: #fff;' onclick="location.href='report/userinfo.php'">
                <img class='content-img' src="../image/img_ksy/menu-icon-06.jpg">
                <div class="">
                    <span class='content-font'>รายงานประจำวัน (TEST)</span>
                </div>
            </button>   
            <button class='btn btn-sm content-btn' style='background-color: #fff;' <?php if($_SESSION['DeptCode'] == "DP002") { ?> onclick="location.href='setting/setting.php'" <?php } ?>>
                <img class='content-img' src="../image/img_ksy/menu-icon-07.jpg">
                <div class="">
                    <span class='content-font'>ตั้งค่า</span>
                </div>
            </button>   
        </div> 
    </div>
   
    <!-- FOOTER -->
    <p class='footer-style'>© <?php echo date("Y"); ?> บจ. คิงบางกอก อินเตอร์เทรด สงวนลิขสิทธิ์</p>

    <script>
        
    </script>

    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
</body>
</html>