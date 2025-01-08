<style type="text/css">
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-file-alt"></i> รายการตรวจนับสินค้า</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <i class='fas fa-search' style='font-size: 20px'></i>&nbsp;&nbsp;
                    <input class='form-control form-control-sm' type="text" id='FilterBox' name='FilterBox' placeholder="เลขที่ใบโอนย้าย/สถานะ">&nbsp;
                    <button class='btn btn-sm btn-secondary' onclick="ReCall()"><i class="fas fa-sync-alt"></i></button>&nbsp;&nbsp;&nbsp;
                    <button class='btn btn-sm btn-primary' style='font-size: 11px;' onclick="TransFer('New')">New</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <table class="table table-borderless">
                    <tbody style='font-size: 13px;' id='TbodyMain'></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalTransFer" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
        <div id='IDscreeenModal' class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center pt-2 pb-2">
                    <h5 class="modal-title"><i class="fas fa-file-alt"></i> รายการตรวจนับสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="ReCall()"></button>
                </div>
                <div class="modal-body">
                  

                </div>
                <div class="modal-footer pt-1 pb-1">
                    <button type="button" class="btn btn-sm btn-primary" id='btnSave' onclick="CallData(4)">ยืนยัน</button>
                    <button type="button" class="btn btn-sm btn-secondary" id='btnCancel' onclick="CallData(6)">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>

    <!-- กรณีมากกว่า1Item -->
    <div class="modal fade" id="ModalSeItem1" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class='d-flex align-items-center justify-content-between'>
                        <div></div>
                        <h5 class="modal-title text-center">เลือกสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="my-4">
                        <table class='table table-sm'>
                            <tbody id='TbodySeItem1' style='font-size: 12px;'></tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="SlectData(1)">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalSeItem2" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class='d-flex align-items-center justify-content-between'>
                        <div></div>
                        <h5 class="modal-title text-center">เลือกสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="my-4">
                        <table class='table table-sm'>
                            <tbody id='TbodySeItem2' style='font-size: 12px;'></tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="SlectData(2)">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalAlert" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h1 class="modal-title text-center" id="HeaderModalAlert"></h1>
                    <p id="DetailModalAlert" class="my-3"></p>
                    <button type="button" class="btn btn-sm w-25 mt-4" id='ModalAlertBTN' data-bs-dismiss="modal" onclick="">ออก</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ModalCheck -->
    <div class="modal fade" id="ModalCheck" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class='d-flex align-items-center justify-content-between'>
                        <div></div>
                        <h5 class="modal-title text-center" id="ModalHeader"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <p id="ModalDetail" class="mt-4 mb-3"></p>

                    <input type="hidden" name='tranSectID' id='tranSectID'>
                    <button type="button" class="btn btn-sm btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="DelSubmit()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <?php require("../template/script.php"); ?>
    <script>
        $(document).ready(function(){
            //Call();
        });

        try{ document.createEvent("TouchEvent"); var isMobile = true; }
        catch(e){ var isMobile = false; }
    </script>
        
<?php require("../template/footer.php"); ?>