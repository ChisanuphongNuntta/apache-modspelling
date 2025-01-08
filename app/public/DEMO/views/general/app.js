$(document).ready(function() {
    ConfigList();
});

function ConfigList() {
    $("#overlay").show();
    $.ajax({
        url: "general/ajax.php?p=ConfigList",
        async: false,
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let tBody = "";
                let GroupName = "";
                if(inval['Status'] == "ERR") {
                    tBody = "<tr><td class='text-center' colspan='2'>ไม่มีข้อมูล :(</tr>";
                } else {
                    for(i = 0; i < inval['Row']; i++) {
                        if(GroupName != inval[i]['GroupName']) {
                            tBody += `
                                <tr>
                                    <td class='table-success fw-bolder' colspan='2'>`+inval[i]['GroupName']+`</td>
                                </tr>
                            `;
                        }
                        tBody += `
                            <tr>
                                <td>`+inval[i]['ConfigName']+`</td>
                                <td><input type='number' class='txt_config text-end form-control form-control-sm' data-CID='`+inval[i]['ConfigID']+`' value='`+inval[i]['ConfigValue']+`' /></td>
                            </tr>
                        `;
                    }
                }

                $("#ConfigList tbody").html(tBody);
                $("#overlay").hide();
            });

            $(".txt_config").on("focusout", function(e) {
                let CID = $(this).attr("data-CID");
                let CVL = $(this).val();
                SaveConfig(CID,CVL);
            })
        }
    });
}

function SaveConfig(CID, CVL) {
    if(CID == 2 && parseFloat(CVL) < 250) {
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณากรอกข้อมูลให้ถูกต้อง");
        $("#alert_modal").modal('show');
    } else {
        $("#overlay").show();
        $.ajax({
            url: "general/ajax.php?p=SaveConfig",
            type: "POST",
            data: { CID: CID, CVL: CVL },
            success: function(result) {
                $("#overlay").hide();
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    if(inval['Status'] == "OK") {
                        $("#alert_header").html("<i class='fas fa-check-circle fa-fw fa-lg'></i> สำเร็จ!");
                        $("#alert_body").html("บันทึกข้อมูลสำเร็จ");
                        $("#alert_modal").modal('show');
                    }
                })
            }
        })
    }
}

function SyncData(SyncType) {
    $("#overlay").show();
    $.ajax({
        url: "general/ajax.php?p=SyncData",
        type: "POST",
        data: { SyncType: SyncType },
        success: function(result) {
            $("#overlay").hide();
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    $("#alert_header").html("<i class='fas fa-check-circle fa-fw fa-lg'></i> สำเร็จ!");
                    $("#alert_body").html("บันทึกข้อมูลสำเร็จ<br><small>[เพิ่มใหม่ "+number_format(inval['NEW'],0)+" รายการ || อัพเดต "+number_format(inval['OLD'],0)+" รายการ]</small>");
                    $("#alert_modal").modal('show');
                }
            })
        }
    })
}