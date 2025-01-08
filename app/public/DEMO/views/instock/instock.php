<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="row">
                <div class="col-auto">
                    <div class="form-group">
                        <label for="WhsCode">เลือกคลัง</label>
                        <select class='form-select form-select-sm' name="WhsCode" id="WhsCode" onchange='GetInstock()'>
                            <option value='ALL' selected>เลือกคลังสินค้าทั้งหมด</option>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <label for="">เงื่อนไข</label>
                        <div class="form-control form-control-sm d-flex align-items-center justify-content-around">
                            <input class="form-check-input m-0" type="checkbox" name='Getzero' id="Getzero" onclick='GetInstock()'>
                            <span class="ms-1">สินค้าคงคลังมากกว่า 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class='table table-sm table-bordered table-hover' id='TableInstock'>
                    <thead>
                        <tr>
                            <th class='text-center'>รหัสสินค้า</th>
                            <th class='text-center'>บาร์โค้ด</th>
                            <th class='text-center'>ชื่อสินค้า</th>
                            <th class='text-center'>หน่วย</th>
                            <th class='text-center'>คงคลัง</th>
                            <th class='text-center'>รอเบิก</th>
                            <th class='text-center'>รออนุมัติ</th>
                            <th class='text-center'>ใช้ได้จริง</th>
                            <th class='text-center'>AGING<br>(เดือน)</th>
                            <th class='text-center'>กำลัง<br>สั่งซื้อ</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<div class='modal fade' id='ModalDetailAvailable' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-xl'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class="modal-title text-primary"><i class="fas fa-file-invoice-dollar fa-fw fa-1x"></i> รายละเอียด SO ที่รอเบิก</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body' style='font-size: 12px;'>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class='table table-sm table-hover table-bordered' id='TableDetailAvailable'>
                                <thead>
                                    <tr class='text-center'>
                                        <th>เลขที่เอกสาร</th>
                                        <th>วันที่เอกสาร</th>
                                        <th>รหัสลูกค้า</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>พนักงานขาย</th>
                                        <th>คลัง</th>
                                        <th>SO รอเปิด</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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

<div class='modal fade' id='ModalViewOnOrder' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-full'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class="modal-title text-primary"><i class="fas fa-file-invoice-dollar fa-fw fa-1x"></i> รายละเอียด SO ที่รอเบิก</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body' style='font-size: 12px;'>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class='table table-sm table-hover table-bordered' id='TableViewOnOrder'>
                                <thead>
                                    <tr class='text-center'>
                                        <th>No.</th>
                                        <th>รหัสสินค้า</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>วันที่เปิด PO</th>
                                        <th>ประมาณการสินค้า</th>
                                        <th>อ้างอิง PO</th>
                                        <th>จำนวน</th>
                                        <th>หน่วย</th>
                                        <th>คลังสินค้า</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                                        <td colspan='8' rowspan='5'>
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