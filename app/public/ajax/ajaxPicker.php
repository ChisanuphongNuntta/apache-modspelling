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
$resultArray = array();
$arrCol = array();
if ($_POST['tableP'] == 'ALL'){
    $data1 = " ";
}else{
    $sql3 = "SELECT IPAddress FROM checkertable WHERE TableID = '".$_POST['tableP']."'";
    $getTB = $getdata->MySQL_SelectX($sql3);
    $DataTable = mysql_fetch_object($getTB);
    $data1 = " AND T1.IPAddress = '".$DataTable->IPAddress."'";
    
}
$SQLToday = date("Y-m-d");
$tomorow =  $SQLToday;
$lastday =  $SQLToday;

$WeekEND1 = 1;
while (($WeekEND1 == 1) || date('N',strtotime($tomorow)) == '7'){
    $tomorow=  date("Y-m-d",strtotime("+1 days",strtotime($tomorow)));
    $WeekEND1 = $getdata->my_sql_show_rows("annual_holiday","Holiday_date = '".$tomorow."'");
}

$WeekEND2 = 1;
while (($WeekEND2 == 1) || date('N',strtotime($lastday)) == '7'){
    $lastday =  date("Y-m-d",strtotime("-1 days",strtotime($lastday)));
    $WeekEND2 = $getdata->my_sql_show_rows("annual_holiday","Holiday_date = '".$lastday."'");
}


$sql1 = "SELECT P0.*, CASE WHEN P0.GroupCH = 'TT' AND ((P0.OriDate < '".$SQLToday."' ) OR (P0.OriDate = '".$SQLToday."' AND P0.OriTime  < 1300)) THEN 'TB11'
                           WHEN P0.GroupCH = 'TT' AND (P0.OriDate >= '".$SQLToday."' AND P0.OriTime  >= 1300) THEN 'TB21'
                           WHEN P0.GroupCH = 'MT' AND (P0.DocDueDate <= '".$tomorow."') THEN 'TB12'
                      ELSE 'TB22' END AS RowGroup,
                      CASE WHEN P0.StatusDoc <= 3 THEN '1WaitPicker'
                           WHEN P0.StatusDoc = 4 THEN '2WaitCoSale1'
                           WHEN P0.StatusDoc = 5 THEN '2WaitCoSale2'
                           WHEN P0.StatusDoc = 6 THEN '2WaitCoSale3'
                           WHEN P0.StatusDoc BETWEEN 7 AND 8 THEN '3WaitOpen'
                           WHEN P0.StatusDoc = 9 THEN '4WaitPack'
                           WHEN P0.StatusDoc = 10 THEN '5Packing'
                      ELSE '6Finish' END AS StatusRow,P1.Status AS FinishGoods

         FROM (SELECT ID,T0.SODocEntry AS DocEntry,T0.DocType,T0.TeamCode,T0.DocNum,T0.OriDate,T0.OriTime,T0.DocDate,T0.DocDueDate,T0.CardCode,T0.CardName,T0.ItemCount,
                      T0.UkeyPicker,T2.name AS PickerName,T2.lastName AS PickerLname,T2.nickname AS PickerNick,T0.StatusDoc,T0.TablePacking,
                      CASE WHEN T0.TeamCode LIKE 'MT%' THEN 'MT' ELSE 'TT' END AS GroupCH,T0.QQst 
               FROM picker_soheader T0
                    JOIN checkertable T1 ON T0.TablePacking = TableID
                    LEFT JOIN user T2 ON T0.UkeyPicker = T2.user_key
               WHERE (T0.StatusDoc BETWEEN 2 AND 11) AND DATE(DateCreate) >= '2021-06-17' ".$data1." 
              ) P0
              LEFT JOIN pack_header P1 ON P0.ID = P1.IDPick
         ORDER BY RowGroup,StatusRow,P0.GroupCH,P0.TeamCode,P0.CardCode";
$getList = $getdata->MySQL_SelectX($sql1);
//echo $sql1;
$tbl['TB1'] = "";
$tbl['TB2'] = "";
$ClassRow = "";
while ($DataList = mysql_fetch_object($getList)){
    switch($DataList->StatusRow) {
        case "2WaitCoSale1": 
            $ClassRow = "class='info text-info'"; 
            $txtShow = "รอ CoSale ตอบ";
            break;
        case "2WaitCoSale2": 
            $ClassRow = "class='info text-info'"; 
            $txtShow = "รอคนเบิกส่งบิล";
            break;
        case "2WaitCoSale3": 
            $ClassRow = "class='info text-info'"; 
            $txtShow = "รอสินค้าเข้า";
            break;
        case "3WaitOpen": 
            $ClassRow = "style='background-color: #72b0f2; color: #fff;'"; 
            $txtShow = "รอเปิดบิล";
            break;
        case "4WaitPack": 
            $ClassRow = "style='background-color: #1e80e8; color: #fff;'"; 
            $txtShow = "รอแพ็คสินค้า";
            break;
        case "5Packing": 
            $ClassRow = "class='success text-success'"; 
            $txtShow = "กำลังแพคสินค้า";
            break;
        case "6Finish":
            if  ($DataList->FinishGoods == 'Y'){
                $ClassRow = "style='background-color: #8bc989; color: #053b03;'"; 
                $txtShow = "สินค้าพร้อมส่ง";
            }else{
                $ClassRow = "class='NotY'";
                $txtShow = "ยังไม่กดยืนยันรายการ"; 
            }
            break;
        default: $ClassRow = NULL; 
            $txtShow = "กำลังเบิกสินค้า";
        break;
    }
   

        if ($DataList->StatusDoc < 9 AND substr($DataList->DocNum,0,2) != 'WO'){
            $DataType = "ORDR";
        }else{
            switch (substr($DataList->DocNum,0,2)){
                case 'SO' :
                    $DataType = "OINV";
                    break;
                case 'SA' :
                case 'SB' :
                    $DataType = "ODLN";
                    break;
                default :
                    $DataType = "OWBS";
                break;
            }
        }
    if ($DataList->QQst == 'Y'){
        $QQ = " <span style='color:red;font-weight: bold;'> [บิลด่วน] </span>  ";
    }else{
        $QQ = " ";
    }


    $tbl[substr($DataList->RowGroup,0,3)] .= " <tr ".$ClassRow.">
                                                    <td class='text-center'><input type='checkbox' /></td>
                                                    <td class='text-center'>".$DataList->TeamCode."</td>
                                                    <td class='text-center'><span onclick=\"CallModal('".$DataType."','".$DataList->DocEntry."')\" class='view_data'>".$DataList->DocNum."</td>
                                                    <td>".$DataList->CardCode." ".$DataList->CardName.$QQ."</td>
                                                    <td class='text-center'>".$DataList->DocDate."</td>
                                                    <td class='text-center'>".$DataList->DocDueDate."</td>
                                                    <td class='text-right'>".$DataList->ItemCount."</td>
                                                    <td>".$DataList->PickerName." ".$DataList->PickerLname." (".$DataList->PickerNick.")</td>
                                                    <td class='text-center'>".$txtShow."</td>
                                                    <td class='text-center'> โต๊ะที่ ".$DataList->TablePacking."</td>
                                                </tr>";
}
$arrCol['Table1'] = $tbl['TB1'];
$arrCol['Table2'] = $tbl['TB2'];



array_push($resultArray,$arrCol);
echo json_encode($resultArray);


