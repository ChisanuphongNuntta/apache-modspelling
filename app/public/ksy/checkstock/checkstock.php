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
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <i class="fas fa-barcode fs-4"></i>&nbsp;
                    <input class='form-control form-control-sm' type="text" id='' name='' placeholder="บาร์โค้ดสินค้า หรือบาร์โค้ดชั้นวาง" >&nbsp;
                    <button class='btn btn-sm btn-primary'><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="row pt-2 ps-2 pe-2">
            <div class="col-sm">
                <table class='table table-sm table-bordere'>
                    <thead style='font-size: 13px;'>
                        <tr class='text-center fw-bold'>
                            <td class='pe-0'>รายการสินค้าที่ต้องนับ</td>
                            <td class='ps-0'>จำนวน</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            for($i = 1; $i <= 4; $i++) {
                                echo"<tr>
                                        <td class='pe-0 pb-0'>
                                            <div class='d-flex' style='font-size: 13px;'>
                                                <a href='checkstocklist.php' class='fw-bold'>05-001-280</a> 
                                            </div>
                                            <div class='d-flex' style='font-size: 12.5px;'>
                                                <span>เจียร์ 4 นิ้ว PITA AG 720 W (สวิตซ์ท้าย)</span>
                                            </div>
                                        </td>
                                        <td class='ps-0 pb-0 text-center text-primary fw-bold' style='font-size: 13px;'>868</td>
                                    </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php require("../template/script.php"); ?>
    <script>
    </script>
<?php require("../template/footer.php"); ?>