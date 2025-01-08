$(document).ready(function() {
    GetItem();
});

function OpenFile() {
    $("#FileImport").click();
}

$('#FileImport').on('change', function() {
    if($('#FileImport').val() != "") {
        const FormImport = new FormData($("#FormImport")[0]);
        $.ajax({
            url: "pricelist/ajax.php?p=ImportFile",
            type: 'POST',
            dataType: 'text',
            cache: false,
            processData: false,
            contentType: false,
            data: FormImport,
            success: function(result) {
                let obj = jQuery.parseJSON(result);
                $.each(obj,function(key,inval) {
                    $("#alert_header").html("<i class='fas fa-check-circle fa-fw fa-lg'></i>");
                    $("#alert_body").html("นำเข้าข้อมูลสำเร็จ");
                    $("#alert_modal").modal('show');
                    $(document).off("click","button.btn-confirm").on("click","button.btn-confirm", function(e) {
                        e.preventDefault();
                        window.location.reload();
                    });
                });
            } 
        })
    }
});

function GetTemplate() {
    window.open("../ExcelTemplate/import_pricelist.xlsx");
}

function GetItem() {
    $("#TableMain").dataTable().fnClearTable();
    $("#TableMain").dataTable().fnDraw();
    $("#TableMain").dataTable().fnDestroy();

    $("#TableMain").DataTable({
        "ajax": {
            url: "pricelist/ajax.php?p=GetItemList",
            type: "GET",
            async: false,
            dataType: "json",
            dataSrc: "0"
        },
        "columns": [
            { "data": "PriceType", class: ""},
            { "data": "ItemCode", class: "dt-body-center" },
            { "data": "ItemName", class: "" },
            { "data": "BarCode", class: "dt-body-center"},
            { "data": "Cost", class: "dt-body-right"},
            { "data": "Stock", class: "dt-body-right"},
            { "data": "PriceDis", class: "dt-body-right"},
            { "data": "GPDis", class: "dt-body-right"},
            { "data": "PriceWSale", class: "dt-body-right"},
            { "data": "GPWSale", class: "dt-body-right"},
            { "data": "PriceRetail", class: "dt-body-right"},
            { "data": "GPRetail", class: "dt-body-right"},
            { "data": "ValueS1", class: "dt-body-right"},
            { "data": "PriceS1", class: "dt-body-right"},
            { "data": "GPS1", class: "dt-body-right"},
            { "data": "ValueS2", class: "dt-body-right"},
            { "data": "PriceS2", class: "dt-body-right"},
            { "data": "GPS2", class: "dt-body-right"},
            { "data": "ValueS3", class: "dt-body-right"},
            { "data": "PriceS3", class: "dt-body-right"},
            { "data": "GPS3", class: "dt-body-right"},
            { "data": "ValueS4", class: "dt-body-right"},
            { "data": "PriceS4", class: "dt-body-right"},
            { "data": "GPS4", class: "dt-body-right"},
        ],
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "pageLength": 20,
        "ordering": true,
        "language": DTTB_TH
    });
}
