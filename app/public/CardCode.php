<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
$connectX = mysqli_connect("192.168.1.9","kbi","passw0rd!","kbidb");
$sql1 = "SELECT CardCode,lat,lon FROM custable WHERE lat > 0 AND lon > 0 AND CardCode != ''";
echo $sql1;
$getCard = mysqli_query($connectX,$sql1);
$a=0;
while ($row = mysqli_fetch_array($getCard)){
    $sql2="UPDATE ocrd SET Lat='".$row['lat']."',Lon='".$row['lon']."', UkeyUpdate='c37d695c6f1144abdefa8890a921b8fb' WHERE CardCode = '".$row['CardCode']."'";
    MySQLUpdate($sql2);
    $a++;
}
echo "Finish :".number_format($a)."Row";
    /*
$sql1 = "SELECT CardCode,CardName,CardType,GroupCode,U_ExternalCust, 
                CASE WHEN QryGroup1 = 'Y' THEN 1
                     WHEN QryGroup2 = 'Y' THEN 2
                     WHEN QryGroup3 = 'Y' THEN 3 
                     WHEN QryGroup4 = 'Y' THEN 4
                     WHEN QryGroup5 = 'Y' THEN 5
                     WHEN QryGroup6 = 'Y' THEN 6
                     WHEN QryGroup7 = 'Y' THEN 7
                     WHEN QryGroup8 = 'Y' THEN 8
                     WHEN QryGroup9 = 'Y' THEN 9
                     WHEN QryGroup10 = 'Y' THEN 10
                     WHEN QryGroup11 = 'Y' THEN 11
                     WHEN QryGroup12 = 'Y' THEN 12
                     WHEN QryGroup13 = 'Y' THEN 13 
                     WHEN QryGroup14 = 'Y' THEN 14
                     WHEN QryGroup15 = 'Y' THEN 15
                     WHEN QryGroup16 = 'Y' THEN 16
                     WHEN QryGroup17 = 'Y' THEN 17
                     WHEN QryGroup18 = 'Y' THEN 18
                     WHEN QryGroup19 = 'Y' THEN 19
                     WHEN QryGroup20 = 'Y' THEN 20
                     WHEN QryGroup21 = 'Y' THEN 21
                     WHEN QryGroup22 = 'Y' THEN 22
                     WHEN QryGroup23 = 'Y' THEN 23 
                     WHEN QryGroup24 = 'Y' THEN 24
                     WHEN QryGroup25 = 'Y' THEN 25
                     WHEN QryGroup26 = 'Y' THEN 26
                     WHEN QryGroup27 = 'Y' THEN 27
                     WHEN QryGroup28 = 'Y' THEN 28
                     WHEN QryGroup29 = 'Y' THEN 29
                     WHEN QryGroup30 = 'Y' THEN 30
                     WHEN QryGroup31 = 'Y' THEN 31
                     WHEN QryGroup32 = 'Y' THEN 32
                     WHEN QryGroup33 = 'Y' THEN 33 
                     WHEN QryGroup34 = 'Y' THEN 34
                     WHEN QryGroup35 = 'Y' THEN 35
                     WHEN QryGroup36 = 'Y' THEN 36
                     WHEN QryGroup37 = 'Y' THEN 37
                     WHEN QryGroup38 = 'Y' THEN 38
                     WHEN QryGroup39 = 'Y' THEN 39
                     ELSE 0 END AS MTGroup

         FROM OCRD 
         ORDER BY CardCode ";
$getSlpCode = SAPSelect($sql1);
$i=0;
while ($result = odbc_fetch_array($getSlpCode)) {
    $i++;

    $sql2 = "SELECT CardCode,
                    CASE WHEN target > 0 THEN target ELSE 0 END AS Trg,
                    CASE WHEN lat > 0 THEN lat ELSE 0 END AS lat,    
                    CASE WHEN lon > 0 THEN lon ELSE 0 END AS lon
             FROM custable WHERE CardCode = '".$result['CardCode']."'";
	$query = mysqli_query($connectX,$sql2);
    if (mysqli_num_rows($query) != 0){
        $MyData = mysqli_fetch_array($query);

        $sql3 = "INSERT INTO ocrd SET CardCode = '".$result['CardCode']."',
                                    CardName = '".conutf8($result['CardName'])."',
                                    CardType = '".$result['CardType']."',
                                    GroupCode = '".$result['GroupCode']."',
                                    BrachCode = '".$result['U_ExternalCust']."',
                                    MTGroup = '".$result['MTGroup']."',
                                    CardTrg = '".$MyData['Trg']."',
                                    Lat=".$MyData['lat'].",
                                    Lon=".$MyData['lon'].",
                                    UkeyCreate='c37d695c6f1144abdefa8890a921b8fb'";
    
    MySQLInsert($sql3);   
    
    }
}
LineUser('DP002','c37d695c6f1144abdefa8890a921b8fb','Finish CardCode : '.$i);
echo "Finish CardCode : ".$i."<br>";

$sql1 = "SELECT ItemCode,BarCode,BarCode2,BarCode3,ItemName,MgrUnit,WhsCode,IsBom,BomGroup,ItemMaster,
                CASE WHEN Status = 1 THEN 'A' ELSE 'I' END AS ItemStatus
         FROM itemdata ";
$getItem = mysqli_query($connectX,$sql1);
$i=0;
$b=0;
while ($row = mysqli_fetch_array($getItem)){
    $sql0 = "SELECT * FROM oitm WHERE ItemCode = '".$row['ItemCode']."'";
    if (CHKRowDB($sql0) == 0){
        $i++;
        $sql1 = "INSERT INTO oitm SET ItemCode = '".$row['ItemCode']."',
                                      BarCode = '".$row['BarCode']."',
                                      BarCode2 = '".$row['BarCode2']."',
                                      BarCode3 = '".$row['BarCode3']."',
                                      ItemName = '".$row['ItemName']."',
                                      MgrUnit = '".$row['MgrUnit']."',
                                      DftWhsCode = '".$row['WhsCode']."',
                                      IsBom = '".$row['IsBom']."',
                                      BomGroup = '".$row['BomGroup']."',
                                      ItemMaster = '".$row['ItemMaster']."',
                                      UkeyCreate = 'c37d695c6f1144abdefa8890a921b8fb',
                                      ItemStatus = '".$row['ItemStatus']."'";
           MySQLInsert($sql1);                      
        //echo $sql1."<br>";
    }else{
        $b++;
        echo "ERROR ItemCode : ".$row['ItemCode'];
    }
}
$show = 'Finish ItemCode : '.$i."\n"."Error ".$b."\n";
LineUser('DP002','c37d695c6f1144abdefa8890a921b8fb',$show);
echo "Finish ItemCode : ".$i."<br>";
/*
$sql1 = "SELECT BomGroup,ItemCode,Qty,
                CASE WHEN xActive = 1 THEN 'A' ELSE 'I' END AS ItemStatus
         FROM bomgroup ORDER BY BomGroup ";
$getBom = mysqli_query($connectX,$sql1);
$i=0;
while ($Bom = mysqli_fetch_array($getBom)){
    $i++;
    $sql1 = "INSERT INTO bomgroup SET BomGroup = ".$Bom['BomGroup'].",
                                      ItemCode = '".$Bom['ItemCode']."',
                                      Qty = ".$Bom['Qty'].",
                                      ItemStatus = '".$Bom['ItemStatus']."',
                                      UkeyCreate = 'c37d695c6f1144abdefa8890a921b8fb'";
    MySQLInsert($sql1);                      
    //echo $sql1."<br>";

}

LineUser('DP002','c37d695c6f1144abdefa8890a921b8fb','Finish BomGroup : '.$i);
echo "Finish BomGroup : ".$i."<br>";
*/
?>