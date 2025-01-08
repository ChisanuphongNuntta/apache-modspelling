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
                    let LastMonth = ((ViewMonth-1) != 0) ? (ViewMonth-1) : 12;
                    let LastYear = ((ViewMonth-1) != 0) ? (ViewYear-1) : ViewYear;
                    let LastDayInMonth = (new Date(LastYear, LastMonth, 0).getDate()-DayOfWeek)+1;
                    for(o = 1; o <= DayOfWeek; o++) { 
                        DataTrip += `
                            <li class='date outside text-end'>
                                <div class='date text-end' style='cursor: default !important;'>`+LastDayInMonth+`</div>
                            </li>
                        `; 
                        LastDayInMonth++; 
                    }
                    let ArrSunday = "";
                    for(let day = 1; day <= DayInMonth; day++) {
                        let ArrloopDate = new Date(ViewYear+'-'+ViewMonth+'-'+day);
                        let loopDate = ArrloopDate.getDate()+"-"+(ArrloopDate.getMonth()+1)+"-"+ArrloopDate.getFullYear();

                        ArrSunday = new Date(ViewYear+'-'+ViewMonth+'-'+day).getDay();
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

                    let DayPMonth = 0;
                    for(d = ArrSunday; d <= 5; d++) { 
                        DayPMonth++;
                        DataTrip += `
                            <li class='date outside text-end'>
                                <div class='date text-end' style='cursor: default !important;'>`+DayPMonth+`</div>
                            </li>
                        `; 
                    }

                    DataTrip += `
                    </ol>
                </div>`;
        break;
        case "LIST":
            DataTrip =
                `<div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover" id="TripList" style="font-size: 13px;">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2" width="5%">ลำดับ</th>
                                <th rowspan="2" width="7.5%">วันที่วางแผน</th>
                                <th rowspan="2" width="8%">เวลา</th>
                                <th rowspan="2">ชื่อลูกค้า</th>
                                <th rowspan="2" width="12.5%">วันที่เข้าพบ</th>
                                <th colspan="5">สถานะการเข้าพบ</th>
                                <th colspan="3">รัศมีการเข้าพบ</th>
                                <th rowspan="2" width="4%"><i class="fas fa-cogs fa-fw fa-1x"></i></th>
                            </tr>
                            <tr>
                                <th width="4.5%">ยังไม่<br/>เข้าพบ</th>
                                <th width="4.5%">เข้าพบ<br/>ก่อนแผน</th>
                                <th width="4.5%">เข้าพบ<br/>ตามแผน</th>
                                <th width="4.5%">เข้าพบ<br/>หลังแผน</th>
                                <th width="4.5%">เข้าพบ<br/>ไม่มีแผน</th>
                                <th width="4.5%">ใน<br/>รัศมี</th>
                                <th width="4.5%">นอก<br/>รัศมี</th>
                                <th width="4.5%">ไม่มี<br/>พิกัด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" colspan="15">ไม่พบข้อมูล :(</td>
                            </tr>
                        </tbody>
                    </table>
                </div>`;
        break;
    }
    $("#view_worktrip").html(DataTrip);
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

    RenderView(Year, Month, View);
    $.ajax({
        url: "routetrip/ajax.php?p=GetTrip",
        type: "POST",
        data: { y: Year, m: Month, uKey: Sale, v: View },
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                if(View == "GRID") {
                    $.each(inval['DataTrip'], function(k, data) {
                        let DateLength = inval['DataTrip'][k].length;
                        let DeteLoop   = (DateLength < 2) ? DateLength : 2 ;
                        for(i = 0; i < DeteLoop; i++) {
                            let bg_event = "";
                            switch(inval['DataTrip'][k][i]['TripStatus']) {
                                case "1": bg_event = "bg-gray"; break;
                                case "2": bg_event = "bg-success"; break;
                                case "3": bg_event = "bg-info"; break;
                            }
                            $("#txt_"+k).append(`<div class="event `+bg_event+`" onclick="DPlan('`+k+`');">`+inval['DataTrip'][k][i]['PlanTime']+` | `+inval['DataTrip'][k][i]['CardName']+`</div>`);
                        }
                        if(DateLength > 2) {
                            let ListMore = DateLength - 2;
                            $("#txt_"+k).append(`<div class="event event-more text-center" onclick="DPlan('`+k+`');">+`+ListMore+` รายการ</div>`);
                        }
                    });
                } else {
                    $.each(inval['DataTrip'], function(k, data) {
                        let PlanList = "";
                        let No = 1;
                        for(i = 0; i < inval['DataTrip'].length; i++) {
                            let [SH,SM,SS] = inval['DataTrip'][i]['Plan_S'].split(":");
                            let [EH,EM,ES] = inval['DataTrip'][i]['Plan_E'].split(":");
                            let S_TIME = SH+":"+SM;
                            let E_TIME = EH+":"+EM;
                            let Actual_D = (inval['DataTrip'][i]['Actual_D'] == null) ? "" : date_format(inval['DataTrip'][i]['Actual_D'],'DMYHM');

                            let Chk_None   = (inval['DataTrip'][i]['Chk_None'] == "0")   ? "" : "<i class='fas fa-check fa-fw fa-lg'></i>"; 
                            let Chk_BFP    = (inval['DataTrip'][i]['Chk_BFP'] == "0")    ? "" : "<i class='fas fa-check fa-fw fa-lg'></i>"; 
                            let Chk_PLN    = (inval['DataTrip'][i]['Chk_PLN'] == "0")    ? "" : "<i class='fas fa-check fa-fw fa-lg'></i>"; 
                            let Chk_ATP    = (inval['DataTrip'][i]['Chk_ATP'] == "0")    ? "" : "<i class='fas fa-check fa-fw fa-lg'></i>"; 
                            let Chk_NOPLAN = (inval['DataTrip'][i]['Chk_NOPLAN'] == "0") ? "" : "<i class='fas fa-check fa-fw fa-lg'></i>"; 
                            let In_Range   = (inval['DataTrip'][i]['In_Range'] == "0")   ? "" : "<i class='fas fa-check fa-fw fa-lg'></i>"; 
                            let Out_Range  = (inval['DataTrip'][i]['Out_Range'] == "0")  ? "" : "<i class='fas fa-check fa-fw fa-lg'></i>"; 
                            let No_Range   = (inval['DataTrip'][i]['No_Range'] == "0")   ? "" : "<i class='fas fa-check fa-fw fa-lg'></i>"; 

                            let RowOpt = "";
                            switch(inval['DataTrip'][i]['TripStatus']) {
                                case "1": RowOpt = `<a href="javascript:void(0);" onclick="CheckIn(`+inval['DataTrip'][i]['TripID']+`)"><i class="fas fa-map-marker-alt fa-fw fa-lg"></i></a>`; break;
                                case "2":
                                case "3": RowOpt = `<a href="javascript:void(0);" onclick="CheckInReport(`+inval['DataTrip'][i]['TripID']+`)"><i class="fas fa-file-alt fa-fw fa-lg"></i></a>`; break;
                            }

                            PlanList +=
                                `<tr>
                                    <td class="text-end">`+number_format(No,0)+`</td>
                                    <td class="text-center">`+date_format(inval['DataTrip'][i]['Plan_D'],'DMY')+`</td>
                                    <td class="text-center">`+S_TIME+` - `+E_TIME+`</td>
                                    <td>`+inval['DataTrip'][i]['CardCode']+` | `+inval['DataTrip'][i]['CardName']+`</td>
                                    <td class="text-center">`+Actual_D+`</td>
                                    <td class="text-center">`+Chk_None+`</td>
                                    <td class="text-center">`+Chk_BFP+`</td>
                                    <td class="text-center">`+Chk_PLN+`</td>
                                    <td class="text-center">`+Chk_ATP+`</td>
                                    <td class="text-center">`+Chk_NOPLAN+`</td>
                                    <td class="text-center">`+In_Range+`</td>
                                    <td class="text-center">`+Out_Range+`</td>
                                    <td class="text-center">`+No_Range+`</td>
                                    <td class="text-center">`+RowOpt+`</td>
                                </tr>`;
                            No++;
                        }
                        $("#TripList tbody").html(PlanList);
                    });
                }
            });
        }
    });
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
        } else if($("#txt_CardCode").val() == null || $("#txt_CardCode").val() == "") {
            $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
            $("#alert_body").html("กรุณาเลือกลูกค้าก่อนทำการบันทึก");
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

function DPlan(D) {
    let Day = D.split("-").join('/');
    $("#ModalDPlan .modal-title").html("<i class='fas fa-tasks fa-fw fa-1x'></i> แผนการเข้าพบประจำวันที่ "+Day);

    let SlpuKey = $("#txt_SlpCode").val();
    $.ajax({
        url: "routetrip/ajax.php?p=GetPlan",
        type: "POST",
        data: { Date: D, uKey: SlpuKey },
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    let PlanTbody = "";
                    let No = 1;
                    for(i = 0; i < inval['DPLAN'].length; i++) {
                        let [SH, SM, SS] = inval['DPLAN'][i]['S_Time'].split(':');
                        let [EH, EM, ES] = inval['DPLAN'][i]['E_Time'].split(':');
                        let txt_status = "";

                        let PlanLat  = inval['DPLAN'][i]['PlanLat'];
                        let PlanLon  = inval['DPLAN'][i]['PlanLon'];

                        let dis_navi = "";
                        if(PlanLat != "" && PlanLon != "") {
                            navi_dis  = "";
                            navi_href = "https://maps.google.com/?q="+PlanLat+","+PlanLon;
                        } else {
                            navi_dis  = "disabled";
                            navi_href = "javascript:void(0);";
                        }

                        chck_dis = ckdt_dis = cncl_dis = "disabled";
                        switch(inval['DPLAN'][i]['TripStatus']) {
                            case "0": txt_status = "<span class='badge py-2 w-100 bg-secondary'><i class='fas fa-ban fa-fw fa-lg'></i> ยกเลิก</span>"; break;
                            case "1":
                                chck_dis = cncl_dis = "";
                                txt_status = "<span class='badge py-2 w-100 bg-info'><i class='fas fa-clock fa-fw fa-lg'></i> รอเข้าพบ</span>";
                                break;
                            case "2":
                                ckdt_dis = cncl_dis = "";
                                txt_status = "<span class='badge py-2 w-100 bg-success'><i class='fas fa-map-marker fa-fw fa-lg'></i> เช็คอินแล้ว</span>";
                                break;
                            case "3":
                                ckdt_dis = cncl_dis = "";
                                txt_status = "<span class='badge py-2 w-100 bg-white text-success'><i class='fas fa-check-circle fa-fw fa-lg'></i> เสร็จสมบูรณ์</span>";
                                break;
                        }
                        PlanTbody +=
                            `<tr>
                                <td class="text-end">`+number_format(No,0)+`</td>
                                <td class="text-center">`+SH+`:`+SM+` - `+EH+`:`+EM+`</td>
                                <td>`+inval['DPLAN'][i]['CardCode']+` | `+inval['DPLAN'][i]['CardName']+`</td>
                                <td>`+inval['DPLAN'][i]['PlanDetail']+`</td>
                                <td class="text-center">`+txt_status+`</td>
                                <td class="text-center">
                                    <div class="dropdown" style="position: static !important;">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="inside">
                                            <i class="fas fa-cog fa-fw fa-1x"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                        <li><a class="dropdown-item `+navi_dis+`" href="`+navi_href+`" target="_blank"><i class="fas fa-directions fa-fw fa-lg text-success"></i> นำทาง</a></li>
                                        <li><a class="dropdown-item `+chck_dis+`" href="javascript:void(0);" onclick="CheckIn(`+inval['DPLAN'][i]['TripID']+`)"><i class="fas fa-map-marker-alt fa-fw fa-lg"></i> เช็คอิน</a></li>
                                        <li><a class="dropdown-item `+cncl_dis+`" href="javascript:void(0);" onclick="CancelTrip(`+inval['DPLAN'][i]['TripID']+`)"><i class="fas fa-ban fa-fw fa-lg"></i> ยกเลิก</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="CheckInReport(`+inval['DPLAN'][i]['TripID']+`)"><i class="fas fa-file-alt fa-fw fa-lg"></i> รายงานการเข้าพบ</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>`;
                        No++;
                    }

                    $("#TableDPlan tbody").html(PlanTbody);
                }
            });
        }
    })
    $("#ModalDPlan").modal("show");
}


function CheckIn(TripID) {
    $(".modal").modal("hide");
    $("#overlay").show();

    if(location.protocol == "https:") {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var GeoLon = position.coords.longitude;
                var GeoLat = position.coords.latitude;
                $("#ChkLon").val(GeoLon);
                $("#ChkLat").val(GeoLat);
                $.ajax({
                    url: "routetrip/ajax.php?p=GetLocation",
                    type: "POST",
                    data: {TripID: TripID },
                    async: false,
                    success: function(result) {
                        var obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            $("#ChkCardName").html(inval['CardName']);
                            $("#ChkCardCode").val(inval['CardCode']);
                            $("#PlanLon").val(inval['CardLon']);
                            $("#PlanLat").val(inval['CardLat']);
                            $("#TarDistance").val(inval['TarDistance']);
        
                            $("#ChkRouteEty").val(TripID);
                        });
                    }
                });
        
                var ChkLon = $("#ChkLon").val();
                var ChkLat = $("#ChkLat").val();
                var CusLon = $("#PlanLon").val();
                var CusLat = $("#PlanLat").val();
                var TarRange = $("#TarDistance").val();
                if(ChkLon.length == 0 || ChkLat.length == 0) {
                    $("#overlay").hide();
                    $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                    $("#alert_body").html("หน้าเว็บโหลดไม่ทัน กดเช็คอินใหม่อีกรอบครับ :)");
                    $("#alert_modal").modal('show');
                } else {
                    CheckInMaps(CusLon, CusLat, ChkLon, ChkLat, TarRange);
                    $("#overlay").hide();
                    $("#ModalCheckIn").modal("show");

                    $(document).off("click","button#btn-checkin").on("click","button#btn-checkin", function(e) {
                        e.preventDefault();
                        AddCheckIn();
                    });
                }
            }, function(error) {
                $("#overlay").hide();
                var txt_error;
                switch(error.code) {
                    case error.PERMISSION_DENIED: txt_error = "กรุณาอนุญาตสิทธิ์ระบุพิกัดก่อนใช้งาน (Permission Denied.)<br/>วิธีแก้ไข <a href='https://support.google.com/chrome/answer/142065?co=GENIE.Platform%3DAndroid&oco=1' target='_blank'>Google Chrome / Microsoft Edge</a> | <a href='https://support.apple.com/guide/iphone/customize-your-safari-settings-iphb3100d149/16.0/ios/16.0' target='_blank'>Safari</a>"; break;
                    case error.POSITION_UNAVAILABLE: txt_error = "ไม่สามารถระบุพิกัดได้ (Location Unavailable.)"; break;
                    case error.TIMEOUT: txt_error = "การร้องขอระบุพิกัดหมดอายุ (Request time out.)"; break;
                    case error.UNKNOWN_ERROR: txt_error = "ข้อผิดพลาดที่ไม่รู้จัก (Unknown Error.)<br/>กรุณาติดต่อผู้ดูแลระบบ"; break;
                }
                $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                $("#alert_body").html(txt_error);
                $("#alert_modal").modal('show');
            });
        } else {
            $("#overlay").hide();
            $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
            $("#alert_body").html("อุปกรณ์หรือแอปพลิเคชั่นนี้ไม่รองรับการใช้งานระบุพิกัด");
            $("#alert_modal").modal('show');
        }
    } else {
        $("#overlay").hide();
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณาเข้าระบบผ่าน https://<br/><a href='https://www.shawpetgroup.com:4433'>Click Here</a>");
        $("#alert_modal").modal('show');
    }
}

function AddCheckIn() {
    var RouteEntry  = $("#ChkRouteEty").val();
    var ChkCardCode = $("#ChkCardCode").val();
    var ChkLon      = $("#ChkLon").val();
    var ChkLat      = $("#ChkLat").val();
    var PlanLon     = $("#PlanLon").val();
    var PlanLat     = $("#PlanLat").val();
    var ChkDistance = $("#ChkDistance").val();
    var TarDistance = $("#TarDistance").val();
    $.ajax({
        url: "routetrip/ajax.php?p=AddCheckIn",
        type: "POST",
        data: {
            TripID: RouteEntry,
            CardCode: ChkCardCode,
            ActualLat: ChkLat,
            ActualLon: ChkLon,
            PlanLat: PlanLat,
            PlanLon: PlanLon,
            ChkDistance: ChkDistance,
            TarDistance: TarDistance
        },
        success: function(result) {
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
                } else {
                    $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                    $("#alert_body").html("ไม่สามารถเช็คอินได้ กรุณาลองใหม่อีกครั้ง");
                    $("#alert_modal").modal('show');
                }
            });
        }
    })
}

function AddCheckInDetail(ActualDetail, TripID) {
    $.ajax({
        url: "routetrip/ajax.php?p=AddChkDetail",
        type: "POST",
        data: {
            ActualDetail: ActualDetail,
            TripID: TripID
        },
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    $("#alert_header").html("<i class=\"far fa-check-circle fa-fw fa-lg text-success\"></i> เสร็จสิ้น !");
                    $("#alert_body").html("บันทึกสำเร็จ");
                    $("#alert_modal").modal("show");
                    $(document).off("click","button.btn-confirm").on("click","button.btn-confirm", function(e) {
                        e.preventDefault();
                        GetTrip();
                    });
                }
            });
        }
    })
}



function CancelTrip(TripID) {
    $("#confirm_modal").modal("show");
    $(document).off("click","#btn-confirm").on("click","#btn-confirm", function(e) {
        e.preventDefault();
        $.ajax({
            url: "routetrip/ajax.php?p=CancelTrip",
            type: "POST",
            data: { TripID: TripID },
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    if(inval['Status'] == 'OK') {
                        $("#confirm_modal").modal("hide");
                        $("#fade_modal h5").html("<i class='fas fa-check-circle text-success'></i>");
                        $("#fade_modal p").html("ลบสำเร็จ");
                        $("#fade_modal").modal("show");
                        setTimeout(function() { $("#fade_modal").modal("hide"); }, 1200)
                        GetTrip();
                        $("#ModalDPlan").modal("hide");
                    }else{
                        $("#fade_modal h5").html("<i class='fas fa-exclamation-circle text-danger'></i>");
                        $("#fade_modal p").html("ลบไม่สำเร็จ");
                        $("#fade_modal").modal("show");
                        setTimeout(function() { $("#fade_modal").modal("hide"); },2000)
                    }
                });
            }
        });
    });
}

$(document).ready(function() {
    GetData();
});