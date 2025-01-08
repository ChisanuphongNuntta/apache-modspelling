<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="row">
                <div class="col-sm col-lg-5 d-flex ">
                    <div class='search-color d-flex w-100 align-items-center p-1 pe-0 border border-end-0' style='border-radius: 20px 0px 0px 20px;'>
                        &nbsp;&nbsp;&nbsp;
                        <i class="fas fa-users"></i>
                        &nbsp;
                        <select type="text" class='border-0 selectpicker form-control form-control-sm' placeholder='เลือกลูกค้า...' onchange="GetDetail();" name='txt_CardCode' id='txt_CardCode' data-live-search="true"></select>
                        &nbsp;
                    </div>
                    <button class='search-color-hover bg-primary d-flex align-items-center border border-start-0 pe-2 ps-3' OnClick="GetDetail();" style='border-radius: 0px 20px 20px 0px; cursor: pointer; z-index: 1;'>
                        <i class="fas fa-search text-white"></i>
                        &nbsp;&nbsp;
                    </button>
                </div>
            </div>

            <div class="table-responsive pt-4">
                <table class='table table-sm table-borderless rounded rounded-2 overflow-hidden'>
                    <thead>
                        <tr class='bg-primary'>
                            <th colspan='6' class='text-white p-2'>ข้อมูลลูกค้า</th>
                        </tr>
                    </thead>
                    <tbody class='table-primary'>
                        <tr>
                            <th width="10%" class="ps-2 pt-2">รหัสลูกค้า</th>
                            <td width="15%" id="view_CardCode"></td>
                            <th width="10%">ชื่อลูกค้า</th>
                            <td id="view_CardName"></td>
                            <th width="10%" class="pe-2 pt-2">เลขที่ประจำตัวผู้เสียภาษี</th>
                            <td width="15%" id="view_LicTradNum"></td>
                        </tr>
                        <tr>
                            <th class="ps-2 pt-2">กลุ่มลูกค้า</th>
                            <td id="view_GroupName"></td>
                            <th>พนักงานขาย</th>
                            <td id="view_SlpName"></td>
                            <th class="pe-2 pt-2">เงื่อนไขการชำระเงิน</th>
                            <td id="view_PymntGroup"></td>
                        </tr>
                        <tr>
                            <th class="ps-2 pt-2">ที่อยู่</th>
                            <td colspan="3" id="view_Address"></td>
                            <th class="pe-2 pt-2">หมายเลขโทรศัพท์</th>
                            <td id="view_Contact"></td>
                        </tr>
                        <tr>
                            <th class="ps-2 pt-2">เครดิตที่ใช้ไป (บาท)</th>
                            <td colspan="5" class="text-center pe-2 pt-2">
                                <div class="progress" style="height: 24px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="view_CreditPcnt" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted" id="view_CreditText">0/0</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row pt-2">
                <div class="col">
                    <nav>
                        <div class="nav nav-tabs" role="tablist" style='font-size: 14px;'>
                            <button class="nav-link active" id="T1-tab" data-bs-toggle="tab" data-bs-target="#T1" type="button" role="tab" aria-controls="T1" aria-selected="false"><i class="fas fa-dollar-sign fa-fw fa-1x"></i> ยอดขาย</button>
                            <button class="nav-link " id="T2-tab" data-bs-toggle="tab" data-bs-target="#T2" type="button" role="tab" aria-controls="T2" aria-selected="false"><i class="fas fa-file-invoice fa-fw fa-1x"></i> หนี้เกินกำหนด</button>
                            <button class="nav-link " id="T3-tab" data-bs-toggle="tab" data-bs-target="#T3" type="button" role="tab" aria-controls="T3" aria-selected="false" disabled>ประวัติสินค้า</button>
                            <button class="nav-link " id="T4-tab" data-bs-toggle="tab" data-bs-target="#T4" type="button" role="tab" aria-controls="T4" aria-selected="false" disabled>ประวัติการสั่งซื้อสินค้า</button>
                            <button class="nav-link " id="T5-tab" data-bs-toggle="tab" data-bs-target="#T5" type="button" role="tab" aria-controls="T5" aria-selected="false"><i class="fas fa-history fa-fw fa-1x"></i> ประวัติการเข้าพบ</button>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="row pt-2">
                <div class="col">
                    <div class="tab-content" id="nav-tabContent">
                    <!-- ยอดขายร้านค้า -->
                        <div class="tab-pane fade show active" id="T1" role="tabpanel" aria-labelledby="T1-tab">
                            <div class="table-responsive">
                                <table class='table table-sm table-bordered rounded rounded-2 overflow-hidden' style='font-size: 13px;'>
                                    <thead class='table-primary text-center'>
                                        <tr>
                                            <th colspan='13' class='pt-1 pb-1'>ยอดขายรายเดือนของร้านค้า (ปีปัจจุบันและย้อนหลัง 1 ปี)</th>
                                            <th colspan='2' class='pt-1 pb-1'>สรุปผล</th>
                                        </tr>
                                        <tr>
                                            <th width='8%'>ปี</th>
                                            <?php  for($m = 1; $m <= 12; $m++){ echo "<th width='6.33%'>".txtMonth($m)."</th>"; } ?>
                                            <th width='8%'>รวมทั้งหมด</th>
                                            <th width='8%'>เฉลี่ยต่อเดือน</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <tr>
                                            <?php 
                                                echo "<td class='fw-bolder'>ยอดขาย ".date("Y")."</td>";
                                                for($i = 1; $i <= 14; $i++){ echo "<td class='text-end' id='data_cm".$i."'></td>"; }
                                            ?>
                                        </tr>
                                        <tr>
                                            <?php 
                                                echo "<td class='fw-bolder'>ยอดขาย ".(date("Y")-1)."</td>";
                                                for($i = 1; $i <= 14; $i++){ echo "<td class='text-end' id='data_pm".$i."'></td>"; }
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <!-- หนี้เกินกำหนด -->
                        <div class="tab-pane fade" id="T2" role="tabpanel" aria-labelledby="T2-tab">
                            <div class="table-responsive">
                                <table class='table table-sm table-bordered rounded rounded-2 overflow-hidden' id="view_BillOverDue" style='font-size: 13px;'>
                                    <thead class='table-primary text-center'>
                                        <tr>
                                            <th width='5%'>ลำดับ</th>
                                            <th width='10%'>เลขที่บิล</th>
                                            <th width='10%'>วันที่บิล</th>
                                            <th width='10%'>วันที่กำหนดชำระ</th>
                                            <th width='7.5%'>เกินกำหนด (วัน)</th>
                                            <th width=''>ชื่อลูกค้า</th>
                                            <th width='10%'>มูลค่าท้ายบิล</th>
                                            <th width='10%'>มูลค่าค้างชำระ</th>
                                        </tr>
                                    </thead>
                                    <tbody><tr><td colspan="8" class="text-center">กรุณาเลือกลูกค้า</td></tr></tbody>
                                </table>
                            </div>
                        </div>
                    <!-- ประวัติสินค้า -->
                        <div class="tab-pane fade " id="T3" role="tabpanel" aria-labelledby="T3-tab">
                            <div class='row'>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for=""><i class="fab fa-searchengin"></i> ค้นหาประวัติสินค้า</label>
                                        <div class='d-flex'>
                                            <select class='form-select form-select-sm' name="" id="">
                                                <option value="" selected disabled>เลือกสินค้า</option>
                                            </select>&nbsp;
                                            <button class='btn btn-sm btn-primary w-auto'><i class="fas fa-search fa-rotate-90"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for=""></label>
                                        <button class='btn btn-sm btn-secondary w-100' onclick='CallStock();'><i class="fas fa-warehouse"></i> สินค้าคงคลัง</button>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label for=""></label>
                                        <button class='btn btn-sm btn-warning w-100' onclick='HisItem10();'><i class="fas fa-hand-holding-usd"></i> ประวัติการสั่งซื้อสินค้า (10 รายการล่าสุด)</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class='table table-sm table-bordered rounded rounded-2 overflow-hidden' style='font-size: 13px;'>
                                    <thead class='table-primary'>
                                        <tr class='text-center'>
                                            <th>เลขที่บิล</th>
                                            <th>วันที่ออกบิล</th>
                                            <th>รหัสสินค้า</th>
                                            <th>ชื่อสินค้า</th>
                                            <th>คลังสินค้า</th>
                                            <th>จำนวน</th>
                                            <th>มูลค่าต่อชิ้น</th>
                                            <th>ภาษีรวม</th>
                                            <th>ราคาสุทธิ (VAT)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($t = 1; $t <= 10; $t++) { ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <!-- ประวัติการสั่งซื้อสินค้า -->
                        <div class="tab-pane fade " id="T4" role="tabpanel" aria-labelledby="T4-tab">
                            <div class="table-responsive">
                                <table class='table table-sm table-bordered rounded rounded-2 overflow-hidden' style='font-size: 13px;'>
                                    <thead class='table-primary text-center'>
                                        <tr>
                                            <th rowspan='2'>รหัสสินค้า</th>
                                            <th rowspan='2'>ชื่อสินค้า</th>
                                            <th rowspan='2'>หน่วยขาย</th>
                                            <th colspan='13'>ยอดขาย (หน่วย)</th>
                                        </tr>
                                        <tr>
                                            <?php for($m = 1; $m <= 12; $m++) { ?>
                                                <th><?php echo txtMonth($m); ?></th>
                                            <?php } ?>
                                            <th>รวม</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($r = 1; $r <= 10; $r++) { ?>
                                            <tr>
                                                <td class='text-center'>03-002-<?php echo rand(10,100); ?></td>
                                                <td><?php echo rand(10,100); ?> ม./ม้วน</td>
                                                <td class='text-center'>เมตร</td>
                                                <?php for($m = 1; $m <= 12; $m++) { ?>
                                                    <td class='text-end'><?php echo rand(10,100); ?></td>
                                                <?php } ?>
                                                <td class='text-end fw-bolder '><?php echo rand(10,100); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <!-- ประวัติการเข้าพบร้านค้า -->
                        <div class="tab-pane fade " id="T5" role="tabpanel" aria-labelledby="T5-tab">
                            <div class="table-responsive">
                                <table class='table table-sm table-bordered rounded rounded-2 overflow-hidden' style='font-size: 13px;' id='TableMeeting'>
                                    <thead>
                                        <tr class='table-primary text-center'>
                                            <th class='text-center'>วันที่เข้าพบ</th>
                                            <th class='text-center'>หัวข้อที่เข้าพบ</th>
                                            <th class='text-center'>รายละเอียดเข้าพบ</th>
                                            <th class='text-center'>พนักงานที่เข้าพบ</th>
                                            <th class='text-center'><i class="fas fa-search-location fa-fw fa-lg"></i></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="ModalShowData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ออก</button>
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
                                    <textarea class="form-control form-control-sm" rows="4" id='RptADetail' readonly></textarea>
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