<?php
date_default_timezone_set('Asia/Bangkok');
require("../core/config.core.php");
include("../core/functions.core.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EUROX Force : PACKER</title>

    <link rel="stylesheet" href="../css/main/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link href="../image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <script src="../js/jquery-min.js" type="text/javascript"></script>
    <style>
        .nav-tabsCus {
            border-bottom: 1px solid rgba(124, 141, 159, 0.73);
            /* border-bottom: 1px solid #dee2e6; */
        }

        .nav-tabsCus .nav-linkCus {
            margin-bottom: -1px;
            background: 0 0;
            border: 1px solid transparent;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            padding: 6px 11px 4px;
            color: #662F2F;
        }

        .nav-tabsCus .nav-linkCus:focus,
        .nav-tabsCus .nav-linkCus:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
            isolation: isolate
        }

        .nav-tabsCus .nav-itemCus.show .nav-linkCus,
        .nav-tabsCus .nav-linkCus.active {
            color: #9A1118;
            /* color: #495057; */
            background-color: #fff;
            border-color: rgba(124, 141, 159, 0.73) rgba(124, 141, 159, 0.73) #fff;
            /* border-color: #dee2e6 #dee2e6 #fff */
        }

        #tbody1 tr:hover {
            background-color: rgba(106, 106, 106, 0.19) !important;
            color: #000000 !important;
        }
        #tbody2 tr:hover {
            background-color: rgba(106, 106, 106, 0.19) !important;
            color: #000000 !important;
        }
    </style>
</head>
<body>
    <?php //require("packercopy.php"); ?>
    <?php
        $sql = "SELECT TableID, IPAddress FROM checkertable WHERE IPAddress = '".$_SERVER['REMOTE_ADDR']."'"; 
        $result = MySQLSelect($sql);
        if(isset($result['TableID'])) {
            $TableID = $result['TableID'];
        }else{
            $TableID = "0";
        }
        switch ($TableID){
            case '1': $selAll = "";         $sel1 = "selected"; $sel2 = "";         $sel3 = "";         $sel4 = "";         $sel5 = ""; break;
            case '2': $selAll = "";         $sel1 = "";         $sel2 = "selected"; $sel3 = "";         $sel4 = "";         $sel5 = ""; break;
            case '3': $selAll = "";         $sel1 = "";         $sel2 = "";         $sel3 = "selected"; $sel4 = "";         $sel5 = ""; break;
            case '4': $selAll = "";         $sel1 = "";         $sel2 = "";         $sel3 = "";         $sel4 = "selected"; $sel5 = ""; break;
            case '5': $selAll = "";         $sel1 = "";         $sel2 = "";         $sel3 = "";         $sel4 = "";         $sel5 = "selected"; break;
            default : $selAll = "selected"; $sel1 = "";         $sel2 = "";         $sel3 = "";         $sel4 = "";         $sel5 = "";break;
        }
    ?>
    <!-- CONTENT -->
    <div class='pt-3 pb-3 ps-4 pe-4'>
        <div class='d-flex align-items-center text-primary'>
            <i class="fas fa-box-open" style='font-size: 40px;'></i> <span style='font-size: 25px;'>สรุปรายการแพ็กสินค้าตาม SO</span>
        </div>
        <hr class='text-gray-500'>
        <div class='d-flex align-items-center justify-content-between'>
            <div class="form-group" style='width: 200px;'>
                <label for=""><i class="fas fa-desktop"></i> เลือกโต๊ะจัด</label>
                <select class="form-select form-select-sm" name="FilterTable" id="FilterTable" onchange="CallData();">
                    <option value="ALL" <?php echo $selAll; ?>>ทุกโต๊ะ</option>
                    <option value="1" <?php echo $sel1; ?>>โต๊ะ 1</option>
                    <option value="2" <?php echo $sel2; ?>>โต๊ะ 2</option>
                    <option value="3" <?php echo $sel3; ?>>โต๊ะ 3</option>
                    <option value="4" <?php echo $sel4; ?>>โต๊ะ 4</option>
                    <option value="5" <?php echo $sel5; ?>>โต๊ะ 5</option>
                </select>
            </div>
            <div class="form-group" style='width: 250px;'>
                <label for=""><i class="fas fa-search"></i> ค้นหา</label>
                <input type="text" class="form-control form-control-sm" placeholder='ค้นหา...' id="FilterSearch">
            </div>
        </div>

            <ul class="nav nav-tabsCus" id="tabReturn" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-linkCus active fw-bold" style='font-size: 14px;' onclick="" id="cBill-tab" data-bs-toggle="tab" data-bs-target="#cBill" type="button" role="tab" aria-controls="cBill" aria-selected="true">รายการแพ็กที่ต้องส่งพรุ่งนี้</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-linkCus fw-bold" style='font-size: 14px;' onclick="" id="pBill-tab" data-bs-toggle="tab" data-bs-target="#pBill" type="button" role="tab" aria-controls="pBill" aria-selected="false">รายการแพ็กที่ต้องส่งล่วงหน้า</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-linkCus fw-bold" style='font-size: 14px;' onclick="" id="BillSuccess-tab" data-bs-toggle="tab" data-bs-target="#BillSuccess" type="button" role="tab" aria-controls="BillSuccess" aria-selected="false">รายการที่แพ็กเสร็จแล้ว</button>
                </li>
            </ul>
        <div class="tab-content" style='background-color: #fff;'>
            <div class="tab-pane fade show active" id="cBill" role="tabpanel" aria-labelledby="cBill-tab">
                <div class="table-responsive">
                    <table class='table table-sm table-bordered'>
                        <thead class='text-center' style='font-size: 14px;'>
                            <tr>
                                <th width='3%'></th>
                                <th width='5%'>ทีม</th>
                                <th width='10%'>เลขที่เอกสาร</th>
                                <th>ชื่อร้านค้า</th>
                                <th width='10%'>วันที่เปิดเอกสาร</th>
                                <th width='10%'>กำหนดส่ง</th>
                                <th width='7%'>จำนวนรายการ</th>
                                <th width='15%'>พนักงานเบิก</th>
                                <th width='10%'>สถานะของงาน</th>
                                <th width='5%'>โต๊ะจัด</th>
                            </tr>
                        </thead>
                        <tbody style='font-size: 13px;' id='tbody1'></tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="pBill" role="tabpanel" aria-labelledby="pBill-tab">
                <div class="table-responsive">
                    <table class='table table-sm table-bordered'>
                        <thead class='text-center' style='font-size: 14px;'>
                            <tr>
                                <th width='3%'></th>
                                <th width='5%'>ทีม</th>
                                <th width='10%'>เลขที่เอกสาร</th>
                                <th>ชื่อร้านค้า</th>
                                <th width='10%'>วันที่เปิดเอกสาร</th>
                                <th width='10%'>กำหนดส่ง</th>
                                <th width='7%'>จำนวนรายการ</th>
                                <th width='15%'>พนักงานเบิก</th>
                                <th width='10%'>สถานะของงาน</th>
                                <th width='5%'>โต๊ะจัด</th>
                            </tr>
                        </thead>
                        <tbody style='font-size: 13px;' id='tbody2'></tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="BillSuccess" role="tabpanel" aria-labelledby="BillSuccess-tab">
                <div class="table-responsive">
                    <table class='table table-sm table-bordered'>
                        <thead class='text-center' style='font-size: 14px;'>
                            <tr>
                                <th width='3%'></th>
                                <th width='5%'>ทีม</th>
                                <th width='10%'>เลขที่เอกสาร</th>
                                <th>ชื่อร้านค้า</th>
                                <th width='10%'>วันที่เปิดเอกสาร</th>
                                <th width='10%'>กำหนดส่ง</th>
                                <th width='7%'>จำนวนรายการ</th>
                                <th width='15%'>พนักงานเบิก</th>
                                <th width='10%'>สถานะของงาน</th>
                                <th width='5%'>โต๊ะจัด</th>
                            </tr>
                        </thead>
                        <tbody style='font-size: 13px;' id='tbody3'></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="CallModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pt-2 pb-2">
                    <h5 class="modal-title"><i class="fas fa-book-open" style='font-size: 20px;'></i>&nbsp;&nbsp;&nbsp;&nbsp;<span>รายละเอียดใบสั่งขาย</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer pt-2 pb-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ออก</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js" type="text/javascript"></script>

    <script>

        $(document).ready(function(){
            CallData();
        });

        function CallData() {
            $.ajax({
                url: "ajax/ajaxpacker.php?a=CallData",
                type: "POST",
                data: { Table : $("#FilterTable").val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#tbody1").html(inval['Table1']);
                        $("#tbody2").html(inval['Table2']);
                        $("#tbody3").html(inval['Table3']);
                    });
                }
            })
        }

        $("#FilterSearch").on("keyup", function(){
            var kwd = $(this).val().toLowerCase();
            $("#tbody1 tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
            $("#tbody2 tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
            $("#tbody3 tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
        });

        function CallModal(DocType,DocEntry) {
            $.ajax({
                url: "ajax/ajaxpacker.php?a=CallModal",
                type: "POST",
                data: { DocEntry : DocEntry, Func : DocType },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#CallModal .modal-body").html(inval['Data']);
                        $("#CallModal").modal('show');
                    });
                }
            })
        }
    </script>
</body>
</html>