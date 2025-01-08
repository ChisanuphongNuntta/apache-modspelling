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
$DocEntryList = "('";
$SQLToday = date("Y-m-d");
$Date3 =  date("Y-m-d",strtotime("+3 days",strtotime($SQLToday)));
$dateOnStart = "2021-06-17";
$WeekEND1 = 1;
$tomorow = $SQLToday;
//echo $tomorow;
while (($WeekEND1 == 1) || date('N',strtotime($tomorow)) == '7'){
    $tomorow =  date("Y-m-d",strtotime("+1 days",strtotime($tomorow)));
    $WeekEND1 = $getdata->my_sql_show_rows("annual_holiday","Holiday_date = '".$tomorow."'");
    //echo "SELECT * FROM annual_holiday WHERE Holiday_date = '".$tomorow."'/".$WeekEND1;

}

$sqlList = "SELECT  SODocEntry AS DocEntry,DocType,DocDate FROM picker_soheader WHERE DocDate >= DATE_SUB(CURDATE(),INTERVAL 1 MONTH) AND StatusDoc != 1 ";
$getFinis = $getdata->MySQL_SelectX($sqlList);
$ORDR = " AND (T0.DocEntry NOT IN ('";
$RDR1 = 0;
$OWAS = " AND (T0.DocEntry NOT IN ('";
$WAS1 = 0;
while ($DocEntryList = mysql_fetch_object($getFinis)){
    if ($DocEntryList->DocType == 'ORDR'){
        $ORDR .= $DocEntryList->DocEntry."','";
        $RDR1++;
    }else{
        $OWAS .= $DocEntryList->DocEntry."','";
        $WAS1++;
    }
}
if ($RDR1 !=0) {
    $ORDR = substr($ORDR,0,($ORDR-2))."))";
}else{
    $ORDR = "";
}
if ($WAS1 !=0) {
    $OWAS = substr($OWAS,0,($OWAS-2))."))";
}else{
    $OWAS = "";
}
//$ORDR = " AND (T0.DocNum IN ('640600549','640600550','640600523','640600535','640600530','640600537','640600529','640600531','640600532','640600133'))";//08/06/2021
//$ORDR = " AND (T0.DocNum IN ('640600657','640600651','640600648','640600649','640600654','640600655','640600636','640600664','640600662','640600666','640600674','640600673','640600675','640600628'))";//09/06/2021
/*$ORDR = " AND (T0.DocNum IN ('640600831','640600825','640600848','640600842','64060043','640600847','640600845','640600835','640600836','640600839','640600838','640600837','640600823','640600824','640600826','640600832','640600829','640600828',
'640600834','640600821','640600822','640600819','640600820','210610052','210610055','210610053'))";
*/
//$ORDR =  "AND (T0.DocEntry ('0000')";
//$ORDR = " AND (T0.DocEntry IN ('185224','185257','185467','185861','185873')) ";
//$FixBill = " OR (T0.DocNum IN ('640601385' , '640601470' )) ";
//$FixBill = "T0.DocNum IN (640601021 , 640601010 , 640601014 , 640601011 , 640601019 , 640601024 , 640601029 , 640601027 , 640601032 , 640601335 , 640601359 , 640601358 , 640601355 , 640601360 , 640601357 , 640601356 , 640601346 , 640601347 , 640601344 , 640601345 , 640601352 , 640601353 , 640601354 , 640601350 , 640601351 , 640601348 , 640601349 , 640601369 , 640601325 , 640601026 , 640601040 , 640601043 , 640601042 , 640601045 , 640601044 , 640601041 , 640601036 , 640601038 , 640601034 , 640601035 , 640601030 , 640601033 , 640601334 , 640601337 , 640601336 , 640601339 , 640601338 , 640601341 , 640601340 , 640601343 , 640601342 , 640601442 , 640601441 , 640601443 , 640601371 , 640601375 , 640601370 , 640601199 , 640601468 , 640601466 , 640601465 , 640601464 , 640601463 , 640601462 , 640601461 , 640601460 , 640601459 , 640601458 , 640601457 , 640601455 , 640601452 , 640601450 , 640601449 , 640601448 , 640601447 , 640601581)";
//$FixBill = "AND T0.DocNum IN (640601306)";
//$FixBill = "AND T0.DocNum IN ('SO-640800225')";
// (T5.[WhsCode] != 'KB1' AND T5.[WhsCode] != 'KB1.1' AND T5.[WhsCode] != 'KB7' AND T5.[WhsCode] != 'P01' AND T5.[WhsCode] != 'P02' AND T5.[WhsCode] NOT LIKE  'WP%' AND T5.[WhsCode] NOT LIKE  'WM%' AND T5.[WhsCode] != 'TT' AND T5.[WhsCode] != 'TT2.1' AND T5.[WhsCode] != 'WA26.1' AND T5.[WhsCode] != 'PM-KBI' AND T5.[WhsCode] != 'RD3' AND T5.[WhsCode] != 'PM-HR' AND T5.[WhsCode] != 'PM-TT' AND T5.[WhsCode] != 'PMTT-KBI' AND T5.[WhsCode] != 'OSP' AND T5.[WhsCode] != 'PMTT-KBI' AND T5.[WhsCode] != 'PM-OUL')
$MsSQL = "SELECT DISTINCT '".$_SESSION['name']." ".$_SESSION['lname']."' AS 'Query Name', '".$_SERVER['REMOTE_ADDR']."' AS 'Query IP', T0.[DocEntry],T0.[DocDate],T0.[DocTime],T0.[DocDueDate],T0.[CreateDate],T1.[BeginStr],T0.[DocNum],T0.DocStatus,T0.[CardCode],T0.[CardName],T0.[OwnerCode],
                 T4.U_Dim1 AS 'xCh',
                 T3.[USER_CODE],T4.[SlpName],T0.[AtcEntry],T0.[Comments],T0.[SlpCode],
                (SELECT COUNT(P0.LineNum) FROM RDR1 P0 WHERE P0.DocEntry = T0.DocEntry) AS xDetail,
                CASE WHEN T5.TrgetEntry IS NULL AND T0.DocStatus = 'C' THEN 'C' ELSE 'OK' END AS  STSO,
                CASE WHEN UPPER(LEFT(T0.[Comments],2)) = 'QQ' THEN 'Y' ELSE 'N' END AS QQst
          FROM  ORDR T0 
                LEFT JOIN NNM1 T1 ON T0.[Series] = T1.[Series] 
                LEFT JOIN OUSR T3 ON T0.[UserSign] = T3.[USERID] 
                LEFT JOIN OSLP T4 ON T0.[SlpCode] = T4.[SlpCode] 
                LEFT JOIN RDR1 T5 ON T0.[DocEntry] = T5.[DocEntry] 
          WHERE T0.CreateDate >= '".$dateOnStart."' AND (T0.CreateDate >= DATEADD(month,-1,GETDATE())) AND ( 
            (T5.[WhsCode] IN ('KSY','KB4','MT','MT2','TT-C','OUL','NST','PM','PM-KSY','PMTT-KSY')) AND
                T0.[SlpCode] NOT IN (20,123,124,125,126))  ".$ORDR." ".$FixBill."  
          ORDER BY xCh DESC,T0.DocEntry";
//echo  $MsSQL."/n"
     

//  ยัดมือ
    //  $FixBill = "T0.DocNum IN (211210059)";
    //  $MsSQL = "SELECT DISTINCT T0.[DocEntry],T0.[DocDate],T0.[DocTime],T0.[DocDueDate],T0.[CreateDate],T1.[BeginStr],T0.[DocNum],T0.DocStatus,T0.[CardCode],T0.[CardName],T0.[OwnerCode],
    //                   T4.U_Dim1 AS 'xCh',
    //                  T3.[USER_CODE],T4.[SlpName],T0.[AtcEntry],T0.[Comments],
    //                 (SELECT COUNT(P0.LineNum) FROM RDR1 P0 WHERE P0.DocEntry = T0.DocEntry) AS xDetail,
    //                 CASE WHEN T5.TrgetEntry IS NULL AND T0.DocStatus = 'C' THEN 'C' ELSE 'OK' END AS  STSO,
    //                  CASE WHEN UPPER(LEFT(T0.[Comments],2)) = 'QQ' THEN 'Y' ELSE 'N' END AS QQst
    //            FROM  ORDR T0 
    //                  LEFT JOIN NNM1 T1 ON T0.[Series] = T1.[Series] 
    //                  LEFT JOIN OUSR T3 ON T0.[UserSign] = T3.[USERID] 
    //                  LEFT JOIN OSLP T4 ON T0.[SlpCode] = T4.[SlpCode] 
    //                  LEFT JOIN RDR1 T5 ON T0.[DocEntry] = T5.[DocEntry] 
    //           WHERE  ".$FixBill."  
    //            ORDER BY xCh DESC,T0.DocEntry";


$sapfqry = odbc_exec($sapconn,$MsSQL);
$xCount=0;
while ($DataORDR = odbc_fetch_array($sapfqry)){
    $xCount++;
    $time = $DataORDR['DocTime'];
    if ($time > 1300) {
        $TimeType = 'PM';   
    }else{
        $TimeType = 'AM';   
    }
    if ($DataORDR['STSO'] == "C"){
        $DocST = 0;
    }else{
        $DocST = 2;
    }
    
    // insert ordr
    $chkData = $getdata->my_sql_show_rows("picker_soheader","SODocEntry = '".$DataORDR['DocEntry']."' AND DocType = 'ORDR'");
    if ($chkData == 0){
        $InsertSET = "SODocEntry = '".$DataORDR['DocEntry']."',
                    DocNum =  '".$DataORDR['BeginStr'].$DataORDR['DocNum']."',
                    QQst = '".$DataORDR['QQst']."',
                    DocType = 'ORDR',
                    DocDate = '".date("Y-m-d",strtotime($DataORDR['DocDate']))."',
                    OriDate = '".date("Y-m-d",strtotime($DataORDR['CreateDate']))."',
                    OriTime = '".$DataORDR['DocTime']."',
                    DocDueDate = '".date("Y-m-d",strtotime($DataORDR['DocDueDate']))."',
                    CardCode = '".$DataORDR['CardCode']."',
                    CardName = '".conutf8($DataORDR['CardName'])."',
                    DateCreate = NOW(),
                    TimeType = '".$TimeType."',
                    ItemCount = '".$DataORDR['xDetail']."',
                    TeamCode = '".$DataORDR['xCh']."',
                    SlpCode = '".$DataORDR['SlpCode']."',
                    StatusDoc = '".$DocST."'";
                    //echo $InsertSET."<br>";
        $getdata->my_sql_insert("picker_soheader",$InsertSET);
    }else{
        $getdata->my_sql_update("picker_soheader","UkeyPicker = '',Status = '".$DocST."'","SODocEntry = '".$DataORDR['DocEntry']."' AND DocType = 'ORDR'");
    }
}

$MySQL = "SELECT T0.DocEntry,T0.DocNum,T0.DateCreate,T0.TimeContrac,CusCode,CusName,T0.TypeOrder,
                 (SELECT COUNT(P0.ID) FROM was1 P0 WHERE P0.DocEntry = T0.DocEntry AND StatusDoc !=0) AS ItemCount,
                 T0.TeamCode,
                 CASE WHEN UPPER(SUBSTR(T0.Remark,1,2)) = 'QQ' THEN 'Y' ELSE 'N' END AS QQst
          FROM owas T0
          WHERE T0.StatusDoc = 2 AND T0.DateCreate >= DATE_SUB(CURDATE(),INTERVAL 1 MONTH) AND T0.TypeOrder IN ('B','S') ".$OWAS;
        
$getOWAS = $getdata->MySQL_SelectX($MySQL);
while ($DataOWAS = mysql_fetch_object($getOWAS)){
    $xCount++;
    $DocDate = date("Y-m-d",strtotime($DataOWAS->DateCreate));
    $time = intval(date("Hi",strtotime($DataOWAS->DateCreate)));
    if ($time > 1300) {
        $DocTime = 'PM';   
    }else{
        $DocTime = 'AM';   
    }
    if ($DataOWAS->TypeOrder == 'B'){
        $orderType = 'OWAB';
    }else{
        $orderType = 'OWAS';
    }
    $chkData2 = $getdata->my_sql_show_rows("picker_soheader","SODocEntry = '".$DataOWAS->DocEntry."' AND DocType = '".$orderType."'");
    if ($chkData2 == 0){
        $InsertSET = "SODocEntry = '".$DataOWAS->DocEntry."',
                    DocNum =  '".$DataOWAS->DocNum."',
                    QQst = '".$DataOWAS->QQst."',
                    DocType = '".$orderType."',
                    DocDate = '".$DocDate."',
                    OriDate = '".$DocDate."',
                    OriTime = '".$time."',
                    DocDueDate = '".date("Y-m-d",strtotime($DataOWAS->TimeContrac))."',
                    CardCode = '".$DataOWAS->CusCode."',
                    CardName = '".$DataOWAS->CusName."',
                    DateCreate = NOW(),
                    TimeType = '".$DocTime."',
                    ItemCount = '".$DataOWAS->ItemCount."',
                    TeamCode = '".$DataOWAS->TeamCode."',
                    StatusDoc = 2";
                    //echo $InsertSET;
        $getdata->my_sql_insert("picker_soheader",$InsertSET);
    }else{
        $getdata->my_sql_update("picker_soheader","UkeyPicker = '',Status = 2","SODocEntry = '".$DataOWAS->DocEntry."' AND DocType = '".$orderType."'");
    }
}

$sqlNewDocEntry = "SELECT T0.ID,T0.SODocEntry,T0.DocDate,T0.OriDate,T0.OriTime,T0.TimeType,T0.DocDueDate, 
                          CASE WHEN (T0.OriDate < '".LastWorkDate($SQLToday)."' AND T0.TeamCode NOT LIKE 'MT%') OR T0.QQst = 'Y' THEN 'A0'
                               WHEN (T0.OriDate = '".LastWorkDate($SQLToday)."' AND T0.OriTime <= '1300' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A1'
                               WHEN (T0.OriDate = '".LastWorkDate($SQLToday)."' AND T0.OriTime > '1300' AND T0.TeamCode NOT LIKE 'MT%')THEN 'A2'
                               WHEN (T0.OriDate = '".$SQLToday."'  AND  T0.OriTime < '1300' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A3'
                               WHEN (T0.TeamCode LIKE 'MT%' AND T0.DocDueDate <= '".$Date3."')  THEN 'A4'
                               WHEN (T0.OriDate = '".$SQLToday."'  AND  T0.OriTime > '1300' AND T0.TeamCode NOT LIKE 'MT%') THEN 'B1'
                               ELSE 'B2' END AS PickDay,
                          T0.StatusDoc
                  FROM picker_soheader T0 
                  WHERE T0.UkeyPicker = '' AND T0.StatusDoc <= 7 
                  ORDER BY PickDay,T0.DocDate,T0.OriTime";
//echo $sqlNewDocEntry;              

$getNewDoc = $getdata->MySQL_SelectX($sqlNewDocEntry);
while ($DataNew = mysql_fetch_object($getNewDoc)){
    switch($DataNew->PickDay){
        case 'A0' :// แพคเช้าวันนี้
        case 'A1' :
        case 'A2' :
        case 'A3' :
            // กลุ่ม TT
            $sqlItem = "SELECT X0.user_key AS UserKey,X0.ItemCount,X0.BillCount
                        FROM (SELECT T0.user_key,
                                     (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE P0.DatePick = DATE(NOW()) AND P0.UkeyPicker = T0.user_key AND P0.TeamCode NOT LIKE 'MT%') AS ItemCount,
                                     (SELECT COUNT(P1.SODocEntry) FROM picker_soheader P1 WHERE P1.DatePick = DATE(NOW()) AND P1.UkeyPicker = T0.user_key AND P1.TeamCode NOT LIKE 'MT%') AS BillCount        
                              FROM picker_online T0
                              WHERE DATE(T0.DateOnline) = DATE(NOW()) AND T0.Status = 1 
                              ) X0
                        ORDER BY X0.ItemCount,X0.BillCount 
                        LIMIT 1";
            $getUserMin = $getdata->MySQL_SelectX($sqlItem);
            $UserMin = mysql_fetch_object($getUserMin);

            /*$sqlTbl = "SELECT X0.TableFix as tblPack,X0.ItemCount,X0.BillCount
                       FROM (SELECT T0.TableFix,
                                    (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE P0.PackDate = '".$tomorow."' AND P0.TablePacking = T0.TableFix AND P0.TeamCode NOT LIKE 'MT%' ) AS ItemCount,
                                    (SELECT COUNT(P1.ItemCount) FROM picker_soheader P1 WHERE P1.PackDate = '".$tomorow."' AND P1.TablePacking = T0.TableFix AND P1.TeamCode NOT LIKE 'MT%' ) AS BillCount 
                             FROM tableOnline T0
                             WHERE DATE(T0.DateOnline) = DATE(NOW())
                             ) X0
                        ORDER BY X0.ItemCount,X0.BillCount 
                        LIMIT 1";*/
            switch($DataNew->PickDay){
                case 'A0' :// บิลเก่า
                case 'A1' :// บิลค้างเช้าเมื่อวาน
                case 'A2' :// บิลบ่ายเมื่อวาน
                    $sqlTbl = "SELECT X0.TableFix as tblPack,X0.ItemCount,X0.BillCount
                               FROM (SELECT T0.TableFix,
                                                (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE P0.PackDate = DATE(NOW()) AND P0.TablePacking = T0.TableFix AND P0.TeamCode NOT LIKE 'MT%' ) AS ItemCount,
                                                (SELECT COUNT(P1.ItemCount) FROM picker_soheader P1 WHERE P1.PackDate = DATE(NOW()) AND P1.TablePacking = T0.TableFix AND P1.TeamCode NOT LIKE 'MT%' ) AS BillCount 
                                        FROM tableOnline T0
                                        WHERE DATE(T0.DateOnline) = DATE(NOW()) AND T0.Status = 1
                                        ) X0
                               ORDER BY X0.ItemCount,X0.BillCount 
                               LIMIT 1";
                    $timePick = 'AM';
                    $datePack = $SQLToday;//today
                    $timePack = 'PM';
                    break;
                case 'A3' :// บิลเช้าวันนี้
                    $sqlTbl = "SELECT X0.TableFix as tblPack,X0.ItemCount,X0.BillCount
                               FROM (SELECT T0.TableFix,
                                                (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE P0.PackDate = '".$tomorow."' AND P0.TablePacking = T0.TableFix AND P0.TeamCode NOT LIKE 'MT%' ) AS ItemCount,
                                                (SELECT COUNT(P1.ItemCount) FROM picker_soheader P1 WHERE P1.PackDate = '".$tomorow."' AND P1.TablePacking = T0.TableFix AND P1.TeamCode NOT LIKE 'MT%' ) AS BillCount 
                                        FROM tableOnline T0
                                        WHERE DATE(T0.DateOnline) = DATE(NOW()) AND T0.Status = 1
                                        ) X0
                               ORDER BY X0.ItemCount,X0.BillCount 
                               LIMIT 1";
                    $timePick = 'PM';
                    $datePack = $tomorow; 
                    $timePack = 'AM';
                    break;
            }
            $getTblMin = $getdata->MySQL_SelectX($sqlTbl);
            $TblMin = mysql_fetch_object($getTblMin);
            //echo $DataNew->ID."/".$SQLToday."<br>";
            $updateAdd = "UkeyPicker = '".$UserMin->UserKey."',DatePick = '".$SQLToday."',TimePack = '".$timePick."',LastUpdate = NOW(),TablePacking = '".$TblMin->tblPack."',PackDate = '".$datePack."',TimePack = '".$timePack."'";
            //echo "UPDATE picker_soheader SET ".$updateAdd."ID = '".$DataNew->ID."'"."\n";
            $getdata->my_sql_update("picker_soheader",$updateAdd,"ID = '".$DataNew->ID."'");
            break;
        case 'A4' :// MT อีก3 วันส่ง
            $sqlItem = "SELECT X0.user_key AS UserKey,X0.ItemCount,X0.BillCount
                        FROM (SELECT T0.user_key,
                                     (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE (MONTH(P0.OriDate) = MONTH(NOW()) AND YEAR(P0.OriDate) = YEAR(NOW()))  AND P0.UkeyPicker = T0.user_key AND P0.TeamCode LIKE 'MT%' AND P0.StatusDoc >=2) AS ItemCount,
                                     (SELECT COUNT(P1.SODocEntry) FROM picker_soheader P1 WHERE (MONTH(P1.OriDate) = MONTH(NOW()) AND YEAR(P1.OriDate) = YEAR(NOW()))  AND P1.UkeyPicker = T0.user_key AND P1.TeamCode LIKE 'MT%' AND P1.StatusDoc >=2) AS BillCount        
                              FROM picker_online T0
                              WHERE DATE(T0.DateOnline) = DATE(NOW()) AND T0.Status = 1
                              ) X0
                        ORDER BY X0.ItemCount,X0.BillCount 
                        LIMIT 1";
            //echo $sqlItem;
            $getUserMin = $getdata->MySQL_SelectX($sqlItem);
            $UserMin = mysql_fetch_object($getUserMin);

            $sqlTbl = "SELECT X0.TableFix as tblPack,X0.ItemCount,X0.BillCount
                       FROM (SELECT T0.TableFix,
                                    (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE (MONTH(P0.OriDate) = MONTH(NOW()) AND YEAR(P0.OriDate) = YEAR(NOW())) AND P0.TablePacking = T0.TableFix AND P0.TeamCode LIKE 'MT%' AND P0.StatusDoc >=2) AS ItemCount,
                                    (SELECT COUNT(P1.ItemCount) FROM picker_soheader P1 WHERE (MONTH(P1.OriDate) = MONTH(NOW()) AND YEAR(P1.OriDate) = YEAR(NOW())) AND P1.TablePacking = T0.TableFix AND P1.TeamCode LIKE 'MT%' AND P1.StatusDoc >=2) AS BillCount 
                            FROM tableOnline T0
                            WHERE DATE(T0.DateOnline) = DATE(NOW()) AND T0.Status = 1
                            ) X0
                       ORDER BY X0.ItemCount,X0.BillCount 
                       LIMIT 1";
            //echo $sqlTbl;

            $getTblMin = $getdata->MySQL_SelectX($sqlTbl);
            $TblMin = mysql_fetch_object($getTblMin);
            $updateAdd = "UkeyPicker = '".$UserMin->UserKey."',DatePick = '".$SQLToday."',TimePack = 'AM',LastUpdate = NOW(),TablePacking = '".$TblMin->tblPack."',PackDate = '".$SQLToday."',TimePack = 'PM'";
            $getdata->my_sql_update("picker_soheader",$updateAdd,"ID = '".$DataNew->ID."'");
            break;
        case 'B1' : // TT บ่าย
            $sqlItem = "SELECT X0.user_key AS UserKey,X0.ItemCount,X0.BillCount
                        FROM (SELECT T0.user_key,
                                    (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE  P0.DatePick = '".$tomorow."' AND P0.UkeyPicker = T0.user_key AND P0.TeamCode NOT LIKE 'MT%') AS ItemCount,
                                    (SELECT COUNT(P1.SODocEntry) FROM picker_soheader P1 WHERE P1.DatePick = '".$tomorow."'  AND P1.UkeyPicker = T0.user_key AND P1.TeamCode NOT LIKE 'MT%') AS BillCount        
                              FROM picker_online T0
                              WHERE DATE(T0.DateOnline) = DATE(NOW()) AND T0.Status = 1
                              ) X0
                        ORDER BY X0.ItemCount,X0.BillCount 
                        LIMIT 1";
                        //echo $sqlItem;
            $getUserMin = $getdata->MySQL_SelectX($sqlItem);
            $UserMin = mysql_fetch_object($getUserMin);

            $sqlTbl = "SELECT X0.TableFix as tblPack,X0.ItemCount,X0.BillCount
                       FROM (SELECT T0.TableFix,
                                    (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE P0.PackDate = '".$tomorow."' AND P0.TablePacking = T0.TableFix AND P0.TeamCode NOT LIKE 'MT%' ) AS ItemCount,
                                    (SELECT COUNT(P1.ItemCount) FROM picker_soheader P1 WHERE P1.PackDate = '".$tomorow."' AND P1.TablePacking = T0.TableFix AND P1.TeamCode NOT LIKE 'MT%' ) AS BillCount 
                             FROM tableOnline T0
                             WHERE DATE(T0.DateOnline) = DATE(NOW()) AND T0.Status = 1 
                             ) X0
                        ORDER BY X0.ItemCount,X0.BillCount 
                        LIMIT 1";
            $getTblMin = $getdata->MySQL_SelectX($sqlTbl);
            $TblMin = mysql_fetch_object($getTblMin);
            $updateAdd = "UkeyPicker = '".$UserMin->UserKey."',DatePick = '".$tomorow."',TimePack = 'AM',LastUpdate = NOW(),TablePacking = '".$TblMin->tblPack."',PackDate = '".$tomorow."',TimePack = 'PM'";
            $getdata->my_sql_update("picker_soheader",$updateAdd,"ID = '".$DataNew->ID."'");
            break;
        case 'B2' : //MT ล่วงหน้า
            $sqlItem = "SELECT X0.user_key AS UserKey,X0.ItemCount,X0.BillCount
                        FROM (SELECT T0.user_key,
                                    (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE  (MONTH(P0.OriDate) = MONTH(NOW())) AND P0.UkeyPicker = T0.user_key AND P0.TeamCode LIKE 'MT%' AND P0.StatusDoc >=2) AS ItemCount,
                                    (SELECT COUNT(P1.SODocEntry) FROM picker_soheader P1 WHERE (MONTH(P1.OriDate) = MONTH(NOW()))  AND P1.UkeyPicker = T0.user_key AND P1.TeamCode LIKE 'MT%' AND P1.StatusDoc >=2) AS BillCount        
                            FROM user T0
                                JOIN picker_online T1 ON T0.user_key = T1.user_key
                            WHERE DATE(T1.DateOnline) = DATE(NOW()) AND T1.Status = 1 
                            ) X0
                        ORDER BY X0.ItemCount,X0.BillCount 
                        LIMIT 1";
            //echo $sqlItem."\n";
            $getUserMin = $getdata->MySQL_SelectX($sqlItem);
            $UserMin = mysql_fetch_object($getUserMin);
            $sqlTbl = "SELECT X0.TableFix as tblPack,X0.ItemCount,X0.BillCount
                       FROM (SELECT T0.TableFix,
                                    (SELECT SUM(P0.ItemCount) FROM picker_soheader P0 WHERE (MONTH(P0.OriDate) = MONTH(NOW())) AND P0.TablePacking = T0.TableFix AND P0.TeamCode LIKE 'MT%' AND P0.StatusDoc >=2) AS ItemCount,
                                    (SELECT COUNT(P1.ItemCount) FROM picker_soheader P1 WHERE (MONTH(P1.OriDate) = MONTH(NOW())) AND P1.TablePacking = T0.TableFix AND P1.TeamCode LIKE 'MT%' AND P1.StatusDoc >=2) AS BillCount 
                            FROM tableOnline T0
                            WHERE DATE(T0.DateOnline) = DATE(NOW()) AND T0.Status = 1 
                            ) X0
                       ORDER BY X0.ItemCount,X0.BillCount 
                       LIMIT 1";
            $getTblMin = $getdata->MySQL_SelectX($sqlTbl);
            $TblMin = mysql_fetch_object($getTblMin);
            
            
            $DocDueDate = $DataNew->DocDueDate;

            $WeekEND2 = 1;
            $Pick3Date =  date("Y-m-d",strtotime("-2 days",strtotime($DocDueDate)));
            $x=0;
            while (($WeekEND2 == 1) || date('N',strtotime($Pick3Date)) == '7'){
                $Pick3Date =  date("Y-m-d",strtotime("-1 days",strtotime($Pick3Date)));
                $WeekEND2=$getdata->my_sql_show_rows("annual_holiday","Holiday_date = '".$Pick3Date."'");
            }


            $WeekEND3 = 1;
            $Pack1Date = $DocDueDate;
            while (($WeekEND3 == 1) || date('N',strtotime($Pack1Date)) == '7'){
                $Pack1Date =  date("Y-m-d",strtotime("-1 days",strtotime($Pack1Date)));
                $WeekEND3 = $getdata->my_sql_show_rows("annual_holiday","Holiday_date = '".$Pack1Date."'");
                $x++;
            }


            $updateAdd = "UkeyPicker = '".$UserMin->UserKey."',DatePick = '".$Pick3Date."',TimePack = 'AM',LastUpdate = NOW(),TablePacking = '".$TblMin->tblPack."',PackDate = '".$Pack1Date."',TimePack = 'AM'";
            $getdata->my_sql_update("picker_soheader",$updateAdd,"ID = '".$DataNew->ID."'");
            break;
    }//Cloas TableGroup 
}//Close Main While
$sqlListSO = "SELECT T0.SODocEntry AS DocEntry,T0.DocNum,T0.QQst,T0.DocType,T0.DocDate,T0.OriTime,T0.DocDueDate,T0.CardCode,T0.CardName,T0.TeamCode,T0.ItemCount,T0.UkeyPicker,T0.TablePacking,T0.StatusDoc,
                     CASE WHEN (T0.OriDate < '".LastWorkDate($SQLToday)."' AND T0.TeamCode NOT LIKE 'MT%') OR T0.QQst = 'Y' THEN 'A0'
                          WHEN (T0.OriDate = '".LastWorkDate($SQLToday)."' AND T0.TimeType = 'AM' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A1'
                          WHEN (T0.OriDate = '".LastWorkDate($SQLToday)."' AND T0.TimeType = 'PM' AND T0.TeamCode NOT LIKE 'MT%')THEN 'A2'
                          WHEN (T0.OriDate = '".$SQLToday."'  AND  T0.TimeType = 'AM' AND T0.TeamCode NOT LIKE 'MT%') THEN 'A3'
                          WHEN (T0.TeamCode LIKE 'MT%' AND T0.DocDueDate <= '".$Date3."')  THEN 'A4'
                          WHEN (T0.OriDate = '".$SQLToday."'  AND  T0.TimeType = 'PM' AND T0.TeamCode NOT LIKE 'MT%') THEN 'B1'
                          ELSE 'B2' END AS PickDay,
                          T1.name AS PickName,T1.nickname AS PickNname
              FROM picker_soheader T0
                   LEFT JOIN user T1 ON T0.UkeyPicker = T1.user_key
              WHERE (T0.StatusDoc >= 1 AND T0.StatusDoc < 7) AND T0.DocDate >= '2021-06-01'
              ORDER BY T0.QQst DESC,PickDay,T0.OriDate,T0.OriTime";
//echo $sqlListSO;
$getSOList = $getdata->MySQL_SelectX($sqlListSO);
while ($SOList = mysql_fetch_object($getSOList)){
    if (substr($SOList->PickDay,0,1) == 'A') {
        $Tbl = 1;
    }else{
        $Tbl = 2;
    }
    if ($SOList->QQst == 'Y'){
        $txtQQ = "<span style='font-weight:bold;color:red'> [บิลด่วน]</span>";
    }else{
        $txtQQ = "";
    }
    switch ($SOList->StatusDoc){
        case '0' : 
            $txtStatus = "เอกสารยกเลิก";
            break;
        case '1' :
            $txtStatus = "เอกสารเปิดใหม่".$txtQQ;
            break;
        case '2' :
            $txtStatus = "รอหยิบสินค้า".$txtQQ;
            break;
        case '3' :
            $txtStatus = "กำลังหยิบสินค้า".$txtQQ;
            break;
        case '4' :
            $txtStatus = "รอตัดสินค้า".$txtQQ;
            break;
        case '5' :
            $txtStatus = "รอสินค้า/แปลงสินค้า".$txtQQ;
            break;
        case '6' :
            $txtStatus = "ตัดสินค้าเรียบร้อย".$txtQQ;
            break;
        case '7' :
            $txtStatus = "เอกสารพร้อมเปิดบิล".$txtQQ;
        break;

    }
    $time = $SOList->OriTime;
    if(strlen($time) != 4) {
        $time = "0".$time;
    }
    $nwtime = substr($time,0,2).":".substr($time,2,2);
    $DocTimeX = $nwtime;
    
    $output[$Tbl] .= "
                        <tr class='txtRow'>
                            <td class='text-center'><span id='Doc_".$SOList->DocEntry."' style='border:0; background-color:transparent;' class='view_data' onclick=\"CallModal('ORDR','".$SOList->DocEntry."')\" >".$SOList->DocNum."</span></td>
                            <td class='text-center'> ".date("d/m/Y",strtotime($SOList->DocDate))." </td>
                            <td class='text-center'> ".$DocTimeX." น. </td>
                            <td class='text-center'> ".date("d/m/Y",strtotime($SOList->DocDueDate))." </td>
                            <td class='text-left'> ".$SOList->CardCode." ".$SOList->CardName." </td>
                            <td class='text-center'> ".$SOList->TeamCode." </td>
                            <td class='text-center'> ".number_format($SOList->ItemCount)." </td>
                            <td class='text-center'> ".$txtStatus." </td>
                            <td class='text-left'> ".$SOList->PickName."(".$SOList->PickNname.") </td>
                            <td class='text-left'> TB-".$SOList->TablePacking." </td>
                        </tr>
                    ";  
}

$arrCol['Table1'] = $output[1];
$arrCol['Table2'] = $output[2];
$arrCol['Date'] = inwmount(date("Y-m-d"));
$arrCol['Time'] = date("H:i:s");

array_push($resultArray,$arrCol);
echo json_encode($resultArray);


