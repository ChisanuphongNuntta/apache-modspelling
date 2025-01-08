<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
$connectX = mysqli_connect("192.168.1.12","kbi","passw0rd!","kbidb");
echo "START Copy <br>";

$sql1 = "SELECT ItemCode,ItemName,DfltWH,SalUnitMsr,U_ProductStatus
         FROM OITM WHERE ItemCode LIKE '".$_GET['wai']."-%'";
         
$getCard= conSAP8($sql1);
$ListBom = "('";
$ax=0;
while ($row = odbc_fetch_array($getCard)) {
    $ax=0;
    $sql1 = "SELECT * FROM itemdata  WHERE ItemCode = '".$row['ItemCode']."'";
    $query = mysqli_query($connectX,$sql1);
    $chk = mysqli_num_rows($query);
    if ($chk > 0){
        $DataOld = mysqli_fetch_array($query);
        $AddDatas['IsBom'] = 0;
        $AddDatas['BomGroup'] = $DataOld['BomGroup'];
        $AddDatas['ItemMaster'] = $DataOld['ItemMaster'];
        if ($DataOld['IsBom'] == 1){
            $ListBom .= $DataOld['ItemCode']."','";
        }
    }else{
        $AddDatas['IsBom'] =0;
        $AddDatas['BomGroup'] = 0;
        $AddDatas['ItemMaster'] = '';
    }

    $sql2 = "SELECT * FROM oitm WHERE ItemCode = '".$row['ItemCode']."'";
    if (CHKRowDB($sql2) > 0){
        $FunControl = 'Update';
        $sql1 = "UPDATE  oitm SET ItemName = '".conutf8($row['ItemName'])."',
                                  MgrUnit = '".conutf8($row['SalUnitMsr'])."',
                                  ProductStatus = '".$row['U_ProductStatus']."',
                                  DftWhsCode = '".$row['DfltWH']."',
                                  IsBom =".$AddDatas['IsBom'].",
                                  BomGroup=".$AddDatas['BomGroup'].",
                                  ItemMaster='".$AddDatas['ItemMaster']."',
                                  UkeyUpdate='c37d695c6f1144abdefa8890a921b8fb'";
        $sql1 .= " WHERE ITemCode = '".$row['ItemCode']."'";
        MySQLUpdate($sql1);
    }else{
        $FunControl = 'NEW';
        $sql1 = "INSERT INTO oitm SET ItemCode = '".$row['ItemCode']."',
                                      ItemName = '".conutf8($row['ItemName'])."',
                                      MgrUnit = '".conutf8($row['SalUnitMsr'])."',
                                      ProductStatus = '".$row['U_ProductStatus']."',
                                      DftWhsCode = '".$row['DfltWH']."',
                                      IsBom =".$AddDatas['IsBom'].",
                                      BomGroup=".$AddDatas['BomGroup'].",
                                      ItemMaster='".$AddDatas['ItemMaster']."',
                                      UkeyCreate='c37d695c6f1144abdefa8890a921b8fb',
                                      UkeyUpdate='c37d695c6f1144abdefa8890a921b8fb'";
        MySQLInsert($sql1);
        //echo $sql1."<br>";
    }
    echo $FunControl.' '.$row['ItemCode']."<br>";
}

if ($ax > 0){
    $ListBom .= substr($ListBom,0,-2).")";
    $sql3  = "SELECT * FROM itemdata WHERE ItemCode IN ".$ListBom;
    $getBom = MySQLSelectX($sql3);
    while ($DataBom = mysqli_fetch_array($getBom)){
        $sql2 = "SELECT * FROM oitm WHERE ItemCode = '".$DataBom['ItemCode']."'";
        if (CHKRowDB($sql2) == 0){
            $sql1 = "INSERT INTO oitm SET ItemCode = '".$DataBom['ItemCode']."',
                                        ItemName = '".$DataBom['ItemName']."',
                                        MgrUnit = '".$DataBom['MgrUnit']."',
                                        ProductStatus = 'B',
                                        DftWhsCode = '".$DataBom['WhsCode']."',
                                        IsBom =".$DataBom['IsBom'].",
                                        BomGroup=".$DataBom['BomGroup'].",
                                        ItemMaster='".$DataBom['ItemMaster']."',
                                        UkeyCreate='c37d695c6f1144abdefa8890a921b8fb',
                                        UkeyUpdate='c37d695c6f1144abdefa8890a921b8fb'";
            MySQLInsert($sql1);
        }
        echo $DataBom['ItemCode']."<br>";
    }
}else{
    echo "Bom = 0";
}


?>