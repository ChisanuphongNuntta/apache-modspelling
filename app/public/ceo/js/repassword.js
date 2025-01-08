function RePassword() {
    let pswd1 = $("#pin_1").val();
    let pswd2 = $("#pin_2").val();

    if(pswd1.length != 6 || pswd2.length != 6 || pswd1 != pswd2) {
        $("#pin_1, #pin_2").removeClass("is-valid text-success is-invalid text-danger").addClass("is-invalid text-danger");
        $("#txt_1, #txt_2").html("กรุณากรอกรหัสผ่านให้ครบถ้วน").removeClass("text-danger text-success").addClass("text-danger");
        $("#pin_1").focus();
    } else {
        $.ajax({
            url: "ajax/ajaxCEO.php?p=RePassword",
            type: "POST",
            data: {
                pswd: pswd1
            },
            success: function(result) {
                $("#confirm_saved").modal('show');
                $("#btn-save-reload").on("click", function(e){
                    e.preventDefault();
                    location.href="main.php";
                });
            }
        });
    }
}
$("#pin_1").focusout(function() {
    let pswd = $(this).val();
    if(pswd.length != 6) {
        $(this).removeClass("is-valid text-success").addClass("is-invalid text-danger").val('').focus();
        $("#txt_1").html("กรุณากรอกรหัสผ่านให้ครบหกหลัก").removeClass("text-success").addClass("text-danger");
    } else {
        $(this).removeClass("is-invalid text-danger").addClass("is-valid text-success");
        $("#txt_1").html("รหัสผ่านถูกต้อง").removeClass("text-danger").addClass("text-success");
    }
});

$("#pin_2").focusout(function() {
    let pswd1 = $("#pin_1").val();
    let pswd2 = $("#pin_2").val();
    if(pswd1 != pswd2) {
        if(pswd1 == "") {
            $("#pin_1").removeClass("is-valid text-success").addClass("is-invalid text-danger").val('').focus();
            $("#txt_1").html("กรุณากรอกรหัสผ่านให้ครบหกหลัก").removeClass("text-success").addClass("text-danger");
        }
        $(this).removeClass("is-valid text-success").addClass("is-invalid text-danger").val('').focus();
        $("#txt_2").html("รหัสผ่านทั้งสองช่องไม่ตรงกัน").removeClass("text-success").addClass("text-danger");
    } else {
        $(this).removeClass("is-invalid text-danger").addClass("is-valid text-success");
        $("#txt_2").html("รหัสผ่านถูกต้อง").removeClass("text-danger").addClass("text-success");
    }
});

$(document).ready(function() {
    $("#pin_1").focus();
})