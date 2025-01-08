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
$WHScode = $_POST['WhsCode'];
$output = "";
$sqlOnKsy = "SELECT P1.ItemCode,P2.ItemName,P1.OnHand 
             FROM (SELECT T0.ItemCode,T0.WhsCode,SUM(T0.OnHand) AS OnHand 
                   FROM OITW T0 
                   WHERE T0.WhsCode = '".$WHScode."' 
                   GROUP BY T0.ItemCode,T0.WhsCode) P1 
                  JOIN OITM P2 ON P1.ItemCode = P2.ItemCode
             WHERE P1.ItemCode NOT LIKE '00-%'
             ORDER BY P1.ItemCode"; 


$sqlRack = "SELECT T0.ItemCode,T0.WhsCode,SUM(T0.OnHand) AS OnRack 
            FROM OITW T0 
            WHERE T0.WhsCode = '".$WHScode."' 
            GROUP BY T0.ItemCode,T0.WhsCode";
$getRack = $getdata->MySQL_SelectX($sqlRack);

$sapfqry = odbc_exec($sapconn,$sqlOnKsy);
$ax=0;
$DataOnSAP = "('";
while ($DataShow = odbc_fetch_array($sapfqry)){
    $ax++;
    $ItemCode[$ax] = $DataShow['ItemCode'];
    $ItemName[$ItemCode[$ax]] = conutf8($DataShow['ItemName']);
    $OnHand[$ItemCode[$ax]] = $DataShow['OnHand'];
    $newOnHand[$ItemCode[$ax]] = $OnHand[$ItemCode[$ax]];
    $dataGreen[$ItemCode[$ax]] = 0;
    $DataOnSAP .= $DataShow['ItemCode']."','";
}

while ($DataRack = mysql_fetch_object($getRack)){
    $OnRack[$DataRack->ItemCode] = $DataRack->OnRack;
    $newOnHand[$DataRack->ItemCode] = $OnHand[$DataRack->ItemCode] - $DataRack->OnRack;
    $dataGreen[$DataRack->ItemCode] = 1;
    //echo $DataRack->ItemCode;
}
$i = 0;
$skip1 = 0;
$Col1 = 'N';
while ($i<=$ax){
    $i++;
    if ($newOnHand[$ItemCode[$i]] < 0){
        $subColor = " txtred ";
    }else{
        $subColor = " ";
    }

    if ($dataGreen[$ItemCode[$i]] == 1){
        $subGreen = " txtGreen ";
        $modalX = "<span onclick='CallModal(\"".$ItemCode[$i]."\")'>".$ItemCode[$i]."</span>";

    }else{
        $subGreen = " " ;
        $modalX = $ItemCode[$i];
    }

    if ($newOnHand[$ItemCode[$i]] != 0){
        if ($Col1 == 'N'){
            $output .= "<tr style='color:#000'>";
            $output .= "   <td class='text-center'><input type='checkbox'></td>";
            $output .= "   <td class='text-center ".$subGreen." '>".$modalX."</td>";
            $output .= "   <td class='text-left ".$subGreen." '>".$ItemName[$ItemCode[$i]]."</td>";
            $output .= "   <td class='text-right ".$subGreen." ".$subColor."'>".number_format($newOnHand[$ItemCode[$i]])."</td>";
            $output .= "   <td style='background-color:#767373;border-top:none;border-bottom:none'></td>";
            $Col1 = 'Y';
        }else{
            $output .= "   <td class='text-center'><input type='checkbox'></td>";
            $output .= "   <td class='text-center ".$subGreen." '>".$modalX."</td>";
            $output .= "   <td class='text-left ".$subGreen." '>".$ItemName[$ItemCode[$i]]."</td>";
            $output .= "   <td class='text-right ".$subGreen." ".$subColor."'>".number_format($newOnHand[$ItemCode[$i]])."</td>";
            $output .= "</tr>";
            $Col1 = 'N';
        }
    }
    $i++;
    if ($newOnHand[$ItemCode[$i]] < 0){
        $subColor = " txtred ";
    }else{
        $subColor = " ";
    }
    if ($dataGreen[$ItemCode[$i]] == 1){
        $subGreen = " txtGreen ";
        $modalX = "<span onclick='CallModal(\"".$ItemCode[$i]."\")'>".$ItemCode[$i]."</span>";
    }else{
        $subGreen = " " ;
        $modalX = $ItemCode[$i];
    }
    if ($newOnHand[$ItemCode[$i]] != 0){
        if ($Col1 == 'N'){
            $output .= "<tr style='color:#000'>";
            $output .= "   <td class='text-center'><input type='checkbox'></td>";
            $output .= "   <td class='text-center ".$subGreen."'>".$modalX."</td>";
            $output .= "   <td class='text-left ".$subGreen." '>".$ItemName[$ItemCode[$i]]."</td>";
            $output .= "   <td class='text-right ".$subGreen." ".$subColor."'>".number_format($newOnHand[$ItemCode[$i]])."</td>";
            $output .= "   <td style='background-color:#767373;border-top:none;border-bottom:none'></td>";
            $Col1 = 'Y';
        }else{
            $output .= "   <td class='text-center'><input type='checkbox'></td>";
            $output .= "   <td class='text-center ".$subGreen." '>".$modalX."</td>";
            $output .= "   <td class='text-left ".$subGreen."'>".$ItemName[$ItemCode[$i]]."</td>";
            $output .= "   <td class='text-right ".$subGreen." ".$subColor."'>".number_format($newOnHand[$ItemCode[$i]])."</td>";
            $output .= "</tr>";
            $Col1 = 'N';
        }
    }

}
####----------------------------------####
$DataOnSAP = substr($DataOnSAP,0,($DataOnSAP-2)).")";
$output .= "<tr><td colspan = '9'>";
$output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table table-bordered table-hover\" style=\"color:#FFFFFF;\">";
$output .= "<tr style=\"color:#FFFFFF; background:#F947A3;\">";
$output .= "    <td></td>";
$output .= "    <td>รหัสสินค้า</td>";
$output .= "    <td>ชื่อ</td>";
$output .= "    <td>คงเหลือ</td>";
$output .= "</tr>";

$SQL2 = "SELECT T0.ItemCode,T0.WhsCode,SUM(T0.OnHand) AS OnRack 
         FROM OITW T0 
         WHERE T0.WhsCode = '".$WHScode."' AND  T0.ItemCode NOT IN ".$DataOnSAP." 
         GROUP BY T0.ItemCode,T0.WhsCode";
         //echo $SQL2."\n";
$getNoSAP = $getdata->MySQL_SelectX($SQL2);
while ($DataRack = mysql_fetch_object($getNoSAP)){
    $ItmCodeNoSAP = $DataRack->ItemCode;
    $WhsCodeNoSAP = $DataRack->WhsCode;
    $OnRackNoSAP = $DataRack->OnRack;

    $output .= "<tr style=\"color:#000;\">";
    $output .= "    <td></td>";
    $output .= "    <td>".$ItmCodeNoSAP."</td>";
    $output .= "    <td>".$WhsCodeNoSAP."</td>";
    $output .= "    <td>".$OnRackNoSAP."</td>";
    $output .= "    </tr>";

}
$output .= "</table></td></tr></table>";
###

$arrCol['output'] = $output;

array_push($resultArray,$arrCol);
echo json_encode($resultArray);