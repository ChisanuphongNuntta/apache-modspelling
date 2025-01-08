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

    .checkbox input[type="checkbox"] {
        display: none;
    }

    .checkbox label {
        padding-left: 0;
    }

    .checkbox label:before {
        content: "";
        width: 30px;
        height: 27px;
        display: inline-block;
        vertical-align: bottom;
        padding-top: 3px;
        line-height: 20px;
        text-align: center;
        border: 1px solid #ccc;
        font-family: "FontAwesome";
        border-radius: 4px;
    }

    .checkbox input[type="checkbox"]+label::before {
        content: "\f218";
        color: #999999;
        transform: scaleX(-1);
    }

    .checkbox input[type="checkbox"]:checked+label::before {
        content: "\f218";
        color: #FFFFFF;
        background: #dc3545;
        transform: scaleX(-1);
    }
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-truck-loading"></i> โหลดสินค้าขึ้น</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
            </div>
        </div>
        <?php $DataHead = MySQLSelect("SELECT LogiNum, DriverName, LcCar FROM logi_head WHERE ID = ".$_GET['IDLogi'].""); ?>
        <input type="hidden" name='DocID' id='DocID' value='<?php echo $DataHead['LogiNum']; ?>'>
        <div class="row">
            <div class="col-sm">
                <table class="table table-borderless">
                    <tbody style='font-size: 13px;'>
                        <tr>
                            <td width='30%' class='pe-0 pb-0 fw-bold text-primary'>เลขที่ใบออกรถ</td>
                            <td width='60%' class='ps-0 pb-0'>
                                <span><?php echo $DataHead['LogiNum'];?></span>
                            </td>
                            <td width='10%' class='ps-0 pb-0 text-center'>
                                <div class="checkbox">
                                <input type="hidden" id='Switch-btn-ecom' value='false'>
                                <input type="checkbox" id="btn-ecom" name="btn-ecom" />
                                <label for="btn-ecom"></label>
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <td width='30%' class='pe-0 pb-0 fw-bold text-primary'>ชื่อพนักงานขับรถ</td>
                            <td width='60%' class='ps-0 pb-0'>
                                <input class='form-control form-control-sm' type="text" id='DriverName' name='DriverName' value='<?php echo $DataHead['DriverName'];?>' onfocusout="SaveEditData('Name')" list="TxtDriverName" readonly>
                                <datalist id="TxtDriverName">
                                    <?php
                                    $sql_DN = "SELECT loginame, logilastname, logiplate FROM logistic WHERE status = 1"; 
                                    $sqlQRY_DN = MySQLSelectX($sql_DN);
                                    $p = 0;
                                    while($result_DN = mysqli_fetch_array($sqlQRY_DN)) {
                                        ++$p;
                                        echo "<option>".$result_DN['loginame']." ".$result_DN['logilastname']."</option>";
                                        $plate[$p] = $result_DN['logiplate'];
                                    }
                                    ?>
                                </datalist>
                            </td>
                            <td width='10%' class='ps-0 pb-0 text-center'>
                                <a href='javascript:void(0);' id='EditName' onclick="EditData('Name')"><i class='fas fa-edit fs-6'></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td width='30%' class='pe-0 pb-0 fw-bold text-primary'>ทะเบียนรถ</td>
                            <td width='60%' class='ps-0 pb-0'>
                                <input class='form-control form-control-sm' type="text" id='LcCar' name='LcCar' value='<?php echo $DataHead['LcCar'];?>' onfocusout="SaveEditData('LcCar')" list="TxtLcCar" readonly>
                                <datalist id="TxtLcCar">
                                    <?php
                                    for($i = 1; $i <= count($plate); $i++) {
                                        echo "<option>".$plate[$i]."</option>"; 
                                    }
                                    ?>
                                </datalist>
                            </td>
                            <td width='10%' class='ps-0 pb-0 text-center'>
                                <a href='javascript:void(0);' id='EditLcCar' onclick="EditData('LcCar')"><i class='fas fa-edit fs-6'></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td width='30%' class='pe-0 pb-0 fw-bold text-primary'>สถานะโหลด</td>
                            <td width='60%' class='ps-0 pb-0'>
                                <div class='d-flex align-items-center'>
                                    <input type="hidden" id='SwitchID' value='Load'>
                                    <label class="switch">
                                        <input type="checkbox" checked onclick="chkLoad()">
                                        <span class="slider round"></span>
                                    </label>
                                    <span class='ps-2 pe-1' id='NameSwitch'>โหลดสินค้า</span>
                                </div>
                            </td>
                            <td width='10%' class='ps-0 pb-0 text-center'>
                                <span id='totalLoad'></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row ps-2 pe-2">
            <div class="col-sm d-flex align-items-center">
                <i class="fas fa-barcode fs-3"></i>&nbsp;<input class='form-control form-control-sm' type='text' id='TxtCodeBars' name='TxtCodeBars' placeholder='รหัสบาร์โค้ดลัง'>&nbsp;&nbsp;
                <button class='btn btn-sm btn-primary' id="BtnSend" name="BtnSend" onclick="CallSubmit()"><i class='fas fa-check fa-fw fa-1x'></i></button>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-sm">
                <div class='tableFix'>
                    <table class="table table-borderless">
                        <thead style='font-size: 14px;'>
                            <tr class='text-center'>
                                <th class='pb-0 fw-bold bg-light'>รายการ</th>
                                <th class='pb-0 fw-bold bg-light'>จำนวนลัง</th>
                            </tr>
                        </thead>
                        <tbody style='font-size: 13px;' id="BoxList"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row pt-2 ps-2 pe-2">
            <div class="col-sm d-flex align-items-center justify-content-end">
                <button class='btn btn-sm btn-success' onclick='saveLoad()'>ยืนยัน</button>
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

    <div class="modal fade" id="ModalDelCF" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="modal-title text-center" id="ModalHeader"><i class="fas fa-minus-circle"></i> ยกเลิกการโหลดสินค้า</h5>
                    <p id="ModalDetailDelCF" class="mt-4 mb-3"></p>
                    <button type="button" class="btn btn-sm btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="DelSubmit()">ยืนยัน</button>
                    &nbsp;&nbsp;
                    <button type="button" class="btn btn-sm btn-secondary w-25 mt-4" data-bs-dismiss="modal">ออก</button>

                    <input type="hidden" name="BillType" id="BillType">
                    <input type="hidden" name="BillEntry" id="BillEntry">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalsaveLoad" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="modal-title text-center" id=""><i class="far fa-check-circle" style='font-size: 60px;'></i></h5>
                    <p id="" class="mt-4 mb-3">ยืนยันการโหลดสินค้า</p>
                    <button type="button" class="btn btn-sm btn-success w-25 mt-4" data-bs-dismiss="modal" onclick="saveLoadSubmit()">ยืนยัน</button>
                    &nbsp;&nbsp;
                    <button type="button" class="btn btn-sm btn-secondary w-25 mt-4" data-bs-dismiss="modal">ออก</button>

                    <input type="hidden" name="BillType" id="BillType">
                    <input type="hidden" name="BillEntry" id="BillEntry">
                </div>
            </div>
        </div>
    </div>

    <?php require("../template/script.php"); ?>
    <script>
        $(document).ready(function(){
            CallData();
            $("#TxtCodeBars").focus();
        });

        function CallData() {
            $(".overlay").show();
            $.ajax({
                url: "ajax/ajaxloadlist.php?a=CallData",
                type: "POST",
                data: { DocID : $("#DocID").val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#BoxList").html(inval['output']);
                        $('#totalLoad').html(inval['Load']);
                    })
                    $(".overlay").hide();
                } 
            })
        }

        $("#btn-ecom").on("click", function() {
            switch($("#Switch-btn-ecom").val()) {
                case 'false': $("#Switch-btn-ecom").val("true"); break;
                case 'true': $("#Switch-btn-ecom").val("false"); break;
            }
            // console.log($("#Switch-btn-ecom").val());
        })

        function chkLoad() {
            switch ($("#SwitchID").val()) {
                case "UnLoad": 
                    $("#SwitchID").val("Load"); 
                    $("#NameSwitch").removeClass("text-muted");
                    $("#NameSwitch").html("โหลดสินค้า");
                    break;
                case "Load": 
                    $("#SwitchID").val("UnLoad"); 
                    $("#NameSwitch").addClass("text-muted");
                    $("#NameSwitch").html("ยกเลิกโหลดสินค้า");
                    break;
            }
            // console.log($("#SwitchID").val());
        }

        function EditData(Edit) {
            switch(Edit) {
                case 'Name':
                    $('#DriverName').removeAttr("readonly");
                    $("#EditName").html("<i class='fas fa-save fs-6'></i>");
                    $('#DriverName').focus();
                    break;
                case 'LcCar':
                    $('#LcCar').removeAttr("readonly");
                    $("#EditLcCar").html("<i class='fas fa-save fs-6'></i>");
                    $('#LcCar').focus();
                    break;
            }
        }

        function SaveEditData(Edit) {
            $.ajax({
                url: "ajax/ajaxloadlist.php?a=EditData",
                type: "POST",
                data: { fun : Edit, DriverName : $("#DriverName").val(), LcCar : $("#LcCar").val(), LogiNum : $('#DocID').val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        switch(Edit) {
                            case 'Name':
                                $('#DriverName').prop('readonly', true);
                                $("#EditName").html("<i class='fas fa-edit fs-6'></i>");
                                break;
                            case 'LcCar':
                                $('#LcCar').prop('readonly', true);
                                $("#EditLcCar").html("<i class='fas fa-edit fs-6'></i>");
                                break;
                        }
                        // $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 60px;'></i>");
                        // $("#DetailModalAlert").html(inval['text']);
                        // $("#ModalAlertBTN").addClass("btn-secondary");
                        // $("#ModalAlert").modal("show");
                    })
                }
            })
        }

        $("#TxtCodeBars").keypress(function (e) {
            if (e.which == 13) {
                CallSubmit();
            }
        });

        function CallSubmit() {
            if($("#TxtCodeBars").val() != "") {
                $.ajax({
                    url: "ajax/ajaxloadlist.php?a=CallSubmit",
                    type: "POST",
                    data: { ChkEcom : $("#Switch-btn-ecom").val(),
                            ChkLoad : $("#SwitchID").val(),
                            TxtCodeBars : $("#TxtCodeBars").val(),
                            DocID : $("#DocID").val(), },
                    success: function(result) {
                        var obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            switch (inval['ar']){
                            case 0 :
                                CallData();
                                break;
                            case 0.5 :
                                /*
                                $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 60px;'></i>");
                                $("#DetailModalAlert").html(inval['Arert']);
                                $("#ModalAlertBTN").addClass("btn-secondary");
                                $("#ModalAlert").modal("show");
                                */
                                CallData();
                                break;
                            case 1 :
                                $("#HeaderModalAlert").html("<i class='fas fa-exclamation' style='font-size: 60px;'></i>");
                                $("#DetailModalAlert").html(inval['Arert']);
                                $("#ModalAlertBTN").addClass("btn-secondary");
                                $("#ModalAlert").modal("show");
                                break;
                            case 2 :
                                $("#HeaderModalAlert").html("<i class='fas fa-exclamation' style='font-size: 60px;'></i>");
                                $("#DetailModalAlert").html(inval['Arert']);
                                $("#ModalAlertBTN").addClass("btn-secondary");
                                $("#ModalAlert").modal("show");
                                CallData();
                                break;
                            case 2.5 :
                                $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 60px;'></i>");
                                $("#DetailModalAlert").html(inval['Arert']);
                                $("#ModalAlertBTN").addClass("btn-secondary");
                                $("#ModalAlert").modal("show");
                                CallData();
                                break;
                            }
                            $('#TxtCodeBars').val("");
                            $('#TxtCodeBars').focus();
                        })
                    }
                })
            }else{
                $("#HeaderModalAlert").html("<i class='fas fa-exclamation' style='font-size: 60px;'></i>");
                $("#DetailModalAlert").html("กรุณาใส่รหัสบาร์โค้ด");
                $("#ModalAlertBTN").addClass("btn-secondary");
                $("#ModalAlert").modal("show");
                setTimeout(function(){
                    $("#ModalAlert").modal("hide");
                    $('#TxtCodeBars').focus();
                }, 1500);
            }
        }

        function DelIV(BillType,BillEntry,BillNo) {
            // console.log($("#DocID").val());
            $("#BillType").val(BillType);
            $("#BillEntry").val(BillEntry);
            $("#ModalDetailDelCF").html(BillNo);
            $("#ModalDelCF").modal("show");
        }

        function DelSubmit() {
            $.ajax({
                url: "ajax/ajaxloadlist.php?a=DelIV",
                type: "POST",
                data: { DocID : $("#DocID").val(), BillType : $("#BillType").val(), BillEntry : $("#BillEntry").val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 60px;'></i>");
                        $("#DetailModalAlert").html(inval['Arert']);
                        $("#ModalAlertBTN").addClass("btn-secondary");
                        $("#ModalAlert").modal("show");
                        CallData();
                    })
                }
            })
        }

        function saveLoad() {
            $("#ModalsaveLoad").modal("show");
        }

        function saveLoadSubmit() {
            if($("#DriverName").val().length > 0 && $("#LcCar").val().length > 0) {
                $.ajax({
                    url: "ajax/ajaxloadlist.php?a=saveLoad",
                    type: "POST",
                    data: { DocID : $('#DocID').val(), },
                    success: function(result) {
                        var obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            $("#HeaderModalAlert").html("<i class='fas fa-check-circle text-success' style='font-size: 60px;'></i>");
                            $("#DetailModalAlert").html(inval['Arert']);
                            $("#ModalAlertBTN").addClass("btn-secondary");
                            $("#ModalAlert").modal("show");
                            window.location.replace("loading.php");
                        })
                    }
                })
            }else{
                $("#HeaderModalAlert").html("<i class='fas fa-exclamation' style='font-size: 60px;'></i>");
                $("#DetailModalAlert").html("กรุณาใส่ชื่อพนักงานขับรถ และทะเบียนรถให้ครบถ้วน");
                $("#ModalAlertBTN").addClass("btn-secondary");
                $("#ModalAlert").modal("show");
            }
        }
    </script> 
<?php require("../template/footer.php"); ?>