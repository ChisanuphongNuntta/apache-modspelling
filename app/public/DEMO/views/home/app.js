function GetAppDoc() {
    $("#TableAppDoc").dataTable().fnClearTable();
    $("#TableAppDoc").dataTable().fnDraw();
    $("#TableAppDoc").dataTable().fnDestroy();

    $("#TableAppDoc").DataTable({
        "ajax": {
            url: "home/ajax.php?p=GetAppDoc",
            type: "GET",
            async: false,
            dataType: "json",
            dataSrc: function (data) {
                return data[0]['ListAppDoc'];
            }
        },
        "columns": [
            { "data": "DocDate", class: "dt-body-center"},
            { "data": "DocDueDate", class: "dt-body-center"},
            { "data": "DocNum", class: "dt-body-center"},
            { "data": "CardName", class: ""},
            { "data": "DocTotal", class: "dt-body-right"},
            { "data": "SlpName", class: ""}
        ],
        "columnDefs": [
            { "width": "8%", "targets": 0 },
            { "width": "8%", "targets": 1 },
            { "width": "10%", "targets": 2 },
            { "width": "27%", "targets": 3 },
            { "width": "7%", "targets": 4 },
            { "width": "12%", "targets": 5 }
        ],
        "createdRow": function (row, data, dataIndex, cells) {
            if(data.StatusData == 'N') {
                $('td:eq(0)', row).attr('colspan', 11);
                $('td:eq(1)', row).css('display', 'none');
                $('td:eq(2)', row).css('display', 'none');
                $('td:eq(3)', row).css('display', 'none');
                $('td:eq(4)', row).css('display', 'none');
                $('td:eq(5)', row).css('display', 'none');
            }
        },
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "pageLength": 6,
        "ordering": false,
        "language": DTTB_TH
    });
}

function GetCalendar() {
    let DataTrip = "";
    let Today = date_format(new Date(), "d");
    let Year = date_format(new Date(), "Y");
    let Month = date_format(new Date(), "m");
    let DayInMonth = new Date(Year, Month, 0).getDate();
    let DayOfWeek = new Date(Year+'-'+Month+'-01').getDay();
    $.ajax({
        url: "home/ajax.php?p=GetCalendar",
        type: "GET",
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let LastMonth = ((Month-1) != 0) ? (Month-1) : 12;
                let LastYear = ((Month-1) != 0) ? (Year-1) : Year;
                let LastDayInMonth = (new Date(LastYear, LastMonth, 0).getDate()-DayOfWeek)+1;
                for(o = 1; o <= DayOfWeek; o++) { 
                    DataTrip += `<li class='border inactive' style='cursor: default;'>`+LastDayInMonth+`</li>`; 
                    LastDayInMonth++;
                }
                let Getday = "";
                for(let day = 1; day <= DayInMonth; day++) {
                    Getday = new Date(Year+'-'+Month+'-'+day).getDay();
                    let ClsSunday = (Getday == 0) ? "text-danger" : "";
                    let ClsToday = (day == Today) ? "active" : "";
                    let DayTrip = (typeof(inval[day]) != "undefined") ? `<span style='position: relative; left: 7px;'>`+day+`</span>` : day;
                    let StyleStar = (day < 10) ? "left: -7px;" : "left: -12px;"; 
                    StyleStar = (day == 11) ? "left: -10px;" : StyleStar; 
                    let DetailTrip = (typeof(inval[day]) != "undefined") ? "<span class='text-danger' style='position: relative; top: 17px; "+StyleStar+" text-shadow: #bb2d2d 0px 0 3px;'>*</span>" : "";
                    DataTrip += `
                        <li class='border dayactive`+day+` `+ClsSunday+` `+ClsToday+`' style='cursor: pointer;' onclick='GetDetailPlan(`+day+`);'>
                            `+DayTrip+`
                            `+DetailTrip+`
                        </li>
                    `; 
                }
                let DayPMonth = 0;
                for(d = Getday; d <= 5; d++) { 
                    DayPMonth++;
                    DataTrip += `<li class='border inactive' style='cursor: default;'>`+DayPMonth+`</li>`; 
                }

                $(".calendar-dates").html(DataTrip);
            });
        }
    });
}

function GetDetailPlan(Day) {
    Day = (Day == undefined) ? date_format(new Date(), "d") : Day;
    sessionStorage.setItem('temDay',Day);
    $("li[class*='dayactive']").removeClass("bg-dark bg-opacity-10");
    let Today = date_format(new Date(), "d");
    if(Day != Today) {
        $(".dayactive"+Day).addClass("bg-dark bg-opacity-10");
    }
    $.ajax({
        url: "home/ajax.php?p=GetDetailPlan",
        type: "POST",
        data: { Day: Day },
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let DataDPlan = "";
                if(inval['DPLAN'] != 0) {
                    $.each(inval['DPLAN'], function(k, data) {
                        let IconStatus = "";
                        let chck_dis = cncl_dis = "disabled";
                        switch (data['TripStatus']) {
                            case '0': IconStatus = `<div class='bg-secondary text-white text-center p-2' style='width: 50px;'><i class="fas fa-ban"></i></div>`; break; //ยกเลิก
                            case '1': IconStatus = `<div class='bg-info text-white text-center p-2' style='width: 50px;'><i class='fas fa-clock'></i></div>`; chck_dis = cncl_dis = ""; break; // รอเข้าพบ
                            case '2': IconStatus = `<div class='bg-success text-white text-center p-2' style='width: 50px;'><i class='fas fa-map-marker'></i></div>`; cncl_dis = ""; break; // เช็คอินแล้ว ยังไม่เขียนสรุป
                            case '3': IconStatus = `<div class='bg-success text-white text-center p-2' style='width: 50px;'><i class='fas fa-check-circle'></i></div>`; cncl_dis = ""; break; // เช็คอินแล้ว เขียนสรุปแล้ว
                        }
    
                        let [SH, SM, SS] = data['S_Time'].split(':');
                        let [EH, EM, ES] = data['E_Time'].split(':');
    
                        let navi_dis = (data['PlanLat'] != "" && data['PlanLon'] != "") ? "" : "disabled";
                        let navi_href = (data['PlanLat'] != "" && data['PlanLon'] != "") ? "https://maps.google.com/?q="+data['PlanLat']+","+data['PlanLon'] : "javascript:void(0);";
    
                        

                        DataDPlan += `
                            <tr>
                                <td>
                                    <div class='d-flex justify-content-between align-items-center' data-bs-toggle="collapse" href="#collapse`+data['TripID']+`" role="button" aria-expanded="false" aria-controls="collapse`+data['TripID']+`">
                                        <div class='d-flex'>
                                            `+IconStatus+`
                                            <div class='ps-2'>
                                                <p class='m-0 fw-bolder'>`+data['CardCode']+` | `+data['CardName']+`</p>
                                                <p class='m-0 '><span class='fw-semibold'>แผนงาน</span> : `+data['PlanDetail']+`</p>
                                            </div>
                                        </div>
                                        <div class='pe-2'><i class="far fa-clock"></i> `+SH+`:`+SM+` - `+EH+`:`+EM+`</div>
                                    </div>
                                    <div class="collapse" id="collapse`+data['TripID']+`">
                                        <div class="card card-body m-0 ps-2 pe-2 pt-3 pb-3">
                                            <div class="row">
                                                <div class="col-sm-12 col-lg">
                                                    <a class='btn btn-sm btn-success' href="`+navi_href+`" target="_blank" `+navi_dis+`><i class="fas fa-directions"></i> นำทาง</a>
                                                    <button class='btn btn-sm btn-outline-secondary' `+chck_dis+` onclick="CheckIn(`+data['TripID']+`);"><i class="fas fa-map-marker-alt"></i> เช็คอิน</button>
                                                    <button class='btn btn-sm btn-secondary' `+cncl_dis+` onclick="CancelTrip(`+data['TripID']+`)"><i class="fas fa-ban"></i> ยกเลิก</button>
                                                    <button class='btn btn-sm btn-secondary' onclick="CheckInReport(`+data['TripID']+`);"><i class="fas fa-file-alt"></i> รายงานการเข้าพบ</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }else{
                    DataDPlan = "<tr><td class='text-center'>ไม่มีแผนงาน</td></tr>";
                }

                $("#TableDPlan thead").html(`<tr><th class='text-center' style='font-size: 17.21px; padding: 5px;'>รายการเข้าพบ วันที่ `+Day+`</th></tr>`);
                $("#TableDPlan tbody").html(DataDPlan);
            });
        }
    });
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
                        GetCalendar();
                        GetDetailPlan(sessionStorage.getItem('temDay'));
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
                        GetCalendar();
                        GetDetailPlan(sessionStorage.getItem('temDay'));
                    });
                }
            });
        }
    })
}

function GetListMonth() {
    $.ajax({
        url: "home/ajax.php?p=GetListMonth",
        type: "GET",
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let DataSaleBill = "";
                $.each(inval['DataSaleBill'], function(k, data) {
                    DataSaleBill += 
                        `<tr>
                            <td class='text-center'>`+(k+1)+`</td>
                            <td class='text-center'><a href='javascript:void(0);' onclick='ViewDocSaleBill(\"`+data['DocType']+`\",`+data['DocEntry']+`)'>`+data['BeginStr']+`-`+data['DocNum']+`</td>
                            <td class='text-center'>`+data['DocDate']+`</td>
                            <td>`+data['CardName']+`</td>
                            <td>`+data['SlpName']+`</td>
                            <td class='text-end'>`+data['DocTotal']+`</td>
                        </tr>`;
                });
                $("#TableSaleBill").dataTable().fnClearTable();
                $("#TableSaleBill").dataTable().fnDraw();
                $("#TableSaleBill").dataTable().fnDestroy();
                $("#TableSaleBill tbody").html(DataSaleBill);

                let DataOverdueBill = "";
                $.each(inval['DataOverdueBill'], function(k, data) {
                    DataOverdueBill += 
                        `<tr>
                            <td class='text-center'>`+(k+1)+`</td>
                            <td class='text-center'><a href='javascript:void(0);' onclick='ViewDocOverdueBill(`+data['SODocEntry']+`)'>`+data['SO_DocNum']+`</td>
                            <td class='text-center'>`+data['DocDate']+`</td>
                            <td>`+data['CardName']+`</td>
                            <td>`+data['SlpName']+`</td>
                            <td class='text-end'>`+data['DocTotal']+`</td>
                        </tr>`;
                });
                $("#TableOverdueBill").dataTable().fnClearTable();
                $("#TableOverdueBill").dataTable().fnDraw();
                $("#TableOverdueBill").dataTable().fnDestroy();
                $("#TableOverdueBill tbody").html(DataOverdueBill);

                $('#TableSaleBill, #TableOverdueBill').DataTable({
                    "columnDefs": [
                        { "width": "7%", "targets": 0 },
                        { "width": "13%", "targets": 1 },
                        { "width": "10%", "targets": 2 },
                        { "width": "40%", "targets": 3 },
                        { "width": "20%", "targets": 4 },
                        { "width": "10%", "targets": 5 }
                    ],
                    "responsive": true, 
                    "lengthChange": false, 
                    "autoWidth": false,
                    "pageLength": 15,
                    "ordering": false,
                    "language": DTTB_TH
                });

                $("#ModalListMonth").modal("show");
            });
        }
    })
}

function ViewDocSaleBill(Type, DocNum) {
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

function ViewDocOverdueBill(DocEntry) {
    const Type = "ORDR";
    const DataSite = (b64_to_utf8(SS_SITE) == '0' || b64_to_utf8(SS_SITE) == 0) ? "SPING" : "SPET";
    $.ajax({
        url: "so_outstan/ajax.php?p=ViewDoc",
        type: "POST",
        data: { Type: Type, DocEntry: DocEntry,DataSite: DataSite },
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
                            <td class='text-end'>`+number_format(data['OnHand'],0)+`</td>
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
    GetAppDoc();
    GetCalendar();
    GetDetailPlan();
});