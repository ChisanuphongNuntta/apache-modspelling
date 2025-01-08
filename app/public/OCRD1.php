<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$sql1 = "SELECT CardCode,DebtLine FROM OCRD WHERE  DebtLine > 0 AND CardCode LIKE 'M-%'";
$get8 = conSAP8($sql1);
$a=0;
$b=0;
while ($SAP8 = odbc_fetch_array($get8)){
    $sql1 = "SELECT CardCode,DebtLine FROM OCRD WHERE CardCode = '".$SAP8['CardCode']."'"; 
    $getCR= SAPSelect($sql1);
    $CRLine10 = odbc_fetch_array($getCR);
    if ($CRLine10['DebtLine'] != $SAP8['DebtLine']){
        $sql2 = "UPDATE OCRD SET DebtLine= ".$SAP8['DebtLine']." WHERE CardCode = '".$SAP8['CardCode']."'";
        //$get10 = SAPSelect($sql2);
        $a++;
        echo $sql2."<br>";
    }
    $b++;

}
echo $a."/".$b;
?>