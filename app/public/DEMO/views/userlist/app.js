// function GetOwnList() {
//     $.ajax({
//         url: "userlist/ajax.php?p=GetOwnList",
//         success: function(result) {
//             var obj = jQuery.parseJSON(result);
//             $.each(obj, function(key, inval) {
//                 var OptTxt = "<option value='' selected disabled>กรุณาเลือก</option>";
//                 $.each(inval['OwnList'], function(k, data) {
//                     OptTxt += `<option value='`+data['empID']+`'>`+data['EmpName']+`</option>`;
//                 });
//                 $("#txt_OwnerCode").html(OptTxt).selectpicker();
//             });
//         }
//     })
// }

function GetUserList() {
    let filt_t = $("#filt_dept").val();
    let filt_s = $("#filt_status").val();

    $("#UserList").dataTable().fnClearTable();
    $("#UserList").dataTable().fnDraw();
    $("#UserList").dataTable().fnDestroy();

    $("#UserList").DataTable({
        "ajax": {
            url: "userlist/ajax.php?p=GetUserList",
            type: "POST",
            data: { filt_t: filt_t, filt_s: filt_s },
            async: false,
            dataType: "json",
            dataSrc: "0"
        },
        "columns": [
            { "data": "No", class: "dt-body-right" },
            { "data": "EmpCode", class: "dt-body-center" },
            { "data": "FullName" },
            { "data": "DeptName" },
            { "data": "LvName" },
            { "data": "UserName" },
            { "data": "txtStatus", class: "dt-body-center" },
            { "data": "BTN", class: "dt-body-center" },
        ],
        "createdRow": (row, data, dataIndex, cells) => {
            switch(data.UserStatus) {
                case "I": $(row).addClass("table-secondary"); break;
            }
        },
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "pageLength": 20,
        "ordering": false,
    });
}

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
                    $("#filt_dept, #txt_DeptCode").html(OptTxt);
                }
            });
        }
    });
}

function GetLvCode(DeptCode) {
    $.ajax({
        url: "userlist/ajax.php?p=GetLvCode",
        type: "POST",
        data: { DeptCode: DeptCode },
        async: false,
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            var OptTxt = "<option value='' selected disabled>กรุณาเลือก</option>";
            $.each(obj, function(key,inval) {
                if(inval['Status'] == "OK") {
                    for(i = 0; i < inval['Row']; i++) {
                        OptTxt+= "<option value='"+inval[i]['LvCode']+"'>"+inval[i]['LvName']+"</option>";
                    }
                    $("#txt_LvCode").html(OptTxt).removeAttr("disabled");
                }
            });
        }
    })
}

function AddMember() {
    /* Reset Form */
    $("#FormAddMember")[0].reset();
    $("#FormAddMember input.form-control, #FormAddMember select.form-select").removeClass("is-invalid");
    $("#txt_LvCode").attr("disabled",true);
    $("#txt_UserName, #txt_UserPswd").attr("readonly", true);
    $("#txt_usnm").html("").removeClass("text-danger");
    $("#txt_uKey").val("");
    $("#ModalAddMember").modal("show");

    /* Check Username */
    $("#txt_EN_uFirstName, #txt_uBirthdate").on("focusout",function(e) {
        sessionStorage.removeItem("ChkUsnm");
        let fName = $("#txt_EN_uFirstName").val().toLowerCase();
        let lName = $("#txt_EN_uLastName").val().toLowerCase();
        let bDate = $("#txt_uBirthdate").val().split("-");
        let ChkUsnm = "";
        
        if(fName != "" && bDate != "") {
            let substr = 1;
            do {
                let usnm = fName+"."+lName.substring(0,substr);
                $.ajax({
                    url: "userlist/ajax.php?p=ChkUsnm",
                    type: "POST",
                    data: { usnm: usnm },
                    async: false,
                    success: function(result) {
                        var obj = jQuery.parseJSON(result);
                        $.each(obj, function(key,inval) {
                            if(inval['Status'] == "OK") {
                                sessionStorage.setItem("ChkUsnm", "OK");
                            } else {
                                sessionStorage.setItem("ChkUsnm", "ERR");
                            }
                            ChkUsnm = sessionStorage.getItem("ChkUsnm");
                        });
                    }
                });
                if(ChkUsnm == "OK") {
                    let pswd = fName.substr(0,3)+bDate[2]+bDate[1];
                    $("#txt_UserName").val(usnm);
                    $("#txt_UserPswd").val(pswd);
                } else {
                    substr++;
                }
            } while(ChkUsnm != "OK" && substr <= lName.length);
            if(ChkUsnm != "OK") {
                $("#txt_UserName").removeAttr("readonly");
                $("#txt_usnm").html("กรุณาระบุ Username ตามที่ต้องการ").addClass("text-danger").focus();
            }
        }
    });

    /* Get Position */
    $(document).off("change","#txt_DeptCode").on("change","#txt_DeptCode", function(e) {
        let DeptCode = $(this).val();
        if(DeptCode != "" || DeptCode != "ALL") {
            GetLvCode(DeptCode);
        }
    });
}

function SaveMember() {
    $("#FormAddMember input.form-control, #FormAddMember select.form-select").removeClass("is-invalid");
    ErrPnt = 0;
    ErrTxt = [];
    ErrID  = [];

    if($("#txt_TH_uFirstName").val() == "") { ErrPnt++; ErrTxt.push("ชื่อ (ภาษาไทย)"); ErrID.push("txt_TH_uFirstName"); }
    if($("#txt_TH_uLastName").val() == "") { ErrPnt++; ErrTxt.push("นามสกุล (ภาษาไทย)"); ErrID.push("txt_TH_uLastName"); }
    if($("#txt_EN_uFirstName").val() == "") { ErrPnt++; ErrTxt.push("ชื่อ (ภาษาอังกฤษ)"); ErrID.push("txt_EN_uFirstName"); }
    if($("#txt_EN_uLastName").val() == "") { ErrPnt++; ErrTxt.push("นามสกุล (ภาษาอังกฤษ)"); ErrID.push("txt_EN_uLastName"); }
    if($("#txt_uGender").val() == "") { ErrPnt++; ErrTxt.push("เพศ"); ErrID.push("txt_uGender"); }
    if($("#txt_DeptCode").val() == "") { ErrPnt++; ErrTxt.push("ฝ่าย"); ErrID.push("txt_DeptCode"); }
    if($("#txt_LvCode").val() == "") { ErrPnt++; ErrTxt.push("ตำแหน่ง"); ErrID.push("txt_LvCode"); }
    if($("#txt_uWorkStartDate").val() == "") { ErrPnt++; ErrTxt.push("วันที่เริ่มงาน"); ErrID.push("txt_uWorkStartDate"); }
    if($("#txt_uBirthdate").val() == "") { ErrPnt++; ErrTxt.push("วันเกิด"); ErrID.push("txt_uBirthdate"); }
    // if($("#txt_OwnerCode").val() == "") { ErrPnt++; ErrTxt.push("รหัสพนักงานใน SAP&reg;"); }
    if($("#txt_UserName").val() == "") { ErrPnt++; ErrTxt.push("Username"); ErrID.push("txt_UserName"); }
    if($("#txt_UserPswd").val() == "") { ErrPnt++; ErrTxt.push("Password"); ErrID.push("txt_UserPswd"); }

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
        var MemberForm = new FormData($("#FormAddMember")[0]);
        MemberForm.append('Image',sessionStorage.getItem('Image'));
        $.ajax({
            url: "userlist/ajax.php?p=SaveMember",
            type: 'POST',
            dataType: 'text',
            cache: false,
            processData: false,
            contentType: false,
            data: MemberForm,
            async: false,
            success: function(result) {
                $("#overlay").hide();
                var obj = jQuery.parseJSON(result);
                $.each(obj ,function(key, inval) {
                    if(inval['Status'] == "OK") {
                        $("#alert_header").html("<i class='fas fa-check-circle fa-fw fa-lg'></i> สำเร็จ!");
                        $("#alert_body").html("บันทึกข้อมูลสำเร็จ");
                        $("#alert_modal").modal('show');
                        if($("#txt_uKey").val() == b64_to_utf8(SS_UKEY)) {
                            const d = new Date();
                            let fulldate = d.getFullYear()+''+d.getMonth()+''+d.getDate()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds();
                            $(".img_profile").attr("src","../images/profile/"+b64_to_utf8(SS_UKEY)+".jpg?v="+fulldate); 
                        }
                        GetUserList();
                    }
                    $("#ModalAddMember").modal("hide");
                    
                })
            }
        });
    }
}

function EditMember(uKey) {
    $(this).off("click");
    /* Reset Form */
    $("#FormAddMember")[0].reset();
    $("#FormAddMember input.form-control, #FormAddMember select.form-select").removeClass("is-invalid");
    $("#txt_LvCode").attr("disabled",true);
    $("#txt_UserName, #txt_UserPswd").attr("readonly", true);
    $("#txt_usnm").html("").removeClass("text-danger");
    $("#txt_uKey").val("");
    $("#ModalAddMember").modal("show");

    $("#overlay").show();

    /* Get Position */
    $(document).off("change","#txt_DeptCode").on("change","#txt_DeptCode", function(e) {
        let DeptCode = $(this).val();
        if(DeptCode != "" || DeptCode != "ALL") {
            GetLvCode(DeptCode);
        }
    });

    $.ajax({
        url: "userlist/ajax.php?p=GetProfile",
        type: "POST",
        data: { u: uKey },
        async: false,
        success: function(result) {
            $("#overlay").hide();
            var obj = jQuery.parseJSON(result);
            let LvCode = "";
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    $("#txt_uKey").val(inval['uKey']);
                    $("#txt_TH_uFirstName").val(inval['TH_uFirstName']);
                    $("#txt_TH_uLastName").val(inval['TH_uLastName']);
                    $("#txt_uNickName").val(inval['uNickName']);
                    $("#txt_EN_uFirstName").val(inval['EN_uFirstName']);
                    $("#txt_EN_uLastName").val(inval['EN_uLastName']);
                    $("#txt_uGender").val(inval['uGender']).change();
                    $("#txt_EmpCode").val(inval['EmpCode']);
                    $("#txt_DeptCode").val(inval['DeptCode']).change();
                    $("#txt_uMobileNo").val(inval['uMobileNo']);
                    $("#txt_uEmailAddr").val(inval['uEmailAddr']);
                    $("#txt_uLineID").val(inval['uLineID']);
                    $("#txt_uWorkStartDate").val(inval['uWorkStartDate']);
                    $("#txt_uBirthdate").val(inval['uBirthdate']);
                    // $("#txt_OwnerCode").selectpicker("destroy").val(inval['OwnerCode']).change().selectpicker();
                    $("#txt_UserName").val(inval['UserName']);
                    $("#txt_UserPswd").val(inval['UserPswd']);
                    LvCode = inval['LvCode'];
                }
            });
            $("#txt_LvCode").val(LvCode).change();
        }
    });
}

function ActiveMember(uKey, Type) {
    $("#confirm_modal").modal("show");
    $(document).off("click","#btn-confirm").on("click","#btn-confirm", function() {
        $("#overlay").show();
        $.ajax({
            url: "userlist/ajax.php?p=ActiveMember",
            type: "POST",
            data: { u: uKey, t: Type },
            async: false,
            success: function(result) {
                $("#overlay").hide();
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    if(inval['Status'] == "OK") {
                        $("#confirm_modal").modal("hide");
                        GetUserList();
                    }
                });
            } 
        })
    });
}

$image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
        width:200,
        height:200,
        type:'square' //circle
    },
    boundary:{
        width:300,
        height:300
    }
});

$('#txt_uPhoto').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
        $image_crop.croppie('bind', {
        url: event.target.result
        }).then(function(){
            // jQuery bind complete
        });
    }
    reader.readAsDataURL(this.files[0]);
    $('#ModalProfile').modal('show');
});

function DfImage() {
    $image_crop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function(response){
        sessionStorage.setItem('Image',response);
        $('#ModalProfile').modal('hide');
    })
}

$(document).ready(function() {
    GetDeptCode();
    GetUserList();
    // GetOwnList();
    sessionStorage.setItem('Image',"");
});