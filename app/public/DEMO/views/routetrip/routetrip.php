<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="row">
                <div class="col-auto">
                    <div class="form-group">
                        <label for="txt_Year">เลือกปี</label>
                        <select class='form-select form-select-sm' name="txt_Year" id="txt_Year" onchange='GetTrip();'>
                            <?php 
                            for($y = date("Y"); $y >= 2023; $y--) {
                                $op_year = ($y == date("Y")) ? "<option value='$y' selected>$y</option>" : "<option value='$y'>$y</option>";
                                echo $op_year;
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <label for="txt_Month">เลือกเดือน</label>
                        <select class='form-select form-select-sm' name="txt_Month" id="txt_Month" onchange='GetTrip();'>
                            <?php 
                            for($m = 1; $m <= 12; $m++) {
                                $op_month = ($m == date("m")) ? "<option value='$m' selected>".FullMonth($m)."</option>" : "<option value='$m'>".FullMonth($m)."</option>";
                                echo $op_month;
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group txt-CardCode-color">
                        <label for="txt_SlpCode">เลือกพนักงาน</label>
                        <select class='form-control form-control-sm selectpicker' name="txt_SlpCode" id="txt_SlpCode" onchange='GetTrip();' data-live-search="true">
                            <option value='' selected>เลือกพนักงาน</option>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <label for="">เลือกมุมมอง</label>
                        <select class='form-select form-select-sm' name="txt_View" id="txt_View" onchange='GetTrip();'>
                            <option value="GRID" selected>ปฏิทินแผนงาน</option>
                            <option value="LIST">รายการแผนงาน</option>
                        </select>
                    </div>
                </div>
                <div class="col d-flex justify-content-end ">
                    <div class="form-group ps-2 pe-2">
                        <label for=""></label>
                        <button class="btn btn-sm btn-danger w-100" onclick='AddNewPlan("show");'><i class="fas fa-flag-checkered fa-fw fa-1x"></i> เพิ่มแผนงานใหม่</button>
                    </div>
                    <div class="form-group ps-2 pe-2">
                        <label for=""></label>
                        <button class="btn btn-sm btn-success w-100" disabled><i class="fas fa-print fa-fw fa-1x"></i> พิมพ์</button>
                    </div>
                </div>
            </div>
            
            <div id="view_worktrip" class="mt-3"></div>
        </div>
    </div>
</section>

<div class='modal fade' id='ModalNewPlan' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class="modal-title text-primary"><i class="fas fa-flag-checkered fa-fw fa-1x"></i> เพิ่มแผนงานใหม่</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body' style='font-size: 14px;'>
                <div class="row">
                    <div class="col">
                        <div class="form-group txt-CardCode-color">
                            <label for="txt_CardCode">ชื่อลูกค้า<span class="text-danger">*</span></label>
                            <select class='form-control form-control-sm selectpicker' name="txt_CardCode" id="txt_CardCode" data-live-search="true">
                                <option value='' selected disabled>กรุณาเลือก</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="txt_PlanStartDate" class="col-2 col-form-label col-form-label-sm">วันที่นัดหมาย</label>
                    <div class="col-5">
                        <input type="date" class="form-control form-control-sm text-center" name="txt_PlanStartDate" id="txt_PlanStartDate" value="<?php echo date("Y-m-d"); ?>" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="txt_PlanStartTime" class="col-2 col-form-label col-form-label-sm">เวลาที่นัดหมาย</label>
                    <div class="col-2">
                        <input type="time" class="form-control form-control-sm text-center" name="txt_PlanStartTime" id="txt_PlanStartTime" value="00:00" />
                    </div>
                    <label for="txt_PlanEndTime" class="col-1 col-form-label col-form-label-sm text-center">ถึง</label>
                    <div class="col-2">
                        <input type="time" class="form-control form-control-sm text-center" name="txt_PlanEndTime" id="txt_PlanEndTime" value="23:59" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="txt_ContactName" class="col-2 col-form-label col-form-label-sm">ชื่อผู้ติดต่อ</label>
                    <div class="col-5">
                        <input type="text" class="form-control form-control-sm" name="txt_ContactName" id="txt_ContactName" maxlength="100" placeholder="ชื่อผู้ติดต่อ" />
                    </div>
                    <label for="txt_ContactPhone" class="col-2 col-form-label col-form-label-sm">เบอร์โทรศัพท์</label>
                    <div class="col-3">
                        <input type="tel" class="form-control form-control-sm" name="txt_ContactPhone" id="txt_ContactPhone" maxlength="10" placeholder="หมายเลขโทรศัพท์" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="txt_ContactEmail" class="col-2 col-form-label col-form-label-sm">E-mail</label>
                    <div class="col-5">
                        <input type="email" class="form-control form-control-sm" name="txt_ContactEmail" id="txt_ContactEmail" placeholder="E-mail" />
                    </div>
                    <label for="txt_ContactLINE" class="col-2 col-form-label col-form-label-sm">LINE ID</label>
                    <div class="col-3">
                        <input type="text" class="form-control form-control-sm" name="txt_ContactLINE" id="txt_ContactLINE" placeholder="LINE ID" />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="txt_PlanDetail">รายละเอียดแผนงาน</label>
                            <textarea class='form-control' name="txt_PlanDetail" id="txt_PlanDetail" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-primary btn-sm' onclick='AddNewPlan("SAVE");'><i class="fas fa-save fa-fw fa-1x"></i> บันทึก</button>
            </div>
        </div>
    </div>
</div>

<div class='modal fade' id='ModalDPlan' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-xl'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class="modal-title text-primary"></h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body' style='font-size: 12px;'>
                <div class="table-responsive">
                    <table class='table table-sm table-hover table-bordered' style='font-size: 12px;' id='TableDPlan'>
                        <thead>
                            <tr class='text-center'>
                                <th width="5%">ลำดับ</th>
                                <th width="6.5%%">ช่วงเวลา</th>
                                <th width="35%">ชื่อลูกค้า</th>
                                <th width="35%">รายละเอียดแผนงาน</th>
                                <th width="10%">สถานะ</th>
                                <th width="7.5%"><i class="fas fa-cog fa-fw fa-1x"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan='6' class='text-center'>ไม่มีข้อมูล :(</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary btn-sm' data-bs-dismiss='modal'>ออก</button>
            </div>
        </div>
    </div>
</div>

<div class='modal fade' id='ModalCheckInReport' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog modal-xl'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class="modal-title text-primary"><i class="fas fa-file-alt fa-fw fa-lg"></i> รายงานการเข้าพบลูกค้า</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body' style='font-size: 13px;'>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <th>ร้านค้า</th>
                                    <td id="RptCardCode" colspan='3'></td>
                                </tr>
                                <tr>
                                    <th>วันที่จะเข้าพบ</th>
                                    <td id="RptPlanDate" colspan='3'></td>
                                </tr>
                                <tr>
                                    <th width='10%'>ชื่อผู้ติดต่อ</th>
                                    <td id="RptConName" width='40%'></td>
                                    <th width='10%'>เบอร์โทรศัพท์</th>
                                    <td id="RptConPhone" width='40%'></td>
                                </tr>
                                <tr>
                                    <th>E-mail</th>
                                    <td id="RptConEmail"></td>
                                    <th>LINE ID</th>
                                    <td id="RptConLine"></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr class='text-center'>
                                <th width='50%'>รายละเอียดแผนงาน</th>
                                <th width='50%'>สรุปผลการเข้าพบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-top" id='RptPDetail' style='height: 100px;'></td>
                                <td class="align-top" style='height: 100px;'>
                                    <textarea class="form-control form-control-sm" rows="4" id='RptADetail'></textarea>
                                    <small class="text-danger">* นำเม้าส์คลิกบริเวณที่ว่างเพื่อบันทึกค่า</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="10%">วันที่เข้าพบ</th>
                            <td id="RptAStart" colspan='2'></td>
                        </tr>
                        <tr>
                            <th>ระยะทาง</th>
                            <td id="RptChkDis" colspan='2'></td>
                        </tr>
                        <tr>
                            <th class="align-top">พิกัดที่เช็คอิน</th>
                            <td><div id="RptCheckInMaps" style="height: 25rem; border: 1px solid #000;"></div></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CHECKIN -->
<div class="modal fade" id="ModalCheckIn" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="CheckInTrip">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-map-marker-alt fa-fw fa-1x"></i> เช็คอิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row mt-2">
                <div class="col-12">
                    <h6>ร้านค้า: <span id="ChkCardName"></span> <small class='text-muted'>&mdash; <span id="GetDistance"></span></small></h6>
                    <input type="hidden" name="ChkRouteEty" id="ChkRouteEty" value="0" readonly />
                    <input type="hidden" name="ChkLon" id="ChkLon" readonly />
                    <input type="hidden" name="ChkLat" id="ChkLat" readonly />
                    <input type="hidden" name="PlanLon" id="PlanLon" readonly />
                    <input type="hidden" name="PlanLat" id="PlanLat" readonly />
                    <input type="hidden" name="ChkCardCode" id="ChkCardCode" readonly />
                    <input type="hidden" name="TarDistance" id="TarDistance" readonly />
                    <input type="hidden" name="ChkDistance" id="ChkDistance" readonly />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12" id="CheckInMaps" style="height: 25rem;"></div>
            </div>
            <div class="row mt-4">
                <div class="col text-end">
                    <button type="button" class="btn btn-success w-100" id="btn-checkin"><i class="fas fa-map-marker-alt fa-fw fa-lg"></i> เช็คอิน</button>
                </div>
            </div>
            </div>
        </div>
        </form>
    </div>
</div>
