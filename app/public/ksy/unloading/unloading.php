<style type="text/css">
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-truck-loading"></i> โหลดสินค้าลง</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <i class='fas fa-search' style='font-size: 20px'></i>&nbsp;&nbsp;
                    <input class='form-control form-control-sm' type="text" id='' name='' placeholder="เลขที่ SO / ช่องทางขาย / สายส่ง / สถานะ" list="TxtComplete">&nbsp;
                    <button class='btn btn-sm btn-secondary'><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-sm">
                <table class="table table-borderless">
                    <tbody style='font-size: 13px;'>
                        <?php
                            $Data = "";
                            for($i = 1; $i <= 7; $i++) {
                                $Data .="<tr>".
                                            "<td class='pb-0'>".
                                                "<div class='ps-2 pe-2 pt-2 border border-1' style='border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px #fff; background-color: #fff;'>".
                                                    "<div class='d-flex justify-content-aroundent'>".
                                                        "<div class='' style='width: 100%'>".
                                                            "<span class='fw-bolder'>เลขที่โหลด</span> <a class='fw-bold TransFer' data-TransFer='WH-".$i."' href='unloadlist.php'>LDN-6511403</a>".
                                                        "</div>".
                                                    "</div>".
                                                "</div>".
                                                "<div class='p-2 border border-1' style='border-radius: 0px 0px 10px 10px; box-shadow: 1px 1px #fff;'>".
                                                    "<div class='d-flex justify-content-aroundent'>".
                                                        "<div class='' style='width: 100%'>".
                                                            "<span class='fw-bolder'>ชื่อคนขับ</span> <span>กฤษณลักษณ์ เพชรเทศ (อ้อย)</span>".
                                                        "</div>".
                                                    "</div>".
                                                    "<div class='d-flex justify-content-aroundent'>".
                                                        "<div class='' style='width: 50%'>".
                                                            "<span class='fw-bolder'>วันที่สายส่ง</span> <span>17/06/2021</span>".
                                                        "</div>".
                                                        "<div class='' style='width: 50%'>".
                                                            "<span class='fw-bolder'>ทะเบียนรถ</span> <span>ฆม.2403</span>".
                                                        "</div>".
                                                    "</div>".
                                                "</div>".
                                            "</td>".
                                        "</tr>";
                            }
                            echo $Data;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php require("../template/script.php"); ?>
<?php require("../template/footer.php"); ?>