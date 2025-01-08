<style type="text/css">
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-file-alt"></i> รายการหยิบเติมสินค้า</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <i class='fas fa-search' style='font-size: 20px'></i>&nbsp;&nbsp;
                    <input class='form-control form-control-sm' type="text" id='FilterBox' name='FilterBox' placeholder="บาร์โค้ดสินค้า หรือบาร์โค้ดชั้นวาง" list="TxtComplete">
                </div>
            </div>
        </div>
        <div class="row ps-2 pe-2">
            <div class="col-sm">
                <table class='table table-hover'>
                    <thead style='font-size: 15px;'>
                        <tr class='text-center fw-bold'>
                            <td colspan='2'>รายการสินค้าต้องเติม</td>
                        </tr>
                    </thead>
                    <tbody style='font-size: 13px;' id='Tbody'></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalAlert" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h1 class="modal-title text-center" id="HeaderModalAlert"></h1>
                    <div id="DetailModalAlert" class="my-3"></div>
                    <button type="button" class="btn btn-sm w-25 mt-4" id='ModalAlertBTN' data-bs-dismiss="modal"></button>
                </div>
            </div>
        </div>
    </div>

    <?php require("../template/script.php"); ?>
    <script>
        $(document).ready(function(){
            CallData1();
            // setTimeout(function(){
            //     $("#FilterBox").focus();
            // }, 1000);
        });

        function CallData1() {
            $(".overlay").show();
            $.ajax({
                url: "ajax/ajaxrestook.php?a=CallData1",
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) { 
                        $(".overlay").hide();
                        $("#Tbody").html(inval['output']);
                    })
                }
            })
        }

        $("#FilterBox").keypress(function(e) {
            if (e.which == 13) {
                // console.log($("#FilterBox").val());
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
                                            $("#ModalAlertBTN").html("ออก");
                                            $("#ModalAlert").modal("show");
                                        }
                                        break;
                                    case 3: 
                                        $("#HeaderModalAlert").html("");
                                        $("#DetailModalAlert").html("<i class='fas fa-exclamation'></i> ไม่พบข้อมูลสินค้า");
                                        $("#ModalAlertBTN").addClass("btn-secondary");
                                        $("#ModalAlertBTN").html("ออก");
                                        $("#ModalAlert").modal("show");
                                        break;
                                }
                            })
                        }
                    })
                }else{
                    $("#HeaderModalAlert").html("");
                    $("#DetailModalAlert").html("<i class='fas fa-exclamation'></i> ไม่มีข้อมูลที่ระบุ");
                    $("#ModalAlertBTN").addClass("btn-secondary");
                    $("#ModalAlertBTN").html("ออก");
                    $("#ModalAlert").modal("show");
                }
            }
        })
    </script>

<?php require("../template/footer.php"); ?>