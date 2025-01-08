<section>
    <div class="container" style='padding-top: 120px;'>
        <div class="row">
            <div class="col-lg">
                <div class="card w-100 h-100">
                    <div class="card-body pt-0 d-flex align-items-center justify-content-center ">
                        <div class="row" style='width: 100%'>
                            <div class="col-lg h-100 card-profile">
                                <div class='text-center'>
                                    <input type="file" class='d-none' name='AddIMG' id='AddIMG' accept=".jpg,.jpeg,.png">
                                    
                                    <a href="javascript:void(0);" class='show-avatar' onclick="EditIMG();"><img class="avatar-rounded img_profile" src="<?php echo $avatar; ?>"></a>
                                </div>
                                <div class='text-center pt-2'>
                                    <span class='text-center text-dark fw-bolder' style='font-size: 22px;' id='HeaderNameProfile'></span>
                                </div>
                                <div class='text-center pt-2'>
                                    <span class='text-center fw-bolder' style='font-size: 20px;' id='HeaderLvNameProfile'></span>
                                </div>

                                <div class='container container-profile pt-5 pb-5'>
                                    <form class="form" id="FormProfile" enctype="multipart/form-data">
                                        <input type="hidden" name="txt_uKey" id="txt_uKey" value="<?php echo $_SESSION['UKEY']; ?>" readonly>
                                        <input type="file" class="form-control form-control-sm d-none" name="txt_uPhoto" id="txt_uPhoto" accept="image/jpeg" readonly>
                                        <div class="row">
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_TH_uFirstName">ชื่อ (ภาษาไทย)<span></span></label>
                                                    <input type="text" class='form-control form-control-sm input-profile ' name='txt_TH_uFirstName' id='txt_TH_uFirstName' disabled />
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_TH_uLastName">นามสกุล (ภาษาไทย)<span></span></label>
                                                    <input type="text" class='form-control form-control-sm input-profile' name='txt_TH_uLastName' id='txt_TH_uLastName' disabled >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_uNickName">ชื่อเล่น (ภาษาไทย)</label>
                                                    <input type="text" class='form-control form-control-sm input-profile' name='txt_uNickName' id='txt_uNickName' disabled >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_EN_uFirstName">ชื่อ (English)<span></span></label>
                                                    <input type="text" class='form-control form-control-sm input-profile' name='txt_EN_uFirstName' id='txt_EN_uFirstName' disabled >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_EN_uLastName">นามสกุล (English)<span></span></label>
                                                    <input type="text" class='form-control form-control-sm input-profile' name='txt_EN_uLastName' id='txt_EN_uLastName' disabled >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_uGender">เพศ<span></span></label>
                                                    <select class='form-select form-select-sm input-profile' name='txt_uGender' id='txt_uGender' disabled >
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
                                                    <label for="txt_uWorkStartDate">วันที่เริ่มงาน <span></span></label>
                                                    <input type="date" class='form-control form-control-sm' name='txt_uWorkStartDate' id='txt_uWorkStartDate' readonly >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_uBirthdate">วันเกิด<span></span></label>
                                                    <input type="date" class='form-control form-control-sm' name='txt_uBirthdate' id='txt_uBirthdate' readonly >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_EmpCode">รหัสพนักงาน <span></span></label>
                                                    <input type="text" class='form-control form-control-sm' name='txt_EmpCode' id='txt_EmpCode' readonly >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_DeptCode">ฝ่าย <span></span></label>
                                                    <input type="text" class='form-control form-control-sm' name='txt_DeptCode' id='txt_DeptCode' readonly >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_LvCode">ตำแหน่ง <span></span></label>
                                                    <input type="text" class='form-control form-control-sm' name='txt_LvCode' id='txt_LvCode' readonly >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_uMobileNo">เบอร์โทรศัพท์</label>
                                                    <input type="tel" class='form-control form-control-sm input-profile' name='txt_uMobileNo' id='txt_uMobileNo' disabled >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_uEmailAddr">E-mail</label>
                                                    <input type="email" class='form-control form-control-sm input-profile' name='txt_uEmailAddr' id='txt_uEmailAddr' disabled >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_uLineID">LINE ID</label>
                                                    <input type="text" class='form-control form-control-sm input-profile' name='txt_uLineID' id='txt_uLineID' disabled >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="">รูปลายเซ็นต์</label>
                                                    <input type="file" class='form-control form-control-sm input-profile' name='txt_uSign' id='txt_uSign' accept="image/jpg" onchange="ShowExFile()" disabled >
                                                    <div id="showimg" class="carousel slide" data-bs-touch="false" data-bs-interval="false">
                                                        <?php if(file_exists("../images/signature/".$_SESSION['UKEY'].".jpg")) { ?>
                                                        <div class='carousel-inner'>
                                                            <div class='carousel-item active text-center p-2'>
                                                                <img src='../images/signature/<?php echo $_SESSION['UKEY'].".jpg"; ?>' style='width: 100%;'>
                                                                <div class='carousel-caption d-none d-md-block'></div>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_password">เปลี่ยนรหัสผ่าน</label>
                                                    <input type="text" class='form-control form-control-sm input-profile' name='txt_password' id='txt_password' disabled >
                                                </div>
                                            </div>
                                            <div class="col-lg">
                                                <div class="form-group">
                                                    <label for="txt_con_password">กรอกรหัสผ่านอีกครั้ง</label>
                                                    <input type="password" class='form-control form-control-sm input-profile' name='txt_con_password' id='txt_con_password' disabled >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pt-2">
                                            <div class="col text-center">
                                                <a type='submit' class='btn btn-sm btn-primary EditProfile' style='font-size: 17px;' href="javascript:void(0);" data-edit='Edit' onclick="EditProfile();"><i class="fas fa-pen"></i> แก้ไขโปรไฟล์</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
                        <button class="btn btn-success crop_image">บันทึกภาพโปรไฟล์</button>
  					</div>
				</div>
      		</div>
    	</div>
    </div>
</div>