<?php 
session_start();
include("../core/functions.core.php");
/* Check Session */
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$page = base64_url_decode($_GET['p']);
$avatar = (isset(glob("../images/profile/".$_SESSION['UKEY'].".*")[0])) ? glob("../images/profile/".$_SESSION['UKEY'].".*")[0] : "../images/profile/no_photo.jpg";
$SQL0 = "SELECT T0.MenuIcon, T0.MenuName FROM menulists T0 WHERE T0.MenuLink = '$page'";
// echo $SQL0;
$RST1 = DBConnect("APP")->query($SQL0)->fetchAll();
foreach($RST1 as $RowData) { $MenuTitle = $RowData['MenuIcon']." ".$RowData['MenuName']; }
?>
<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title><?php echo $_SESSION['SITE']['site_name']; ?> <?php echo TitlePage($page); ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/<?php echo $_SESSION['SITE']['Logo_Img']; ?>">
    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="../assets/css/core/libs.min.css">
    <!-- Aos Animation Css -->
    <link rel="stylesheet" href="../assets/vendor/aos/dist/aos.css">
    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="../assets/css/hope-ui.min.css?v=4.0.0">
    <!-- Custom Css -->
    <link rel="stylesheet" href="../assets/css/custom.min.css?v=4.0.0">
    <!-- Dark Css -->
    <link rel="stylesheet" href="../assets/css/dark.min.css">
    <!-- Customizer Css -->
    <link rel="stylesheet" href="../assets/css/customizer.min.css">
    <!-- RTL Css -->
    <link rel="stylesheet" href="../assets/css/rtl.min.css">
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <!-- Style Datatable -->
    <link rel="stylesheet" href="../assets/js/Datatable/jquery.dataTables.min.css">
    <!-- Selectpicker -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css">
    <!-- Index Css -->
    <?php if(file_exists("../assets/css/style_site_".$_SESSION['SITE']['site_id'].".css")) { ?>
        <link rel="stylesheet" href="../assets/css/style_site_<?php echo $_SESSION['SITE']['site_id']; ?>.css">
    <?php } ?>
    
    
    <!-- Style Page -->
    <?php if(file_exists($page."/style.css")) { ?>
        <link rel="stylesheet" href="<?php echo $page; ?>/style.css">
    <?php } ?>
  </head>
  <body class="  ">
    <!-- loader Start -->
    <div id="loading">
      <div class="loader simple-loader">
          <div class="loader-body">
          </div>
      </div>    
    </div>
    <!-- loader END -->

    <!-- loader data start -->
    <div id="overlay">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#6b8b1e" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round" style="animation-play-state: running; animation-delay: 0s;">
                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0 50 50;360 50 50" style="animation-play-state: running; animation-delay: 0s;"></animateTransform>
            </circle>
        </svg>
    </div>
    <!-- loader data end-->
    
    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
        <div class="sidebar-header d-flex align-items-center justify-content-start">
            <a href="?p=<?php echo base64_url_encode("home"); ?>" sub-menu='' class="navbar-brand">
                <!--Logo start-->
                <div class="logo-main">
                    <div class="logo-normal">
                        <img src="../assets/images/<?php echo $_SESSION['SITE']['Logo_Img']; ?>" style='width: 40px;'>
                    </div>
                    <div class="logo-mini">
                        <img src="../assets/images/<?php echo $_SESSION['SITE']['Logo_Img']; ?>" style='width: 40px;'>
                    </div>
                </div>
                <!--logo End-->
                <h4 class="logo-title"><?php echo $_SESSION['SITE']['site_name']; ?></h4>
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar">
            <div class="sidebar-list">
                <!-- Sidebar Menu Start -->
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">เมนู</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>
                    <?php 
                    $MenuClass = ($_SESSION['LVCLASS'] != '0') ? "SUBSTRING(T0.MenuClass, ".$_SESSION['LVCLASS'].", 1) = '1' AND" : "";
                    $SQL = 
                        "SELECT
                            T0.MenuKey, T0.HeadMenuKey, T0.MenuLevel, T0.MenuName, T0.MenuIcon, T0.MenuCase, T0.MenuLink, T0.MenuSort,
                            (SELECT COUNT(P0.MenuKey) FROM menulists P0 WHERE P0.HeadMenuKey = T0.MenuKey AND P0.MenuLevel = '1') AS 'SubMenuQty'
                        FROM menulists T0
                        WHERE $MenuClass T0.MenuStatus = 'A' AND T0.HeadMenuKey IS NULL
                        ORDER BY T0.HeadMenuKey, T0.MenuSort";
                    $RST = DBConnect("APP")->query($SQL);
                    foreach($RST as $Data) {
                        $SubMenu = ($Data['SubMenuQty'] != 0) ? $Data['MenuCase'] : "";
                        if($SubMenu == "") {
                            echo "
                            <li class='nav-item'>
                                <a class='nav-link ' aria-current='page' href='?p=".base64_url_encode($Data['MenuCase'])."' sub-menu='$SubMenu'>
                                    <div class='icon text-center'>".$Data['MenuIcon']."</div>
                                    <span class='item-name'>".$Data['MenuName']."</span>
                                </a>
                            </li>";
                        }else{
                            $SQL_Sub = 
                                "SELECT
                                    T0.MenuKey, T0.HeadMenuKey, T0.MenuLevel, T0.MenuName, T0.MenuIcon, T0.MenuCase, T0.MenuLink, T0.MenuSort,
                                    (SELECT COUNT(P0.MenuKey) FROM menulists P0 WHERE P0.HeadMenuKey = T0.MenuKey AND P0.MenuLevel = '1') AS 'SubMenuQty'
                                FROM menulists T0
                                WHERE $MenuClass T0.MenuStatus = 'A' AND T0.HeadMenuKey = '".$Data['MenuKey']."'
                                ORDER BY T0.HeadMenuKey, T0.MenuSort";
                            $RST_Sub = DBConnect("APP")->query($SQL_Sub);

                            echo "
                            <li class='nav-item'>
                                <a class='nav-link' data-bs-toggle='collapse' href='#$SubMenu-menu' role='button' aria-expanded='false' aria-controls='$SubMenu-menu'>
                                    <div class='icon text-center'>".$Data['MenuIcon']."</div>
                                    <span class='item-name'>".$Data['MenuName']."</span>
                                    <i class='right-icon'>
                                        <svg class='icon-18' xmlns='http://www.w3.org/2000/svg' width='18' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7' />
                                        </svg>
                                    </i>
                                </a>
                                <ul class='sub-nav collapse' id='$SubMenu-menu' data-bs-parent='#sidebar-menu'>";
                                    foreach($RST_Sub as $DataSub) {
                                        echo "
                                        <li class='nav-item'>
                                            <a class='nav-link ' href='?p=".base64_url_encode($DataSub['MenuLink'])."' sub-menu='$SubMenu'>
                                            <div class='icon text-center'>".$DataSub['MenuIcon']."</div>
                                            <i class='sidenav-mini-icon'>".$DataSub['MenuIcon']."</i>
                                            <span class='item-name'>".$DataSub['MenuName']."</span>
                                            </a>
                                        </li>";
                                    } 
                                echo "
                                </ul>
                            </li>";

                        }
                    }
                    ?>
                </ul>
                <!-- Sidebar Menu End -->        
            </div>
        </div>
    </aside>    
    
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--Nav Start-->
            <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
                <div class="container-fluid navbar-inner">
                    <a href="#" class="navbar-brand">
                        <!--Logo start-->
                        <div class="logo-main">
                            <div class="logo-normal">
                                <img src="../assets/images/<?php echo $_SESSION['SITE']['Logo_Img']; ?>" style='width: 40px;'>
                            </div>
                            <div class="logo-mini">
                                <img src="../assets/images/<?php echo $_SESSION['SITE']['Logo_Img']; ?>" style='width: 40px;'>
                            </div>
                        </div>
                        <!--logo End-->
                        <h4 class="logo-title"><?php echo $_SESSION['SITE']['site_name']; ?></h4>
                    </a>
                    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                        <i class="icon">
                            <svg  width="20px" class="icon-20" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                        </svg>
                        </i>
                    </div>
                    <!-- <div class="input-group search-input">
                        <span class="input-group-text" id="search-input">
                        <svg class="icon-18" width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                            <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        </span>
                        <input type="search" class="form-control" placeholder="Search...">
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <span class="mt-2 navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button> -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                            <!-- <li class="nav-item dropdown">
                                <a href="#"  class="nav-link" id="notification-drop" data-bs-toggle="dropdown" >
                                    <svg class="icon-24" width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.7695 11.6453C19.039 10.7923 18.7071 10.0531 18.7071 8.79716V8.37013C18.7071 6.73354 18.3304 5.67907 17.5115 4.62459C16.2493 2.98699 14.1244 2 12.0442 2H11.9558C9.91935 2 7.86106 2.94167 6.577 4.5128C5.71333 5.58842 5.29293 6.68822 5.29293 8.37013V8.79716C5.29293 10.0531 4.98284 10.7923 4.23049 11.6453C3.67691 12.2738 3.5 13.0815 3.5 13.9557C3.5 14.8309 3.78723 15.6598 4.36367 16.3336C5.11602 17.1413 6.17846 17.6569 7.26375 17.7466C8.83505 17.9258 10.4063 17.9933 12.0005 17.9933C13.5937 17.9933 15.165 17.8805 16.7372 17.7466C17.8215 17.6569 18.884 17.1413 19.6363 16.3336C20.2118 15.6598 20.5 14.8309 20.5 13.9557C20.5 13.0815 20.3231 12.2738 19.7695 11.6453Z" fill="currentColor"></path>
                                    <path opacity="0.4" d="M14.0088 19.2283C13.5088 19.1215 10.4627 19.1215 9.96275 19.2283C9.53539 19.327 9.07324 19.5566 9.07324 20.0602C9.09809 20.5406 9.37935 20.9646 9.76895 21.2335L9.76795 21.2345C10.2718 21.6273 10.8632 21.877 11.4824 21.9667C11.8123 22.012 12.1482 22.01 12.4901 21.9667C13.1083 21.877 13.6997 21.6273 14.2036 21.2345L14.2026 21.2335C14.5922 20.9646 14.8734 20.5406 14.8983 20.0602C14.8983 19.5566 14.4361 19.327 14.0088 19.2283Z" fill="currentColor"></path>
                                    </svg>
                                    <span class="bg-danger dots"></span>
                                </a>
                                <div class="p-0 sub-drop dropdown-menu dropdown-menu-end" aria-labelledby="notification-drop">
                                    <div class="m-0 shadow-none card">
                                    <div class="py-3 card-header d-flex justify-content-between bg-primary">
                                        <div class="header-title">
                                            <h5 class="mb-0 text-white">แจ้งเตือนทั้งหมด</h5>
                                        </div>
                                    </div>
                                    <div class="p-0 card-body">
                                        <a href="#" class="iq-sub-card">
                                            <div class="d-flex align-items-center">
                                                <div class="">
                                                <img class="p-1 avatar-40 rounded-pill bg-soft-primary" src="../assets/images/shapes/02.png" alt="">
                                                </div>
                                                <div class="ms-3 w-100">
                                                <h6 class="mb-0 ">ข่าว</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0">รายละเอียดข่าว</p>
                                                    <small class="float-end font-size-12">3 วันที่ผ่านมา</small>
                                                </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    </div>
                                </div>
                            </li> -->
                            <li class="nav-item dropdown">
                                <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo $avatar."?v=".date("YmdHis"); ?>" alt="User-Profile" class="img_profile theme-color-green-img img-fluid avatar avatar-50 avatar-rounded">
                                    <div class="caption ms-3 d-none d-md-block ">
                                        <h6 class="mb-0 caption-title name-top-profile"><?php echo $_SESSION['EMPNAME']; ?></h6>
                                        <p class="mb-0 caption-sub-title dept-top-profile"><?php echo $_SESSION['LVNAME']; ?></p>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="?p=<?php echo base64_url_encode("profile"); ?>" sub-menu=''>จัดการโปรไฟล์</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="../core/LogChk.php?p=O">ออกจากระบบ</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>          

            <div class="ms-4 me-4 mt-3 mb-3">
            <!-- Nav Header Component Start -->
                <?php
                require("$page/$page.php"); 
                ?>  
            <!-- Nav Header Component End -->
            </div>

        <!--Nav End-->
        </div>
        <!-- Footer Section Start -->
        <footer class="footer" style='background: Transparent !important;'>
            <div class="footer-body">
                <ul class="left-panel list-inline mb-0 p-0">
                    <!-- <li class="list-inline-item"><a href="../dashboard/extra/privacy-policy.html">Privacy Policy</a></li> -->
                </ul>
                <div class="right-panel">
                    ©<script>document.write(new Date().getFullYear())</script> <?php echo $_SESSION['SITE']['site_name']; ?>
                </div>
            </div>
        </footer>
        <!-- Footer Section End -->    
    </main>

    <!-- offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" data-bs-scroll="true" data-bs-backdrop="true" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-body data-scrollbar">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-grid gap-3 grid-cols-3 mb-4">
                        <div class="btn btn-border active" data-setting="color-mode" data-name="color" data-value="light">
                            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M12,8A4,4 0 0,0 8,12A4,4 0 0,0 12,16A4,4 0 0,0 16,12A4,4 0 0,0 12,8M12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6A6,6 0 0,1 18,12A6,6 0 0,1 12,18M20,8.69V4H15.31L12,0.69L8.69,4H4V8.69L0.69,12L4,15.31V20H8.69L12,23.31L15.31,20H20V15.31L23.31,12L20,8.69Z" />
                            </svg>
                            <span class="ms-2 "> Light</span>
                        </div>
                    </div>
                    <hr class="hr-horizontal"> 
                    <div class="grid-cols-5 mb-4 d-grid gap-x-2">
                        <div class="btn btn-border bg-transparent theme-custom"  data-value="theme-color-red" data-info="#b31b21" data-setting="color-mode1" data-name="color" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Theme-3">
                            <svg  class="customizer-btn icon-32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" > <circle cx="12" cy="12" r="10" fill="#b31b21" /> <path d="M2,12 a1,1 1 1,0 20,0" fill="#b31b21" /></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ALERT -->
    <div class="modal fade" id="alert_modal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="modal-title" id="alert_header"></h5>
                    <p id="alert_body" class="my-4"></p>
                    <button type="button" class="btn btn-primary btn-sm btn-confirm" data-bs-dismiss="modal">ตกลง</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FADE -->
    <div class="modal fade" id="fade_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="modal-title" style='font-size: 80px;'></h5>
                    <p class="my-4"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CONFIRM -->
    <div class="modal fade" id="confirm_modal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="modal-title"><i class="far fa-question-circle fa-fw fa-lg"></i> ยืนยัน</h5>
                    <p class="defult my-4">คุณต้องการดำเนินการต่อหรือไม่?</p>
                    <p class='custom d-none my-4'></p>
                    <button type="button" class="btn btn-secondary btn-sm w-25" data-bs-dismiss="modal"><i class="fas fa-times fa-fw fa-1x"></i> ไม่</button>
                    <button type="button" class="btn btn-primary btn-sm w-25" id="btn-confirm"><i class="fas fa-check fa-fw fa-1x"></i> ใช่</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Longdo Map API -->
    <script src="https://api.longdo.com/map/?key=80ef894dbb3712dc9028fc95106dd202"></script>
    <!-- Library Bundle Script -->
    <script src="../assets/js/core/libs.min.js"></script>
    <!-- External Library Bundle Script -->
    <script src="../assets/js/core/external.min.js"></script>
    <!-- Widgetchart Script -->
    <script src="../assets/js/charts/widgetcharts.js"></script>
    <!-- mapchart Script -->
    <script src="../assets/js/charts/vectore-chart.js"></script>
    <script src="../assets/js/charts/dashboard.js" ></script>
    <!-- fslightbox Script -->
    <script src="../assets/js/plugins/fslightbox.js"></script>
    <!-- Settings Script -->
    <script src="../assets/js/plugins/setting.js"></script>
    <!-- Slider-tab Script -->
    <script src="../assets/js/plugins/slider-tabs.js"></script>
    <!-- Form Wizard Script -->
    <script src="../assets/js/plugins/form-wizard.js"></script>
    <!-- AOS Animation Plugin-->
    <script src="../assets/vendor/aos/dist/aos.js"></script>
    <!-- App Script -->
    <script src="../assets/js/hope-ui.js" defer></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- Selectpicker -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js" type="text/javascript"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>
    <!-- Datatable -->
    <script src="../assets/js/Datatable/jquery.dataTables.min.js"></script>
    <script src="../assets/js/Datatable/dataTables.buttons.min.js"></script>
    <script src="../assets/js/Datatable/jszip.min.js"></script>
    <script src="../assets/js/Datatable/buttons.html5.min.js"></script>
    <script src="../assets/js/Datatable/buttons.print.min.js"></script>
    <script src="../assets/js/Croppie/croppie.min.js"></script>
    <script src="../assets/js/charts/apexcharts.js"></script>
    <!-- Script Page -->
    <script type="text/javascript"> 
        const FIX_Page = '<?php echo base64_encode($page); ?>';
    </script>
    <script src="../core/functions.core.js"></script>
    <script type="text/javascript"> 
        const SS_LVCODE   = '<?php echo base64_encode($_SESSION['LVCODE']); ?>';
        const SS_UKEY     = '<?php echo base64_encode($_SESSION['UKEY']); ?>';
        const SS_USERNAME = '<?php echo base64_encode($_SESSION['USERNAME']); ?>';
        const SS_DEPTCODE = '<?php echo base64_encode($_SESSION['DEPTCODE']); ?>';
        const SS_SITE = '<?php echo base64_encode($_SESSION['SITE']['site_id']); ?>';
    </script>
    <?php if(file_exists($page."/app.js")) { ?>
        <script src="<?php echo $page; ?>/app.js?v=<?php echo date("YmdHis"); ?>" type="text/javascript"></script>
    <?php } ?>
    <?php if(file_exists($page."/module.js")) { ?>
        <script src="<?php echo $page; ?>/module.js?v=<?php echo date("YmdHis"); ?>" type="module"></script>
    <?php } ?>

    <script>
        $(document).ready(function(){
            $("#overlay").hide();
            $(".theme-custom").click();
            $("a[href='?p=<?php echo base64_url_encode($page); ?>']").addClass("active");
            const SubMenu = $("a[href='?p=<?php echo base64_url_encode($page); ?>']").attr("sub-menu");
            if(SubMenu != "") {
                $("a[href='#"+SubMenu+"-menu'").addClass("active");
            }
        });
    </script>
  </body>
</html>