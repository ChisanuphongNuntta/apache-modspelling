<html lang="en">
<?php include('template/header.php'); ?>

<body class="bg-light">
  <div class="container">
    <main>
      <div class="row g-5">
        <div class="col-md-5 col-lg-4 order-md-last">
        </div>
        <div class="col-md-7 col-lg-8">
          <!-- from -->
          <form class="" id="insert-form3" method="post" action="">
            <div class="row g-3">
              <h4 class="mb-3">ส่วนที่ 1: ข้อมูลส่วนตัวของผู้ซื้อสินค้า</h4>
              <div class="row g-3">
                <!-- brand radio -->
                <label for="brand" class="form-label">แบรนด์สินค้า</label>
                <div class="my-3">
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
                </div>
                <!-- product radio -->
                <label for="product" class="form-label">ประเภทสินค้าที่สั่งซื้อ</label>
                <div class="my-3">
                  <div class="form-check">
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
                  </div>
                </div>
                <!--  -->
                <div class="col-12">
                  <label for="nameProduct" class="form-label">ชื่อสินค้า หรือรุ่นของสินค้า</label>
                  <input type="text" class="form-control" id="nameProduct" name="nameProduct" value="" required="">
                </div>
                <div class="col-12">
                  <label for="serialNumber" class="form-label">หมายเลขเครื่อง (Serial Number) (ถ้ามี)</label>
                  <input type="text" class="form-control" id="serialNumber" name="serialNumber" value="">
                </div>
                <button class="w-100 btn btn-primary btn-lg" type="submit" id="next3" name="next3">submit</button>
                <!-- Pagination -->
                <div id="paginations">
                  <?php include('template/pagination.php') ?>
                </div>
          </form>
        </div>
      </div>
    </main>
    <?php include('template/footer.php'); ?>
  </div>
  <!-- <script src="/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <script src="form-validation.js"></script> -->
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</body>

</html>

<script>
  $(document).ready(function() {
    $('#insert-form3').on('submit', function(e) {
      e.preventDefault();
      var formp3 = new FormData($('#insert-form3')[0]);
      $.ajax({
        url: "ajax/ajaxwrt.php?a=page3", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
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
              console.log('data');
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
              console.log('is not data');
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