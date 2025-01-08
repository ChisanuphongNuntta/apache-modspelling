<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>ลงทะเบียนใบรับประกันสินค้าและบริการ</title>
</head>

<body>
    <div class="login-card-container">
        <div class="login-card">
            <div class="login-card-logo">
                <img src="kbi_logo.png" alt="logo">
            </div>
            <div class="login-card-header">
                <h1>ลงทะเบียนใบรับประกันสินค้าและบริการ</h1>
                <div>Please register to use the platform</div>
            </div>
            <!-- form -->
            <form class="card-form" id="insert-form" method="post" action="">
                <div class="form-item">
                    <span class="form-item-icon material-symbols-rounded">person</span>
                    <input type="text" class="form-control" placeholder="ชื่อ นามสกุล" id="name" name="name" value="" required="">
                </div>
                <div class="form-item">
                    <span class="form-item-icon material-symbols-rounded">email</span>
                    <input type="email" class="form-control" id="email" placeholder="you@example.com" name="email">
                </div>
                <div class="form-item">
                <span class="form-item-icon material-symbols-rounded">water_drop</span>
                    <input type="number" class="form-control" placeholder="อายุ" id="age" required="" name="age">
                </div>
                <div class="form-item">    
                    <span class="form-item-icon material-symbols-rounded">phone</span>
                    <input type="text" class="form-control" placeholder="เบอร์โทรศัพท์" id="phone" required="" name="phone">
                </div>
                <div class="form-item">
                    <span class="form-item-icon material-symbols-rounded">public</span>
                    <input type="text" class="form-control" placeholder="Line Id" id="line" name="line">
                </div>
                <!-- gender radio -->
                <label for="gender">เพศ</label>
                <div class="">
                    <div class="form-check">
                        <input id="male" name="gender" type="radio" class="form-check-input" checked="" value="Male" required="">
                        <label class="form-check-label" for="male">ผู้ชาย</label>
                    </div>
                    <div class="form-check">
                        <input id="female" name="gender" type="radio" class="form-check-input" value="Female" required="">
                        <label class="form-check-label" for="feman">ผู้หญิง</label>
                    </div>
                </div>
                <!-- job radio -->
                <label for="job">อาชีพ</label>
                <div class="">
                    <div class="form-check">
                        <input id="official" name="job" type="radio" class="form-check-input" checked="" value="official" required="">
                        <label class="form-check-label" for="official">ข้าราชการ / ทหาร / ตำรวจ</label>
                    </div>
                    <div class="form-check">
                        <input id="employee" name="job" type="radio" class="form-check-input" value="employee" required="">
                        <label class="form-check-label" for="employee">พนักงานเอกชน</label>
                    </div>
                    <div class="form-check">
                        <input id="student" name="job" type="radio" class="form-check-input" value="student" required="">
                        <label class="form-check-label" for="student">นักเรียน / นักศึกษา</label>
                    </div>
                    <div class="form-check">
                        <input id="contractor" name="job" type="radio" class="form-check-input" value="contractor" required="">
                        <label class="form-check-label" for="contractor">ผู้รับเหมาก่อสร้าง</label>
                    </div>
                    <div class="form-check">
                        <input id="business" name="job" type="radio" class="form-check-input" value="business" required="">
                        <label class="form-check-label" for="business">เจ้าของธุรกิจ / เจ้าของโรงงาน /
                            ธุรกิจส่วนตัว</label>
                    </div>
                    <div class="form-check">
                        <input id="farmer" name="job" type="radio" class="form-check-input" value="farmer" required="">
                        <label class="form-check-label" for="farmer">เกษตรกร / ฟาร์มเลี้ยงสัตว์</label>
                    </div>
                </div>
                <button type="submit" id="next1" name="next1">ต่อไป</button>

            </form>
            <div class="login-card-footer">
                Don't have an account? <a href="#">Create a free account.</a>
            </div>
        </div>
        <div class="login-card-social">
            <div>Other Sign-In Options</div>
            <div class="login-card-social-btns">
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                    </svg>
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-google" width="24" height="24" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M17.788 5.108a9 9 0 1 0 3.212 6.892h-8"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</body>

</html>

<script>
    $(document).ready(function() {
        $('#insert-form').on('submit', function(e) {
            e.preventDefault();
            var formp1 = new FormData($('#insert-form')[0]);
            $.ajax({
                url: "../ajax/ajaxwrt.php?a=page1", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
                type: "POST",
                data: formp1,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#next1').val('insert...');
                },
                success: function(data) {
                    // $('#insert-form')[0].reset();
                    var obj = jQuery.parseJSON(data);
                    $.each(obj, function(key, inval) {
                        if (inval["output"]) {
                            location.href = 'info2.php';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'หมายเลขโทรศัพท์ไม่ถูกต้อง',
                                text: 'Done, we will take you to the home page!',
                                timer: 5000,
                                timerProgressBar: true,
                            }).then(function() {
                                
                            });
                        }
                    });
                }
            })
        });
    });
</script>

<!-- <script>
    $(document).ready(function() {
        $('#insert-form').on('next1', function(e) {
            e.preventDefault();
            var formp1 = new FormData($('#insert-form')[0]);
            $.ajax({
                url: "../ajax/ajaxwrt.php?a=page1", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
                type: "POST",
                data: formp1,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#next1').val('insert...');
                },
                success: function(data) {
                    // $('#insert-form')[0].reset();
                    var obj = jQuery.parseJSON(data);
                    $.each(obj, function(key, inval) {

                    });
                    if (data) {
                        // location.href = 'info2.php';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: 'Done, we will take you to the home page!',
                            timer: 5000,
                            timerProgressBar: true,
                        }).then(function() {
                            window.location.href = "index.php";
                        });
                    }
                }
            })
        });
    });
</script> -->

<!-- <script>
    $(document).ready(function() {
        $('#next1').click(function(e) {
            e.preventDefault();
            var phone = $("#phone").val();
            $.ajax({
                type: "POST",
                url: "../ajax/ajaxwrt.php?a=ckPN",
                data: {
                    phone: phone,
                },
                success: function(result) {

                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {

                        if (inval["phone"]) {
                            $("#phone").html(inval["phone"]);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'กรุณากรอกหมายเลขให้ถูกต้อง',
                                text: 'Done, we will take you to the home page!',
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                    });

                    // if (data.code == "200") {
                    //     $('#phone').html(data.output);
                    //     insertForm1();
                    //     // sendToInfo1();
                    //     // alert("Success: " + data.output);
                    //     console.log(data.output);
                    // } else {
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'กรุณากรอก' + data.output,
                    //         text: 'Done, we will take you to the home page!',
                    //         timer: 3000,
                    //         timerProgressBar: true,
                    //     });
                    // }
                }
            });
        });
    });
</script> -->