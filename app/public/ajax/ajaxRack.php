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
//$WhsCode = ;
switch ($_POST['WhsCode']){
    case 'KSY' :
        $WhsCode = " LocationRack NOT LIKE 'M1-%' ";
        break;
    case 'KSM' :
        $WhsCode = " LocationRack LIKE 'M1-%' ";
        break;
}
$sqlRack = "SELECT T0.WhsCode,T0.LocationRack,AllFree,
            (SELECT CASE WHEN SUM(P1.OnHand) > 0 THEN 1 ELSE 0 END FROM oitw P1 WHERE P1.WhsCode = T0.WhsCode AND P1.LocRack = T0.LocationRack )AS Instock    

            FROM allwhs T0 
            WHERE ".$WhsCode." ORDER BY T0.LocationRack";
        //echo $sqlRack;
$getRack = $getdata->MySQL_SelectX($sqlRack);
$countCol = 0;
$tmpRackN = "";
$tmpRow = "";
while ($TableRack = mysql_fetch_object($getRack)){
    if ($TableRack->Instock == 1){
        $rowBg = " instock ";
    }else{
        if ($TableRack->AllFree == 'Y'){
            $rowBg = " aFree ";

        }else{
            $rowBg = "";
        }
        
    }
    if ($_POST['WhsCode'] != $TableRack->WhsCode){
        $ShowWhs = "<br>[".$TableRack->WhsCode."] ";

    }else{
        $ShowWhs = "";
    }
    if (substr($TableRack->LocationRack,0,2)  != $tmpLocationRack OR substr($TableRack->LocationRack,3,2)  != $tmpRow OR $countCol == 10){
        $tmpLocationRack = substr($TableRack->LocationRack,0,2);
        $tmpRow = substr($TableRack->LocationRack,3,2);
        if ((substr($TableRack->LocationRack,0,2)  != $tmpLocationRack OR substr($TableRack->LocationRack,3,2) OR $countCol == 10) AND $tmpRackN != ""){
            
        }
        $countCol = 0;
        $NewRow = 'Y';
        $output .= "<tr>";
        $output .= "<td class='text-center ".$rowBg."'>".$TableRack->LocationRack.$ShowWhs."</td>";$countCol++;

    }else{
        $NewRow = 'N';
        $output .= "<td class='text-center ".$rowBg."'>".$TableRack->LocationRack.$ShowWhs."</td>";$countCol++;
    }

    if ($countCol == 10 OR ($NewRow == 'Y' AND $countCol !=1)){
        if ($countCol < 10){
            $spCol = 10 - $countCol;
            $output .= "<td colspan = '".$spCol."' ></td>";
            $output .= "</tr><tr><td colspan='10'>&nbsp;</td></tr>";
        }else{
            $output .= "</tr>";
        }
        
    }


}

$arrCol['output'] = $output;

array_push($resultArray,$arrCol);
echo json_encode($resultArray);


