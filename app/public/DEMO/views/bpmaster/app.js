function GetCardList() {
    $.ajax({
        url: "bpmaster/ajax.php?p=GetCardList",
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
               if(inval['Data'] != 'ERR') {
                    $("#txt_CardCode").selectpicker("destroy").html(inval['Data']).selectpicker();
               }
            });
        }
    });
}

function GetDetail() {
    let CardCode = $("#txt_CardCode").val();
    if(CardCode == "") {
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณาเลือกลูกค้าก่อนค้นหา");
        $("#alert_modal").modal("show");
    } else {
        $("#overlay").show();
        $.ajax({
            url: "bpmaster/ajax.php?p=GetDetail",
            type: "POST",
            data: { CardCode: CardCode },
            success: function(result) {
                $("#overlay").hide();
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    if(inval['Status'] == "OK") {
                        /*===== HEADER =====*/
                        $("#view_CardCode").html(inval['HEADER']['CardCode']);
                        $("#view_CardName").html(inval['HEADER']['CardName']);
                        $("#view_LicTradNum").html(inval['HEADER']['LicTradNum']);
                        $("#view_GroupName").html(inval['HEADER']['GroupName']);
                        $("#view_SlpName").html(inval['HEADER']['SlpName']);
                        $("#view_PymntGroup").html(inval['HEADER']['PymntGroup']);
                        $("#view_Address").html(inval['HEADER']['Address']);

                        let Contact = "";
                        Contact += (inval['HEADER']['Phone1'] == "") ? "" : inval['HEADER']['Phone1']+` `;
                        Contact += (inval['HEADER']['Phone2'] == "") ? "" : inval['HEADER']['Phone2']+` `;
                        Contact += (inval['HEADER']['Cellular'] == "") ? "" : inval['HEADER']['Cellular']+` `;

                        $("#view_Contact").html(Contact);

                        let CreditPcnt = (inval['HEADER']['CreditLine'] > 0) ? (inval['HEADER']['Balance'] / inval['HEADER']['CreditLine']) * 100 : 0;
                        CreditPcnt = (CreditPcnt > 100) ? 100 : CreditPcnt;
                        let PcntBg = "";
                        if(CreditPcnt == 100) {
                            PcntBg = "bg-danger";
                        } else if(CreditPcnt > 80 && CreditPcnt < 100) {
                            PcntBg = "bg-warning";
                        } else {
                            PcntBg = "bg-success";
                        }
                        $("#view_CreditPcnt").css({"width": CreditPcnt+"%"}).attr("aria-valuenow",CreditPcnt).html(number_format(inval['HEADER']['Balance'],0)+' / '+number_format(inval['HEADER']['CreditLine'],0)).removeClass("bg-danger bg-warning bg-success").addClass(PcntBg);
                        $("#view_CreditText").html(number_format(inval['HEADER']['Balance'],0)+' / '+number_format(inval['HEADER']['CreditLine'],0))

                        /*===== SALE AMOUNT HISTORY =====*/
                        CurrTotal = 0;
                        PrevTotal = 0;
                        for(m = 1; m <= 12; m++) {
                            $("#data_cm"+m).html(number_format(inval['CURR_SALE']['M'+m],0));
                            $("#data_pm"+m).html(number_format(inval['PREV_SALE']['M'+m],0));

                            CurrTotal = CurrTotal + parseFloat(inval['CURR_SALE']['M'+m]);
                            PrevTotal = PrevTotal + parseFloat(inval['PREV_SALE']['M'+m]);
                        }
                        $("#data_cm13").html(number_format(CurrTotal,0)).addClass("fw-bolder");
                        $("#data_pm13").html(number_format(PrevTotal,0)).addClass("fw-bolder");
                        $("#data_cm14").html(number_format(CurrTotal/12,0)).addClass("fw-bolder");
                        $("#data_pm14").html(number_format(PrevTotal/12,0)).addClass("fw-bolder");

                        /*===== BILL OVERDUE =====*/
                        let DueTbody = "";
                        if(inval['OVERDUE'].length == 0) {
                            DueTbody = `<tr><td colspan="8" class="text-center">ไม่มีข้อมูลหนี้เกินกำหนด :)</td></tr>`;
                        } else {
                            let No = 1;
                            $.each(inval['OVERDUE'], function(k, data) {
                                let DocDate = new Date(data['DocDate']);
                                    DocDate = DocDate.getDate().toString().padStart(2,"0")+"/"+(DocDate.getMonth()+1).toString().padStart(2,"0")+"/"+DocDate.getFullYear();
                                let DocDueDate = new Date(data['DocDueDate']);
                                    DocDueDate = DocDueDate.getDate().toString().padStart(2,"0")+"/"+(DocDueDate.getMonth()+1).toString().padStart(2,"0")+"/"+DocDueDate.getFullYear();
                                
                                let trClass = (data['Aging'] > 0) ? " class='table-danger'" : "";

                                DueTbody += 
                                    `<tr`+trClass+`>
                                        <td class="text-center">`+No+`</td>
                                        <td class="text-center">`+data['DocNum']+`</td>
                                        <td class="text-center">`+DocDate+`</td>
                                        <td class="text-center">`+DocDueDate+`</td>
                                        <td class="fw-bolder text-end">`+data['Aging']+`</td>
                                        <td>`+data['CardCode']+` `+data['CardName']+`</td>
                                        <td class="fw-bolder text-end">`+number_format(data['DocTotal'],2)+`</td>
                                        <td class="fw-bolder text-end text-danger">`+number_format(data['DocTotal']-data['PaidToDate'],2)+`</td>
                                    </tr>`;
                                No++;
                            });
                        }
                        $("#view_BillOverDue tbody").html(DueTbody);

                        /*===== HISMEETING =====*/
                        GetHisMeeting(CardCode);
                    } else {
                        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                        $("#alert_body").html("Error Code: "+inval['ErrMsg']);
                        $("#alert_modal").modal("show");
                    }
                });
            }
        })
    }
}

function GetHisMeeting(CardCode) {
    $("#TableMeeting").dataTable().fnClearTable();
    $("#TableMeeting").dataTable().fnDraw();
    $("#TableMeeting").dataTable().fnDestroy();
    
    $("#TableMeeting").DataTable({
        "ajax": {
            url: "bpmaster/ajax.php?p=GetHisMeeting",
            type: "POST",
            data: { CardCode: CardCode },
            async: false,
            dataType: "json",
            dataSrc: function (data) {
                return data[0]['HisMeeting'];
            }
        },
        "columns": [
            { "data": "ActualStart", class: "dt-body-center"},
            { "data": "PlanDetail", class: ""},
            { "data": "ActualDetail", class: ""},
            { "data": "SlpName", class: ""},
            { "data": "View", class: "dt-body-center"}
        ],
        "columnDefs": [
            { "width": "15%", "targets": 0 },
            { "width": "30%", "targets": 1 },
            { "width": "30%", "targets": 2 },
            { "width": "20%", "targets": 3 },
            { "width": "5%", "targets": 4 },
        ],
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "pageLength": 10,
        "ordering": false,
        "language": DTTB_TH
    });
}

$(document).ready(function() {
    GetCardList();
});






// function CallStock() {
//     const structureData = 
//         `<table class='table table-sm table-bordered' style='font-size: 13px;'>
//             <thead class='table-primary'>
//                 <tr class='text-center'>
//                     <th>คลังสินค้า</th>
//                     <th>จำนวนคงคลัง</th>
//                 </tr>
//             </thead>
//             <tbody>
//                 <tr>
//                     <td class='text-center'>DDS</td>
//                     <td class='text-end'>245</td>
//                 </tr>
//                 <tr>
//                     <td class='text-center'>POS</td>
//                     <td class='text-end'>73</td>
//                 </tr>
//             </tbody>
//         </table>`;
//     $("#ModalShowData .modal-dialog").addClass("");
//     $("#ModalShowData .modal-title").html("<i class='fas fa-warehouse'></i> สินค้าคงคลัง");
//     $("#ModalShowData .modal-body").html(structureData);
//     $("#ModalShowData").modal("show");
// }

// function HisItem10() {
//     $.ajax({
//         url: "bpmaster/ajax.php?p=HisItem10",
//         type: "GET",
//         success: function(result) {
//             var obj = jQuery.parseJSON(result);
//             $.each(obj, function(key, inval) {
//                 const structureData = 
//                     `<table class='table table-sm table-bordered' style='font-size: 13px;'>
//                         <thead class='table-primary'>
//                             <tr class='text-center'>
//                                 <th>เลขที่บิล</th>
//                                 <th>วันที่สั่งซื้อ</th>
//                                 <th>พนักงานขาย</th>
//                                 <th>ยอดขาย (บาท)</th>
//                             </tr>
//                         </thead>
//                         <tbody>`+inval['Data']+`</tbody>
//                     </table>`;
//                 $("#ModalShowData .modal-dialog").addClass("modal-lg");
//                 $("#ModalShowData .modal-title").html("ประวัติการสั่งซื้อสินค้า (10 รายการล่าสุด)");
//                 $("#ModalShowData .modal-body").html(structureData);
//                 $("#ModalShowData").modal("show");
//             });
//         }
//     })
// }