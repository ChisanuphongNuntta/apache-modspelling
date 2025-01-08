function CallData() {
    $.ajax({
        url: "salesapprove/ajax.php?p=CallData",
        type: "GET",
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                let DataOrder = "";
                if(inval['Status'] == 'SUCCESS') {
                    $.each(inval['ListOrder'], function(k, data) {
                        let DocDate = "";
                        if(data['DocDate'] != null) {
                            let ArrDocDate = new Date(data['DocDate']);
                            DocDate = ArrDocDate.getDate()+"/"+(ArrDocDate.getMonth()+1)+"/"+ArrDocDate.getFullYear();
                        }

                        let DocDueDate = "";
                        if(data['DocDueDate'] != null) {
                            let ArrDocDueDate = new Date(data['DocDueDate']);
                            DocDueDate = ArrDocDueDate.getDate().toString().padStart(2,"0")+"/"+(ArrDocDueDate.getMonth()+1).toString().padStart(2,"0")+"/"+ArrDocDueDate.getFullYear();
                        }

                        let App0 = (data['APP0'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        let App1 = (data['APP1'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        let App2 = (data['APP2'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        let App3 = (data['APP3'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";
                        let App4 = (data['APP4'] == 'Y') ? "<i class='fas fa-check'></i>":"<i class='fas fa-minus'></i>";

                        DataOrder += `
                            <tr>
                                <td class='text-center'>`+DocDate+`</td>
                                <td class='text-center'>`+DocDueDate+`</td>
                                <td class='text-center'><a href='javascript:void(0);' onclick='Fn_ViewAppDoc(`+data['DocEntry']+`);'>`+data['DocNum']+`</a></td>
                                <td>`+data['CardName']+`</td>
                                <td class='text-end'>`+number_format(data['DocTotal'],2)+`</td>
                                <td>`+data['SlpName']+`</td>
                                <td class='text-center'>`+App0+`</td>
                                <td class='text-center'>`+App1+`</td>
                                <td class='text-center'>`+App2+`</td>
                                <td class='text-center'>`+App3+`</td>
                                <td class='text-center'>`+App4+`</td>
                            </tr>`;
                    });
                }else{
                    DataOrder = `<tr><td colspan='11' class='text-center'>ไม่มีข้อมูล :(</td></tr>`;
                }
                $("#TableListOrder tbody").html(DataOrder);
            });
        }
    })
}





$(document).ready(function() {
    CallData();
});