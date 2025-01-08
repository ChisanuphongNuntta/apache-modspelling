function date_format(DataDate,Type) {
    const ArrDate = new Date(DataDate)
    let DateFormat = "";
    switch (Type.toUpperCase()) {
        case "D":
            DateFormat = ArrDate.getDate().toString().padStart(2,"0");
        break;
        case "M":
            DateFormat = (ArrDate.getMonth()+1).toString().padStart(2,"0");
        break;
        case "Y":
            DateFormat = ArrDate.getFullYear();
        break;
        case "HM":
            DateFormat = ArrDate.getHours().toString().padStart(2,"0")+":"+ArrDate.getMinutes().toString().padStart(2,"0");
        break;
        case "DMY":
            DateFormat = ArrDate.getDate().toString().padStart(2,"0")+"/"+(ArrDate.getMonth()+1).toString().padStart(2,"0")+"/"+ArrDate.getFullYear();
        break;
        case "YMD":
            DateFormat = ArrDate.getFullYear()+"/"+(ArrDate.getMonth()+1).toString().padStart(2,"0")+"/"+ArrDate.getDate().toString().padStart(2,"0");
        break;
        case "DMYH":
            DateFormat = ArrDate.getDate().toString().padStart(2,"0")+"/"+(ArrDate.getMonth()+1).toString().padStart(2,"0")+"/"+ArrDate.getFullYear()+" เวลา "+ArrDate.getHours().toString().padStart(2,"0")+" ชม.";
        break;
        case "DMYHM":
            DateFormat = ArrDate.getDate().toString().padStart(2,"0")+"/"+(ArrDate.getMonth()+1).toString().padStart(2,"0")+"/"+ArrDate.getFullYear()+" เวลา "+ArrDate.getHours().toString().padStart(2,"0")+":"+ArrDate.getMinutes().toString().padStart(2,"0")+" น.";
        break;
        default: DateFormat = "ไม่มี Date Format นี้"; break
    }
    return DateFormat;
}

function number_format(number,decimal) {
    var options = { roundingPriority: "lessPrecision", minimumFractionDigits: decimal, maximumFractionDigits: decimal };
    var formatter = new Intl.NumberFormat("en",options);
    return formatter.format(number)
}

function utf8_to_b64( str ) {
    return window.btoa(unescape(encodeURIComponent( str )));
}

function b64_to_utf8( str ) {
    return decodeURIComponent(escape(window.atob( str )));
}

function PrintOpen(Style,FileName,Data) {
    let DataArr = Data.split('||');
    let DataPush = "";
    for(let d = 0; d < DataArr.length; d++) {
        let SubArr = DataArr[d].split('=');
        DataPush += "&"+utf8_to_b64(SubArr[0])+"="+utf8_to_b64(SubArr[1]);
    }
    return window.open("../print/?fpage="+FIX_Page+"&fname="+utf8_to_b64(FileName)+"&fstyle="+(Style.toLowerCase())+DataPush,"_blank");
}

function Fn_ViewAppDoc(DocEntry) {
    $.ajax({
        url: "salesapprove/ajax.php?p=CallDetail",
        type: "POST",
        data: { DocEntry: DocEntry, },
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                // HEADER
                let ArrTxtSale = new Date();
                let ArrDocDate = new Date(inval['HEADER']['DocDate']);
                let DocDate = ArrDocDate.getDate().toString().padStart(2,"0")+"/"+(ArrDocDate.getMonth()+1).toString().padStart(2,"0")+"/"+ArrDocDate.getFullYear();
                let DiscUnit = (inval['HEADER']['DiscPcnt'] != 0) ? "%" : "บาท";
                let DocDisc  = (inval['HEADER']['DiscPcnt'] != 0 ) ? inval['HEADER']['DiscPcnt'] : inval['HEADER']['DiscTotal'];
                $("#txtDocNum").html(inval['HEADER']['DocNum']);                         //เลขที่เอกสาร
                $("#txtCardName").html(inval['HEADER']['CardCode']+" | "+inval['HEADER']['CardName']);                     //ชื่อลูกค้า
                $("#txtDocDate").html(DocDate);                                          //วันที่เอกสาร
                $("#txtSlpName").html(inval['HEADER']['SlpName']);                       //พนักงานขาย
                $("#txtGroupNum").html(inval['HEADER']['GroupNum']);                     //เครดิต
                $("#txtCusSale").html(inval['HEADER']['Aging']+" วัน");                   //ลูกค้าสั่งซื้อมาแล้ว
                $("#txtOpenSale").html(inval['HEADER']['1stDate']);                      //วันที่เปิดบิลครั้งแรก
                $("#txtPay").html(inval['HEADER']['PaidTotal']+" บาท");                  //มียอดชำระเงินมาแล้ว
                $("#txtSaleP").html(inval['HEADER'][ArrTxtSale.getFullYear()-1]+" บาท"); //ยอดสั่งซื้อปีที่แล้ว
                $("#txtSaleC").html(inval['HEADER'][ArrTxtSale.getFullYear()]+" บาท");   //ยอดสั่งซื้อปีปัจจุบัน
                $("#txtCredit").html(number_format(inval['HEADER']['CreditLine'],0)+" บาท");              //เครดิตวงเงิน
                $("#txtComment").html(inval['HEADER']['Comments']);                      //หมายเหตุท้าย SO

                $("#Doc_SumTotal").html(number_format((inval['HEADER']['DocTotal']-inval['HEADER']['VatSum']) - inval['HEADER']['DiscTotal'],2));
                $("#Doc_DiscSum").html(number_format(DocDisc,2));
                $("#Doc_DiscUnit").html(DiscUnit);
                $("#Doc_Discount").html(number_format(inval['HEADER']['DocTotal']-inval['HEADER']['VatSum'],2));
                $("#Doc_VatSum").html(number_format(inval['HEADER']['VatSum'],2));
                $("#Doc_Total").html(number_format(inval['HEADER']['DocTotal'],2));
                $("#Doc_DiscPcnt").html(number_format(inval['HEADER']['DocPcntProfit'],2)+"%");
                if((parseFloat(inval['HEADER']['Balance'])+parseFloat(inval['HEADER']['DocTotal'])) > parseFloat(inval['HEADER']['CreditLine'])) {
                    $("#Doc_Balance").addClass("text-danger");
                }else{
                    $("#Doc_Balance").removeClass("text-danger");
                }
                $("#Doc_Balance").html(number_format(parseFloat(inval['HEADER']['Balance'])+parseFloat(inval['HEADER']['DocTotal']),2));
                if((parseFloat(inval['HEADER']['CreditLine'])-(parseFloat(inval['HEADER']['Balance'])+parseFloat(inval['HEADER']['DocTotal']))) < 0) {
                    $("#Doc_BalanceCredit").addClass("text-danger");
                }else{
                    $("#Doc_BalanceCredit").removeClass("text-danger");
                }
                $("#Doc_BalanceCredit").html(number_format(parseFloat(inval['HEADER']['CreditLine'])-(parseFloat(inval['HEADER']['Balance'])+parseFloat(inval['HEADER']['DocTotal'])),2));

                // รายการรออนุมัติ
                let DataListApp = "";
                $.each(inval['APPROVE'], function(k, data) {
                    let uApp = "";
                    if(data['APP0'] == 'Y'){ uApp += "<span class='text-danger'>อนุมัติ วงเงินเครดิต</span><br>"; }
                    if(data['APP1'] == 'Y'){ uApp += "<span class='text-danger'>อนุมัติ หนี้เกินกำหนด</span><br>"; }
                    if(data['APP2'] == 'Y'){ uApp += "<span class='text-danger'>อนุมัติ เช็คเกินกำหนด</span><br>"; }
                    if(data['APP3'] == 'Y'){ uApp += "<span class='text-danger'>อนุมัติ ราคาพิเศษ</span><br>"; }
                    if(data['APP4'] == 'Y'){ uApp += "<span class='text-danger'>อนุมัติ มูลค่าท้ายบิลต่ำกว่ากำหนด</span><br>"; }
                    uApp = uApp.slice(0,-4);

                    let DisInput = "disabled";
                    if(data['LvClassReq'] == inval['LVCLASS'] || inval['LVCLASS'] == '0') {
                        DisInput = "";
                    }

                    Opt_App0 = Opt_AppY = Opt_AppN = "";

                    switch(data['AppResult']) {
                        case "0": Opt_App0 = "selected"; break;
                        case "Y": Opt_AppY = "selected"; break;
                        case "N": Opt_AppN = "selected"; break;
                    }

                    AppRemark = (data['AppRemark'] == null) ? "" : data['AppRemark'];

                    DataListApp += `
                        <tr>
                            <th class='text-center'>`+(k+1)+`</th>
                            <th>`+data['LvName']+`</th>
                            <th>`+uApp+`</th>
                            <th><input class='form-control form-control-sm' name="AppRemark_`+data['AppID']+`" id="AppRemark_`+data['AppID']+`" `+DisInput+` value="`+AppRemark+`" maxlength="255"></th>
                            <th>
                                <select class='form-select form-select-sm' name="AppResult_`+data['AppID']+`" id="AppResult_`+data['AppID']+`" `+DisInput+`>
                                    <option value="0" `+Opt_App0+` disabled>รอพิจารณา</option>
                                    <option value="Y" `+Opt_AppY+`>อนุมัติ</option>
                                    <option value="N" `+Opt_AppN+`>ไม่อนุมัติ</option>
                                </select>
                            </th>
                            <th class='text-center'>
                                <button class='btn btn-sm btn-primary' onclick="Fn_AppOrder(`+data['DocEntry']+`,`+data['AppID']+`)" `+DisInput+`><i class="fas fa-save"></i> บันทึก</button>
                            </th>
                        </tr>`;
                });
                if(DataListApp == '') {
                    DataListApp = `<tr><td colspan='6' class='text-center'>ไม่มีข้อมูล :(</td></tr>`;
                }
                $("#TableListApp tbody").html(DataListApp);

                // รายการหนี้เกินกำหนด
                let DataListOver = "";
                $.each(inval['OVERDUE'], function(k, data) {
                    let ArrDocDate = new Date(data['DocDate']);
                    let DocDate = ArrDocDate.getDate().toString().padStart(2,"0")+"/"+(ArrDocDate.getMonth()+1).toString().padStart(2,"0")+"/"+ArrDocDate.getFullYear();
                    let ArrDocDueDate = new Date(data['DocDueDate']);
                    let DocDueDate = ArrDocDueDate.getDate().toString().padStart(2,"0")+"/"+(ArrDocDueDate.getMonth()+1).toString().padStart(2,"0")+"/"+ArrDocDueDate.getFullYear();

                    DataListOver += `
                    <tr>
                        <td class='text-center'>`+(k+1)+`</td>
                        <td class='text-center'>`+data['DocNum']+`</td>
                        <td class='text-center'>`+DocDate+`</td>
                        <td class='text-center'>`+DocDueDate+`</td>
                        <td class='text-center'>`+data['OverDate']+`</td>
                        <td>`+data['CardCode']+` | `+data['CardName']+`</td>
                        <td class='text-end fw-bolder'>`+number_format(data['DocTotal'],2)+`</td>
                        <td class='text-end text-danger fw-bolder'>`+number_format(data['Balance'],2)+`</td>
                    </tr>
                    `;
                });
                if(DataListOver == '') {
                    DataListOver = `<tr><td colspan='8' class='text-center'>ไม่มีข้อมูล :(</td></tr>`;
                }
                $("#TableOver tbody").html(DataListOver);

                // รายการเช็คเด้ง

                // รายการสินค้า/ราคาพิเศษ
                let DataItemList = "";
                $.each(inval['ITEMLIST'], function(k, data) {
                    let Discount = "";
                    if(data['Line_Disc0'] != null) {
                        Discount = number_format(data['Line_Disc0'],2);
                    }else{
                        if(data['Line_Disc4'] != null) {
                            Discount = data['Line_Disc1']+"%+"+data['Line_Disc2']+"%+"+data['Line_Disc3']+"%+"+data['Line_Disc4']+"%";
                        }else if(data['Line_Disc3'] != null){
                            Discount = data['Line_Disc1']+"%+"+data['Line_Disc2']+"%+"+data['Line_Disc3']+"%";
                        }else if(data['Line_Disc2'] != null){
                            Discount = data['Line_Disc1']+"%+"+data['Line_Disc2']+"%";
                        }else if(data['Line_Disc1'] != null){
                            Discount = data['Line_Disc1']+"%";
                        }
                    }
                    let trClass   = (data['Line_SP'] == "Y") ? " class='table-warning'" : "" ;
                    let pcntClass = (data['PcntProfit'] < 25) ? " text-danger": "" ; 
                    DataItemList += `
                    <tr`+trClass+`>
                        <td class='text-center'>`+(k+1)+`</td>
                        <td class='text-center'>`+data['ItemCode']+`</td>
                        <td>`+data['ItemName']+`</td>
                        <td class='text-end'>`+number_format(data['Cost'],2)+`</td>
                        <td class='text-end'>`+number_format(data['GrandPrice'],2)+`</td>
                        <td class='text-center'>`+Discount+`</td>
                        <td class='text-end'>`+number_format(data['UnitPrice'],2)+`</td>
                        <td class='text-center'>`+data['WhsCode']+`</td>
                        <td class='text-end'>`+number_format(data['Quantity'],0)+`</td>
                        <td>`+data['UnitMsr']+`</td>
                        <td class='text-end fw-bolder'>`+number_format(data['LineTotal'],2)+`</td>
                        <td class='text-end fw-bolder text-success'>`+number_format(data['LineProfit'],2)+`</td>
                        <td class='text-center`+pcntClass+`'>`+number_format(data['PcntProfit'],2)+`%</td>
                    </tr>
                    `; 
                });

                if(DataItemList == '') {
                    DataItemList = `<tr><td colspan='13' class='text-center'>ไม่มีข้อมูล :(</td></tr>`;
                }
                $("#Listandsale tbody").html(DataItemList);

                // เอกสารแนบ
                let DataListFile = "";
                $.each(inval['ItemAttach'], function(k, data) {
                    let d = new Date(data['DateCreate']);
                    let DateCreate = d.getDate().toString().padStart(2,"0")+"/"+(d.getMonth()+1).toString().padStart(2,"0")+"/"+d.getFullYear()+" เวลา "+d.getHours()+":"+d.getMinutes()+" น.";
                    DataListFile += 
                        `<tr>
                            <td class='text-end'>`+(k+1)+`</td>
                            <td>`+data['FileOriName']+`</td>
                            <td class='text-center'>`+DateCreate+`</td>
                            <td class='text-center'>
                                <button class='btn btn-sm btn-success' onclick='Fn_ActiveBTN(\"Download\",\"`+data['FileDirName']+`.`+data['FileExt']+`||`+data['FileOriName']+`.`+data['FileExt']+`\");' style='--bs-btn-padding-y: 0.1rem !important; --bs-btn-padding-x: 0.5rem !important; --bs-btn-font-size: 0.875rem !important;'>
                                    <i class="fas fa-file-download"></i>
                                </button>
                            </td>
                        </tr>`;
                });
                if(DataListFile == '') {
                    DataListFile = `<tr><td colspan='4' class='text-center'>ไม่มีข้อมูล :(</td></tr>`;
                }
                $("#TableFile tbody").html(DataListFile);

                $("#App-tab").click();
                $("#ModalAppDoc").modal("show");
            });
        }
    })
    
}

function Fn_ActiveBTN(Type,D) {
    switch(Type) {
        case 'Download': 
            $("#confirm_modal .modal-body .defult").addClass("d-none");$("#confirm_modal .modal-body .custom").removeClass("d-none");
            $("#confirm_modal .modal-body .custom").html("คุณต้องการดาวน์โหลดหรือไม่?");
            $("#confirm_modal").modal("show");
            $(document).off("click","#btn-confirm").on("click","#btn-confirm", function() {
                let FileArr = D.split('||');
                let req = new XMLHttpRequest();
                req.open("GET", "../FileAttach/SO/"+FileArr[0], true);
                req.responseType = "blob";
                req.onload = function (event) {
                    let blob = req.response;
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = FileArr[1];
                    link.click();
                };
                req.send();
                $("#confirm_modal").modal("hide");
                $("#confirm_modal .modal-body .defult").removeClass("d-none");$("#confirm_modal .modal-body .custom").addClass("d-none");
            });
        break;
    }
}

function Fn_AppOrder(DocEntry, AppID) {
    $("#overlay").show();
    let AppRemark = $("#AppRemark_"+AppID).val();
    let AppResult = $("#AppResult_"+AppID).val();

    if(AppRemark == "" || AppRemark == null || AppResult == "0" || AppResult == "" || AppResult == null) {
        $("#overlay").hide();
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณาระบุความคิดเห็น และผลการพิจารณาให้ครบถ้วน");
        $("#alert_modal").modal('show');
    } else {
        $.ajax({
            url: "salesapprove/ajax.php?p=AppOrder",
            type: "POST",
            data: {
                DocEntry: DocEntry,
                AppID: AppID,
                Remark: AppRemark,
                Result: AppResult
            },
            success: function(result) {
                $("#overlay").hide();
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    if(inval['Status'] == "OK") {
                        $("#alert_header").html("<i class=\"far fa-check-circle fa-fw fa-lg text-success\"></i> เสร็จสิ้น !");
                        $("#alert_body").html(inval['Message']);
                        $("#alert_modal").modal("show");
                        $(document).off("click","button.btn-confirm").on("click","button.btn-confirm", function(e) {
                            e.preventDefault();
                            window.location.reload();
                        });
                    } else {
                        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                        $("#alert_body").html(inval['Message']);
                        $("#alert_modal").modal("show");
                    }
                });
            }
        });
    }
}

function CheckInMaps(Cus_Lon, Cus_Lat, Chk_Lon, Chk_Lat, TarRange) {
    /* CusLon + CusLat = พิกัดร้านค้า || ChkLon + ChkLat = พิกัดผู้ใช้ */
    var CusLon;
    var CusLat;
    var ChkLon = Chk_Lon;
    var ChkLat = Chk_Lat;
    var TarRange = TarRange;

    var map = new longdo.Map({
        placeholder: document.getElementById("CheckInMaps"),
        lastview: false,
        language: 'th',
        ui: longdo.UiComponent.Mobile
    });

    map.Layers.setBase(longdo.Layers.GRAY);
    map.zoom(15,true);
    map.zoomRange({ min: 10, max: 20 });
    map.location({ lon: ChkLon, lat: ChkLat }, true);

    /* CheckIn Marker */
    var CheckPin = new longdo.Marker({ lon: ChkLon, lat: ChkLat },{ icon: { html: '<i class=\'fas fa-male fa-4x\' style=\'color: #fc0380;\'></i>', offset: { x: 9, y: 48 } }, weight: 999 });
    map.Overlays.add(CheckPin);

    if(Cus_Lon != 0.00 && Cus_Lat != 0.00) {
        /* Customer Marker */
        var StorePin = new longdo.Marker({ lon: Cus_Lon, lat: Cus_Lat },{ icon: { html: '<i class=\'fas fa-map-marker-alt fa-2x text-primary\'></i>', offset: { x: 9, y: 24 } }, weight: 999 });
        map.Overlays.add(StorePin);
        /* 
            Safezone Generator
            Add CirCle radius ~5km.
            ระยะห่าง 1 องศา Lat/Lon = ~111.195km. @ เส้นศูนย์สูตรโลก
            ~1km. = 1/111.195 = 0.008993210126355 degree
        */
       SafeRange = 0.008993210126355 * parseFloat(TarRange);
        var SafeZone = new longdo.Circle({
            lon: Cus_Lon, lat: Cus_Lat
        }, SafeRange, {
            lineWidth: 2,
            lineColor: 'rgba(128,252,3,0.8)',
            fillColor: 'rgba(128,252,3,0.25)'
        });
        map.Overlays.add(SafeZone);

        /* Find Distance */
        var Distance = longdo.Util.distance([CheckPin.location(),StorePin.location()]);
        $("#ChkDistance").val((Distance/1000).toFixed(2));
        var LineDistance = new longdo.Polyline([CheckPin.location(),StorePin.location()],{ lineColor: "rgba(154,17,24,1)", lineWidth: 2, lineStyle: longdo.LineStyle.Dashed });
        map.Overlays.add(LineDistance);
    }

    ShowDistance = (isNaN(Distance) == true) ? "ไม่พบพิกัดร้านค้า" : "ระยะห่างจากจุดเช็คอินถึงร้านค้า: "+(number_format(Distance/1000,2))+" กม.";
    $("#GetDistance").html(ShowDistance);

    $("#ModalCheckIn").on("shown.bs.modal", function () {
        map.resize();
    });
}

function CheckInReport(TripID) {
    $.ajax({
        url: "routetrip/ajax.php?p=CheckInReport",
        type: "POST",
        data: { TripID: TripID },
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                $("#RptCardCode").html(inval['Head']['CardName']);
                $("#RptPlanDate").html(date_format(inval['Head']['PlanStart'], "dmYhm")+" ถึง "+date_format(inval['Head']['PlanEnd'], "hm")+" น.");
                $("#RptCheckInDate").html(date_format(inval['Head']['PlanEnd'], "dmYhm")+" "+((inval['Head']['PlanType'] == 'P') ? "(เพิ่มเนื่องจากอยู่ในแผน)" : "(เพิ่มเนื่องจากไม่อยู่ในแผน)"));
                $("#RptDistance").html((inval['Head']['ChkDistance'] != null) ? "เช็คอินจากในพื้นที่ (ห่างจากร้านค้า "+number_format(inval['Head']['ChkDistance'])+" กม.)" : "ยังไม่ได้เช็คอิน");
                $("#RptConName").html((inval['Head']['ContactName'] != null) ? inval['Head']['ContactName'] : "-");
                $("#RptConPhone").html((inval['Head']['ContactPhone'] != null) ? inval['Head']['ContactPhone'] : "-");
                $("#RptConEmail").html((inval['Head']['ContactEmail'] != null) ? inval['Head']['ContactEmail'] : "-");
                $("#RptConLine").html((inval['Head']['ContactLINE'] != null) ? inval['Head']['ContactLINE'] : "-");
                $("#RptPDetail").html((inval['Head']['PlanDetail'] != null) ? inval['Head']['PlanDetail'] : "&nbsp;");
                $("#RptADetail").val((inval['Head']['ActualDetail'] != null) ? inval['Head']['ActualDetail'] : "");
                $("#RptAStart").html((inval['Head']['ActualStart'] != null) ? date_format(inval['Head']['ActualStart'], "dmYhm") : "-");
                $("#RptChkDis").html((inval['Head']['ChkDistance'] != null) ? inval['Head']['ChkDistance']+" กม." : "-");

                if(inval['Head']['TripStatus'] != "1") {
                    var map = new longdo.Map({
                        placeholder: document.getElementById("RptCheckInMaps"),
                        lastview: false,
                        language: 'th',
                        ui: longdo.UiComponent.Mobile
                    });
                
                    map.Layers.setBase(longdo.Layers.GRAY);
                    map.zoom(15,true);
                    map.zoomRange({ min: 10, max: 20 });
                    map.location({ lon: inval['Head']['ActualLon'], lat: inval['Head']['ActualLat'] }, true);

                    /* CheckIn Marker */
                    var CheckPin = new longdo.Marker({ lon: inval['Head']['ActualLon'], lat: inval['Head']['ActualLat'] },{ icon: { html: '<i class=\'fas fa-male fa-4x\' style=\'color: #fc0380;\'></i>', offset: { x: 9, y: 48 } }, weight: 999 });
                    map.Overlays.add(CheckPin);

                    if(inval['Head']['PlanLat'] != null && inval['Head']['PlanLon'] != null) {
                        var StorePin = new longdo.Marker({ lon: inval['Head']['PlanLon'], lat: inval['Head']['PlanLat'] },{ icon: { html: '<i class=\'fas fa-map-marker-alt fa-2x text-primary\'></i>', offset: { x: 9, y: 24 } }, weight: 999 });
                        map.Overlays.add(StorePin);
                        /* 
                            Safezone Generator
                            Add CirCle radius ~5km.
                            ระยะห่าง 1 องศา Lat/Lon = ~111.195km. @ เส้นศูนย์สูตรโลก
                            ~1km. = 1/111.195 = 0.008993210126355 degree
                        */
                        var SafeRange = parseFloat(inval['Head']['TarDistance']) * 0.008993210126355;
                        var SafeZone = new longdo.Circle({
                            lon: inval['Head']['PlanLon'], lat: inval['Head']['PlanLat']
                        }, SafeRange, {
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

                    $("#RptADetail").attr("disabled",false);
                    $(document).off("focusout","#RptADetail").on("focusout","#RptADetail", function() {
                        let ActualDetail = $("#RptADetail").val();
                        AddCheckInDetail(ActualDetail,TripID);
                    });
                } else {
                    $("#RptADetail").attr("disabled",true);
                    $("#RptCheckInMaps").empty();
                }
                $("#ModalCheckInReport").modal("show");
            });
        }
    });
}


const DTTB_TH = {
    "emptyTable": "ไม่มีข้อมูลในตาราง",
    "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
    "infoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
    "infoThousands": ",",
    "lengthMenu": "แสดง _MENU_ แถว",
    "loadingRecords": "กำลังโหลดข้อมูล...<i class='fas fa-spinner fa-spin'></i>",
    "processing": "กำลังดำเนินการ...",
    "zeroRecords": "ไม่พบข้อมูล",
    "aria": {
        "sortAscending": ": เปิดใช้งานการเรียงข้อมูลจากน้อยไปมาก",
        "sortDescending": ": เปิดใช้งานการเรียงข้อมูลจากมากไปน้อย"
    },
    "autoFill": {
        "cancel": "ยกเลิก",
        "fill": "กรอกทุกช่องด้วย",
        "fillHorizontal": "กรอกตามแนวนอน",
        "fillVertical": "กรอกตามแนวตั้ง"
    },
    "buttons": {
        "collection": "ชุดข้อมูล",
        "colvis": "การมองเห็นคอลัมน์",
        "colvisRestore": "เรียกคืนการมองเห็น",
        "copy": "คัดลอก",
        "copyKeys": "กดปุ่ม Ctrl หรือ Command + C เพื่อคัดลอกข้อมูลบนตารางไปยัง Clipboard ที่เครื่องของคุณ",
        "copySuccess": {
            "_": "คัดลอกช้อมูลแล้ว จำนวน %ds แถว",
            "1": "คัดลอกข้อมูลแล้ว จำนวน 1 แถว"
        },
        "copyTitle": "คัดลอกไปยังคลิปบอร์ด",
        "csv": "CSV",
        "excel": "<i class='fas fa-file-excel'></i> Excel",
        "pageLength": {
            "_": "แสดงข้อมูล %d แถว",
            "-1": "แสดงข้อมูลทั้งหมด"
        },
        "pdf": "PDF",
        "print": "สั่งพิมพ์",
        "createState": "สร้างสถานะ",
        "removeAllStates": "ลบสถานะทั้งหมด",
        "removeState": "ลบสถานะ",
        "renameState": "เปลี่ยนชื่อสถานะ",
        "savedStates": "บันทึกสถานะ",
        "stateRestore": "คืนค่าสถานะ",
        "updateState": "แก้ไขสถานะ"
    },
    "infoEmpty": "แสดงทั้งหมด 0 to 0 of 0 รายการ",
    "search": "ค้นหา :",
    "thousands": ",",
    "datetime": {
        "amPm": [
            "เที่ยงวัน",
            "เที่ยงคืน"
        ],
        "hours": "ชั่วโมง",
        "minutes": "นาที",
        "months": {
            "0": "มกราคม",
            "1": "กุมภาพันธ์",
            "10": "พฤศจิกายน",
            "11": "ธันวาคม",
            "2": "มีนาคม",
            "3": "เมษายน",
            "4": "พฤษภาคม",
            "5": "มิถุนายน",
            "6": "กรกฎาคม",
            "7": "สิงหาคม",
            "8": "กันยายน",
            "9": "ตุลาคม"
        },
        "next": "ถัดไป",
        "seconds": "วินาที",
        "unknown": "ไม่ทราบ",
        "weekdays": [
            "วันอาทิตย์",
            "วันจันทร์",
            "วันอังคาร",
            "วันพุธ",
            "วันพฤหัส",
            "วันศุกร์",
            "วันเสาร์"
        ],
        "previous": "ก่อนหน้า"
    },
    "decimal": "จุดทศนิยม",
    "editor": {
        "close": "ปิด",
        "create": {
            "button": "สร้าง",
            "submit": "สร้างข้อมูล",
            "title": "สร้างข้อมูลใหม่"
        },
        "edit": {
            "button": "แก้ไข",
            "submit": "บันทึก",
            "title": "แก้ไขข้อมูล"
        },
        "error": {
            "system": "เกิดข้อผิดพลาดของระบบ (&lt;a target=\"\\\" rel=\"nofollow\" href=\"\\\"&gt;ดูข้อมูลเพิ่มเติม)."
        },
        "remove": {
            "button": "ลบ",
            "submit": "ลบข้อมูล",
            "title": "ลบข้อมูล",
            "confirm": {
                "_": "คุณแน่ใจที่จะลบข้อมูล %d รายการนี้ หรือไม่?",
                "1": "คุณแน่ใจที่จะลบข้อมูลรายการนี้ หรือไม่?"
            }
        },
        "multi": {
            "restore": "ยกเลิกการแก้ไข",
            "title": "หลายค่า",
            "info": "รายการที่เลือกมีค่าที่แตกต่างกันสำหรับอินพุตนี้ หากต้องการแก้ไขและตั้งค่ารายการทั้งหมดสำหรับการป้อนข้อมูลนี้เป็นค่าเดียวกัน ให้คลิกหรือแตะที่นี่ มิฉะนั้น รายการเหล่านั้นจะคงค่าแต่ละรายการไว้",
            "noMulti": "อินพุตนี้สามารถแก้ไขทีละรายการได้ แต่ไม่สามารถแก้ไขเป็นส่วนหนึ่งของกลุ่มได้"
        }
    },
    "searchBuilder": {
        "add": "เพิ่มเงื่อนไข",
        "clearAll": "ยกเลิกทั้งหมด",
        "condition": "เงื่อนไข",
        "data": "ข้อมูล",
        "deleteTitle": "ลบเงื่อนไขการกรอง",
        "logicAnd": "และ",
        "logicOr": "หรือ",
        "button": {
            "0": "สร้างการค้นหา",
            "_": "ตัวสร้างการค้นหา (%d)"
        },
        "conditions": {
            "date": {
                "after": "ก่อน",
                "before": "ก่อน",
                "between": "ระหว่าง",
                "equals": "เท่ากับ",
                "not": "ไม่",
                "notEmpty": "ไม่ใช่ระหว่าง"
            },
            "number": {
                "between": "ระหว่าง",
                "equals": "เท่ากับ",
                "gt": "มากกว่า",
                "gte": "มากกว่าเท่ากับ",
                "lt": "น้อยกว่า",
                "lte": "น้อยกว่าเท่ากับ",
                "not": "ไม่",
                "notBetween": "ไม่ใช่ระหว่าง"
            },
            "string": {
                "contains": "ประกอบด้วย",
                "endsWith": "ลงท้ายด้วย",
                "equals": "เท่ากับ",
                "not": "ไม่",
                "startsWith": "เริ่มต้นด้วย",
                "notContains": "ไม่มี",
                "notStartsWith": "ไม่เริ่มต้นด้วย",
                "notEndsWith": "ไม่ลงท้ายด้วย"
            },
            "array": {
                "equals": "เท่ากับ",
                "contains": "เงื้อนไข",
                "not": "ไม่"
            }
        },
        "title": {
            "0": "สร้างการค้นหา",
            "_": "ตัวสร้างการค้นหา (%d)"
        },
        "value": "ค่า"
    },
    "select": {
        "cells": {
            "1": "เลือก 1 cell",
            "_": "เลือก %d cells"
        },
        "columns": {
            "1": "เลือก 1 column",
            "_": "เลือก %d columns"
        }
    },
    "stateRestore": {
        "duplicateError": "มีข้อมูลที่ใช้ชื่อนี้แล้ว",
        "emptyError": "ชื่อต้องไม่เป็นค่าว่าง",
        "emptyStates": "ไม่มีสถานะที่บันทึกไว้",
        "removeConfirm": "คุณแน่ใจหรือไม่ว่าต้องการลบ %s",
        "removeError": "ไม่สามารถลบสถานะ"
    }
}