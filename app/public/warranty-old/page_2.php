<?php
include("../core/config.core.php");
include("../core/functions.core.php");
date_default_timezone_set('Asia/Bangkok');

?>
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
          <!-- from 2 -->
          <form class="" id="insert-form2" method="post" action="">
            <div class="row g-3">
              <h4 class="mb-3">ส่วนที่ 2: ข้อมูลการซื้อสินค้า</h4>
              <div class="row g-3">
                <div class="col-12">
                  <label for="date" class="form-label">1. วันที่ซื้อ</label>
                  <input type="date" class="form-control" id="date" name="date" value="" required="">
                </div>
                <!-- objective radio -->
                <label for="objective" class="form-label">วัตถุประสงค์ในการซื้อสินค้า</label>
                <div class="my-3">
                  <div class="form-check">
                    <input id="contractor" name="objective" type="radio" class="form-check-input" checked="" value="contractor">
                    <label class="form-check-label" for="contractor">ใช้ในงานรับเหมา</label>
                  </div>
                  <div class="form-check">
                    <input id="factory" name="objective" type="radio" class="form-check-input" value="factory" required="">
                    <label class="form-check-label" for="factory">ใช้ในโรงงาน</label>
                  </div>
                  <div class="form-check">
                    <input id="home" name="objective" type="radio" class="form-check-input" value="home" required="">
                    <label class="form-check-label" for="home">ใช้งานภายในบ้าน</label>
                  </div>
                </div>
                <hr class="my-4">
                <!--  market radio -->
                <label for="market" class="form-label">2. ชื่อร้านค้าหรือห้างสรรพสินค้าที่สั่งซื้อ
                  (พร้อมระบุสาขา)</label>
                <div class="my-3">
                  <div class="form-check">
                    <input id="official" name="market" type="radio" class="form-check-input" checked="" value="4" required="">
                    <label class="form-check-label" for="official">ดูโฮม</label>
                  </div>
                  <div class="form-check">
                    <input id="employee" name="market" type="radio" class="form-check-input" value="8" required="">
                    <label class="form-check-label" for="employee">ไทยพิพัฒน์ทูล</label>
                  </div>
                  <div class="form-check">
                    <input id="student" name="market" type="radio" class="form-check-input" value="19" required="">
                    <label class="form-check-label" for="student">ไทยเพิ่มพูลโฮมช็อป</label>
                  </div>
                  <div class="form-check">
                    <input id="contractor" name="market" type="radio" class="form-check-input" value="6" required="">
                    <label class="form-check-label" for="contractor">นพดลพานิช</label>
                  </div>
                  <div class="form-check">
                    <input id="business" name="market" type="radio" class="form-check-input" value="11" required="">
                    <label class="form-check-label" for="business">เมืองเลยบิ๊กโฮม</label>
                  </div>
                  <div class="form-check">
                    <input id="farmer" name="market" type="radio" class="form-check-input" value="21" required="">
                    <label class="form-check-label" for="farmer">ศิริมหาชัย</label>
                  </div>
                  <div class="form-check">
                    <input id="farmer" name="market" type="radio" class="form-check-input" value="3" required="">
                    <label class="form-check-label" for="farmer">สยามโกลบอลเฮ้าส์</label>
                  </div>
                  <div class="form-check">
                    <input id="farmer" name="market" type="radio" class="form-check-input" value="15" required="">
                    <label class="form-check-label" for="farmer">โฮมฮับ</label>
                  </div>
                  <div class="form-check">
                    <input id="farmer" name="market" type="radio" class="form-check-input" value="14" required="">
                    <label class="form-check-label" for="farmer">ไทวัสดุ</label>
                  </div>
                  <div class="form-check">
                    <input id="farmer" name="market" type="radio" class="form-check-input" value="17" required="">
                    <label class="form-check-label" for="farmer">เมกา โฮม เซ็นเตอร์</label>
                  </div>
                  <div class="form-check">
                    <input id="farmer" name="market" type="radio" class="form-check-input" value="26" required="">
                    <label class="form-check-label" for="farmer">ฮาร์ดแวร์ คิง</label>
                  </div>
                  <div class="form-check">
                    <input id="farmer" name="market" type="radio" class="form-check-input" value="7" required="">
                    <label class="form-check-label" for="farmer">ฮาร์ดแวร์ เฮาส์</label>
                  </div>
                  <div class="form-check">
                    <input id="farmer" name="market" type="radio" class="form-check-input" value="2" required="">
                    <label class="form-check-label" for="farmer">โฮมโปร</label>
                  </div>
                </div>

                <!-- branch -->
                <!-- <label class="form-label" for="branch">เลือกสาขา</label> -->
                <select class="form-select" name="branch" id="branch" disabled>
                <option value=''  selected>เลือกสาขา</option>
                  
                </select>
                <!-- <div class="col-12">
                  <input type="text" class="form-control" id="marketValue" name="marketValue">
                </div> -->
                <button class="w-100 btn btn-primary btn-lg" type="submit" id="submit" name="next2">next</button>
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
  </script>
</body>

</html>

<script type="text/javascript">
  $(document).ready(function() {

    $("input[name='market']").on("click",function() {
      var MarketValue = $(this).val();
      CallBranch(MarketValue);
    })
  });
</script>

<script type="text/javascript">
  function CallBranch(MarketValue) {
    // $(".overlay").show();
    $.ajax({
      url: "ajax/ajaxwrt.php?a=callBranch", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
      type: "POST",
      data: {
        branch: MarketValue,
      },
      success: function(result) {
        var obj = jQuery.parseJSON(result);
        $.each(obj, function(key, inval) {
          $("#branch").html(inval["output"]).removeAttr("disabled");
        });
        // $(".overlay").hide();
      }
    });
  };
</script>

<script>
  function redirect() {
    $(document).ready(function() {
      // $('#insert-form2').on('submit', function(e) {
      //   e.preventDefault();
      var formp2 = new FormData($('#insert-form2')[0]);
      $.ajax({
        url: "ajax/ajaxwrt.php?a=page2", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
        type: "POST",
        data: formp2,
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#next2').val('insert...');
        },
        success: function(result) {
          // $('#insert-form')[0].reset();
          var obj = jQuery.parseJSON(result);
          $.each(obj, function(key, inval) {
            if (inval["output"]) {
              location.href = 'page_3.php';
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
        }
      })
      // });
    });
  }
</script>

<script>
  $(document).ready(function() {
    $('#submit').click(function(e) {
      e.preventDefault();
      var branch = $("#branch").val();
      var date = $("#date").val();
      $.ajax({
        type: "POST",
        url: "process/formProcess.php?a=info2",
        dataType: "json",
        data: {
          branch: branch,
          date: date,
        },
        success: function(data) {
          if (data.code == "200") {
            redirect();
            // alert("Success: " + data.output);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'กรุณาเลือก' + data.output,
              text: 'Done, we will take you to the home page!',
              timer: 3000,
              timerProgressBar: true,
            });
            // $(".display-error").html("<ul>" + data.msg + "</ul>");
            // $(".display-error").css("display", "block");
          }
        }
      });
    });
  });
</script>