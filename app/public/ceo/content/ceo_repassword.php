<style rel="stylesheet" type="text/css">
    input[type="number"] {
        letter-spacing: 0.2em;
        -webkit-text-security: disc !important;
        -moz-text-security: disc !important;
        -o-text-security: disc !important;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        -moz-appearance: none;
        -o-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }
</style>
<div class="container-fluid">
    <div class="row pt-3">
        <div class="col-sm text-center">
            <h6 style='color: #607080;'><i class="fas fa-key"></i> เปลี่ยนรหัสผ่าน</h6>
            <div class='d-flex justify-content-center'>
                <hr class='mt-1 w-75 text-muted'>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col px-4">
            <div style="text-align: center;">
                <label for="pin_1" class="my-1 mr-2 strong">กรอกรหัสผ่านใหม่</label>
                <input class="form-control form-control-lg pin text-center mb-2" id="pin_1" type="number" inputmode="numeric">
                <span id="txt_1"></span>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col px-4">
            <div style="text-align: center;">
                <label for="pin_2" class="my-1 mr-2 strong">กรุณากรอกรหัสผ่านให้ตรงกัน</label>
                <input class="form-control form-control-lg pin text-center mb-2" id="pin_2" type="number" inputmode="numeric">
                <span id="txt_2"></span>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col px-4">
            <button type="button" class="btn btn-primary w-100" onclick="RePassword();"><i class="far fa-save fa-fw fa-1x"></i> บันทึก</button>
        </div>
    </div>
</div>
<!-- MODAL SAVE SUCCESS -->
<div class="modal fade" id="confirm_saved" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="modal-title" id="confirm_header"><i class="far fa-check-circle fa-fw fa-lg text-success"></i> สำเร็จ</h5>
                <p id="confirm_Wai" class="my-4">บันทึกข้อมูลสำเร็จ</p>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-reload" data-bs-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/repassword.js"></script>