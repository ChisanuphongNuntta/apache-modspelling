function GetOrder() {
    const Year = $("#txtYear").val();
    const Month = $("#txtMonth").val();
    $("#OrderTracking").dataTable().fnClearTable();
    $("#OrderTracking").dataTable().fnDraw();
    $("#OrderTracking").dataTable().fnDestroy();
    $("#OrderTracking").DataTable({
        "ajax": {
            url: "order_tracking/ajax.php?p=GetOrder",
            type: "POST",
            data: { Year: Year, Month: Month },
            async: false,
            dataType: "json",
            dataSrc: "0"
        },
        "columns": [
            { "data": "No",         class: "dt-body-right" },
            { "data": "DocNum",     class: "dt-body-center" },
            { "data": "DocDate",    class: "dt-body-center" },
            { "data": "CardCode",    class: "dt-body-center" }, // Colum พิเศษ เฉพาะ Export Excel
            { "data": "CardName2",   class: "" }, // Colum พิเศษ เฉพาะ Export Excel
            { "data": "CardName",   class: "" },
            { "data": "SlpName",   class: "" }, // Colum พิเศษ เฉพาะ Export Excel
            { "data": "U_PONo",     class: "dt-body-center" },
            { "data": "DocTotal",   class: "dt-body-right" },
            
            { "data": "DocNumSO",   class: "dt-body-center" },
            { "data": "DocDateSO",  class: "dt-body-center" },
            { "data": "DocTotalSO",  class: "dt-body-right" },

            { "data": "DocNumDO",   class: "dt-body-center" },
            { "data": "DocDateDO",  class: "dt-body-center" },
            { "data": "DocTotalDO", class: "dt-body-right" },

            { "data": "DocNumIV",   class: "dt-body-center" },
            { "data": "DocDateIV",  class: "dt-body-center" },
            { "data": "DocDueDateIV", class: "dt-body-center" },
            { "data": "DocTotalIV", class: "dt-body-right" },
            { "data": "PaidIV",     class: "dt-body-right" },

            { "data": "Comments",     class: "" },
            { "data": "FOC",     class: "dt-body-center" },
            { "data": "TotalFOC",     class: "dt-body-right" },
        ],
        "createdRow": function (row, data, dataIndex, cells) {
            if(data.Status == 'N') {
                $(row).addClass("table-secondary");
            }
        },
        columnDefs: [
            { target: 3, visible: false, searchable: false }, // Colum พิเศษ เฉพาะ Export Excel
            { target: 4, visible: false, searchable: false }, // Colum พิเศษ เฉพาะ Export Excel
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
                columns: [0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22 ]
            }
        }]
    });
}

function ViewDoc(Type, DocNum) {
    if(Type == 'WBAP') {
        $.ajax({
            url: "order_tracking/ajax.php?p=ViewDoc",
            type: "POST",
            data: { Type: Type, DocNum: DocNum },
            success: function(result) {
                let obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    let tBody = "";
                    let AllTotal = 0;
                    $.each(inval['ItemList'], function(k, data) {
                        let tHead = [
                            'ชื่อลูกค้า', 'เลขที่ผู้เสียภาษี',
                            'วันที่ใบสั่งขาย', 'วันที่กำหนดส่ง',
                            'เงื่อนไขการชำระเงิน', 'Sale Orders Type',
                            'ที่อยู่เปิดบิล', 'ที่อยู่จัดส่ง',
                            'พนักงานขาย', 'เอกสารอ้างอิง'
                        ];
                    
                        let DataViewDoc = "";
                        for(let r = 0; r < tHead.length; r++) {
                            DataViewDoc += `
                            <div class="row p-0 pb-2">`;
                            DataViewDoc += `
                                <div class="col-lg d-flex" style='font-size: 14px;'>
                                    <div style='width: 20%;' class='fw-bold'>`+tHead[r]+`</div>
                                    <div style='width: 80%;'>`+inval['tBody'][r]+`</div>
                                </div>`;
                                r++;
                                DataViewDoc += `
                                <div class="col-lg d-flex" style='font-size: 14px;'>
                                    <div style='width: 20%;' class='fw-bold'>`+tHead[r]+`</div>
                                    <div style='width: 80%;'>`+inval['tBody'][r]+`</div>
                                </div>`;
                                DataViewDoc += `
                            </div>`;
                        }
                        $("#DataViewDoc").html(DataViewDoc);

                        
                        AllTotal = AllTotal+parseFloat(data['LineTotal']);
                        let Line_Disc = "";
                        if(data['Line_Disc0'] != null) {
                            Line_Disc = number_format(data['Line_Disc0'],2);
                        }else{
                            if(data['Line_Disc4'] != null) {
                                Line_Disc = data['Line_Disc1']+"%+"+data['Line_Disc2']+"%+"+data['Line_Disc3']+"%+"+data['Line_Disc4']+"%";
                            }else if(data['Line_Disc3'] != null){
                                Line_Disc = data['Line_Disc1']+"%+"+data['Line_Disc2']+"%+"+data['Line_Disc3']+"%";
                            }else if(data['Line_Disc2'] != null){
                                Line_Disc = data['Line_Disc1']+"%+"+data['Line_Disc2']+"%";
                            }else if(data['Line_Disc1'] != null){
                                Line_Disc = data['Line_Disc1']+"%";
                            }
                        }
                        let Line_SP = (data['Line_SP'] == 'Y') ? ["<i class='fas fa-check'></i>", "class='table-warning'"] : ["",""];
                        tBody += 
                            `<tr `+Line_SP[1]+`>
                                <td class='text-end'>`+(k+1)+`</td>
                                <td class='text-center'>`+data['ItemCode']+`</td>
                                <td class='text-center'>`+data['CodeBars']+`</td>
                                <td>`+data['ItemName']+`</td>
                                <td class='text-center'>`+data['WhsCode']+`</td>
                                <td class='text-end'>`+number_format(data['Quantity'],0)+`</td>
                                <td>`+data['UnitMsr']+`</td>
                                <td class='text-end'>`+number_format(data['GrandPrice'],2)+`</td>
                                <td class='text-center'>`+Line_Disc+`</td>
                                <td class='text-end'>`+number_format(data['UnitPrice'],2)+`</td>
                                <th class='text-end'>`+number_format(data['LineTotal'],2)+`</th>
                                <td class='text-center'>&nbsp;</td>
                            </tr>`;
                    });

                    $("#vd_comments").val(inval['Comments']);
                    $("#vd_AllTotal").val(number_format(AllTotal,2));
                    let DiscPcnt = (parseInt(inval['DiscPcnt']) == 0) ? [number_format(parseFloat(inval['DiscTotal']),2), "บาท"] : [number_format(parseFloat(inval['DiscPcnt']),2), "%"];
                    $("#vd_DiscPcnt").val(DiscPcnt[0]);
                    $("#vd_TypeDiscPcnt").html(DiscPcnt[1]);
                    $("#vd_Discount").val(number_format(parseFloat(inval['DocTotal'])-parseFloat(inval['VatSum']),2));
                    $("#vd_VatSum").val(number_format(parseFloat(inval['VatSum']),2));
                    $("#vd_Total").val(number_format(parseFloat(inval['DocTotal']),2));
                    $("#ItemListViewDoc tbody").html(tBody);
                    $("#ModalViewDoc").modal("show");
                });
            }
        });
    }else{
        $.ajax({
            url: "order_tracking/ajax.php?p=ViewDoc",
            type: "POST",
            data: { Type: Type, DocNum: DocNum },
            success: function(result) {
                let obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    let tHead = [
                        'เลขที่เอกสาร', 'สถานะเอกสาร',
                        'ชื่อลูกค้า', 'เลขที่ผู้เสียภาษี',
                        'วันที่ใบสั่งขาย', 'วันที่กำหนดส่ง',
                        'เงื่อนไขการชำระเงิน', '',
                        'ที่อยู่เปิดบิล', 'ที่อยู่จัดส่ง',
                        'พนักงานขาย', 'เอกสารอ้างอิง'
                    ];
    
                    let DataViewDoc = "";
                    for(let r = 0; r < tHead.length; r++) {
                        DataViewDoc += `
                        <div class="row p-0 pb-2">`;
                        DataViewDoc += `
                            <div class="col-lg d-flex" style='font-size: 14px;'>
                                <div style='width: 20%;' class='fw-bold'>`+tHead[r]+`</div>
                                <div style='width: 80%;'>`+inval['DataHead'][r]+`</div>
                            </div>`;
                            r++;
                            DataViewDoc += `
                            <div class="col-lg d-flex" style='font-size: 14px;'>
                                <div style='width: 20%;' class='fw-bold'>`+tHead[r]+`</div>
                                <div style='width: 80%;'>`+inval['DataHead'][r]+`</div>
                            </div>`;
                            DataViewDoc += `
                        </div>`;
                    }
                    $("#DataViewDoc").html(DataViewDoc);
                    
    
                    let tBody = "";
                    let AllTotal = 0;
                    $.each(inval['DataView'], function(k, data) {
                        AllTotal = AllTotal+parseFloat(data['LineTotal']);
                        let Line_Disc = "";
                        if(data['U_DiscP4'] != null && data['U_DiscP4'] != 0) {
                            Line_Disc = number_format(data['U_DiscP1'],2)+"%+"+number_format(data['U_DiscP2'],2)+"%+"+number_format(data['U_DiscP3'],2)+"%+"+number_format(data['U_DiscP4'],2)+"%";
                        }else if(data['U_DiscP3'] != null && data['U_DiscP3'] != 0){
                            Line_Disc = number_format(data['U_DiscP1'],2)+"%+"+number_format(data['U_DiscP2'],2)+"%+"+number_format(data['U_DiscP3'],2)+"%";
                        }else if(data['U_DiscP2'] != null && data['U_DiscP2'] != 0){
                            Line_Disc = number_format(data['U_DiscP1'],2)+"%+"+number_format(data['U_DiscP2'],2)+"%";
                        }else if(data['U_DiscP1'] != null && data['U_DiscP1'] != 0){
                            Line_Disc = number_format(data['U_DiscP1'],2)+"%";
                        }
                        let Line_SP = (data['LineStatus'] == 'O') ? [data['LineStatus'],"class='table-warning'"] : [data['LineStatus'],""];
    
                        tBody += 
                            `<tr `+Line_SP[1]+`>
                                <td class='text-end'>`+(k+1)+`</td>
                                <td class='text-center'>`+data['ItemCode']+`</td>
                                <td class='text-center'>`+data['CodeBars']+`</td>
                                <td>`+data['Dscription']+`</td>
                                <td class='text-center'>`+data['WhsCode']+`</td>
                                <td class='text-end'>`+number_format(data['Quantity'],0)+`</td>
                                <td>`+data['unitMsr']+`</td>
                                <td class='text-end'>`+number_format(data['PriceBefDi'],2)+`</td>
                                <td class='text-center'>`+Line_Disc+`</td>
                                <td class='text-end'>`+number_format(data['PriceAfVAT'],2)+`</td>
                                <th class='text-end'>`+number_format(data['LineTotal'],2)+`</th>
                                <td class='text-center'>&nbsp;</td>
                            </tr>`;
                    });
                    $("#ItemListViewDoc tbody").html(tBody);
                    
                    $("#vd_comments").val(inval['DataView'][0]['Comments']);
                    $("#vd_AllTotal").val(number_format(AllTotal,2));
                    let DiscPrcnt = (parseInt(inval['DataView'][0]['DiscPrcnt']) == 0) ? [number_format(parseFloat(inval['DiscTotal']),2), "บาท"] : [number_format(parseFloat(inval['DataView'][0]['DiscPrcnt']),2), "%"];
                    $("#vd_DiscPcnt").val(DiscPrcnt[0]);
                    $("#vd_TypeDiscPcnt").html(DiscPrcnt[1]);
                    $("#vd_Discount").val(number_format(parseFloat(inval['DataView'][0]['DocTotal'])-parseFloat(inval['DataView'][0]['VatSum']),2));
                    $("#vd_VatSum").val(number_format(parseFloat(inval['DataView'][0]['VatSum']),2));
                    $("#vd_Total").val(number_format(parseFloat(inval['DataView'][0]['DocTotal']),2));
                    $("#ModalViewDoc").modal("show");
                });
            }
        })
    }
}

$(document).ready(function() {
    GetOrder();
});