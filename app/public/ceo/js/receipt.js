function number_format(number,decimal) {
    var options = { roundingPriority: "lessPrecision", minimumFractionDigits: decimal, maximumFractionDigits: decimal };
    var formatter = new Intl.NumberFormat("en",options);
    return formatter.format(number)
}

function GetReceipt() {
    var filt_year  = $("#filt_year").val();
    var filt_month = $("#filt_month").val();
    $(".overlay").show();
    $.ajax({
        url: "../kbi/menus/account/ajax/ajaxdaily_receipt.php?a=CallData",
        type: "POST",
        data: {
            Year: filt_year,
            Month: filt_month
        },
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $(".overlay").hide();
            $.each(obj, function(key, inval) {
                $("#TableReceipt tbody").html(inval['Tbody']);
                $("#TableReceipt tfoot").html(inval['Tfoot']);
            });
        }
    });
}

$(document).ready(function() {
    GetReceipt();

    $("#filt_year, #filt_month").on("change", function() {
        GetReceipt();
    });
});