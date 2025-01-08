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
switch ($_POST['Func']){
    case 'ORDR' :
        $sqldata = "SELECT
                    T0.[DocEntry], T0.[DocDate], T0.[DocDueDate], T2.[BeginStr], T0.[DocNum], T0.[CardCode], T0.[CardName], T0.[U_PONo],T3.[SlpName],
                    T1.[VisOrder],T1.[ItemCode], T1.[CodeBars], T1.[Dscription],T1.[WhsCode], T1.[Quantity],  T1.[UnitMsr], 
                    T1.[LineStatus],T0.[OwnerCode],T4.[lastname],T4.[firstname]

            FROM ORDR T0
                 JOIN RDR1 T1 ON T0.[DocEntry] = T1.[DocEntry]
                 LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
                 JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
                 LEFT JOIN OHEM T4 ON T0.[OwnerCode] = T4.[EmpID] 
            WHERE T0.[DocEntry] = '".$_POST['docEntry']."' ORDER BY T1.[VisOrder]";
        break;
    case 'OINV' :
        $sqldata = "SELECT
                            T0.[DocEntry], T0.[DocDate], T0.[DocDueDate], T2.[BeginStr], T0.[DocNum], T0.[CardCode], T0.[CardName], T0.[U_PONo],T3.[SlpName],
                            T1.[VisOrder],T1.[ItemCode], T1.[CodeBars], T1.[Dscription],T1.[WhsCode], T1.[Quantity],  T1.[UnitMsr], 
                            T1.[LineStatus],T0.[OwnerCode],T4.[lastname],T4.[firstname]
                    FROM OINV T0
                        JOIN INV1 T1 ON T0.[DocEntry] = T1.[DocEntry]
                        LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
                        JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
                        LEFT JOIN OHEM T4 ON T0.[OwnerCode] = T4.[EmpID] 
                    WHERE T1.[BaseEntry] = '".$_POST['docEntry']."' ORDER BY T1.[VisOrder]";
        break;
    case 'ODLN' :
        $sqldata = "SELECT
                            T0.[DocEntry], T0.[DocDate], T0.[DocDueDate], T2.[BeginStr], T0.[DocNum], T0.[CardCode], T0.[CardName], T0.[U_PONo],T3.[SlpName],
                            T1.[VisOrder],T1.[ItemCode], T1.[CodeBars], T1.[Dscription],T1.[WhsCode], T1.[Quantity],  T1.[UnitMsr], 
                            T1.[LineStatus],T0.[OwnerCode],T4.[lastname],T4.[firstname]
                    FROM ODLN T0
                        JOIN DLN1 T1 ON T0.[DocEntry] = T1.[DocEntry]
                        LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
                        JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
                        LEFT JOIN OHEM T4 ON T0.[OwnerCode] = T4.[EmpID] 
                    WHERE T1.[BaseEntry] = '".$_POST['docEntry']."' ORDER BY T1.[VisOrder]";
         break;
    case 'OWBS' :
        $waiwai = 2;
        break;


}

//echo $sqldata;

$sapfqryModal = odbc_exec($sapconn,$sqldata);
$i=0;
$DocTotal = 0;
while ($DataMain = odbc_fetch_array($sapfqryModal)){
    $i++;
    if ($i ==1){
        $CusName = $DataMain['CardCode']." - ".conutf8($DataMain['CardName']);
        $DocDate = $DataMain['DocDate'];
        if ($_POST['Func'] == 'OINV'){
            $DocNum ="IV-".$DataMain['DocNum'];
        }else{
            $DocNum = $DataMain['BeginStr'].$DataMain['DocNum'];
        }
        

        $saleName = conutf8($DataMain['SlpName']);
        $DueDate = $DataMain['DocDueDate'];
        $PoNo = $DataMain['U_PONo'];
        $NetPrice = $DataMain['DocTotal'];
        $CoSale = conutf8($DataMain['lastname'])." ".conutf8($DataMain['firstname']);
    }
    $VisOrder[$i] = $DataMain['VisOrder'];
    $itemCode[$VisOrder[$i]] = $DataMain['ItemCode'];
    $CodeBars[$VisOrder[$i]] = $DataMain['CodeBars'];
    $itemName[$VisOrder[$i]] = conutf8($DataMain['Dscription']);
    $unitMsr[$VisOrder[$i]] = conutf8($DataMain['UnitMsr']);
    $QTY[$VisOrder[$i]] = $DataMain['Quantity'];
    $oQTY[$VisOrder[$i]] = $DataMain['OpenQty'];
    $BarCode[$VisOrder[$i]] = $DataMain['CodeBars'];
    $WHS[$VisOrder[$i]] = conutf8($DataMain['WhsCode']);
    $OnHand[$VisOrder[$i]] = $DataMain['OnHand'];
    $LocationRack[$VisOrder[$i]] = "KSY-Recive";
}

$output .= "  
            <div class='table-responsive'>  
                <table border='0' cellpadding='0' cellspacing='0' background='#FFFFFF'>
                <tr style='font-size:18px;'>
                    <td class='htxtinput' width='150'>&nbsp;&nbsp;ชื่อลูกค้า</td>
                    <td class='txtinput' width='20'>&nbsp;</td>
                    <td class='txtinput'>
                        <input name='cus_code' type='text' class='txtdisable' id='cus_code' value='".$CusName."' style='width:250px;  color:#900;font-size:16px;' readonly></td>
                    </td>
                    <td class='txtinput'>&nbsp;</td>
                    <td width='120' class='htxtinput' style='text-align:center;'>&nbsp;&nbsp;วันที่เอกสาร</td>
                    <td width='5' class='txtinput'>&nbsp;</td>
                    <td width='148' class='txtinput'><input name='cust_contact' type='text' value='".date('d/m/Y',strtotime($DocDate))."' class='txtdisable' id='cust_contact' size='28' maxlength='30' readonly='' style='width:120px; text-align:center; color:#900;font-size:16px;'></td>
                    <td width='5' class='txtinput'>&nbsp;</td>
                    <td width='120' class='htxtinput' style='text-align:center;'>&nbsp;&nbsp;เลขที่ใบขาย</td>
                    <td width='5' class='txtinput'>&nbsp;</td>
                    <td class='txtinput' colspan='2'>
                        <input name='DocNo' type='text' class='txtdisable' id='DocNo'  value='".$DocNum."' style='width:125px; text-align:center; color:#900;font-size:16px;'  readonly>
                    </td>
                </tr>
                <tr style='font-size:18px;'>
                    <td class='htxtinput'>&nbsp;&nbsp;พนักงานขาย</td>
                    <td class='txtinput'>&nbsp;</td>
                    <td class='txtinput'>
                        <input name='cus_code' type='text' class='txtdisable' id='cus_code' value='".$saleName."' style='width:250px;  color:#900;font-size:16px;' readonly></td>
                    </td>
                    <td class='txtinput'>&nbsp;</td>
                    <td width='120' class='htxtinput' style='text-align:center;'>&nbsp;&nbsp;วันที่ส่งของ</td>
                    <td width='5' class='txtinput'>&nbsp;</td>
                    <td width='148' class='txtinput'><input name='cust_contact' type='text' value='".date('d/m/Y',strtotime($DueDate))."' class='txtdisable' id='cust_contact' size='28' maxlength='30' readonly='' style='width:120px; text-align:center; color:#900;font-size:16px;'></td>
                    <td width='5' class='txtinput'>&nbsp;</td>
                    <td class='htxtinput' style='text-align:center;'>&nbsp;&nbsp;เลขที่ PO</td>
                    <td class='txtinput'>&nbsp;</td>
                    <td class='txtinput' >
                        <input name='cus_code' type='text' class='txtdisable' id='cus_code' value='".$PoNo."' style='width:125px;  text-align:center; color:#900;font-size:16px;' readonly></td>
                    </td>
                </tr>
                <tr style='font-size:18px;'>
                    <td class='htxtinput'>&nbsp;&nbsp;ธุรการขาย</td>
                    <td class='txtinput'>&nbsp;</td>
                    <td class='txtinput'>
                        <input name='cus_code' type='text' class='txtdisable' id='cus_code' value='".$CoSale."' style='width:250px;  color:#900;font-size:16px;' readonly></td>
                    </td>
                    <td class='txtinput' colspan='8'>&nbsp;</td>
                </tr>
            </table>    ";
            
$output .= "<BR>
            <table width='1150' border='0' cellpadding='0' cellspacing='0' class='table table-bordered table-hover'>
                <thead>
                    <tr>
                        <th width='42' height='22' align='center' class='txtdbhead'><span style='font-size:16px;'>ลำดับ</span></th>
                        <th width='100' height='22' align='center' class='txtdbhead'><span style='font-size:16px;'>รหัสสินค้า</span></th>
                        <th width='100' height='22' align='center' class='txtdbhead'><span style='font-size:16px;'>BarCode</span></th>
                        <th width='290' height='22' align='center' class='txtdbhead'><span style='font-size:16px;'>ชื่อสินค้า</span></th>
                        <th width='96' height='22' align='center' class='txtdbhead'><span style='font-size:16px;'>คลัง</span></th>
                        <th width='92' height='22' align='center' class='txtdbhead'><span style='font-size:16px;'>หน่วยนับ</span></th>
                        <th width='96' height='22' align='center' class='txtdbhead'><span style='font-size:16px;'>จำนวน</span></th>
                    </tr>
                </thead>
            <body>";
for ($x=1;$x<=$i;$x++){
    if ($LineST[$VisOrder[$x]] == 'O'){
        $output .= "<tr style='color:#000000; background:#ffcce0;font-size:18px;'>";
    }else{
        $output .= "<tr style='font-size:18px;'>";
    }

    $output .= "    <td align='center'>".$x."</td>";
    $output .= "    <td align='center'>".$itemCode[$VisOrder[$x]]."</td>";
    $output .= "    <td align='center'>".$CodeBars[$VisOrder[$x]]."</td>";
    $output .= "    <td align='left'>".$itemName[$VisOrder[$x]]."</td>";
    $output .= "    <td align='center'>".$WHS[$VisOrder[$x]]."</td>";
    $output .= "    <td align='center'>".$unitMsr[$VisOrder[$x]]."</td>";
    $output .= "    <td align='right'>".number_format($QTY[$VisOrder[$x]])."</td>";

    $output .= "</tr>";
}
$output .= "    </body>
            </table>";
$output .= "<div class=\"text-right\"> <button type=\"button\" class=\"btn btn-danger\" id=\"btn-printSO\" data-DocEntry=\"".$_POST['docEntry']."\"> <i class=\"fas fa-print fa-fw fa-lg\"></i> พิมพ์ </button></div>";
$output .= "<script type=\"text/javascript\">
    $('#btn-printSO').on('click',function(e){
        e.preventDefault();
        var docnum = $(this).attr('data-DocEntry');
        window.open(\"../../mk/dashboard/report/sales/printSO.php?doc=\"+docnum+\"\",\"_blank\");
    });
</script>";
echo $output;


