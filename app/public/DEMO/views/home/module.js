import { chart_saletarget } from "./module/charts.js";

function ChartSaletarget() {
    $.ajax({
        url: "home/ajax.php?p=ChartSaletarget",
        type: "GET",
        success: function(result) {
            let obj = jQuery.parseJSON(result);
            $.each(obj,function(key,inval) {
                let chart_report_sale = new ApexCharts(document.querySelector("#SaleTargetProgress"), chart_saletarget); chart_report_sale.render();
                chart_report_sale.updateSeries([inval['SalePer']]);
                $("#SaleSOMonth").html(inval['SaleSOMonth']);
                $("#SaleMonth").html(inval['SaleMonth']);
                $("#SaleTarget").html(inval['SaleTarget']);
            });
        }
    })
}

$(document).ready(function() {
    ChartSaletarget();
});