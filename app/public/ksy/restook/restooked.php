<style type="text/css">
    @media only screen and (max-width:425px) {
        .tableFixRestook {
            overflow-y: auto;
            height: 350px;
        }
        .tableFixRestook th {
            position: sticky;
            top: 0;
        }
    }
    @media (min-width:426px) and (max-width:850px) {
        .tableFixRestook {
            overflow-y: auto;
            height: 500px;
        }
        .tableFixRestook th {
            position: sticky;
            top: 0;
        }
    }
    @media (min-width:851px) {
        .tableFixRestook {
            overflow-y: auto;
            height: 600px;
        }
        .tableFixRestook th {
            position: sticky;
            top: 0;
        }
    }
</style>
<?php require("../template/header.php"); ?>
    <input type="hidden" name='ItemCode' id='ItemCode' value='<?php echo $_GET['itemcode'] ?>'>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3 pb-2">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-file-alt"></i> รายการหยิบเติมสินค้า</h6>
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <i class='fas fa-search' style='font-size: 20px'></i>&nbsp;&nbsp;
                    <input class='form-control form-control-sm' type="text" id='FilterBox' name='FilterBox' placeholder="บาร์โค้ดสินค้า หรือบาร์โค้ดชั้นวาง" list="TxtComplete">
                </div>
            </div>
        </div>
        <div class="row ps-2 pe-2">
            <div class="col-sm">
                <table class='table table-borderless'>
                    <tbody style='font-size: 13px;' id='Tbody1'></tbody>
                </table>

                <div class='tableFixRestook'>
                    <table class='table table-sm table-hover'>
                        <thead style='font-size: 15px;' id='TbodyH2'></thead>
                        <tbody style='font-size: 12px;' id='Tbody2'></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalTfer" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title"><i class='fas fa-sync'></i> โอนย้ายสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tbody style='font-size: 13px;' id='Tbody3'></tbody>
                    </table>
                </div>
                <div class="modal-footer pt-1 pb-1" id='ModalFter'></div>
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

    <?php require("../template/script.php"); ?>
    <script>
        $(document).ready(function(){
            CallData2();
        });

        function CallData2() {
            // console.log($("#ItemCode").val());
            $.ajax({
                url: "ajax/ajaxrestooked.php?a=CallData2",
                type: "POST",
                data: { ItemCode : $("#ItemCode").val(), },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) { 
                        $("#Tbody1").html(inval['output']);
                        $("#TbodyH2").html(inval['outputH2']);
                        $("#Tbody2").html(inval['output2']);
                    })
                }
            })
        }

        function NewRow(Item){
            // console.log($("#ItemCode").val());
            $.ajax({  
                url: "ajax/ajaxrestooked.php?a=NewRow",
                type: "POST",
                data: { ItemCode : Item,},  
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) { 
                        $("#ItemCode").val(Item);
                        // console.log($("#ItemCode").val());
                        $("#ModalFter").html(inval['M_Fter']);
                        $("#Tbody3").html(inval['tbody']);
                        setTimeout(function(){
                            $("#NewRack").focus();
                        }, 1000);
                        $("#ModalTfer").modal("show");
                    })
                    $("#NewRack, #QtrMove").keypress(function (e) {
                        if (e.which == 13) {
                            AddRow();
                        }
                    });
                }  
            });  
        }

        function AddRow() {
            if($("#NewRack").val() == "") {
                setTimeout(function(){
                    $("#NewRack").focus();
                }, 500);
            }else{
                if($("#QtrMove").val() == "") {
                    setTimeout(function(){
                        $("#QtrMove").focus();
                    }, 500);
                }else{
                    var chk = document.getElementById("DftRack").checked 
                    if (chk){
                        Vchk = 1;
                    }else{
                        Vchk = 0
                    }
                    $.ajax({
                        url: "ajax/ajaxrestooked.php?a=MoveItem",
                        type: "POST",
                        data: { ItemCode : $('#ItemCode').val(),
                                OldRack  : $("#OldRack").val(),
                                NewRack  : $('#NewRack').val(),
                                QtyMove  : $('#QtrMove').val(),
                                WhsCode  : $('#WhsCode').val(),
                                DftSheft : Vchk },
                        success: function(result) {
                            var obj = jQuery.parseJSON(result);
                            $.each(obj, function(key, inval) {
                                // console.log($("#ItemCode").val());
                                $("#ModalTfer").modal("hide");
                                CallData2()

                                $("#HeaderModalAlert").html(inval['HeadAlert']);
                                $("#DetailModalAlert").html(inval['alertBox']);
                                $("#ModalAlertBTN").addClass("btn-secondary");
                                $("#ModalAlert").modal("show");
                            })
                        }
                    })
                }
            }
        }

        function MoveSKU(Item,x) {
            // console.log(Item);
            $.ajax({
                url: "ajax/ajaxrestooked.php?a=MoveSKU",
                type: "POST",
                data: { ItemCode : Item, LocRack : x, },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $('#ItemCode').val(Item);
                        // console.log($("#ItemCode").val());
                        $("#ModalFter").html(inval['M_Fter']);
                        $("#Tbody3").html(inval['tbody']);
                        setTimeout(function(){
                            $("#NewRack").focus();
                        }, 1000);
                        $("#ModalTfer").modal("show");
                    })
                    $("#NewRack, #QtrMove").keypress(function (e) {
                        if (e.which == 13) {
                            MoveItem();
                        }
                    });
                }
            })
        }

        function MoveItem() {
            // console.log($('#ItemCode').val());
            if($("#NewRack").val() == "") {
                setTimeout(function(){
                    $("#NewRack").focus();
                }, 500);
            }else{
                if($("#QtrMove").val() == "") {
                    setTimeout(function(){
                        $("#QtrMove").focus();
                    }, 500);
                }else{
                    var chk = document.getElementById("DftRack").checked 
                    if (chk){
                        Vchk = 1;
                    }else{
                        Vchk = 0
                    }
                    $.ajax({
                        url: "ajax/ajaxrestooked.php?a=MoveItem",
                        type: "POST",
                        data: { ItemCode : $('#ItemCode').val(),
                                OldRack  : $("#OldRack").val(),
                                NewRack  : $('#NewRack').val(),
                                QtyMove  : $('#QtrMove').val(),
                                WhsCode  : $('#WhsCode').val(),
                                DftSheft : Vchk, },
                        success: function(result) {
                            var obj = jQuery.parseJSON(result);
                            $.each(obj, function(key, inval) {
                                $("#ModalTfer").modal("hide");
                                CallData2()

                                $("#HeaderModalAlert").html(inval['HeadAlert']);
                                $("#DetailModalAlert").html(inval['alertBox']);
                                $("#ModalAlertBTN").addClass("btn-secondary");
                                $("#ModalAlert").modal("show");
                            })
                        }
                    })
                }
            }
        }

        function LockItem(x){
            $.ajax({
                url: "ajax/ajaxrestooked.php?a=LockItem",
                type: "POST",
                data: { Rack : x, },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#HeaderModalAlert").html(inval['HeadAlert']);
                        $("#DetailModalAlert").html(inval['alertBox']);
                        $("#ModalAlertBTN").addClass("btn-secondary");
                        $("#ModalAlert").modal("show");
                    })
                } 
            })
        }

        $("#FilterBox").keypress(function(e) {
            if (e.which == 13) {
                if($("#FilterBox").val() != "") {
                    $.ajax({
                        url: "ajax/ajaxrestook.php?a=SearchData",
                        type: "POST",
                        data: { Data : $("#FilterBox").val(), },
                        success: function(result) {
                            var obj = jQuery.parseJSON(result);
                            $.each(obj, function(key, inval) { 
                                switch(inval['FindData']) {
                                    case 1: window.location.replace("restooked.php?itemcode="+inval['ItemCode']+""); break;
                                    case 2: 
                                        if(inval['chkItem'] == 1) {
                                            window.location.replace("restooked.php?itemcode="+inval['ItemCode']+"");
                                        }else{
                                            $("#HeaderModalAlert").html("เลือกรายการสินค้า");
                                            $("#DetailModalAlert").html(inval['TB']);
                                            $("#ModalAlertBTN").addClass("btn-secondary");
                                            $("#ModalAlert").modal("show");
                                        }
                                        break;
                                    case 3: 
                                        $("#HeaderModalAlert").html("");
                                        $("#DetailModalAlert").html("<i class='fas fa-exclamation'></i> ไม่พบข้อมูลสินค้า");
                                        $("#ModalAlertBTN").addClass("btn-secondary");
                                        $("#ModalAlert").modal("show");
                                        $("#FilterBox").val("");
                                        break;
                                }
                            })
                        }
                    })
                }else{
                    $("#HeaderModalAlert").html("");
                    $("#DetailModalAlert").html("<i class='fas fa-exclamation'></i> ไม่มีข้อมูลที่ระบุ");
                    $("#ModalAlertBTN").addClass("btn-secondary");
                    $("#ModalAlert").modal("show");
                }
            }
        })

        
    </script>
<?php require("../template/footer.php"); ?>