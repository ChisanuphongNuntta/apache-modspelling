<?php
include('../../core/config.core.php');
include('../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
$resultArray = array();
$arrCol = array();
$output = "";

if($_GET['a'] == 'CallData') {
    $Table = $_POST['Table'];

    if($Table == 'ALL') {
        $DataSQL = "";
    }else{
        $sql_IPAddress = "SELECT IPAddress FROM checkertable WHERE TableID = '$Table'";
        $result_IPAddress  = MySQLSelect($sql_IPAddress);
        $DataSQL = "AND T1.IPAddress = '".$result_IPAddress['IPAddress']."'";
    }

    $cDate = date("Y-m-d");
    $Tomorow=  date("Y-m-d",strtotime("+1 days",strtotime($cDate)));
    $sql1 = "SELECT P0.*, CASE WHEN P0.GroupCH = 'TT' AND ((P0.OriDate < '".$cDate."' ) OR (P0.OriDate = '".$cDate."' AND P0.OriTime  < 1300)) THEN 'TB11'
                            WHEN P0.GroupCH = 'TT' AND (P0.OriDate >= '".$cDate."' AND P0.OriTime  >= 1300) THEN 'TB21'
                            WHEN P0.GroupCH = 'MT' AND (P0.DocDueDate <= '".$Tomorow."') THEN 'TB12'
                        ELSE 'TB22' END AS RowGroup,
                        CASE
                            WHEN P0.StatusDoc < 6 THEN 'BB' 
                            WHEN P0.StatusDoc = 6 THEN '6Wait' 
                            WHEN P0.StatusDoc BETWEEN 7 AND 8 THEN '3WaitOpen'
                            WHEN P0.StatusDoc = 9 THEN '4WaitPack'
                            WHEN P0.StatusDoc = 10 THEN '5Packing'
                            WHEN P0.StatusDoc = 11 THEN 'OnLoad'
                        ELSE '6Finish' END AS StatusRow,P1.Status AS FinishGoods

            FROM (SELECT ID,T0.SODocEntry AS DocEntry,T0.DocType,T0.TeamCode,T0.DocNum,T0.OriDate,T0.OriTime,T0.DocDate,T0.DocDueDate,T0.CardCode,T0.CardName,T0.ItemCount,
                        T0.UkeyPicker,T2.uName AS PickerName,T2.uLastName AS PickerLname,T2.uNickName AS PickerNick,T0.StatusDoc,T0.TablePacking,
                        CASE WHEN T0.TeamCode LIKE 'MT%' THEN 'MT' ELSE 'TT' END AS GroupCH,T0.QQst 
                FROM picker_soheader T0
                        JOIN checkertable T1 ON T0.TablePacking = TableID
                        LEFT JOIN users T2 ON T0.UkeyPicker = T2.uKey
                WHERE (T0.StatusDoc BETWEEN 3 AND 11) $DataSQL
                ) P0
                LEFT JOIN pack_header P1 ON P0.ID = P1.IDPick
            ORDER BY RowGroup,StatusRow,P0.GroupCH,P0.TeamCode,P0.CardCode";
    $qry = MySQLSelectX($sql1);
    $tbl['TB1'] = "";
    $tbl['TB2'] = "";
    while($result = mysqli_fetch_array($qry)) {

        if ($result['StatusDoc'] == 11 AND $result['FinishGoods'] == 'Y'){
            $NotRow = 0;
        }else{
            switch($result['StatusRow']) {
                case "4WaitPack": 
                    $ClassRow = "style='background-color: #1e80e8; color: #fff;'"; 
                    $txtShow = "รอแพ็คสินค้า";
                    break;
                case "5Packing": 
                    $ClassRow = "class='bg-light-success text-success'"; 
                    $txtShow = "กำลังแพคสินค้า";
                    break;
                case "6Finish":
                    if  ($result['FinishGoods'] == 'Y'){
                        $ClassRow = "class='text-light' style='background-color: #4CAF50;'"; 
                        $txtShow = "สินค้าพร้อมส่ง";
                    }else{
                        $ClassRow = "class='text-light bg-danger'";
                        $txtShow = "ยังไม่กดยืนยันรายการ3"; 
                    }
                    break;
                case "6Wait" :
                    $ClassRow = "class='table-warning'";
                    $txtShow = "กำลังรอสินค้า"; 
                    break;

                default: $ClassRow = NULL; $txtShow = "กำลังเบิกสินค้า"; break;
            }

            if($result['StatusDoc'] < 9 AND substr($result['DocNum'],0,2) != 'WO') {
                $DataType = "ORDR";
            }else{
                switch (substr($result['DocNum'],0,2)){
                    case 'SO' :
                        $DataType = "OINV";
                        break;
                    case 'SA' :
                    case 'SB' :
                        $DataType = "ODLN";
                        break;
                    default :
                        $DataType = "OWAS";
                    break;
                }
            }

            if ($result['QQst'] == 'Y'){
                $QQ = " <strong class='badge bg-danger'>บิลด่วน</strong>";
            }else{
                $QQ = "";
            }

            $tbl[substr($result['RowGroup'],0,3)] .= 
                "<tr ".$ClassRow.">
                    <td class='text-center'><input class='form-check-input' type='checkbox'></td>
                    <td class='text-center'>".$result['TeamCode']."</td>";
            if($DataType == 'OWAS') {
                $tbl[substr($result['RowGroup'],0,3)] .= " 
                    <td class='text-center'>".$result['DocNum']."</td>";
            }else{
                $tbl[substr($result['RowGroup'],0,3)] .= " 
                    <td class='text-center'><span onclick=\"CallModal('".$DataType."','".$result['DocEntry']."')\" class='ViewData' style='cursor: pointer;'>".$result['DocNum']."</span></td>";
            }
            $tbl[substr($result['RowGroup'],0,3)] .= "         
                    <td>".$result['CardCode']." ".$result['CardName'].$QQ."</td>
                    <td class='text-center'>".date('d/m/Y',strtotime($result['DocDate']))."</td>
                    <td class='text-center'>".date('d/m/Y',strtotime($result['DocDueDate']))."</td>
                    <td class='text-right'>".$result['ItemCount']."</td>
                    <td>".$result['PickerName']." ".$result['PickerLname']." (".$result['PickerNick'].")</td>
                    <td class='text-center'>".$txtShow."</td>
                    <td class='text-center'> โต๊ะที่ ".$result['TablePacking']."</td>
                </tr>";
        }
    }
    $arrCol['Table1'] = $tbl['TB1'];
    $arrCol['Table2'] = $tbl['TB2'];

    $sql2 = "SELECT P0.*, P1.Status AS FinishGoods
            FROM (SELECT ID,T0.SODocEntry AS DocEntry,T0.DocType,T0.TeamCode,T0.DocNum,T0.OriDate,T0.OriTime,T0.DocDate,T0.DocDueDate,T0.CardCode,T0.CardName,T0.ItemCount,
            T0.UkeyPicker,T2.uName AS PickerName,T2.uLastName AS PickerLname,T2.uNickName AS PickerNick,T0.StatusDoc,T0.TablePacking,
            CASE WHEN T0.TeamCode LIKE 'MT%' THEN 'MT' ELSE 'TT' END AS GroupCH,T0.QQst 
            FROM picker_soheader T0
            JOIN checkertable T1 ON T0.TablePacking = TableID
            LEFT JOIN users T2 ON T0.UkeyPicker = T2.uKey
            WHERE (T0.StatusDoc = 11) $DataSQL
            ) P0
            LEFT JOIN pack_header P1 ON P0.ID = P1.IDPick
            ORDER BY P0.GroupCH,P0.TeamCode,P0.CardCode";
    $qry2 = MySQLSelectX($sql2);
    $tbl['TB3'] = "";
    while($result2 = mysqli_fetch_array($qry2)) {
        if($result2['FinishGoods'] == 'Y'){
            $ClassRow = "class='text-light' style='background-color: #4CAF50;'"; 
            $txtShow = "สินค้าพร้อมส่ง";
        }else{
            $ClassRow = "class='text-light bg-danger'";
            $txtShow = "ยังไม่กดยืนยันรายการ"; 
        }

        if($result2['StatusDoc'] < 9 AND substr($result2['DocNum'],0,2) != 'WO') {
            $DataType = "ORDR";
        }else{
            switch (substr($result2['DocNum'],0,2)){
                case 'SO' :
                    $DataType = "OINV";
                    break;
                case 'SA' :
                case 'SB' :
                    $DataType = "ODLN";
                    break;
                default :
                    $DataType = "OWAS";
                break;
            }
        }

        if ($result2['QQst'] == 'Y'){
            $QQ = " <strong class='badge bg-danger'>บิลด่วน</strong>";
        }else{
            $QQ = "";
        }

        $tbl["TB3"] .= 
            "<tr ".$ClassRow.">
                <td class='text-center'><input class='form-check-input' type='checkbox'></td>
                <td class='text-center'>".$result2['TeamCode']."</td>";
        if($DataType == 'OWAS') {
            $tbl["TB3"] .= " 
                <td class='text-center'>".$result2['DocNum']."</td>";
        }else{
            $tbl["TB3"] .= " 
                <td class='text-center'><span onclick=\"CallModal('".$DataType."','".$result2['DocEntry']."')\" class='ViewData' style='cursor: pointer;'>".$result2['DocNum']."</span></td>";
        }
        $tbl["TB3"] .= "         
                <td>".$result2['CardCode']." ".$result2['CardName'].$QQ."</td>
                <td class='text-center'>".date('d/m/Y',strtotime($result2['DocDate']))."</td>
                <td class='text-center'>".date('d/m/Y',strtotime($result2['DocDueDate']))."</td>
                <td class='text-right'>".$result2['ItemCount']."</td>
                <td>".$result2['PickerName']." ".$result2['PickerLname']." (".$result2['PickerNick'].")</td>
                <td class='text-center'>".$txtShow."</td>
                <td class='text-center'> โต๊ะที่ ".$result2['TablePacking']."</td>
            </tr>";
        $arrCol['Table3'] = $tbl['TB3'];
    }
}

if($_GET['a'] == 'CallModal') {
    $Func     = $_POST['Func'];
    $DocEntry = $_POST['DocEntry'];
    switch ($Func) {
        case 'ORDR' :
            $sqldata = "SELECT  T0.[DocEntry], T0.[DocDate], T0.[DocDueDate], T2.[BeginStr], T0.[DocNum], T0.[CardCode], T0.[CardName], T0.[U_PONo],T3.[SlpName],
                                T1.[VisOrder],T1.[ItemCode], T1.[CodeBars], T1.[Dscription],T1.[WhsCode], T1.[Quantity],  T1.[UnitMsr], 
                                T1.[LineStatus],T0.[OwnerCode],T4.[lastname],T4.[firstname],T0.AtcEntry
                        FROM ORDR T0
                        JOIN RDR1 T1 ON T0.[DocEntry] = T1.[DocEntry]
                        LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
                        JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
                        LEFT JOIN OHEM T4 ON T0.[OwnerCode] = T4.[EmpID] 
                        WHERE T0.[DocEntry] = '$DocEntry' ORDER BY T1.[VisOrder]";
            break;
        case 'OINV' :
            $sqldata = "SELECT  T0.[DocEntry], T0.[DocDate], T0.[DocDueDate], T2.[BeginStr], T0.[DocNum], T0.[CardCode], T0.[CardName], T0.[U_PONo],T3.[SlpName],
                                T1.[VisOrder],T1.[ItemCode], T1.[CodeBars], T1.[Dscription],T1.[WhsCode], T1.[Quantity],  T1.[UnitMsr], 
                                T1.[LineStatus],T0.[OwnerCode],T4.[lastname],T4.[firstname]
                        FROM OINV T0
                        JOIN INV1 T1 ON T0.[DocEntry] = T1.[DocEntry]
                        LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
                        JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
                        LEFT JOIN OHEM T4 ON T0.[OwnerCode] = T4.[EmpID] 
                        WHERE T1.[BaseEntry] = '$DocEntry' ORDER BY T1.[VisOrder]";
            break;
        case 'ODLN' :
            $sqldata = "SELECT  T0.[DocEntry], T0.[DocDate], T0.[DocDueDate], T2.[BeginStr], T0.[DocNum], T0.[CardCode], T0.[CardName], T0.[U_PONo],T3.[SlpName],
                                T1.[VisOrder],T1.[ItemCode], T1.[CodeBars], T1.[Dscription],T1.[WhsCode], T1.[Quantity],  T1.[UnitMsr], 
                                T1.[LineStatus],T0.[OwnerCode],T4.[lastname],T4.[firstname]
                        FROM ODLN T0
                        JOIN DLN1 T1 ON T0.[DocEntry] = T1.[DocEntry]
                        LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
                        JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]
                        LEFT JOIN OHEM T4 ON T0.[OwnerCode] = T4.[EmpID] 
                        WHERE T1.[BaseEntry] = '$DocEntry' ORDER BY T1.[VisOrder]";
             break;
    }
// echo $sqldata;
    $qry = SAPSelect($sqldata);
    $i   = 0;
    while($result = odbc_fetch_array($qry)) {
        $i++;
        if($i == 1) {
            $CusName = $result['CardCode']." - ".conutf8($result['CardName']);
            $DocDate = $result['DocDate'];
            if ($Func == 'OINV'){
                $DocNum ="IV-".$result['DocNum'];
            }else{
                $DocNum = $result['BeginStr'].$result['DocNum'];
            }
            $saleName = conutf8($result['SlpName']);
            $DueDate  = $result['DocDueDate'];
            $PoNo     = $result['U_PONo'];
            $CoSale   = conutf8($result['lastname'])." ".conutf8($result['firstname']);
        }
        $VisOrder[$i]                = $result['VisOrder'];
        $itemCode[$VisOrder[$i]]     = $result['ItemCode'];
        $CodeBars[$VisOrder[$i]]     = $result['CodeBars'];
        $itemName[$VisOrder[$i]]     = conutf8($result['Dscription']);
        $unitMsr[$VisOrder[$i]]      = conutf8($result['UnitMsr']);
        $QTY[$VisOrder[$i]]          = $result['Quantity'];
        $BarCode[$VisOrder[$i]]      = $result['CodeBars'];
        $WHS[$VisOrder[$i]]          = conutf8($result['WhsCode']);
        $LocationRack[$VisOrder[$i]] = "KSY-Recive";
        $LineST[$VisOrder[$i]]       = $result['LineStatus'];

    }

    $Data = "
        <div class='table-responsive'>  
            <table class='table table-sm table-borderless' style='font-size: 13px;'>
                <tr>
                    <td class='fw-bolder'>ชื่อลูกค้า</td>
                    <td>".$CusName."</td>

                    <td class='fw-bolder'>วันที่เอกสาร</td>          
                    <td>".date('d/m/Y',strtotime($DocDate))."</td>

                    <td class='fw-bolder'>เลขที่ใบสั่งขาย</td>
                    <td>".$DocNum."</td>
                </tr>
                <tr>
                    <td class='fw-bolder'>พนักงานขาย</td>
                    <td>".$saleName."</td>

                    <td class='fw-bolder'>วันที่ส่งของ</td>          
                    <td>".date('d/m/Y',strtotime($DueDate))."</td>

                    <td class='fw-bolder'>เลขที่ PO</td>
                    <td>".$PoNo."</td>
                </tr>
                <tr>
                    <td class='fw-bolder'>ธุรการขาย</td>
                    <td>".$CoSale."</td>
                </tr>
            </table>
        </div>";

    $Data .= "
        <div class='table-responsive'>  
            <table class='table table-sm table-bordered'>
                <thead style='font-size: 13px;'>
                    <tr class='text-center'>
                        <th>ลำดับ</th>
                        <th>รหัสสินค้า</th>
                        <th>บาร์โค้ด</th>
                        <th>ชื่อสินค้า</th>
                        <th>คลัง</th>
                        <th>หน่วยนับ</th>
                        <th>จำนวน</th>
                    </tr>
                </thead>
                <tbody style='font-size: 12px;' class='fw-bold'>";
    for ($x = 1; $x <= $i; $x++) {
        if ($LineST[$VisOrder[$x]] == 'O'){
            $Data .= "<tr style='color:#000000; background:#ffcce0;'>";
        }else{
            $Data .= "<tr>";
        }
        $Data .= "
            <td class='text-center'>".$x."</td>
            <td class='text-center'>".$itemCode[$VisOrder[$x]]."</td>
            <td class='text-center'>".$CodeBars[$VisOrder[$x]]."</td>
            <td>".$itemName[$VisOrder[$x]]."</td>
            <td class='text-center'>".$WHS[$VisOrder[$x]]."</td>
            <td class='text-center'>".$unitMsr[$VisOrder[$x]]."</td>
            <td class='text-right'>".number_format($QTY[$VisOrder[$x]],0)."</td>
        ";
    }
    $Data .= "   
                </tbody>     
            </table>
        </div>";

    $Data .= "
        <div class='table-responsive pt-2'>
            <table class='table table-sm table-bordered'>
                <thead style='font-size: 13px;'>
                    <tr class='text-center'>
                        <th>ลำดับ</th>
                        <th>ชื่อเอกสารแนบ</th>
                        <th>ดาวน์โหลด</th>
                    </tr>
                </thead>
                <tbody style='font-size: 12px;'>";
    $AttSQL = "SELECT TOP 1 T0.AtcEntry FROM ORDR T0 WHERE T0.DocEntry = $DocEntry";
    $AttQRY = SAPSelect($AttSQL);
    $AttRST = odbc_fetch_array($AttQRY);
    $AtcEntry = $AttRST['AtcEntry'];
    if($AtcEntry != NULL) {
        $AttSQL  = "SELECT T0.trgtPath, T0.FileName,T0.FileExt FROM ATC1 T0 WHERE T0.AbsEntry = $AtcEntry ORDER BY T0.Line ASC";
		$AttRows = ChkRowSAP($AttSQL);
        // echo $AttRows;
        if($AttRows == 0) {
            $Data .= "
                    <tr class='text-center'>
                        <td colspan='3'>ไม่มีไฟล์แนบ</td>
                    </tr>";
		} else {
			$AttQRY = SAPSelect($AttSQL);
			$i = 0;
			while($AttRST = odbc_fetch_array($AttQRY)) {
                $i++;
                $Data .= "
                    <tr >
                        <td class='text-center'>$i</td>
                        <td>".conutf8($AttRST['FileName'].".".$AttRST['FileExt'])."</td>
                        <td class='text-center'><a href='file:".str_replace(" ","%20",str_replace("\\","/",$AttRST['trgtPath']))."/".conutf8(str_replace(" ","%20",$AttRST['FileName']).".".$AttRST['FileExt'])."' target='_blank'><i class='fas fa-download fa-fw fa-1x'></i></a></td>
                    </tr>";
			}
		}
    }else{
        $AttSQL =
			"SELECT
				T0.VisOrder, T0.FileOriName, T0.FileDirName, T0.FileExt
			FROM order_attach T0
			LEFT JOIN order_header T1 ON T0.DocEntry = T1.DocEntry
			WHERE T1.ImportEntry = $DocEntry AND T0.FileStatus = 'A'";
		$AttRows = ChkRowDB($AttSQL);
		if($AttRows == 0) {
			$Data .= "
                    <tr class='text-center'>
                        <td colspan='3'>ไม่มีไฟล์แนบ</td>
                    </tr>";
		} else {
			$AttQRY = MySQLSelectX($AttSQL);
			$i = 0;
			while($AttRST = mysqli_fetch_array($AttQRY)) {
                $i++;
                $Data .= "
                    <tr>
                        <td class='text-center'>$i</td>
                        <td>".$AttRST['FileOriName'].".".$AttRST['FileExt']."</td>
                        <td class='text-center'><a href='../FileAttach/SO/".$AttRST['FileDirName'].".".$AttRST['FileExt']."' target='_blank'><i class='fas fa-download fa-fw fa-1x'></i></a></td>
                    </tr>";
			}
		}
    }

    $Data .= "
                </tbody>  
            </table>
        </div>
        <div class='alert alert-warning text-center'>
            <strong>คำเตือน!</strong> กรุณาดาวน์โหลดไฟล์ผ่านเครือข่ายภายในบริษัทฯ เท่านั้น<br/>หากดาวน์โหลดไม่ได้กรุณาติดตั้ง <a href='https://chrome.google.com/webstore/detail/enable-local-file-links/nikfmfgobenbhmocjaaboihbeocackld' target='_blank'>ส่วนขยาย Google Chrome <i class='fas fa-external-link-alt fa-fw fa-1x'></i></a> ก่อนดาวน์โหลดอีกครั้ง
        </div>";


    $arrCol['Data'] = $Data;
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>