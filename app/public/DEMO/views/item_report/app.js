function GetItemProduct() {
    const Year = $("#txtYear").val();
    const Month = $("#txtMonth").val();
    $("#TableItemProduct").dataTable().fnClearTable();
    $("#TableItemProduct").dataTable().fnDraw();
    $("#TableItemProduct").dataTable().fnDestroy();
    $("#TableItemProduct").DataTable({
        "ajax": {
            url: "item_report/ajax.php?p=GetItemProduct",
            type: "POST",
            data: { Year: Year, Month: Month },
            async: false,
            dataType: "json",
            dataSrc: "0"
        },
        "columns": [
            { "data": "No", class: "dt-body-right" },
            { "data": "CardName", class: "" },
            { "data": "Excel_CardCode", class: "" }, // Colum พิเศษ เฉพาะ Export Excel
            { "data": "Excel_CardName", class: "" }, // Colum พิเศษ เฉพาะ Export Excel
            { "data": "Excel_SlpName", class: "" }, // Colum พิเศษ เฉพาะ Export Excel
            { "data": "Excel_ItemCode", class: "" }, // Colum พิเศษ เฉพาะ Export Excel
            { "data": "Excel_ItemName", class: "" }, // Colum พิเศษ เฉพาะ Export Excel
            { "data": "ItemName", class: "" },
            { "data": "Brand", class: "" },
            { "data": "SubBrand", class: "" },
            { "data": "Pet", class: "" },
            { "data": "Catagory", class: "" },
            { "data": "CardFName", class: "" },
            { "data": "Region", class: "" },
            { "data": "GroupName", class: "" },
            

            { "data": "SO_DocNum", class: "dt-body-center" },
            { "data": "SO_DocDate", class: "dt-body-center" },
            { "data": "SO_Quantity", class: "dt-body-right" },
            { "data": "SO_Unit", class: "" },
            { "data": "SO_Price", class: "dt-body-right" },
            { "data": "SO_DisPrcnt", class: "dt-body-center" },
            { "data": "SO_LineTotal", class: "dt-body-right" },

            { "data": "IV_DocNum", class: "dt-body-center" },
            { "data": "IV_DocDate", class: "dt-body-center" },
            { "data": "IV_DocDueDate", class: "dt-body-center" },
            { "data": "IV_Quantity", class: "dt-body-right" },
            { "data": "IV_Unit", class: "" },
            { "data": "IV_Price", class: "dt-body-right" },
            { "data": "IV_DisPrcnt", class: "dt-body-center" },
            { "data": "IV_LineTotal", class: "dt-body-right" },
        ],
        columnDefs: [
            { target: 2, visible: false, searchable: false }, // Colum พิเศษ เฉพาะ Export Excel
            { target: 3, visible: false, searchable: false }, // Colum พิเศษ เฉพาะ Export Excel
            { target: 4, visible: false, searchable: false },  // Colum พิเศษ เฉพาะ Export Excel
            { target: 5, visible: false, searchable: false },  // Colum พิเศษ เฉพาะ Export Excel
            { target: 6, visible: false, searchable: false }  // Colum พิเศษ เฉพาะ Export Excel
        ],
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "pageLength": 20,
        "ordering": true,
        "language": DTTB_TH,
        "dom": 'Bfrtip',
        "buttons": [{ 
            "extend": 'excelHtml5',
            "footer": true, 
            exportOptions: {
                columns: [0, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29 ]
            }
        }]
    });
}

$(document).ready(function() {
    GetItemProduct();
});