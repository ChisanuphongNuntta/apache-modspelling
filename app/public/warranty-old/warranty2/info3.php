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
    <title>Login Page</title>
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
            <form class="card-form" id="insert-form3" method="post" action="">
                <label for="brand" class="form-label">แบรนด์สินค้า</label>
                <div>
                    <div class="form-check">
                        <input id="EUROX" name="brand" type="radio" class="form-check-input" checked="" value="EUROX" required="">
                        <label class="form-check-label" for="EUROX">EUROX</label>
                    </div>
                    <div class="form-check">
                        <input id="KING" name="brand" type="radio" class="form-check-input" value="KING" required="">
                        <label class="form-check-label" for="KING">KING</label>
                    </div>
                    <div class="form-check">
                        <input id="REDKING" name="brand" type="radio" class="form-check-input" value="REDKING" required="">
                        <label class="form-check-label" for="REDKING">REDKING</label>
                    </div>
                    <div class="form-check">
                        <input id="PITA" name="brand" type="radio" class="form-check-input" value="PITA" required="">
                        <label class="form-check-label" for="PITA">PITA</label>
                    </div>
                    <div class="form-check">
                        <input id="BASE_POWER" name="brand" type="radio" class="form-check-input" value="BASE_POWER" required="">
                        <label class="form-check-label" for="BASE_POWER">BASE POWER</label>
                    </div>
                    <div class="form-check">
                        <input id="PUD" name="brand" type="radio" class="form-check-input" value="PUD" required="">
                        <label class="form-check-label" for="PUD">PUD</label>
                    </div>
                    <div class="form-check">
                        <input id="COOFIX" name="brand" type="radio" class="form-check-input" value="COOFIX" required="">
                        <label class="form-check-label" for="COOFIX">COOFIX</label>
                    </div>
                    <div class="form-check">
                        <input id="other" name="brand" type="radio" class="form-check-input" value="other" required="">
                        <label class="form-check-label" for="other">อื่นๆ</label>
                    </div>
                    <div><br>
                        <input type="text" class="form-control" id="otherTxt" name="otherTxt" placeholder="อื่นๆ(โปรดระบุ)" value="" required="" disabled>
                    </div>
                </div>
                <!-- product radio -->
                <label for="product" class="form-label">ประเภทสินค้าที่สั่งซื้อ</label>
                <div id="product">
                    <!-- <div class="form-check">
                        <input id="p1" name="product" type="radio" class="form-check-input" value="p1" checked="" required="">
                        <label class="form-check-label" for="p1">ปืนยิงตะปูลม / ไฟฟ้า</label>
                    </div>
                    <div class="form-check">
                        <input id="p2" name="product" type="radio" class="form-check-input" value="p2" required="">
                        <label class="form-check-label" for="p2">กาพ่นสีลม / ไฟฟ้า</label>
                    </div>
                    <div class="form-check">
                        <input id="p3" name="product" type="radio" class="form-check-input" value="p3" required="">
                        <label class="form-check-label" for="p3">เครื่องเลเซอร์วัดระดับ / ระยะ</label>
                    </div>
                    <div class="form-check">
                        <input id="p4" name="product" type="radio" class="form-check-input" value="p4" required="">
                        <label class="form-check-label" for="p4">ปั๊มลม</label>
                    </div>
                    <div class="form-check">
                        <input id="p5" name="product" type="radio" class="form-check-input" value="p5" required="">
                        <label class="form-check-label" for="p5">บล็อคลม / สว่านลม / ไขควงลม / เจียร์ลม /
                            เครื่องขัดกระดาษทรายกลม</label>
                    </div>
                    <div class="form-check">
                        <input id="p6" name="product" type="radio" class="form-check-input" value="p6" required="">
                        <label class="form-check-label" for="p6">สว่าน / สว่านโรตารี่ / สว่านเจาะทำลาย</label>
                    </div>
                    <div class="form-check">
                        <input id="p7" name="product" type="radio" class="form-check-input" value="p7" required="">
                        <label class="form-check-label" for="p7">เครื่องเจียร์ / เครื่องทริมเมอร์ / เลื่อยวงเดือน /
                            เครื่องตัดหินอ่อน / เลื่อยจิ๊กซอว์ / แท่นตัดไฟเบอร์</label>
                    </div>
                    <div class="form-check">
                        <input id="p8" name="product" type="radio" class="form-check-input" value="p8" required="">
                        <label class="form-check-label" for="p8">สว่านไร้สาย / ไขควงไร้สาย</label>
                    </div>
                    <div class="form-check">
                        <input id="p9" name="product" type="radio" class="form-check-input" value="p9" required="">
                        <label class="form-check-label" for="p9">เครื่องสีข้าว</label>
                    </div>
                    <div class="form-check">
                        <input id="p10" name="product" type="radio" class="form-check-input" value="p10" required="">
                        <label class="form-check-label" for="p10">เลื่อยโซ่ยนต์ / เจียร์บาร์โซ่</label>
                    </div>
                    <div class="form-check">
                        <input id="p11" name="product" type="radio" class="form-check-input" value="p11" required="">
                        <label class="form-check-label" for="p11">เครื่องมือช่างอื่น ๆ เช่น แท่นตัดกระเบื้อง</label>
                    </div>
                    <div class="form-check">
                        <input id="p12" name="product" type="radio" class="form-check-input" value="p12" required="">
                        <label class="form-check-label" for="p12">เครื่องตัดหญ้า</label>
                    </div>
                    <div class="form-check">
                        <input id="p13" name="product" type="radio" class="form-check-input" value="p13" required="">
                        <label class="form-check-label" for="p13">เครื่องเจาะดิน</label>
                    </div>
                    <div class="form-check">
                        <input id="p14" name="product" type="radio" class="form-check-input" value="p14" required="">
                        <label class="form-check-label" for="p14">เครื่องพ่นยา</label>
                    </div>
                    <div class="form-check">
                        <input id="p15" name="product" type="radio" class="form-check-input" value="p15" required="">
                        <label class="form-check-label" for="p15">เครื่องยนต์ / เครื่องยนต์ชนปั๊ม /
                            เครื่องปั่นไฟ</label>
                    </div>
                    <div class="form-check">
                        <input id="p16" name="product" type="radio" class="form-check-input" value="p16" required="">
                        <label class="form-check-label" for="p16">เครื่องมือเกษตรอื่น ๆ เช่น
                            ล้อหยอดเมล็ดพันธุ์</label>
                    </div> -->
                </div>
                <!--  -->
                <div>
                    <input type="text" class="form-control" id="nameProduct" name="nameProduct" placeholder="ชื่อสินค้า หรือรุ่นของสินค้า" value="" required="">
                </div>
                <div>
                    <input type="text" class="form-control" id="serialNumber" name="serialNumber" placeholder="หมายเลขเครื่อง (Serial Number) (ถ้ามี)" value="">
                </div>
                <button type="submit" id="next3" name="next3">ลงทะเบียน</button>
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
    $("input[name='brand'").on("click", function() {
        var brandValue = $(this).val();
        if (brandValue == 'other') {
            $("#otherTxt").removeAttr("disabled");
        } else {
            $("#otherTxt").attr("disabled", true);
        } 
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var productValue = $('#product').val();
        $.ajax({
            url: "../ajax/ajaxwrt.php?a=callProduct", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
            type: "POST",
            data: {
                product: productValue,
            },
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    $("#product").html(inval["output"]);
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#insert-form3').on('submit', function(e) {
            e.preventDefault();
            var formp3 = new FormData($('#insert-form3')[0]);
            $.ajax({
                url: "../ajax/ajaxwrt.php?a=page3", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
                type: "POST",
                data: formp3,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#next3').val('insert...');
                },
                success: function(result) {
                    // $('#insert-form')[0].reset();
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        if (inval["output"]) {
                            Swal.fire({
                                icon: 'success',
                                title: 'เพิ่มข้อมูลเรียบร้อย',
                                text: 'Done, we will take you to the home page!',
                                timer: 5000,
                                timerProgressBar: true,
                            }).then(function() {
                                window.location.href = "index.php";
                            });
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
                    });
                },
            })
        });
    });
</script>