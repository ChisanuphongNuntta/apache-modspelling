<style type="text/css">
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-truck-loading"></i> ตรวจสอบข้อมูล</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <input class='form-control form-control-sm' type="text" id='' name='' placeholder="เลขที่ SO / เลขที่บิล / รหัสกล่อง / รหัสสินค้า" list="TxtComplete">&nbsp;
                    <input type="date" class='form-control form-control-sm w-75' id="" value='<?php echo date("Y-m-d"); ?>'>&nbsp;
                    <button class='btn btn-sm btn-primary'><i class="fas fa-search"></i></button>
                </div>
                <datalist id="TxtComplete">
                    <option>SO-650702056</option>
                    <option>02-065-010</option>
                </datalist>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-sm">
                <table class="table table-borderless">
                    <tbody style='font-size: 13px;'>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary'>เลขที่บิล</td>
                            <td class='ps-0 pb-0'>
                                <span>IV-650730062</span>
                            </td>
                        </tr>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary'>ชื่อร้านค้า</td>
                            <td class='ps-0 pb-0'>
                                <span>M-00310 ไทยเพิ่มพูลโฮมช็อป (พังโคน)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary'>ชื่อผู้จัด</td>
                            <td class='ps-0 pb-0'>
                                <span>สังวาลย์ ศรีสมบูรณ์ (เป็ด)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary'>โต๊ะที่จัด</td>
                            <td class='ps-0 pb-0'>
                                <span>1</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row pt-1">
            <div class="col-sm">
                <table class="table table-sm table-borderless">
                    <thead style='font-size: 13px;'>
                        <tr class='fw-bold'>
                            <td>
                                <div class='d-flex align-items-center text-center'>
                                    <span style='width: 40%'>รหัสกล่อง</span>
                                    <span style='width: 40%'>ใบขนส่ง</span>
                                    <span style='width: 20%'>สถานะ</span>  
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody style='font-size: 12.5px;'>
                        <?php 
                            for($i = 1; $i <= 3; $i++) {
                                echo"<tr>
                                        <td class='pt-0 pb-1'>
                                            <div class='d-flex align-items-center text-center border border-1' style='border-radius: 10px 10px 10px 10px; padding: 6px 6px 6px 6px'>
                                                <a style='width: 40%' data-bs-toggle='collapse' href='#CollaT".$i."' role='button' aria-expanded='false' aria-controls='CollaT".$i."'>BX-22072910075</a>
                                                <span style='width: 40%'>LDN-6507778</span>
                                                <span style='width: 20%'><i class='fas fa-check'></i></span>
                                            </div>
                                            <div class='collapse ps-2 pe-2 ms-2 me-2' id='CollaT".$i."' style='padding-top: 1px; padding-bottom: 0.2px; background-color: #fff; border-radius: 0px 0px 10px 10px;'>
                                                <table class='table table-borderless mb-0'>
                                                    <tbody style='font-size: 12px;'>
                                                        <tr>
                                                            <td class='pe-0 pb-0 fw-bold text-primary'>ร้านค้า</td>
                                                            <td class='ps-0 pb-0'>
                                                                <span>M-00310 ไทยเพิ่มพูลโฮมช็อป (พังโคน)</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='pe-0 pb-0 fw-bold text-primary'>วัน/เวลาแพ็ค</td>
                                                            <td class='ps-0 pb-0'>
                                                                <span>29/07/2022 เวลา 14:25 น. [ โต๊ะที่ 1]</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='pe-0 pb-0 fw-bold text-primary'>วัน/เวลาโหลด</td>
                                                            <td class='ps-0 pb-0'>
                                                                <span>30/07/2022 เวลา 09:37 น. [สุวิทย์ (วิทย์)]</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='pe-0 fw-bold text-primary'>พนักงานขับรถ</td>
                                                            <td class='ps-0'>
                                                                <span>ชัยวัฒน์ เพชรเทศ (วัฒน์) [ฒช.8049]</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class='table table-sm table-bordere'>
                                                    <thead style='font-size: 13px;'>
                                                        <tr class='text-center fw-bold'>
                                                            <td class='pe-0'>รายการสินค้า</td>
                                                            <td class='ps-0'>จำนวน</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class='pe-0 pb-0'>
                                                                <div class='d-flex' style='font-size: 12px;'>
                                                                    <span class='fw-bold'>36-531-031</span> 
                                                                </div>
                                                                <div class='d-flex' style='font-size: 11px;'>
                                                                    <span>ใบตัดเหล็ก 14 EUROX PRO (สีเขียวคมพิเศษ)</span>
                                                                </div>
                                                            </td>
                                                            <td class='ps-0 pb-0 text-center fw-bold' style='font-size: 12px;'>50</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>";
                            }
                        ?>
                    </tbody>
                    <tfoot style='font-size: 13px;'>
                        <tr>
                            <td class='text-center fw-bold'>
                                <span>รวมจำนวนกล่อง</span>
                                <span>3/3</span>
                                <i class="fas fa-box"></i>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php require("../template/script.php"); ?>
    <script>
    </script>
<?php require("../template/footer.php"); ?>