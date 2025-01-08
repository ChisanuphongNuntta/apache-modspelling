<style type="text/css">
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-file-alt"></i> รายการนับสต๊อคสินค้า</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
            </div>
        </div>
        <div class="row ps-2 pe-2">
            <div class="col-sm">
                <table class="table table-borderless">
                    <tbody style='font-size: 13px;'>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary'>รหัสสินค้า</td>
                            <td class='pb-0' style='padding-left: 2px;'>
                                <span>05-001-280 [8859007709353]</span>
                            </td>
                        </tr>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary'>ชื่อสินค้า</td>
                            <td class='pb-0' style='padding-left: 2px;'>
                                <span>เจียร์ 4 นิ้ว PITA AG 720 W (สวิตซ์ท้าย)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary'>นับแล้ว/ทั้งหมด</td>
                            <td class='pb-0' style='padding-left: 2px;'>
                                <span>723/869</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm" style='padding-left: 20px; padding-right: 20px;'>
                <div class='d-flex align-items-center'>
                    <i class="fas fa-barcode" style='font-size: 25px;'></i>&nbsp;
                    <input class='form-control form-control-sm' type="text" id='' name='' placeholder='รหัสบาร์โค้ด'>&nbsp;
                    <input class='form-control form-control-sm w-25' type='number' id='' name='' placeholder='จำนวน'>&nbsp;
                    <button class='btn btn-sm btn-primary'>ส่ง</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                
            </div>
        </div>
    </div>
    <?php require("../template/script.php"); ?>
    <script>
    </script>
<?php require("../template/footer.php"); ?>