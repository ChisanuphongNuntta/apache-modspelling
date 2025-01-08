<?php
require("../core/Main.core.php");
require("../../".MainPathKSY()."/core/config.core.php");
require("../../".MainPathKSY()."/core/connect.core.php");
require("../../".MainPathKSY()."/core/functions.core.php");
require("../../".MainPathKSY()."/core/coresap.php");
date_default_timezone_set('Asia/Bangkok');
session_start();
$getdata = new clear_db();
$connect = $getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
$getdata->my_sql_set_utf8();
$resultArray = array();
$arrCol = array();
$output = "";
$sqlOnKsy = "SELECT P1.ItemCode,P2.ItemName,P1.OnHand,P2.InvntryUom
             FROM (SELECT T0.ItemCode,T0.WhsCode,SUM(T0.OnHand) AS OnHand 
                   FROM OITW T0 
                   WHERE T0.WhsCode = '".$_POST['WhsCode']."' 
                   GROUP BY T0.ItemCode,T0.WhsCode) P1 
                  JOIN OITM P2 ON P1.ItemCode = P2.ItemCode
             WHERE P1.ItemCode = '".$_POST['docEntry']."'
             ORDER BY P1.ItemCode"; 
$sqlRack = "SELECT T0.ItemCode,T0.LocRack,T0.OnHand 
            FROM oitw T0
            WHERE T0.ItemCode = '".$_POST['docEntry']."' AND T0.WhsCode = '".$_POST['WhsCode']."'
            ORDER BY LocRack  ";
$getRack = $getdata->MySQL_SelectX($sqlRack);

$sapfqry = odbc_exec($sapconn,$sqlOnKsy);
$ax=0;
$Total = 0;
while ($DataShow = odbc_fetch_array($sapfqry)){
    $ax++;
    $ItemCode = $DataShow['ItemCode'];
    $ItemName = conutf8($DataShow['ItemName']);
    $OnHand = $DataShow['OnHand'];
    $UnitMsr = conutf8($DataShow['InvntryUom']);
}
$output .= "<table width='100%' class='table table-bordered'>";
$output .= "     <tr>";           
$output .= "        <td style='font-weight: bold;' width='15%'>รหัสสินค้า</td>";
$output .= "        <td>".$ItemCode."</td>";
$output .= "     </tr>";
$output .= "        <td style='font-weight: bold;'>ชื่อสินค้า</td>";
$output .= "        <td>".$ItemName."</td>";
$output .= "     </tr>";
$output .= "     <tr>"; 
$output .= "        <td style='font-weight: bold;'>จำนวนทั้งหมด</td>";
$output .= "        <td>".number_format($OnHand)." ".$UnitMsr."</td>";
$output .= "     </tr>";
$output .= "</table><br>";
$output .= "<table width='100%' class='table table-bordered'>";
$output .= "     <tr>";    
$output .= "        <td align='center' style='font-weight: bold;'>ชั้นวาง</td>";              
$output .= "        <td width='10%' align='center' style='font-weight: bold;'>QTY</td>";            
$output .= "        <td width='10%' align='center' style='font-weight: bold;'>หน่วย</td>";           
$output .= "     </tr>";               
$Total = $OnHand;
while ($DataRack = mysql_fetch_object($getRack)){
    $output .= "     <tr>";    
    $output .= "        <td>".$DataRack->LocRack."</td>";
    $output .= "        <td align='right'>".number_format($DataRack->OnHand)."</td>";
    $output .= "        <td align='center'>".$UnitMsr."</td>";
    $output .= "     </tr>";    
    $Total =  $Total - $DataRack->OnHand;
}
$output .= "     <tr>";    
$output .= "     <td style='font-weight: bold;'>คงเหลือนับ</td>";    
$output .= "     <td align='right' style='font-weight: bold;'>".number_format($Total)."</td>";    
$output .= "     <td align='center' style='font-weight: bold;'>".$UnitMsr."</td>";    
$output .= "     </tr>"; 
$output .= "</table>";

echo $output;





