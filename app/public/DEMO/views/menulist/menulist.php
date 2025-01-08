<style>

</style>
<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="main-nav">
                <nav>
                    <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-menu-tab" data-bs-toggle="tab" data-bs-target="#nav-menu" type="button" role="tab" aria-controls="nav-menu" aria-selected="true"><i class="fas fa-list fa-fw fa-1x"></i> จัดการเมนู</button>
                        <button class="nav-link" id="nav-file-tab" data-bs-toggle="tab" data-bs-target="#nav-file" type="button" role="tab" aria-controls="nav-file" aria-selected="false" disabled><i class="fas fa-file-code fa-fw fa-1x"></i> จัดการไฟล์</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- TAB 1 -->
                    <div class="tab-pane fade show active" id="nav-menu" role="tabpanel" aria-labelledby="nav-menu-tab">
                        <div class="row mt-4">
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm" onclick="AddMenu();"><i class="fas fa-plus fa-fw fa-1x"></i> เพิ่มเมนูใหม่</button>
                            </div>
                        </div>

                        <div class="table-responsive pt-2">
                            <table class='table table-sm table-hover' id='Table1'>
                                <thead>
                                    <tr class='text-center'>
                                        <th>
                                            <div class='d-flex' style='width: 100%'>
                                                <div style='width: 10%'>ลำดับที่</div>
                                                <div style='width: 70%'>ชื่อเมนู</div>
                                                <div style='width: 20%'>ตัวจัดการ</div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- TAB 2 -->
                    <div class="tab-pane fade" id="nav-file" role="tabpanel" aria-labelledby="nav-file-tab">
                        <div class="row mt-2">
                            <div class="row">
                                <div class="col">
                                    <?php $Class = "1010010010010"; for($i = 0; $i < strlen($Class); $i++)  { echo substr($Class,$i,1); }  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MODAL ADD MENU -->
<div class='modal fade' id='ModalAddMenu' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class='modal-title text-primary'><i class="fas fa-plus"></i> เพิ่มเมนูใหม่</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
        <form class="form" id="FormAddMenu">
            <div class="modal-body" style="font-size: 14px;"> 
                <div class="row mt-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_MenuName">ชื่อเมนู<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="txt_MenuName" id="txt_MenuName" placeholder="ชื่อของเมนู" />
                            <input type="hidden" name="txt_MenuKey" id="txt_MenuKey">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_MenuLevel">ระดับของเมนู<span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="txt_MenuLevel" id="txt_MenuLevel">
                                <option value="0">ระดับ 0 เมนูหลัก</option>
                                <option value="1">---- ระดับ 1 เมนูรอง</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_HeadMenuKey">เมนูหลัก</label>
                            <select class="form-select form-select-sm" name="txt_HeadMenuKey" id="txt_HeadMenuKey" onchange='GetMenuCase();' disabled>
                                <option value='' selected disabled>กรุณาเลือก</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_MenuCase">Menu Case<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="txt_MenuCase" id="txt_MenuCase" />
                            <small id="help_MenuCase" class="text-muted">ข้อมูลสำหรับกำหนด File Path</small>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_MenuLink">Menu Link</label>
                            <input type="text" class="form-control form-control-sm" name="txt_MenuLink" id="txt_MenuLink" />
                            <small id="help_MenuCase" class="text-muted">ข้อมูลในนี้จะนำไปเป็นส่วนหนึ่งของ URL เพื่อเข้าถึงเมนูนั้น ๆ</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_MenuIcon">ไอคอน<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="txt_MenuIcon" id="txt_MenuIcon" placeholder="<i class='fax fa-xxxxx fa-fw fa-1x'></i>" />
                            <small id="help_MenuIcon" class="text-muted">ดูรายการไอคอนทั้งหมดได้ <a href="https://fontawesome.com/v5/search?m=free" target="_blank">ที่นี่</a></small>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_MenuSort">ลำดับที่<span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm text-end" name="txt_MenuSort" id="txt_MenuSort" min="0" step="any" value="0" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class='btn btn-primary btn-sm' onclick='SaveMenu();'><i class="fas fa-save"></i> บันทึก</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- MODAL APP PERMISSION -->
<div class="modal fade" id="ModalAppClass" tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h5 class='modal-title text-primary'><i class="fas fa-tasks"></i> กำหนดสิทธิ์การใช้งาน</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
        <form class="form" id="FormAppClass">
            <div class="modal-body" style="font-size: 14px;">
                <div class="row mt-2">
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_MenuType">สิทธิ์การเข้าถึง</label>
                            <select class="form-select form-select-sm" name="txt_MenuType" id="txt_MenuType">
                                <option value="A">เข้าถึงได้ทั้งหมด</option>
                                <option value="D">เข้าถึงได้เฉพาะฝ่าย</option>
                                <option value="L">เข้าถึงได้เฉพาะตำแหน่ง</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_MenuDeptCode">กำหนดสิทธิ์สำหรับฝ่าย</label>
                            <select class="form-select form-select-sm" name="txt_MenuDeptCode" id="txt_MenuDeptCode" disabled>
                                <option value="" disabled selected>กรุณาเลือก</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col" id="ClassSelect">

                    </div>
                </div>

                
            </div>
        </form>
        </div>
    </div>
</div>