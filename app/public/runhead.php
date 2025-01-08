<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');

$sql1 = "SELECT DISTINCT T1.DocEntry,T1.DocNum,T1.CardCode,T1.CardName 
FROM RDR1 T0
LEFT JOIN ORDR T1 ON T0.DocEntry = T1.DocEntry
WHERE T0.WhsCode IN ('KSY','KB4','MT','MT2','TT-C','OUL','OSP','NST','PM','PM-KSY','PMTT-KSY') AND T1.DocStatus = 'O'";
$SOQRY = SAPSelect($sql1);
$ax=0;
while($SORST = odbc_fetch_array($SOQRY)) {
    
    $sql2 = "SELECT ID FROM picker_soheader WHERE SODocEntry = '".$SORST['DocEntry']."'";
    if (ChkRowDB($sql2) == 0){
        $ax++;
        echo $SORST['DocNum']." ".$SORST['CardCode']." - ".conuts8($SORST['CardName'])."<br>";
    }

}
if ($ax==0){
    echo "No Recode";
}


?>