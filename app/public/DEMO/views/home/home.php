<?php 
switch(substr($_SESSION['LVCODE'],0,1)) {
    case "I":
    case "C":
    case "M":
    case "A":
    case "S":
    case "0" :$Show = "Y"; $Padding = "pt-3"; break;
    default : $Show = "N"; $Padding = ""; break;
}
?>
<section>
    <div class="row">
        <div class="col-sm-12 col-lg-3">
            <div class="card h-100">
                <h5 class="card-header text-primary"><i class="fas fa-chart-pie"></i> ยอดขายเดือน <?php echo FullMonth(date("m"))." ".date("Y"); ?></h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-lg">
                                    <div id="SaleTargetProgress" class="text-center"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="50%" class='ps-2 pt-2 pb-2'>ยอด SO คงค้าง</td>
                                                <td class="text-end pe-2 pt-2 pb-2" width="50%"> <span class="text-primary" style="font-weight: bold;" id='SaleSOMonth'></span> บาท</td>
                                            </tr>
                                            <tr>
                                                <td width="50%" class='ps-2 pt-2 pb-2'>ยอดขายเดือนนี้</td>
                                                <td class="text-end pe-2 pt-2 pb-2" width="50%"> <span class="text-primary" style="font-weight: bold;" id='SaleMonth'></span> บาท</td>
                                            </tr>
                                            <tr>
                                                <td width="50%" class='ps-2 pt-2 pb-2'>เป้าขายเดือนนี้</td>
                                                <td class="text-end pe-2 pt-2 pb-2" width="50%"> <span class="text-primary" style="font-weight: bold;" id='SaleTarget'></span> บาท</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($Show == 'Y') { ?>
            <div class="col-sm-12 col-lg-9 pt-sm">
                <div class="card h-100">
                    <h5 class="card-header text-primary"><i class="fas fa-file-signature"></i> เอกสารรออนุมัติ</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class='table table-sm table-bordered table-hover' style='font-size: 13px;' id='TableAppDoc'>
                                        <thead>
                                            <tr class='text-center'>
                                                <th width='8%' class='text-center'>วันที่เอกสาร</th>
                                                <th width='8%' class='text-center'>กำหนดส่ง</th>
                                                <th width='10%' class='text-center'>เลขที่ S/O</th>
                                                <th width='27%' class='text-center'>ชื่อลูกค้า</th>
                                                <th width='7%' class='text-center'>มูลค่าท้ายบิล</th>
                                                <th width='12%' class='text-center'>พนักงานขาย</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="col pt-sm <?php echo $Padding; ?>">
            <div class="card h-100">
                <h5 class="card-header text-primary"><i class="fas fa-clipboard-list"></i> แผนการขายเดือน <?php echo FullMonth(date("m"))." ".date("Y"); ?></h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-lg-6">
                            <div class="calendar-body">
                                <ul class="calendar-weekdays fw-bolder p-0 m-0">
                                    <li class='border text-danger'>อา.</li>
                                    <li class='border'>จ.</li>
                                    <li class='border'>อ.</li>
                                    <li class='border'>พ.</li>
                                    <li class='border'>พฤ.</li>
                                    <li class='border'>ศ.</li>
                                    <li class='border'>ส.</li>
                                </ul>
                                <ul class="calendar-dates p-0"></ul>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-6">
                            <div class="table-responsive">
                                <table class='table table-sm table-hover' style='cursor: pointer;' id='TableDPlan'>
                                    <thead></thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="row pt-3">
        
    </div>
</section>

<div class='modal fade' id='ModalAppDoc' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-full'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class='modal-title text-primary'><i class="fas fa-clipboard-check"></i> อนุมัติใบสั่งขาย</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body' style='font-size: 14px;'>
                <div class="row">
                    <div class="col-lg fw-600">เลขที่เอกสาร : <span class='text-primary' id='txtDocNum'></span></div>
                    <div class="col-lg-8 fw-600">ชื่อลูกค้า : <span class='text-primary' id='txtCardName'></span></div>
                </div>
                <div class="row">
                    <div class="col-lg fw-600">วันที่เอกสาร : <span class='text-primary' id='txtDocDate'></span></div>
                    <div class="col-lg fw-600">พนักงานขาย : <span class='text-primary' id='txtSlpName'></span></div>
                    <div class="col-lg fw-600">เครดิต : <span class='text-primary' id='txtGroupNum'></span></div>
                </div>
                <div class="row">
                    <div class="col-lg fw-600">ลูกค้าสั่งซื้อมาแล้ว : <span class='text-primary' id='txtCusSale'></span></div>
                    <div class="col-lg fw-600">วันที่เปิดบิลครั้งแรก : <span class='text-primary' id='txtOpenSale'></span></div>
                    <div class="col-lg fw-600">มียอดชำระเงินมาแล้ว : <span class='text-primary' id='txtPay'></span></div>
                </div>
                <div class="row">
                    <div class="col-lg fw-600">ยอดสั่งซื้อปี <?php echo date("Y")-1; ?> : <span class='text-primary' id='txtSaleP'></span></div>
                    <div class="col-lg fw-600">ยอดสั่งซื้อปี <?php echo date("Y"); ?> : <span class='text-primary' id='txtSaleC'></span></div>
                    <div class="col-lg fw-600">เครดิตวงเงิน : <span class='text-primary' id='txtCredit'></span></div>
                </div>
                <div class="row">
                    <div class="col-lg fw-600">หมายเหตุท้ายคำสั่งขาย : <span class='text-primary' id='txtComment'></span></div>
                </div>

                <div class="row pt-4">
                    <div class="col">
                        <nav>
                            <div class="nav nav-tabs" role="tablist">
                                <button class="nav-link active" id="App-tab" data-bs-toggle="tab" data-bs-target="#App" type="button" role="tab" aria-controls="App" aria-selected="false"><i class="fas fa-list"></i> รายการรอนุมัติ</button>
                                <button class="nav-link " id="Over-tab" data-bs-toggle="tab" data-bs-target="#Over" type="button" role="tab" aria-controls="Over" aria-selected="false"><i class="fas fa-list-ul"></i> รายการหนี้เกินกำหนด</button>
                                <button class="nav-link " id="Spring-tab" data-bs-toggle="tab" data-bs-target="#Spring" type="button" role="tab" aria-controls="Spring" aria-selected="false" disabled><i class="fas fa-money-check"></i> รายการเช็คเด้ง</button>
                                <button class="nav-link " id="Listandsale-tab" data-bs-toggle="tab" data-bs-target="#Listandsale" type="button" role="tab" aria-controls="Listandsale" aria-selected="false"><i class="fas fa-hand-holding-usd"></i> รายการสินค้า/ราคาพิเศษ</button>
                                <button class="nav-link " id="File-tab" data-bs-toggle="tab" data-bs-target="#File" type="button" role="tab" aria-controls="File" aria-selected="false"><i class="far fa-file"></i> เอกสารแนบ</button>
                            </div>
                        </nav>
                    </div>
                </div>

                <div class="row pt-2">
                    <div class="col">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="App" role="tabpanel" aria-labelledby="App-tab">
                                <div class="table-responsive">
                                    <table class='table table-sm table-bordered table-hover' style='font-size: 13px;' id='TableListApp'>
                                        <thead>
                                            <tr class='text-center'>
                                                <th>ลำดับ</th>
                                                <th>ผู้อนุมัติ</th>
                                                <th>การดำเนินการ</th>
                                                <th>ความคิดเห็น</th>
                                                <th>สถานะอนุมัติ</th>
                                                <th>บันทึก</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th colspan='6' class='text-center'>ไม่มีข้อมูล :(</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>  
                            </div>
                            <div class="tab-pane fade" id="Over" role="tabpanel" aria-labelledby="Over-tab">
                                <div class="table-responsive">
                                    <table class='table table-sm table-hover table-bordered' style='font-size: 13px;' id='TableOver'>
                                        <thead>
                                            <tr class='text-center'>
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
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Spring" role="tabpanel" aria-labelledby="Spring-tab">
                                3
                            </div>
                            <div class="tab-pane fade" id="Listandsale" role="tabpanel" aria-labelledby="Listandsale-tab">
                                <div class="table-responsive">
                                    <table class='table table-sm table-bordered table-hover' style='font-size: 13px;' id='TableItem'>
                                        <thead>
                                            <tr class='text-center'>
                                                <th width='3.5%'>ลำดับ</th>
                                                <th width='7.5%'>รหัสสินค้า</th>
                                                <th width='%'>ชื่อสินค้า</th>
                                                <th width='6%'>ต้นทุน<br/>ต่อชิ้น</th>
                                                <th width='6%'>ราคาขาย<br/>ก่อนส่วนลด<br/>(ต่อหน่วย)</th>
                                                <th width='10%'>ส่วนลด</th>
                                                <th width='6%'>ราคาขาย<br/>หลังส่วนลด<br/>(ต่อหน่วย)</th>
                                                <th width='5%'>คลังสินค้า</th>
                                                <th width='5%'>จำนวน</th>
                                                <th width='4%'>หน่วย</th>
                                                <th width='6%'>ราคาขาย<br/>(รวม)</th>
                                                <th width='6%'>กำไร<br/>(รวม)</th>
                                                <th width='5%'>% กำไร</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class='fw-bolder'>
                                                <td colspan='10' class='text-end'>ยอดรวมทุกรายการ</td>
                                                <td class='text-end' id='Doc_SumTotal'></td>
                                                <td>บาท</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan='10' class='text-end'>ส่วนลดท้ายบิล</td>
                                                <td class='text-end' id='Doc_DiscSum'></td>
                                                <td id='Doc_DiscUnit'></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr class='fw-bolder'>
                                                <td colspan='10' class='text-end'>ยอดสินค้าหลังหักส่วนลด</td>
                                                <td class='text-end' id='Doc_Discount'></td>
                                                <td>บาท</td>
                                                <td class='text-center text-danger' id='Doc_DiscPcnt'></td>
                                            </tr>
                                            <tr>
                                                <td colspan='10' class='text-end'>ภาษีมูลค่าเพิ่ม (VAT)</td>
                                                <td class='text-end' id='Doc_VatSum'></td>
                                                <td>บาท</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr class='table-active fw-bolder text-primary'>
                                                <td colspan='10' class='text-end'>จำนวนเงินรวมสุทธิ</td>
                                                <td class='text-end' id='Doc_Total'></td>
                                                <td>บาท</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr class=''>
                                                <td colspan='10' class='text-end'>เครดิตที่ใช้ไป (รวมคำสั่งขายนี้)</td>
                                                <td class='text-end' id='Doc_Balance'></td>
                                                <td>บาท</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr class='table-active fw-bolder'>
                                                <td colspan='10' class='text-end'>เครดิตคงเหลือ</td>
                                                <td class='text-end' id='Doc_BalanceCredit'></td>
                                                <td>บาท</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="File" role="tabpanel" aria-labelledby="File-tab">
                                <div class="table-responsive">
                                    <table class='table table-sm table-bordered table-hover' style='font-size: 13px;' id='TableFile'>
                                        <thead>
                                            <tr class='text-center'>
                                                <th width='10%'>ลำดับ</th>
                                                <th>ชื่อเอกสารแนบ</th>
                                                <th width='20%'>วันที่อัพโหลด</th>
                                                <th width='10%' class='text-secondary'><i class="fas fa-file-download"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan='4' class='text-center'>ไม่มีข้อมูล :(</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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

<div class='modal fade' id='ModalListMonth' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-xl'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class='modal-title text-primary'><i class="fas fa-file-invoice-dollar"></i> ยอดขายเดือนนี้</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="SaleBill-tab" data-bs-toggle="tab" data-bs-target="#SaleBill" type="button" role="tab" aria-controls="SaleBill" aria-selected="true">บิลขาย</button>
                        <button class="nav-link" id="OverdueBill-tab" data-bs-toggle="tab" data-bs-target="#OverdueBill" type="button" role="tab" aria-controls="OverdueBill" aria-selected="false">SO คงค้าง</button>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="SaleBill" role="tabpanel" aria-labelledby="SaleBill-tab">
                        <div class="table-responsive">
                            <table class='table table-sm table-bordered table-hover' id='TableSaleBill' style='font-size: 13px;'>
                                <thead>
                                    <tr>
                                        <th class='text-center'>No.</th>
                                        <th class='text-center'>เลขที่เอกสาร</th>
                                        <th class='text-center'>วันที่เอกสาร</th>
                                        <th class='text-center'>ชื่อลูกค้า</th>
                                        <th class='text-center'>ชื่อพนักงาน</th>
                                        <th class='text-center'>ยอดขาย</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="OverdueBill" role="tabpanel" aria-labelledby="OverdueBill-tab">
                        <div class="table-responsive">
                            <table class='table table-sm table-bordered table-hover' id='TableOverdueBill' style='font-size: 13px;'>
                                <thead>
                                    <tr>
                                        <th class='text-center'>No.</th>
                                        <th class='text-center'>เลขที่เอกสาร</th>
                                        <th class='text-center'>วันที่เอกสาร</th>
                                        <th class='text-center'>ชื่อลูกค้า</th>
                                        <th class='text-center'>ชื่อพนักงาน</th>
                                        <th class='text-center'>มูลค่า (บาท)</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary btn-sm' data-bs-dismiss='modal'>ออก</button>
            </div>
        </div>
    </div>
</div>  

<div class='modal fade' id='ModalViewDoc' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-full'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class="modal-title text-primary"><i class="fas fa-file-invoice-dollar"></i> รายละเอียดใบสั่งขาย</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body' style='font-size: 14px;'>
                <div class="row pb-3">
                    <div class="col" id='DataViewDoc'></div>
                </div>

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="itemlist-tab" data-bs-toggle="tab" data-bs-target="#itemlist" type="button" role="tab" aria-controls="itemlist" aria-selected="true">รายการสินค้า</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="itemlist" role="tabpanel" aria-labelledby="itemlist-tab">
                        <div class="table-responsive tableFix2">
                            <table class='table table-sm table-bordered table-hover' id='ItemListViewDoc' style='font-size: 13px;'>
                                <thead>
                                    <tr class='text-center py-2'>
                                        <th width='3.5%'>No.</th>
                                        <th width='7.5%'>รหัสสินค้า</th>
                                        <th width='8%'>บาร์โค้ด</th>
                                        <th>ชื่อสินค้า</th>
                                        <th width='5%'>สต๊อกคงเหลือ</th>
                                        <th width='5%'>คลังสินค้า</th>
                                        <th width='5%'>จำนวน</th>
                                        <th width='5%'>หน่วยขาย</th>
                                        <th width='7.5%'>ราคาขาย</th>
                                        <th width='10%'>ส่วนลด</th>
                                        <th width='7.5%'>ราคาสุทธิ</th>
                                        <th width='10%'>รวมทั้งหมด</th>
                                        <th width='3%'>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" colspan="13">ไม่มีข้อมูล :(</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan='9' rowspan='5'>
                                            <textarea class='form-control form-control-sm' rows='7' placeholder='ระบุหมายเหตุ' id='vd_comments' readonly></textarea>
                                        </td>
                                        <td colspan='2' class='fw-bolder text-end'>ยอดรวมทุกรายการ</td>
                                        <td><input type='text' class='fw-bolder text-end form-control-plaintext form-control-sm' id='vd_AllTotal' value='0.00' readonly /></td>
                                        <td class='fw-bolder'>บาท</td>
                                    </tr>
                                    <tr>
                                        <td colspan='2' class='text-success text-end'>ส่วนลดท้ายบิล</td>
                                        <td><input type='text' class='text-success text-end form-control-plaintext form-control-sm' id='vd_DiscPcnt' value='0.00' /></td>
                                        <td id='vd_TypeDiscPcnt'></td>
                                    </tr>
                                    <tr>
                                        <td colspan='2' class='text-success text-end'>ยอดสินค้าหลังหักส่วนลด</td>
                                        <td><input type='text' class='fw-bolder text-success text-end form-control-plaintext form-control-sm' id='vd_Discount' value='0.00' readonly /></td>
                                        <td>บาท</td>
                                    </tr>
                                    <tr>
                                        <td colspan='2' class='text-end'>ภาษีมูลค่าเพิ่ม (VAT)</td>
                                        <td><input type='text' class='text-end form-control-plaintext form-control-sm' id='vd_VatSum' value='0.00' readonly /></td>
                                        <td>บาท</td>
                                    </tr>
                                    <tr>
                                        <th colspan='2' class='text-end fw-bolder text-primary '>จำนวนเงินรวมสุทธิ</th>
                                        <th>
                                            <input type='text' class='text-primary fw-bolder  text-end form-control-plaintext form-control-sm' id='vd_Total' value='0.00' readonly />
                                        </th>
                                        <th class='fw-bolder text-primary '>บาท</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary btn-sm' data-bs-dismiss='modal'>ปิด</button>
            </div>
        </div>
    </div>
</div>