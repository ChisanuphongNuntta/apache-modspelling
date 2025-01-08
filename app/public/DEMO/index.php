<?php 
include("core/functions.core.php");
?>
<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>BexSys WebSale Demo</title>
      <!-- Favicon -->
      <link rel="shortcut icon" href="assets/images/logo.jpg">
      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="assets/css/core/libs.min.css">
      <!-- Hope Ui Design System Css -->
      <link rel="stylesheet" href="assets/css/hope-ui.min.css?v=4.0.0">
      <!-- Custom Css -->
      <link rel="stylesheet" href="assets/css/custom.min.css?v=4.0.0">
      <!-- Dark Css -->
      <link rel="stylesheet" href="assets/css/dark.min.css">
      <!-- Customizer Css -->
      <link rel="stylesheet" href="assets/css/customizer.min.css">
      <!-- RTL Css -->
      <link rel="stylesheet" href="assets/css/rtl.min.css">
      <link rel="stylesheet" href="assets/css/style_site_0.css">
      <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
      <style>
        #overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 999999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
      </style>
  </head>
  <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
      <div class="loader simple-loader">
          <div class="loader-body">
          </div>
      </div>    </div>
    <!-- loader END -->
    <div id="overlay">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#6b8b1e" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round" style="animation-play-state: running; animation-delay: 0s;">
                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0 50 50;360 50 50" style="animation-play-state: running; animation-delay: 0s;"></animateTransform>
            </circle>
        </svg>
    </div>
    
    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">            
                <div class="col-md-6">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                            <div class="card-body">
                                <div class="navbar-brand d-flex align-items-center mb-3">
                                    <!--Logo start-->
                                    <div class="logo-main">
                                        <div class="logo-normal">
                                            <img src="assets/images/logo.jpg" style='width: 50px;'>
                                        </div>
                                        <div class="logo-mini">
                                            <img src="assets/images/logo.jpg" style='width: 40px;'>
                                        </div>
                                    </div>
                                    <!--logo End-->
                                    <h4 class="logo-title ms-3">Bex Sys</h4>
                                </div>
                                <h2 class="mb-2 text-center">เข้าสู่ระบบ</h2>
                                <div class="row pt-3">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="username" class="form-label">ชื่อผู้ใช้</label>
                                            <input type="username" class="form-control" id="username" aria-describedby="username" placeholder=" ">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="password" class="form-label">รหัสผ่าน</label>
                                            <input type="password" class="form-control" id="password" aria-describedby="password" placeholder=" ">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center pt-2">
                                    <button class="btn btn-primary" onclick='SignIn();'>เข้าสู่ระบบ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sign-bg">
                    <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.05">
                            <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#6b8b1e"/>
                            <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#6b8b1e"/>
                        </g>
                    </svg>
                </div>
                </div>
                <div class="col-md-6 d-md-block d-none bg-danger p-0 vh-100 overflow-hidden">
                    <img src="assets/images/bg_login.jpg" class="img-fluid gradient-main animated-scaleX" alt="images">
                </div>
            </div>
        </section>
    </div>

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
                        <div class="btn btn-border bg-transparent theme-patar"  data-value="theme-color-red" data-info="#b31b21" data-setting="color-mode1" data-name="color" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Theme-3">
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
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">ตกลง</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Library Bundle Script -->
    <script src="assets/js/core/libs.min.js"></script>
    <!-- External Library Bundle Script -->
    <script src="assets/js/core/external.min.js"></script>
    <!-- Widgetchart Script -->
    <script src="assets/js/charts/widgetcharts.js"></script>
    <!-- mapchart Script -->
    <script src="assets/js/charts/vectore-chart.js"></script>
    <script src="assets/js/charts/dashboard.js" ></script>
    <!-- fslightbox Script -->
    <script src="assets/js/plugins/fslightbox.js"></script>
    <!-- Settings Script -->
    <script src="assets/js/plugins/setting.js"></script>
    <!-- Slider-tab Script -->
    <script src="assets/js/plugins/slider-tabs.js"></script>
    <!-- Form Wizard Script -->
    <script src="assets/js/plugins/form-wizard.js"></script>
    <!-- AOS Animation Plugin-->
    <!-- App Script -->
    <script src="assets/js/hope-ui.js" defer></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $("#overlay").hide();
            $(".theme-patar").click();
            // document.querySelector(".theme-patar").click();
        });

        $(".theme-patar").on("click", () => {
            console.log("Click");
        })

        $("#password").on('keypress',function(e) {
            if(e.which == 13) {
                SignIn();
            }
        });

        function SignIn() {
            const username = $("#username").val();
            const password = $("#password").val();
            const SiteID   = 0;
            if(username != "" && password != "" && SiteID != null) {
                $("#username, #password").removeClass("is-invalid");
                $("#overlay").show();
                $.ajax({
                    url: "core/LogChk.php?p=I",
                    type: "POST",
                    data: { usnm: username, pswd: password, SiteID: SiteID },
                    success: function(result) {
                        $("#overlay").hide();
                        var obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            if(inval['Status'] == "OK") {
                                window.open("views/?p=<?php echo base64_url_encode("home"); ?>","_parent");
                            } else {
                                switch(inval['Status']) {
                                    case 'ERR::NOUSER': 
                                        $("#alert_header").html("<i class='fas fa-exclamation-circle fa-lg'></i> ข้อผิดพลาด");
                                        $("#alert_body").html("ไม่มีชื่อผู้ใช้นี้อยู่ในระบบ");
                                        $("#alert_modal").modal("show");
                                        $("#username").addClass("is-invalid");
                                        $("#password").val("");
                                    break;
                                    case 'ERR::WRONGPSWD': 
                                        $("#alert_header").html("<i class='fas fa-exclamation-circle fa-lg'></i> ข้อผิดพลาด");
                                        $("#alert_body").html("รหัสผ่านไม่ถูกต้อง");
                                        $("#alert_modal").modal("show");
                                        $("#password").addClass("is-invalid");
                                    break;
                                }
                            }
                        });
                    }
                })
                
            }else{
                $("#alert_header").html("<i class='fas fa-exclamation-circle fa-lg'></i> ข้อผิดพลาด");
                $("#alert_body").html("กรุณากรอกข้อมูลให้ครบถ้วน");
                $("#alert_modal").modal("show");
                const username = $("#username").val() == "" ? $("#username").addClass("is-invalid") : $("#username").removeClass("is-invalid");
                const password = $("#password").val() == "" ? $("#password").addClass("is-invalid") : $("#password").removeClass("is-invalid");
            }
        }

        $("#username, #password").keyup(function(){
            $(this).removeClass("is-invalid");
        })
    </script>
  </body>
</html>