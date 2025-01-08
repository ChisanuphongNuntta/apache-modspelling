<!DOCTYPE html>
<html lang="en">
<!--62011212091 THIANCHAI CHAMNAN -->
<!-- MAHASARAKHAM UNIVERSITY -->
<head>
    <?php include('header.php'); ?>
</head>

<body>
    <div class="login-card-container">
        <div class="login-card">
            <div class="login-card-logo">
                <img src="kbi_logo.png" alt="logo">
            </div>
            <div class="login-card-header">
                <h1>ลงทะเบียนใบรับประกันสินค้าและบริการ</h1>
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
                url: "ajax/ajaxwrt.php?a=page1", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
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