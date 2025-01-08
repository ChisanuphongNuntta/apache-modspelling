function GetSaleKPI() {
    const Year = $("#txtYear").val();
    const Month = $("#txtMonth").val();
    $("#overlay").show();
    $.ajax({
        url: "salekpi/ajax.php?p=GetSaleKPI",
        type: "POST",
        data: { Year: Year, Month: Month },
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                if(inval['Data'] != "") {
                    let Data = ""; 
                    let tmpTeam = "";
                    let SumSaleTarget = 0;
                    let SumDocTotal = 0; 
                    let SumAmount = 0;
                    let AllSaleTarget = 0;
                    let AllDocTotal = 0; 
                    let AllAmount = 0;
                    $.each(inval['Data'], function(k, item) {
                        if(tmpTeam != item['TeamCode']) {
                            if(tmpTeam != "") {
                                let SumPercent = (SumSaleTarget != 0) ? (SumDocTotal*100)/SumSaleTarget : 0;
                                Data +=
                                    `<tr class='fw-bolder bg-light bg-opacity-10'>
                                        <td>รวมทีม `+item['TeamCode']+`</td>
                                        <td class='text-end'>`+number_format(SumSaleTarget,2)+`</td>
                                        <td class='text-center'></td>
                                        <td class='text-center'></td>
                                        <td class='text-end'>`+number_format(SumDocTotal,2)+`</td>
                                        <td class='text-end'>`+number_format(SumAmount,2)+`</td>
                                        <td class='text-center'>`+number_format(SumPercent,2)+`%</td>
                                    </tr>`;
                                AllSaleTarget = AllSaleTarget+SumSaleTarget;
                                AllDocTotal = AllDocTotal+SumDocTotal;
                                AllAmount = AllAmount+SumAmount;
                                // Reste value Sum*
                                SumSaleTarget = 0;
                                SumDocTotal = 0;
                                SumAmount = 0;
                            }
                            tmpTeam = item['TeamCode'];
                            Data +=`<tr><td colspan='7' class='text-center table-primary fw-bold'>`+item['TeamCode']+`</td></tr>`;
                        }
                        let DocTotal = (item['DocTotal'] != null) ? parseFloat(item['DocTotal']) : 0;
                        let Percent = (item['SaleTarget'] != 0) ? (DocTotal*100)/parseFloat(item['SaleTarget']) : 0;
                        let SO = (item['SO'] != null) ? parseFloat(item['SO']) : 0;
                        Data += 
                            `<tr>
                                <td>`+item['SlpName']+`</td>
                                <td class='text-end'>`+number_format(parseFloat(item['SaleTarget']),2)+`</td>
                                <td class='text-center'>`+item['wApp']+`</td>
                                <td class='text-end'>`+number_format(SO,2)+`</td>
                                <td class='text-end'>`+number_format(DocTotal,2)+`</td>
                                <td class='text-end'>`+number_format(parseFloat(item['Amount']),2)+`</td>
                                <td class='text-center'>`+number_format(Percent,2)+`%</td>
                            </tr>`;
                        SumSaleTarget = SumSaleTarget+parseFloat(item['SaleTarget']);
                        SumDocTotal = SumDocTotal+DocTotal;
                        SumAmount = SumAmount+parseFloat(item['Amount']);
                    })
                    let SumPercent = (SumSaleTarget != 0) ? (SumDocTotal*100)/SumSaleTarget : 0;
                    Data +=
                        `<tr class='fw-bolder bg-light bg-opacity-10'>
                            <td>รวมทีม `+tmpTeam+`</td>
                            <td class='text-end'>`+number_format(SumSaleTarget,2)+`</td>
                            <td class='text-center'></td>
                            <td class='text-center'></td>
                            <td class='text-end'>`+number_format(SumDocTotal,2)+`</td>
                            <td class='text-end'>`+number_format(SumAmount,2)+`</td>
                            <td class='text-center'>`+number_format(SumPercent,2)+`%</td>
                        </tr>`;
                    AllSaleTarget = AllSaleTarget+SumSaleTarget;
                    AllDocTotal = AllDocTotal+SumDocTotal;
                    AllAmount = AllAmount+SumAmount;
                    $("#TableSaleKPI tbody").html(Data);

                    let AllPercent = (AllSaleTarget != 0) ? (AllDocTotal*100)/AllSaleTarget : 0;
                    let DataFooter =
                        `<tr class='fw-bold table-secondary'>
                            <td>รวมทุกทีม</td>
                            <td class='text-end'>`+number_format(AllSaleTarget,2)+`</td>
                            <td class='text-center'></td>
                            <td class='text-center'></td>
                            <td class='text-end'>`+number_format(AllDocTotal,2)+`</td>
                            <td class='text-end'>`+number_format(AllAmount,2)+`</td>
                            <td class='text-center'>`+number_format(AllPercent,2)+`%</td>
                        </tr>`;
                    $("#TableSaleKPI tfoot").html(DataFooter);
                }else{
                    $("#TableSaleKPI tbody").html("<td colspan='7' class='text-center'>ไม่มีข้อมูล</td>");
                    $("#TableSaleKPI tfoot").html("");
                }
                $("#overlay").hide();
            })
        }
    })
}

$(document).ready(function() {
    GetSaleKPI();
});