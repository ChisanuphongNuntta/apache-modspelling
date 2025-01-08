<style type="text/css">
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 21px;
    }
    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d9edf7;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 13px;
        width: 13px;
        left: 2.5px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(23px);
        -ms-transform: translateX(23px);
        transform: translateX(23px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <input type="hidden" id="IDEntry" name="IDEntry" value="<?php echo $_GET['docety']; ?>">
        
        <div class="row pt-3">
            <div class="col-sm">
                <table class="table table-sm table-borderless">
                    <thead style='font-size: 15px;'>
                        <tr class='text-center fw-bolder'>
                            <td class='pb-0 pt-0' colspan='2'><i class="far fa-hand-lizard"></i> หยิบสินค้า</td>
                        </tr>
                    </thead>
                    <tbody style='font-size: 13px;'>
                        <tr>
                            <td width='27%' class='pe-0 pb-0 fw-bold text-primary align-baseline'>เลขที่ใบสั่งขาย</td>
                            <td width='' class='ps-0 pb-0'><span id='DocNum'></span></td>
                        </tr>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary align-baseline'>ชื่อลูกค้า</td>
                            <td class='ps-0 pb-0'><span id='Customer'></span></td>
                        </tr>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary align-baseline'>หมายเหตุ</td>
                            <td class='ps-0 pb-0'><span id="Remark"></span></td>
                        </tr>
                        <tr>
                            <td class='pe-0 pb-0 fw-bold text-primary align-baseline'>ผู้จัดทำ</td>
                            <td class='ps-0 pb-0'>
                                <div class='d-flex'>
                                    <span style='width: 80%;' id="CoSale"></span>
                                    <span class='fw-bold' style='width: 20%;' id='qtyShow'></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm" style='padding-left: 20px; padding-right: 20px;'>
                <div class='d-flex align-items-center'>
                    <input type="hidden" id="SODocEntry" name="SODocEntry">
                    <input type="hidden" id="DocType" name="DocType">
                    <input type="hidden" id="CHEntry" name="CHEntry">
                    <input type="hidden" id="DocNumX" name="DocNumX">

                    <i class="fas fa-barcode" style='font-size: 25px;'></i>&nbsp;
                    <input class='form-control form-control-sm' type="text" id='TxtCodeBars' name='TxtCodeBars' placeholder='บาร์โค้ดหรือรหัสสินค้า' autocomplete="off">
                    &nbsp;
                    <input class='form-control form-control-sm w-25 text-right' type='number' id='TxtQty' name='TxtQty' placeholder='จำนวน' autocomplete="off">
                </div>
                <div class='d-flex align-items-center pt-2'>
                    <i class="fas fa-barcode" style='font-size: 25px;'></i>&nbsp;
                    <input class='form-control form-control-sm' type="text" id='RacKBar' name='RacKBar' placeholder='บาร์โค้ดชั้นวางสินค้า' autocomplete="off">
                    &nbsp;
                    <button class='btn btn-sm btn-primary ps-4 pe-4' name='addData' id='addData' onclick="SOSearchBar()">ส่ง</button>
                </div>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-sm">
                <table class='table table-sm table-borderless'>
                    <tbody id='TbodyList' style='font-size: 12px;'></tbody>
                </table>
            </div>
        </div>
        <div class="row ps-2 pe-2">
            <div class="col-sm">
                <div class='tableFix'>
                    <table class='table table-sm'>
                        <thead style='font-size: 13px;'>
                            <tr class='text-center'>
                                <th width='48%' class='bg-light ps-0 pe-0 pt-0'>รายการ</th>
                                <th width='' class='bg-light ps-0 pt-0' style='font-size: 11px;'>
                                    <div class='d-flex align-items-center justify-content-center'>
                                        <label class="switch">
                                            <input type="checkbox" id='CheckID' checked onclick="CheckID(0)">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class='ps-2 pe-1' id='NameSwitch'>ตามรายการ</span>
                                        <!-- <span id='qtyShow'></span> -->
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class='text-black' style='font-size: 13px;' id='Tbody'></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-sm">
                <div class='d-flex align-items-center justify-content-around'>
                    <button class='btn btn-sm btn-outline-dark' name="btnCutOff" id="btnCutOff" onclick="CallFunction('CutAmount',0)">ตัดยอด</button>
                    <button class='btn btn-sm btn-secondary' name='btnCancel' id='btnCancel' disabled>ยกเลิก</button>
                    <button class='btn btn-sm btn-info' name="btnUpdate" id="btnUpdate" onclick="CallFunction('updateSO',0)">ปรับคลัง</button>
                    <button class='btn btn-sm btn-success' name="btnSave" id="btnSave" onclick="CallFunction('SaveSO',0)">ยืนยัน</button>
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
                    <div class='d-flex justify-content-center' id='ModalHR'><hr class='w-50 mt-0'></div>
                    <p class='m-0 fw-bolder' style='font-size: 30px;' id='Confirm'></p> <input type="hidden" name='CHKdata' id='CHKdata'> <!-- รหัสยืนยัน -->
                    <div class='d-flex justify-content-center'>
                        <div id='DInput' class='position-relative'>
                            <input type="number" id="InputCHK" name="InputCHK" class="form-control text-center" placeholder="กรอกรหัสยืนยัน" onfocusout="" required>
                            <div class="invalid-tooltip" style='font-size: 9px;'>รหัสยืนยันไม่ถูกต้อง</div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success w-25 mt-4" onclick="CallSubmit()">ยืนยัน</button>
                    <input type="hidden" name='CallBtn' id='CallBtn'> <!-- ID ปุ่ม -->
                    <input type="hidden" name='CallRowID' id='CallRowID'> <!-- รหัสยืนยันที่กรอก -->
                </div>
            </div>
        </div>
    </div>

    <!-- กรณีมากกว่า1Item -->
    <div class="modal fade" id="ModalSeItem" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class='d-flex align-items-center justify-content-between'>
                        <div></div>
                        <h5 class="modal-title text-center"><i class="far fa-hand-lizard"></i> เลือกสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="my-4">
                        <table class='table table-sm'>
                            <tbody id='TbodySeItem' style='font-size: 12px;'></tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="SelectItem()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div class="modal fade" id="ModalAlert" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h1 class="modal-title text-center" id="HeaderModalAlert"></h1>
                    <p id="DetailModalAlert" class="my-3"></p>
                    <button type="button" class="btn btn-sm w-25 mt-4" id='ModalAlertBTN' data-bs-dismiss="modal" onclick="focusBar()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Remark -->
    <div class="modal fade" id="ModalAlertRemark" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h1 class="modal-title text-center" id="HeaderModalAlertRemark"></h1>
                    <p id="DetailModalAlertRemark" class="my-3 text-primary"></p>
                    <button type="button" class="btn btn-sm btn-secondary w-25 mt-4" data-bs-dismiss="modal">ตกลง</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CallLocation -->
    <div class="modal fade" id="ModalLocation" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h1 class="modal-title text-center"></h1>
                    <table class='table table-sm table-hover'>
                        <thead style='font-size: 13px'>
                            <tr class='tect-center'>
                                <th>WHS</th>
                                <th>Location</th>
                                <th>จำนวน</th>
                            </tr>
                        </thead>
                        <tbody id='tbodyLocation' style='font-size: 12px'></tbody>
                    </table>
                    <button type="button" class="btn btn-sm w-25 btn-secondary mt-4"  data-bs-dismiss="modal">ออก</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php require("../template/script.php"); ?>
    <script>
        $(document).ready(function(){
            CheckID();
            setTimeout(function(){
                $('#TxtCodeBars').focus();
            }, 1000);
        });

        function CheckID(alert) {
            var IDEntry =  $('#IDEntry').val();
            var chk = document.getElementById('CheckID').checked;
            // console.log(IDEntry);
            switch (chk) {
                case true: 
                    $("#NameSwitch").html("ตามรายการ");
                    var RunData = 1;
                    break;
                case false: 
                    $("#NameSwitch").html("ตามตำแหน่ง");
                    var RunData = 0;
                    break;
            }
            // console.log(chk+' | '+RunData+' | '+IDEntry);
            $(".overlay").show();
            $.ajax({
                url: "ajax/ajaxpicklist.php?a=CheckID",
                type: "POST",
                data: { DocEntry : IDEntry, Sort:RunData },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        // Alert
                        if(inval['Remark'] != "" && alert == undefined) {
                            $("#HeaderModalAlertRemark").html("หมายเหตุ <i class='fas fa-exclamation' style='font-size: ;'></i>");
                            $("#DetailModalAlertRemark").html(inval['Remark']);
                            $("#ModalAlertRemark").modal("show");
                        }
                        // ข้อมูล หยิบสินค้า
                        $("#DocNum").html(inval['DocNum']);
                        $("#Customer").html(inval['Customer']);
                        $("#Remark").html(inval['Remark']);
                        $("#CoSale").html(inval['CoSale']);

                        $("#qtyShow").html(inval['qtyShow']);

                        $("#SODocEntry").val(inval['SODocEntry']);
                        // console.log(inval['SODocEntry']);
                        $("#DocType").val(inval['DocType']);
                        $('#CHEntry').val(inval['CH']);
                        $('#DocNumX').val(inval['DocNumX']);

                        if (inval['StatusDoc'] == 'N'){
                            $('#TxtCodeBars').attr("disabled", "disabled"); // บาร์โค้ดหรือรหัสสินค้า
                            $('#TxtQty').attr("disabled", "disabled"); // จำนวน
                            $('#addData').attr("disabled", "disabled"); // ปุ่มส่ง
                            $('#btnCutOff').attr("disabled", "disabled"); // ตัดยอด
                            $('#btnSave').attr("disabled", "disabled"); // ยืนยัน
                            $('#RacKBar').attr("disabled", "disabled"); // บาร์โค้ดชั้นวางสินค้า
                        }else{
                            $('#RacKBar').val("");
                            $('#TxtQty').val("");
                            $('#TxtCodeBars').val("");
                            setTimeout(function(){
                                $('#TxtCodeBars').focus();
                            }, 1000);
                        }

                        // รายการ
                        $("#Tbody").html(inval['Tbody']);
                        // CheckID();
                    })
                    $(".overlay").hide();
                }
            })
        }

        function CallLocation(x) {
            $.ajax({
                url: "ajax/ajaxpicklist.php?a=CallLocation",
                type: "POST",
                data: { ItemCode : x, },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#tbodyLocation").html(inval['tbl']);
                        $("#ModalLocation").modal("show");
                    })
                }
            })
        }

        $("#TxtCodeBars, #TxtQty, #RacKBar").keypress(function (e) {
            if (e.which == 13) {
                SOSearchBar();
            }
        });
        
        function SOSearchBar(BoxItem) {
            if(BoxItem == undefined) {
                var TxtCodeBars = $('#TxtCodeBars').val(); // บาร์โค้ดหรือรหัสสินค้า
            }else{
                var TxtCodeBars = BoxItem; // รหัสสินค้า
            }
            // console.log(BoxItem+" | "+TxtCodeBars+" | "+$('#SODocEntry').val()+" | "+$('#DocType').val());
            var TxtQty = $('#TxtQty').val(); // จำนวน
            var RacKBar = $('#RacKBar').val(); // บาร์โค้ดชั้นวางสินค้า
            if (TxtCodeBars == "" || TxtQty == "" || RacKBar == ""){
                if (TxtCodeBars == ""){
                    $('#TxtCodeBars').focus();
                }else{
                    if (TxtQty == ""){
                        $("#TbodyList").html("");
                        $('#TxtQty').focus();
                        $.ajax({
                            url: "ajax/ajaxpicklist.php?a=TxtQty",
                            type: "POST",
                            data: { ItemCode : TxtCodeBars, SODocEntry : $('#SODocEntry').val(), DocType : $('#DocType').val(), },
                            success: function(result) {
                                var obj = jQuery.parseJSON(result);
                                $.each(obj, function(key, inval) {
                                    if(inval['CkRow'] == 1) {
                                        if(inval['Alert'] == 'N') {
                                            var Tbody = "<tr class='bg-light-danger'>"+
                                                            "<td class='fw-bold' style='color: #032AB0;'>"+inval['ItemName']+"</td>"+
                                                            "<td width='25%' class='fw-bold text-center' style='color: #208504;'>สั่งซื้อ "+inval['Qty']+"</td>"+
                                                            "<td width='25%' class='fw-bold text-center' style='color: #032AB0;'>เบิกแล้ว "+inval['OpenQty']+"</td>"+
                                                        "</tr>";
                                            $("#TbodyList").html(Tbody);
                                        }else{
                                            var Tbody = "<tr class='bg-light-danger'>"+
                                                            "<td class='fw-bold text-primary text-center'><i class='fas fa-exclamation'></i> ไม่มีสินค้านี้ในรายการ</td>"+
                                                        "</tr>";
                                            $("#TbodyList").html(Tbody);
                                        }
                                        
                                    }else{
                                        if(inval['CkRow'] == 0){
                                            $("#HeaderModalAlert").html("<i class='fas fa-exclamation-triangle' style='font-size: 70px;'></i>");
                                            $("#DetailModalAlert").html("แจ้ง IT ให้เพิ่มรหัสสินค้า<br>โค้ดบาร์ "+TxtCodeBars);
                                            $("#ModalAlertBTN").html("ยืนยัน");
                                            $("#ModalAlertBTN").addClass("btn-primary");
                                            $("#ModalAlert").modal("show");
                                            $('#TxtCodeBars').val("");
                                        }else{
                                            if(inval['Alert'] == 'N') {
                                                var Tbody = "";
                                                var dis = "";
                                                var redline = "";
                                                        for(var i = 1; i <= inval['CkRow']; i++) {
                                                            if (inval['ItemCode'+i] == inval['ItemMain']){
                                                                dis = "";
                                                                redline = "";
                                                            }else{
                                                                dis = "disabled";
                                                                redline = "rgba(96, 112, 128, 0.61)";
                                                            }
                                                            Tbody +="<tr>"+
                                                                        "<td width='38%' class='fw-bold text-center' style='color: "+redline+";'><input class='form-check-input  ' type='radio' name='BoxItem' id='BoxItem' "+dis+" value='"+inval['ItemCode'+i]+"'>&nbsp&nbsp"+inval['ItemCode'+i]+"</td>"+
                                                                        "<td class='fw-bold' style='color: "+redline+";'>"+inval['ItemName'+i]+"</td>"+
                                                                    "</tr>";
                                                        }
                                                $("#TbodySeItem").html(Tbody);
                                                $("#ModalSeItem").modal("show");
                                            }else{
                                                var Tbody = "<tr>"+
                                                                "<td class='fw-bold text-primary text-center'><i class='fas fa-exclamation'></i> ไม่มีสินค้านี้ในรายการ</td>"+
                                                            "</tr>";
                                                $("#TbodyList").html(Tbody);
                                            }
                                        }
                                    }
                                })
                            }
                        })
                    }else{
                        $('#RacKBar').focus(); 
                    }
                }
            }else{
                $('#addData').focus(); 
                $("#TbodyList").html("");
                var chk = document.getElementById('CheckID').checked;
                switch (chk) {
                    case true:
                        var RunData = 1;
                        break;
                    case false:
                        var RunData = 0;
                        break;
                }
                $.ajax({
                    url: "ajax/ajaxpicklist.php?a=AddItem",
                    type: "POST",
                    data: { TxtCodeBars : $('#TxtCodeBars').val(), 
                            SODocEntry : $('#SODocEntry').val(), 
                            DocType : $('#DocType').val(), 
                            TxtQty : $('#TxtQty').val(), 
                            RacKBar : $('#RacKBar').val(), 
                            CHEntry : $('#CHEntry').val(),
                            DocNumX : $("#DocNumX").val(), },
                    success: function(result) {
                        var obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            if(inval['CHKRow'] != 0) {
                                if (inval['Status'] == 2){
                                    if (inval['txtMAX'] == 1){
                                        $('#RacKBar').val("");
                                    }
                                    $('#NewQty_'+inval['VisOrder']).html(inval['NewQty']);
                                }else{
                                    if (inval['Status'] == 2){
                                        $('#TxtCodeBars').focus();
                                    }else{
                                        var txtST = inval['txtST'];
                                        switch (inval['Status']) {
                                            case 8:
                                                $("#HeaderModalAlert").html("แจ้งเตือน");
                                                $("#DetailModalAlert").html(txtST.substring(0, 21)+"<br>"+txtST.substring(21));
                                                $("#ModalAlertBTN").html("ออก");
                                                $("#ModalAlertBTN").addClass("btn-secondary");
                                                break;
                                            case 9:
                                                $("#HeaderModalAlert").html("แจ้งเตือน");
                                                $("#DetailModalAlert").html(txtST.substring(0, 11)+"<br>"+txtST.substring(11, 27)+"<br>"+txtST.substring(27));
                                                $("#ModalAlertBTN").html("ออก");
                                                $("#ModalAlertBTN").addClass("btn-secondary");
                                                break;
                                            default:
                                                $("#HeaderModalAlert").html("แจ้งเตือน");
                                                $("#DetailModalAlert").html(txtST);
                                                $("#ModalAlertBTN").html("ออก");
                                                $("#ModalAlertBTN").addClass("btn-secondary");
                                                break;
                                        }
                                        $("#ModalAlert").modal("show");
                                    }
                                }
                                // var tmpTR = $('#tmpVisOrder').val();
                                // $('#tmpVisOrder').val(inval['VisOrder']);

                                $('#TxtQty').val("");
                                $('#TxtCodeBars').val("");
                                $('#RacKBar').val("");
                                $('#TxtCodeBars').focus();
                                $("#TbodySeItem").html("");
                                $("#qtyShow").html("("+inval['qtyShow']+")");

                                // console.log("NewQty : "+inval['NewQty']); console.log("newOpen : "+inval['newOpen']); console.log("Status : "+inval['Status']);
                                // console.log("txtST : "+inval['txtST']);   console.log("txtMAX : "+inval['txtMAX']);   console.log("VisOrder : "+inval['VisOrder']);
                            }else{
                                $("#HeaderModalAlert").html("<i class='fas fa-exclamation-triangle' style='font-size: 70px;'></i>");
                                $("#DetailModalAlert").html("ไม่มีสินค้านี้ในรายการ");
                                $("#ModalAlertBTN").html("ออก");
                                $("#ModalAlertBTN").addClass("btn-primary");
                                $("#ModalAlert").modal("show");
                            }
                        })
                    }
                })
            }
        }

        function focusBar() {
            setTimeout(function(){
                $('#TxtCodeBars').focus();
            }, 1000);
        }

        function SelectItem() {
            var BoxItem = $("#BoxItem:checked").val();
            $('#TxtCodeBars').val(BoxItem);
            // console.log(BoxItem+" | "+TxtCodeBars);
            SOSearchBar(BoxItem);
        }

        function AddRemark(i,DocEntry,RowID,DocType) {
            var Comments = $('#Remark'+i+'_'+DocEntry+"_"+RowID).val();
            var WaitData = $('#WaitOP'+i+'_'+DocEntry+"_"+RowID).val();
            // console.log('Comments : '+Comments+" | WaitData :"+WaitData);
            // console.log('DocEntry : '+DocEntry+" | RowID :"+RowID);
            $.ajax({
                url: "ajax/ajaxpicklist.php?a=AddRemarkAndWaitOP",
                type: "POST",
                data: { DocEntry : DocEntry, RowID : RowID, Remark : Comments, WaitOP : WaitData, DocType : DocType, }
            })
        }

        function CallFunction(btn,RowID) {
            $("#CallBtn").val(btn);
            $("#CallRowID").val(RowID);

            var Pconfirm = Math.floor(Math.random().toFixed(2)*100);
            if (Pconfirm < 10){
                Pconfirm = "0"+Pconfirm;
            }
            $("#CHKdata").val(Pconfirm);

            $('#InputCHK').val("");
            switch (btn) {
                case "DelRow":
                    $("#ModalHeader").html("<i class='fas fa-exclamation-circle fa-fw fa-lg'></i> ยกเลิกการเบิก");
                    $("#ModalDetail").html("<span class='text-danger fw-bold'>** กรุณานำสินค้าไปไว้ที่ชั้นวางเดิม **</span>");
                    $("#ModalHR hr").show();
                    break;
                case "CutAmount":
                    $("#ModalHeader").html("ยืนยันการส่งตัดรายการ");
                    $("#ModalDetail").html("");
                    $("#ModalHR hr").show();
                    break;
                case "updateSO":
                    $("#ModalHeader").html("ยืนยันการปรับปรุง");
                    $("#ModalDetail").html("รายการปรับปรุงจะทำได้เฉพาะสินค้าที่ยังไม่หยิบเท่านั้น");
                    $("#ModalHR hr").show();
                    break;
                case "SaveSO":
                    $("#ModalHeader").html("ยืนยันการส่ง SO เปิดบิล");
                    $("#ModalDetail").html("");
                    $("#ModalHR hr").show();
                    break;
            }
            $("#Confirm").html(Pconfirm);
            $("#InputCHK").removeClass("is-invalid");
            $("#ModalCheck").modal("show");
            // $('#InputCHK').focus();
            // console.log(btn+" | "+RowID+" | "+Item);
        }

        function CallSubmit() {
            if($("#InputCHK").val() == $("#CHKdata").val()) {
                switch ($("#CallBtn").val()) { //$("#CallBtn").val() ค่าปุ่มที่ทำการ active
                    case "DelRow":
                        DelRow();
                        break;
                    case 'CutAmount' :
                        CutAmount();
                        break;
                    case 'updateSO' :
                        updateSO();
                        break;
                    case 'SaveSO' :
                        SaveSO();
                        break;
                }
            }else{
                $("#InputCHK").addClass("is-invalid");
            }
        }
        $("#InputCHK").on("keyup", function(){
            $("#InputCHK").removeClass("is-invalid");
        });

        function ShowOnHand(element,ItemCode, WhsCode, Qty, Bom) {
            element.innerHTML = "<i class='fas fa-spinner fa-pulse fa-fw fa-1x'></i>";
            let ShowOnHand = "";
            $.ajax({
                url: "ajax/ajaxpicklist.php?a=ShowOnHand",
                type: "POST",
                data: {
                    ItemCode: ItemCode,
                    WhsCode : WhsCode,
                    Qty     : Qty,
                    Bom     : Bom
                },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        ShowOnHand = inval['output'];
                    });
                    element.innerHTML = ShowOnHand;
                }
            });
        }

        function DelRow() {
            $.ajax({
                url: "ajax/ajaxpicklist.php?a=DelRow",
                type: "POST",
                data: {  DocEntry : $('#IDEntry').val(), RowID : $("#CallRowID").val(),},
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#InputCHK").val("");
                        $("#ModalCheck").modal("hide");

                        $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 70px;'></i>");
                        $("#DetailModalAlert").html(inval['text']);
                        $("#ModalAlertBTN").html("ยืนยัน");
                        $("#ModalAlertBTN").addClass("btn-success");
                        $("#ModalAlert").modal("show");
                        CheckID(0);
                    })
                }
            })
        }

        function CutAmount() {
            $.ajax({
                url: "ajax/ajaxpicklist.php?a=CutAmount",
                type: "POST",
                data: { SODocEntry : $('#SODocEntry').val(), DocType : $('#DocType').val() },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $('#TxtCodeBars').attr("disabled", "disabled"); // บาร์โค้ดหรือรหัสสินค้า
                        $('#TxtQty').attr("disabled", "disabled"); // จำนวน
                        $('#addData').attr("disabled", "disabled"); // ปุ่มส่ง
                        $('#btnCutOff').attr("disabled", "disabled"); // ตัดยอด
                        $('#btnSave').attr("disabled", "disabled"); // ยืนยัน
                        $('#RacKBar').attr("disabled", "disabled"); // บาร์โค้ดชั้นวางสินค้า
                        $("#ModalCheck").modal("hide");
                        CheckID(0);
                    })
                }
            })
        }

        function updateSO() {
            $.ajax({
                url: "ajax/ajaxpicklist.php?a=updateSO",
                type: "POST",
                data: { SODocEntry : $('#SODocEntry').val(), DocType : $('#DocType').val(), DocNumX : $('#DocNumX').val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#ModalCheck").modal("hide");
                        $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 70px;'></i>");
                        $("#DetailModalAlert").html(inval['Text']);
                        $("#ModalAlertBTN").html("ยืนยัน");
                        $("#ModalAlertBTN").addClass("btn-success");
                        $("#ModalAlert").modal("show");
                        CheckID(0);
                    })
                }
            })
        }

        function SaveSO() {
            $.ajax({
                url: "ajax/ajaxpicklist.php?a=SaveSO",
                type: "POST",
                data: { SODocEntry : $('#SODocEntry').val(), DocType: $("#DocType").val(), DocNumX : $('#DocNumX').val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#ModalCheck").modal("hide");
                        $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 70px;'></i>");
                        $("#DetailModalAlert").html("ส่งข้อมูลเปิดบิลใบสั่งขาย "+inval['DocNumX']+" เรียบร้อยแล้ว");
                        $("#ModalAlertBTN").html("ยืนยัน");
                        $("#ModalAlertBTN").addClass("btn-success");
                        $("#ModalAlert").modal("show");
                        CheckID(0);
                    })
                }
            })
        }

        <?php if($_SESSION['DeptCode'] != 'DP002') { ?>
        $(function() {
            $(this).bind("contextmenu",function(e) {
                e.preventDefault();
            })
        })

        document.addEventListener('copy', function(e){
            e.clipboardData.setData("text/plain","");
            e.preventDefault();
        });
        <?php } ?>
    </script>
<?php require("../template/footer.php"); ?>