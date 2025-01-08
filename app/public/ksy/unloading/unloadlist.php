<style type="text/css">
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-truck-loading"></i> ยกเลิกโหลดสินค้า</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <table class="table table-borderless">
                    <tbody style='font-size: 13px;'>
                        <tr>
                            <td width='30%' class='pe-0 pb-0 fw-bold text-primary'>เลขที่ใบออกรถ</td>
                            <td width='60%' class='ps-0 pb-0'>
                                <span>LDN-6511402</span>
                            </td>
                        </tr>
                        <tr>
                            <td width='30%' class='pe-0 pb-0 fw-bold text-primary'>ชื่อพนักงานขับรถ</td>
                            <td width='60%' class='ps-0 pb-0'>
                                <input class='form-control form-control-sm' type="text" id='' name=''>
                            </td>
                        </tr>
                        <tr>
                            <td width='30%' class='pe-0 pb-0 fw-bold text-primary'>ทะเบียนรถ</td>
                            <td width='60%' class='ps-0 pb-0'>
                                <input class='form-control form-control-sm' type="text" id='' name=''>
                            </td>
                        </tr>
                        <tr>
                            <td width='30%' class='pe-0 pb-0 fw-bold text-primary'>สาเหตุการคืน</td>
                            <td width='60%' class='ps-0 pb-0'>
                                <textarea class="form-control" rows="1"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row ps-2 pe-2">
            <div class="col-sm d-flex align-items-center">
                <i class="fas fa-barcode fs-3"></i>&nbsp;<input class='form-control form-control-sm' type='text' id='' name='' placeholder='รหัสบาร์โค้ดลัง'>&nbsp;&nbsp;
                <button class='btn btn-sm btn-primary'><i class='fas fa-check fa-fw fa-1x'></i></button>
            </div>
        </div>
        <div class="row ps-2 pe-2 pt-2">
            <div class="col-sm">
                <div class='tableFix'>
                    <table class='table'>
                        <thead style='font-size: 13px;'>
                            <tr class='text-center'>
                                <th class='align-baseline pe-0 bg-light'>No.</th>
                                <th class='align-baseline pe-0 bg-light'>ร้านค้า</th>
                                <th class='align-baseline pe-0 bg-light'>เลขที่ใบเสร็จ/<br>เลขที่ลัง</th>
                                <th class='align-baseline pe-0 bg-light'>ลังที่</th>
                                <th class='align-baseline bg-light'>Unload<br>
                                    <input type="hidden" name='ckBox' id='ckBox' value='0'>
                                    <input class="form-check-input" type="checkbox" id="unLckBox">
                                </th>
                            </tr>
                        </thead>
                        <tbody style='font-size: 13px;'>
                            <?php
                                $tr = "";
                                for($i = 1; $i <= 7; $i++) {
                                    $tr .="<tr>".
                                            "<td class='text-center pe-0'>".$i."</td>".
                                            "<td class='text-center pe-0'>PA-64061042</td>".
                                            "<td class='text-center pe-0'>PA-64061042</td>".
                                            "<td class='text-center pe-0'>".$i."</td>".
                                            "<td class='text-center'><input class='form-check-input ckBox' type='checkbox' disabled></td>".
                                        "</tr>";
                                }
                                echo $tr;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row pt-2 ps-2 pe-2">
            <div class="col-sm d-flex align-items-center justify-content-end">
                <button class='btn btn-sm btn-success'>ยืนยัน</button>
            </div>
        </div>
    </div>
    <?php require("../template/script.php"); ?>
    <script>
        $("#unLckBox").on("click", function() {
            if($("#ckBox").val() == "0"){
                $(".ckBox").prop('checked', true);
                $("#ckBox").val("1");
            }else{
                $(".ckBox").prop('checked', false);
                $("#ckBox").val("0");
            }
        })
    </script>
<?php require("../template/footer.php"); ?>