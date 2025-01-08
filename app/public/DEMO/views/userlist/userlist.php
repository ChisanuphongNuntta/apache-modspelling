<style>
.txt-CardCode-color div button {
    background-color: transparent !important;
    border: 1px solid #eee !important;
}
</style>
<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="row pt-2">
                <div class="col">
                    <button class="btn btn-primary btn-sm" onclick="AddMember();"><i class="fas fa-user-plus"></i> เพิ่มผู้ใช้งานใหม่</button>
                </div>
            </div>
            <div class="row pt-4">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="">เลือกฝ่าย</label>
                        <select class="form-select form-select-sm" id="filt_dept" onchange='GetUserList();'>
                            <option value="ALL" selected>ทุกฝ่าย</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="">สถานะของผู้ใช้งาน</label>
                        <select class="form-select form-select-sm" id="filt_status" onchange='GetUserList();'>
                            <option value="ALL" selected>ทั้งหมด</option>
                            <option value="A">Active</option>
                            <option value="I">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row pt-2">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm DataTable" id="UserList">
                            <thead>
                                <th class="text-center" width="3.5%">No.</th>
                                <th class="text-center" width="10%">รหัสพนักงาน</th>
                                <th class="text-center">ชื่อพนักงาน</th>
                                <th class="text-center" width="10%">ฝ่าย</th>
                                <th class="text-center" width="15%">ตำแหน่ง</th>
                                <th class="text-center" width="12.5%">Username</th>
                                <th class="text-center" width="7.5%">Status</th>
                                <th class="text-center" width="7.5%"><i class="fas fa-cogs"></i></th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class='modal fade' id='ModalAddMember' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class='modal-title text-primary'><i class="fas fa-user-plus"></i> เพิ่มผู้ใช้งานใหม่</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
        <form class="form" id="FormAddMember" enctype="multipart/form-data">
            <div class="modal-body" style="font-size: 14px;"> 
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="txt_TH_uFirstName">ชื่อ (ภาษาไทย)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="txt_TH_uFirstName" id="txt_TH_uFirstName" required>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="txt_TH_uLastName">นามสกุล (ภาษาไทย)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="txt_TH_uLastName" id="txt_TH_uLastName" required>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="txt_uNickName">ชื่อเล่น</label>
                            <input type="text" class="form-control form-control-sm" name="txt_uNickName" id="txt_uNickName">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="txt_EN_uFirstName">ชื่อ (ภาษาอังกฤษ)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="txt_EN_uFirstName" id="txt_EN_uFirstName" required>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label for="txt_EN_uLastName">นามสกุล (ภาษาอังกฤษ)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="txt_EN_uLastName" id="txt_EN_uLastName" required>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="txt_uGender">เพศ<span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="txt_uGender" id="txt_uGender" required>
                                <option value="" selected disabled>กรุณาเลือก</option>
                                <option value="M">ชาย</option>
                                <option value="F">หญิง</option>
                                <option value="O">อื่น ๆ</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="txt_EmpCode">รหัสพนักงาน</label>
                            <input type="text" class="form-control form-control-sm" name="txt_EmpCode" id="txt_EmpCode">
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="txt_DeptCode">ฝ่าย<span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="txt_DeptCode" id="txt_DeptCode">
                                <option value="" selected disabled>กรุณาเลือก</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="txt_LvCode">ตำแหน่ง<span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="txt_LvCode" id="txt_LvCode" disabled>
                                <option value="" selected disabled>กรุณาเลือก</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="txt_uMobileNo">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-control form-control-sm" name="txt_uMobileNo" id="txt_uMobileNo">
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="txt_uEmailAddr">อีเมลล์</label>
                            <input type="email" class="form-control form-control-sm" name="txt_uEmailAddr" id="txt_uEmailAddr">
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="txt_uLineID">LINE ID</label>
                            <input type="text" class="form-control form-control-sm" name="txt_uLineID" id="txt_uLineID">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="txt_uWorkStartDate">วันที่เริ่มงาน<span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" name="txt_uWorkStartDate" id="txt_uWorkStartDate" value="<?php echo date("Y-m-d"); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="txt_uBirthdate">วันเกิด<span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" name="txt_uBirthdate" id="txt_uBirthdate" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="txt_uPhoto">รูปโปรไฟล์</label>
                            <input type="file" class="form-control form-control-sm" name="txt_uPhoto" id="txt_uPhoto" accept="image/jpeg">
                            <small class="text-muted">.jpg file support.</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="txt_UserName">Username<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm text-success text-center" name="txt_UserName" id="txt_UserName" required readonly>
                            <small id="txt_usnm"></small>
                            <input type="hidden" name="txt_uKey" id="txt_uKey" value="" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="txt_UserPswd">Password<span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm text-success text-center" name="txt_UserPswd" id="txt_UserPswd" required readonly>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="txt_uSign">รูปลายเซ็นต์</label>
                            <input type="file" class="form-control form-control-sm" name="txt_uSign" id="txt_uSign" accept="image/jpeg">
                            <small class="text-muted">.jpg file support.</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle fa-fw fa-2x"></i>
                            <i>Username และ Password ระบบจะสร้างอัตโนมัติจาก ชื่อ-นามสกุล (ภาษาอังกฤษ) และวันเกิดของผู้ใช้งาน</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class='btn btn-primary btn-sm' onclick='SaveMember();'><i class="fas fa-save"></i> บันทึก</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal" id="ModalProfile" tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
	<div class="modal-dialog">
		<div class="modal-content">
      		<div class="modal-body">
        		<div class="row">
  					<div class="col text-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  					</div>
				</div>
        		<div class="row">
  					<div class="col text-center">
						  <div id="image_demo" ></div>
  					</div>
				</div>
        		<div class="row">
  					<div class="col text-center">
                        <button class="btn btn-sm btn-success" onclick='DfImage();'>ตกลง</button>
  					</div>
				</div>
      		</div>
    	</div>
    </div>
</div>