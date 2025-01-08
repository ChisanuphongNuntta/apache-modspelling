<style>
    /* Nav Custom */
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
        padding: 5px 11px 3px;
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
    /* END Nav Custom */
</style>
<?php require("../template/header.php"); ?>
    <div class='container p-0 bg-light h-100'>
        <div class="row pt-3">
            <div class="col-sm text-center">
                <h6 style='color: #607080;'><i class="fas fa-file-alt"></i> รายการคำสั่งขาย</h6>
                <div class='d-flex justify-content-center'>
                    <hr class='mt-1 w-75 text-muted'>
                </div>
                <div class='d-flex align-items-center justify-content-center ps-2 pe-2'>
                    <i class='fas fa-search' style='font-size: 20px'></i>&nbsp;&nbsp;
                    <input class='form-control form-control-sm' type="text" id='FilterBox' name='FilterBox' placeholder="กรอกเลขที่ SO / ช่องทางขาย / สถานะ" list="TxtComplete">&nbsp;&nbsp;
                    <button class='btn btn-secondary' onclick="ReCall()"><i class="fas fa-sync-alt"></i></button>
                </div>
                <datalist id="TxtComplete">
                    <option>MT1</option>
                    <option>MT2</option>
                    <option>TT1</option>
                    <option>TT2</option>
                    <option>หน้าร้าน</option>
                    <option>ออนไลน์</option>
                    <option>โรงงาน</option>
                    <option>รอจัด</option>
                    <option>กำลังจัด</option>
                    <option>รอตัด</option>
                    <option>รอสินค้า</option>
                    <option>จัดเสร็จ</option>
                </datalist>
            </div>
        </div>
        <div class="row pt-3 ps-2 pe-2">
            <div class="col-sm">
                <input type="hidden" name='btnTab' id='btnTab' value='0'>
                <ul class="nav nav-tabsCus" id="tabReturn" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-linkCus active fw-bold" onclick="CallData()" id="cBill-tab" data-bs-toggle="tab" data-bs-target="#cBill" type="button" role="tab" aria-controls="cBill" aria-selected="true">บิลปัจจุบัน</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-linkCus fw-bold" onclick="CallData2()" id="pBill-tab" data-bs-toggle="tab" data-bs-target="#pBill" type="button" role="tab" aria-controls="pBill" aria-selected="false">บิลล่วงหน้า</button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content" style='background-color: #fff;'>
            <div class="tab-pane fade show active" id="cBill" role="tabpanel" aria-labelledby="cBill-tab">
                <div class="row pt-2">
                    <div class="col-sm">
                        <table class="table table-borderless">
                            <tbody style='font-size: 13px;' id='SOList1'></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pBill" role="tabpanel" aria-labelledby="pBill-tab">
                <div class="row pt-2">
                    <div class="col-sm">
                        <table class="table table-borderless">
                            <tbody style='font-size: 13px;' id='SOList2'></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require("../template/script.php"); ?>

    <script>
        $(document).ready(function(){
            CallData();
        });

        $("#FilterBox").on("keyup", function(){
            var kwd = $(this).val().toLowerCase();
            $("#SOList1 td").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
            $("#SOList2 td").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
        });

        function FilterBox1() {
            var kwd = $("#FilterBox").val().toLowerCase();
            $("#SOList1 td").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
        }
        function FilterBox2() {
            var kwd = $("#FilterBox").val().toLowerCase();
            $("#SOList2 td").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(kwd) > -1)
            });
        }

        function CallData() {
            $(".overlay").show();
            // $("#FilterBox").val("");
            $("#btnTab").val("0")
            $.ajax({
                url: "ajax/ajaxpicking.php?a=CallData",
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#SOList1").html(inval['Tbody']);
                        FilterBox1();
                    })
                    $(".overlay").hide();
                }
            })
        }

        function CallData2() {
            $(".overlay").show();
            // $("#FilterBox").val("");
            $("#btnTab").val("1")
            $.ajax({
                url: "ajax/ajaxpicking.php?a=CallData2",
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#SOList2").html(inval['Tbody']);
                        FilterBox2();
                    })
                    $(".overlay").hide();
                }
            })
        }

        function ReCall() {
            if($("#btnTab").val() == '0'){
                $("#SOList1").html("");
                CallData()
            }else{
                $("#SOList2").html("");
                CallData2()
            }
        }
    </script>
<?php require("../template/footer.php"); ?>