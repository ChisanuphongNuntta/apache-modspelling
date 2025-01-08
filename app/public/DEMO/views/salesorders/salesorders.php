<section>
    <div class="card">
        <h5 class="card-header"><i class="fas fa-file-invoice-dollar"></i> เปิดคำสั่งขาย</h5>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <nav>
                        <div class="nav nav-tabs" role="tablist">
                            <button class="nav-link active" id="List-tab" data-bs-toggle="tab" data-bs-target="#List" type="button" role="tab" aria-controls="List" aria-selected="false"><i class="fas fa-list"></i>  รายการคำสั่งขาย</button>
                            <button class="nav-link " id="Add-tab" data-bs-toggle="tab" data-bs-target="#Add" type="button" role="tab" aria-controls="Add" aria-selected="false"><i class="fas fa-plus"></i> เพิ่ม/แก้ไขคำสั่งขายใหม่</button>
                        </div>
                    </nav>
                </div>
            </div>
    
            <div class="row pt-2">
                <div class="col">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="List" role="tabpanel" aria-labelledby="List-tab">
                            <div class="row">
                                <div class="col-lg-auto">
                                    <div class="form-group">
                                        <label for="">เลือกปี</label>
                                        <select class='form-select form-select-sm' name="" id="filt_y">
                                            <?php for($y = date("Y"); $y >= 2023; $y--) {
                                                $show_year = ($y == date("Y")) ? "<option value='$y' selected>$y</option>" : "<option value='$y'>$y</option>";
                                                echo $show_year;
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-auto">
                                    <div class="form-group">
                                        <label for="">เลือกเดือน</label>
                                        <select class='form-select form-select-sm' name="" id="filt_m">
                                            <?php for($m = 1; $m <= 12; $m++) {
                                                $show_month = ($m == date("m")) ? "<option value='$m' selected>".FullMonth($m)."</option>" : "<option value='$m'>".FullMonth($m)."</option>";
                                                echo $show_month;
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" style="min-height: 600px;">
                                <table class='table table-sm table-bordered table-hover' style='font-size: 12px;' id="OrderList">
                                    <thead>
                                        <tr>
                                            <th width="3.5%" class='text-center'>ลำดับ</th>
                                            <th width="6.5%" class='text-center'>วันที่<br/>เอกสาร</th>
                                            <th width="6.5%" class='text-center'>วันที่<br/>กำหนดส่ง</th>
                                            <th width="7.5%" class='text-center'>เลขที่<br/>เอกสาร</th>
                                            <th class='text-center'>ชื่อลูกค้า</th>
                                            <th width="10%" class='text-center'>เอกสาร<br/>อ้างอิง</th>
                                            <th width="6.5%" class='text-center'>ยอดขาย</th>
                                            <th width="17.5%" class='text-center'>พนักงานขาย</th>
                                            <th width="7.5%" class='text-center'>สถานะ<br/>เอกสาร</th>
                                            <th width="7.5%" class='text-center'>SAP&reg;<br/>S/O No.</th>
                                            <th width="5%" class='text-center'><i class="fas fa-cog"></i></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="Add" role="tabpanel" aria-labelledby="Add-tab">
                            <div class="Step1" style='font-size: 14px;'>
                                <h5 class='text-primary'>Step 1: เลือกข้อมูลลูกค้า และข้อมูลการเปิดบิล</h5>
                                <div class="row pt-4">
                                    <div class="col-lg-6">
                                        <div class="form-group txt-CardCode-color">
                                            <label for="txt_CardCode">ชื่อลูกค้า<span class="text-danger">*</span></label>
                                            <select class='form-control form-control-sm' name="txt_CardCode" id="txt_CardCode" data-live-search="true">
                                                <option value='' selected disabled>กรุณาเลือก</option>
                                            </select>
                                            <small class="fw-bolder"><i class="fas fa-hand-holding-usd"></i> เครดิตที่ใช้ไป (บาท): <span id="txt_balance">0</span> / <span id="txt_creditline" class="text-success">0</span></small>
                                            <input type="hidden" name="txt_DocEntry" id="txt_DocEntry" value="-1" />
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="txt_LicTradeNum">เลขที่ประจำตัวผู้เสียภาษี</label>
                                            <input type="text" class='form-control form-control-sm text-center' name="txt_LicTradeNum" id="txt_LicTradeNum">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="txt_DocType">ประเภทเอกสาร<span class="text-danger">*</span></label>
                                            <select class='form-select form-select-sm' name="txt_DocType" id="txt_DocType">
                                                <option value="" disabled>เลือก</option>
                                                <option value="SO" selected>[SO] ใบสั่งขายต่างประเทศ</option>
                                                <option value="SD">[SD] ใบสั่งขายในประเทศ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="txt_TaxType">ประเภทภาษี (VAT)</label>
                                            <select class='form-select form-select-sm' name="txt_TaxType" id="txt_TaxType">
                                                <!-- ถ้าทำการแยกแล้ว site ค่อยแยก selected ตาม site -->
                                                <option value="" disabled>เลือก</option>
                                                <option value="S07">มี VAT (VAT+)</option>
                                                <option value="S00" selected>ไม่มี VAT (No VAT)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="txt_Billto">ที่อยู่สำหรับเปิดบิล<span class="text-danger">*</span></label>
                                            <select class='form-select form-select-sm' name="txt_Billto" id="txt_Billto">
                                                <option value="" selected disabled>เลือก</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="txt_Shipto">ที่อยู่สำหรับจัดส่ง<span class="text-danger">*</span></label>
                                            <select class='form-select form-select-sm' name="txt_Shipto" id="txt_Shipto">
                                                <option value="" selected disabled>เลือก</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group txt-CardCode-color">
                                            <label for="txt_SlpCode">พนักงานขาย<span class="text-danger">*</span></label>
                                            <select class='form-control form-control-sm' name="txt_SlpCode" id="txt_SlpCode" data-live-search="true">
                                                <option value="" selected disabled>เลือก</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="txt_DocDate">วันที่เอกสาร<span class="text-danger">*</span></label>
                                            <input type="date" class='form-control form-control-sm' name="txt_DocDate" id="txt_DocDate" value="<?php echo date("Y-m-d");?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="txt_DocDueDate">วันที่กำหนดส่ง<span class="text-danger">*</span></label>
                                            <input type="date" class='form-control form-control-sm' name="txt_DocDueDate" id="txt_DocDueDate" value="<?php echo date("Y-m-d");?>">
                                        </div>
                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="txt_U_PONo">อ้างอิงเลขที่ PO</label>
                                            <input type="text" class='form-control form-control-sm' name="txt_U_PONo" id="txt_U_PONo">
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="txt_U_SO_TYPE">Sale Orders Type<span class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" name="txt_U_SO_TYPE" id="txt_U_SO_TYPE"></select>
                                        </div>
                                    </diV> -->
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="txt_GroupNum">เงื่อนไขชำระเงิน<span class="text-danger">*</span></label>
                                            <select class='form-select form-select-sm' name="txt_GroupNum" id="txt_GroupNum">
                                                <option value="" selected disabled>เลือก</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="txt_FileAttach">
                                                แนบไฟล์ 
                                                <a href="javascript:void(0);" 
                                                    class="text-muted" 
                                                    data-bs-toggle="tooltip" 
                                                    title="" 
                                                    data-bs-original-title="รองรับนามสกุลไฟล์รูปภาพ (*.jpg, *.jpeg, *.png) / MS Word (*.doc, *.docx) / MS Excel (*.xls, *.xlsx) / เอกสาร (*.pdf) เท่านั้น" 
                                                    aria-label="รองรับนามสกุลไฟล์รูปภาพ (*.jpg, *.jpeg, *.png) / MS Word (*.doc, *.docx) / MS Excel (*.xls, *.xlsx) / เอกสาร (*.pdf) เท่านั้น">
                                                    <i class="far fa-question-circle"></i>
                                                </a>
                                            </label>
                                            <input type="file" class="form-control form-control-sm" name="txt_FileAttach[]" id="txt_FileAttach" accept=".jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.pdf" multiple="" onchange="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col d-flex justify-content-end">
                                        <button class='btn btn-sm btn-primary' onclick="CheckForm(1,2);">ต่อไป <i class="fas fa-angle-right"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="Step2 d-none">
                                <h5 class='text-primary'>Step 2: เพิ่มข้อมูลสินค้า</h5>
                                <div class="row pt-4">
                                    <div class="col">
                                        <button class='btn btn-sm btn-primary' id="btn-AddItem"><i class="fas fa-plus"></i> เพิ่มรายการใหม่</button>
                                        <button class='btn btn-sm btn-secondary' id="btn-ImportItem" disabled ><i class="fas fa-file-import"></i> นำเข้า</button>
                                    </div>
                                </div>

                                <div class="row pt-2">
                                    <div class="col">
                                        <div class="table-responsive tableFix">
                                            <table class='table table-sm table-bordered table-hover' id='Add_ItemList' style='font-size: 13px;'>
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
                                                        <th width='5%'>ราคาพิเศษ</th>
                                                        <th width='5%'>จัดการ</th>
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
                                                            <textarea class='form-control form-control-sm' rows='7' placeholder='ระบุหมายเหตุ' id='txt_comments' maxlength="250"></textarea>
                                                        </td>
                                                        <td colspan='2' class='fw-bolder text-end'>ยอดรวมทุกรายการ</td>
                                                        <td><input type='text' class='fw-bolder text-end form-control-plaintext form-control-sm' id='All_Total' value='0.00' readonly /></td>
                                                        <td colspan='2' class='fw-bolder'>บาท</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan='2' class='text-success text-end'>ส่วนลดท้ายบิล</td>
                                                        <td><input type='text' class='text-success text-end form-control-plaintext form-control-sm' id='All_Discount' value='0.00' /></td>
                                                        <td colspan='2'>
                                                            <select class='form-select form-select-sm w-75' id='All_DiscUnit'>
                                                                <option value='%' selected>%</option>
                                                                <option value='THB'>บาท</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan='2' class='text-success text-end'>ยอดสินค้าหลังหักส่วนลด</td>
                                                        <td><input type='text' class='fw-bolder text-success text-end form-control-plaintext form-control-sm' id='Doc_Discount' value='0.00' readonly /></td>
                                                        <td colspan='2'>บาท</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan='2' class='text-end'>ภาษีมูลค่าเพิ่ม (VAT)</td>
                                                        <td><input type='text' class='text-end form-control-plaintext form-control-sm' id='Doc_VatSum' value='0.00' readonly /></td>
                                                        <td colspan='2'>บาท</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan='2' class='text-end fw-bolder text-primary '>จำนวนเงินรวมสุทธิ</th>
                                                        <th>
                                                            <input type='text' class='text-primary fw-bolder  text-end form-control-plaintext form-control-sm' id='Doc_Total' value='0.00' readonly />
                                                            <input type='hidden' id="Doc_Profit" value="0.00" readonly />
                                                            <input type='hidden' id="Doc_Cost" value="0.00" readonly />
                                                        </th>
                                                        <th colspan='2' class='fw-bolder text-primary '>บาท</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col d-flex justify-content-between">
                                        <button class='btn btn-sm btn-secondary' onclick="CheckForm(2,1);"><i class="fas fa-angle-left"></i> กลับ</i></button>
                                        <button class='btn btn-sm btn-primary' onclick="CheckForm(2,3);">ต่อไป <i class="fas fa-angle-right"></i></button>
                                    </div>
                                </div>
                            </div>  

                            <div class="Step3 d-none">
                                <h5 class='text-primary'>Step 3: ตรวจสอบความถูกต้องของข้อมูล</h5>
                                <div class="row pt-4">
                                    <div class="col" id='DataShow'></div>
                                </div>

                                <div class="row pt-2">
                                    <div class="col">
                                        <div class="table-responsive tableFix2">
                                            <table class='table table-sm table-bordered table-hover' style='font-size: 13px;' id='TableData'>
                                                <thead>
                                                    <tr class='text-center'>
                                                        <th width='5%'>ลำดับ</th>
                                                        <th width='50%'>รายการ</th>
                                                        <th colspan='2'>จำนวน</th>
                                                        <th>ราคาตั้ง</th>
                                                        <th>ส่วนลด (%)</th>
                                                        <th>ราคารวม</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot></tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col d-flex justify-content-between">
                                        <button class='btn btn-sm btn-secondary' onclick="CheckForm(3,2);"><i class="fas fa-angle-left"></i> กลับ</i></button>
                                        <div>
                                            <button id="btn-Draft" class='btn btn-sm btn-secondary' onclick="SaveDoc(0)"><i class="fas fa-save fa-fw fa-1x"></i> บันทึกร่าง</button>
                                            <button id="btn-NewDoc" class='btn btn-sm btn-primary' onclick="SaveDoc(1)"><i class="fas fa-plus fa-fw fa-1x"></i> เพิ่มคำสั่งขายใหม่</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class='modal fade' id='ModalAddItem' tabindex='-1' role='dialog' data-bs-backdrop='static' aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header pt-2 pb-2'>
                <h5 class="modal-title text-primary"><i class="fas fa-plus"></i> เพิ่มรายการใหม่</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body' style='font-size: 14px;'>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="form-group txt-CardCode-color">
                            <label for="Add_ItemCode">รหัสสินค้า<span class="text-danger">*</span></label>
                            <select class='form-control form-control-sm' name="Add_ItemCode" id="Add_ItemCode" data-live-search="true">
                                <option value="" selected disable>กรุณาเลือกรหัสสินค้า</option>
                            </select>
                            <input type="hidden" name="Add_ItemName" id="Add_ItemName" readonly />
                            <input type="hidden" name="Add_CodeBars" id="Add_CodeBars" readonly />
                            <input type="hidden" name="Add_UnitMsr"  id="Add_UnitMsr" readonly />
                            <input type="hidden" name="Add_RowID"    id="Add_RowID" readonly />
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">จำนวน<span class="text-danger">*</span></label>
                            <input type="number" class='form-control form-control-sm text-end' min='0' value='0' step="any" id="Add_Quantity" name="Add_Quantity"> 
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for=""></label>
                            <button class='btn btn-sm btn-primary w-100' id="btn-calprice"><i class="fas fa-calculator"></i></button>
                        </div>
                    </div>
                </div>

                <span class='text-primary'><i class="fas fa-history"></i> ประวัติการสั่งซื้อสินค้า (3 รายการล่าสุด)</span>
                <div class="table-responsive">
                    <table class='table table-sm table-bordered' id="Add_ItemHistory" style='font-size: 14px;'>
                        <thead class='text-center'>
                            <tr>
                                <th width='12.5%'>วันที่สั่งซื้อ</th>
                                <th width='10%'>จำนวน</th>
                                <th width='12.5%'>ราคาขาย<br/>(ก่อน VAT)</th>
                                <th width='20%'>ส่วนลด (%)</th>
                                <th width='12.5%'>ราคาสุทธิ<br/>(ก่อน VAT)</th>
                                <th width='10%'>ภาษี<br/>(VAT)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan='6' class='text-center'>ไม่มีข้อมูล :(</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row pt-2">
                    <div class="col-lg"> 
                        <div class="form-group">
                            <label for="">ราคาขาย <!--<span class="text-muted"> (ก่อน VAT)</span><span class="text-danger">*</span>--></label>
                            <input type="number" class='form-control form-control-sm text-end' min='0.000' value='0.000' step="any" id="Add_GrandPrice" name="Add_GrandPrice" disabled> <!-- readonly -->
                            <input type="hidden" id="Chk_DefaultPrice" name="Chk_DefaultPrice" readonly />
                            <input type="hidden" id="Chk_Cxst" name="Chk_Cxst" readonly />
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="">ส่วนลด <sup class="text-muted">1/2</sup></label>
                            <input type="text" class='form-control form-control-sm text-center' name="Add_Discount" id="Add_Discount" disabled>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="">ราคาสุทธิ <span class="text-muted">(ก่อน VAT)</span></label>
                            <input type="number" class='form-control form-control-sm text-end text-success' min='0.000' value='0.000' id="Add_UnitPrice" name="Add_UnitPrice" readonly>
                        </div>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-lg">
                        <div class="form-group mb-3">
                            <label for="Add_WhsCode">คลังสินค้า<span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="Add_WhsCode" id="Add_WhsCode" disabled>
                                <option value="" selected disabled>กรุณาเลือก</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg">
                        <div class="form-group">
                            <input type="checkbox" class="form-check-input" name="Chk_SPrice" id="Chk_SPrice" disabled>
                            <label for="Chk_SPrice"> <i class="fas fa-hand-holding-usd text-warning"></i> ขออนุมัติราคาพิเศษ</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg text-muted" style="font-size: 12px;">
                        <p class="font-weight">หมายเหตุ</p>
                        <ol>
                            <li>ในช่องส่วนลด หากต้องการส่วนลดที่เป็น % ใช้เครื่องหมายลบ (-) คั่นส่วนลดระหว่าง STEP ได้เท่านั้น</li>
                            <li>หากต้องการส่วนลดเป็นจำนวนเงินให้ใส่เครื่องหมายดอกจัน (*) ไว้ด้านหน้า</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary btn-sm' data-bs-dismiss='modal'>ปิด</button>
                <button type='button' class='btn btn-primary btn-sm btn-saveitem' id="btn-AddRow"></button>
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
                        <button class="nav-link" id="docattach-tab" data-bs-toggle="tab" data-bs-target="#docattach" type="button" role="tab" aria-controls="docattach" aria-selected="false">เอกสารแนบ</button>
                        <button class="nav-link" id="approve-tab" data-bs-toggle="tab" data-bs-target="#approve" type="button" role="tab" aria-controls="approve" aria-selected="false" >สถานะการอนุมัติ</button>
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
                                        <th width='5%'>ราคาพิเศษ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" colspan="12">ไม่มีข้อมูล :(</td>
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
                    <div class="tab-pane fade" id="docattach" role="tabpanel" aria-labelledby="docattach-tab">
                        <div class="table-responsive tableFix2">
                            <table class='table table-sm table-bordered table-hover' id='TableDocAttach'>
                                <thead>
                                    <tr class='text-center'>
                                        <th width='10%'>ลำดับ</th>
                                        <th>ชื่อเอกสารแนบ</th>
                                        <th width='20%'>วันที่อัพโหลด</th>
                                        <th width='8%'><i class="fas fa-file-download"></i></th>
                                        <th width='8%'><i class="fas fa-trash-alt"></i></th>
                                    </tr>
                                </thead>
                                <tbody class='mb-3'></tbody>
                                <tfoot >
                                    <tr>
                                        <td colspan='5' class='pt-2'>
                                            <label for="AttachOrder">แนบไฟล์เพิ่มเติม</label>  <a href="javascript:void(0);" class="text-muted" data-bs-toggle="tooltip" title="รองรับนามสกุลไฟล์รูปภาพ (*.jpg, *.jpeg, *.png) / MS Word (*.doc, *.docx) / MS Excel (*.xls, *.xlsx) / เอกสาร (*.pdf) เท่านั้น"><i class="far fa-question-circle fa-fw fa-lg"></i></a>
                                            <form id="UploadsForm" enctype="multipart/form-data">
                                                <input type="file" class="form-control form-control-sm w-25" name="AttachOrder" id="AttachOrder" accept=".jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.pdf" />
                                            </form>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="approve" role="tabpanel" aria-labelledby="approve-tab">
                        <div class="table-responsive">
                            <table class='table table-sm table-bordered table-hover' id='TableApprove'>
                                <thead>
                                    <tr class='text-center'>
                                        <th rowspan='2' width='3%'>ลำดับ</th>
                                        <th rowspan='2' width='11%'>ผู้อนุมัติ</th>
                                        <th colspan='5'>หัวข้อการอนุมัติ</th>
                                        <th rowspan='2' width='10%'>ผลการ<br>พิจารณา</th>
                                        <th rowspan='2' width='23%'>หมายเหตุ</th>
                                        <th rowspan='2' width='11%'>ผู้อนุมัติ</th>
                                        <th rowspan='2' width='10%'>วันที่อนุมัติ</th>
                                    </tr>
                                    <tr class='text-center'>
                                        <th width='8%'>วงเงิน<br/>เครดิต</th>
                                        <th width='8%'>หนี้<br/>เกินกำหนด</th>
                                        <th width='8%'>เช็ค<br/>เกินกำหนด</th>
                                        <th width='8%'>ราคา<br/>พิเศษ</th>
                                        <th width='8%'>มูลค่าท้ายบิล<br>ต่ำกว่ากำหนด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan='10' class='text-center'>&nbsp;</td></tr>
                                </tbody>
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

<div class="modal fade" id="ModalImport" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h5 class="modal-title text-primary"><i class="fas fa-file-import"></i> นำเข้า</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2 mb-2">
                    <div class="col-lg-12">
                        <div class="alert alert-light-info color-info alert-dismissable fade show">
                            <i class="fas fa-exclamation-circle fa-fw fa-1x"></i> <strong>การนำเข้ารายการสินค้า</strong>
                            <ul>
                                <li>ระบบจะนำข้อมูล รหัสสินค้า - บาร์โค้ด - จำนวน - ราคา - ส่วนลด จากรายการบิลเดิมมาเท่านั้น</li>
                                <li>ชื่อสินค้า - สถานะสินค้า - คลังสินค้าจะดึงมาค่าเริ่มต้นจากฐานข้อมูลเท่านั้น</li>
                                <li class="text-danger">การนำเข้าข้อมูล จะทำให้รายการที่เพิ่มไปก่อนหน้าหายทั้งหมด</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs" id="main-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="javascript:void(0);" class="btn-tabs nav-link active" id="ImportSearch-tab" data-bs-toggle="tab" data-bs-target="#ImportSearch" role="tab" data-tabs="0" aria-controls="ImportSearch" aria-selected="false">
                            <i class="fas fa-search fa-fw fa-1x"></i> นำเข้าจากเลขที่เอกสาร
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="javascript:void(0);" class="btn-tabs nav-link disabled" id="ImportExcel-tab" data-bs-toggle="tab" data-bs-target="#ImportExcel" role="tab" data-tabs="1" aria-controls="ImportExcel" aria-selected="true">
                            <i class="fas fa-file-excel fa-fw fa-1x"></i> นำเข้าจากไฟล์ Excel
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="ImportSearch" role="tabpanel" aria-labelledby="ImportSearch-tab">
                        <div class="row pt-2 pb-2">
                            <div class="col">
                                <div class="form-group">
                                    <label for="ImportSearchInput">ค้นหาจากเลขที่เอกสาร<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="ImportSearchInput" id="ImportSearchInput" placeholder="SD-XXXXXXXXX" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="ImportExcel" role="tabpanel" aria-labelledby="ImportExcel-tab"></div>
                </div>
            </div>
            <div class="modal-footer pt-2 pb-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-sm btn-primary" id="btn-searchdoc"><i class="fas fa-search fa-fw fa-1x"></i> ค้นหา</button>
            </div>
        </div>
    </div>
</div>