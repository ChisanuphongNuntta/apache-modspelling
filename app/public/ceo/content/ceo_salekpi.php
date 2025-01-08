<div class="container-fluid">
    <div class="row pt-3">
        <div class="col-sm text-center">
            <h6 style='color: #607080;'><i class="fas fa-dollar-sign"></i> รายงานยอดขาย</h6>
            <div class='d-flex justify-content-center'>
                <hr class='mt-1 w-75 text-muted'>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-5">
            <select class="form-select form-select-sm" id="filt_year">
            <?php
                for($y = $this_year; $y >= $start_year; $y--) {
                    if($y == $this_year) {
                        $y_slct = " selected";
                    } else {
                        $y_slct = " disabled";
                    }
                    echo "<option value='$y'$y_slct>$y</option>";
                }
            ?>
            </select>
        </div>
        <div class="col-7">
            <select class="form-select form-select-sm" id="filt_month">
            <?php
                for($m = 1; $m <= 12; $m++) {
                    if($m == $this_month) {
                        $m_slct = " selected";
                    } else {
                        $m_slct = "";
                    }
                    echo "<option value='$m'$m_slct>".FullMonth($m)."</option>";
                }
            ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <select class="form-select form-select-sm" name="filt_team" id="filt_team">
                <option value="ALL">ทุกทีม</option>
                <option value="MT1"><?php echo SATeamName("MT1"); ?></option>
                <option value="EXP"><?php echo SATeamName("EXP"); ?></option>
                <option value="MT2"><?php echo SATeamName("MT2"); ?></option>
                <option value="TT2"><?php echo SATeamName("TT2"); ?></option>
                <option value="TT1"><?php echo SATeamName("TT1"); ?></option>
                <option value="OUL"><?php echo SATeamName("OUL"); ?></option>
                <option value="ONL"><?php echo SATeamName("ONL"); ?></option>
            </select>
        </div>
    </div>

    <div id="SAContent"></div>
</div>

<div class="modal fade" tabindex="-1" id="SaleByMonth">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-dollar-sign fa-fw fa-1x"></i> ข้อมูลการขายของ <span id="txt_TeamName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/salekpi.js"></script>