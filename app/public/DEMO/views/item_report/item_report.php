<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="row">
                <div class="col-auto">
                    <div class="form-group">
                        <label for="txtYear">เลือกปี</label>
                        <select class='form-select form-select-sm' name="txtYear" id="txtYear" onchange='GetItemProduct()'>
                            <?php for($y = date("Y"); $y >= 2024; $y--) {
                                echo (($y == date("Y")) ? "<option value='$y' selected>$y</option>" : "<option value='$y'>$y</option>");
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <label for="txtMonth">เลือกเดือน</label>
                        <select class='form-select form-select-sm' name="txtMonth" id="txtMonth" onchange='GetItemProduct()'>
                            <?php for($m = 1; $m <= 12; $m++) {
                                echo (($m == date("m")) ? "<option value='$m' selected>".FullMonth($m)."</option>" : "<option value='$m'>".FullMonth($m)."</option>");
                            } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class='table table-sm table-hover table-bordered' id='TableItemProduct' style='font-size: 11.5px;'>
                    <thead>
                        <tr>
                            <th rowspan='2' class='text-center border'>No.</th>
                            <th rowspan='2' class='text-center border'>ชื่อลูกค้า</th>
                            <th rowspan='2' class='text-center border'>รหัสลูกค้า</th>  <!-- Colum พิเศษ เฉพาะ Export Excel -->
                            <th rowspan='2' class='text-center border'>ชื่อลูกค้า</th> <!-- Colum พิเศษ เฉพาะ Export Excel -->
                            <th rowspan='2' class='text-center border'>พนักงานขาย</th> <!-- Colum พิเศษ เฉพาะ Export Excel -->
                            <th rowspan='2' class='text-center border'>รหัสสินค้า</th> <!-- Colum พิเศษ เฉพาะ Export Excel -->
                            <th rowspan='2' class='text-center border'>ชื่อสินค้า</th> <!-- Colum พิเศษ เฉพาะ Export Excel -->
                            <th rowspan='2' class='text-center border'>ชื่อสินค้า</th>
                            <th rowspan='2' class='text-center border'>ยี่ห้อ</th>
                            <th rowspan='2' class='text-center border'>รุ่น</th>
                            <th rowspan='2' class='text-center border'>ประเภท<br>อาหาร</th>
                            <th rowspan='2' class='text-center border'>ชนิด<br>อาหาร</th>
                            <th rowspan='2' class='text-center border'>ร้านค้าหลัก</th>
                            <th rowspan='2' class='text-center border'>เขตการขาย</th>
                            <th rowspan='2' class='text-center border'>กลุ่มลูกค้า</th>
                            <th colspan='7' class='text-center border'>Sale Orders</th>
                            <th colspan='8' class='text-center border'>Invoice</th>
                        </tr>
                        <tr>
                            <th class='text-center border'>เลขที่เอกสาร</th>
                            <th class='text-center border'>วันที่เอกสาร</th>
                            <th class='text-center border'>จำนวน</th>
                            <th class='text-center border'>หน่วย</th>
                            <th class='text-center border'>ราคา</th>
                            <th class='text-center border'>ส่วนลด</th>
                            <th class='text-center border'>ราคารวม</th>

                            <th class='text-center border'>เลขที่เอกสาร</th>
                            <th class='text-center border'>วันที่เอกสาร</th>
                            <th class='text-center border'>วันที่กำหนดชำระ</th>
                            <th class='text-center border'>จำนวน</th>
                            <th class='text-center border'>หน่วย</th>
                            <th class='text-center border'>ราคา</th>
                            <th class='text-center border'>ส่วนลด</th>
                            <th class='text-center border'>ราคารวม</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>