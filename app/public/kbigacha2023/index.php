<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KBI Gacha</title>
    <link href="app.css" type="text/css" rel="stylesheet" />
    <link href="../image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
</head>
<body>
    <header>
        <svg viewBox="0 0 600 128">
            <!-- Symbol-->
            <symbol id="s-text">
            <text text-anchor="middle" x="50%" y="50%" dy=".35em">EUROX GACHA</text>
            </symbol>
            <!-- Duplicate symbols-->
            <use class="text" xlink:href="#s-text"></use>
            <use class="text" xlink:href="#s-text"></use>
            <use class="text" xlink:href="#s-text"></use>
            <use class="text" xlink:href="#s-text"></use>
            <use class="text" xlink:href="#s-text"></use>
        </svg>
    </header>
    <section style="margin: 0 8rem;">
        <div class="tab">
            <button class="tablinks" data-tab="1">รางวัลใหญ่</button><button class="tablinks" data-tab="2">รางวัลรอง</button>
        </div>
        <div class="tab-content text-center" data-tab="1" class="text-center">
            <button id="AddWinnerTypeA" onclick="ShowModalA();"><i class="fas fa-award fa-fw fa-lg"></i> กรอกชื่อผู้โชคดีรางวัลใหญ่ <i class="fas fa-award fa-fw fa-lg"></i></button>
            <div class="text-center" style="width: 100%; position: absolute; bottom: 1.5rem;">
                <a href="javascript:void(0);" onclick="ResetReward();"><i class="fas fa-sync fa-spin fa-fw fa-lg"></i> RESET DATA</a> | <a href="javascript:void(0);" onclick="WinnerDetail();"><i class="fas fa-award fa-fw fa-lg"></i> รายชื่อผู้โชคดี</a>
            </div>
        </div>
        <div class="tab-content" data-tab="2">
            <table class="table table-bordered" id="RewardListB" style='font-size: 2rem;'>
                <thead>
                    <tr>
                        <th width="5%">ลำดับ</th>
                        <th width="50%">ชื่อรางวัล</th>
                        <th width="10%">จำนวนรางวัล</th>
                        <th width="15%">สุ่มผู้โชคดี</th>
                        <th width="15%">ผู้โชคดี</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>

    <div id="ModalA" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>กรอกชื่อผู้โชคดีรางวัลใหญ่</h2>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="RewardListA" style="font-size: 2rem;">
                    <thead>
                        <tr>
                            <th width="5%">ลำดับ</th>
                            <th width="45%">ชื่อรางวัล</th>
                            <th width="50%">ผู้โชคดี</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="ModalWinnerList" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close_winner">&times;</span>
                <h2><i class="fas fa-award fa-fw fa-lg"></i> รายชื่อผู้โชคดี</h2>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="WinnerList" style="font-size: 1.5rem;">
                    <thead>
                        <tr>
                            <th width="5%">ลำดับ</th>
                            <th width="45%">ชื่อรางวัล</th>
                            <th width="50%">ผู้โชคดี</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">

    function ResetReward() {
        var r = confirm("คุณต้องการล้างข้อมูลทั้งหมดหรือไม่?");
        if(r == true) {
            $.ajax({
                url: "ajaxgacha.php?p=ResetReward",
                success: function(result) {
                    alert("ล้างข้อมูลสำเร็จ");
                    location.reload();
                }
            });
        }
    }

    function WinnerDetail() {
        var modal = document.getElementById("ModalWinnerList");
        var span = document.getElementsByClassName("close_winner")[0];

        modal.style.display = "block";

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        $.ajax({
            url: "ajaxgacha.php?p=WinnerList",
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key,inval) {
                    let Rows = parseFloat(inval['Row']);
                    var tBody = "";
                    if(Rows == 0) {
                        tBody += "<tr><td class='text-center' colspan='3'>ไม่มีรางวัล</td></tr>";
                    } else {
                        for(i = 0; i < Rows; i++) {
                            if(inval[i]['WinnerName'] != "") {
                                var RowCls = " class='table-success text-success'";
                            } else {
                                var RowCls = "";
                            }

                            tBody +=
                                "<tr"+RowCls+">"+
                                    "<td class='text-right'>"+inval[i]['VisOrder']+"</td>"+
                                    "<td>"+inval[i]['RewardName']+"</td>"+
                                    "<td>"+inval[i]['WinnerName']+"</td>"+
                                "</tr>";
                        }

                        $("#WinnerList tbody").html(tBody);
                        
                    }
                });
            }
        })


    }

    function ShowModalA() {
        var modal = document.getElementById("ModalA");
        var span = document.getElementsByClassName("close")[0];

        modal.style.display = "block";

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        $.ajax({
            url: "ajaxgacha.php?p=GetRewardA",
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key,inval) {
                    let Rows = parseFloat(inval['Row']);
                    var tBody = "";
                    if(Rows == 0) {
                        alert("ไม่มีรางวัล");
                    } else {
                        for(i = 0; i < Rows; i++) {
                            if(inval[i]['EmpCode'] != "") {
                                var Disabled = "disabled";
                            } else {
                                var Disabled = "";
                            }
                            tBody +=
                                "<tr data-Row='"+inval[i]['ID']+"'>"+
                                    "<td class='text-right'>"+inval[i]['No']+"</td>"+
                                    "<td>"+inval[i]['RewardName']+"</td>"+
                                    "<td><input type='text' class='form-control WinnerA' id='A__"+inval[i]['ID']+"' value='"+inval[i]['EmpCode']+"' "+Disabled+" autocomplete='off' /></td>"+
                                "</tr>";
                        }
                        $("#RewardListA tbody").html(tBody);

                        $(".WinnerA").focusout(function() {
                            var InputVal = $(this).val();
                            if(InputVal != "") {
                                var InputID  = $(this).attr("id");
                                var RewardID = InputID.split("__");
                                var EmpCode  = InputVal;
                                $.ajax({
                                    url: "ajaxgacha.php?p=WinnerA",
                                    type: "POST",
                                    data: {
                                        RewardID: RewardID[1],
                                        EmpCode : EmpCode
                                    },
                                    success: function(result) {
                                        var obj = jQuery.parseJSON(result);
                                        $.each(obj, function(key,inval) {
                                            if(inval['Status'] == "SUCCESS") {
                                                // alert("บันทึกสำเร็จ");
                                                $("#A__"+RewardID[1]).removeClass("form-danger").addClass("form-success").attr("disabled",true);
                                            } else {
                                                switch(inval['Status']) {
                                                    case "ERR::DUPLICATE": var alert_txt = "บุคคลนี้เคยได้รับรางวัลแล้ว"; break;
                                                    case "ERR::NOUSERS": var alert_txt = "ไม่พบพนักงานท่านนี้"; break;
                                                }
                                                alert(alert_txt);
                                                $("#A__"+RewardID[1]).removeClass("form-success").addClass("form-danger").val("");
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                    
                });
            }
        })
    }

    function ShowModalB(SubGroup) {
        var modal = document.getElementById("ModalWinnerList");
        var span = document.getElementsByClassName("close_winner")[0];

        modal.style.display = "block";

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        $.ajax({
            url: "ajaxgacha.php?p=GetWinnerB",
            type: "POST",
            data: {
                SubGroup: SubGroup
            },
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key,inval) {
                    let Rows = parseFloat(inval['Row']);
                    var tBody = "";
                    if(Rows == 0) {
                        tBody += "<tr><td class='text-center' colspan='3'>ไม่มีรางวัล</td></tr>";
                    } else {
                        for(i = 0; i < Rows; i++) {
                            if(inval[i]['WinnerName'] != "") {
                                var RowCls = " class='table-success text-success'";
                            } else {
                                var RowCls = "";
                            }

                            tBody +=
                                "<tr"+RowCls+">"+
                                    "<td class='text-right'>"+inval[i]['VisOrder']+"</td>"+
                                    "<td>"+inval[i]['RewardName']+"</td>"+
                                    "<td>"+inval[i]['WinnerName']+"</td>"+
                                "</tr>";
                        }

                        $("#WinnerList tbody").html(tBody);
                        
                    }
                });
            }
        })
    }

    function RewardB() {
        $.ajax({
            url: "ajaxgacha.php?p=GetRewardB",
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    let Rows = inval['Row'];
                    var tBody = "";
                    if(Rows == 0) {
                        tBody += "<tr><td class='text-center' colspan='5'>ไม่มีข้อมูล :(</td></tr>";
                        $("#RewardListB tbody").html(tBody);
                    } else {
                        for(i = 0; i < Rows; i++) {
                            tBody +=
                                "<tr>"+
                                    "<td class='text-right'>"+inval[i]['No']+"</td>"+
                                    "<td>"+inval[i]['RewardName']+"</td>"+
                                    "<td class='text-center'>"+inval[i]['LineNum']+"</td>"+
                                    "<td class='text-center'><button type='button' class='btn btn-random' data-RewardType='"+inval[i]['SubGroup']+"' data-LineNum='"+inval[i]['LineNum']+"'><i class='fas fa-random fa-fw fa-lg'></i></button></td>"+
                                    "<td class='text-center'><button type='button' class='btn btn-winner' data-RewardType='"+inval[i]['SubGroup']+"'><i class='fas fa-award fa-fw fa-lg'></i></button></td>"+
                                "</tr>";
                        }

                        $("#RewardListB tbody").html(tBody);

                        $(".btn-random").on("click", function(e) {
                            e.preventDefault();
                            let SubGroup = $(this).attr("data-RewardType");
                            let LineNum  = $(this).attr("data-LineNum");

                            let RefreshTime = LineNum * 200;
                            $(this).html("<i class='fas fa-spinner fa-pulse fa-fw fa-lg'></i>");
                            let element = $(this);
                            setTimeout(() => {
                                $.ajax({
                                    url: "ajaxgacha.php?p=WinnerB",
                                    type: "POST",
                                    data: {
                                        SubGroup: SubGroup
                                    },
                                    success: function(result) {
                                        $(element).html("<i class='fas fa-check fa-fw fa-lg'></i>").attr("disabled",true);
                                        ShowModalB(SubGroup)
                                    }
                                });
                            }, RefreshTime);
                        });

                        $(".btn-winner").on("click", function(e) {
                            e.preventDefault();
                            let SubGroup = $(this).attr("data-RewardType");
                            ShowModalB(SubGroup)
                        })
                    }
                })
            }
        });
    }

    $("button.tablinks").on("click",function(e){
        e.preventDefault();
        $("button.tablinks").removeClass("active");
        $(this).addClass("active");

        let tabs = $(this).attr("data-tab");
        $(".tab-content:not([data-tab='"+tabs+"'])").hide();
        $(".tab-content[data-tab='"+tabs+"']").show();
    });

    


    $(document).ready(function() {
        $("button.tablinks[data-tab='1']").addClass("active");
        $(".tab-content[data-tab='1']").show();
        $(".tab-content[data-tab='2']").hide();
        RewardB();
    });
</script>
</html>