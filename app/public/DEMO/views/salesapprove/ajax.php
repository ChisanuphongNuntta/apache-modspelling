<?php session_start();
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
require_once("../../core/functions.core.php");
$JSON  = array();
$inval = array();

$UKEY = $_SESSION['UKEY'];
$LvClass = $_SESSION['LVCODE'];
$SiteID = $_SESSION['SITE']['site_id'];

if($_GET['p'] == 'CallData') {
    $SQL1 = 
        "SELECT
        T0.DocEntry, T0.DocDate, T0.DocDueDate, CONCAT(T0.DocType,'-',T0.DocNum) AS 'DocNum', CONCAT(T0.CardCode,' | ',T0.CardName) AS 'CardName', T0.DocTotal, IFNULL(T1.SlpName,'') AS SlpName,
        CASE WHEN (SELECT COUNT(P0.AppID) FROM order_approve P0 WHERE P0.DocEntry = T0.DocEntry AND P0.APP0 = 'Y') > 0 THEN 'Y' ELSE 'N' END AS 'APP0',
        CASE WHEN (SELECT COUNT(P0.AppID) FROM order_approve P0 WHERE P0.DocEntry = T0.DocEntry AND P0.APP1 = 'Y') > 0 THEN 'Y' ELSE 'N' END AS 'APP1',
        CASE WHEN (SELECT COUNT(P0.AppID) FROM order_approve P0 WHERE P0.DocEntry = T0.DocEntry AND P0.APP2 = 'Y') > 0 THEN 'Y' ELSE 'N' END AS 'APP2',
        CASE WHEN (SELECT COUNT(P0.AppID) FROM order_approve P0 WHERE P0.DocEntry = T0.DocEntry AND P0.APP3 = 'Y') > 0 THEN 'Y' ELSE 'N' END AS 'APP3',
        CASE WHEN (SELECT COUNT(P0.AppID) FROM order_approve P0 WHERE P0.DocEntry = T0.DocEntry AND P0.APP4 = 'Y') > 0 THEN 'Y' ELSE 'N' END AS 'APP4'
       FROM order_header T0
       LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode AND T1.site_id = $SiteID
       LEFT JOIN order_approve T2 ON T0.DocEntry = T2.DocEntry
       WHERE T0.IntStatus = '2' AND (T2.LvClassReq = '".$_SESSION['LVCODE']."' AND T2.AppResult = '0') AND (IFNULL((SELECT P0.AppResult FROM order_approve P0 WHERE P0.DocEntry = T0.DocEntry AND P0.StepApprove < T2.StepApprove ORDER BY P0.StepApprove DESC LIMIT 1),'Y') = 'Y') AND T0.site_id = $SiteID";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    $Status = "ERR";
    if($RST1) {
        $Status = "SUCCESS";
        $inval['ListOrder'] = $RST1;
    }
    $inval['Status'] = $Status;
}

if($_GET['p'] == "CallDetail") {

    $DocEntry = $_POST['DocEntry'];
    $this_year = date("Y");
    $prev_year = date("Y")-1;

    /*========== HEADER ==========*/
    $SQL1 = 
        "SELECT
            T0.DocEntry, T0.DocDate, T0.CardCode, T0.CardName, T0.GroupNum, CONCAT(T0.DocType,'-',T0.DocNum) AS 'DocNum', IFNULL(T1.SlpName,'') AS 'SlpName', T0.Comments, 
            T0.DocTotal, T0.VatSum, T0.DiscPcnt, T0.DiscTotal, CASE WHEN (T0.DocTotal-T0.VatSum) <> 0 THEN (T0.GrossProfit / (T0.DocTotal - T0.VatSum)) * 100 ELSE 0 END AS 'DocPcntProfit'
        FROM order_header T0
        LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode AND T1.site_id = $SiteID
        WHERE T0.DocEntry = $DocEntry LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    if($RST1) {
        $inval['Status'] = "OK";
        $inval['LVCLASS'] = $LvClass;

        $inval['HEADER'] = $RST1[0];

        $CardCode = $RST1[0]['CardCode'];
        $GroupNum = $RST1[0]['GroupNum'];

        $SQL2 = 
            "SELECT
                (SELECT T0.[CreditLine] FROM OCRD T0 WHERE T0.[CardCode] = '$CardCode') AS [CreditLine],
                (SELECT T0.[Balance]    FROM OCRD T0 WHERE T0.[CardCode] = '$CardCode') AS [Balance],
                (SELECT T0.[PymntGroup] FROM OCTG T0 WHERE T0.[GroupNum] = '$GroupNum') AS [PymntGroup]";
        $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll();
        $inval['HEADER']['GroupNum']   = SapTH($RST2[0]['PymntGroup']);
        $inval['HEADER']['CreditLine'] = $RST2[0]['CreditLine'];
        $inval['HEADER']['Balance']    = $RST2[0]['Balance'];

        $SQL3 = "SELECT TOP 1 ISNULL(T0.[DocDate],'') AS [DocDate], ISNULL(DATEDIFF(day, T0.[DocDate], GETDATE()),0) AS [Aging] FROM OINV T0 LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode] WHERE T0.[CardCode] = '$CardCode' OR T1.[FatherCard] = '$CardCode' ORDER BY T0.[DocDate]";
        $RST3 = DBConnect("SAP")->query(SQLtoHANA($SQL3))->fetchAll();
        if($RST3) {
            $inval['HEADER']['Aging'] = $RST3[0]['Aging'];
            $inval['HEADER']['1stDate'] = ($RST3[0]['DocDate'] != "") ? date("d/m/Y",strtotime($RST3[0]['DocDate'])) : "" ;
        } else {
            $inval['HEADER']['Aging'] = 0;
            $inval['HEADER']['1stDate'] = "" ;
        }

        $inval['HEADER'][$prev_year] = 0;
        $inval['HEADER'][$this_year] = 0;

        $SQL4 = 
            "SELECT
                A0.[DocYear], SUM(A0.[DocTotal]) AS [DocTotal]
            FROM (
                SELECT
                    YEAR(T0.[DocDate]) AS [DocYear], SUM(T0.[DocTotal]) AS [DocTotal]
                FROM OINV T0
                LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
                WHERE (T0.[CardCode] = '$CardCode' OR T1.[FatherCard] = '$CardCode') AND (T0.CANCELED = 'N' AND YEAR(T0.[DocDate]) BETWEEN $prev_year AND $this_year)
                GROUP BY YEAR(T0.[DocDate])
                UNION ALL
                SELECT
                    YEAR(T0.[DocDate]) AS [DocYear], -SUM(T0.[DocTotal]) AS [DocTotal]
                FROM ORIN T0
                LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
                WHERE (T0.[CardCode] = '$CardCode' OR T1.[FatherCard] = '$CardCode') AND (T0.CANCELED = 'N' AND YEAR(T0.[DocDate]) BETWEEN $prev_year AND $this_year)
                GROUP BY YEAR(T0.[DocDate])
            ) A0
            GROUP BY A0.[DocYear]
            ORDER BY A0.[DocYear]";
        $RST4 = DBConnect("SAP")->query(SQLtoHANA($SQL4))->fetchAll();
        if($RST4) {
            foreach($RST4 as $data) {
                $inval['HEADER'][$data['DocYear']] = number_format($data['DocTotal'],2);
            }
        }

        $SQL5 =
            "SELECT
                SUM(A0.[PaidtoDate]) AS [PaidTotal]
            FROM (
                SELECT
                    SUM(T0.[PaidtoDate]-T0.[VatPaid]) AS [PaidtoDate]
                FROM OINV T0
                LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
                WHERE (T0.[CardCode] = '$CardCode' OR T1.[FatherCard] = '$CardCode') AND (T0.CANCELED = 'N')
                UNION ALL
                SELECT
                    -SUM(T0.[PaidtoDate]-T0.[VatPaid]) AS [PaidtoDate]
                FROM ORIN T0
                LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
                WHERE (T0.[CardCode] = '$CardCode' OR T1.[FatherCard] = '$CardCode') AND (T0.CANCELED = 'N')
            ) A0";
        $RST5 = DBConnect("SAP")->query(SQLtoHANA($SQL5))->fetchAll();
        $inval['HEADER']['PaidTotal'] = number_format($RST5[0]['PaidTotal'],2);

        /*========== APPROVE LIST ==========*/
        $SQL1 = "SELECT T0.AppID, T0.DocEntry, T0.LvClassReq, T1.LvName, T0.APP0, T0.APP1, T0.APP2, T0.APP3, T0.APP4, T0.AppResult, T0.AppRemark FROM order_approve T0 LEFT JOIN positions T1 ON T0.LvClassReq = T1.LvCode WHERE T0.DocEntry = $DocEntry";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
        $inval['APPROVE'] = $RST1;

        /*========== OVERDUE LIST ==========*/
        $SQL1 = "SELECT T0.RefID, T0.DocNum, T0.DocDate, T0.DocDueDate, T0.CardCode, T0.CardName, T0.DocTotal, (T0.DocTotal-T0.PaidtoDate) AS 'Balance', DATEDIFF(NOW(),T0.DocDueDate) AS 'OverDate' FROM order_appdue T0 WHERE T0.DocEntry = $DocEntry AND T0.RefStatus = 'A'";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
        $inval['OVERDUE'] = $RST1;

        /*=========== ITEM LIST ==========*/
        $SQL1 = 
            "SELECT
                T0.VisOrder, T0.ItemCode, T0.CodeBars, T0.ItemName, T0.WhsCode, T0.Quantity, T0.UnitMsr,
                (T0.LineTotal-T0.LineProfit)/T0.Quantity AS 'Cost', T0.GrandPrice, T0.Line_Disc0, T0.Line_Disc1, T0.Line_Disc2, T0.Line_Disc3, T0.Line_Disc4, T0.Line_Disc5,
                T0.UnitPrice, T0.LineTotal, T0.LineProfit, T0.Line_SP, CASE WHEN T0.UnitPrice <> 0 THEN ((T0.UnitPrice - (T0.LineTotal-T0.LineProfit)/T0.Quantity)/T0.UnitPrice)*100 ELSE 0 END AS 'PcntProfit'
            FROM order_detail T0 WHERE T0.DocEntry = $DocEntry AND T0.LineStatus != 'I' ORDER BY T0.VisOrder";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
        $inval['ITEMLIST'] = $RST1;

        /*========== ATTACH LIST ==========*/
        $SQL2 = 
            "SELECT T0.AttachID, T0.FileOriName, T0.FileDirName, T0.FileExt, T0.DateCreate
            FROM order_attach T0 
            WHERE T0.DocEntry = $DocEntry AND T0.FileStatus = 'A'
            ORDER BY T0.VisOrder";
        $RST2 = DBConnect("APP")->query($SQL2)->fetchAll(PDO::FETCH_ASSOC);
        $inval['ItemAttach'] = $RST2;
    } else {
        $inval['Status'] = "ERR";
    }
    
}

if($_GET['p'] == "AppOrder") {

    $inval['Status']  = "OK";
    $inval['Message'] = "บันทึกสำเร็จ";

    $DocEntry  = $_POST['DocEntry'];
    $AppID     = $_POST['AppID'];
    $AppRemark = $_POST['Remark'];
    $AppResult = $_POST['Result'];

    /*========== SAVE INTO ORDER APPROVE ==========*/
    $SQL1 = 
        "UPDATE order_approve SET
            AppResult = :AppResult, 
            AppRemark = :AppRemark, 
            uKeyApproved = :UKEY, 
            DateApproved = NOW(), 
            ssidApproved = :SSID, 
            uKeyUpdate = :UKEY 
        WHERE AppID = :AppID";

    $QRY1 = DBConnect("APP")->prepare($SQL1);
    $QRY1->bindparam(":AppResult", $AppResult);
    $QRY1->bindparam(":AppRemark", $AppRemark);
    $QRY1->bindparam(":UKEY",      $UKEY);
    $QRY1->bindparam(":SSID",      $SSID);
    $QRY1->bindparam(":AppID",     $AppID);
    $QRY1->execute();

    if($AppResult == "N") {
        $SQL1 =
            "UPDATE order_header SET
                DocStatus = 'C',
                AppStatus = 'N',
                IntStatus = 4,
                uKeyUpdate = :UKEY,
                ssidUpdate = :SSID,
                DateUpdate = NOW()
            WHERE DocEntry = :DocEntry";
        $QRY1 = DBConnect("APP")->prepare($SQL1);
        $QRY1->bindparam(":UKEY",     $UKEY);
        $QRY1->bindparam(":SSID",     $SSID);
        $QRY1->bindparam(":DocEntry", $DocEntry);
        $QRY1->execute();

    } else {
        // Check Below Rows
        $SQL2 = "SELECT T0.StepApprove FROM order_approve T0 WHERE T0.DocEntry = $DocEntry AND T0.AppID = $AppID LIMIT 1";
        $RST2 = DBConnect("APP")->query($SQL2)->fetchAll()[0];
        $StepApp = $RST2['StepApprove'];

        $SQL3 = "SELECT COUNT(T0.AppID) AS 'AppRow' FROM order_approve T0 WHERE T0.DocEntry = $DocEntry AND T0.StepApprove > $StepApp";
        $RST3 = DBConnect("APP")->query($SQL3)->fetchAll()[0];
        $NextRow = $RST3['AppRow'];

        if($NextRow == 0) {
            $SQL1 =
                "UPDATE order_header SET
                    DocStatus = 'C',
                    AppStatus = 'Y',
                    IntStatus = 3,
                    uKeyUpdate = :UKEY,
                    ssidUpdate = :SSID,
                    DateUpdate = NOW()
                WHERE DocEntry = :DocEntry";
            $QRY1 = DBConnect("APP")->prepare($SQL1);
            $QRY1->bindparam(":UKEY",     $UKEY);
            $QRY1->bindparam(":SSID",     $SSID);
            $QRY1->bindparam(":DocEntry", $DocEntry);
            $QRY1->execute();
            
            $ImportSAP = ImportSAP($_SESSION['SITE']['site_id'],"ORDR",$DocEntry);
            
            $inval['Status']  = $ImportSAP['Status'];
            $inval['Message'] = $ImportSAP['Message'];
        }
    }    
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>