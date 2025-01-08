<!DOCTYPE html>
<html lang="en">

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
            <form class="card-form" id="insert-form2" method="post" action="">
                <!-- <h4 class="mb-3">ส่วนที่ 2: ข้อมูลการซื้อสินค้า</h4> -->
                <div class="col-12">
                    <div class="p-head">
                        <label for="date" class="form-label">1. วันที่ซื้อ</label>
                        <label for="brand" class="form-label sha">*</label>
                    </div>
                    <input type="date" class="form-control" id="date" name="date" value="" required="">
                </div>
                <!-- objective radio -->
                <div class="p-head">
                    <label for="objective" class="form-label">วัตถุประสงค์ในการซื้อสินค้า</label>
                    <label for="brand" class="form-label sha">*</label>
                </div>
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
                <div class="p-head">
                    <label for="market" class="form-label">2. ชื่อร้านค้าหรือห้างสรรพสินค้าที่สั่งซื้อ
                        (พร้อมระบุสาขา)</label>
                    <label for="brand" class="form-label sha">*</label>
                </div>
                <div class="my-3">
                    <div class="form-check">
                        <input id="4" name="market" type="radio" class="form-check-input" checked="" value="4" required="">
                        <label class="form-check-label" for="official">ดูโฮม</label>
                    </div>
                    <div class="form-check">
                        <input id="8" name="market" type="radio" class="form-check-input" value="8" required="">
                        <label class="form-check-label" for="employee">ไทยพิพัฒน์ทูล</label>
                    </div>
                    <div class="form-check">
                        <input id="19" name="market" type="radio" class="form-check-input" value="19" required="">
                        <label class="form-check-label" for="student">ไทยเพิ่มพูลโฮมช็อป</label>
                    </div>
                    <div class="form-check">
                        <input id="6" name="market" type="radio" class="form-check-input" value="6" required="">
                        <label class="form-check-label" for="contractor">นพดลพานิช</label>
                    </div>
                    <div class="form-check">
                        <input id="11" name="market" type="radio" class="form-check-input" value="11" required="">
                        <label class="form-check-label" for="business">เมืองเลยบิ๊กโฮม</label>
                    </div>
                    <div class="form-check">
                        <input id="21" name="market" type="radio" class="form-check-input" value="21" required="">
                        <label class="form-check-label" for="farmer">ศิริมหาชัย</label>
                    </div>
                    <div class="form-check">
                        <input id="3" name="market" type="radio" class="form-check-input" value="3" required="">
                        <label class="form-check-label" for="farmer">สยามโกลบอลเฮ้าส์</label>
                    </div>
                    <div class="form-check">
                        <input id="15" name="market" type="radio" class="form-check-input" value="5" required="">
                        <label class="form-check-label" for="farmer">โฮมฮับ</label>
                    </div>
                    <div class="form-check">
                        <input id="14" name="market" type="radio" class="form-check-input" value="14" required="">
                        <label class="form-check-label" for="farmer">ไทวัสดุ</label>
                    </div>
                    <div class="form-check">
                        <input id="17" name="market" type="radio" class="form-check-input" value="17" required="">
                        <label class="form-check-label" for="farmer">เมกา โฮม เซ็นเตอร์</label>
                    </div>
                    <div class="form-check">
                        <input id="26" name="market" type="radio" class="form-check-input" value="26" required="">
                        <label class="form-check-label" for="farmer">ฮาร์ดแวร์ คิง</label>
                    </div>
                    <div class="form-check">
                        <input id="7" name="market" type="radio" class="form-check-input" value="7" required="">
                        <label class="form-check-label" for="farmer">ฮาร์ดแวร์ เฮาส์</label>
                    </div>
                    <div class="form-check">
                        <input id="2" name="market" type="radio" class="form-check-input" value="2" required="">
                        <label class="form-check-label" for="farmer">โฮมโปร</label>
                    </div>
                    <div class="form-check">
                        <input id="0" name="market" type="radio" class="form-check-input" value="0" required="">
                        <label class="form-check-label" for="other">อื่นๆ</label>
                    </div>
                    <div><br>
                        <input type="text" class="form-control" id="otherTxt" name="otherTxt" placeholder="อื่นๆ(โปรดระบุ)" value="" required="" disabled>
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
                <a href="/warranty">ย้อนกลับ</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</body>

</html>

<script type="text/javascript">
    $(document).ready(function() {
        $('#date').focus();
        CallBranch($("input[name='market']").val());
        $("input[name='market']").on("click", function() {
            var MarketValue = $(this).val();
            if (MarketValue == 0) {
                $("#otherTxt").removeAttr("disabled").focus();
                callProvince();
            } else {
                $("#otherTxt").attr("disabled", true);
                CallBranch(MarketValue);
            }
        })
    });
</script>

<script type="text/javascript">
    function callProvince() {
        // $(".overlay").show();
        $.ajax({
            url: "ajax/ajaxwrt.php?a=callProvince", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
            type: "POST",
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
            var marketId = $("input[name='market']:checked").val();
            var otherTxt = $("#otherTxt").val();
            $.ajax({
                type: "POST",
                url: "process/formProcess.php?a=info2",
                dataType: "json",
                data: {
                    branch: branch,
                    date: date,
                    market: marketId,
                    otherTxt: otherTxt
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
                    }
                }
            });
        });
    });
</script>