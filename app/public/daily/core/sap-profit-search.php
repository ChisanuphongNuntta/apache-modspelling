<?php session_start(); require('coresap.php');require('functions.core.php');
date_default_timezone_set('Asia/Bangkok');
header("Content-type:text/html; charset=UTF-8"); 
header("Cache-Control: no-store, no-cache, must-revalidate");       
header("Cache-Control: post-check=0, pre-check=0", false);
$prftsql = "
SELECT T0.[DocEntry],T2.[BeginStr],T0.[DocNum],T0.[DocDate],T1.[SlpName],T0.[CardCode],T0.[CardName],T0.[DocTotal],T0.[WddStatus] 
FROM ODRF T0 
JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode]
JOIN NNM1 T2 ON T0.[Series] = T2.[Series] 
WHERE (T0.[DocNum] LIKE '".$_POST['fm-docbox']."' AND T0.[CardCode] LIKE '".$_POST['fm-cusbox']."')";
$prftqry = odbc_exec($sapconn,$prftsql);
echo "<br><br>";
echo "<table class=\"table table-bordered table-hover table-responsive-xl\">";
echo "<thead>";
echo "<tr>";
echo "<th>รหัสเอกสาร</th>";
echo "<th>วันที่</th>";
echo "<th>ผู้แทนขาย</th>";
echo "<th>รหัสลูกค้า</th>";
echo "<th>ชื่อลูกค้า</th>";
echo "<th>จำนวนเงิน (บาท)</th>";
echo "<th>สถานะการอนุมัติ</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
while($prftrst = odbc_fetch_array($prftqry)) {
    echo "<tr>";
    echo "<td class=\"text-center\"><a href=\"../dashboard/report/sales/so-gp.php?dcn=".$prftrst['DocNum']."&ety=".$prftrst['DocEntry']."\" target=\"_blank\"  >".$prftrst['BeginStr'].$prftrst['DocNum']."</a></td>";
    echo "<td class=\"text-center\">".date_format(date_create($prftrst['DocDate']),"d-m-Y")."</td>";
    echo "<td>".conutf8($prftrst['SlpName'])."</td>";
    echo "<td class=\"text-center\">".$prftrst['CardCode']."</td>";
    echo "<td>" .conutf8($prftrst['CardName'])."</td>";
    echo "<td class=\"text-right\">".number_format($prftrst['DocTotal'],2)."</td>";
    echo "<td class=\"text-center\">".$prftrst['WddStatus']."</td>";
}
echo "</tbody>";
echo "</table>";
?>