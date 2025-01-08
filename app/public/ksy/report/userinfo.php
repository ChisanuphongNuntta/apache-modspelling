<?php 
$start_year = 2023;
$this_year  = date("Y");
$this_month = date("m");
require("../template/header.php");

$LvCode = $_SESSION['LvCode'];
if($LvCode != "LV077") {
    $opt_MT = " disabled";
    $opt_TT = " disabled";
} else {
    $opt_MT = NULL;
    $opt_TT = NULL;
}
?>
<div class='container bg-light h-100'>
    <div class="row pt-3">
        <div class="col-sm text-center">
            <h6 style='color: #607080;'><i class="fas fa-file-alt"></i> รายงานการปฏิบัติงานประจำวัน</h6>
            <div class='d-flex justify-content-center'>
                <hr class='mt-1 w-75 text-muted'>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-5">
            <select class="form-select form-select-sm" name="filt_year" id="filt_year">
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
            <div class="form-group">
                <select class="form-select form-select-sm" name="filt_month" id="filt_month">
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
        <?php if($LvCode == "LV077") { ?>
        <div class="col-12">
            <div class="form-group">
                <select class="form-select form-select-sm" name="filt_teamcode" id="filt_teamcode">
                    <option value="ALL">ร้านค้าทั้งหมด</option>
                    <option value="MT" <?php echo $opt_MT;?>>ร้านค้าโมเดิร์นเทรด</option>
                    <option value="TT" <?php echo $opt_TT;?>>ร้านค้าทั่วไป</option>
                </select>
            </div>
        </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-sm" style="font-size: 12px;" id="ShowData">
                <thead class="text-center text-white" style="background-color: #9A1118;">
            <?php
                if($LvCode == "LV077") {
            ?>
                    <tr>
                        <th width="7.5%" rowspan="2">วันที่</th>
                        <th colspan="2">เป้าหมาย<br/>การเบิก</th>
                        <th colspan="2">เบิกตาม<br/>กำหนด</th>
                        <th width="10%" rowspan="2">S/O<br/>ยกเลิก</th>
                    </tr>
                    <tr>
                        <th width="10%">S/O</th>
                        <th width="10%">SKU</th>
                        <th width="10%">S/O</th>
                        <th width="10%">SKU</th>
                    </tr>
            <?php
                } else {
            ?>
                    <tr>
                        <th width="15%" rowspan="2">วันที่</th>
                        <th width="42.5%">เติม</th>
                        <th width="42.5%">โอนย้าย</th>
                    </tr>
            <?php
                }
            ?>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?php require("../template/script.php"); ?>
<script type="text/javascript">
    function GetData() {
        let filt_y = $("#filt_year").val();
        let filt_m = $("#filt_month").val();
        let filt_t = $("#filt_teamcode").val();
        let LvCode = '<?php echo $LvCode; ?>';
        let SendData = "";

        if(LvCode == "LV077") {
            SendData = 
                {
                    y: filt_y,
                    m: filt_m,
                    t: filt_t
                }
        } else {
            SendData = 
                {
                    y: filt_y,
                    m: filt_m,
                }
        }

        $.ajax({
            url: "ajax/ajaxuserinfo.php?p=GetData",
            type: "POST",
            data: SendData,
            success: function(result) {
                let obj = jQuery.parseJSON(result);
                $.each(obj ,function(key, inval) {
                    let tBody = "";
                    let Template = parseFloat(inval['Template']);
                    let LoopDay  = parseFloat(inval['LoopDay']);
                    if(Template == 1) {
                        for(d = 1; d <= LoopDay; d++) {
                            if(inval[d]['WeekDate'] == 0) {
                                var RowCls = "class= 'table-danger text-danger'";
                            } else {
                                var RowCls = "";
                            }

                            if(inval[d]['TargetSO'] == null || inval[d]['TargetSO'] == 0) { var TargetSO = "-"; } else { var TargetSO = inval[d]['TargetSO']; }
                            if(inval[d]['TargetSKU'] == null || inval[d]['TargetSKU'] == 0) { var TargetSKU = "-"; } else { var TargetSKU = inval[d]['TargetSKU']; }
                            if(inval[d]['ONTIME_SO'] == null || inval[d]['ONTIME_SO'] == 0) { var ONTIME_SO = "-"; } else { var ONTIME_SO = inval[d]['ONTIME_SO']; }
                            if(inval[d]['ONTIME_SKU'] == null || inval[d]['ONTIME_SKU'] == 0) { var ONTIME_SKU = "-"; } else { var ONTIME_SKU = inval[d]['ONTIME_SKU']; }
                            if(inval[d]['CANCELED_SO'] == null || inval[d]['CANCELED_SO'] == 0) { var CANCELED_SO = "-"; } else { var CANCELED_SO = inval[d]['CANCELED_SO']; }
                            tBody +=
                                "<tr "+RowCls+">"+
                                    "<td class='text-right'>"+d+"</td>"+
                                    "<td class='text-right'>"+TargetSO+"</td>"+
                                    "<td class='text-right'>"+TargetSKU+"</td>"+
                                    "<td class='text-right' style='font-weight: bold;'>"+ONTIME_SO+"</td>"+
                                    "<td class='text-right' style='font-weight: bold;'>"+ONTIME_SKU+"</td>"+
                                    "<td class='text-right'>"+CANCELED_SO+"</td>"+
                                "</tr>";
                        }
                    } else {
                        for(d = 1; d <= LoopDay; d++) {
                            if(inval[d]['WeekDate'] == 0) {
                                var RowCls = "class= 'table-danger text-danger'";
                            } else {
                                var RowCls = "";
                            }

                            if(inval[d]['Refill'] == null || inval[d]['Refill'] == 0) { var Refill = "-"; } else { var Refill = inval[d]['Refill']; }
                            if(inval[d]['Transfer'] == null || inval[d]['Transfer'] == 0) { var Transfer = "-"; } else { var Transfer = inval[d]['Transfer']; }

                            tBody +=
                                "<tr "+RowCls+">"+
                                    "<td class='text-right'>"+d+"</td>"+
                                    "<td class='text-right'>"+Refill+"</td>"+
                                    "<td class='text-right'>"+Transfer+"</td>"+
                                "</tr>";
                        }
                    }
                    $("#ShowData tbody").html(tBody);
                })
            }
        });
    }

    GetData();
    $("#filt_year, #filt_month, #filt_teamcode").on("change", function() {
        GetData();
    });
</script>
<?php require("../template/footer.php"); ?>