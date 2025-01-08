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

if($_GET['a'] == 'CallData') {
    $cDate = date("Y-m-d");
    $cDatePluss3 = date("Y-m-d",strtotime("+3 days",strtotime($cDate)));
    $cDateReduce1 = date("Y-m-d",strtotime("-1 days",strtotime($cDate)));

    if ($_SESSION['uClass'] == 44 OR $_SESSION['uClass'] == 45 OR $_SESSION['uClass'] == 0){
        $ListShow =  " ";
    }else{
        $ListShow =  " AND T0.UkeyPicker = '".$_SESSION['ukey']."' ";
    }
    $sql = "SELECT T0.ID,T0.SODocEntry AS DocEntry,T0.DocNum,T0.QQst,T0.DocType,T0.DocDate,T0.OriTime,T0.DocDueDate,T0.TeamCode,T0.StatusDoc,T2.NameStatus,T0.TablePacking,
                CASE WHEN (T0.OriDate < '".LastWorkDate($cDate)."' AND T0.TeamCode NOT LIKE 'MT%') OR T0.QQst = 'Y' THEN 'A0'
                    WHEN (T0.OriDate = '".LastWorkDate($cDate)."' AND T0.TimeType = 'AM' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A1'
                    WHEN (T0.OriDate = '".LastWorkDate($cDate)."' AND T0.TimeType = 'PM' AND T0.TeamCode NOT LIKE 'MT%')THEN 'A2'
                    WHEN (T0.OriDate = '".$cDate."'  AND  T0.TimeType = 'AM' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A3'
                    WHEN (T0.TeamCode LIKE 'MT%' AND T0.DocDueDate < '".$cDatePluss3."')  THEN 'A4'
                    ELSE '' END AS PickDay,
                    T1.uName AS PickName, T1.uNickName AS PickNname,
                    CASE WHEN T0.StatusDoc = 5 OR T0.StatusDoc = 6 THEN 0 
                        WHEN T0.StatusDoc < 4 THEN 1
                        WHEN T0.StatusDoc = 4 THEN 2
                        WHEN T0.StatusDoc > 6 THEN 3
                        ELSE 4 END AS RunOrder
            FROM picker_soheader T0
                LEFT JOIN users T1 ON T0.UkeyPicker = T1.uKey
                LEFT JOIN docstatus T2 ON T0.StatusDoc = T2.CodeStatus
            WHERE T0.DocNum != '' AND ((T0.StatusDoc >= 1 AND T0.StatusDoc < 11) ".$ListShow." ) OR (T0.StatusDoc = 0 AND (DATE(T0.TimeCUT1) = '".$cDateReduce1."' OR DATE(T0.TimeCUT1) = '".$cDate."'))
            ORDER BY T0.QQst DESC,RunOrder,PickDay,T0.DocDueDate,T0.TimeType";
    // echo $sql;
    $sqlQRY = MySQLSelectX($sql);
    $cx = 0;
    while($result = mysqli_fetch_array($sqlQRY)) {
        if($result['PickDay'] != ''){
            $cx++;
            $DocID[$cx] = $result['ID'];
            $DataSO['DocEntry'][$DocID[$cx]] = $result['DocEntry'];
            $DataSO['DocType'][$DocID[$cx]] = $result['DocType'];
            $DataSO['DocNum'][$DocID[$cx]] = $result['DocNum'];
            $DataSO['QQst'][$DocID[$cx]] = $result['QQst'];
            $DataSO['TeamCode'][$DocID[$cx]] = $result['TeamCode'];
            $DataSO['ST'][$DocID[$cx]] = $result['StatusDoc'];
            $DataSO['TxtST'][$DocID[$cx]] = $result['NameStatus'];
            $DataSO['Group'][$DocID[$cx]] = $result['PickDay'];
            $DataSO['DocDue'][$DocID[$cx]] = $result['DocDueDate'];
            $DataSO['Picker'][$DocID[$cx]] = $result['PickNname'];
            $DataSO['Table'][$DocID[$cx]] = $result['TablePacking']; 
        }
    }

    $Tbody = "";
    if ($cx == 0){
        $Tbody = "<tr>
                    <td class='text-center fw-bold'>
                        ไม่มีข้อมูลรายการใบสั่งขาย
                    </td>
                  </tr>";
    }else{
        for ($i=1; $i <= $cx; $i++){
            $text = "";
            switch ($DataSO['ST'][$DocID[$i]]){
                case 0 :
                    $Color = "#f5f5f5"; // เอกสารยกเลิก เทา
                    $text = "text-muted";
                    break;
                case 2 :
                    $Color = "#fff"; // รอหยิบสินค้า ขาว
                    break;
                case 3 :
                    $Color = "#d9edf7"; // กำลังหยิบสินค้า ฟ้า
                    break;
                case 4 :
                    $Color = "#fcf8e3"; // รอตัดสินค้า เหลือง
                    break;
                case 5 :
                    $Color = "#ebd460";//ยืนยันตัดสินค้า เหลืองเข้ม
                    break;
                case 6 :
                    $Color = "#f2dede"; //รอสินค้า/รอแปลงสินค้า, คืนบิล/รอกรอกข้อมูลการจัดส่ง แดง
                    break;
                case 7 :
                case 8 :
                case 9 :
                case 10 :
                    $Color = "#dff0d8";//รอเปิดบิล-กำลังแพ็ค // เขียว
                    break;
            }

            $BilQQ = "";
            if ($DataSO['QQst'][$DocID[$i]] == 'Y'){ $BilQQ = "(บิลด่วน)"; }
            switch ($DataSO['DocType'][$DocID[$i]]){
                case 'ORDR' :
                        $DocType = "";
                        break;
                case 'OWAS' :
                        $DocType = "(ฝาก)</span>";
                        break;
                case 'OWAB' :
                        $DocType = "(เบิก)</span>";
                        break;
            }

            if ($_SESSION['uClass'] == 44 OR $_SESSION['uClass'] == 45 OR $_SESSION['uClass'] == 0){
                $nameShow = $DataSO['Picker'][$DocID[$i]];
            }else{
                $nameShow = "";
            }

            $sqlCHK = "SELECT SUM(P0.RowItem) AS RowItem
                    FROM (
                        SELECT 1 AS RowItem,
                            CASE WHEN T0.Qty = T0.OpenQty THEN 1 ELSE 0 END AS FinishItem
                        FROM  picker_sodetail T0
                        WHERE T0.DocEntry = '".$DataSO['DocEntry'][$DocID[$i]]."' AND T0.DocType = '".$DataSO['DocType'][$DocID[$i]]."'
                    ) P0";
            $resultCHK = MySQLSelect($sqlCHK);

            $Tbody .=   "<tr>".
                            "<td class='pb-0'>".
                                "<div class='ps-2 pe-2 pt-2 border border-1' style='border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px ".$Color."; background-color: ".$Color.";'>".
                                    "<div class='d-flex w-100 ".$text."'>".
                                        "<div style='width: 62%'>".
                                            "<span class='fw-bolder'>เลขที่ใบสั่งขาย</span>
                                            <a class='fw-bold ".$text."' href='picklist.php?docety=".$DocID[$i]."'>".$DataSO['DocNum'][$DocID[$i]]."</a>
                                        </div>
                                        <div style='width: 8%'>";
                                    if($resultCHK['RowItem'] != "") {
                                        $Tbody .= "<span class='fw-bold'>[".$resultCHK['RowItem']."]</apan>";
                                    }else{
                                        $Tbody .= "<span class='fw-bold'></apan>";
                                    }
                              $Tbody .= "</div>".
                                        "<div class='text-right'style='width: 30%'>".
                                            "<span class='fw-bolder' style='color: #0059b3;'>".$DocType."</span>".
                                            "<span class='text-primary fw-bolder'>".$BilQQ."</span>".
                                        "</div>".
                                    "</div>".
                                "</div>".
                                "<div class='p-2 border border-1' style='border-radius: 0px 0px 10px 10px; box-shadow: 1px 1px ".$Color.";'>".
                                    "<div class='d-flex'>".
                                        "<div class='".$text."' style='width: 25%;'>".
                                            "<span class='fw-bolder'>ฝ่าย</span> <span>".$DataSO['TeamCode'][$DocID[$i]]."</span>&nbsp;&nbsp;".
                                        "</div>".
                                        "<div class='".$text."' style='width: 20%;'>".
                                            "<i class='fas fa-user'></i> <span>".$nameShow."</span>&nbsp;&nbsp;".
                                        "</div>".
                                        "<div class='".$text."' style='width: 20%;'>".
                                            "<i class='fas fa-archive'></i> <span>โต๊ะ ".$DataSO['Table'][$DocID[$i]]."</span>&nbsp;&nbsp;".
                                        "</div>".
                                        "<div class='".$text."' style='width: 35%;'>".
                                            "<i class='fas fa-truck'></i> <span>".date("d/m/Y",strtotime($DataSO['DocDue'][$DocID[$i]]))."</span>".
                                        "</div>".
                                    "</div>".
                                    "<div class='d-flex justify-content-between ".$text."'>".
                                        "<div>".
                                            "<span class='fw-bolder'>สถานะ</span> <span>".$DataSO['TxtST'][$DocID[$i]]."</span>".
                                        "</div>".
                                        "<a class='".$text."' data-bs-toggle='collapse' href='#CollaT".$DocID[$i]."' role='button' aria-expanded='false' aria-controls='CollaT".$DocID[$i]."'>รายละเอียด <i class='fas fa-angle-down'></i></a>".
                                    "</div>".
                                "</div>";
                    $sqlDetail ="SELECT T0.ID,T0.SODocEntry,T0.DocType,T0.QQst,T0.CardCode,T0.CardName,T0.OriDate,T0.OriTime,T0.DocDueDate
                                FROM picker_soheader T0
                                WHERE T0.ID = '".$DocID[$i]."'";
                    $resultDetail = MySQLSelect($sqlDetail);
                    if ($resultDetail['QQst'] == 'Y'){
                        $QQ = "(บิลด่วน)";
                    }else{
                        $QQ = "";
                    }
                    switch ($resultDetail['DocType']){
                        case 'ORDR' :
                            $DocType = "ใบสั่งขาย";
                            break;
                        case 'OWAB' :
                            $DocType = "ใบเบิกสินค้า";
                            break;
                        case 'OWAS' :
                            $DocType = "ใบฝากส่งสินค้า";
                            break;
                    }
                    $time = $resultDetail['OriTime'];
                    if(strlen($time) != 4) {
                        $time = "0".$time;
                    }
                    $nwtime = substr($time,0,2).":".substr($time,2,2);
                    $Tbody .=   "<div class='collapse' id='CollaT".$DocID[$i]."' style='padding-top: 1px;'>".
                                    "<div class='bg-light-secondary ms-2 me-2 p-2' style='border-radius: 0px 0px 10px 10px;'>".
                                        "<div class=''>".
                                            "<span class='fw-bold'>ประเภทใบหยิบ : </span> <span>".$DocType." ".$QQ."</span>".
                                        "</div>".
                                        "<div>".
                                            "<span class='fw-bold'>ชื่อร้านค้า : </span> <span>".$resultDetail['CardCode']." - ".$resultDetail['CardName']."</span>".
                                        "</div>".
                                        "<div>".
                                            "<span class='fw-bold'>วันที่เปิด S/O : </span> <span>".date("d/m/Y",strtotime($resultDetail['OriDate']))."</span>".
                                        "</div>".
                                        "<div>".
                                            "<span class='fw-bold'>เวลาที่เปิด S/O : </span> <span>".$nwtime." น.</span>".
                                        "</div>".
                                        "<div>".
                                            "<span class='fw-bold'>กำหนดส่ง : </span> <span>".date("d/m/Y",strtotime($resultDetail['DocDueDate']))."</span>".
                                        "</div>".
                                    "</div>".
                                "</div>  ".
                            "</td>".
                        "</tr>";
        }
    }
    $arrCol['Tbody'] = $Tbody;
}

if($_GET['a'] == 'CallData2') {
    $cDate = date("Y-m-d");
    $cDatePluss3 = date("Y-m-d",strtotime("+3 days",strtotime($cDate)));
    $cDateReduce1 = date("Y-m-d",strtotime("-1 days",strtotime($cDate)));

    if ($_SESSION['uClass'] == 44 OR $_SESSION['uClass'] == 45 OR $_SESSION['uClass'] == 0){
        $ListShow =  " ";
    }else{
        $ListShow =  " AND T0.UkeyPicker = '".$_SESSION['ukey']."' ";
    }
    $sql = "SELECT T0.ID,T0.SODocEntry AS DocEntry,T0.DocNum,T0.QQst,T0.DocType,T0.DocDate,T0.OriTime,T0.DocDueDate,T0.TeamCode,T0.StatusDoc,T2.NameStatus,T0.TablePacking,
                CASE WHEN (T0.OriDate < '".LastWorkDate($cDate)."' AND T0.TeamCode NOT LIKE 'MT%') OR T0.QQst = 'Y' THEN 'A0'
                    WHEN (T0.OriDate = '".LastWorkDate($cDate)."' AND T0.TimeType = 'AM' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A1'
                    WHEN (T0.OriDate = '".LastWorkDate($cDate)."' AND T0.TimeType = 'PM' AND T0.TeamCode NOT LIKE 'MT%')THEN 'A2'
                    WHEN (T0.OriDate = '".$cDate."'  AND  T0.TimeType = 'AM' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A3'
                    WHEN (T0.TeamCode LIKE 'MT%' AND T0.DocDueDate < '".$cDatePluss3."')  THEN 'A4'
                    WHEN (T0.OriDate = '".$cDate."'  AND  T0.TimeType = 'PM' AND T0.TeamCode NOT LIKE 'MT%') THEN 'B1'
                    ELSE 'B2' END AS PickDay,
                    T1.uName AS PickName, T1.uNickName AS PickNname,
                    CASE WHEN T0.StatusDoc = 5 OR T0.StatusDoc = 6 THEN 0 
                        WHEN T0.StatusDoc < 4 THEN 1
                        WHEN T0.StatusDoc = 4 THEN 2
                        WHEN T0.StatusDoc > 6 THEN 3
                        ELSE 4 END AS RunOrder
            FROM picker_soheader T0
                LEFT JOIN users T1 ON T0.UkeyPicker = T1.uKey
                LEFT JOIN docstatus T2 ON T0.StatusDoc = T2.CodeStatus
            WHERE T0.DocNum != '' AND ((T0.StatusDoc >= 1 AND T0.StatusDoc < 11) ".$ListShow." ) OR (T0.StatusDoc = 0 AND (DATE(T0.TimeCUT1) = '".$cDateReduce1."' OR DATE(T0.TimeCUT1) = '".$cDate."'))
            ORDER BY T0.QQst DESC,RunOrder,PickDay,T0.DocDueDate,T0.TimeType";
    // echo $sql;
    $sqlQRY = MySQLSelectX($sql);
    $cx = 0;
    while($result = mysqli_fetch_array($sqlQRY)) {
        if($result['PickDay'] == 'B1' || $result['PickDay'] == 'B2'){
            $cx++;
            $DocID[$cx] = $result['ID'];
            $DataSO['DocEntry'][$DocID[$cx]] = $result['DocEntry'];
            $DataSO['DocType'][$DocID[$cx]] = $result['DocType'];
            $DataSO['DocNum'][$DocID[$cx]] = $result['DocNum'];
            $DataSO['QQst'][$DocID[$cx]] = $result['QQst'];
            $DataSO['TeamCode'][$DocID[$cx]] = $result['TeamCode'];
            $DataSO['ST'][$DocID[$cx]] = $result['StatusDoc'];
            $DataSO['TxtST'][$DocID[$cx]] = $result['NameStatus'];
            $DataSO['Group'][$DocID[$cx]] = $result['PickDay'];
            $DataSO['DocDue'][$DocID[$cx]] = $result['DocDueDate'];
            $DataSO['Picker'][$DocID[$cx]] = $result['PickNname'];
            $DataSO['Table'][$DocID[$cx]] = $result['TablePacking']; 
        }
    }

    $Tbody = "";
    if ($cx == 0){
        $Tbody = "<tr>
                    <td class='text-center fw-bold'>
                        ไม่มีข้อมูลรายการใบสั่งขาย
                    </td>
                  </tr>";
    }else{
        for ($i=1; $i <= $cx; $i++){
            $text = "";
            switch ($DataSO['ST'][$DocID[$i]]){
                case 0 :
                    $Color = "#f5f5f5"; // เอกสารยกเลิก เทา
                    $text = "text-muted";
                    break;
                case 2 :
                    $Color = "#fff"; // รอหยิบสินค้า ขาว
                    break;
                case 3 :
                    $Color = "#d9edf7"; // กำลังหยิบสินค้า ฟ้า
                    break;
                case 4 :
                    $Color = "#fcf8e3"; // รอตัดสินค้า เหลือง
                    break;
                case 5 :
                    $Color = "#ebd460";//ยืนยันตัดสินค้า เหลืองเข้ม
                    break;
                case 6 :
                    $Color = "#f2dede"; //รอสินค้า/รอแปลงสินค้า, คืนบิล/รอกรอกข้อมูลการจัดส่ง แดง
                    break;
                case 7 :
                case 8 :
                case 9 :
                case 10 :
                    $Color = "#dff0d8";//รอเปิดบิล-กำลังแพ็ค // เขียว
                    break;
            }

            $BilQQ = "";
            if ($DataSO['QQst'][$DocID[$i]] == 'Y'){ $BilQQ = "(บิลด่วน)"; }
            switch ($DataSO['DocType'][$DocID[$i]]){
                case 'ORDR' :
                        $DocType = "";
                        break;
                case 'OWAS' :
                        $DocType = "(ฝาก)</span>";
                        break;
                case 'OWAB' :
                        $DocType = "(เบิก)</span>";
                        break;
            }

            if ($_SESSION['uClass'] == 44 OR $_SESSION['uClass'] == 45 OR $_SESSION['uClass'] == 0){
                $nameShow = $DataSO['Picker'][$DocID[$i]];
            }else{
                $nameShow = "";
            }

            $sqlCHK = "SELECT SUM(P0.RowItem) AS RowItem
                    FROM (
                        SELECT 1 AS RowItem,
                            CASE WHEN T0.Qty = T0.OpenQty THEN 1 ELSE 0 END AS FinishItem
                        FROM  picker_sodetail T0
                        WHERE T0.DocEntry = '".$DataSO['DocEntry'][$DocID[$i]]."' AND T0.DocType = '".$DataSO['DocType'][$DocID[$i]]."'
                    ) P0";
            $resultCHK = MySQLSelect($sqlCHK);

            $Tbody .=   "<tr>".
                            "<td class='pb-0'>".
                                "<div class='ps-2 pe-2 pt-2 border border-1' style='border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px ".$Color."; background-color: ".$Color.";'>".
                                    "<div class='d-flex justify-content-between ".$text."'>".
                                        "<div style='width: 62%'>".
                                            "<span class='fw-bolder'>เลขที่ใบสั่งขาย</span> <a class='fw-bold' href='picklist.php?docety=".$DocID[$i]."'>".$DataSO['DocNum'][$DocID[$i]]."</a>".
                                        "</div>
                                        <div style='width: 8%'>";
                                    if($resultCHK['RowItem'] != "") {
                                        $Tbody .= "<span class='fw-bold'>[".$resultCHK['RowItem']."]</apan>";
                                    }else{
                                        $Tbody .= "<span class='fw-bold'></apan>";
                                    }
                              $Tbody .= "</div>".
                                        "<div class='text-right'style='width: 30%'>".
                                            "<span class='fw-bolder' style='color: #0059b3;'>".$DocType."</span>".
                                            "<span class='text-primary fw-bolder'>".$BilQQ."</span>".
                                        "</div>".
                                    "</div>".
                                "</div>".
                                "<div class='p-2 border border-1' style='border-radius: 0px 0px 10px 10px; box-shadow: 1px 1px ".$Color.";'>".
                                    "<div class='d-flex'>".
                                        "<div class='".$text."' style='width: 25%;'>".
                                            "<span class='fw-bolder'>ฝ่าย</span> <span>".$DataSO['TeamCode'][$DocID[$i]]."</span>&nbsp;&nbsp;".
                                        "</div>".
                                        "<div class='".$text."' style='width: 20%;'>".
                                            "<i class='fas fa-user'></i> <span>".$nameShow."</span>&nbsp;&nbsp;".
                                        "</div>".
                                        "<div class='".$text."' style='width: 20%;'>".
                                            "<i class='fas fa-archive'></i> <span>โต๊ะ ".$DataSO['Table'][$DocID[$i]]."</span>&nbsp;&nbsp;".
                                        "</div>".
                                        "<div class='".$text."' style='width: 35%;'>".
                                            "<i class='fas fa-truck'></i> <span>".date("d/m/Y",strtotime($DataSO['DocDue'][$DocID[$i]]))."</span>".
                                        "</div>".
                                    "</div>".
                                    "<div class='d-flex justify-content-between ".$text."'>".
                                        "<div>".
                                            "<span class='fw-bolder'>สถานะ</span> <span>".$DataSO['TxtST'][$DocID[$i]]."</span>".
                                        "</div>".
                                        "<a class='".$text."' data-bs-toggle='collapse' href='#CollaT".$DocID[$i]."' role='button' aria-expanded='false' aria-controls='CollaT".$DocID[$i]."'>รายละเอียด <i class='fas fa-angle-down'></i></a>".
                                    "</div>".
                                "</div>";
                    $sqlDetail ="SELECT T0.ID,T0.SODocEntry,T0.DocType,T0.QQst,T0.CardCode,T0.CardName,T0.OriDate,T0.OriTime,T0.DocDueDate
                                FROM picker_soheader T0
                                WHERE T0.ID = '".$DocID[$i]."'";
                    $resultDetail = MySQLSelect($sqlDetail);
                    if ($resultDetail['QQst'] == 'Y'){
                        $QQ = "(บิลด่วน)";
                    }else{
                        $QQ = "";
                    }
                    switch ($resultDetail['DocType']){
                        case 'ORDR' :
                            $DocType = "ใบสั่งขาย";
                            break;
                        case 'OWAB' :
                            $DocType = "ใบเบิกสินค้า";
                            break;
                        case 'OWAS' :
                            $DocType = "ใบฝากส่งสินค้า";
                            break;
                    }
                    $time = $resultDetail['OriTime'];
                    if(strlen($time) != 4) {
                        $time = "0".$time;
                    }
                    $nwtime = substr($time,0,2).":".substr($time,2,2);
                    $Tbody .=   "<div class='collapse' id='CollaT".$DocID[$i]."' style='padding-top: 1px;'>".
                                    "<div class='bg-light-secondary ms-2 me-2 p-2' style='border-radius: 0px 0px 10px 10px;'>".
                                        "<div class=''>".
                                            "<span class='fw-bold'>ประเภทใบหยิบ : </span> <span>".$DocType." ".$QQ."</span>".
                                        "</div>".
                                        "<div>".
                                            "<span class='fw-bold'>ชื่อร้านค้า : </span> <span>".$resultDetail['CardCode']." - ".$resultDetail['CardName']."</span>".
                                        "</div>".
                                        "<div>".
                                            "<span class='fw-bold'>วันที่เปิด S/O : </span> <span>".date("d/m/Y",strtotime($resultDetail['OriDate']))."</span>".
                                        "</div>".
                                        "<div>".
                                            "<span class='fw-bold'>เวลาที่เปิด S/O : </span> <span>".$nwtime." น.</span>".
                                        "</div>".
                                        "<div>".
                                            "<span class='fw-bold'>กำหนดส่ง : </span> <span>".date("d/m/Y",strtotime($resultDetail['DocDueDate']))."</span>".
                                        "</div>".
                                    "</div>".
                                "</div>  ".
                            "</td>".
                        "</tr>";
        }
    }
    $arrCol['Tbody'] = $Tbody;
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>