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
$x=0;
$y=0;
$z=0;
$Date3 =  date("Y-m-d",strtotime("+3 days",strtotime($SQLToday)));

$sqlRead = "SELECT CASE WHEN BillType = 'OINV' THEN 13 ELSE 15 END AS tblData ,BillEntry FROM shipping_header ";
$getShip = $getdata->MySQL_SelectX($sqlRead);
while ($DataSHIP = mysql_fetch_object($getShip)){
        $chkOIDN  = "SELECT  DocEntry  FROM RDR1 WHERE TrgetEntry = '".$DataSHIP->BillEntry."' AND TargetType = '".$DataSHIP->tblData."'";
        $sapfqry = odbc_exec($sapconn,$chkOIDN);
        $DataORDR = odbc_fetch_array($sapfqry);
        $chkrow = $getdata->my_sql_show_rows("picker_soheader","SODocEntry = '".$DataORDR['DocEntry']."'");
        //echo $chkrow;
        if ($chkrow == 0){
            $MsSQL = "SELECT DISTINCT T0.[DocEntry],T0.[DocDate],T0.[DocTime],T0.[DocDueDate],T0.[CreateDate],T1.[BeginStr],T0.[DocNum],T0.DocStatus,T0.[CardCode],T0.[CardName],T0.[OwnerCode],
                                CASE WHEN T4.U_Dim1 LIKE 'TT%' THEN 'TT' ELSE T4.U_Dim1 END AS 'xCh',
                                T3.[USER_CODE],T4.[SlpName],T0.[AtcEntry],T0.[Comments],
                            (SELECT COUNT(P0.LineNum) FROM RDR1 P0 WHERE P0.DocEntry = T0.DocEntry) AS xDetail,
                            CASE WHEN T5.TrgetEntry IS NULL AND T0.DocStatus = 'C' THEN 'C' ELSE 'OK' END AS  STSO,
                            CASE WHEN UPPER(LEFT(T0.[Comments],2)) = 'QQ' THEN 'Y' ELSE 'N' END AS QQst
                        FROM  ORDR T0 
                            LEFT JOIN NNM1 T1 ON T0.[Series] = T1.[Series] 
                            LEFT JOIN OUSR T3 ON T0.[UserSign] = T3.[USERID] 
                            LEFT JOIN OSLP T4 ON T0.[SlpCode] = T4.[SlpCode] 
                            LEFT JOIN RDR1 T5 ON T0.[DocEntry] = T5.[DocEntry] 
                        WHERE T0.DocEntry = '".$DataORDR['DocEntry']."'
                        ORDER BY xCh DESC,T0.DocEntry";
            $sapfqryX = odbc_exec($sapconn,$MsSQL);
            $DataORDRX = odbc_fetch_array($sapfqryX);
            $time = $DataORDRX['DocTime'];
            if ($time > 1300) {
                $TimeType = 'PM';   
            }else{
                $TimeType = 'AM';   
            }
              $InsertSET = "SODocEntry = '".$DataORDRX['DocEntry']."',
              DocNum =  '".$DataORDRX['BeginStr'].$DataORDRX['DocNum']."',
              QQst = '".$DataORDRX['QQst']."',
              DocType = 'ORDR',
              DocDate = '".date("Y-m-d",strtotime($DataORDRX['DocDate']))."',
              OriDate = '".date("Y-m-d",strtotime($DataORDRX['CreateDate']))."',
              OriTime = '".$DataORDRX['DocTime']."',
              DocDueDate = '".date("Y-m-d",strtotime($DataORDRX['DocDueDate']))."',
              CardCode = '".$DataORDRX['CardCode']."',
              CardName = '".conutf8($DataORDRX['CardName'])."',
              DateCreate = NOW(),
              TimeType = '".$TimeType."',
              ItemCount = '".$DataORDRX['xDetail']."',
              TeamCode = '".$DataORDRX['xCh']."',
              StatusDoc = '14'";
              //echo "INSERT INTO picker_soheader SET ".$InsertSET."<br>";
              $getdata->my_sql_insert("picker_soheader",$InsertSET);
              $y++;
        }else{
            $getdata->my_sql_update("picker_soheader","StatusDoc = 14","SODocEntry = '".$DataORDR->DocEntry."'");
            //echo "UPDATE picker_soheader SET StatusDoc = 14","SODocEntry = '".$DataORDR->DocEntry."'";
            $x++;
        }
        $z++;
}
echo "Finish  Update : ".$x."<br>";
echo "Finish  New    : ".$y."<br>";
echo "Finish  Total  : ".$z."<br>";

