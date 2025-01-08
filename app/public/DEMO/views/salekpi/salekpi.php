<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="row">
                <div class="col-auto">
                    <div class="form-group">
                        <label for="txtYear">เลือกปี</label>
                        <select class='form-select form-select-sm' name="txtYear" id="txtYear" onchange='GetSaleKPI()'>
                            <?php for($y = date("Y"); $y >= 2024; $y--) {
                                echo (($y == date("Y")) ? "<option value='$y' selected>$y</option>" : "<option value='$y'>$y</option>");
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <label for="txtMonth">เลือกเดือน</label>
                        <select class='form-select form-select-sm' name="txtMonth" id="txtMonth" onchange='GetSaleKPI()'>
                            <?php for($m = 1; $m <= 12; $m++) {
                                echo (($m == date("m")) ? "<option value='$m' selected>".FullMonth($m)."</option>" : "<option value='$m'>".FullMonth($m)."</option>");
                            } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class='table table-sm table-bordered table-hover' id='TableSaleKPI'>
                    <thead>
                        <tr>
                            <th width='' class='text-center'>พนง.ขาย</th>
                            <th width='10%' class='text-center'>เป้าขาย (บาท)</th>
                            <th width='10%' class='text-center'>รออนุมัติ</th>
                            <th width='15%' class='text-center'>SO คงค้าง</th>
                            <th width='10%' class='text-center'>ยอดขาย</th>
                            <th width='10%' class='text-center'>ยอดเก็บเงิน</th>
                            <th width='10%' class='text-center'>%</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
</section>