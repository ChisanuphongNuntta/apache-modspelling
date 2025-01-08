<?php session_start();
// $profile_id = '';
?>
<!DOCTYPE html>
<html lang="en">
<?php include('template/header.php'); ?>

<body class="bg-light">
    <div class="container">
        <main>
            
            <div class="row g-5">
                <div class="col-md-5 col-lg-4 order-md-last">
                    <!--  -->
                </div>
                <div class="col-md-7 col-lg-8">
                    <!-- from 1 -->
                    <h4 class="mb-3">ส่วนที่ 1: ข้อมูลส่วนตัวของผู้ซื้อสินค้า</h4>
                    <form class="" id="insert-form" method="post" action="">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label">ชื่อ และนามสกุลผู้ซื้อ</label>
                                <input type="name" class="form-control" id="name" name="name" value="" required="">

                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email </label>
                                <input type="email" class="form-control" id="email" placeholder="you@example.com" name="email">

                            </div>

                            <div class="col-12">
                                <label for="age" class="form-label">อายุ</label>
                                <input type="number" class="form-control" id="age" required="" name="age">

                            </div>

                            <div class="col-12">
                                <label for="phone" class="form-label">หมายเลขโทรศัพท์</label>
                                <input type="text" class="form-control" id="phone" required="" name="phone">

                            </div>

                            <div class="col-12">
                                <label for="line" class="form-label">Line ID</label>
                                <input type="text" class="form-control" id="line" name="line">

                            </div>
                            <!-- gender radio -->
                            <h4 class="mb-3">เพศ</h4>
                            <div class="my-3">
                                <div class="form-check">
                                    <input id="men" name="gender" type="radio" class="form-check-input" checked="" value="Male" required="">
                                    <label class="form-check-label" for="man">ผู้ชาย</label>
                                </div>
                                <div class="form-check">
                                    <input id="female" name="gender" type="radio" class="form-check-input" value="Female" required="">
                                    <label class="form-check-label" for="feman">ผู้หญิง</label>
                                </div>
                            </div>
                            <!-- job radio -->
                            <h4 class="mb-3">อาชีพ</h4>
                            <div class="my-3">
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
                                    <label class="form-check-label" for="business">เจ้าของธุรกิจ / เจ้าของโรงงาน / ธุรกิจส่วนตัว</label>
                                </div>
                                <div class="form-check">
                                    <input id="farmer" name="job" type="radio" class="form-check-input" value="farmer" required="">
                                    <label class="form-check-label" for="farmer">เกษตรกร / ฟาร์มเลี้ยงสัตว์</label>
                                </div>
                            </div>
                            <button class="w-100 btn btn-primary btn-lg" type="submit" id="next1" name="next1">next</button>
                            <!-- Pagination -->
                            <div id="paginations">
                                <?php include('template/pagination.php') ?>
                            </div>
                    </form>
                    <!-- end form 1 -->
                </div>
            </div>
        </main>
        <?php include('template/footer.php'); ?>
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
                    $.each(obj, function(key, inval) {});
                    if (data) {
                        location.href = 'page_2.php';
                    } else {
                        console.log(' Not data');
                    }
                }
            })
        });
    });
</script>