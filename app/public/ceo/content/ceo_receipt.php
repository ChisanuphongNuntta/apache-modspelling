<style rel="stylesheet" type="text/css">
    @media only screen and (max-width:820px) {
        .tableFix {
            overflow-y: auto;
            height: 560px;
        }
    }

    @media (min-width:821px) and (max-width: 1180px) {
        .tableFix {
            overflow-y: auto;
            height: 720px;
        }
    }

    @media (min-width:1181px) {
        .tableFix {
            overflow-y: auto;
            height: 800px;
        }
    }

    .tableFix table.table {
        border-collapse: collapse;
    }

    .tableFix thead tr:first-child th {
        background-color: #9A1118;
        box-shadow: inset 0.5px 0.5px #eee, 0 0.5px #eee;
        position: sticky;
        top: 0;
        height: 36px;
    }
    .tableFix thead tr:last-child th {
        background-color: #9A1118;
        box-shadow: inset 0.5px 0.5px #eee, 0 0.5px #eee;
        position: sticky;
        top: 36px;
    }
</style>
<div class="container-fluid">
    <div class="row pt-3">
        <div class="col-sm text-center">
            <h6 style="color: #607080;"><i class="fas fa-money-bill-alt"></i> รายงานเก็บเงิน</h6>
        </div>
        <div class="d-flex justify-content-center">
            <hr class="mt-1 w-75 text-muted" />
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
                        $y_slct = "";
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
    <div class="SAContent">
        <div class="table-responsive tableFix">
            <table class="table table-bordered table-sm" style="font-size: 12px;" id="TableReceipt">
                <thead class="text-center text-white" style="background-color: #9A1118;">
                    <tr>
                        <th width="48px" rowspan="2">วัน</th>
                        <th width="48px" rowspan="2">วันที่</th>
                        <th colspan="9">ยอดเก็บเงิน KBI</th>
                        <th colspan="3">ยอดเก็บเงิน PITA</th>
                    </tr>
                    <tr>
                        <th width="84px">หน้าร้าน<br/>(บิล AA)</th>
                        <th width="84px">หน้าร้าน<br/>(ไม่ใช่บิล AA)</th>
                        <th width="84px">ออนไลน์</th>
                        <th width="84px">TT กทม.</th>
                        <th width="84px">TT ตจว.</th>
                        <th width="84px">MT 1</th>
                        <th width="84px">MT 2</th>
                        <th width="84px">ยอดเก็บเงิน<br/>ทั้งหมด</th>
                        <th width="84px">ค่าใช้จ่าย</th>
                        <th width="84px">ยอดขาย<br/>ทั้งหมด</th>
                        <th width="84px">ยอดเก็บ<br/>ทั้งหมด</th>
                        <th width="84px">ค่าใช้จ่าย</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/receipt.js"></script>