function GetDeptCode() {
    $.ajax({
        url: "userlist/ajax.php?p=GetDeptCode",
        async: false,
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            var OptTxt = "<option value='' selected disabled>กรุณาเลือก</option>";
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    for(i = 0; i < inval['Row']; i++) {
                        OptTxt+= "<option value='"+inval[i]['DeptCode']+"'>"+inval[i]['DeptName']+"</option>";
                    }
                    $("#txt_MenuDeptCode").html(OptTxt);
                }
            });
        }
    });
}

function GetMainMenu() {
    $.ajax({
        url: "menulist/ajax.php?p=MainMenu",
        async: false,
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            var OptTxt = "<option value='' selected disabled>กรุณาเลือก</option>";
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    for(i = 0; i < inval['Row']; i++) {
                        OptTxt+= "<option value='"+inval[i]['MenuKey']+"'>"+inval[i]['MenuName']+"</option>";
                    }
                    $("#txt_HeadMenuKey").html(OptTxt);
                }
            });
        }
    });
}

function GetMenuSort(MenuLevel,MenuHead) {
    let mlv = (MenuLevel == null) ? "null" : MenuLevel;
    let mhd  = (MenuHead == null || MenuHead == "") ? "null" : MenuHead;

    $.ajax({
        url: "menulist/ajax.php?p=GetMenuSort",
        type: "POST",
        data: { mlv: mlv, mhd: mhd },
        async: false,
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                $("#txt_MenuSort").val(inval['MenuSort']);
            });
        }
    })
}

function AddMenu() {
    let usnm = b64_to_utf8(SS_USERNAME);
    
    if(usnm != "admin") {
        $("#alert_header").html("<i class='fas fa-exclamation-triangle fa-fw fa-lg'></i> ข้อผิดพลาด!");
        $("#alert_body").html("ไม่สามารถเพิ่มเมนูได้เนื่องจากคุณไม่มีสิทธิ์");
        $("#alert_modal").modal('show');
    } else {
        let mlv = "";
        let mhd = "";

        $("#FormAddMenu")[0].reset();
        $("#FormAddMenu input.form-control, #FormAddMenu select.form-select").removeClass("is-invalid");
        $("#txt_HeadMenuKey").attr("disabled",true);
        $("#txt_MenuKey").val("");
        $("#ModalAddMenu .modal-title").html("<i class='fas fa-plus'></i> เพิ่มเมนูใหม่");
        $("#ModalAddMenu").modal("show");
        GetMainMenu();
        GetMenuSort(null,null);

        $(document).off("change","#txt_MenuLevel").on("change","#txt_MenuLevel", function(e) {
            mlv = $(this).val();
            mhd = $("#txt_HeadMenuKey").val();
            (mlv == '0') ? $("#txt_HeadMenuKey").attr("disabled",true).val('').change() : $("#txt_HeadMenuKey").removeAttr("disabled") ;
            GetMenuSort(mlv,mhd);
        });

        $(document).off("change","#txt_HeadMenuKey").on("change","#txt_HeadMenuKey", function(e) {
            mlv = $("#txt_MenuLevel").val();
            mhd  = $(this).val();
            GetMenuSort(mlv,mhd);
        })
    }
}

function SaveMenu() {
    ErrPnt = 0;
    ErrTxt = [];
    ErrID  = [];

    if($("#txt_MenuName").val() == "") { ErrPnt++; ErrTxt.push("ชื่อเมนู"); ErrID.push("txt_MenuName"); }
    if($("#txt_MenuLevel").val() == "") { ErrPnt++; ErrTxt.push("ระดับของเมนู"); ErrID.push("txt_MenuLevel"); }
    if($("#txt_MenuLevel").val() == "1" && ($("#txt_HeadMenuKey").val() == "" || $("#txt_HeadMenuKey").val() == null)) { ErrPnt++; ErrTxt.push("เมนูหลัก"); ErrID.push("txt_HeadMenuKey"); }
    if($("#txt_MenuCase").val() == "") { ErrPnt++; ErrTxt.push("Menu Case"); ErrID.push("txt_MenuCase"); }
    if($("#txt_MenuLink").val() == "") { ErrPnt++; ErrTxt.push("Menu Link"); ErrID.push("txt_MenuLink"); }
    if($("#txt_MenuIcon").val() == "") { ErrPnt++; ErrTxt.push("ไอคอน"); ErrID.push("txt_MenuIcon"); }
    if($("#txt_MenuSort").val() == "") { ErrPnt++; ErrTxt.push("ลำดับที่"); ErrID.push("txt_MenuSort"); }


    if(ErrPnt > 0) {
        let alert_body = "กรุณากรอกข้อมูลต่อไปนี้ให้ครบถ้วน<br/><span class='text-danger'>";
        for(e = 0; e < ErrTxt.length; e++) {
            alert_body += "&bull; "+ErrTxt[e]+"<br/>";

            $("#"+ErrID[e]).addClass("is-invalid");
        }
        $("#alert_header").html("<i class='fas fa-exclamation-triangle fa-fw fa-lg'></i> ข้อผิดพลาด!");
        $("#alert_body").html(alert_body);
        $("#alert_modal").modal('show');
    } else {
        $("#overlay").show();
        var MenuForm = new FormData($("#FormAddMenu")[0]);
        $.ajax({
            url: "menulist/ajax.php?p=SaveMenu",
            type: 'POST',
            dataType: 'text',
            cache: false,
            processData: false,
            contentType: false,
            data: MenuForm,
            success: function(result) {
                $("#overlay").hide();
                var obj = jQuery.parseJSON(result);
                $.each(obj ,function(key, inval) {
                    if(inval['Status'] == "OK") {
                        $("#alert_header").html("<i class='fas fa-check-circle fa-fw fa-lg'></i> สำเร็จ!");
                        $("#alert_body").html("บันทึกข้อมูลสำเร็จ");
                        $("#alert_modal").modal('show');
                        $("#ModalAddMenu").modal("hide");
                        GetMenus();
                        // window.location.reload();
                    }else{
                        $("#alert_header").html("<i class='fas fa-exclamation-triangle fa-fw fa-lg'></i> ข้อผิดพลาด!");
                        $("#alert_body").html(inval['Status']);
                        $("#alert_modal").modal('show');
                    }
                    
                })
            }
        });
    }
}

function GetMenus() {
    $.ajax({
        url: "menulist/ajax.php?p=GetMenus",
        type: "GET",
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj ,function(key, inval) {
                $("#Table1 tbody").html(inval['Tbody']);
            })
        }
    })
}

function AppList(data) {
    let MenuType = $("#txt_MenuType").val();
    let DeptCode = $("#txt_MenuDeptCode").val();
    let txtAppList = "";
    const SplitLvClass = (data != null) ? data.split("||") : null;
    $.ajax({
        url: "menulist/ajax.php?p=AppList",
        type: "POST",
        data: { MenuType: MenuType, DeptCode: DeptCode, MenuKey: sessionStorage.getItem('tmpMenuKey') },
        async: false,
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    for(i = 0; i < inval['Row']; i++){
                        let disabled = "";
                        let checked = "";
                        if(SplitLvClass != null) {
                            if(MenuType == 'L') {
                                checked = (jQuery.inArray(MenuType+"_"+inval[i]['Value'], SplitLvClass) !== -1) ? "checked" : "";
                            }else{
                                checked = (jQuery.inArray(inval[i]['DeptCode'], SplitLvClass) !== -1 || inval[i]['DeptCode'] == 'DP000') ? "checked" : "";
                                disabled = (inval[i]['DeptCode'] == 'DP000') ? "disabled" : "";
                            }
                        }else{
                            if(MenuType == 'L') {
                                const SplitLvClass2 = (inval['LvClass'] != null) ? inval['LvClass'].split("||") : null;
                                checked = (jQuery.inArray(MenuType+"_"+inval[i]['Value'], SplitLvClass2) !== -1) ? "checked" : "";
                            }else{
                                checked = (MenuType == "A" || inval[i]['DeptCode'] == 'DP000') ? "checked" : "";
                                disabled = (MenuType == "A" || inval[i]['DeptCode'] == 'DP000') ? "disabled" : "";
                            }
                        }
                        txtAppList +=
                            "<div class='form-check'>"+
                                "<input class='form-check-input' type='checkbox' onclick='SaveClass(\""+MenuType+"\",\""+inval[i]['Value']+"\");' value='"+inval[i]['Value']+"' name='"+MenuType+"_"+inval[i]['Value']+"' id='"+MenuType+"_"+inval[i]['Value']+"' "+disabled+" "+checked+" />"+
                                "<label class='form-check-label' for='"+MenuType+"_"+inval[i]['Value']+"'>"+inval[i]['Text']+"</label>"+
                            "</div>";
                    }
                    $("#ClassSelect").html(txtAppList);
                }else{
                    $("#ClassSelect").html("");
                }
            });
        }
    });
}

function Manager(btn, data) {
    let usnm = b64_to_utf8(SS_USERNAME);
    let alert_body = "";

    if((usnm != "admin" && btn != "Permission") && (usnm != "admin")) {
        $("#alert_header").html("<i class='fas fa-exclamation-triangle fa-fw fa-lg'></i> ข้อผิดพลาด!");
        $("#alert_body").html("ไม่สามารถดำเนินการใด ๆ ได้เนื่องจากคุณไม่มีสิทธิ์");
        $("#alert_modal").modal('show');
    } else {
        switch(btn) {
            case 'HideShow': 
                let ArrayData = data.split('||');
                let MenuKey = ArrayData[0];
                let MenuStatus = ArrayData[1];
                $("#overlay").show();
                $.ajax({
                    url: "menulist/ajax.php?p=HideShow",
                    type: "POST",
                    data: { MenuKey: MenuKey, MenuStatus: MenuStatus, },
                    success: function(result) {
                        let Icon = MenuStatus == 'A' ? "<i class='fas fa-eye-slash'></i>" : "<i class='fas fa-eye'></i>";
                        let Status =  MenuStatus == 'A' ? "I" : "A";
                        $("#Menu"+MenuKey).html(Icon);
                        $("#Menu"+MenuKey).attr("onclick", "Manager(\"HideShow\", \""+MenuKey+"||"+Status+"\");")
                        $("#overlay").hide();
                    }
                })
            break;
            case 'Edit': 
                $(this).off("click");
                let mlv = "";
                let mhd = "";

                $("#FormAddMenu")[0].reset();
                $("#FormAddMenu input.form-control, #FormAddMenu select.form-select").removeClass("is-invalid");
                $("#txt_HeadMenuKey").attr("disabled",true);
                $("#txt_MenuCase, #txt_MenuLink").attr("readonly",true);
                $("#txt_MenuKey").val("");
                $("#ModalAddMenu .modal-title").html("<i class='fas fa-plus'></i> เพิ่มเมนูใหม่");
                $("#ModalAddMenu").modal("show");
                GetMainMenu();

                $(document).off("change","#txt_MenuLevel").on("change","#txt_MenuLevel", function(e) {
                    mlv = $(this).val();
                    mhd = $("#txt_HeadMenuKey").val();
                    (mlv == '0') ? $("#txt_HeadMenuKey").attr("disabled",true).val('').change() : $("#txt_HeadMenuKey").removeAttr("disabled") ;
                });

                $(document).off("change","#txt_HeadMenuKey").on("change","#txt_HeadMenuKey", function(e) {
                    mlv = $("#txt_MenuLevel").val();
                    mhd  = $(this).val();
                });

                $.ajax({
                    url: "menulist/ajax.php?p=GetMenuInfo",
                    type: "POST",
                    data: { mnk: data },
                    async: false,
                    success: function(result) {
                        var obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            let HeadMenuKey = (inval['HeadMenuKey'] == null) ? "" : inval['HeadMenuKey'];
                            $("#txt_MenuKey").val(inval['MenuKey']);
                            $("#txt_MenuName").val(inval['MenuName']);
                            $("#txt_MenuLevel").val(inval['MenuLevel']).change();
                            $("#txt_HeadMenuKey").val(HeadMenuKey).change();
                            $("#txt_MenuCase").val(inval['MenuCase']);
                            $("#txt_MenuLink").val(inval['MenuLink']);
                            $("#txt_MenuIcon").val(inval['MenuIcon']);
                            $("#txt_MenuSort").val(inval['MenuSort']);
                        });
                    }
                })
            break;
            case 'Permission':
                sessionStorage.setItem('tmpMenuKey',data);
                GetDeptCode();
                GetPermissions(data);
                $("#ModalAppClass").modal("show");
                
                /* Event */
                $(document).off("change","#txt_MenuType").on("change","#txt_MenuType", function() {
                    let MenuType = $(this).val();
                    let dis_slct = (MenuType == "L") ? false : true ;
                    (MenuType != "L") ? $("#txt_MenuDeptCode").val("") : null ;
                    (MenuType != "L") ? AppList(null) : null ;
                    if(MenuType == "L") {
                        $("#ClassSelect").html("");
                    }
                    $("#txt_MenuDeptCode").attr("disabled",dis_slct);
                });

                $(document).off("change","#txt_MenuDeptCode").on("change","#txt_MenuDeptCode", function() {
                    let MenuDeptCode = $(this).val();
                    (MenuDeptCode != "" || MenuDeptCode != null) ? AppList(null) : null ;
                });

                
            break;
            case 'Delete':
                $("#confirm_modal").modal("show");
                $(document).off("click","#btn-confirm").on("click","#btn-confirm", function() {
                    $("#overlay").show();
                    $.ajax({
                        url: "menulist/ajax.php?p=DeleteMenu",
                        type: "POST",
                        data: { mnk: data },
                        async: false,
                        success: function(result) {
                            $("#overlay").hide();
                            var obj = jQuery.parseJSON(result);
                            $.each(obj, function(key, inval) {
                                $("#confirm_modal").modal("hide");
                                if(inval['Status'] == "OK") {
                                    GetMenus();
                                } else {
                                    let err_type = inval['Status'].split("::");
                                    switch(err_type[1]) {
                                        case "CANNOT_DELETE": alert_body = "ไม่สามารถลบเมนูนี้ได้กรุณาลองใหม่อีกครั้ง"; break;
                                        case "CLEAR_SUBMENU": alert_body = "กรุณาลบหรือย้ายเมนูรองออกจากเมนูนี้ทั้งหมดก่อน"; break;
                                    }
                                    $("#alert_header").html("<i class='fas fa-exclamation-triangle fa-fw fa-lg'></i> ข้อผิดพลาด!");
                                    $("#alert_body").html(alert_body);
                                    $("#alert_modal").modal('show');
                                }
                            });
                        } 
                    })
                });
            break;
        }
    }
}

function GetPermissions(MenuKey) {
    $.ajax({
        url: "menulist/ajax.php?p=GetPermissions",
        type: "POST",
        data: { MenuKey: MenuKey, },
        async: false,
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                $("#txt_MenuType").val(inval['MenuType']).change();
                if(inval['DeptCode'] != null) {
                    if(inval['MenuType'] == 'L') {
                        $("#txt_MenuDeptCode").attr("disabled",false);
                        $("#txt_MenuDeptCode").val(inval['DeptCode']).change();
                        AppList(inval['LvClass']);
                    }else{
                        $("#txt_MenuDeptCode").attr("disabled",true);
                        $("#txt_MenuDeptCode").val("").change();
                        AppList(inval['DeptCode']);
                    }
                }
            });
        }
    })
}

function SaveClass(MenuType, value) {
    let LvClass = ($("input[id='"+MenuType+"_"+value+"']:checked").val() != undefined) ? "1" : "0";
    $("#overlay").show();
    $.ajax({
        url: "menulist/ajax.php?p=SaveClass",
        type: "POST",
        data: { MenuType: MenuType, DeptCode: value, LvClass: LvClass, MenuKey: sessionStorage.getItem('tmpMenuKey') },
        async: false,
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                $("#overlay").hide();
            });
        }
    })
}

$("#txt_MenuLevel").on("change", function() {
    if($(this).val() == 0) {
        $("#txt_MenuCase").prop("readonly", false);
    }else{
        $("#txt_MenuCase").prop("readonly", true);
    }
})

function GetMenuCase() {
    $.ajax({
        url: "menulist/ajax.php?p=GetMenuCase",
        type: "POST",
        data: { MenuKey: $("#txt_HeadMenuKey").val() },
        async: false,
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                $("#txt_MenuCase").val(inval['MenuCase']);
            });
        }
    })
}

$(document).ready(function() {
    GetMainMenu();
    GetMenus();
});