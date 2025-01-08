<style type="text/css"></style>
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-truck-loading"></i> โหลดสินค้าขึ้น</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <i class='fas fa-search' style='font-size: 20px'></i>&nbsp;&nbsp;
                    <input class='form-control form-control-sm' type="text" id='FilterBox' name='FilterBox' placeholder="เลขที่ SO / ช่องทางขาย / สายส่ง / สถานะ" list="TxtComplete">&nbsp;
                    <button class='btn btn-sm btn-secondary' onclick="ReCall()"><i class="fas fa-sync-alt"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;
                    <button class='btn btn-sm btn-primary' style='font-size: 11px;' id="BtnNewLoad" onclick='NewLoad()'><i class="fas fa-plus fs-6"></i></button>
                </div>
                <datalist id="TxtComplete">
                    <?php
                    $sqlQRY = MySQLSelectX("SELECT loginame, logilastname, loginickname FROM logistic WHERE status = 1");
                    while($result = mysqli_fetch_array($sqlQRY)) {
                        if($result['loginickname'] == "") {
                            echo "<option>".$result['loginame']." ".$result['logilastname']."</option>";
                        }else{
                            echo "<option>".$result['loginame']." ".$result['logilastname']." (".$result['loginickname'].")</option>";
                        }
                    }
                    ?>
                </datalist>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-sm">
                <table class="table table-borderless">
                    <thead style='font-size: 13px;'>
                        <tr>
                            <td class='pb-0'>
                                <a href="javascript:void(0);" data-bs-container="body" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="bottom">
                                    <i class="fas fa-info-circle"></i> คำอธิบายสถานะ
                                </a>
                            </td>
                        </tr>
                    </thead>
                    <tbody style='font-size: 13px;' id='Tbody'></tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require("../template/script.php"); ?>
    <script>
        $(document).ready(function(){
            $('[data-bs-toggle="popover"]').popover({
                html:true,
                content:function(){
                    var Text =  "<div class='row'>"+
                                    "<div class='col-sm'>"+
                                        "<i class='fas fa-hourglass-half'></i> = รอโหลดสินค้า"+
                                        "&nbsp;&nbsp;&nbsp;"+
                                        "<i class='fas fa-check text-success'></i> = โหลดสินค้าสำเร็จ"+
                                    "</div>"+
                                "</div>";
                    return (Text);
                },
            });   

            CallData();
        });

        $("#FilterBox").on("keyup", function(){
            var kwd = $(this).val().toLowerCase();
            $("#Tbody td").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
        });

        function CallData() {
            $(".overlay").show();
            $.ajax({
                url: "ajax/ajaxloading.php?a=CallData",
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#Tbody").html(inval['output']);
                    })
                    $(".overlay").hide();
                }
            })
        }

        function ReCall() {
            CallData()
        }

        function NewLoad() {
            $.ajax({
                url: "ajax/ajaxloading.php?a=NewLoad",
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        window.location.replace("loadlist.php?IDLogi="+inval['lastid']+"");
                    })
                }
            })
        }
    </script>
<?php require("../template/footer.php"); ?>