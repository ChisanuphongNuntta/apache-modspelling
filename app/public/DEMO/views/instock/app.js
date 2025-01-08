function GetWhs() {
    $.ajax({
        url: "instock/ajax.php?p=GetWhs",
        type: "GET",
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let option = "";
                $.each(inval['DataWhs'], function(k, data) {
                    option += "<option value='"+data['WhsCode']+"'>"+data['WhsCode']+" - "+data['WhsName']+"</option>";
                })
                $("#WhsCode").append(option);
            })
        }
    })
}

function GetInstock() {
    const WhsCode = $("#WhsCode").val();
    const Getzero = $("#Getzero").is(":checked");
    $("#TableInstock").dataTable().fnClearTable();
    $("#TableInstock").dataTable().fnDraw();
    $("#TableInstock").dataTable().fnDestroy();
    $("#TableInstock").DataTable({
        "ajax": {
            url: "instock/ajax.php?p=GetInstock",
            type: "POST",
            data: { WhsCode: WhsCode, Getzero: Getzero },
            async: false,
            dataType: "json",
            dataSrc: function (data) {
                return data[0]['Instock'];
            }
        },
        "columns": [
            { "data": "ItemCode", class: "dt-body-center" },
            { "data": "CodeBars", class: "dt-body-center" },
            { "data": "ItemName", class: "" },
            { "data": "SalUnitMsr", class: "dt-body-center" },
            { "data": "OnHand", class: "dt-body-right" },
            { "data": "IsCommited", class: "dt-body-center" },
            { "data": "Pending", class: "dt-body-right" },
            { "data": "ForUse", class: "dt-body-right" },
            { "data": "Aging", class: "dt-body-center" },
            { "data": "OnOrder", class: "dt-body-right" },
        ],
        "columnDefs": [
            { "width": "9%", "targets": 0 },
            { "width": "10%", "targets": 1 },
            { "width": "%", "targets": 2 },
            { "width": "7%", "targets": 3 },
            { "width": "7%", "targets": 4 },
            { "width": "7%", "targets": 5 },
            { "width": "7%", "targets": 6 },
            { "width": "7%", "targets": 7 },
            { "width": "7%", "targets": 8 },
            { "width": "7%", "targets": 9 },
        ],
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "pageLength": 20,
        "ordering": true,
        "language": DTTB_TH,
        "dom": 'Bfrtip',
        "buttons": [{ "extend": 'excelHtml5',"footer": true, },]
    })
}

function DetailAvailable(ItemCode) {
    const WhsCode = $("#WhsCode").val();
    $.ajax({
        url: "instock/ajax.php?p=DetailAvailable",
        type: "POST",
        data: { ItemCode: ItemCode, WhsCode: WhsCode },
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let Data = "";
                $.each(inval['DetailAvailable'], function(k, data) {
                    Data += `
                        <tr>
                            <td class='text-center'><a href='javascript:void(0);' onclick="ViewDoc('ORDR',`+data['DocEntry']+`)">`+data['DocNum']+`</a></td>
                            <td class='text-center'>`+date_format(data['DocDate'], 'dmY')+`</td>
                            <td class='text-center'>`+data['CardCode']+`</td>
                            <td>`+data['CardName']+`</td>
                            <td>`+data['SlpName']+`</td>
                            <td class='text-center'>`+data['WhsCode']+`</td>
                            <td class='text-end'>`+number_format(data['Quantity'],0)+`</td>
                        </tr>`;
                })
                $("#TableDetailAvailable tbody").html(Data);
                $("#ModalDetailAvailable").modal("show");
            })
        }
    })
}

function ViewOnOrder(ItemCode) {
    $.ajax({
        url: "instock/ajax.php?p=ViewOnOrder",
        type: "POST",
        data: { ItemCode: ItemCode },
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let Data = "";
                $.each(inval['DataOnOrder'], function(k, data) {
                    Data += `
                        <tr>
                            <td class='text-center'>`+(k+1)+`</td>
                            <td class='text-center'>`+data['ItemCode']+`</td>
                            <td>`+data['Dscription']+`</td>
                            <td class='text-center'>`+date_format(data['DocDate'], 'dmY')+`</td>
                            <td class='text-center'>`+date_format(data['DocDueDate'], 'dmY')+`</td>
                            <td class='text-center'>`+data['DocNum']+`</td>
                            <td class='text-end'>`+number_format(data['Quantity'],0)+`</td>
                            <td>`+data['unitMsr']+`</td>
                            <td class='text-center'>`+data['WhsCode']+`</td>
                        </tr>`;
                })
                $("#TableViewOnOrder tbody").html(Data);
                $("#ModalViewOnOrder").modal("show");
            })
        }
    })
}

function ViewDoc(DocType, DocEntry) {
    $.ajax({
        url: "order_tracking/ajax.php?p=ViewDoc",
        type: "POST",
        data: { Type: DocType, DocNum: DocEntry },
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
    GetWhs();
    GetInstock();
});