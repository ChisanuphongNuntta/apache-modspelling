$(document).ready(function(){
    GetProfile();
});

function GetProfile() {
    $.ajax({
        url: "profile/ajax.php?p=GetProfile",
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key,inval) {
                const uNickName = inval['uNickName'] != "" && inval['uNickName'] != null ? "("+inval['uNickName']+")" : "";
                $("#HeaderNameProfile").html(inval['TH_uFirstName']+" "+inval['TH_uLastName']+" "+uNickName);
                $("#HeaderLvNameProfile").html("<i class='fas fa-id-card-alt'></i> "+inval['LvName']);
                $("#txt_TH_uFirstName").val(inval['TH_uFirstName']);
                $("#txt_TH_uLastName").val(inval['TH_uLastName']);
                $("#txt_uNickName").val(inval['uNickName']);
                $("#txt_EN_uFirstName").val(inval['EN_uFirstName']);
                $("#txt_EN_uLastName").val(inval['EN_uLastName']);
                $("#txt_uGender").val(inval['uGender']);
                $("#txt_uWorkStartDate").val(inval['uWorkStartDate']);
                $("#txt_uBirthdate").val(inval['uBirthdate']);
                $("#txt_EmpCode").val(inval['EmpCode']);
                $("#txt_DeptCode").val(inval['DeptName']);
                $("#txt_LvCode").val(inval['LvName']);
                $("#txt_uMobileNo").val(inval['uMobileNo']);
                $("#txt_uEmailAddr").val(inval['uEmailAddr']);
                $("#txt_uLineID").val(inval['uLineID']);

                $(".name-top-profile").html(inval['TH_uFirstName']+" "+inval['TH_uLastName']);
            });
        }
    })
}

$("#txt_TH_uFirstName, #txt_TH_uLastName, #txt_EN_uFirstName, #txt_EN_uLastName").keydown(function (e) {
    if (e.keyCode == 32 || e.keyCode==9) {
        e.preventDefault();
    }
});

$("#txt_TH_uFirstName, #txt_TH_uLastName, #txt_EN_uFirstName, #txt_EN_uLastName").keyup(function(){
    if($(this).val() != "") {
        $(this).removeClass("is-invalid");
    }else{
        $(this).addClass("is-invalid");
    }
})

$("#txt_con_password").on("keyup", function() {
    $("#txt_con_password").removeClass('is-invalid');
})

function EditProfile() {
    if($(".EditProfile").attr("data-edit") == 'Edit') {
        $("label[for='txt_TH_uFirstName'] span, label[for='txt_TH_uLastName'] span, label[for='txt_EN_uFirstName'] span, label[for='txt_EN_uLastName'] span, label[for='txt_uGender'] span").html("<span class='text-primary'>*</span>");
        $("label[for='txt_uWorkStartDate'] span, label[for='txt_EmpCode'] span, label[for='txt_DeptCode'] span, label[for='txt_LvCode'] span, label[for='txt_uBirthdate'] span").html("<i class='fas fa-lock' style='font-size: 12px;'></i>");
        $(".input-profile").prop("disabled", false);
        $(".EditProfile").attr("data-edit", "Add").removeClass("btn-primary").addClass("btn-success");
        $(".EditProfile").html("<i class='fas fa-save'></i> บันทึกโปรไฟล์");
    }else{
        let ErrPoint = 0;
        if(b64_to_utf8(SS_LVCODE) == 'I0001') {
            $("#txt_TH_uFirstName, #txt_TH_uLastName, #txt_EN_uFirstName, #txt_EN_uLastName").removeClass("is-invalid");
        }else{
            if($("#txt_TH_uFirstName").val() == "") { ErrPoint++; }
            if($("#txt_TH_uLastName").val() == "") { ErrPoint++; }
            if($("#txt_EN_uFirstName").val() == "") { ErrPoint++; }
            if($("#txt_EN_uLastName").val() == "") { ErrPoint++; }
        }
        
        if($("#txt_password").val() != "") {
            if($("#txt_con_password").val() == "") {
                $("#alert_header").html("<i class='fas fa-exclamation-triangle fa-fw fa-lg'></i> ข้อผิดพลาด!");
                $("#alert_body").html("กรุณากรอกรหัสผ่านอีกครั้ง");
                $("#alert_modal").modal('show');
                $("#txt_con_password").addClass('is-invalid');
                return;
            }else{
                if($("#txt_password").val() != $("#txt_con_password").val()) {
                    $("#alert_header").html("<i class='fas fa-exclamation-triangle fa-fw fa-lg'></i> ข้อผิดพลาด!");
                    $("#alert_body").html("รหัสผ่านไม่ตรงกัน กรุณากรอกรหัสผ่านอีกครั้ง");
                    $("#alert_modal").modal('show');
                    $("#txt_con_password").addClass('is-invalid');
                    return;
                }
            }
        }

        if(ErrPoint == 0) {
            $("#overlay").show();
            var DataForm = new FormData($("#FormProfile")[0]);
            $.ajax({
                url: "userlist/ajax.php?p=SaveMember&page=Profile",
                type: 'POST',
                dataType: 'text',
                cache: false,
                processData: false,
                contentType: false,
                data: DataForm,
                async: false,
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj ,function(key, inval) {
                        if(inval['Status'] == "OK") {
                            $("label[for='txt_TH_uFirstName'] span, label[for='txt_TH_uLastName'] span, label[for='txt_EN_uFirstName'] span, label[for='txt_EN_uLastName'] span, label[for='txt_uGender'] span, label[for='txt_uBirthdate'] span").html("");
                            $("label[for='txt_uWorkStartDate'] span, label[for='txt_EmpCode'] span, label[for='txt_DeptCode'] span, label[for='txt_LvCode'] span").html("");
                            $("#txt_password, #txt_con_password").val("");
                            $(".EditProfile").attr("data-edit", "Edit").removeClass("btn-success").addClass("btn-primary");
                            $(".EditProfile").html("<i class='fas fa-pen'></i> แก้ไขโปรไฟล์");
                            GetProfile();

                            $("#alert_header").html("<i class='fas fa-check-circle fa-fw fa-lg'></i> สำเร็จ!");
                            $("#alert_body").html("บันทึกข้อมูลสำเร็จ");
                            $("#alert_modal").modal('show');
                        }
                        
                    });
                    $(".input-profile").prop("disabled", true);
                    $("#overlay").hide();
                }
            });
        }else{
            $("#alert_header").html("<i class='fas fa-exclamation-triangle fa-fw fa-lg'></i> ข้อผิดพลาด!");
            $("#alert_body").html("กรุณากรอกข้อมูลที่มีเครื่องหมาย (*) ให้ครบ");
            $("#alert_modal").modal('show');
        }
    }
}

function EditIMG() {
    $("#AddIMG").click();
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

$('#AddIMG').on('change', function(){
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

$('.crop_image').click(function(event){
    $("#overlay").show();
    $image_crop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function(response){
        $.ajax({
            url:"profile/ajax.php?p=UploadProfil",
            type: "POST",
            data:{ Image: response},
            success:function(data) {
                const d = new Date();
                let fulldate = d.getFullYear()+''+d.getMonth()+''+d.getDate()+''+d.getHours()+''+d.getMinutes()+''+d.getSeconds();
                $(".img_profile").attr("src","../images/profile/"+b64_to_utf8(SS_UKEY)+".jpg?v="+fulldate); 
                $('#ModalProfile').modal('hide');
            }
        });
    })
    $("#overlay").hide();
});

function ShowExFile() {
    var show = "";
    var img = 0;
    var pdf = 0;
    if(file_upload.files.length != 0) {
        var [file] = [file_upload.files[0]];
        show += "<div class='carousel-item active text-center p-2'>"+
                    "<img src='"+URL.createObjectURL(file)+"' style='width: 100%;'>"+
                    "<div class='carousel-caption d-none d-md-block'></div>"+
                "</div>";
        var showimg="<div class='carousel-inner'>"+show+"</div>";
        $("#showimg").html(showimg);
    }else{
        $("#file_upload").val("");
    }
}