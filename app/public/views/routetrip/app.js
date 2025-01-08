function GetData() {
    $.ajax({
        url: "routetrip/ajax.php?p=GetData",
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let SlpTxt = CardTxt = `<option value='' selected disabled>กรุณาเลือก</option>`;
                let DeptName = "";
                for(i = 0; i < inval['SALE_EMP'].length; i++) {
                    if(DeptName != inval['SALE_EMP'][i]['DeptName']) {
                        DeptName = inval['SALE_EMP'][i]['DeptName'];
                        if(DeptName == "") { SlpTxt += `</optgroup>`; }
                        SlpTxt += `<optgroup label="`+DeptName+`">`;
                    }
                    SlpTxt += `<option value="`+inval['SALE_EMP'][i]['uKey']+`">`+inval['SALE_EMP'][i]['SalesName']+`</small></option>`;
                };
                $("#txt_SlpCode").selectpicker("destroy").html(SlpTxt).val(inval['UKEY']).change().selectpicker();

                for(i = 0; i < inval['CARD_LIST'].length; i++) {
                    CardTxt += `<option value="`+inval['CARD_LIST'][i]['CardCode']+`">`+inval['CARD_LIST'][i]['CardCode']+` | `+inval['CARD_LIST'][i]['CardName']+`</option>`;
                }
                $("#txt_CardCode").selectpicker("destroy").html(CardTxt).selectpicker();
            });
        }
    })
}

function RenderView(ViewYear, ViewMonth, ViewType) {
    let DataTrip   = "";
    let ArrDate    = new Date();
    let Today      = ArrDate.getDate()+"-"+(ArrDate.getMonth()+1)+"-"+ArrDate.getFullYear();
    let DayInMonth = new Date(ViewYear, ViewMonth, 0).getDate();
    switch(ViewType) {
        case "GRID":
            DataTrip += 
                `<div class='calendar mt-2'>
                    <ol class='day-names list-unstyled'>
                        <li class='font-weight-bold text-center text-danger'>อาทิตย์</li>
                        <li class='font-weight-bold text-center'>จันทร์</li>
                        <li class='font-weight-bold text-center'>อังคาร</li>
                        <li class='font-weight-bold text-center'>พุธ</li>
                        <li class='font-weight-bold text-center'>พฤหัสบดี</li>
                        <li class='font-weight-bold text-center'>ศุกร์</li>
                        <li class='font-weight-bold text-center'>เสาร์</li>
                    </ol> `;

            let DayOfWeek = new Date(ViewYear+'-'+ViewMonth+'-01').getDay();
            DataTrip += 
                `<ol class='days list-unstyled'>`;
                    for(o = 1; o <= DayOfWeek; o++) { DataTrip += `<li class='outside'>&nbsp;</li>`; }
                    for(let day = 1; day <= DayInMonth; day++) {
                        let ArrloopDate = new Date(ViewYear+'-'+ViewMonth+'-'+day);
                        let loopDate = ArrloopDate.getDate()+"-"+(ArrloopDate.getMonth()+1)+"-"+ArrloopDate.getFullYear();

                        let ArrSunday = new Date(ViewYear+'-'+ViewMonth+'-'+day).getDay();
                        let ClsSunday = (ArrSunday == 0) ? "text-danger" : "";
                        let ClsToday = (loopDate == Today) ? "today" : "";
                        let StrDate  = ArrloopDate.getFullYear()+"-"+(ArrloopDate.getMonth()+1).toString().padStart(2,"0")+"-"+ArrloopDate.getDate().toString().padStart(2,"0");

                        let DataPlan = "";
                        DataTrip += 
                            `<li class='`+ClsToday+`' id='txt_`+StrDate+`'>
                                <div class='date text-end `+ClsSunday+`' style='cursor: default !important;'>`+day+`</div>
                                `+DataPlan+`
                            </li>`;
                        }
            DataTrip += `<li class='outside'>&nbsp;</li>
                    </ol>
                </div>`;
            $("#view_worktrip").html(DataTrip);
        break;
    }
}

function GetTrip() {
    const Year  = $("#txt_Year").val();
    const Month = $("#txt_Month").val();
    const Sale =  $("#txt_SlpCode").val();
    const View =  $("#txt_View").val();

    let DataTrip = "";
    let ArrDate    = new Date();
    let Today      = ArrDate.getDate()+"-"+(ArrDate.getMonth()+1)+"-"+ArrDate.getFullYear();
    let DayInMonth = new Date(Year, Month, 0).getDate();

    if(View == 'GRID') {
        RenderView(Year, Month, View);
        $.ajax({
            url: "routetrip/ajax.php?p=GetTrip",
            type: "POST",
            data: { y: Year, m: Month, uKey: Sale },
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    $.each(inval['DataTrip'], function(k, data) {
                        let DateLength = inval['DataTrip'][k].length;
                        let DeteLoop   = (DateLength < 2) ? DateLength : 2 ;
                        for(i = 0; i < DeteLoop; i++) {
                            let bg_event = "";
                            switch(inval['DataTrip'][k][i]['TripStatus']) {
                                case "1": bg_event = "bg-gray"; break;
                                case "2":
                                case "3": bg_event = "bg-gray"; break;
                            }
                            $("#txt_"+k).append(`<div class="event `+bg_event+`" onclick="DPlan('`+k+`');">`+inval['DataTrip'][k][i]['PlanTime']+` | `+inval['DataTrip'][k][i]['CardName']+`</div>`);
                        }
                        if(DateLength > 2) {
                            let ListMore = DateLength - 2;
                            $("#txt_"+k).append(`<div class="event event-more text-center" onclick="DPlan('`+k+`');">+`+ListMore+` รายการ</div>`);
                        }
                    });
                });
            }
        })
    } else {
        $.ajax({
            url: "routetrip/ajax.php?p=GetTripLIST",
            type: "POST",
            data: { Year: Year, Month: Month, Sale: Sale },
            success: function(result) {
                let obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    let DataTripBody = "";
                    for(let day = 1; day <= DayInMonth; day++) {
                        let ArrloopDate = new Date(Year+'-'+Month+'-'+day);
                        let loopDate = ArrloopDate.getDate()+"-"+(ArrloopDate.getMonth()+1)+"-"+ArrloopDate.getFullYear();

                        let ArrSunday = new Date(Year+'-'+Month+'-'+day).getDay();
                        let ClsSunday = (ArrSunday == 0) ? "table-danger text-danger" : "";

                        let ClsToday = (loopDate == Today) ? "today text-success" : "";

                        if(typeof(inval['DataLIST'][day]) != "undefined") {
                            $.each(inval['DataLIST'][day], function(k, DataLIST) {
                                let SetRowSpan = (k == 0) ? "<td rowspan='"+inval['DataLIST'][day].length+"' class='text-center "+ClsSunday+" "+ClsToday+"'>"+day+"</td>" : "";
                                DataTripBody += `
                                <tr class='`+ClsSunday+` `+ClsToday+`'>
                                    `+SetRowSpan+`
                                    <td class='text-center'>`+(k+1)+`</td>
                                    <td>`+DataLIST['CardName']+`</td>
                                    <td>`+DataLIST['Province']+`</td>
                                    <td>`+DataLIST['DetailPlan']+`</td>
                                    <td class='text-end'>`+DataLIST['SomeTotal']+`</td>
                                    <td class='text-end'>`+DataLIST['Total']+`</td>
                                    <td class='text-end'>`+DataLIST['Bill']+`</td>
                                    <td class='text-center'>`+DataLIST['Status']+`</td>
                                    <td class='text-center'>`+DataLIST['Setting']+`</td>
                                </tr>
                                `;
                            });
                        }else{
                            DataTripBody += `
                                <tr class='`+ClsSunday+` `+ClsToday+`'>
                                    <td class='text-center `+ClsSunday+` `+ClsToday+`'>`+day+`</td>
                                    <td class='text-center'></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class='text-end'></td>
                                    <td class='text-end'></td>
                                    <td class='text-end'></td>
                                    <td class='text-center'></td>
                                    <td class='text-center'></td>
                                </tr>
                            `;
                        }
                    }

                    DataTrip += `
                        <div class='table-responsive'>
                            <table class='table table-sm table-hover table-bordered'>
                                <thead>
                                    <tr class='text-center'>
                                        <th>วันที่</th>
                                        <th>จุดที่</th>
                                        <th>ชื่อร้านค้า</th>
                                        <th>จังหวัด</th>
                                        <th>รายละเอียดแผนงาน</th>
                                        <th>ประมาณการยอดขาย (บาท)</th>
                                        <th>ยอดขาย (บาท)</th>
                                        <th>บิลรอเรียกเก็บ (บาท)</th>
                                        <th>สถานะ</th>
                                        <th><i class="fas fa-cog fa-fw fa-1x"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    `+DataTripBody+`
                                </tbody>
                            </table>
                        <div>
                    `;
                    $("#view_worktrip").html(DataTrip);
                })
            }
        });
    }
}

function AddNewPlan(x) {
    const PostData = {};
    function AddPostData(key, value) {
        if(!PostData[key]) {
            PostData[key] = value;
        }
    }
    if(x == "show") {
        $("#ModalNewPlan").modal("show");
    }else{
        // Add data
        let S_Date = $("#txt_PlanStartDate").val();
        let S_Time = $("#txt_PlanStartTime").val();
        let E_Date = S_Date;
        let E_Time = $("#txt_PlanEndTime").val();
        let txt_PlanStart = S_Date+` `+S_Time+`:00`;
        let txt_PlanEnd   = E_Date+` `+E_Time+`:00`;
            
            S_Date = S_Date.split("-");
            S_Time = S_Time.split(":");
            E_Date = E_Date.split("-");
            E_Time = E_Time.split(":");

        let S_Full = new Date(S_Date[0],S_Date[1]-1,S_Date[2],S_Time[0],S_Time[1], 0 , 0);
        let E_Full = new Date(E_Date[0],E_Date[1]-1,E_Date[2],E_Time[0],E_Time[1], 0 , 0);
        let PlanDiff = (E_Full - S_Full);
            PlanDiff = Math.round(((PlanDiff % 86400000) % 3600000) / 60000);

        if(PlanDiff < 0) {
            $("#txt_PlanStartDate, #txt_PlanStartTime, #txt_PlanEndDate, #txt_PlanEndTime").addClass("is-invalid");
            $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
            $("#alert_body").html("กรุณาเลือกวันที่เริ่มต้นและวันที่สิ้นสุดให้ถูกต้อง");
            $("#alert_modal").modal("show");
        } else {
            let txt_CardCode     = $("#txt_CardCode").val();
            let txt_CardName     = $("#txt_CardCode option:selected").text();
                txt_CardName     = txt_CardName.split(" | ");
            let txt_ContactName  = $("#txt_ContactName").val();
            let txt_ContactPhone = $("#txt_ContactPhone").val();
            let txt_ContactEmail = $("#txt_ContactEmail").val();
            let txt_ContactLINE  = $("#txt_ContactLINE").val();
            let txt_PlanDetail   = $("#txt_PlanDetail").val();

            AddPostData("txt_CardCode",     txt_CardCode);
            AddPostData("txt_CardName",     txt_CardName[1]);
            AddPostData("txt_PlanStart",    txt_PlanStart);
            AddPostData("txt_PlanEnd",      txt_PlanEnd);
            AddPostData("txt_ContactName",  txt_ContactName);
            AddPostData("txt_ContactPhone", txt_ContactPhone);
            AddPostData("txt_ContactEmail", txt_ContactEmail);
            AddPostData("txt_ContactLINE",  txt_ContactLINE);
            AddPostData("txt_PlanDetail",   txt_PlanDetail);

            const Form_Data = new FormData();
            for(let key in PostData) { Form_Data.append(key, PostData[key]); }

            $("#overlay").show();
            $.ajax({
                url: "routetrip/ajax.php?p=SavePlan",
                type: "POST",
                data: Form_Data,
                processData: false,
                contentType: false,
                success: function(result) {
                    $("#overlay").hide();
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(key, inval) {
                        if(inval['Status'] == "OK") {
                            $("#alert_header").html("<i class=\"far fa-check-circle fa-fw fa-lg text-success\"></i> เสร็จสิ้น !");
                            $("#alert_body").html("บันทึกสำเร็จ");
                            $("#alert_modal").modal("show");
                            $(document).off("click","button.btn-confirm").on("click","button.btn-confirm", function(e) {
                                e.preventDefault();
                                window.location.reload();
                            });
                        }
                    });
                }
            });
        }
    }
}

function HistoryTrip() {
    let UserCheck = "วรวิทย์ รรรธรร (วิน)";
    $("#ModalHistoryTrip .modal-title").html("<i class='fas fa-history fa-fw fa-1x'></i> ประวัติการเข้าพบ ผู้เช็คอิน: "+UserCheck);
    let DataHistoryTrip = `
        <tr>
            <td class="text-center">06/12/2023<br>เวลา 15:11 น.</td>
            <td>C-00619 ส.ฮงหลี</td>
            <td>เสนอโปรสินค้ารายการอื่นทางไลน์</td>
            <td class="text-center"><span class="badge w-100 p-2 text-white MeetType1"><i class="fas fa-street-view fa-fw fa-1x"></i> เข้าพบ (ในพื้นที่)</span></td>
            <td class="text-center"><a href="javascript:void(0);" onclick="CheckInReport()"><i class="fas fa-file-alt fa-fw fa-1x"></i></a></td>
        </tr>
    `;
    $("#TableHistoryTrip tbody").html(DataHistoryTrip);
    $("#ModalHistoryTrip").modal("show");
}

function DPlan(D) {
    let Day = D.split("-").join('/');
    $("#ModalDPlan .modal-title").html("<i class='fas fa-tasks fa-fw fa-1x'></i> แผนการเข้าพบประจำวันที่ "+Day);

    let uKey = $("#txt_SlpCode").val();

    $.ajax({
        url: "routetrip/ajax.php?p=GetPlan",
        type: "POST",
        
    })
    let DataTableDplan = `
        <tr>
            <td class="text-right">1</td>
            <td>C-02856 วิไลพันธ์พานิช</td>
            <td>เสนอโปรสินค้ารายการอื่นทางไลน์</td>
            <td class="text-right">0</td>
            <td class="text-right"><span>0.00</span></td>
            <td class="text-right"><a href="javascript:void(0);" onclick="GetOpenIV('');">-</a></td>
            <td><span class="badge w-100 p-2 text-white text-center MeetType1"><i class="fas fa-street-view fa-fw fa-1x"></i></span></td>
            <td class="text-center">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="inside">
                        <i class="fas fa-cog fa-fw fa-1x"></i>
                    </button>
                    <ul class="dropdown-menu position-fixed">
                        <li><a class="dropdown-item disabled" href="javascript:void(0);" onclick="EditTrip()"><i class="fas fa-edit fa-fw fa-lg"></i> แก้ไขแผนงาน</a></li>
                        <li><a class="dropdown-item" href="#" target="_blank"><i class="fas fa-directions fa-fw fa-lg text-success"></i> นำทาง</a></li>
                        <li><a class="dropdown-item disabled" href="javascript:void(0);" onclick="CheckIn('')"><i class="fas fa-map-marker-alt fa-fw fa-lg text-primary"></i> เช็คอิน</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="CheckInReport()"><i class="fas fa-file-alt fa-fw fa-lg"></i> รายงานการเข้าพบ</a></li>
                    </ul>
                </div>
            </td>
        </tr>`;
    $("#TableDPlan tbody").html(DataTableDplan);
    $("#ModalDPlan").modal("show");
}

function CheckInReport() {
    $("#ModalCheckInReport").modal("show");

    let inval = "";
    inval['chk_lat'] = 15.295658600000000;
    inval['chk_lon'] = 100.183501100000000;
    inval['plan_lat'] = 15.301777514333400;
    inval['plan_lon'] = 100.183947664278000;

    var map = new longdo.Map({
        placeholder: document.getElementById("RptCheckInMaps"),
        lastview: false,
        language: 'th',
        ui: longdo.UiComponent.Mobile
    });

    map.Layers.setBase(longdo.Layers.GRAY);
    map.zoom(15,true);
    map.zoomRange({ min: 10, max: 20 });
    map.location({ lon: inval['chk_lon'], lat: inval['chk_lat'] }, true);

    /* CheckIn Marker */
    var CheckPin = new longdo.Marker({ lon: inval['chk_lon'], lat: inval['chk_lat'] },{ icon: { html: '<i class=\'fas fa-male fa-4x\' style=\'color: #fc0380;\'></i>', offset: { x: 9, y: 48 } }, weight: 999 });
    map.Overlays.add(CheckPin);

    if(inval['chk_lon'].length != 0 && inval['chk_lat'].length != 0) {
        var StorePin = new longdo.Marker({ lon: inval['plan_lon'], lat: inval['plan_lat'] },{ icon: { html: '<i class=\'fas fa-map-marker-alt fa-2x text-primary\'></i>', offset: { x: 9, y: 24 } }, weight: 999 });
        map.Overlays.add(StorePin);

        var SafeZone = new longdo.Circle({
            lon: inval['plan_lon'], lat: inval['plan_lat']
        }, 0.0465, {
            lineWidth: 2,
            lineColor: 'rgba(128,252,3,0.8)',
            fillColor: 'rgba(128,252,3,0.25)'
        });
        map.Overlays.add(SafeZone);
        var LineDistance = new longdo.Polyline([CheckPin.location(),StorePin.location()],{ lineColor: "rgba(154,17,24,1)", lineWidth: 2, lineStyle: longdo.LineStyle.Dashed });
        map.Overlays.add(LineDistance);
    }

    $("#ModalCheckInReport").on("shown.bs.modal", function () {
        map.resize();
    });
}

$(document).ready(function() {
    GetData();
});