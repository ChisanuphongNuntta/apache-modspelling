<style type="text/css">
    
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-file-alt"></i> รายการโอนย้าย</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <i class='fas fa-search' style='font-size: 20px'></i>&nbsp;&nbsp;
                    <input class='form-control form-control-sm' type="text" id='FilterBox' name='FilterBox' placeholder="เลขที่ใบโอนย้าย/สถานะ">&nbsp;
                    <button class='btn btn-sm btn-secondary' onclick="ReCall()"><i class="fas fa-sync-alt"></i></button>&nbsp;&nbsp;&nbsp;
                    <button class='btn btn-sm btn-primary' style='font-size: 11px;' onclick="TransFer('New')">New</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <table class="table table-borderless">
                    <tbody style='font-size: 13px;' id='TbodyMain'></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalTransFer" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
        <div id='IDscreeenModal' class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center pt-2 pb-2">
                    <h5 class="modal-title"><i class="fas fa-file-alt"></i> รายการสินค้าโอนย้ายคลัง</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="ReCall()"></button>
                </div>
                <div class="modal-body">
                    <table class='table table-sm table-borderless'>
                        <thead style='font-size: 13px;'>
                            <tr>
                                <td colspan='2' class='fw-bold text-primary pb-1'><span id='textHead'></span></td>
                            </tr>
                        </thead>
                        <tbody style='font-size: 13px;'>
                            <tr>
                                <td class='fw-bold pb-0 pe-0'>เลขที่</td>
                                <td class='pb-0 d-flex'>
                                    <input class='form-control form-control-sm' type="text" id='trnID' name='trnID' readonly>&nbsp;
                                    <select class='form-select form-select-sm w-50' id='targetWHS'>
                                        <?php 
                                        $option = ['0','KSY','KSM','TT-C','MT','MT2','Outlet','WPO1','WP1','WP2','WP2.2','WP5','KB5','KB6','RD3','RD4','Z1','Z2'];
                                        $value = ['0','KSY','KSM','KSM','KSM','KSM','KSM','WP01','WP1','WP2','WP2.2','WP5','KB5','KB6','RD3','RD4','Z1','Z2'];
                                        echo "<option value='0' disabled selected>ปลายทาง</option>";
                                        for($i = 1; $i <= count($option)-1; $i++) {
                                            echo "<option value='".$value[$i]."'>".$option[$i]."</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class='pb-0 pe-0 fw-bold'>รายการสินค้า</td>
                                <td class='pb-0' colspan='2'><input class='form-control form-control-sm' type="text" id='ItemCode' name='ItemCode' placeholder="กรอกบาร์โค้ดสินค้า"></td>
                            </tr>
                            <tr>
                                <td class='pb-0 pe-0 fw-bold'>ชั้นวาง</td>
                                <td class='pb-0' colspan='2'><input class='form-control form-control-sm' type="text" id='LocRack' name='LocRack' placeholder="กรอกบาร์โค้ดชั้นวาง"></td>
                            </tr>
                            <tr>
                                <td class='pb-0 pe-0 fw-bold'>จำนวน</td>
                                <td class='pb-0 d-flex'>
                                    <input class='form-control form-control-sm' type="number" id='ItemQty' name='ItemQty' placeholder="จำนวน">&nbsp;
                                    <button type="button" class="btn btn-sm btn-primary" id='Btn_Save' onclick="CallData(3)">ส่ง</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class='table table-sm table-borderless m-0'>
                        <tbody id='ShowItem' style='font-size: 12px;'></tbody>
                    </table>
                    <input type="hidden" name='DataShowItem' id='DataShowItem'>
                    
                    <div class='tableFix'>
                        <table class='table table-sm'>
                            <thead style='font-size: 13px;'>
                                <tr class='text-center fw-bold'>
                                    <th style='background-color: #fff;'>รายการ</th>
                                </tr>
                            </thead>
                            <tbody style='font-size: 12px;' id='TbodyList'></tbody>
                        </table>
                    </div>
                    <div>
                        <div class='d-flex align-items-center'>
                            <span class='fw-bold'>ผู้ทำการโอนย้าย : </span>&nbsp;<span class='text-primary' id="nameTRN"></span>
                        </div>
                        <div class='d-flex align-items-center'>
                            <span class='fw-bold'>ผู้อนุมัติ : </span>&nbsp;<span class='text-primary' id="AppTRN"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-1 pb-1">
                    <?php
                        if ($_SESSION['LvCode'] == 'LV072' OR $_SESSION['LvCode'] == 'LV073'){
                            $InAppKey = 1;
                        }else{
                            $InAppKey = 0;
                        } 
                    ?>
                    <input type="hidden" id='AppKey' value='<?php echo $InAppKey; ?>'>
                    <?php if ($InAppKey == 1) {?>
                        <button type="button" class="btn btn-sm btn-primary" id='btnApp' onclick="CallData(5)">อนุมัติ</button>
                    <?php } ?>

                    <?php if($_SESSION['DeptCode'] == "DP002" || ($_SESSION['LvCode'] == "LV072" || $_SESSION['LvCode'] == "LV073" || $_SESSION['LvCode'] == "LV079" || $_SESSION['LvCode'] == "LV080")){ ?>
                    <div class='btn-print'></div>
                    <?php } ?>
                    
                    <button type="button" class="btn btn-sm btn-primary" id='btnSave' onclick="CallData(4)">ยืนยัน</button>
                    <button type="button" class="btn btn-sm btn-secondary" id='btnCancel' onclick="CallData(6)">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>

    <!-- กรณีมากกว่า1Item -->
    <div class="modal fade" id="ModalSeItem1" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class='d-flex align-items-center justify-content-between'>
                        <div></div>
                        <h5 class="modal-title text-center">เลือกสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="my-4">
                        <table class='table table-sm'>
                            <tbody id='TbodySeItem1' style='font-size: 12px;'></tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="SlectData(1)">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalSeItem2" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class='d-flex align-items-center justify-content-between'>
                        <div></div>
                        <h5 class="modal-title text-center">เลือกสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="my-4">
                        <table class='table table-sm'>
                            <tbody id='TbodySeItem2' style='font-size: 12px;'></tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="SlectData(2)">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalAlert" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h1 class="modal-title text-center" id="HeaderModalAlert"></h1>
                    <p id="DetailModalAlert" class="my-3"></p>
                    <button type="button" class="btn btn-sm w-25 mt-4" id='ModalAlertBTN' data-bs-dismiss="modal" onclick="">ออก</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ModalCheck -->
    <div class="modal fade" id="ModalCheck" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class='d-flex align-items-center justify-content-between'>
                        <div></div>
                        <h5 class="modal-title text-center" id="ModalHeader"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <p id="ModalDetail" class="mt-4 mb-3"></p>

                    <input type="hidden" name='tranSectID' id='tranSectID'>
                    <button type="button" class="btn btn-sm btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="DelSubmit()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <?php require("../template/script.php"); ?>
    <script>
        $(document).ready(function(){
            Call();
        });

        try{ document.createEvent("TouchEvent"); var isMobile = true; }
        catch(e){ var isMobile = false; }

        function Call() {
            $(".overlay").show();
            $("#FilterBox").val("");
            $.ajax({
                url: "ajax/ajaxtransfer.php?a=Call",
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) { 
                        $("#TbodyMain").html(inval['tr']);
                    })
                    $(".overlay").hide();
                }
            })
        }

        function ReCall() {
            $('#ShowItem').html("");
            Call();
        }

        $("#FilterBox").on("keyup", function(){
            var kwd = $(this).val().toLowerCase();
            $("#TbodyMain td").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
        });

        function TransFer(x) {
            switch(isMobile) {
                case true: var ClassMD = "modal-fullscreen"; break;
                case false: var ClassMD = "modal-lg"; break;
                default: var ClassMD = "modal-lg"; break;
            }
            $.ajax({
                url: "ajax/ajaxtransfer.php?a=TransFer",
                type: "POST",
                data: { trnID : x, },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) { 
                        $("#trnID").val(inval['trnID']);
                        CallData(0);
                        $("#IDscreeenModal").addClass(ClassMD);
                        $("#ModalTransFer").modal("show");
                    })
                }
            })
        }

        function CallData(x) {
            $(".btn-print").html("");
            var appKey = $('#AppKey').val();
            $.ajax({
                url: "ajax/ajaxtransfer.php?a=AddItem",
                type: "POST",
                data: { trnID : $("#trnID").val(),
                        ItemCode : $("#ItemCode").val(),
                        LocRack : $("#LocRack").val(),
                        Qty : $("#ItemQty").val(),
                        fun : x,
                        targetX : $("#targetWHS").val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) { 
                        switch (inval['Modal']) {
                            case 0:
                                $("#ItemCode").val("");
                                $("#LocRack").val("");
                                $("#ItemQty").val("");
                                if(inval['tg'] != null) {
                                    $("#targetWHS option[value='"+inval['tg']+"']").prop('selected', true);
                                }else{
                                    $("#targetWHS option[value='0']").prop('selected', true);
                                }
                                $('#nameTRN').html(inval['nameTRN']);
                                $('#AppTRN').html(inval['AppTRN']);
                                $('#textHead').html(inval['textH']);
                                $("#TbodyList").html(inval['output']);

                                
                                switch(inval['stDOC']) {
                                    case '1'://เอกสารใหม่
                                        // console.log(inval['stDOC']+" เอกสารใหม่");
                                        $("#btnCancel, #btnApp").attr('disabled','disabled');
                                        $('#Btn_Save, #btnSave, #ItemCode ,#LocRack, #ItemQty, #targetWHS').removeAttr('disabled');
                                        setTimeout(function(){
                                            $("#ItemCode").focus();
                                        }, 500);
                                        break;
                                    case '2'://เอกสารรออนุมัติ
                                        // console.log(inval['stDOC']+" เอกสารรออนุมัติ");
                                        $("#ItemCode, #LocRack, #ItemQty, #Btn_Save, #btnSave, #targetWHS").attr('disabled','disabled');
                                        if (appKey == 1){
                                            $('#btnApp, #btnCancel').removeAttr('disabled');
                                        }
                                        break;
                                    case '0': //เอกสารยกเลิก
                                    case '3': //เอกสารอนุมัติแล้ว
                                    case '4': //เอกสารสมบูรณ์
                                        // console.log(inval['stDOC']+" เอกสารยกเลิก, เอกสารอนุมัติแล้ว, เอกสารสมบูรณ์");
                                        $("#ItemCode, #LocRack, #ItemQty, #Btn_Save, #targetWHS, #btnCancel, #btnSave, #btnApp").attr('disabled','disabled');
                                        if(inval['stDOC'] != '0'){
                                            $(".btn-print").html("<button type='button' class='btn btn-sm btn-info' onclick='Print();'><i class='fas fa-print'></i> Print</button>");
                                        }
                                        break;
                                }
                                break;    
                            case 1: //ไม่พบรายการสินค้า
                                $('#ShowItem').html(inval['alert']);
                                $("#ItemCode").val("");
                                $("#LocRack").val("");
                                $("#ItemQty").val("");
                                setTimeout(function(){
                                    $('#ItemCode').focus();
                                }, 500);
                                break;     
                            case 2: //พบรายการสินค้า 1 รายการ
                                $('#ShowItem').html(inval['ShowItem']);
                                $("#DataShowItem").val(inval['DataShowItem']);
                                $('#TbodyList').html(inval['output']);
                                setTimeout(function(){
                                    $("#ItemQty").focus();
                                }, 500);
                                break;    
                            case 3: //พบรายการสินค้าหลายรายการ
                                if(inval['SeItem1'] != undefined) {
                                    if(isset(inval['SeItem1'])){
                                        $("#TbodySeItem1").html(inval['SeItem1']);
                                        $("#ModalSeItem1").modal("show");
                                    }else{
                                        $("#TbodySeItem2").html(inval['SeItem2']);
                                        $("#ModalSeItem2").modal("show");
                                    }
                                }else{
                                    setTimeout(function(){
                                        $('#ItemCode').focus();
                                    }, 500);
                                }
                                break;
                            case 4: //ระบุจำนวนและบันทึก
                                $("#ItemCode").val("");
                                $("#LocRack").val("");
                                $("#ItemQty").val("");
                                $('#ShowItem').html($("#DataShowItem").val());
                                $('#TbodyList').html(inval['output']);
                                setTimeout(function(){
                                    $('#ItemCode').focus();
                                }, 500);
                                break;
                            case 5:
                                switch(inval['alert']) {
                                    case 0:
                                        $("#HeaderModalAlert").html("<i class='fas fa-exclamation text-primary' style='font-size: 60px;'></i>");
                                        $("#DetailModalAlert").html("โปรดระบุคลังปลายทาง");
                                        break;
                                    case 1:
                                        $("#ItemCode, #LocRack, #ItemQty, #targetWHS, #Btn_Save, #btnSave, #btnCancel").attr('disabled','disabled');

                                        $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 60px;'></i>");
                                        $("#DetailModalAlert").html("ส่งเอกสารโอนย้ายเรียบร้อย");
                                        break;
                                    case 2:
                                        $("#HeaderModalAlert").html("<i class='fas fa-exclamation text-primary' style='font-size: 60px;'></i>");
                                        $("#DetailModalAlert").html("โปรดเพิ่มรายการที่ต้องการย้าย");
                                        break;
                                }
                                $("#ModalAlertBTN").addClass("btn-secondary");
                                $("#ModalAlert").modal("show");
                                break;
                        }
                    })
                }
            })
        }

        $("#ItemCode, #LocRack, #ItemQty").keypress(function (e) {
            if (e.which == 13) {
                if($("#ItemCode").val() == "") {
                    setTimeout(function(){
                        $('#ItemCode').focus();
                    }, 500);
                }else{
                    if($('#LocRack').val() == "") {
                        setTimeout(function(){
                            $('#LocRack').focus();
                        }, 500);
                    }else{
                        CallData(1);
                    }
                }
            }
        });

        $("#ItemQty").keypress(function (e) {
            if($("#ItemQty").val() == "") {
                setTimeout(function(){
                    $('#ItemQty').focus();
                }, 500);
            }
        });

        function SlectData(x){
            if(x == 1) {
                var BoxItem = $("#BoxItem:checked").val();
                $("#ItemCode").val(BoxItem);
                setTimeout(function(){
                    CallData(1);
                }, 500);
            }else{
                var BoxItem = $("#BoxItem:checked").val();
                $("#ItemCode").val(BoxItem);
                setTimeout(function(){
                    CallData(3);
                }, 500);
            }
        }

        function DelList(tranSectID,ItemName) {
            // console.log(tranSectID+" | "+ItemName);
            $("#tranSectID").val(tranSectID);

            $("#ModalHeader").html("<i class='fas fa-exclamation-circle fa-fw fa-lg'></i> ลบรายการ");
            $("#ModalDetail").html(ItemName);
            $("#ModalCheck").modal("show");
        }

        function DelSubmit() {
            $.ajax({
                url: "ajax/ajaxtransfer.php?a=DelItem",
                type: "POST",
                data: { trnSecID : $("#tranSectID").val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) { 
                        $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 60px;'></i>");
                        $("#DetailModalAlert").html(inval['output']);
                        $("#ModalAlertBTN").addClass("btn-secondary");
                        $("#ModalAlert").modal("show");
                        CallData(0);
                    })
                }
            })
        }

        function Print() {
            let trnID = $("#trnID").val();
            console.log(trnID);
            window.open ('print/printtransfer.php?trnID='+trnID,'_blank');
        }
    </script>
<?php require("../template/footer.php"); ?>