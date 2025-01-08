const ItemList = [];
const SITE_ID = b64_to_utf8(SS_SITE);

function OrderList() {
    let filt_y = $("#filt_y").val();
    let filt_m = $("#filt_m").val();
    
    $("#OrderList").dataTable().fnClearTable();
    $("#OrderList").dataTable().fnDraw();
    $("#OrderList").dataTable().fnDestroy();
    $("#OrderList").DataTable({
        "ajax": {
            url: "salesorders/ajax.php?p=OrderList",
            type: "POST",
            data: { DocY: filt_y, DocM: filt_m },
            async: false,
            dataType: "json",
            dataSrc: "0"
        },
        "columns": [
            { "data": "No",         class: "dt-body-right" },
            { "data": "DocDate",    class: "dt-body-center" },
            { "data": "DocDueDate", class: "dt-body-center" },
            { "data": "DocNum",     class: "dt-body-center" },
            { "data": "CardCode" },
            { "data": "U_PONo",     class: "dt-body-center" },
            { "data": "DocTotal",   class: "dt-body-right" },
            { "data": "SlpName" },
            { "data": "Status",     class: "dt-body-center" },
            { "data": "SAP",        class: "dt-body-center" },
            { "data": "BTN",        class: "dt-body-center" },
        ],
        "createdRow": (row, data, dataIndex, cells) => {
            switch(data.IntStatus) {
                case "0": $(row).addClass("table-secondary"); break;
                case "2": $(row).addClass("table-warning"); break;
                case "3": $(row).addClass("table-success"); break;
                case "4": $(row).addClass("table-danger"); break;
                case "5": $(row).addClass("table-success"); break;
            }
        },
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "pageLength": 20,
        "ordering": true,
        "language": DTTB_TH
    })
}


function GetAppData() {
    $.ajax({
        url: "salesorders/ajax.php?p=GetAppData",
        async: false,
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            var OCRD_OptTxt = OSLP_OptTxt = OCTG_OptTxt = OITM_OptTxt = "<option value='' selected disabled>กรุณาเลือก</option>";
            var SOTYPE_OptTxt = "";
            
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    /* CardCode */
                    for(i = 0; i < inval['OCRD']['Row']; i++) {
                        OCRD_OptTxt+= "<option value='"+inval['OCRD'][i]['CardCode']+"'>"+inval['OCRD'][i]['CardCode']+" | "+inval['OCRD'][i]['CardName']+"</option>";
                    }
                    $("#txt_CardCode").html(OCRD_OptTxt).selectpicker();

                    /* SlpCode */
                    for(i = 0; i < inval['OSLP']['Row']; i++) {
                        OSLP_OptTxt+= "<option value='"+inval['OSLP'][i]['SlpCode']+"'>"+inval['OSLP'][i]['SlpName']+"</option>";
                    }
                    $("#txt_SlpCode").html(OSLP_OptTxt).selectpicker();

                    /* GroupNum */
                    for(i = 0; i < inval['OCTG']['Row']; i++) {
                        OCTG_OptTxt+= "<option value='"+inval['OCTG'][i]['GroupNum']+"'>"+inval['OCTG'][i]['PymntGroup']+"</option>";
                    }
                    $("#txt_GroupNum").html(OCTG_OptTxt);

                    /* ItemCode */
                    for(i = 0; i < inval['OITM']['Row']; i++) {
                        OITM_OptTxt+= `<option value="`+inval['OITM'][i]['ItemCode']+`" data-ItemName="`+inval['OITM'][i]['ItemName']+`" data-CodeBars="`+inval['OITM'][i]['CodeBars']+`" data-UnitMsr="`+inval['OITM'][i]['UnitMsr']+`">`+inval['OITM'][i]['ItemCode']+` | `+inval['OITM'][i]['ItemName']+`</option>`;
                    }
                    $("#Add_ItemCode").html(OITM_OptTxt).selectpicker();

                    /* Sale Order Type */
                    // for(i = 0; i < inval['SO_TYPE']['Row']; i++) {
                    //     let OptDef = (inval['SO_TYPE'][i]['Default'] == 'Y') ? " selected" : "" ;
                    //     SOTYPE_OptTxt+= "<option value='"+inval['SO_TYPE'][i]['Value']+"'"+OptDef+">"+inval['SO_TYPE'][i]['Text']+"</option>";
                    // }
                    // $("#txt_U_SO_TYPE").html(SOTYPE_OptTxt);
                }
            });
        }
    });
}

function GetCardInfo(CardCode) {
    $.ajax({
        url: "salesorders/ajax.php?p=GetCardInfo",
        type: "POST",
        data: { CardCode: CardCode },
        async: false,
        success: function(result) {
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    $("#txt_LicTradeNum").val(inval['TaxID']);
                    $("#txt_Billto").html("<option value='' selected disabled>กรุณาเลือก</option>"+inval['outputB']).val(inval['BilltoCode']).change();
                    $("#txt_Shipto").html("<option value='' selected disabled>กรุณาเลือก</option>"+inval['outputS']).val(inval['ShiptoCode']).change();
                    $("#txt_TaxType").val(inval['VatGroup']).change();
                    $("#txt_SlpCode").selectpicker('destroy').val(inval['SlpCode']).change().selectpicker();
                    $("#txt_GroupNum").val(inval['GroupNum']).change();
                    $("#txt_DocType").val(inval['CardType']).change();
                    $("#txt_balance").html(inval['Balance']);
                    $("#txt_creditline").html(inval['CreditLine']);
                }
            });
        }
    });
}

function Chk_SPPrice(TotalPrice, DefaultPrice, GrandPrice) {
    let PriceCheck = parseFloat(TotalPrice);
    let PriceDefault = 0;
    if(SITE_ID == '0') {
        PriceDefault = (GrandPrice*0.75);
    }else{
        PriceDefault = (GrandPrice*0.70);
    }
    if(PriceCheck < PriceDefault) {
        $("#Chk_SPrice").prop('checked',true).attr({ readonly: true, disabled: true});
    } else {
        $("#Chk_SPrice").prop('checked',false).removeAttr("readonly disabled");
    }
}

function CheckForm(StepNow, StepTo) {
    let Now = StepNow;
    let To  = StepTo;
    let ErrorPoint = 0;
    let ErrorID    = [];
    let SuccessID  = [];
    let CheckID    = [];

    switch(Now) {
        case 1:  CheckID = ["txt_CardCode", "txt_DocType", "txt_TaxType", "txt_Billto", "txt_Shipto", "txt_SlpCode", "txt_DocDate", "txt_DocDueDate", "txt_GroupNum"]; /* "txt_U_SO_TYPE" */ break;
        default: CheckID = []; break;
    }

    if(CheckID.length > 0) {
        for(i = 0; i < CheckID.length; i++) {
            $("#"+CheckID[i]).removeClass("is-valid is-invalid");
            let txt_Value = $("#"+CheckID[i]).val();
            if(txt_Value == null || txt_Value == "" || txt_Value == "null") {
                ErrorPoint = ErrorPoint+1;
                ErrorID.push(CheckID[i]);
            } else {
                SuccessID.push(CheckID[i]);
            }
        }
    }
    if(ErrorPoint > 0) {
        for(let i = 0; i < ErrorID.length; i++) { $("#"+ErrorID[i]).removeClass("is-valid is-invalid").addClass("is-invalid"); }
        for(let i = 0; i < SuccessID.length; i++) { $("#"+SuccessID[i]).removeClass("is-invalid is-invalid").addClass("is-valid"); }
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณากรอกข้อมูลให้ครบถ้วน");
        $("#alert_modal").modal('show');
    } else {
        switch(Now) {
            case 1:
                for(let i = 0; i < SuccessID.length; i++) { $("#"+SuccessID[i]).removeClass("is-invalid is-invalid").addClass("is-valid"); }
                $(".Step"+Now).addClass("d-none");
                $(".Step"+To).removeClass("d-none");

                $("#txt_CardCode").selectpicker("destroy").attr("disabled",true).selectpicker();
            break;
            case 2:
                let ItemRow = ItemList.length;
                if(ItemRow == 0) {
                    if(To == 1) {
                        $(".Step"+Now).addClass("d-none");
                        $(".Step"+To).removeClass("d-none");
                    } else {
                        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                        $("#alert_body").html("กรุณาเพิ่มรายการสินค้าอย่างน้อย 1 รายการ");
                        $("#alert_modal").modal('show');
                    }
                } else {
                    $(".Step"+Now).addClass("d-none");
                    $(".Step"+To).removeClass("d-none");
                    OrderPreview();
                }
            break;
            default:
                $(".Step"+Now).addClass("d-none");
                $(".Step"+To).removeClass("d-none");
            break;
        }
    }

    // $(".Step"+Now).addClass("d-none");
    // $(".Step"+To).removeClass("d-none");
}

function AddItem() {
    $(document).off("click","#btn-AddItem").on("click","#btn-AddItem", function() {
        let Header  = "<i class='fas fa-plus fa-fw fa-1x'></i> เพิ่มรายการใหม่";
        $("#Add_Header").html(Header);
        let BtnSave = "<i class='fas fa-plus fa-fw fa-1x'></i> เพิ่ม";
        $("#btn-AddRow").html(BtnSave);

        $("#Add_RowID").val("-1");
        $("#Add_ItemCode").selectpicker("destroy").val('').change().selectpicker();
        $("#Add_Quantity").val(0);
        $("#Add_GrandPrice, #Add_UnitPrice").val('0.00');
        $("#Add_GrandPrice, #Add_Discount").attr("disabled",true);
        $("#Add_ItemName, #Add_CodeBars, #Add_UnitMsr, #Add_Discount").val('');
        $("#Add_WhsCode").html("<option value='' selected disabled>กรุณาเลือก</option>").attr("disabled",true);

        $("#ModalAddItem").modal("show");
        $("input[type='checkbox']").prop('checked',false).removeAttr("disabled");
    });

    $(document).off("change","#Add_ItemCode").on("change","#Add_ItemCode", function() {
        let ItemName = $("#Add_ItemCode option:selected").attr("data-ItemName");
        let CodeBars = $("#Add_ItemCode option:selected").attr("data-CodeBars");
        let UnitMsr  = $("#Add_ItemCode option:selected").attr("data-UnitMsr");

        $("#Add_ItemName").val(ItemName);
        $("#Add_CodeBars").val(CodeBars);
        $("#Add_UnitMsr").val(UnitMsr);
        $("#Add_Quantity").focus();
    });

    $(document).off("focusout", "#Add_Quantity").on("focusout", "#Add_Quantity", function(e) {
        e.preventDefault();
        if($(this).val() > 0) {
            $("#btn-calprice").click();
        }
    });

    $(document).off("keypress", "#Add_Quantity").on("keypress", "#Add_Quantity", function(e) {
        let kbd = e.key;
        if(kbd === "Enter") {
            e.preventDefault();
            if($(this).val() > 0) {
                $("#btn-calprice").click();
            }
        }
    });

    $(document).off("click", "#btn-calprice").on("click", "#btn-calprice", function(e) {
        e.preventDefault();
        let CardCode = $("#txt_CardCode").val();
        let ItemCode = $("#Add_ItemCode").val();
        let Quantity = $("#Add_Quantity").val();

        if(ItemCode.substr(0,2) == 'RV') {
            $("#Add_GrandPrice").attr("readonly", false);
            $("#Add_Discount").val("");
        }else{
            const DPCODE = b64_to_utf8(SS_DEPTCODE);
            $("#Add_GrandPrice").attr("readonly", false);
            // if(DPCODE == "DP002") {
            //     $("#Add_GrandPrice").attr("readonly", false); 
            // } else {
            //     $("#Add_GrandPrice").attr("readonly", true); 
            // }
            // $("#Add_Discount").val(25);
            $("#Add_Discount").val("");
        }

        if(CardCode == "" || ItemCode == "" || Quantity < 1) {
            $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
            $("#alert_body").html("กรุณาเลือกชื่อลูกค้า รหัสสินค้า และจำนวนสินค้าให้ครบถ้วน");
            $("#alert_modal").modal('show');
        } else {
            GetItemDetail(CardCode, ItemCode, Quantity);
            $("#Add_GrandPrice, #Add_Discount").attr("disabled",false);
        }
    });
}

function GetItemDetail(CardCode, ItemCode, Quantity) {
    $("#overlay").show();
    $("#Add_ItemHistory tbody").html("<tr><td colspan='6' class='text-center'>ไม่มีข้อมูล :(</td></tr>");
    $("#Add_WhsCode").html("<option value='' selected disabled>กรุณาเลือก</option>").attr("disabled",true);

    $.ajax({
        url: "salesorders/ajax.php?p=GetItemDetail",
        type: "POST",
        data: { CardCode: CardCode, ItemCode: ItemCode, Quantity: Quantity },
        async: false,
        success: function(result) {
            $("#overlay").hide();
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                /* History */
                if(inval['History']['Row'] == 0) {
                    $("#Add_ItemHistory tbody").html("<tr><td colspan='6' class='text-center'>ไม่มีข้อมูล :(</td></tr>");
                } else {
                    let ItemTbody = "";
                    for(i = 0; i < inval['History']['Row']; i++) {
                        ItemTbody+=
                            "<tr>"+
                                "<td class='text-center'>"+inval['History'][i]['DocDate']+"</td>"+
                                "<td class='text-end'>"+inval['History'][i]['Quantity']+"</td>"+
                                "<td class='text-end'>"+inval['History'][i]['PriceBefDi']+"</td>"+
                                "<td class='text-center'>"+inval['History'][i]['Discount']+"</td>"+
                                "<td class='text-success text-end'>"+inval['History'][i]['Price']+"</td>"+
                                "<td class='text-end'>"+inval['History'][i]['VatSum']+"</td>"+
                            "</tr>";
                    }
                    $("#Add_ItemHistory tbody").html(ItemTbody);
                }
                /* Warehouse */
                $("#Add_WhsCode").html(inval['WHSE']['Opt']);
                (inval['WHSE']['Row'] == 1) ? $("#Add_WhsCode").val(inval['WHSE']['Def']).removeAttr("disabled") : null ;

                /* DP and CXST */
                let DP   = parseFloat(b64_to_utf8(inval['DefaultPrice'])).toFixed(2);
                let CXST = parseFloat(b64_to_utf8(inval['CXST'])).toFixed(2);

                let UnitPrice = (parseFloat(b64_to_utf8(inval['DefaultPrice']))*0.75).toFixed(2);

                $("#Add_GrandPrice, #Chk_DefaultPrice").val(DP);
                $("#Add_UnitPrice").val(UnitPrice);
                $("#Chk_Cxst").val(CXST);
                $("#Add_GrandPrice").focus();

                $("#Add_GrandPrice, #Add_Discount").focusout(function() {
                    var GrandPrice = $("#Add_GrandPrice").val();
                    var Discount   = $("#Add_Discount").val();
                    var Chk_DP     = $("#Chk_DefaultPrice").val();

                    if(ItemCode.substr(0,2) == 'RV') {
                        $("#Add_Discount").val("");
                    }else{
                        // if($("#Add_Discount").val() == "" || $("#Add_Discount").val() == 0) {
                        //     $("#Add_Discount").val(25);
                        // }
                    }

                    if(GrandPrice.length == 0) {
                        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                        $("#alert_body").html("กรุณากรอกราคาให้ถูกต้อง");
                        $("#alert_modal").modal('show');
                    } else {
                        if(Discount.length > 0) {
                            let DiscPrefix = Discount.charAt(0);
                            if(DiscPrefix != '*') {
                                let pattern =  /^[0-9-.]+$/;
                                let result  = pattern.test(Discount);
                                if (result == false) {
                                    $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พอข้อผิดพลาด!");
                                    $("#alert_body").html("กรุณากรอกส่วนลดให้ถูกต้อง<br/>(ใช้ตัวเลข และเครื่องหมายลบ (-) คั่นส่วนลดระหว่าง STEP ได้เท่านั้น)");
                                    $("#alert_modal").modal('show');
                                } else {
                                    let disStep = Discount.split("-");
                                    let errorPoint = 0;
                                    let stepPrice = GrandPrice;
                                    let conDisStep = 0;
                                    if(disStep.length <= 4) {
                                        for (i = 0; i < disStep.length; i++) {
                                            conDisStep = conDisStep+parseInt(disStep[i]);
                                        }
                                        if (conDisStep > 100.00) {
                                            errorPoint++;
                                        }
                                        if (errorPoint > 0) {
                                            $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พอข้อผิดพลาด!");
                                            $("#alert_body").html("กรุณากรอกส่วนลดให้ถูกต้อง<br/>(ส่วนลดต้องไม่เกิน 100%)");
                                            $("#alert_modal").modal('show');
                                        } else {
                                            for (i = 0; i < disStep.length; i++) {
                                                let conDisStep = parseInt(disStep[i]);
                                                let stepDiscount = stepPrice*(disStep[i]/100);
                                                stepPrice = stepPrice - stepDiscount;
                                            }
                                            $("#Add_UnitPrice").val(stepPrice.toFixed(2));
                                            Chk_SPPrice(stepPrice, Chk_DP, GrandPrice);
                                        }
                                    } else {
                                        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พอข้อผิดพลาด!");
                                        $("#alert_body").html("กรุณากรอกส่วนลดต้องไม่เกิน 4 สเต็ป");
                                        $("#alert_modal").modal('show');
                                    }
                                }
                            } else {
                                let pattern =  /^[0-9*.]+$/;
                                let result = pattern.test(Discount);
                                if (result == false) {
                                    $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พอข้อผิดพลาด!");
                                    $("#alert_body").html("กรุณากรอกส่วนลดให้ถูกต้อง<br/>(ระบุจำนวนส่วนลดหลังเครื่องหมายดอกจันทร์ (*) เท่านั้น)");
                                    $("#alert_modal").modal('show');
                                } else {
                                    var DiscAmount = parseFloat(Discount.substring(1));
                                    stepPrice = GrandPrice-DiscAmount;
                                    if(DiscAmount <= GrandPrice) {
                                        $("#Add_UnitPrice").val(stepPrice.toFixed(2));
                                        Chk_SPPrice(stepPrice, Chk_DP, GrandPrice);
                                    } else {
                                        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พอข้อผิดพลาด!");
                                        $("#alert_body").html("กรุณากรอกส่วนลดให้ถูกต้อง<br/>(ส่วนลดต้องไม่เกินราคาขาย)");
                                        $("#alert_modal").modal('show');
                                    }
                                }
                            }
                        } else {
                            $("#Add_UnitPrice").val(GrandPrice);
                            if(GrandPrice > 0) {
                                Chk_SPPrice(GrandPrice, Chk_DP, GrandPrice);
                            }
                        }
                    }
                });

                $(document).off("click","#btn-AddRow").on("click","#btn-AddRow", function(e) {
                    e.preventDefault();
                    $("#Add_GrandPrice, #Add_Discount").focusout();
                    AddNewRow();
                });
            });
        }
    })
}

function AddNewRow() {
    let EditRow      = $("#Add_RowID").val();
    let ItemCode     = $("#Add_ItemCode").val();
    let ItemName     = $("#Add_ItemName").val();
    let CodeBars     = $("#Add_CodeBars").val();
    let UnitMsr      = $("#Add_UnitMsr").val();
    let Cost         = $("#Chk_Cxst").val();
    let GrandPrice   = parseFloat($("#Add_GrandPrice").val()).toFixed(2);
    let Discount     = $("#Add_Discount").val();
    let UnitPrice    = parseFloat($("#Add_UnitPrice").val()).toFixed(2);
    let ItemQuantity = $("#Add_Quantity").val();
    let ItemWhse     = $("#Add_WhsCode").val();
    let SPPrice      = "";
    let SPPrice_Icon = "";

    let LineTotal = ItemQuantity * UnitPrice;

    //  || ItemWhse == null
    if (ItemCode == null || GrandPrice.length == 0 || (ItemQuantity.length == 0 || ItemQuantity < 1) || ItemWhse == null){
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณากรอกข้อมูลให้ครบถ้วน");
        $("#alert_modal").modal('show');
    } else {
        if($("#Chk_SPrice").is(":checked")) {
            SPPrice_Icon = "<i class='fas fa-check fa-fw fa-1x'></i>";
            SPPrice      = "Y";
        } else {
            SPPrice_Icon = "";
            SPPrice      = "N";
        }
        if(EditRow == "-1") {
            const NewRow = [];
            NewRow['ItemCode']     = ItemCode;
            NewRow['CodeBars']     = CodeBars;
            NewRow['ItemName']     = ItemName;
            NewRow['ItemWhse']     = ItemWhse;
            NewRow['ItemQuantity'] = ItemQuantity;
            NewRow['UnitMsr']      = UnitMsr;
            NewRow['Cost']         = Cost;
            NewRow['GrandPrice']   = GrandPrice;
            NewRow['Discount']     = Discount;
            NewRow['UnitPrice']    = UnitPrice;
            NewRow['LineTotal']    = LineTotal;
            NewRow['SPPrice_Icon'] = SPPrice_Icon;
            NewRow['SPPrice']      = SPPrice;
            ItemList.push(NewRow);
        } else {
            const NewRow = ItemList[EditRow];
            ItemList[EditRow]['ItemCode']     = ItemCode;
            ItemList[EditRow]['CodeBars']     = CodeBars;
            ItemList[EditRow]['ItemName']     = ItemName;
            ItemList[EditRow]['ItemWhse']     = ItemWhse;
            ItemList[EditRow]['ItemQuantity'] = ItemQuantity;
            ItemList[EditRow]['UnitMsr']      = UnitMsr;
            ItemList[EditRow]['Cost']         = Cost;
            ItemList[EditRow]['GrandPrice']   = GrandPrice;
            ItemList[EditRow]['Discount']     = Discount;
            ItemList[EditRow]['UnitPrice']    = UnitPrice;
            ItemList[EditRow]['LineTotal']    = LineTotal;
            ItemList[EditRow]['SPPrice_Icon'] = SPPrice_Icon;
            ItemList[EditRow]['SPPrice']      = SPPrice;
        }
    
        RenderItemList(ItemList);
        $("#ModalAddItem").modal("hide");

        // console.log(ItemList);
    }
}

function RenderItemList(ItemList) {
    let ItemRow      = ItemList.length;
    let RenderRow    = "";
    let All_Cost     = 0;
    let All_Total    = 0;
    let All_Profit   = 0;

    if(ItemRow < 1) {
        RenderRow = "<tr><td class='text-center' colspan='13'>ไม่มีข้อมูล :(</td></tr>";
    } else {
        let VisOrder = 1;
        for(i = 0; i < ItemRow; i++) {
            let trClass = (ItemList[i]['SPPrice'] == "Y") ? " class='table-warning'" : "" ;
            let CodeBars = (ItemList[i]['CodeBars'] != "null") ? ItemList[i]['CodeBars'] : "";
            RenderRow +=
                "<tr"+trClass+">"+
                    "<td class='text-end'>"+VisOrder+"</td>"+
                    "<td class='text-center'>"+ItemList[i]['ItemCode']+"</td>"+
                    "<td class='text-center'>"+CodeBars+"</td>"+
                    "<td>"+ItemList[i]['ItemName']+"</td>"+
                    "<td class='text-center'>"+ItemList[i]['ItemWhse']+"</td>"+
                    "<td class='text-end'>"+number_format(ItemList[i]['ItemQuantity'],0)+"</td>"+
                    "<td>"+ItemList[i]['UnitMsr']+"</td>"+
                    "<td class='text-end'>"+number_format(ItemList[i]['GrandPrice'],2)+"</td>"+
                    "<td class='text-center'>"+ItemList[i]['Discount']+"</td>"+
                    "<td class='text-end text-success'>"+number_format(ItemList[i]['UnitPrice'],2)+"</td>"+
                    "<td class='fw-bolder text-end'>"+number_format(ItemList[i]['LineTotal'],2)+"</td>"+
                    "<td class='text-center'>"+ItemList[i]['SPPrice_Icon']+"</td>"+
                    "<td class='text-center'>"+
                        "<div calss='dropdown'>"+
                            "<button class='btn btn-outline-secondary btn-sm dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false' data-bs-auto-close='inside'>"+
                                "<i class='fas fa-cog fa-fw fa-1x'></i>"+
                            "</button>"+
                            "<ul class='dropdown-menu' style='font-size: 13px;'>"+
                                "<li><a href='javascript:void(0);' class='dropdown-item' onclick='EditItem("+i+")'><i class='fas fa-edit fa-fw fa-1x'></i> แก้ไขรายการ</a></li>"+
                                "<li><a href='javascript:void(0);' class='dropdown-item' onclick='DeleteItem("+i+")'><i class='fas fa-trash fa-fw fa-1x'></i> ลบรายการ</a></li>"+
                            "</ul>"+
                        "</div>"+
                    "</td>"+
                "</tr>";
            VisOrder++;
            All_Cost  = parseFloat(All_Cost) + (parseFloat(ItemList[i]['Cost']) * parseFloat(ItemList[i]['ItemQuantity']));
            All_Total = parseFloat(All_Total) + parseFloat(ItemList[i]['LineTotal']);
        }
    }

    
    $("#Add_ItemList tbody").html(RenderRow);
    $("#All_Total").val(number_format(All_Total,2));
    $("#Doc_Cost").val(All_Cost);
    GetDocTotal();

}

function GetDocTotal() {
    let All_Total    = $("#All_Total").val();
    let All_Discount = parseFloat($("#All_Discount").val());
    let All_DiscUnit = $("#All_DiscUnit").val();
    let Doc_Cost     = $("#Doc_Cost").val();
    let Doc_Discount = 0;
    let Doc_VatSum   = 0;
    let Doc_Total    = 0;
    let Doc_Profit   = 0;
    let TaxType      = $("#txt_TaxType").val();

    All_Total = parseFloat(All_Total.replace(/,/g,""));

    if(All_Discount < 0) {
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณาใส่ส่วนลดมากกว่า 0");
        $("#alert_modal").modal('show');
        $("#All_Discount").val(number_format(0,2));
        Doc_Discount = 0;
        Doc_Discount = All_Total;
    } else if(All_Discount > 100 && All_DiscUnit == "%") {
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณาใส่ส่วนลดไม่เกิน 100%");
        $("#alert_modal").modal('show');
        $("#All_Discount").val(number_format(0,2));
        Doc_Discount = 0;
        Doc_Discount = All_Total;
    } else {
        if(All_Discount > 0) {
            if(All_DiscUnit == "%") {
                Doc_Discount = All_Total - ((All_Total * All_Discount) / 100);
            } else {
                Doc_Discount = All_Total - All_Discount;
            }
        } else {
            Doc_Discount = All_Total;
        }
    }

    All_Total    = All_Total.toFixed(2);
    if (TaxType == "S07"){
        Doc_Discount =  Doc_Discount/1.07;
        Doc_VatSum  = Doc_Discount * 0.07;
    }else{
        Doc_Discount = Doc_Discount;
        Doc_VatSum = 0;
    }
    Doc_Discount = Doc_Discount.toFixed(2);
    Doc_VatSum   = Doc_VatSum.toFixed(2);
    Doc_Total    = parseFloat(Doc_Discount) + parseFloat(Doc_VatSum);
    Doc_Total    = Doc_Total.toFixed(2);
    Doc_Profit   = (parseFloat(Doc_Total) - parseFloat(Doc_VatSum)) - parseFloat(Doc_Cost);

    $("#Doc_Discount").val(number_format(Doc_Discount,2));
    $("#Doc_VatSum").val(number_format(Doc_VatSum,2));
    $("#Doc_Total").val(number_format(Doc_Total,2));
    $("#Doc_Profit").val(number_format(Doc_Profit,2));

}

function EditItem(RowID) {
    let Header  = "<i class='fas fa-edit fa-fw fa-1x'></i> แก้ไข";
    $("#Add_Header").html(Header);
    let BtnSave = "<i class='fas fa-save fa-fw fa-1x'></i> บันทึก";
    $("#btn-AddRow").html(BtnSave);

    $("#ModalAddItem").modal("show");
    $("input[type='checkbox']").prop('checked',false).removeAttr("disabled");

    $("#Add_RowID").val(RowID);
    $("#Add_ItemCode").selectpicker("destroy").val(ItemList[RowID]['ItemCode']).change().selectpicker();
    $("#Add_Quantity").val(ItemList[RowID]['ItemQuantity']);

    let CardCode = $("#txt_CardCode").val();
    let ItemCode = $("#Add_ItemCode").val();
    let Quantity = $("#Add_Quantity").val();

    if(ItemCode.substr(0,2) == 'RV') {
        $("#Add_GrandPrice").attr("readonly", false);
        $("#Add_GrandPrice, #Add_Discount").attr("disabled",false);
        $("#Add_Discount").val("");
    }else{
        const DPCODE = b64_to_utf8(SS_DEPTCODE);
        // if(DPCODE == "DP002") {
        //     $("#Add_GrandPrice").attr("readonly", false); 
        //     $("#Add_GrandPrice, #Add_Discount").attr("disabled",false);
        // } else {
        //     $("#Add_GrandPrice").attr("readonly", true); 
        //     $("#Add_GrandPrice, #Add_Discount").attr("disabled",true);
        // }
        $("#Add_GrandPrice").attr("readonly", false);
        // $("#Add_Discount").val(25);
        $("#Add_Discount").val("");
    }

    GetItemDetail(CardCode, ItemCode, Quantity);

    $("#Add_GrandPrice").val(ItemList[RowID]['GrandPrice']);
    $("#Add_UnitPrice").val(ItemList[RowID]['UnitPrice']);
    $("#Add_WhsCode").val(ItemList[RowID]['ItemWhse']);
    $("#Add_Discount").val(ItemList[RowID]['Discount']);

    (ItemList[RowID]['SPPrice'] == "Y") ? $("#Chk_SPrice").prop("checked",true) : null ;

    $(document).off("focusout", "#Add_Quantity").on("focusout", "#Add_Quantity", function(e) {
        e.preventDefault();
        if($(this).val() > 0) {
            $("#btn-calprice").click();
        }
    });

    $(document).off("keypress", "#Add_Quantity").on("keypress", "#Add_Quantity", function(e) {
        let kbd = e.key;
        if(kbd === "Enter") {
            e.preventDefault();
            if($(this).val() > 0) {
                $("#btn-calprice").click();
            }
        }
    });

    $(document).off("click", "#btn-calprice").on("click", "#btn-calprice", function(e) {
        e.preventDefault();
        let CardCode = $("#txt_CardCode").val();
        let ItemCode = $("#Add_ItemCode").val();
        let Quantity = $("#Add_Quantity").val();

        if(CardCode == "" || ItemCode == "" || Quantity < 1) {
            $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
            $("#alert_body").html("กรุณาเลือกชื่อลูกค้า รหัสสินค้า และจำนวนสินค้าให้ครบถ้วน");
            $("#alert_modal").modal('show');
        } else {
            GetItemDetail(CardCode, ItemCode, Quantity);
            $("#Add_GrandPrice, #Add_Discount").attr("disabled",false);
        }
    });
}

function DeleteItem(RowID) {
    $("#confirm_modal").modal("show");
    $(document).off("click","#btn-confirm").on("click","#btn-confirm", function() {
        ItemList.splice(RowID,1);
        RenderItemList(ItemList);
        $("#confirm_modal").modal("hide");
    });
}

function OrderPreview() {
    let tHead = [
        'ชื่อลูกค้า', 'เลขที่ผู้เสียภาษี',
        'วันที่ใบสั่งขาย', 'วันที่กำหนดส่ง',
        'เงื่อนไขการชำระเงิน', '',
        'ที่อยู่เปิดบิล', 'ที่อยู่จัดส่ง',
        'พนักงานขาย', 'เอกสารอ้างอิง'
    ];

    let Arr_DocDate = $("#txt_DocDate").val().split("-");
    let txt_DocDate = Arr_DocDate[2]+"/"+Arr_DocDate[1]+"/"+Arr_DocDate[0];
    let Arr_DocDueDate = $("#txt_DocDueDate").val().split("-");
    let txt_DocDueDate = Arr_DocDueDate[2]+"/"+Arr_DocDueDate[1]+"/"+Arr_DocDueDate[0];

    let tBody = [
        $("#txt_CardCode option:selected").text(), $("#txt_LicTradeNum").val(),
        txt_DocDate, txt_DocDueDate,
        $("#txt_GroupNum option:selected").text(), '',
        $("#txt_Billto option:selected").text(), $("#txt_Shipto option:selected").text(),
        $("#txt_SlpCode option:selected").text(), $("#txt_U_PONo").val()
    ];

    let DataShow = "";
    for(let r = 0; r < tHead.length; r++) {
        DataShow += `
        <div class="row p-0 pb-2">`;
            DataShow += `
            <div class="col-lg d-flex" style='font-size: 14px;'>
                <div style='width: 20%;' class='fw-bold'>`+tHead[r]+`</div>
                <div style='width: 80%;'>`+tBody[r]+`</div>
            </div>`;
            r++;
            DataShow += `
            <div class="col-lg d-flex" style='font-size: 14px;'>
                <div style='width: 20%;' class='fw-bold'>`+tHead[r]+`</div>
                <div style='width: 80%;'>`+tBody[r]+`</div>
            </div>`;
        DataShow += `
        </div>`;
    }
    $("#DataShow").html(DataShow);

    let RenderRow = ""; let VisOrder = 0;
    $.each(ItemList, function (i) {
        let trClass = (ItemList[i]['SPPrice'] == "Y") ? " table-warning" : null ;
        VisOrder = i+1;
        RenderRow +=
                "<tr class='py-2"+trClass+"'>"+
                    "<td class='text-end'>"+VisOrder+"</td>"+
                    "<td class=''>"+ItemList[i]['ItemCode']+" "+ItemList[i]['CodeBars']+" "+ItemList[i]['ItemWhse']+" | "+ItemList[i]['ItemName']+"</td>"+
                    "<td class='text-end'>"+number_format(ItemList[i]['ItemQuantity'],0)+"</td>"+
                    "<td>"+ItemList[i]['UnitMsr']+"</td>"+
                    "<td class='text-end'>"+number_format(ItemList[i]['GrandPrice'],2)+"</td>"+
                    "<td class='text-center'>"+ItemList[i]['Discount']+"</td>"+
                    "<td class='fw-bolder text-end'>"+number_format(ItemList[i]['LineTotal'],2)+"</td>"+
                "</tr>";
    });

    $("#TableData tbody").html(RenderRow);
    $("#TableData tfoot").html(`
        <tr>
            <td colspan='4' rowspan='5'>
                <textarea class='form-control form-control-sm' rows='7' placeholder='ระบุหมายเหตุ' readonly>`+$("#txt_comments").val()+`</textarea>
            </td>
            <td colspan='2' class='fw-bolder text-end'>ยอดรวมทุกรายการ</td>
            <td class='fw-bolder text-end'>`+$("#All_Total").val()+`</td>
        </tr>
        <tr>
            <td colspan='2' class='text-success text-end'>ส่วนลดท้ายบิล</td>
            <td class='text-success text-end'>`+$("#All_Discount").val()+` `+$("#All_DiscUnit option:selected").text()+`</td>
        </tr>
        <tr>
            <td colspan='2' class='text-success text-end'>ยอดสินค้าหลังหักส่วนลด</td>
            <td class='fw-bolder text-success text-end'>`+$("#Doc_Discount").val()+`</td>
        </tr>
        <tr>
            <td colspan='2' class='text-end'>ภาษีมูลค่าเพิ่ม (VAT)</td>
            <td class='text-end'>`+$("#Doc_VatSum").val()+`</td>
        </tr>
        <tr>
            <th colspan='2' class='text-end fw-bolder text-primary '>จำนวนเงินรวมสุทธิ</th>
            <th class='text-end fw-bolder text-primary'>`+$("#Doc_Total").val()+`</th>
        </tr>
    `);
}

function SaveDoc(SaveType) {
    $("#overlay").show();
    $("#btn-Draft, #btn-NewDoc").attr("disabled", true);

    const PostData = {};
    function AddPostData(key, value) {
        if(!PostData[key]) {
            PostData[key] = value;
        }
    }

    /* SaveType = 0 = Save Draft // 1 = Add New Order */
    let txt_CardCode    = $("#txt_CardCode").val();
    let txt_CardName    = $("#txt_CardCode option:selected").text();
        txt_CardName    = txt_CardName.split(" | ");
    let txt_DocEntry    = $("#txt_DocEntry").val();
    let txt_LicTradeNum = $("#txt_LicTradeNum").val();
    let txt_DocType     = $("#txt_DocType").val();
    let txt_TaxType     = $("#txt_TaxType").val();
    let txt_Billto      = $("#txt_Billto").val();
    let txt_BillAddr    = $("#txt_Billto option:selected").text();
    let txt_Shipto      = $("#txt_Shipto").val();
    let txt_ShipAddr    = $("#txt_Shipto option:selected").text();
    let txt_SlpCode     = $("#txt_SlpCode").val();
    let txt_DocDate     = $("#txt_DocDate").val();
    let txt_DocDueDate  = $("#txt_DocDueDate").val();
    let txt_U_PONo      = $("#txt_U_PONo").val();
    // let txt_U_SO_TYPE   = $("#txt_U_SO_TYPE").val();
    let txt_GroupNum    = $("#txt_GroupNum").val();
    let txt_comments    = $("#txt_comments").val();

    let All_Total       = $("#All_Total").val();
    let All_Discount    = $("#All_Discount").val();
    let All_DiscUnit    = $("#All_DiscUnit").val();
    let Doc_Discount    = $("#Doc_Discount").val();
    let Doc_VatSum      = $("#Doc_VatSum").val();
    let Doc_Total       = $("#Doc_Total").val();
    let Doc_Profit      = $("#Doc_Profit").val();

    // SS_LVCODE = O0006, O0007 ยกเว้น Online
    const LVCODE = b64_to_utf8(SS_LVCODE);
    const SiteID = b64_to_utf8(SS_SITE);
    // if (SiteID == 0){
    //     AddTotal = 3000;
    // }else{
    //     AddTotal = 5000;
    // }
    //console.log(AddTotal);
    //console.log(SiteID);
    //console.log(SaveType);

    if((LVCODE == 'O0006' || LVCODE == 'O0007' || LVCODE == 'M0001' || LVCODE == 'O0005') || SaveType == '0'){
        // ไม่กำหนดราคาขั้นต่ำ
    }else{
        // if(parseFloat(Doc_Total.replace(/,/g,"")) < AddTotal) {
        //     $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พอข้อผิดพลาด!");
        //     $("#alert_body").html("ไม่สามารถบันทึกได้ เนื่องจากจำนวนเงินรวมสุทธิไม่ถึงเกณฑ์ราคา");
        //     $("#alert_modal").modal('show');
        //     $("#btn-Draft, #btn-NewDoc").attr("disabled", false);
        //     $("#overlay").hide();
        //     return;
        // }
    }

    AddPostData("txt_CardCode",    txt_CardCode);
    AddPostData("txt_CardName",    txt_CardName[1]);
    AddPostData("txt_DocEntry",    txt_DocEntry);
    AddPostData("txt_LicTradeNum", utf8_to_b64(txt_LicTradeNum));
    AddPostData("txt_DocType",     txt_DocType);
    AddPostData("txt_TaxType",     txt_TaxType);
    AddPostData("txt_Billto",      txt_Billto);
    AddPostData("txt_BillAddr",    txt_BillAddr);
    AddPostData("txt_Shipto",      txt_Shipto);
    AddPostData("txt_ShipAddr",    txt_ShipAddr);
    AddPostData("txt_SlpCode",     txt_SlpCode);
    AddPostData("txt_DocDate",     txt_DocDate);
    AddPostData("txt_DocDueDate",  txt_DocDueDate);
    AddPostData("txt_U_PONo",      txt_U_PONo);
    // AddPostData("txt_U_SO_TYPE",   txt_U_SO_TYPE);
    AddPostData("txt_GroupNum",    txt_GroupNum);
    AddPostData("txt_comments",    txt_comments);

    AddPostData("All_Total",    utf8_to_b64(All_Total.replace(/,/g,"")));
    AddPostData("All_Discount", utf8_to_b64(All_Discount.replace(/,/g,"")));
    AddPostData("All_DiscUnit", utf8_to_b64(All_DiscUnit.replace(/,/g,"")));
    AddPostData("Doc_Discount", utf8_to_b64(Doc_Discount.replace(/,/g,"")));
    AddPostData("Doc_VatSum",   utf8_to_b64(Doc_VatSum.replace(/,/g,"")));
    AddPostData("Doc_Total",    utf8_to_b64(Doc_Total.replace(/,/g,"")));
    AddPostData("Doc_Profit",   utf8_to_b64(Doc_Profit.replace(/,/g,"")));

    AddPostData("SaveType",SaveType);
    AddPostData("ItemRow", ItemList.length);

    const Form_Data = new FormData();
    for(let key in PostData) { Form_Data.append(key, PostData[key]); }

    $.each(ItemList, function(key, value) {
        let ItemRow = "";
        ItemRow =
        /* Pos 00 */ key+"::"+
        /* Pos 01 */ ItemList[key]['ItemCode']+"::"+
        /* Pos 02 */ ItemList[key]['CodeBars']+"::"+
        /* Pos 03 */ ItemList[key]['ItemName']+"::"+
        /* Pos 04 */ ItemList[key]['ItemWhse']+"::"+
        /* Pos 05 */ ItemList[key]['ItemQuantity']+"::"+
        /* Pos 06 */ ItemList[key]['UnitMsr']+"::"+
        /* Pos 07 */ utf8_to_b64(ItemList[key]['Cost'])+"::"+
        /* Pos 08 */ utf8_to_b64(ItemList[key]['GrandPrice'])+"::"+
        /* Pos 09 */ ItemList[key]['Discount']+"::"+
        /* Pos 10 */ utf8_to_b64(ItemList[key]['UnitPrice'])+"::"+
        /* Pos 11 */ utf8_to_b64(ItemList[key]['LineTotal'])+"::"+
        /* Pos 12 */ ItemList[key]['SPPrice'];
        Form_Data.append("ItemList["+key+"]", ItemRow);
    });

    $.each($("#txt_FileAttach"), function(i, obj) {
        $.each(obj.files, function(j, file) {
            Form_Data.append('DocAttach['+j+']',file);
        });
    });

    $("#overlay").show();

    $.ajax({
        url: "salesorders/ajax.php?p=SaveDoc",
        type: "POST",
        data: Form_Data,
        async: false,
        processData : false,
        contentType : false,
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
                } else {
                    $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                    $("#alert_body").html(inval['Message']);
                    $("#alert_modal").modal("show");
                }
            });
        }
    });
}

$(document).off("change","#txt_CardCode").on("change", "#txt_CardCode", function() {
    let CardCode = $(this).val();
    GetCardInfo(CardCode);
});

$("#All_Discount").on("focusout", function() { GetDocTotal(); });
$("#All_DiscUnit").on("change", function() { GetDocTotal(); });

$(document).ready(function() {
    GetAppData();
    AddItem();
    OrderList();
});

$("#filt_y, #filt_m").on("change", function() {
    OrderList();
})

function ViewDoc(DocEntry) {
    $.ajax({
        url: "salesorders/ajax.php?p=ViewDoc",
        type: "POST",
        data: { DocEntry: DocEntry, },
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
                            <td class='text-center'>`+Line_SP[0]+`</td>
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

                let DataApprove = "";
                if(inval['StatusTabApp'] == 'Y') {
                    $.each(inval['ItemApprove'], function(k, data) {
                        let App0 = (data['APP0'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        let App1 = (data['APP1'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        let App2 = (data['APP2'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        let App3 = (data['APP3'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        let App4 = (data['APP4'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        
                        let AppResult = "";
                        switch (data['AppResult']) {
                            case '0': AppResult = "<span class='text-muted'><i class='far fa-clock'></i> รอพิจารณา</span>"; break;
                            case 'Y': AppResult = "<span class='text-success'><i class='far fa-check-circle'></i> อนุมัติ</span>"; break;
                            case 'N': AppResult = "<span class='text-danger'><i class='far fa-times-circle'></i> ไม่อนุมัติ</span>"; break;
                        }

                        let DateApproved = "";
                        if(data['DateApproved'] != null) {
                            let d = new Date(data['DateApproved']);
                            DateApproved = d.getDate().toString().padStart(2,"0")+"/"+(d.getMonth()+1).toString().padStart(2,"0")+"/"+d.getFullYear()+" เวลา "+d.getHours().toString().padStart(2,"0")+":"+d.getMinutes().toString().padStart(2,"0")+" น.";
                        }
                        
                        DataApprove += `
                            <tr>
                                <td class='text-end'>`+(k+1)+`</td>
                                <td>`+data['LvName']+`</td>
                                <td class='text-center'>`+App0+`</td>
                                <td class='text-center'>`+App1+`</td>
                                <td class='text-center'>`+App2+`</td>
                                <td class='text-center'>`+App3+`</td>
                                <td class='text-center'>`+App4+`</td>
                                <td class='text-center'>`+AppResult+`</td>
                                <td>`+data['AppRemark']+`</td>
                                <td>`+data['ApproveName']+`</td>
                                <td class='text-center'>`+DateApproved+`</td>
                            </tr>`;
                    });
                    $("#approve-tab").prop('disabled', false);
                }else{
                    DataApprove += "<tr><td colspan='10' class='text-center'></td></tr>";
                    $("#approve-tab").prop('disabled', true);
                }
                $("#TableApprove tbody").html(DataApprove);

                DocAttach(DocEntry);
                sessionStorage.setItem('DocEntry',DocEntry);
                $("#itemlist-tab").click();
                $("#ModalViewDoc").modal("show");
            });
        }
    })
}

function DocAttach(DocEntry) {
    $.ajax({
        url: "salesorders/ajax.php?p=DocAttach",
        type: "POST",
        data: { DocEntry: DocEntry, },
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let tbody = "";
                if(inval['ItemAttach'].length != 0) {
                    $.each(inval['ItemAttach'], function(k, data) {
                        let d = new Date(data['DateCreate']);
                        let DateCreate = d.getDate().toString().padStart(2,"0")+"/"+(d.getMonth()+1).toString().padStart(2,"0")+"/"+d.getFullYear()+" เวลา "+d.getHours()+":"+d.getMinutes()+" น.";
                        tbody += 
                            `<tr>
                                <td class='text-end'>`+(k+1)+`</td>
                                <td>`+data['FileOriName']+`</td>
                                <td class='text-center'>`+DateCreate+`</td>
                                <td class='text-center'>
                                    <button class='btn btn-sm btn-success' onclick='ActiveBTN(\"Download\",\"`+data['FileDirName']+`.`+data['FileExt']+`||`+data['FileOriName']+`.`+data['FileExt']+`\");' style='--bs-btn-padding-y: 0.1rem !important; --bs-btn-padding-x: 0.5rem !important; --bs-btn-font-size: 0.875rem !important;'>
                                        <i class="fas fa-file-download"></i>
                                    </button>
                                </td>
                                <td class='text-center'>
                                    <button class='btn btn-sm btn-danger' onclick='ActiveBTN(\"Delete\",`+data['AttachID']+`);' style='--bs-btn-padding-y: 0.1rem !important; --bs-btn-padding-x: 0.5rem !important; --bs-btn-font-size: 0.875rem !important;'>
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                }else{
                    tbody = "<tr><td colspan='5' class='text-center'>ไม่มีข้อมูล :(</td></tr>";
                }
                $("#TableDocAttach tbody").html(tbody);
            });
        }
    })
}

function ActiveBTN(Type,D) {
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
        case 'Delete': 
            $("#confirm_modal .modal-body .defult").addClass("d-none");$("#confirm_modal .modal-body .custom").removeClass("d-none");
            $("#confirm_modal .modal-body .custom").html("คุณต้องการลบหรือไม่?");
            $("#confirm_modal").modal("show");
            $(document).off("click","#btn-confirm").on("click","#btn-confirm", function() {
                $.ajax({
                    url: "salesorders/ajax.php?p=ActiveBTN",
                    type: "POST",
                    data: { Type: Type, ID: D, },
                    success: function(result) {
                        let obj = jQuery.parseJSON(result);
                        $.each(obj, function(key, inval) {
                            if(inval['Status'] == 'OK') {
                                $("#fade_modal h5").html("<i class='fas fa-check-circle text-success'></i>");
                                $("#fade_modal p").html("ลบสำเร็จ");
                                $("#fade_modal").modal("show");
                                setTimeout(function() { $("#fade_modal").modal("hide"); }, 1200)
                                DocAttach(parseInt(sessionStorage.getItem('DocEntry')));
                            }else{
                                $("#fade_modal h5").html("<i class='fas fa-exclamation-circle text-danger'></i>");
                                $("#fade_modal p").html("ลบไม่สำเร็จ");
                                $("#fade_modal").modal("show");
                                setTimeout(function() { $("#fade_modal").modal("hide"); },2000)
                            }
                        });
                    } 
                })
                $("#confirm_modal").modal("hide");
                $("#confirm_modal .modal-body .defult").removeClass("d-none");$("#confirm_modal .modal-body .custom").addClass("d-none");
            });
        break;
    }
}

$("#AttachOrder").on("change", function() {
    let UploadsForm = new FormData($("#UploadsForm")[0]);
    UploadsForm.append('DocEntry',sessionStorage.getItem('DocEntry'));
    $.ajax({
        url: "salesorders/ajax.php?p=UploadsFile",
        type: 'POST',
        dataType: 'text',
        cache: false,
        processData: false,
        contentType: false,
        data: UploadsForm,
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj,function(key,inval) {
                if(inval['Status'] == 'OK') {
                    $("#fade_modal h5").html("<i class='fas fa-check-circle text-success'></i>");
                    $("#fade_modal p").html("บันทึกสำเร็จ");
                    $("#fade_modal").modal("show");
                    setTimeout(function() { $("#fade_modal").modal("hide"); }, 1200)
                    DocAttach(parseInt(sessionStorage.getItem('DocEntry')));
                }else{
                    $("#fade_modal h5").html("<i class='fas fa-exclamation-circle text-danger'></i>");
                    $("#fade_modal p").html("บันทึกไม่สำเร็จ");
                    $("#fade_modal").modal("show");
                    setTimeout(function() { $("#fade_modal").modal("hide"); },2000)
                }
                $("#AttachOrder").val("");
            });
        }
    });
})

function PrintDoc(DocEntry,IntStatus) {
    switch(IntStatus) {
        case 0: 
        case 1: 
        case 2: 
        case 4: 
            PrintOpen("Y","printqt","DEntry="+DocEntry); // ใบเสนอราคา
        break;
        case 3: 
        case 5: 
            PrintOpen("Y","printso","DEntry="+DocEntry); // ใบสั่งขาย
        break;
    }
}

function CancelDoc(DocEntry) {
    $("#confirm_modal").modal("show");
    $(document).off("click","#btn-confirm").on("click","#btn-confirm", function() {
        $("#confirm_modal").modal("hide");
        $.ajax({
            url: "salesorders/ajax.php?p=CancelDoc",
            type: "POST",
            data: { DocEntry: DocEntry },
            success: function(result) {
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
                    // if(inval['Status'] == "OK") {
                    //     $("#alert_header").html("<i class=\"far fa-check-circle fa-fw fa-lg text-success\"></i> เสร็จสิ้น !");
                    //     $("#alert_body").html("บันทึกสำเร็จ");
                    //     $("#alert_modal").modal("show");
                    //     $(document).off("click","button.btn-confirm").on("click","button.btn-confirm", function(e) {
                    //         e.preventDefault();
                    //         window.location.reload();
                    //     });
                    // } else {

                    // }
                });
            }
        })
    });
}

function EditDoc(DocEntry) {
    $.ajax({
        url: "salesorders/ajax.php?p=EditDoc",
        type: "POST",
        data: { DocEntry: DocEntry },
        async: false,
        success: function(result) {
            let AddTab = new bootstrap.Tab(document.querySelector("#Add-tab"));
            AddTab.show();
            $(".Step1").removeClass("d-none");
            $(".Step2, .Step3").addClass("d-none");
            var obj = jQuery.parseJSON(result);

            $.each(obj, function(key, inval) {
                if(inval['Status'] == "OK") {
                    ItemList.length = 0;

                    /* Header */
                    $("#txt_DocEntry").val(inval['HD']['DocEntry']);
                    $("#txt_CardCode").selectpicker("destroy").val(inval['HD']['CardCode']).change().selectpicker();
                    $("#txt_DocType").val(inval['HD']['DocType']).change();
                    $("#txt_TaxType").val(inval['HD']['TaxType']).change();
                    // console.log(inval['HD']['TaxType']);
                    $("#txt_Billto").val(inval['HD']['BilltoCode']).change();
                    $("#txt_Shipto").val(inval['HD']['ShiptoCode']).change();
                    $("#txt_SlpCode").selectpicker("destroy").val(inval['HD']['SlpCode']).change().selectpicker();
                    $("#txt_DocDate").val(inval['HD']['DocDate']);
                    $("#txt_DocDueDate").val(inval['HD']['DocDueDate']);
                    $("#txt_U_PONo").val(inval['HD']['U_PONo']);
                    // $("#txt_U_SO_TYPE").val(inval['HD']['U_SO_TYPE']);
                    $("#txt_GroupNum").val(inval['HD']['GroupNum']).change();
                    $("#txt_comments").html(inval['HD']['Comments']);

                    let DocDiscount = (parseFloat(inval['HD']['DiscPcnt']) > 0) ? parseFloat(inval['HD']['DiscPcnt']) : parseFloat(inval['HD']['DiscTotal']) ;
                    let DocDiscUnit = (parseFloat(inval['HD']['DiscPcnt']) > 0) ? "%" : "THB" ;

                    $("#All_Discount").val(DocDiscount);
                    $("#All_DiscUnit").val(DocDiscUnit).change();

                    /* Detail */
                    for(i = 0; i < inval['BD'].length; i++) {
                        ItemList.push(inval['BD'][i]);
                    }
                }
            })
        }
    });
    RenderItemList(ItemList);
}

function ImportDoc(DocEntry) {
    $("#overlay").show();
    let SiteID = "null";
    let DocType = "ORDR";
    $.ajax({
        url: "salesorders/ajax.php?p=ImportSAP",
        type: "POST",
        data: {
            SiteID: SiteID,
            DocType: DocType,
            DocEntry: DocEntry
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
    })
}

$("#btn-ImportItem").on("click", function(e){
    e.preventDefault();
    $("#ImportSearchInput").val("");
    $("#ModalImport").modal("show");
})

$("#btn-searchdoc").on("click", function(e) {
    e.preventDefault();
    const ImportSearchInput = $("#ImportSearchInput").val();
    if(ImportSearchInput != "") {
        $.ajax({
            url: "salesorders/ajax.php?p=EditDoc&ImportItem=Y",
            type: "POST",
            data: { ImportSearchInput: ImportSearchInput },
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $.each(obj, function(key, inval) {
                    if(inval['Status'] == "OK") {
                        ItemList.length = 0;
    
                        /* Header */
                        $("#txt_DocEntry").val(inval['HD']['DocEntry']);
                        $("#txt_CardCode").selectpicker("destroy").val(inval['HD']['CardCode']).change().selectpicker();
                        $("#txt_DocType").val(inval['HD']['DocType']).change();
                        $("#txt_TaxType").val(inval['HD']['TaxType']).change();
                        $("#txt_Billto").val(inval['HD']['BilltoCode']).change();
                        $("#txt_Shipto").val(inval['HD']['ShiptoCode']).change();
                        $("#txt_SlpCode").selectpicker("destroy").val(inval['HD']['SlpCode']).change().selectpicker();
                        $("#txt_DocDate").val(inval['HD']['DocDate']);
                        $("#txt_DocDueDate").val(inval['HD']['DocDueDate']);
                        $("#txt_U_PONo").val(inval['HD']['U_PONo']);
                        // $("#txt_U_SO_TYPE").val(inval['HD']['U_SO_TYPE']);
                        $("#txt_GroupNum").val(inval['HD']['GroupNum']).change();
                        $("#txt_comments").html(inval['HD']['Comments']);
    
                        let DocDiscount = (parseFloat(inval['HD']['DiscPcnt']) > 0) ? parseFloat(inval['HD']['DiscPcnt']) : parseFloat(inval['HD']['DiscTotal']) ;
                        let DocDiscUnit = (parseFloat(inval['HD']['DiscPcnt']) > 0) ? "%" : "THB" ;
    
                        $("#All_Discount").val(DocDiscount);
                        $("#All_DiscUnit").val(DocDiscUnit).change();
    
                        /* Detail */
                        for(i = 0; i < inval['BD'].length; i++) {
                            ItemList.push(inval['BD'][i]);
                        }
                        $("#ModalImport").modal("hide");
                        RenderItemList(ItemList);
                    }else{
                        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
                        $("#alert_body").html("ไม่มีเลขที่เอกสารนี้ในระบบ");
                        $("#alert_modal").modal("show");
                    }
                });
            }
        })
    }else{
        $("#alert_header").html("<i class=\"far fa-times-circle fa-fw fa-lg text-danger\"></i> พบข้อผิดพลาด!");
        $("#alert_body").html("กรุณากรอกเลขที่เอกสาร");
        $("#alert_modal").modal("show");
    }
})