<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
//$connectX = mysqli_connect("192.168.1.9","kbi","passw0rd!","kbidb");
echo "START Copy <br>";
$PaidAll=$Paid22=0;

$sql1 = "SELECT T0.CardCode,T0.CardName,T0.CardType,T0.GroupCode,T0.FatherCard,U_ExternalCust,
                CASE WHEN T0.QryGroup1 = 'Y' THEN 1 WHEN T0.QryGroup2 = 'Y' THEN 2 WHEN T0.QryGroup3 = 'Y' THEN 3 WHEN T0.QryGroup4 = 'Y' THEN 4
                     WHEN T0.QryGroup5 = 'Y' THEN 5 WHEN T0.QryGroup6 = 'Y' THEN 6 WHEN T0.QryGroup7 = 'Y' THEN 7 WHEN T0.QryGroup8 = 'Y' THEN 8
                     WHEN T0.QryGroup9 = 'Y' THEN 9 WHEN T0.QryGroup10 = 'Y' THEN 10 WHEN T0.QryGroup11 = 'Y' THEN 11 WHEN T0.QryGroup12 = 'Y' THEN 12
                     WHEN T0.QryGroup13 = 'Y' THEN 13 WHEN T0.QryGroup14 = 'Y' THEN 14 WHEN T0.QryGroup15 = 'Y' THEN 15 WHEN T0.QryGroup16 = 'Y' THEN 16
                     WHEN T0.QryGroup17 = 'Y' THEN 17 WHEN T0.QryGroup18 = 'Y' THEN 18 WHEN T0.QryGroup19 = 'Y' THEN 19 WHEN T0.QryGroup20 = 'Y' THEN 20
                     WHEN T0.QryGroup21 = 'Y' THEN 21 WHEN T0.QryGroup22 = 'Y' THEN 22 WHEN T0.QryGroup23 = 'Y' THEN 23 WHEN T0.QryGroup24 = 'Y' THEN 24
                     WHEN T0.QryGroup25 = 'Y' THEN 25 WHEN T0.QryGroup26 = 'Y' THEN 26 WHEN T0.QryGroup27 = 'Y' THEN 27 WHEN T0.QryGroup28 = 'Y' THEN 28
                     WHEN T0.QryGroup29 = 'Y' THEN 29 WHEN T0.QryGroup30 = 'Y' THEN 30 WHEN T0.QryGroup31 = 'Y' THEN 31 WHEN T0.QryGroup32 = 'Y' THEN 32
                     WHEN T0.QryGroup33 = 'Y' THEN 33 WHEN T0.QryGroup34 = 'Y' THEN 34 WHEN T0.QryGroup35 = 'Y' THEN 35 WHEN T0.QryGroup36 = 'Y' THEN 36
                     WHEN T0.QryGroup37 = 'Y' THEN 37 WHEN T0.QryGroup38 = 'Y' THEN 38 WHEN T0.QryGroup39 = 'Y' THEN 39 WHEN T0.QryGroup40 = 'Y' THEN 40
                     WHEN T0.QryGroup41 = 'Y' THEN 41 WHEN T0.QryGroup42 = 'Y' THEN 42 WHEN T0.QryGroup43 = 'Y' THEN 43 WHEN T0.QryGroup44 = 'Y' THEN 44
                     WHEN T0.QryGroup45 = 'Y' THEN 45 WHEN T0.QryGroup46 = 'Y' THEN 46 WHEN T0.QryGroup47 = 'Y' THEN 47 WHEN T0.QryGroup48 = 'Y' THEN 48
                     WHEN T0.QryGroup49 = 'Y' THEN 49 WHEN T0.QryGroup50 = 'Y' THEN 50 WHEN T0.QryGroup51 = 'Y' THEN 51 WHEN T0.QryGroup52 = 'Y' THEN 52
                     WHEN T0.QryGroup53 = 'Y' THEN 53 WHEN T0.QryGroup54 = 'Y' THEN 54 WHEN T0.QryGroup55 = 'Y' THEN 55 WHEN T0.QryGroup56 = 'Y' THEN 56
                     WHEN T0.QryGroup57 = 'Y' THEN 57 WHEN T0.QryGroup58 = 'Y' THEN 58 WHEN T0.QryGroup59 = 'Y' THEN 59 WHEN T0.QryGroup60 = 'Y' THEN 60
                     WHEN T0.QryGroup61 = 'Y' THEN 61 WHEN T0.QryGroup62 = 'Y' THEN 62 WHEN T0.QryGroup63 = 'Y' THEN 63 WHEN T0.QryGroup64 = 'Y' THEN 64
                     ELSE 0 END AS MTGroup,
                (SELECT TOP 1 A0.DocDate FROM OINV A0 WHERE A0.CardCode = T0.CardCode AND A0.CANCELED = 'N' AND A0.DocStatus = 'C' ORDER BY DocDate ) AS FristDate,
                (SELECT COUNT(A1.DocEntry) FROM OINV A1 WHERE A1.CardCode = T0.CardCode AND A1.CANCELED = 'N' )AS BillCount,
                (SELECT SUM(B1.PaidToDate)  FROM OINV B1 WHERE B1.CardCode = T0.CardCode AND B1.CANCELED = 'N' AND B1.DocStatus = 'C') AS PaidAll,
                (SELECT SUM(B2.PaidToDate)  FROM ORIN B2 WHERE B2.CardCode = T0.CardCode AND B2.CANCELED = 'N' AND B2.DocStatus = 'C') AS RetuneAll,
                (SELECT SUM(C1.PaidToDate)  FROM OINV C1 WHERE C1.CardCode = T0.CardCode AND C1.CANCELED = 'N' AND C1.DocStatus = 'C' AND YEAR(C1.DocDate) = '2022') AS Paid22,
                (SELECT SUM(C2.PaidToDate)  FROM ORIN C2 WHERE C2.CardCode = T0.CardCode AND C2.CANCELED = 'N' AND C2.DocStatus = 'C' AND YEAR(C2.DocDate) = '2022') AS Retune22
         FROM OCRD T0
         WHERE T0.CardCode LIKE '".$_GET['wai']."%'";
         
$getCard= conSAP8($sql1);

$up=$NewX=0;
while ($row = odbc_fetch_array($getCard)) {
    $PaidAll = intval($row['PaidAll']) - intval($row['RetuneAll']);
    $Paid22 = intval($row['Paid22']) - intval($row['Retune22']);
    /*
    $sql1 = "SELECT * FROM custable  WHERE CardCode = '".$row['CardCode']."'";
    $query = mysqli_query($connectX,$sql1);
    $chk = mysqli_num_rows($query);
    if ($chk > 0){
        //$DataOld = MySQLSelect($sql1);
        $DataOld = mysqli_fetch_array($query);
        if ($DataOld['lat'] >0 || $DataOld['lon']){
            $AddDatas['lat'] = $DataOld['lat'];
            $AddDatas['lon'] = $DataOld['lon'];
            $AddDatas['CardName'] = $DataOld['CardName'];
        }else{
            $AddDatas['lat'] = '';
            $AddDatas['lon'] = '';
            $AddDatas['CardName'] = conutf8($row['CardName']);
        }
    }else{
        $AddDatas['lat'] = '';
        $AddDatas['lon'] = '';
    }
    */
    $sql2 = "SELECT * FROM ocrd WHERE CardCode = '".$row['CardCode']."'";
    if (CHKRowDB($sql2) > 0){
        echo $row['CardCode']." UPDATE <br>";
        $up++;
        $sql1 = "UPDATE ocrd SET MasterCode = '".$row['FatherCard']."',
                                 CardType = '".$row['CardType']."',
                                 GroupCode = '".$row['GroupCode']."',
                                 BranchCode='".$row['U_ExternalCust']."',
                                 MTGroup= ".$row['MTGroup'].",
                                 UkeyUpdate = 'c37d695c6f1144abdefa8890a921b8fb',
                                 CardStatus = 'A',
                                 FirstBillDate = '".$row['FristDate']."',
                                 PaidAll =".$PaidAll.",
                                 BillCount= '".$row['BillCount']."',
                                 Paid22=".$Paid22;
        $sql1 .=  " WHERE CardCode = '".$row['CardCode']."'";
        MySQLUpdate($sql1);
    }else{
        echo $row['CardCode']." NEW <br>";
        $NewX++;
        $sql1 = "INSERT INTO ocrd SET CardCode = '".$row['CardCode']."',
                                      CardName = '".$row['CardName']."',
                                      MasterCode = '".$row['FatherCard']."',
                                      CardType = '".$row['CardType']."',
                                      GroupCode = '".$row['GroupCode']."',
                                      BranchCode='".$row['U_ExternalCust']."',
                                      MTGroup= ".$row['MTGroup'].",
                                      UkeyUpdate = 'c37d695c6f1144abdefa8890a921b8fb',
                                      UkeyCreate = 'c37d695c6f1144abdefa8890a921b8fb',
                                      CardStatus = 'A',
                                      FirstBillDate = '".$row['FristDate']."',
                                      PaidAll =".$PaidAll.",
                                      BillCount= '".$row['BillCount']."',
                                      Paid22=".$Paid22;
        MySQLInsert($sql1);
    }

}
$sql3 = "SELECT CardCode FROM ocrd WHERE CardCode = '".$row."' AND DATE(DateCreate) = DATE(NOW()) OR DATE(DateUpdate) = DATE(NOW())";
echo "AllCard = ".CHKRowDB($sql3)."<br>";
echo "NEW : ".$NewX."<br> Update : ".$up;



?>