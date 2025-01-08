<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>EUROX FORCE : PACK</title>
        <link href="../../../image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />

        <link rel="stylesheet" href="../../../css/main/app.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
        <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
        <script src="../../../js/jquery-min.js" type="text/javascript"></script>

        <style>
            body{
                overflow-y: hidden;
            }

            .list-so{
                padding-left: 5px;
                color: #515151;
                font-size: 13.5px;
            }

            .list-so:hover{
                color: #9A1118;
            }

            .list-bx{
                padding-left: 5px;
                color: #515151;
                font-size: 13px;
            }

            .list-bx:hover{
                color: #9A1118;
            }

            .TableHeader tbody tr td{
                padding: 5px;
            }

            .checkbox-custom{
                height: 26px;
                width: 26px;
                border: 1px solid #dce7f1 !important;
            }
            .checkbox-custom:disabled{
                background-color: #e9ecef;
                opacity: 1;
            }

            .checkbox-Hbx-custom{
                height: 23px;
                width: 23px;
                border: 1px solid #dce7f1 !important;
            }
            .checkbox-Hbx-custom:disabled{
                background-color: #e9ecef;
                opacity: 1;
            }

            .checkbox-bx-custom{
                height: 14px;
                width: 14px;
                border: 1px solid #dce7f1 !important;
            }

            .form-show:disabled{
                background-color: #FFF !important;
                opacity: 1 !important;
            }
        </style>

        <style>
            
        </style>
    </head>
    <body class='bg-light'>
        <div class="d-flex" style='padding: 5px 5px 5px 5px'>
        <!-- Col 1 -->
            <div style='width: 11%; height: 100vh;'>
                <div class='d-flex'>
                    <div style='width: 20%;'>
                        <i class="fas fa-user-tie fa-fw text-primary" style='font-size: 24px;'></i>
                    </div>
                    <div style='width: 80%;'>
                        <span class='fw-bolder' style='font-size: 12px;'>วรวิทย์ ขันธมูล (โต๊ะ <span id='IDTable'></span>)</span>
                    </div>
                </div>
                <div class='d-flex pt-2 pe-1'>
                    <div style='width: 20%;'>
                        <i class="fas fa-people-carry text-primary" style='font-size: 24px;'></i>
                    </div>
                    <div style='width: 80%;'>
                        <select class='form-select form-select-sm fw-bolder' style='font-size: 12px;' name="Checker" id="Checker" onchange="InserPackerOnline();"></select>
                    </div>
                </div>

                <div class="border border-end-0 mt-2 p-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class='text-primary fw-bolder' style='font-size: 17px;'>รายการจัดสินค้า</span>
                        <button class='btn btn-sm btn-primary' onclick="ResetSO();"><i class="fas fa-sync-alt"></i></button>
                    </div>

                    <div class="d-flex pt-2">
                        <input type="text" class='form-control form-control-sm' name='search_so' id='search_so' placeholder='ค้นหา' onfocusout="CheckIDTable();">
                    </div>
                    
                    <div class="mt-2 border" style='height: 100vh;' id='List-SO'>
                        <div class='d-flex'>
                            <a href="javascript:void(0);" class='list-so' onclick="CallSO('SO-660100660');">SO-660100660 [ KBI ]</a>
                        </div>
                        <div class='d-flex'>
                            <a href="javascript:void(0);" class='list-so' onclick="CallSO('SO-660100640');">SO-660100640 [ OUL ]</a>
                        </div>
                        <div class='d-flex'>
                            <a href="javascript:void(0);" class='list-so' onclick="CallSO('SO-660100666');">SO-660100666 [ TT2 ]</a>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Col 2 -->
            <div style='width: 76%; height: 100vh;'>
                <div class="border p-1" style='height: 100vh;'>
                    <div class='text-center text-primary fw-bolder' style='font-size: 15px;'>รายการแพ็ค <a href="javascript:void(0);" onclick="FullScreen()" ><i class="fas fa-desktop iconFullScreen text-secondary"></i></a></div>
                    <div class="d-flex">
                        <div style="width: 50%; padding-right: 10%;">
                            <table class='table table-sm table-borderless TableCustom' style='font-size: 13px;'>
                                <tbody>
                                    <tr>
                                        <td width="15%" class='fw-bolder'>เลขที่ใบเสร็จ</td>
                                        <td><input type="text" class='form-show form-control form-control-sm' name='DocNum' id='DocNum' disabled></td>
                                        <td class='fw-bolder'>พนักงานเบิก</td>
                                        <td><input type="text" class='form-show form-control form-control-sm' name='SlpName' id='SlpName' disabled></td>
                                    </tr>
                                    <tr>
                                        <td class='fw-bolder'>ชื่อลูกค้า</td>
                                        <td colspan='3'><input type="text" class='form-show form-control form-control-sm' name='CardName' id='CardName' disabled></td>
                                    </tr>
                                    <tr>
                                        <td class='fw-bolder align-baseline'>ที่อยู่จัดส่ง</td>
                                        <td colspan='3'>
                                            <textarea class='form-show form-control form-control-sm txtInput' style='resize: none;' name="Address" id="Address" disabled></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='fw-bolder'>ชื่อขนส่ง</td>
                                        <td colspan='3'><input type="text" class='form-show form-control form-control-sm' name='TransName' id='TransName' disabled></td>
                                    </tr>
                                    <tr>
                                        <td class='fw-bolder'>วันที่จัด</td>
                                        <td colspan='3'><input type="text" class='form-show form-control form-control-sm' name='DocDate' id='DocDate' disabled></td>
                                    </tr>
                                    <tr>
                                        <td class='fw-bolder align-baseline'>หมายเหตุ</td>
                                        <td colspan='3'>
                                            <textarea class='form-show form-control form-control-sm txtInput' style='resize: none;' name="Remark" id="Remark" disabled></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="width: 50%; padding-left: 10%;">
                            <table class='table table-sm table-borderless TableCustom' style='font-size: 13px;'>
                                <tbody>
                                    <tr>
                                        <td width="15%" class='fw-bolder'>เลือกสาขา</td>
                                        <td colspan='2'><select class='form-select form-select-sm' name="ItemCRC" id="ItemCRC" disabled></select></td>
                                    </tr>
                                    <tr class=''>
                                        <td colspan='3' class='text-right pb-3'><button class='btn btn-sm btn-secondary btn-AddCRC' disabled>ยืนยันสาขา</button></td>
                                    </tr>
                                    <tr>
                                        <td width="15%" class='fw-bolder'>บาร์โค้ด</td>
                                        <td colspan='2'><input type="text" class='form-control form-control-sm' name="BarCode" id="BarCode" disabled></td>
                                    </tr>
                                    <tr>
                                        <td width="15%" class='fw-bolder'>จำนวน</td>
                                        <td colspan='2' class='d-flex justify-content-between'>
                                            <div class='d-flex align-items-center '>
                                                <input type="number" class='form-control form-control-sm text-right' name='Quantity' id='Quantity' value='1' disabled>
                                                <span class='lock-item ps-2' style='cursor: pointer;'><i class='fas fa-unlock fa-fw' style='font-size: 20px;'></i></span>
                                                <input type="hidden" name='LockItem' id='LockItem' value='N'>
                                            </div>
                                            <div>
                                                <button class='btn btn-sm btn-primary btn-Pack' disabled>แพ็คลงลัง</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="15%">&nbsp;</td>
                                        <td colspan='2' class='d-flex'>
                                            <button class='btn btn-sm btn-primary btn-AddBill' disabled>เพิ่มบิลลงลัง</button>
                                            <button class='btn btn-sm btn-secondary ms-3 btn-DeleteBill' disabled>ลบบิลลงลัง</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="15%">&nbsp;</td>
                                        <td colspan='2' class='d-flex align-items-end'>
                                            <input type="checkbox" class='checkbox-custom form-check-input' name='DoHome' id='DoHome' disabled>
                                            <span class="ps-2 fw-bolder">เพิ่มรายการสินค้าสำหรับดูโฮม</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="border" style='height: 100vh; background-color: #e9ecef; opacity: 1;'>
                        
                    </div>
                </div>
            </div>

        <!-- Col 3 -->
            <div class='ps-2 pe-1' style='width: 13%; height: 100vh;'>
                <div style='height: 35%;'>
                    <div class="border text-center pt-1 pb-1">
                        <span class='text-primary fw-bolder'>จำนวนลัง</span>
                    </div>
                    <div class="border border-top-0 pt-2 pb-2">
                        <div class='d-flex align-items-center'>
                            <div style='width: 30%;'></div>
                            <div class='text-center' style='width: 40%;'>
                                <span class='fw-bolder text-dark' style='font-size: 30px;'>0</span>
                            </div>
                            <div style='width: 30%;'>
                                <img src="../../../image/logo/Box.gif" style='width: 35px;'>
                            </div>
                        </div>
                    </div>
    
                    <div class="border text-center pt-1 pb-1 mt-3">
                        <span class='text-primary fw-bolder'>กรอกจำนวนลัง</span>
                    </div>
                    <div class="border border-top-0 p-2">
                        <input class='form-control form-control-sm text-right fw-bolder text-dark' style='font-size: 20px;' type="number" name='AddBox' id='AddBox' value='1' disabled>
                        <button class='btn btn-secondary w-100 mt-2 btn-AddBox' disabled>เปิดลังใหม่</button>
                    </div>
                </div>

                <div style='height: 65%;'>
                    <div class='d-flex align-items-end justify-content-center' >
                        <input type="checkbox" class='checkbox-Hbx-custom form-check-input' name='SelectAllBox' id='SelectAllBox' disabled>
                        <span class="fw-bolder ps-2" style='font-size: 17px;'>เลือกกล่องทั้งหมด</span>
                    </div>
                    

                    <div class='border mt-2' style='height: 42vh;' id='ListBox'></div>

                    <div class='d-flex align-items-center justify-content-between' >
                        <button class='btn btn-sm btn-outline-dark btn-VP mt-2' style='width: 80px;' onclick="CallView();" disabled>ดูใบ</button>
                        <button class='btn btn-sm btn-outline-danger btn-VP mt-2' style='width: 80px;' onclick="CallPrint();" disabled>พิมพ์</button>
                    </div>

                    <button class='btn btn-sm btn-outline-success w-100 mt-4 btn-ConfirmPack' style='font-size: 18px;' disabled>ยืนยันการแพ็ค</button>

                    <button class='btn btn-sm btn-primary w-100 mt-2 btn-Save' style='font-size: 18px;' disabled>บันทึก</button>
                </div>
            </div>
        </div>

        <!-- MODAL ALERT -->
        <div class="modal fade" id="Alert" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h5 class="modal-title" id="AlertHeader"></h5>
                        <p id="AlertBody" class="my-4 font-Mitr fw-bold"></p>
                        <button type="button" class="btn btn-sm btn-secondary btn-alert" data-bs-dismiss="modal">ตกลง</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL VIEW / PRINT -->
        <div class="modal fade" id="ModalVP" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="far fa-file-alt " style='font-size: 17px;'></i> <span style='font-size: 17px;'>ใบปะหน้ากล่อง</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style=''></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">ออก</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../../js/app.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js" type="text/javascript"></script>

        <script>
            function FullScreen() {
                let doc = window.document;
                let docEl = doc.documentElement;

                let requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
                let cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

                if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
                    requestFullScreen.call(docEl);
                    $(".iconFullScreen").removeClass("text-secondary");
                    $(".iconFullScreen").addClass("text-danger");
                }
                else {
                    cancelFullScreen.call(doc);
                    $(".iconFullScreen").addClass("text-secondary");
                    $(".iconFullScreen").removeClass("text-danger");
                }
            }
        </script>

        <script>
            $(document).ready(function(){
                GetTable();
                GetChecker();
                GetListSO();
            });

            function GetTable() {
                $.ajax({
                    url: "ajax/ajaxmain.php?a=GetTable",
                    type: "GET",
                    success: function(result) {
                        let obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            $("#IDTable").html(inval['Table']);
                            sessionStorage.setItem('tmpTable',JSON.stringify(inval['Table']));
                        })
                    }
                })
            }

            function GetChecker() {
                $.ajax({
                    url: "ajax/ajaxmain.php?a=GetChecker",
                    type: "GET",
                    success: function(result) {
                        let obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            $("#Checker").html(inval['Option']);
                        })
                    }
                })
            }

            function GetListSO() {
                $.ajax({
                    url: "ajax/ajaxmain.php?a=GetListSO",
                    type: "GET",
                    success: function(result) {
                        let obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            $("#List-SO").html(inval['ListSO']);
                        })
                    }
                })
            }

            function InserPackerOnline() {
                const Checker = $("#Checker").val();
                const tmpTable = JSON.parse(sessionStorage.getItem('tmpTable'));
                $.ajax({
                    url: "ajax/ajaxmain.php?a=InserPackerOnline",
                    type: "POST",
                    data: { Checker : Checker, tmpTable : tmpTable, },
                    success: function(result) {
                        let obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {

                        })
                    }
                })
            }

            function CheckIDTable() {
                const IDTable = $("#Checker").val();
                if(IDTable == null) {
                    $("#AlertHeader").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                    $("#AlertBody").html("กรุณาเลือกพนักงาน Checker ก่อน");
                    $("#Alert").modal('show');
                }
            }

            $("#search_so").on('keypress',function(e) {
                if(e.which == 13) {
                    const SO = $("#search_so").val();
                    CallSO(SO);
                }
            });

            function CallSO(SO) {
                const IDTable = $("#Checker").val();
                if(IDTable != null) {
                    $("#search_so").val(SO);
                    $.ajax({
                        url: "ajax/ajaxmain.php?a=CallSO",
                        type: "POST",
                        data: { SO : SO,},
                        success: function(result) {
                            var obj = jQuery.parseJSON(result);
                            $.each(obj, function(key, inval) {
                                // DocNum => เลขที่ใบเสร็จ
                                $("#DocNum").val(inval['DocNum']);

                                // SlpName => พนักงานเบิก
                                $("#SlpName").val('วิทยา จันดากุล');

                                // CardName => ชื่อลูกค้า
                                $("#CardName").val(inval['CardName']);

                                // Address => ที่อยู่จัดส่ง
                                $("#Address").val(inval['Address']);

                                // TransName => ชื่อขนส่ง
                                $("#TransName").val(inval['Uname']);

                                // DocDate => วันที่จัด
                                $("#DocDate").val(inval['PackDate']);

                                // Remark => หมายเหตุ
                                $("#Remark").val(inval['Remark']);
                                $("#AlertHeader").html("<i class=\"fas fa-thumbtack fa-fw fa-lg text-danger\"></i> หมายเหตุ");
                                $("#AlertBody").html(inval['Remark']);
                                $("#Alert").modal('show');

                                // --- Unlock Input --- //
                                // #กรณีที่ลูกค้าคือ ซีอาร์ซี ไทวัสดุ ให้เปิดปุ่ม `เลือกสาขา`
                                if(inval['CRC'] == 'Y') {
                                    $("#ItemCRC").html(inval['option_crc']);
                                    $("#ItemCRC").val(inval['value_crc']);
                                    $("#ItemCRC").prop("disabled", false);
                                    // btn-AddCRC => ยืนยันสาขา
                                    $(".btn-AddCRC").prop("disabled", false);
                                }else{
                                    $("#ItemCRC").html("");
                                    $("#ItemCRC").prop("disabled", true);
                                    // btn-AddCRC => ยืนยันสาขา
                                    $(".btn-AddCRC").prop("disabled", true);
                                }

                                // BarCode => บาร์โค้ด
                                $("#BarCode").prop("disabled", false);

                                // Quantity => จำนวน
                                $("#Quantity").prop("disabled", false);

                                // btn-Pack => แพ็คลงลัง
                                $(".btn-Pack").prop("disabled", false);
                                
                                // btn-AddBill => เพิ่มบิลลงลัง
                                $(".btn-AddBill").prop("disabled", false);

                                // btn-DeleteBill => ลบบิลลงลัง
                                $(".btn-DeleteBill").prop("disabled", false);

                                // #กรณีสินค้าดูโฮม
                                // $("#DoHome").prop("disabled", false);

                                // AddBox => กรอกจำนวนลัง
                                $("#AddBox").prop("disabled", false);

                                // btn-AddBox => เปิดลังใหม่
                                $(".btn-AddBox").prop("disabled", false);

                                // SelectAllBox => เลือกกล่องทั้งหมด, ListBox => Box
                                $("#SelectAllBox").prop("disabled", false);
                                
                                // btn-View => ดูใบ / พิมพ์
                                // $(".btn-VP").prop("disabled", false);

                                // btn-ConfirmPack => ยืนยันการแพ็ค
                                $(".btn-ConfirmPack").prop("disabled", false);

                                // btn-Save => บันทึก
                                $(".btn-Save").prop("disabled", false);
                            })
                        }
                    })
                }else{
                    $("#AlertHeader").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                    $("#AlertBody").html("กรุณาเลือกพนักงาน Checker ก่อน");
                    $("#Alert").modal('show');
                }
            }

            function ResetSO() {
                $("#search_so").val("");
                $("#DocNum").val('');
                $("#SlpName").val('');
                $("#CardName").val('');
                $("#Address").val('');
                $("#TransName").val('');
                $("#DocDate").val('');
                $("#Remark").val('');
                $("#ItemCRC").prop("disabled", true);
                $(".btn-AddCRC").prop("disabled", true);
                $("#BarCode").prop("disabled", true);
                $("#Quantity").prop("disabled", true);
                $("#Quantity").val(1);
                $(".btn-Pack").prop("disabled", true);
                $(".btn-AddBill").prop("disabled", true);
                $(".btn-DeleteBill").prop("disabled", true);
                $("#DoHome").prop("disabled", true);
                $("#AddBox").prop("disabled", true);
                $(".btn-AddBox").prop("disabled", true);
                $("#SelectAllBox").prop("disabled", true);
                $("#ListBox").html("");
                $(".btn-VP").prop("disabled", true);
                $(".btn-ConfirmPack").prop("disabled", true);
                $(".btn-Save").prop("disabled", true);
                GetListSO();
            }

            $(".lock-item").on("click", function(){
                if($("#LockItem").val() == 'N') {
                    $("#LockItem").val("Y");
                    $(".lock-item").html("<i class='fas fa-lock fa-fw text-primary' style='font-size: 20px;'></i>");
                }else{
                    $("#LockItem").val("N");
                    $(".lock-item").html("<i class='fas fa-unlock fa-fw' style='font-size: 20px;'></i>");
                }
            })

            // เปิดลังใหม่
            $(".btn-AddBox").on("click", function() {
                const NumBox = $("#AddBox").val();
                let Box = "";
                for(let b = 1; b <= NumBox; b++) {
                    if(b < 10) {
                        Box += 
                        "<div class='d-flex align-items-center list-bx'>"+
                            "<input type='checkbox' name='chk_box"+b+"' id='chk_box"+b+"' class='checkbox-bx-custom form-check-input chk_box' onchange='ChkBox();'>"+
                            "<label for='chk_box"+b+"' class='list-bx'>[0"+b+"] BX-23011120008 (03)</label>"+
                        "</div>";
                    }else{
                        Box += 
                        "<div class='d-flex align-items-center list-bx'>"+
                            "<input type='checkbox' name='chk_box"+b+"' id='chk_box"+b+"' class='checkbox-bx-custom form-check-input chk_box' onchange='ChkBox();'>"+
                            "<label for='chk_box"+b+"' class='list-bx'>["+b+"] BX-23011120008 (03)</label>"+
                        "</div>";
                    }
                }
                $("#ListBox").html(Box);
                sessionStorage.setItem('tmpChkBox',JSON.stringify(NumBox));
            })

            // เลือกกล่องทั้งหมด
            $("#SelectAllBox").on("click", function() {
                if($('#SelectAllBox').is(':checked') == true) {
                    $('.chk_box').prop('checked', true);
                }else{
                    $('.chk_box').prop('checked', false);
                }
                ChkBox();
            })

            function ChkBox() {
                let tmpChkBox = JSON.parse(sessionStorage.getItem('tmpChkBox'));
                let Chk = 0;
                for(let r = 1; r <= tmpChkBox; r++) {
                    if($('#chk_box'+r).is(':checked') == true) {
                        Chk++;
                    }
                }
                if(Chk != 0) {
                    $(".btn-VP").prop("disabled", false);
                }else{
                    $(".btn-VP").prop("disabled", true);
                    $("#SelectAllBox").prop('checked', false);
                }
            }

            function CallView() {
                const DocNum = 120942;
                // rpGeneral, rpThaiwatsadu = height: 700px;
                // rpHomepro = height: 900px;
                $("#ModalVP .modal-body").attr("style", "height: 900px;");
                $("#ModalVP .modal-body").html("<iframe id='printf' name='printf' src='report/rpHomepro.php?DocNum="+DocNum+"' frameborder='yes' scrolling='yes' width='100%' height='100%'></iframe>");
                $("#ModalVP").modal("show");
            }

            function CallPrint() {
                const DocNum = 120942;
                $("#ModalVP .modal-body").html("<iframe id='printf' name='printf' src='report/rpHomepro.php?DocNum="+DocNum+"' frameborder='yes' scrolling='yes' width='100%' height='100%'></iframe>");
                $("#printf").get(0).contentWindow.print();
                // window.open("report/rpThaiwatsadu.php?DocNum="+DocNum,"_blank").print();
            }
        </script>
    </body>
</html>