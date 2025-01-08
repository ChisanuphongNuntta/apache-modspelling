<?php
include("../core/config.core.php");
include("../core/functions.core.php");
date_default_timezone_set('Asia/Bangkok');

?>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>ระบบรับส่งบิลขนส่ง</title>
    <link rel="stylesheet" href="../css/main/app.css">
    <link rel="stylesheet" href="../css/main/jquery.dataTables.min.css">
    <!-- <link rel="stylesheet" href="../css/main/app-dark.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link href="../image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />
    <link rel="stylesheet" href="../css/pages/simple-datatables.css">
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="../css/shared/iconly.css"> -->
    <script src="../js/jquery-min.js" type="text/javascript"></script>
    <style rel="stylesheet" type="text/css"></style>

    <!-- custom -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bai Jamjuree">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .btn {
            width: 30%;
            font-size: 0.8rem;
        }

        body {
            font-family: "Bai Jamjuree", sans-serif;
        }

        .material-symbols-rounded {
            font-variation-settings:
                'FILL'0,
                'wght'700,
                'GRAD'0,
                'opsz'48
        }

        span.size-icon {
            font-size: 60px;
            font-variation-settings: 'OPSZ'24;
        }

        h1 {
            font-weight: bold;
        }

        .dateinput2 {
            font-size: 0.8rem;
            background-color: #EBCE74;
        }

        .tbd {
            font-size: 0.8rem;
        }
    </style>
</head>

<body class="bg-light">
    <div class="overlay text-center" style="color: #151515;">
        <div>
            <i class="fas fa-spinner fa-pulse fa-fw fa-4x"></i><br /><br />
            กำลังโหลด...
        </div>
    </div>
    <div class="container">
        <main>
            <div>
                <div>
                    <h1 class="mb-5 mt-5"><span class="material-symbols-rounded size-icon">
                            inventory
                        </span>รายงานรับ/ส่ง บิลขาย</h1>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <!-- <label for="country" class="form-label">Country</label> -->
                            <select class="form-select" name="ThisYear" id="ThisYear" onchange="CallData();">
                                <?php 
                                for($y = (date('Y')+1); $y >= 2022; $y--) {
                                    echo (($y == date('Y')) ? "<option value='$y' selected>$y</option>" : "<option value='$y'>$y</option>");
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <!-- <label for="state" class="form-label">State</label> -->
                            <?php
                            $thisMonth = date("m");
                            ?>
                            <select class="form-select" name="opMounth" id="opMounth" onchange="CallData();">
                                <option value="1" <?php if ($thisMonth == 1) {
                                                        echo "selected";
                                                    } ?>>มกราคม</option>
                                <option value="2" <?php if ($thisMonth == 2) {
                                                        echo "selected";
                                                    } ?>>กุมภาพันธ์</option>
                                <option value="3" <?php if ($thisMonth == 3) {
                                                        echo "selected";
                                                    } ?>>มีนาคม</option>
                                <option value="4" <?php if ($thisMonth == 4) {
                                                        echo "selected";
                                                    } ?>>เมษายน</option>
                                <option value="5" <?php if ($thisMonth == 5) {
                                                        echo "selected";
                                                    } ?>>พฤษภาคม</option>
                                <option value="6" <?php if ($thisMonth == 6) {
                                                        echo "selected";
                                                    } ?>>มิถุนายน</option>
                                <option value="7" <?php if ($thisMonth == 7) {
                                                        echo "selected";
                                                    } ?>>กรกฎาคม</option>
                                <option value="8" <?php if ($thisMonth == 8) {
                                                        echo "selected";
                                                    } ?>>สิงหาคม</option>
                                <option value="9" <?php if ($thisMonth == 9) {
                                                        echo "selected";
                                                    } ?>>กันยายน</option>
                                <option value="10" <?php if ($thisMonth == 10) {
                                                        echo "selected";
                                                    } ?>>ตุลาคม</option>
                                <option value="11" <?php if ($thisMonth == 11) {
                                                        echo "selected";
                                                    } ?>>พฤศจิกายน</option>
                                <option value="12" <?php if ($thisMonth == 12) {
                                                        echo "selected";
                                                    } ?>>ธันวาคม</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <!-- <label for="state" class="form-label">State</label> -->
                            <?php


                            $sql1 = "SELECT LogiID,loginame,logilastname,loginickname,logiplate FROM logistic WHERE status = 1";
                            //echo $sql1;

                            ?>
                            <select class="form-select" id="employee" name="employee" required="">
                                <option value="" disabled selected>ระบุชื่อพนักงานส่งบิล</option>
                                <?php

                                $getName = MySQLSelectX($sql1);
                                while ($ShowName = mysqli_fetch_array($getName)) {
                                    echo "<option value='" . $ShowName['LogiID'] . "'>" . $ShowName['loginame'] . " " . $ShowName['logilastname'] . " (" . $ShowName['loginickname'] . ") - [" . $ShowName['logiplate'] . "].</option>";
                                }
                                ?>
                                <option value="dd" disabled selected>ระบุชื่อพนักงานส่งบิล</option>
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid state.
                            </div>
                            <br>
                        </div>
                        <hr>
                        <div class="col-sm-4">
                            <!-- <label for="firstName" class="form-label">ชื่อผู้ส่งบิล</label> -->
                            <input type="text" class="form-control" name="person" id="person" value="" placeholder="ชื่อผู้ส่งบิล" disabled selected>
                        </div>
                        <div class="col-sm-4">
                            <!-- <label for="firstName" class="form-label">วันที่ส่งบิล</label> -->
                            <input type="date" class="form-control" name="nDate" id="nDate" value="<?php echo date("Y-m-d"); ?>" placeholder="วันที่ส่งบิล" disabled selected readonly>
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-danger">ออกจากระบบ</button>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="search" id="search" placeholder="ค้นหาข้อมูล">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="BillNoX" name="BillNoX" value="" placeholder="เลขที่บิล" disabled selected>
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-success" onclick="CallModal()">ตรวจสอบ</button>
                        </div>
                        <div>
                            <hr>
                        </div>
                        <div class="content">
                            <div class="container">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-sm tbd">
                                        <thead class="text-center">
                                            <tr>
                                                <th scope="col">เลขที่บิล</th>
                                                <th scope="col">วันที่</th>
                                                <th scope="col">ลูกค้า</th>
                                                <th scope="col">พนักงาขาย</th>
                                                <th scope="col">ยอดรวม</th>
                                                <th scope="col">วันที่ส่งของ</th>
                                                <th scope="col" colspan='2' width="15%">ผู้คืนบิล</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb1">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- <script src="/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    <!-- <script src="form-validation.js"></script> -->
    <div id="dataModal" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span style="font-size:24px;font-weight:bold;">ไม่พบข้อมูลในระบบ</span>
                </div>
                <div class="modal-body" id="newData">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="saveNew" class="btn btn-primary btn-sm" onclick="addnewX()"><i class="fa fa-save fa-fw"></i>บันทึก</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="chkmodal" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span>รายงานการส่งบิล วันที่ <? echo inwmount(date("d-m-Y")); ?></span>
                </div>
                <div class="modal-body" id="chklist">
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="billx" id="billx" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal" name="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

<div class="modal fade" id="alert_modal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="modal-title" id="alert_header"></h5>
                <p id="alert_body" class="my-4"></p>
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>


</html>
<script src="../js/app.js"></script>
                            
<script type="text/javascript">
    $(document).ready(function() {
        CallData();
    });
</script>

<script>
    $(document).ready(function() {
        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tb1 tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

<script type="text/javascript">
    function CallData() {
        $(".overlay").show();
        $.ajax({
            url: "ajax/ajaxdaily.php?a=read", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
            type: "POST",
            data: {
                opM: $('#opMounth').val(),
                opY: $('#ThisYear').val(),
                EmpUser: $('#EmpUser').val()
            },
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    $("#tb1").html(inval["output"]);
                });
                $(".overlay").hide();
            }
        });
    };
</script>
<script type="text/javascript">
    function CallModal() {
        $(".overlay").show();
        $.ajax({
            url: "ajax/ajaxdaily.php?a=modal", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
            type: "POST",
            data: {
                ukey: $('#employee').val(),
                BillIV: $('#BillNoX').val(),

            },
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    $('#chklist').html(inval["output"]);
                    $('#chkmodal').modal('show');
                });
                $(".overlay").hide();
            }
        });
    }
    function AddName(x,y){
        
        var chkID = $('#employee').val();
        var nameCHK = $('#person').val();
        
        var chk = document.getElementById("CHK_"+x+"_"+y).checked;

        if(chkID != "" && chkID != null && chkID != undefined) {
            console.log(chkID);
            if (chk){
                chkvalue = 1;
            }else{
                chkvalue = 0;
            }
            if (nameCHK == "" ){
                $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                $("#alert_body").html("กรุณาระบุคนส่งบิลก่อน");
                $("#alert_modal").modal("show");
                document.getElementById("CHK_"+x+"_"+y).checked = false;
            }else{
                $(".overlay").show();
                $.ajax({
                    url: "ajax/ajaxdaily.php?a=addchk", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
                    type: "POST",
                    data: {
                        DocType: x,
                        DocEntry: y,
                        CHKPoint: chkvalue,
                        chkID:chkID,
                    },
                    success: function(result) {
                        var obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            $('#Name_'+x+"_"+y).html(inval['output']);
                        });
                        $(".overlay").hide();
                    }
                });
            }
        }else{
            $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
            $("#alert_body").html("กรุณาระบุพนักงานส่งบิล");
            $("#alert_modal").modal("show");
            $("#CHK_"+x+"_"+y).prop('checked', false);
        }
    }

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#employee").change(function() {
            $(".overlay").show();
            $.ajax({
                url: "ajax/ajaxdaily.php?a=emp", //แก้ บรรทัดนี้ทุกครั้ง  URL ajax เอง
                type: "POST",
                data: {
                    ukey: $('#employee').val()
                },
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        $("#person").val(inval["output"]);
                        $('#BillNoX').removeAttr("disabled");
                    });
                    $(".overlay").hide();
                }
            });
        });
    });
</script>

<script>
    $(document).ajaxStart(function(){
        $(".btn").click(function(){
            $("#chkmodal").modal('hide');
        });
    });
</script>
