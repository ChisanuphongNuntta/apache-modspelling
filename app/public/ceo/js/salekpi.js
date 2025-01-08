function number_format(number,decimal) {
    var options = { roundingPriority: "lessPrecision", minimumFractionDigits: decimal, maximumFractionDigits: decimal };
    var formatter = new Intl.NumberFormat("en",options);
    return formatter.format(number)
}

function ThaiMonth(m) {
    var MonthName = "";
    switch(m) {
        case 1: MonthName = "มกราคม"; break;
        case 2: MonthName = "กุมภาพันธ์"; break;
        case 3: MonthName = "มีนาคม"; break;
        case 4: MonthName = "เมษายน"; break;
        case 5: MonthName = "พฤษภาคม"; break;
        case 6: MonthName = "มิถุนายน"; break;
        case 7: MonthName = "กรกฎาคม"; break;
        case 8: MonthName = "สิงหาคม"; break;
        case 9: MonthName = "กันยายน"; break;
        case 10: MonthName = "ตุลาคม"; break;
        case 11: MonthName = "พฤศจิกายน"; break;
        case 12: MonthName = "ธันวาคม"; break;
    }
    return MonthName;
}

function GetSaleByMonth(TeamCode, SAL_JSON) {

    var TeamArr  = ["MT1","MT2","TT2","TT1","OUL","ONL","EXP"];

    var TeamCode = TeamCode;
    var TeamName = "";
    var Thead = 
        "<div class='table-responsive'>"+
            "<table class='table table-bordered table-sm' style='font-size: 12px;'>"+
                "<thead class='text-center'>"+
                    "<tr class='text-white' style='background-color: #9A1118;'>"+
                        "<th>เดือน</th>"+
                        "<th>เป้าขาย<br/>(บาท)</th>"+
                        "<th>ยอดขาย<br/>(บาท)</th>"+
                        "<th>%</th>"+
                    "</tr>"+
                "</thead>"+
                "<tbody></tbody>"+
            "</table>"+
        "</div>";
    var Tbody = "";

    switch(TeamCode) {
        case "MT1": TeamName = "ทีมโมเดิร์นเทรด 1"; break;
        case "MT2": TeamName = "ทีมโมเดิร์นเทรด 2"; break;
        case "TT2": TeamName = "ทีมต่างจังหวัด";    break;
        case "TT1": TeamName = "ทีมกรุงเทพฯ";     break;
        case "OUL": TeamName = "ทีมหน้าร้าน";      break;
        case "ONL": TeamName = "ทีมออนไลน์";     break;
        case "EXP": TeamName = "ทีมต่างประเทศ";   break;
        case "ALL": TeamName = "ทุกทีม";   break;
    }

    $("div.modal#SaleByMonth .modal-content .modal-body").html(Thead);
    $("#txt_TeamName").html(TeamName);

    var obj = jQuery.parseJSON(SAL_JSON);
    $.each(obj, function(key, inval) {
        switch(TeamCode) {
            case "ALL":
                var TarY = ActY = 0;
                for(m = 1; m <= 12; m++) {
                    var TarM = ActM = 0;
                    for(t = 0; t < TeamArr.length; t++) {
                        if(m <= 9) {
                            TarM = TarM + parseFloat(inval[TeamArr[t]]['TAR']['M0'+m]);
                            if(typeof inval[TeamArr[t]]['ACT'] !== "undefined") {
                                ActM = ActM + parseFloat(inval[TeamArr[t]]['ACT']['M0'+m]);
                            } else {
                                ActM = ActM;
                            }
                        } else {
                            TarM = TarM + parseFloat(inval[TeamArr[t]]['TAR']['M'+m]);
                            if(typeof inval[TeamArr[t]]['ACT'] !== "undefined") {
                                ActM = ActM + parseFloat(inval[TeamArr[t]]['ACT']['M'+m]);
                            } else {
                                ActM = ActM;
                            }
                        }
                    }

                    ActY = ActY + ActM;
                    if(TarM > 0) {
                        PctM = (ActM / TarM) * 100;
                    } else {
                        PctM = 0;
                    }
                    
                    if(PctM >= 100.01) {    
                        PctMClass = "bg-success text-white";
                    } else if(PctM >= 80.01 && PctM < 100) {
                        PctMClass = "text-success";
                    } else if(PctM >= 60.01 && PctM < 80) {
                        PctMClass = "text-warning";
                    } else if(PctM >= 40.01 && PctM < 60) {
                        PctMClass = "text-danger";
                    } else {
                        PctMClass = "";
                    }
                    Tbody =
                        "<tr>"+
                            "<td>"+ThaiMonth(m)+"</td>"+
                            "<td class='text-right' style='font-weight: bold;'>"+number_format(TarM,0)+"</td>"+
                            "<td class='text-right'>"+number_format(ActM,0)+"</td>"+
                            "<td class='text-center "+PctMClass+"'>"+number_format(PctM,2)+"</td>"+
                        "</tr>"; 
                    $("div.modal#SaleByMonth .modal-content .modal-body table.table tbody").append(Tbody);
                }
                for(t = 0; t < TeamArr.length; t++) {
                    TarY = TarY + parseFloat(inval[TeamArr[t]]['TAR']['YEAR']);
                }
                if(TarY > 0) {
                    PctY = (ActY / TarY) * 100;
                } else {
                    PctY = 0;
                }
                
                if(PctY >= 100.01) {  
                    PctYClass = "bg-success text-white";
                } else if(PctY >= 80.01 && PctY < 100) {
                    PctYClass = "text-success";
                } else if(PctY >= 50.01 && PctY < 75) {
                    PctMClass = "text-warning";
                } else if(PctY >= 25.01 && PctY < 50) {
                    PctYClass = "text-danger";
                } else {
                    PctYClass = "";
                }
                Tfoot =
                        "<tr class='table-active' style='font-weight: bold'>"+
                            "<td>รวมทั้งหมด</td>"+
                            "<td class='text-right' style='font-weight: bold;'>"+number_format(TarY,0)+"</td>"+
                            "<td class='text-right'>"+number_format(ActY,0)+"</td>"+
                            "<td class='text-center "+PctYClass+"'>"+number_format(PctY,2)+"</td>"+
                        "</tr>"; 
                    $("div.modal#SaleByMonth .modal-content .modal-body table.table tbody").append(Tfoot);
            break;
            default:
                for(m = 1; m <= 12; m++) {
                    var TarM = "";
                    if(m <= 9) {
                        TarM = inval[TeamCode]['TAR']['M0'+m];
                        if(typeof inval[TeamCode]['ACT'] !== "undefined") {
                            ActM = inval[TeamCode]['ACT']['M0'+m];
                        } else {
                            ActM = 0;
                        }
                    } else {
                        TarM = inval[TeamCode]['TAR']['M'+m];
                        if(typeof inval[TeamCode]['ACT'] !== "undefined") {
                            ActM = inval[TeamCode]['ACT']['M'+m];
                        } else {
                            ActM = 0;
                        }
                    }

                    TarY = inval[TeamCode]['TAR']['YEAR'];
                    if(typeof inval[TeamCode]['ACT'] !== "undefined") {
                        ActY = inval[TeamCode]['ACT']['YEAR'];
                    } else {
                        ActY = 0;
                    }

                    if(TarM > 0) {
                        PctM = (ActM / TarM) * 100;
                    } else {
                        PctM = 0;
                    }

                    if(TarY > 0) {
                        PctY = (ActY / TarY) * 100;
                    } else {
                        PctY = 0;
                    }

                    if(PctM >= 100.01) {    
                        PctMClass = "bg-success text-white";
                    } else if(PctM >= 80.01 && PctM < 100) {
                        PctMClass = "text-success";
                    } else if(PctM >= 60.01 && PctM < 80) {
                        PctMClass = "text-warning";
                    } else if(PctM >= 40.01 && PctM < 60) {
                        PctMClass = "text-danger";
                    } else {
                        PctMClass = "";
                    }

                    if(PctY >= 100.01) {  
                        PctYClass = "bg-success text-white";
                    } else if(PctY >= 80.01 && PctY < 100) {
                        PctYClass = "text-success";
                    } else if(PctY >= 50.01 && PctY < 75) {
                        PctMClass = "text-warning";
                    } else if(PctY >= 25.01 && PctY < 50) {
                        PctYClass = "text-danger";
                    } else {
                        PctYClass = "";
                    }

                    Tbody =
                        "<tr>"+
                            "<td>"+ThaiMonth(m)+"</td>"+
                            "<td class='text-right' style='font-weight: bold;'>"+number_format(TarM,0)+"</td>"+
                            "<td class='text-right'>"+number_format(ActM,0)+"</td>"+
                            "<td class='text-center "+PctMClass+"'>"+number_format(PctM,2)+"</td>"+
                        "</tr>"; 
                    $("div.modal#SaleByMonth .modal-content .modal-body table.table tbody").append(Tbody);
                }
                Tfoot =
                    "<tr class='table-active' style='font-weight: bold'>"+
                        "<td>รวมทั้งปี</td>"+
                        "<td class='text-right'>"+number_format(TarY,0)+"</td>"+
                        "<td class='text-right'>"+number_format(ActY,0)+"</td>"+
                        "<td class='text-center "+PctYClass+"'>"+number_format(PctY,2)+"</td>"+
                    "</tr>"; 
                $("div.modal#SaleByMonth .modal-content .modal-body table.table tbody").append(Tfoot);
            break;
        }
    });
    $("div.modal#SaleByMonth").modal("show");
}

function GetSaleBySlp(TeamCode,UserKey,SlpName,SAL_JSON) {
    var TeamCode = TeamCode;
    var UserKey  = UserKey;
    var SlpName  = SlpName;

    var Thead = 
        "<div class='table-responsive'>"+
            "<table class='table table-bordered table-sm' style='font-size: 12px;'>"+
                "<thead class='text-center'>"+
                    "<tr class='text-white' style='background-color: #9A1118;'>"+
                        "<th>เดือน</th>"+
                        "<th>เป้าขาย<br/>(บาท)</th>"+
                        "<th>ยอดขาย<br/>(บาท)</th>"+
                        "<th>%</th>"+
                    "</tr>"+
                "</thead>"+
                "<tbody></tbody>"+
            "</table>"+
        "</div>";
    
    $("div.modal#SaleByMonth .modal-content .modal-body").html(Thead);
    $("#txt_TeamName").html(SlpName+" ["+$("#filt_year").val()+"]");
    var obj = jQuery.parseJSON(SAL_JSON);
    $.each(obj, function(key, inval) {
        var TarY = ActY = 0;
        for(m = 1; m <= 12; m++) {
            if(m <= 9) {
                TarM = inval[TeamCode][UserKey]['TAR']['M0'+m];
                if(typeof inval[TeamCode][UserKey]['ACT'] !== "undefined") {
                    ActM = inval[TeamCode][UserKey]['ACT']['M0'+m];
                } else {
                    ActM = 0;
                }
            } else {
                TarM = inval[TeamCode][UserKey]['TAR']['M'+m];
                if(typeof inval[TeamCode][UserKey]['ACT'] !== "undefined") {
                    ActM = inval[TeamCode][UserKey]['ACT']['M'+m];
                } else {
                    ActM = 0;
                }
            }

            TarY = TarY + parseFloat(TarM);
            ActY = ActY + parseFloat(ActM);

            if(TarM > 0) {
                PctM = (ActM / TarM) * 100;
            } else {
                PctM = 0;
            }

            if(TarY > 0) {
                PctY = (ActY / TarY) * 100;
            } else {
                PctY = 0;
            }
            
            if(PctM >= 100.01) {    
                PctMClass = "bg-success text-white";
            } else if(PctM >= 80.01 && PctM < 100) {
                PctMClass = "text-success";
            } else if(PctM >= 60.01 && PctM < 80) {
                PctMClass = "text-warning";
            } else if(PctM >= 40.01 && PctM < 60) {
                PctMClass = "text-danger";
            } else {
                PctMClass = "";
            }

            if(PctY >= 100.01) {    
                PctYClass = "bg-success text-white";
            } else if(PctY >= 80.01 && PctY < 100) {
                PctYClass = "text-success";
            } else if(PctY >= 60.01 && PctY < 80) {
                PctYClass = "text-warning";
            } else if(PctY >= 40.01 && PctY < 60) {
                PctYClass = "text-danger";
            } else {
                PctYClass = "";
            }

            Tbody =
                "<tr>"+
                    "<td>"+ThaiMonth(m)+"</td>"+
                    "<td class='text-right' style='font-weight: bold;'>"+number_format(TarM,0)+"</td>"+
                    "<td class='text-right'>"+number_format(ActM,0)+"</td>"+
                    "<td class='text-center "+PctMClass+"'>"+number_format(PctM,2)+"</td>"+
                "</tr>"; 
            $("div.modal#SaleByMonth .modal-content .modal-body table.table tbody").append(Tbody);
        }
        Tfoot =
            "<tr class='table-active' style='font-weight: bold'>"+
                "<td>รวมทั้งปี</td>"+
                "<td class='text-right'>"+number_format(TarY,0)+"</td>"+
                "<td class='text-right'>"+number_format(ActY,0)+"</td>"+
                "<td class='text-center "+PctYClass+"'>"+number_format(PctY,2)+"</td>"+
            "</tr>"; 
        $("div.modal#SaleByMonth .modal-content .modal-body table.table tbody").append(Tfoot);
    });
    $("div.modal#SaleByMonth").modal("show");
}

function GetSaleKPI() {
    var filt_year  = $("#filt_year").val();
    var filt_month = $("#filt_month").val();
    var filt_team  = $("#filt_team").val();

    var MT1_TarM = MT1_TarA = MT1_TarY = "";
    var MT2_TarM = MT2_TarA = MT2_TarY = "";
    var TT1_TarM = TT1_TarA = TT1_TarY = "";
    var TT2_TarM = TT2_TarA = TT2_TarY = "";
    var OUL_TarM = OUL_TarA = OUL_TarY = "";
    var ONL_TarM = ONL_TarA = OUL_TarY = "";
    var PctClass = SAL_JSON = "";

    var txt_year = parseFloat(filt_year);
    var txt_month = $("#filt_month option:selected").text();
    var SaTbody = SaTfoot = "";
    $(".overlay").show();
    $.ajax({
        url: "ajax/ajaxCEO.php?p=SaleKPI",
        type: "POST",
        data: {
            y: filt_year,
            m: filt_month,
            t: filt_team
        },
        success: function(result) {
            $(".overlay").hide();
            SAL_JSON = result;
            
            var obj = jQuery.parseJSON(result);
            var SUM_TarM = SUM_TarA = SUM_TarY = 0;
            var SUM_ActM = SUM_ActA = SUM_ActY = 0;
            var SUM_PctM = SUM_PctA = SUM_PctY = 0;
            $.each(obj, function(key, inval) {
                var TeamCode = ["MT1","MT2","TT2","TT1","OUL","ONL","EXP"];
                var TeamName = [
                            "โมเดิร์นเทรด 1<br/>(K. ส้ม)",
                            "โมเดิร์นเทรด 2<br/>(K. มุ่น)",
                            "ต่างจังหวัด<br/>(K. นิด)",
                            "กรุงเทพฯ<br/>(K. แจ็ค)",
                            "หน้าร้าน<br/>(K. แจ็ค)",
                            "ออนไลน์<br/>(K. โมเม)",
                            "ต่างประเทศ<br/>(K. ส้ม)",
                        ];
                switch(filt_team) {
                    case "ALL":
                        var SaThead = 
                            "<div class='table-responsive'>"+
                                "<table class='table table-bordered table-sm' style='font-size: 12px;'>"+
                                    "<thead class='text-center'>"+
                                        "<tr class='text-white' style='background-color: #9A1118;'>"+
                                            "<th>ทีม</th>"+
                                            "<th>รายละเอียด</th>"+
                                            "<th>เป้าขาย<br/>(บาท)</th>"+
                                            "<th>ยอดขาย<br/>(บาท)</th>"+
                                            "<th>%</th>"+
                                        "</tr>"+
                                    "</thead>"+
                                    "<tbody>"+
                                    "</tbody>"+
                                "</table>"+
                            "</div>";
                        
                        $("#SAContent").html(SaThead);
                        for(t = 0; t < TeamCode.length; t++) {
                            var b = {};
                            if(filt_month <= 9) {
                                b[TeamCode[t]+'_TarM'] = inval[TeamCode[t]]['TAR']['M0'+filt_month];

                                if(typeof inval[TeamCode[t]]['ACT'] !== "undefined") {
                                    b[TeamCode[t]+'_ActM'] = inval[TeamCode[t]]['ACT']['M0'+filt_month];
                                } else {
                                    b[TeamCode[t]+'_ActM'] = 0;
                                }
                            } else {
                                b[TeamCode[t]+'_TarM'] = inval[TeamCode[t]]['TAR']['M'+filt_month];

                                if(typeof inval[TeamCode[t]]['ACT'] !== "undefined") {
                                    b[TeamCode[t]+'_ActM'] = inval[TeamCode[t]]['ACT']['M'+filt_month];
                                } else {
                                    b[TeamCode[t]+'_ActM'] = 0;
                                }
                            }
                            if(typeof inval[TeamCode[t]]['ACT'] !== "undefined") {
                                if(b[TeamCode[t]+'_TarM'] > 0) {
                                    b[TeamCode[t]+'_PctM'] = (b[TeamCode[t]+'_ActM'] / b[TeamCode[t]+'_TarM'])*100;
                                } else {
                                    b[TeamCode[t]+'_PctM'] = 0;
                                }
                                if(inval[TeamCode[t]]['TAR']['YEAR'] > 0) {
                                    b[TeamCode[t]+'_PctY'] = (inval[TeamCode[t]]['ACT']['YEAR'] / inval[TeamCode[t]]['TAR']['YEAR'])*100;
                                } else {
                                    b[TeamCode[t]+'_PctY'] = 0;
                                }
                                b[TeamCode[t]+'_ActY'] = inval[TeamCode[t]]['ACT']['YEAR'];
                            } else {
                                b[TeamCode[t]+'_PctM'] = b[TeamCode[t]+'_PctY'] = b[TeamCode[t]+'_ActY'] = 0;
                            }
                            
                            PctMClass = "";
                            if(b[TeamCode[t]+'_PctM'] >= 100.01) {
                                PctMClass = "bg-success text-white";
                            } else if(b[TeamCode[t]+'_PctM'] >= 80.01 && b[TeamCode[t]+'_PctM'] < 100) {
                                PctMClass = "text-success";
                            } else if(b[TeamCode[t]+'_PctM'] >= 60.01 && b[TeamCode[t]+'_PctM'] < 80) {
                                PctMClass = "text-warning";
                            } else if(b[TeamCode[t]+'_PctM'] >= 40.01 && b[TeamCode[t]+'_PctM'] < 60) {
                                PctMClass = "text-danger";
                            } else {
                                PctMClass = "";
                            }

                            PctYClass = "";
                            if(b[TeamCode[t]+'_PctY'] >= 100.01) {
                                PctYClass = "bg-success text-white";
                            } else if(b[TeamCode[t]+'_PctY'] >= 80.01 && b[TeamCode[t]+'_PctY'] < 100) {
                                PctYClass = "text-success";
                            } else if(b[TeamCode[t]+'_PctY'] >= 60.01 && b[TeamCode[t]+'_PctY'] < 80) {
                                PctYClass = "text-warning";
                            } else if(b[TeamCode[t]+'_PctY'] >= 40.01 && b[TeamCode[t]+'_PctY'] < 60) {
                                PctYClass = "text-danger";
                            } else {
                                PctYClass = "";
                            }

                            SaTbody =
                                "<tr>"+
                                    "<td rowspan='2'>"+TeamName[t]+"</td>"+
                                    "<td>เดือน"+txt_month+"</td>"+
                                    "<td class='text-right' style='font-weight: bold;'>"+number_format(b[TeamCode[t]+'_TarM'],0)+"</td>"+
                                    "<td class='text-right'>"+number_format(b[TeamCode[t]+'_ActM'],0)+"</td>"+
                                    "<td class='text-center "+PctMClass+"'>"+number_format(b[TeamCode[t]+'_PctM'],2)+"</td>"+
                                "</tr>"+
                                "<tr class='table-active' style='font-weight: bold'>"+
                                    "<td><a href='javascript:void(0);' class='btn_SAbyMonth' data-TeamCode='"+TeamCode[t]+"'><i class='fas fa-clipboard-list fa-fw fa-lg'></i> ทั้งปี "+txt_year+"</a></td>"+
                                    "<td class='text-right'>"+number_format(inval[TeamCode[t]]['TAR']['YEAR'],0)+"</td>"+
                                    "<td class='text-right'>"+number_format(b[TeamCode[t]+'_ActY'],0)+"</td>"+
                                    "<td class='text-center "+PctYClass+"'>"+number_format(b[TeamCode[t]+'_PctY'],2)+"</td>"+
                                "</tr>";
                            
                            SUM_TarM = SUM_TarM + parseFloat(b[TeamCode[t]+'_TarM']);
                            SUM_ActM = SUM_ActM + parseFloat(b[TeamCode[t]+'_ActM']);
                            SUM_TarY = SUM_TarY + parseFloat(inval[TeamCode[t]]['TAR']['YEAR']);
                            SUM_ActY = SUM_ActY + parseFloat(b[TeamCode[t]+'_ActY']);
                            $("#SAContent table.table tbody").append(SaTbody);
                        }
                        if(SUM_TarM > 0) {
                            SUM_PctM = (SUM_ActM / SUM_TarM) * 100;
                        } else {
                            SUM_PctM = 0;
                        }

                        if(SUM_TarY > 0) {
                            SUM_PctY = (SUM_ActY / SUM_TarY) * 100;
                        } else {
                            SUM_PctY = 0;
                        }

                        if(SUM_PctM >= 100.01){
                            PctMClass = "bg-success text-white";
                        } else if(SUM_PctM >= 80.01 && SUM_PctM < 100) {
                            PctMClass = "text-success";
                        } else if(SUM_PctM >= 60.01 && SUM_PctM < 80) {
                            PctMClass = "text-warning";
                        } else if(SUM_PctM >= 40.01 && SUM_PctM < 60) {
                            PctMClass = "text-danger";
                        } else {
                            PctMClass = null;
                        }

                        if(SUM_PctY >= 100.01) {
                            PctYClass = "bg-success text-white";
                        } else if(SUM_PctY >= 80.01 && SUM_PctY < 100) {
                            PctYClass = "text-success";
                        } else if(SUM_PctY >= 60.01 && SUM_PctY < 80) {
                            PctYClass = "text-warning";
                        } else if(SUM_PctY >= 40.01 && SUM_PctY < 60) {
                            PctYClass = "text-danger";
                        } else {
                            PctYClass = null;
                        }

                        SaTfoot +=
                            "<tr>"+
                                "<td rowspan='2'>รวมทุกทีม</td>"+
                                "<td>เดือน"+txt_month+"</td>"+
                                "<td class='text-right' style='font-weight: bold;'>"+number_format(SUM_TarM,0)+"</td>"+
                                "<td class='text-right'>"+number_format(SUM_ActM,0)+"</td>"+
                                "<td class='text-center "+PctMClass+"'>"+number_format(SUM_PctM, 2)+"</td>"+
                            "</tr>"+
                            "<tr class='table-active' style='font-weight: bold'>"+
                                "<td><a href='javascript:void(0);' class='btn_SAbyMonth' data-TeamCode='ALL'><i class='fas fa-clipboard-list fa-fw fa-lg'></i> ทั้งปี "+txt_year+"</a></td>"+
                                "<td class='text-right'>"+number_format(SUM_TarY,0)+"</td>"+
                                "<td class='text-right'>"+number_format(SUM_ActY,0)+"</td>"+
                                "<td class='text-center "+PctYClass+"'>"+number_format(SUM_PctY, 2)+"</td>"+
                            "</tr>";
                        $("#SAContent table.table tbody").append(SaTfoot);
                    break;
                    default:
                        var SUM_TarAll  = SUM_ActAll  = 0;
                        var SaThead = 
                            "<div class='table-responsive'>"+
                                "<table class='table table-bordered table-sm' style='font-size: 12px;'>"+
                                    "<thead class='text-center'>"+
                                        "<tr class='text-white' style='background-color: #9A1118;'>"+
                                            "<th>พนง.ขาย</th>"+
                                            "<th>เป้าขาย<br/>(บาท)</th>"+
                                            "<th>ยอดขาย<br/>(บาท)</th>"+
                                            "<th>%</th>"+
                                        "</tr>"+
                                    "</thead>"+
                                    "<tbody></tbody>"+
                                    "<tfoot></tfoot>"+
                                "</table>"+
                            "</div>";
                        
                        $("#SAContent").html(SaThead);
                        var TeamCode = [];
                        for(const [key,value] of Object.entries(inval)) {
                            if(key != "YEAR") {
                                TeamCode.push(`${key}`);
                            }
                        }
                        for(ax = 0; ax < TeamCode.length; ax++) {
                            var TEAM = TeamCode[ax];
                            var b = {};
                            switch(TEAM) {
                                case "MT100": TeamName = "ทีมโมเดิร์นเทรด 1"; break;
                                case "MT200": TeamName = "ทีมโมเดิร์นเทรด 2"; break;
                                case "TT101": TeamName = "ทีมกรุงเทพฯ"; break;
                                case "TT201": TeamName = "ทีมต่างจังหวัด (ทีม 1)"; break;
                                case "TT202": TeamName = "ทีมต่างจังหวัด (ทีม 2)"; break;
                                case "TT203": TeamName = "ทีมต่างจังหวัด (ประเทศลาว)"; break;
                                case "OUL":   TeamName = "ทีมหน้าร้าน"; break;
                                case "ONL":   TeamName = "ทีมออนไลน์"; break;
                                case "EXP101":   TeamName = "ทีมต่างประเทศ"; break;
                            }
                            SaTh =
                                "<tr class='table-danger text-center' style='font-weight: bold;'>"+
                                    "<td colspan='5'>"+TeamName+"</td>"+
                                "</tr>";
                            $("#SAContent table.table tbody").append(SaTh);

                            var UserCode = [];
                            for(const [key,value] of Object.entries(inval[TEAM])) {
                                UserCode.push(`${key}`);
                            }

                            var SUM_TarTeam = SUM_ActTeam = 0;
                            
                            var cx = 0;

                            for(bx = 0; bx < UserCode.length; bx++) {
                                var SlpCode = UserCode[bx];
                                var b = {};

                                if(filt_month <= 9) {
                                    b[TeamCode[t]+'_TarM'] = inval[TEAM][SlpCode]['TAR']['M0'+filt_month];
                                    if(typeof inval[TEAM][SlpCode]['ACT'] !== "undefined") {
                                        b[TeamCode[t]+'_ActM'] = inval[TEAM][SlpCode]['ACT']['M0'+filt_month];
                                    } else {
                                        b[TeamCode[t]+'_ActM'] = 0;
                                    }
                                } else {
                                    b[TeamCode[t]+'_TarM'] = inval[TEAM][SlpCode]['TAR']['M'+filt_month];
                                    if(typeof inval[TEAM][SlpCode]['ACT'] !== "undefined") {
                                        b[TeamCode[t]+'_ActM'] = inval[TEAM][SlpCode]['ACT']['M'+filt_month];
                                    } else {
                                        b[TeamCode[t]+'_ActM'] = 0;
                                    }
                                }

                                if(b[TeamCode[t]+'_TarM'] > 0) {
                                    PctM = (b[TeamCode[t]+'_ActM'] / b[TeamCode[t]+'_TarM']) * 100;
                                } else {
                                    PctM = 0;
                                }

                                if(PctM >= 100.01) {
                                    PctMClass = "bg-success text-white";
                                } else if(PctM >= 80.01 && PctM < 100) {
                                    PctMClass = "text-success font-weight";
                                } else if(PctM >= 60.01 && PctM < 80) {
                                    PctMClass = "text-warning";
                                } else if(PctM >= 40.01 && PctM < 60) {
                                    PctMClass = "text-danger";
                                } else {
                                    PctMClass = null;
                                }

                                SaTbody =
                                    "<tr>"+
                                        "<td class='d-flex'>"+
                                            "<div class='w-75'>"+inval[TEAM][SlpCode]['SlpName']+"</div>"+
                                            "<div class='w-25 text-right'>"+
                                                "<a href='javascript:void();' class='btn_SAbySlp' data-Name='"+inval[TEAM][SlpCode]['SlpName']+"' data-Ukey='"+SlpCode+"' data-TeamCode='"+TEAM+"'>"+
                                                    "<i class='fas fa-clipboard-list fa-fw fa-lg'></i>"+
                                                "</a>"+
                                            "</div>"+
                                        "</td>"+
                                        "<td class='text-right' style='font-weight: bold;'>"+number_format(b[TeamCode[t]+'_TarM'],0)+"</td>"+
                                        "<td class='text-right'>"+number_format(b[TeamCode[t]+'_ActM'],0)+"</td>"+
                                        "<td class='text-center "+PctMClass+"'>"+number_format(PctM,2)+"</td>"+
                                    "</tr>";
                                $("#SAContent table.table tbody").append(SaTbody);

                                SUM_TarTeam = SUM_TarTeam + parseFloat(b[TeamCode[t]+'_TarM']);
                                SUM_ActTeam = SUM_ActTeam + parseFloat(b[TeamCode[t]+'_ActM']);
                                

                                if(SUM_TarTeam > 0) {
                                    SUM_PctTeam = (SUM_ActTeam / SUM_TarTeam) * 100
                                } else {
                                    SUM_PctTeam = 0;
                                }

                                if(SUM_PctTeam >= 100.01) {
                                    SumPctMClass = "bg-success text-white";
                                } else if(SUM_PctTeam >= 80.01 && SUM_PctTeam < 100) {
                                    SumPctMClass = "text-success font-weight";
                                } else if(SUM_PctTeam >= 60.01 && SUM_PctTeam < 80) {
                                    SumPctMClass = "text-warning";
                                } else if(SUM_PctTeam >= 40.01 && SUM_PctTeam < 60) {
                                    SumPctMClass = "text-danger";
                                } else {
                                    SumPctMClass = null;
                                }

                                cx++;
                                if((TeamCode.length > 1 && cx == UserCode.length)) {
                                    SaTbody =
                                    "<tr class='table-active' style='font-weight: bold;'>"+
                                        "<td>รวม"+TeamName+"</td>"+
                                        "<td class='text-right'>"+number_format(SUM_TarTeam,0)+"</td>"+
                                        "<td class='text-right'>"+number_format(SUM_ActTeam,0)+"</td>"+
                                        "<td class='text-center "+SumPctMClass+"'>"+number_format(SUM_PctTeam,2)+"</td>"+
                                    "</tr>";
                                    $("#SAContent table.table tbody").append(SaTbody);
                                }
                                SUM_TarAll = SUM_TarAll + parseFloat(b[TeamCode[t]+'_TarM']);
                                SUM_ActAll = SUM_ActAll + parseFloat(b[TeamCode[t]+'_ActM']);
                            }
                        }
                        if(SUM_TarAll > 0) {
                            SUM_PctTeam = (SUM_ActAll / SUM_TarAll) * 100
                        } else {
                            SUM_PctTeam = 0;
                        }
                        SaTfoot =
                            "<tr class='bg-danger text-white' style='font-weight: bold;'>"+
                                "<td>รวมทุกทีม</td>"+
                                "<td class='text-right'>"+number_format(SUM_TarAll,0)+"</td>"+
                                "<td class='text-right'>"+number_format(SUM_ActAll,0)+"</td>"+
                                "<td class='text-center'>"+number_format(SUM_PctTeam,2)+"</td>"+
                            "</tr>";
                        $("#SAContent table.table tbody").append(SaTfoot);

                        if(filt_month <= 9) {
                            NoActive = inval['YEAR']['NOACTIVE']['M0'+filt_month];
                        } else {
                            NoActive = inval['YEAR']['NOACTIVE']['M'+filt_month];
                        }

                        if(inval['YEAR']['TAR'] > 0) {
                            YearPct = (inval['YEAR']['ACT'] / inval['YEAR']['TAR']) * 100;
                        } else {
                            YearPct = 0;
                        }

                        SaTfoot =
                            "<tr>"+
                                "<td colspan='2'>ยอดขาย พนง.ที่ลาออก</td>"+
                                "<td class='text-right'>"+number_format(NoActive,0)+"</td>"+
                                "<td>&nbsp;</td>"+
                            "</tr>"+
                            "<tr class='text-white' style='font-weight: bold; background-color: #9A1118;'>"+
                                "<td>รวมทั้งปี "+filt_year+"</td>"+
                                "<td class='text-right'>"+number_format(inval['YEAR']['TAR'],0)+"</td>"+
                                "<td class='text-right'>"+number_format(inval['YEAR']['ACT'],0)+"</td>"+
                                "<td class='text-center'>"+number_format(YearPct,2)+"</td>"+
                            "</tr>";
                        $("#SAContent table.table tfoot").append(SaTfoot);
                    break;
                }

                switch(filt_team) {
                    case "TT2": GetDataDM(); break;
                    case "MT2": GetDataDM(); break;
                }
            });

            $(".btn_SAbyMonth").on("click", function(e) {
                e.preventDefault();
                var TeamCode = $(this).attr("data-TeamCode");
                GetSaleByMonth(TeamCode,SAL_JSON);
            });

            $(".btn_SAbySlp").on("click", function(e) {
                e.preventDefault();
                var SlpName  = $(this).attr("data-Name");
                var TeamCode = $(this).attr("data-TeamCode");
                var UserKey  = $(this).attr("data-Ukey");
                GetSaleBySlp(TeamCode,UserKey,SlpName,SAL_JSON);
            })
        }
    });
}

function GetDataDM() {
    var filt_year  = $("#filt_year").val();
    var filt_month = $("#filt_month").val();
    var filt_team  = $("#filt_team").val();
    $.ajax({
        url: "ajax/ajaxCEO.php?p=GetDataDM",
        type: "POST",
        data: { 
            y: filt_year,
            m: filt_month,
            t: filt_team
        },
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let TableSaleCustom = 
                    `<hr class='hrprice border-secondary'>
                    <div class='table-responsive'>
                        <table class='table table-bordered table-sm' style='font-size: 12px;'>
                            <thead class='text-center'>
                                <tr class='text-white' style='background-color: #9A1118;'>
                                    <th colspan='4'>ยอดขาย พนักงาน Demon</th>
                                </tr>
                                <tr class='text-white' style='background-color: #9A1118;'>
                                    <th>พนง.ขาย</th>
                                    <th>เป้าขาย<br/>(บาท)</th>
                                    <th>ยอดขาย<br/>(บาท)</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                `+((inval['Tbody'] != "") ? inval['Tbody'] : "<tr><td colspan='4' class='text-center'>ไม่มีข้อมูล :(</td></tr>")+`
                                `+inval['TbodyAll']+`
                            </tbody>
                            <tfoot>
                                `+inval['Tfoot']+`
                            </tfoot>
                        </table>
                    </div>`;
                $("#SAContent").append(TableSaleCustom);
            });
        }
    })
    
}

function number_format(number,decimal) {
    var options = { roundingPriority: "lessPrecision", minimumFractionDigits: decimal, maximumFractionDigits: decimal };
    var formatter = new Intl.NumberFormat("en",options);
    return formatter.format(number)
}

function GetDataDM_All(Ukey) {
    var filt_year  = $("#filt_year").val();
    $.ajax({
        url: "ajax/ajaxCEO.php?p=GetDataDM_All",
        type: "POST",
        data: { Ukey: Ukey, y: filt_year },
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                $("#txt_TeamName").html(inval['SaleName']+" ["+filt_year+"]");

                let AllSaleTarget = 0;
                let AllSaleActual = 0;
                let Tbody = "";
                for(let m = 1; m <= 12; m++) {
                    let SaleTarget = parseFloat(inval['M'+m]['SaleTarget']);
                    let SaleActual = parseFloat(inval['M'+m]['SaleActual']);

                    AllSaleTarget = AllSaleTarget+SaleTarget;
                    AllSaleActual = AllSaleActual+SaleActual;

                    if(SaleTarget > 0) {
                        PctM = (SaleActual / SaleTarget) * 100;
                    } else {
                        PctM = 0;
                    }
                    
                    if(PctM >= 100.01) {    
                        PctMClass = "bg-success text-white";
                    } else if(PctM >= 80.01 && PctM < 100) {
                        PctMClass = "text-success";
                    } else if(PctM >= 60.01 && PctM < 80) {
                        PctMClass = "text-warning";
                    } else if(PctM >= 40.01 && PctM < 60) {
                        PctMClass = "text-danger";
                    } else {
                        PctMClass = "";
                    }

                    Tbody +=`
                        <tr>
                            <td>`+ThaiMonth(m)+`</td>
                            <td class='text-right' style='font-weight: bold;'>`+number_format(SaleTarget,0)+`</td>
                            <td class='text-right'>`+number_format(SaleActual,0)+`</td>
                            <td class='text-center `+PctMClass+`'>`+number_format(PctM,2)+`</td>
                        </tr>`;
                }

                if(AllSaleTarget > 0) {
                    PctY = (AllSaleActual / AllSaleTarget) * 100;
                } else {
                    PctY = 0;
                }

                if(PctY >= 100.01) {    
                    PctYClass = "bg-success text-white";
                } else if(PctY >= 80.01 && PctY < 100) {
                    PctYClass = "text-success";
                } else if(PctY >= 60.01 && PctY < 80) {
                    PctYClass = "text-warning";
                } else if(PctY >= 40.01 && PctY < 60) {
                    PctYClass = "text-danger";
                } else {
                    PctYClass = "";
                }

                let DataBody = `
                    <div class='table-responsive'>
                        <table class='table table-bordered table-sm' style='font-size: 12px;'>
                            <thead class='text-center'>
                                <tr class='text-white' style='background-color: #9A1118;'>
                                    <th>เดือน</th>
                                    <th>เป้าขาย<br/>(บาท)</th>
                                    <th>ยอดขาย<br/>(บาท)</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>`+Tbody+`</tbody>
                            <tfoot>
                                <tr class='table-active' style='font-weight: bold'>
                                    <td>รวมทั้งปี</td>
                                    <td class='text-right'>`+number_format(AllSaleTarget,0)+`</td>
                                    <td class='text-right'>`+number_format(AllSaleActual,0)+`</td>
                                    <td class='text-center `+PctYClass+`'>`+number_format(PctY,2)+`</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>`;

                $("#SaleByMonth .modal-body").html(DataBody);
                $("#SaleByMonth").modal("show");
            });
        }
    })
    
}

$(document).ready(function() {
    GetSaleKPI();

    $("#filt_year, #filt_month, #filt_team").on("change", function() {
        GetSaleKPI();
    });

    $("#HeaderModalAlertRemark").html("<i class='fas fa-exclamation-circle fa-fw fa-lg'></i> คำเตือน");
    $("#DetailModalAlertRemark").html("ข้อมูลในการรายงานยอดขายของบริษัทถือเป็นความลับสุดยอด<br/>ห้ามส่งต่อหรือทำการ Copy เด็ดขาด");
    $("#ModalAlertRemark").modal("show");
});