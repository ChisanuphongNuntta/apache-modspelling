<?php
include('../../core/config.core.php');
include('../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
$resultArray = array();
$arrCol = array();

if($_GET['a'] == 'ChkLocRack') {
    $LocRack = $_POST['LocRack'];
    $SQL = "SELECT WhsCode FROM allwhs WHERE LocationRack = '$LocRack'";
    $RST = MySQLSelect($SQL);
    $arrCol['Status'] = (isset($RST['WhsCode'])) ? "Y" : "N";
}

if($_GET['a'] == 'ChkItemCode') {
    $ItemCode = $_POST['ItemCode'];
    $Tbody = "";
    $SQL = "SELECT ItemCode, ItemName FROM oitm WHERE (ItemCode = '$ItemCode' OR BarCode = '$ItemCode' OR BarCode2 = '$ItemCode' OR BarCode3 = '$ItemCode')";
    if(CHKRowDB($SQL) == 1) {
        $arrCol['Status'] = "Y";
        $RST = MySQLSelect($SQL);
        $arrCol['ItemCode'] = $RST['ItemCode'];
    }else{
        $arrCol['Status'] = "N";
        $QRY = MySQLSelectX($SQL);
        while($RST = mysqli_fetch_array($QRY)) {
            $Tbody .= 
            "<tr>
                <td>
                    <div class='fw-bold text-black'>".$RST['ItemCode']."</div>
                    <span style='font-size: 11.5px;'>".$RST['ItemName']."</span>
                </td>
                <td width='30%'>
                    <div class='d-flex align-items-center text-primary'>
                        <a href='javascript:void(0);' onclick='ftItemCode(\"".$RST['ItemCode']."\")'><i class='far fa-plus-square'></i>&nbsp;เลือก</a>
                    </div>
                </td>
            </tr>";
        }
    }
    $arrCol['Tbody'] = $Tbody;
}

if($_GET['a'] == 'Save') {
    $ItemCode = $_POST['ItemCode'];
    $LocRack = $_POST['LocRack'];

    $InQty = ($_POST['OnHand'] >= 0) ? $_POST['OnHand'] : "NULL";
    $OutQty = ($_POST['OnHand'] < 0) ? $_POST['OnHand'] : "NULL";
    $WhsCode = MySQLSelect("SELECT WhsCode FROM allwhs WHERE LocationRack = '$LocRack'");

    $RST_RackOnHand = MySQLSelect("SELECT RackOnHand FROM chk_items WHERE ItemCode = '$ItemCode' AND WhsCode = '".$WhsCode['WhsCode']."' AND LocRack = '$LocRack' ORDER BY CreateDate DESC LIMIT 1");
    $RackOnHand = (isset($RST_RackOnHand['RackOnHand'])) ? ($_POST['OnHand']+$RST_RackOnHand['RackOnHand']) : $_POST['OnHand'];

    $RST_WhseOnHand = MySQLSelect("SELECT WhseOnHand FROM chk_items WHERE ItemCode = '$ItemCode' AND WhsCode = '".$WhsCode['WhsCode']."' ORDER BY CreateDate DESC LIMIT 1");
    $WhseOnHand = (isset($RST_WhseOnHand['WhseOnHand'])) ? ($_POST['OnHand']+$RST_WhseOnHand['WhseOnHand']) : $_POST['OnHand'];
    
    $SQL_SAP = "SELECT OnHand FROM oitw WHERE ItemCode = '$ItemCode' AND WhsCode = '".$WhsCode['WhsCode']."'";
    $QRY_SAP = SAPSelect($SQL_SAP);
    $RST_SAP = odbc_fetch_array($QRY_SAP);
    $SAPOnHand = $RST_SAP['OnHand'];

    $INSERT = 
        "INSERT INTO chk_items 
        SET ItemCode = '$ItemCode',
            WhsCode = '".$WhsCode['WhsCode']."',
            LocRack = '$LocRack',
            InQty = $InQty,
            OutQty = $OutQty,
            RackOnHand = $RackOnHand,
            WhseOnHand = $WhseOnHand,
            SAPOnHand = $SAPOnHand,
            CreateDate = NOW()";
    $GetLastID = MySQLInsert($INSERT);
    $GetLast = MySQLSelect("SELECT RackOnHand, SAPOnHand FROM chk_items WHERE ID = $GetLastID");
    $arrCol['OnHand'] = $GetLast['RackOnHand']." / ".$GetLast['SAPOnHand'];
}

if($_GET['a'] == 'GetScore') {
    $SQL1 = "SELECT COUNT(DISTINCT ItemCode) AS ItemCode FROM chk_items";
    $RST1 = MySQLSelect($SQL1);

    $SQL2 = "SELECT COUNT(DISTINCT ItemCode) AS ItemCode FROM OITW WHERE WhsCode = 'KB1' AND OnHand >0";
    $QRY2 = SAPSelect($SQL2);
    $RST2 = odbc_fetch_array($QRY2);

    $arrCol['Score'] = $RST1['ItemCode']." / ".$RST2['ItemCode'];
}

array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>