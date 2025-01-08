function GetSoOutstan() {
    $("#TableSoOutstan").dataTable().fnClearTable();
    $("#TableSoOutstan").dataTable().fnDraw();
    $("#TableSoOutstan").dataTable().fnDestroy();
    $("#TableSoOutstan").DataTable({
        "ajax": {
            url: "so_outstan/ajax.php?p=GetSoOutstan",
            type: "GET",
            async: false,
            dataType: "json",
            dataSrc: function (data) {
                return data[0]['SoOutstan'];
            }
        },
        "columns": [
            { "data": "No", class: "dt-body-right" },
            { "data": "Company", class: "" },
            { "data": "SO_DocNum", class: "dt-body-center" },
            { "data": "DocDate", class: "dt-body-center" },
            { "data": "CardCode", class: "dt-body-center" },
            { "data": "CardName", class: "" },
            { "data": "SlpName", class: "" },
            { "data": "DocTotal", class: "dt-body-right" },
        ],
        "columnDefs": [
            { "width": "4%", "targets": 0 },
            { "width": "10%", "targets": 1 },
            { "width": "13%", "targets": 2 },
            { "width": "13%", "targets": 3 },
            { "width": "10%", "targets": 4 },
            { "width": "20%", "targets": 5 },
            { "width": "20%", "targets": 6 },
            { "width": "10%", "targets": 7 }
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
        }]
    });
}

function ViewDoc(DocEntry,DataSite) {
    const Type = "ORDR";
    $.ajax({
        url: "so_outstan/ajax.php?p=ViewDoc",
        type: "POST",
        data: { Type: Type, DocEntry: DocEntry,DataSite: DataSite, },
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

$(document).ready(function() {
    GetSoOutstan();
});