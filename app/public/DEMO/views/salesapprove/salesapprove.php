<section>
    <div class="card">
        <h5 class="card-header"><i class="fas fa-clipboard-check"></i> อนุมัติคำสั่งขาย</h5>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class='table table-sm table-bordered table-hover' style='font-size: 12px;' id='TableListOrder'>
                            <thead>
                                <tr class='text-center'>
                                    <th width='8%' rowspan='2'>วันที่เอกสาร</th>
                                    <th width='8%' rowspan='2'>กำหนดส่ง</th>
                                    <th width='10%' rowspan='2'>เลขที่ S/O</th>
                                    <th width='27%' rowspan='2'>ชื่อลูกค้า</th>
                                    <th width='7%' rowspan='2'>มูลค่าท้ายบิล</th>
                                    <th width='12%' rowspan='2'>พนักงานขาย</th>
                                    <th colspan='5'>เงื่อนไขการขออนุมัติ</th>
                                </tr>
                                <tr class='text-center'>
                                    <th width='7%'>วงเงิน<br/>เครดิต</th>
                                    <th width='7%'>หนี้<br/>เกินกำหนด</th>
                                    <th width='7%'>เช็ค<br/>เกินกำหนด</th>
                                    <th width='7%'>ราคา<br/>พิเศษ</th>
                                    <th width='7%'>มูลค่าท้ายบิล<br>ต่ำกว่ากำหนด</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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