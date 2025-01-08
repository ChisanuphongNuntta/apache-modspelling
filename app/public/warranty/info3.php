<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('header.php'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
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
            <form class="card-form" id="insert-form3" method="post" action="">
                <div class="p-head">
                    <label for="brand" class="form-label">แบรนด์สินค้า</label>
                    <label for="brand" class="form-label sha">*</label>
                </div>
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
                <div class="p-head">
                    <label for="product" class="form-label">ประเภทสินค้าที่สั่งซื้อ</label>
                    <label for="brand" class="form-label sha">*</label>
                </div>
                <div id="productdiv">
                    <!-- callProduct -->
                </div>
                <!--  -->
                <div>
                    <input type="text" class="form-control" id="nameProduct" name="nameProduct" placeholder="ชื่อสินค้า หรือรุ่นของสินค้า *" value="" required="">
                </div>
                <div>
                    <input type="text" class="form-control" id="serialNumber" name="serialNumber" placeholder="หมายเลขเครื่อง (Serial Number) (ถ้ามี)" value="">
                </div>
                <button type="submit" id="next3" name="next3">ลงทะเบียน</button>
                <a class="btn-back" href="/warranty/info2.php">ย้อนกลับ</a>
            </form>
        </div>
    </div>
    <!-- modal -->
    <div class="modal" tabindex="-1" id="modalDialogX">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ผู้ลงทะเบียน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="iconX" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modal-form" method="post">
                        <div class="form-check">
                            <input id="user" name="status" type="radio" class="form-check-input" checked="" value="User" required="">
                            <label class="form-check-label" for="KING">บุคลทั่วไป</label>
                        </div>
                        <div class="form-check">
                            <input id="pc" name="status" type="radio" class="form-check-input" value="PC" required="">
                            <label class="form-check-label" for="EUROX">พนักงานขาย</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnModalX">Close</button>
                            <button type="submit" class="btn btn-danger" id="saveModal">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</body>

</html>

<script type="text/javascript">
    function callModal() {
        var modal = $('#modalDialogX');
        var btn = $("#saveModal");
        var btnX = $("#btnModalX");
        var iconX = $("#iconX");
        var span = $(".close");
        $(document).ready(function() {
            modal.show();
            btn.on('click', function() {
                $('#modal-form').on('submit', function(e) {
                    e.preventDefault();
                    var formModal = new FormData($('#modal-form')[0]);
                    $.ajax({
                        url: "ajax/ajaxwrt.php?a=modal", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
                        type: "POST",
                        data: formModal,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#saveModal').val('insert...');
                        },
                        success: function(result) {
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
            span.on('click', function() {
                modal.hide();
            });
            btnX.on('click', function() {
                modal.hide();
            });
            iconX.on('click', function() {
                modal.hide();
            });

        });
        $('body').bind('click', function(e) {
            if ($(e.target).hasClass("modal")) {
                modal.hide();
            }
        });
    }
</script>

<script>
    $("input[name='brand'").on("click", function() {
        var brandValue = $(this).val();
        if (brandValue == 'other') {
            $("#otherTxt").removeAttr("disabled").focus();
        } else {
            $("#otherTxt").attr("disabled", true);
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var productValue = $('#product').val();
        $.ajax({
            url: "ajax/ajaxwrt.php?a=callProduct", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
            type: "POST",
            data: {
                product: productValue,
            },
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    $("#productdiv").html(inval["output"]);
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
                            callModal();
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