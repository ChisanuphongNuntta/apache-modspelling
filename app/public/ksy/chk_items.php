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
    <title>EUROX Force : นับสินค้า KSY</title>

    <link rel="stylesheet" href="../css/main/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link href="../image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <script src="../js/jquery-min.js" type="text/javascript"></script>
    <style>
        .body-input:focus+.head-input {
			display: block;
		}

        .body-input:valid+.head-input { 
            display: block;
         }     

        .head-input {
			display: none;
		}

        .tableFix {
            overflow-y: auto;
            height: 300px;
        }
        .tableFix th {
            position: sticky;
            top: 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid justify-content-center d-flex align-items-center bg-dark" style="height: 100vh;">
        <div class="row">
            <div class="col-lg-12 rounded p-4" style="background-color: #9a1118;">
                <div class='text-center'>
                    <span class="text-white" style='font-size: 40px;'><i class="fas fa-warehouse"></i></span>
                </div>
                <div class='text-center pb-3'>
                    <span class="text-white" style='font-size: 20px;'>นับสินค้า KSY</span>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" name="LocRack" id='LocRack' class="form-control form-control-xl body-input" placeholder="โซนสินค้า" style='width: 287px; text-transform:uppercase;' value='K1-01-01-01' required>
                    <div class='position-absolute text-white text-opacity-75 head-input' style='top: -20px; left: 8px;'>โซนสินค้า</div>
                    <div class="form-control-icon">
                        <i class="fas fa-pallet"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" name="ItemCode" id='ItemCode' class="form-control form-control-xl body-input" placeholder="บาร์โค้ด" style='width: 287px;' required >
                    <div class='position-absolute text-white text-opacity-75 head-input' style='top: -20px; left: 8px;'>บาร์โค้ด</div>
                    <div class="form-control-icon">
                        <i class="fas fa-barcode"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4 d-flex justify-content-between ">
                    <div style='width: 170px;'>
                        <input type="number" name="OnHand" id='OnHand' class="form-control form-control-xl body-input" placeholder="จำนวนสินค้า" required onkeydown="if(event.key==='.' || event.key==='+'){event.preventDefault();}" >
                        <div class='position-absolute text-white text-opacity-75 head-input' style='top: -20px; left: 8px;'>จำนวนสินค้า</div>
                        <div class="form-control-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                    <div style=''>
                        <button class='btn btn-secondary btn-xl' onclick="Save()";>บันทึก</button>
                    </div>
                </div>

                <div class='text-center text-white' id='Score'></div>
            </div>  
        </div>
    </div>

    <div class="modal fade" id="alert_modal" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="modal-title" id="alert_header"></h5>
                    <p id="alert_body" class="my-4"></p>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ออก</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="list_modal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h1 class="modal-title text-center" id="list_header"></h1>
                    <div id="list_detail" class="my-3">
                        <div class='tableFix'>
                            <table class='table table-sm' id='TableList'>
                                <thead>
                                    <tr>
                                        <th colspan='2' class='text-center'>เลือกรายการสินค้า</th>
                                    </tr>
                                </thead>
                                <tbody style='font-size: 13px;'></tbody>
                            </table>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm w-25 mt-4" data-bs-dismiss="modal"></button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/app.js"></script>
    <script>
        $(document).ready(function(){ 
            GetScore();
        });

        $("#LocRack").keypress(function (e) {
            if (e.which == 13) {
                if($(this).val() != "") {
                    $.ajax({
                        url: "ajax/ajaxchk_items.php?a=ChkLocRack",
                        type: "POST",
                        data: { LocRack: $(this).val() },
                        success: function(result) {
                            let obj = jQuery.parseJSON(result);
                            $.each(obj, function(key, inval) {
                                if(inval['Status'] == 'Y') {
                                    //$("#ItemCode").prop("disabled", false);
                                    $("#ItemCode").focus();
                                }else{
                                    $("#alert_header").html("<i class='fas fa-exclamation-circle' style='font-size: 70px;'></i>");
                                    $("#alert_body").html("ไม่มีโซนสินค้านี่ในระบบ");
                                    $("#alert_modal").modal("show");

                                    $("#LocRack").val("");
                                    $("#LocRack").focus();
                                }
                            });
                        }
                    })
                }
            }
        });

        $("#ItemCode").keypress(function (e) {
            if (e.which == 13) {
                if($(this).val() != "") {
                    $.ajax({
                        url: "ajax/ajaxchk_items.php?a=ChkItemCode",
                        type: "POST",
                        data: { ItemCode: $(this).val() },
                        success: function(result) {
                            let obj = jQuery.parseJSON(result);
                            $.each(obj, function(key, inval) {
                                if(inval['Status'] == 'Y'){
                                    $("#ItemCode").val(inval['ItemCode']);
                                    // $("#OnHand").prop("disabled", false);
                                    $("#OnHand").focus();
                                }else{
                                    $("#TableList tbody").html(inval['Tbody']);
                                    $("#list_modal").modal("show");
                                }
                            });
                        }
                    })
                }
            }
        });

        function ftItemCode(ItemCode) {
            $("#ItemCode").val(ItemCode);
            $("#list_modal").modal("hide");
            setTimeout(function(){
                $("#OnHand").focus();
            }, 500);
        }

        // $("#OnHand").keypress(function (e) {
        //     if (e.which == 13) {
        //         Save();
        //     }
        // });

        function Save() {
            const LocRack = $("#LocRack").val();
            const ItemCode = $("#ItemCode").val();
            const OnHand = $("#OnHand").val();
            if(LocRack != "" && ItemCode != "" && OnHand != "") {
                $.ajax({
                    url: "ajax/ajaxchk_items.php?a=Save",
                    type: "POST",
                    data: { LocRack: LocRack, ItemCode: ItemCode, OnHand: OnHand },
                    success: function(result) {
                        let obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            $("#alert_header").html("<i class='fas fa-check-circle text-success' style='font-size: 70px;'></i>");
                            $("#alert_body").html("<span style='font-size: 20px;'>เพิ่มสำเร็จ</span><br>จำนวนที่เพิ่ม / จำนวนใน SAP<br><span style='font-size: 15px;'>"+inval['OnHand']+"</span>");
                            $("#alert_modal").modal("show");
                            GetScor();
                            // $("#LocRack").val("");
                            $("#ItemCode").val("");
                            $("#OnHand").val("");
                            // $("#OnHand").prop("disabled", true);
                        });
                    }
                })
            }else{
                $("#alert_header").html("<i class='fas fa-exclamation-circle' style='font-size: 70px;'></i>");
                $("#alert_body").html("กรุณากรอกข้อมูลให้ครบ");
                $("#alert_modal").modal("show");
            }
        }

        function GetScore(){ 
            $.ajax({
                url: "ajax/ajaxchk_items.php?a=GetScore",
                type: "GET",
                success: function(result) {
                    let obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#Score").html(inval['Score']);
                    });
                }
            })
        }
    </script>

    
</body>
</html>