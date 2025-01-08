<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$sql1 = "SELECT T0.CardCode, 
               (SELECT COUNT(A1.LineNum) FROM CRD1 A1 WHERE A1.CardCode = T0.CardCode)   AS CRD1
        FROM OCRD T0
        WHERE T0.CardCode NOT LIKE 'A%'
        ORDER BY T0.CardCode";
//echo $sql1;
$Q1 = SAPSelect($sql1);
$ax=0;
while ($row = odbc_fetch_array($Q1)) {
    $ax++;
    $CardCode[$ax] = $row['CardCode'];
    $CRD1[$CardCode[$ax]] = $row['CRD1'];
    $CRD2[$CardCode[$ax]] = 0;

}

$sql1 = "SELECT T0.CardCode, 
               (SELECT COUNT(A1.LineNum) FROM CRD1 A1 WHERE A1.CardCode = T0.CardCode)   AS CRD1
        FROM OCRD T0
        WHERE T0.CardCode NOT LIKE 'A%'
        ORDER BY T0.CardCode";
$Q1 = conSAP8($sql1);
while ($row2 = odbc_fetch_array($Q1)) {
    $CRD2[$row2['CardCode']] = $row2['CRD1'];
}
$o=0;
$ListData = "('";
for ($i=1;$i<=$ax;$i++){
    if ($CRD1[$CardCode[$i]] < $CRD2[$CardCode[$i]] AND $CRD2[$CardCode[$i]] <50){
        //$nots = " style='color:#CCCC99;' ";
        $o++;
        echo "<span >".$o.". ".$CardCode[$i]."</span><span style='color:#CCCC99;'> [".$CRD1[$CardCode[$i]]."]/[".$CRD2[$CardCode[$i]]."]</span><br>";
        if (($o%10) == 0){
            $ListData = substr($ListData,0,-1)."<br>'".$CardCode[$i];
        }else{
            $ListData .= $CardCode[$i]."','";
        }
        

    }/*else{
        $nots = " ";
    }*/
    
    
}

echo substr($ListData,0,-2).")";


?>