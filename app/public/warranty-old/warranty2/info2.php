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
            <form class="card-form" id="insert-form2" method="post" action="">
                <!-- <h4 class="mb-3">ส่วนที่ 2: ข้อมูลการซื้อสินค้า</h4> -->
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
                    <option value='' selected>เลือกสาขา</option>

                </select>
                <button type="submit" id="next2" name="next2">ต่อไป</button>

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

<script type="text/javascript">
    $(document).ready(function() {

        $("input[name='market']").on("click", function() {
            var MarketValue = $(this).val();
            CallBranch(MarketValue);
        })
    });
</script>

<script type="text/javascript">
    function CallBranch(MarketValue) {
        // $(".overlay").show();
        $.ajax({
            url: "../ajax/ajaxwrt.php?a=callBranch", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
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
        url: "../ajax/ajaxwrt.php?a=page2", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
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
              location.href = 'info3.php';
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
        }
      })
      // });
    });
  }
</script>

<script>
  $(document).ready(function() {
    $('#next2').click(function(e) {
      e.preventDefault();
      var branch = $("#branch").val();
      var date = $("#date").val();
      $.ajax({
        type: "POST",
        url: "../process/formProcess.php?a=info2",
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