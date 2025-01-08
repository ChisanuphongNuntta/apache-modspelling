<style type="text/css">
</style>
<?php require("../template/header.php"); ?>

    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-cogs"></i> ตั้งค่าทั้งหมด</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
            </div>
        </div>
        <div class="row pt-2 ps-2 pe-2">
            <div class="col-auto">
                <a href='#' class="btn btn-primary p-3" onclick='ClearB1P0()'>
                    <i class="fas fa-eraser pe-5" style='font-size: 50px;'></i>
                    <br><br><br>
                    ลบคลัง B1-P0
                </a>
            </div>
        </div>
    </div>

    <?php require("../template/script.php"); ?>
    <script>
        function ClearB1P0() {
            let chk = confirm("คุณต้องการลบคลัง B1-P0 ทั้งหมดใช่หรือไม่?");
            if (chk == true) {
                $.ajax({
                    url: "ajax/ajaxsetting.php?a=ClearB1P0",
                    success: function(result) {
                        let obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) { 
                            alert("ลบข้อมูลสำเร็จ");
                        })
                    }
                })
            }
        }
    </script>
<?php require("../template/footer.php"); ?>