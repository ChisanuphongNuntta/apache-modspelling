<?php
include('../../../core/config.core.php');
include('../../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();
$output = "";
if($_SESSION['UserName']==NULL ){
	echo '<script>window.location="../../../"</script>';
}

if($_GET['a'] == 'CallData1') {
    $output = "";
    $R = 0;
    $sqlMin =  "SELECT X1.*, (X1.InCommit - X1.OnHand) AS AddItem
                FROM (SELECT P0.ItemCode,P0.ItemName,P0.OnHand,SUM(P1.Qty-P1.OpenQty) AS InCommit
                    FROM (SELECT T0.ItemCode,T0.ItemName,SUM(T1.OnHand) AS OnHand
                            FROM oitm T0
                            LEFT JOIN oitw T1 ON T1.ItemCode = T0.ItemCode 
                            WHERE T0.ItemCode != '' AND (T1.LocRack LIKE 'A1%' OR T1.LocRack LIKE 'B1%' OR T1.LocRack LIKE 'A2%' OR T1.LocRack LIKE 'B2%' OR T1.LocRack LIKE 'C1-06%' OR T1.LocRack LIKE 'C1-07%' OR T1.LocRack LIKE 'C1-08%') 
                                AND T1.LocRack NOT LIKE 'B1-P%'
                            GROUP BY T0.ItemCode,T0.ItemName
                    ) P0
                    LEFT JOIN picker_sodetail P1 ON P0.ItemCode = P1.ItemCode
                    LEFT JOIN picker_soheader P2 ON P1.DocEntry = P2.SODocEntry AND P1.DocType = P2.DocType
                    WHERE P2.StatusDoc IN (2,3,4,5,6) AND P1.WhsCode IN ('KSY','OUL','MT','TT-C','MT2') 
                    GROUP BY P0.ItemCode,P0.ItemName,P0.OnHand
                ) X1 
               WHERE X1.OnHand < X1.InCommit AND X1.InCommit > 0";
    /*
    $sqlQRY = MySQLSelectX($sqlMin);
   
    while($MinShow = mysqli_fetch_array($sqlQRY)) {
        ++$R;
        $output .= "<tr>".
                        "<td>".
                            "<div class='d-flex align-items-center w-100'>".
                                "<div class='fw-bold text-black' style='width: 35%'>".
                                    "<span>".$MinShow['ItemCode']."</span>".
                                "</div>".
                                "<div class='fw-bold' style='width: 45%'>".
                                    "<span class='text-black'>จำนวนเติม&nbsp;</span>".
                                    "<span class='text-primary'>".$MinShow['AddItem']."</span>".
                                "</div>".
                            "</div>".
                            "<div class=''>".
                                "<div>".
                                    "<span style='font-size: 11.5px;'>".$MinShow['ItemName']."</span>".
                                "</div>".
                            "</div>".
                        "</td>".
                        "<td>".
                            "<div class='d-flex align-items-center text-primary'>".
                                "<a href='restooked.php?itemcode=".$MinShow['ItemCode']."'><i class='far fa-plus-square'></i>&nbsp;เติม</a>".
                            "</div>".
                        "</td>".
                    "</tr>";
    }
    */
    if($R != 0) {
        $arrCol['output'] = $output;
    }else{
        $output .= "<tr>".
                        "<td class='text-center fw-bold' colspan='2'>ไม่มีรายการสินค้าที่ต้องเติม</td>".
                    "</tr>";
        $arrCol['output'] = $output;
    }
}

if($_GET['a'] == 'SearchData') {
    $chkRack = CHKRowDB("SELECT * FROM allwhs WHERE LocationRack = '".$_POST['Data']."'");
    $chkItem = 0;
    if($chkRack > 0) {
        $FindData = 1;
        $arrCol['ItemCode'] = $_POST['Data'];
    }else{
        $chkItem = CHKRowDB("SELECT * 
                             FROM oitm 
                             WHERE (ItemCode = '".$_POST['Data']."' OR BarCode = '".$_POST['Data']."' OR BarCode2 = '".$_POST['Data']."' OR BarCode3 = '".$_POST['Data']."' OR ItemName Like '%".$_POST['Data']."%')");
        if($chkItem > 0) {
            $FindData = 2; //ข้อมูลสินค้า
        }else{
            $FindData = 3; // ไม่พบข้อมูลสินค้า
        }
    }

    if($FindData == 2) {
        if ($chkItem == 1){
            $arrCol['ItemCode'] = $_POST['Data'];
        }else{
            $MySQL = "SELECT ItemCode,ItemName FROM oitm WHERE ItemCode = '".$_POST['Data']."' OR BarCode = '".$_POST['Data']."' OR BarCode2 = '".$_POST['Data']."' OR BarCode3 = '".$_POST['Data']."' OR ItemName Like '%".$_POST['Data']."%'";
            $getItemList = MySQLSelectX($MySQL);
            $TB =  "<div class='tableFix'>
                        <table class='table table-sm'>
                            <tbody style='font-size: 13px;'>";
            while($ItemList = mysqli_fetch_array($getItemList)) {
                $TB .= "<tr>".
                            "<td>".
                                "<div class='d-flex align-items-center'>".
                                    "<div class='fw-bold text-black'>".
                                        "<span>".$ItemList['ItemCode']."</span>".
                                    "</div>".
                                "</div>".
                                "<div class=''>".
                                    "<div>".
                                        "<span style='font-size: 11.5px;'>".$ItemList['ItemName']."</span>".
                                    "</div>".
                                "</div>".
                            "</td>".
                            "<td width='30%'>".
                                "<div class='d-flex align-items-center text-primary'>".
                                    "<a href='restooked.php?itemcode=".$ItemList['ItemCode']."'><i class='far fa-plus-square'></i>&nbsp;เลือก</a>".
                                "</div>".
                            "</td>".
                        "</tr>";
            }
            $TB .= "        </tbody>
                        </table>
                    </div>";

            $arrCol['TB'] = $TB;
        }
    }

    $arrCol['FindData'] = $FindData;
    $arrCol['chkItem'] = $chkItem;
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>