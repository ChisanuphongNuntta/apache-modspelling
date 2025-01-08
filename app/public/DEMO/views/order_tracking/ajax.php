<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();
$SiteID = $_SESSION['SITE']['site_id'];

if($_GET['p'] == 'GetOrder') {
    $DocY = $_POST['Year'];
    $DocM = $_POST['Month'];
    $UKEY = $_SESSION['UKEY'];
    $DEPT = $_SESSION['DEPTCODE'];
    if($_SESSION['LVCLASS'] == 12) {
        $WHR1 = "AND T0.uKeyCreate = '$UKEY'";
    } else {
        switch($DEPT) {
            case "DP000":
            case "DP001":
            case "DP004":
            case "DP005":    
                $WHR1 = ""; break;
            case "DP003":
                $WHR1 = "AND T4.DeptCode = '$DEPT'"; break;
                
            default: $WHR1 = "AND T4.DeptCode = '$DEPT'"; break;
        }
    }

    $SQL1 = 
        "SELECT
            T0.DocEntry, T0.DocDate, CONCAT(T0.DocType,'-',T0.DocNum) AS 'DocNum', T0.CardCode, T0.CardName AS CardName2,
            CONCAT(T0.CardCode, ' | ', T0.CardName) AS 'CardName', T0.U_PONo, (T0.DocTotal-T0.VatSum) AS 'DocTotal', T0.ImportEntry,
            T0.DateImport
        FROM order_header T0
        LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode AND T1.site_id = $SiteID
        LEFT JOIN users T3 ON T0.uKeyCreate = T3.uKey
        LEFT JOIN positions T4 ON T3.LvCode = T4.LvCode
        WHERE (YEAR(T0.DocDate) = $DocY AND MONTH(T0.DocDate) = $DocM) AND T0.ImportEntry > 0 AND T0.site_id = $SiteID $WHR1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    $ListDocEntry = "";
    foreach($RST1 as $k=>$DataApp) {
        $ListDocEntry .= ($k < (count($RST1)-1)) ? $DataApp['ImportEntry']."," : $DataApp['ImportEntry'];
        $ArrDataApp[$DataApp['ImportEntry']]['DocEntry'] = $DataApp['DocEntry'];
        $ArrDataApp[$DataApp['ImportEntry']]['DocNum'] = $DataApp['DocNum'];
        $ArrDataApp[$DataApp['ImportEntry']]['DocDate'] = ($DataApp['DocDate'] != "") ? date("d/m/Y", strtotime($DataApp['DocDate'])) : "-";
        $ArrDataApp[$DataApp['ImportEntry']]['CardCode'] = $DataApp['CardCode'];
        $ArrDataApp[$DataApp['ImportEntry']]['CardName2'] = $DataApp['CardName2'];
        $ArrDataApp[$DataApp['ImportEntry']]['CardName'] = $DataApp['CardName'];
        $ArrDataApp[$DataApp['ImportEntry']]['U_PONo'] = $DataApp['U_PONo'];
        $ArrDataApp[$DataApp['ImportEntry']]['DocTotal'] = ($DataApp['DocTotal'] != "") ? number_format($DataApp['DocTotal'], 2) : "-";
        $ArrDataApp[$DataApp['ImportEntry']]['DateImport'] = "<br/><small style='font-size: 10px;'>".date("d/m/Y", strtotime($DataApp['DateImport']))." เวลา ".date("H:i:s", strtotime($DataApp['DateImport']))."</small>";
       
    }

    if($ListDocEntry != "") {
        $SQL2 = 
            "SELECT DISTINCT
                T0.[DocEntry] AS [SODocEntry], T2.[BeginStr]+CAST(T0.[DocNum] AS VARCHAR) AS [SODocNum], T0.[DocDate] AS [SOCreate], T0.[CANCELED] AS 'SOCanceled', T0.[DocTotal] AS [SODocTotal], T8.[SlpName],
                T4.[DocEntry] AS [DODocEntry], T5.[BeginStr]+CAST(T3.[DocNum] AS VARCHAR) AS [DODocNum], T3.[DocDate] AS [DOCreate], T3.[CANCELED] AS 'DOCanceled', T3.[DocTotal] AS [DODocTotal],
                T6.[DocEntry] AS [IVDocEntry], T7.[BeginStr]+CAST(T6.[DocNum] AS VARCHAR) AS [IVDocNum], T6.[DocDate] AS [IVCreate], T6.[CANCELED] AS 'IVCanceled', T6.[DocDueDate] AS [IVDueDate], T6.[DocTotal] AS [IVDocTotal], T6.[PaidToDate], T6.[DocStatus], T6.[CANCELED],
                T0.[Comments], CASE WHEN UPPER(T0.Comments) LIKE '%FOC%' THEN UPPER(SUBSTRING(UPPER(T0.Comments), CHARINDEX('FOC', UPPER(T0.Comments)),7)) ELSE '' END AS [FOC],
                (SELECT SUM(A0.[PriceBefDi]*A0.[Quantity]) AS [LineTotal] FROM INV1 A0 WHERE A0.[DocEntry] = T9.[DocEntry] AND A0.[DiscPrcnt] = 100) AS [TotalFOC]
            FROM ORDR T0
            LEFT JOIN RDR1 T1 ON T0.[DocEntry]   = T1.[DocEntry]
            LEFT JOIN NNM1 T2 ON T0.[Series]     = T2.[Series]
            LEFT JOIN ODLN T3 ON T1.[TargetType] = T3.[ObjType] AND T1.[TrgetEntry] = T3.[DocEntry]
            LEFT JOIN DLN1 T4 ON T3.[DocEntry]   = T4.[DocEntry]
            LEFT JOIN NNM1 T5 ON T3.[Series]     = T5.[Series]
            LEFT JOIN OINV T6 ON T4.[TargetType] = T6.[ObjType] AND T4.[TrgetEntry] = T6.[DocEntry]
            LEFT JOIN NNM1 T7 ON T6.[Series]     = T7.[Series]
            LEFT JOIN OSLP T8 ON T0.[SlpCode]    = T8.[SlpCode]
            LEFT JOIN INV1 T9 ON T6.[DocEntry] = T9.[DocEntry]
            WHERE T0.[CANCELED] = 'N' AND T0.[DocEntry] IN ($ListDocEntry)
            ORDER BY [SODocEntry] ASC, [DODocEntry] DESC";
        // echo  $SQL2;
        $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll();
        $tmpDoc = ""; $tmpDocFirst  = 0; $tmpDocSecond = 0; 
        $r = 0;
        foreach($RST2 as $k=>$DataSAP) {
            if($tmpDoc != $DataSAP['SODocEntry']) {
                $tmpDoc = $DataSAP['SODocEntry'];
                if($DataSAP['DODocEntry'] != "" || $DataSAP['IVDocEntry'] != "") {
                    $tmpDocFirst = ($DataSAP['DODocEntry'] != "" && $DataSAP['IVDocEntry'] != "") ? 2 : 1;
                }
    
                $inval[$r]['Status'] = "Y";
    
                $inval[$r]['No'] = ($r+1);
                $inval[$r]['DocNum'] = "<a href='javascript:void(0);' onclick='ViewDoc(\"WBAP\",".$ArrDataApp[$DataSAP['SODocEntry']]['DocEntry'].")'>".$ArrDataApp[$DataSAP['SODocEntry']]['DocNum']."</a>";
                $inval[$r]['DocDate'] = $ArrDataApp[$DataSAP['SODocEntry']]['DocDate'];
                $inval[$r]['CardCode'] = $ArrDataApp[$DataSAP['SODocEntry']]['CardCode'];
                $inval[$r]['CardName2'] = $ArrDataApp[$DataSAP['SODocEntry']]['CardName2'];
                $inval[$r]['CardName'] = $ArrDataApp[$DataSAP['SODocEntry']]['CardName']."<br><small>พนักงานขาย: ".SapTH($DataSAP['SlpName'])."</small>";
                $inval[$r]['SlpName'] = SapTH($DataSAP['SlpName']);
                $inval[$r]['U_PONo'] = $ArrDataApp[$DataSAP['SODocEntry']]['U_PONo'];
                $inval[$r]['DocTotal'] = $ArrDataApp[$DataSAP['SODocEntry']]['DocTotal'];
    
                $inval[$r]['DocNumSO'] = ($DataSAP['SODocEntry'] != "") ? "<a href='javascript:void(0);' onclick='ViewDoc(\"ORDR\",".$DataSAP['SODocEntry'].")'>".$DataSAP['SODocNum']."</a>".$ArrDataApp[$DataSAP['SODocEntry']]['DateImport'] : "-";
                $inval[$r]['DocDateSO'] = ($DataSAP['SOCreate'] != "") ? date("d/m/Y", strtotime($DataSAP['SOCreate'])) : "-";
                $inval[$r]['DocTotalSO'] = ($DataSAP['SODocTotal'] != "") ? number_format($DataSAP['SODocTotal'], 2) : "-";
    
                $inval[$r]['DocNumDO'] = ($DataSAP['DODocEntry'] != "") ? "<a href='javascript:void(0);' onclick='ViewDoc(\"ODLN\",".$DataSAP['DODocEntry'].")'>".$DataSAP['DODocNum']."</a>" : "-";
                $inval[$r]['DocDateDO'] = ($DataSAP['DOCreate'] != "") ? date("d/m/Y", strtotime($DataSAP['DOCreate'])) : "-";
                $inval[$r]['DocTotalDO'] = ($DataSAP['DODocTotal'] != "") ? number_format($DataSAP['DODocTotal'], 2) : "-";
    
                $inval[$r]['DocNumIV'] = ($DataSAP['IVDocEntry'] != "") ? "<a href='javascript:void(0);' onclick='ViewDoc(\"OINV\",".$DataSAP['IVDocEntry'].")'>".$DataSAP['IVDocNum']."</a>" : "-";
                $inval[$r]['DocDateIV'] = ($DataSAP['IVCreate'] != "") ? date("d/m/Y", strtotime($DataSAP['IVCreate'])) : "-";
                $inval[$r]['DocDueDateIV'] = ($DataSAP['IVDueDate'] != "") ? date("d/m/Y", strtotime($DataSAP['IVDueDate'])) : "-";
                $inval[$r]['DocTotalIV'] = ($DataSAP['IVDocTotal'] != "") ? number_format($DataSAP['IVDocTotal'], 2) : "-";
                $inval[$r]['PaidIV'] = ($DataSAP['PaidToDate'] != "") ? number_format($DataSAP['PaidToDate'], 2) : "-";
                
                $inval[$r]['Comments'] = SapTH($DataSAP['Comments']);
                $inval[$r]['FOC'] = SapTH($DataSAP['FOC']);
                $inval[$r]['TotalFOC'] = ($DataSAP['TotalFOC'] != "") ? number_format($DataSAP['TotalFOC'], 2) : "-";
    
                $r++;
            }else{
                if($DataSAP['DODocEntry'] != "" || $DataSAP['IVDocEntry'] != "") {
                    $tmpDocSecond = ($DataSAP['DODocEntry'] != "" && $DataSAP['IVDocEntry'] != "") ? 2 : 1;

                    $inval[$r]['Status'] = ($tmpDocFirst == $tmpDocSecond) ? "Y" : "N";
    
                    $inval[$r]['No'] = ($r+1);
                    $inval[$r]['DocNum'] = "<a href='javascript:void(0);' onclick='ViewDoc(\"WBAP\",".$ArrDataApp[$DataSAP['SODocEntry']]['DocEntry'].")'>".$ArrDataApp[$DataSAP['SODocEntry']]['DocNum']."</a>";
                    $inval[$r]['DocDate'] = $ArrDataApp[$DataSAP['SODocEntry']]['DocDate'];
                    $inval[$r]['CardCode'] = $ArrDataApp[$DataSAP['SODocEntry']]['CardCode'];
                    $inval[$r]['CardName2'] = $ArrDataApp[$DataSAP['SODocEntry']]['CardName2'];
                    $inval[$r]['CardName'] = $ArrDataApp[$DataSAP['SODocEntry']]['CardName']."<br><small>พนักงานขาย: ".SapTH($DataSAP['SlpName'])."</small>";
                    $inval[$r]['SlpName'] = SapTH($DataSAP['SlpName']);
                    $inval[$r]['U_PONo'] = $ArrDataApp[$DataSAP['SODocEntry']]['U_PONo'];
                    $inval[$r]['DocTotal'] = $ArrDataApp[$DataSAP['SODocEntry']]['DocTotal'];
    
                    $inval[$r]['DocNumSO'] = ($DataSAP['SODocEntry'] != "") ? "<a href='javascript:void(0);' onclick='ViewDoc(\"ORDR\",".$DataSAP['SODocEntry'].")'>".$DataSAP['SODocNum']."</a>".$ArrDataApp[$DataSAP['SODocEntry']]['DateImport'] : "-";
                    $inval[$r]['DocDateSO'] = ($DataSAP['SOCreate'] != "") ? date("d/m/Y", strtotime($DataSAP['SOCreate'])) : "-";
                    $inval[$r]['DocTotalSO'] = ($DataSAP['SODocTotal'] != "") ? number_format($DataSAP['SODocTotal'], 2) : "-";
    
                    $inval[$r]['DocNumDO'] = ($DataSAP['DODocEntry'] != "") ? "<a href='javascript:void(0);' onclick='ViewDoc(\"ODLN\",".$DataSAP['DODocEntry'].")'>".$DataSAP['DODocNum']."</a>" : "-";
                    $inval[$r]['DocDateDO'] = ($DataSAP['DOCreate'] != "") ? date("d/m/Y", strtotime($DataSAP['DOCreate'])) : "-";
                    $inval[$r]['DocTotalDO'] = ($DataSAP['DODocTotal'] != "") ? number_format($DataSAP['DODocTotal'], 2) : "-";
    
                    $inval[$r]['DocNumIV'] = ($DataSAP['IVDocEntry'] != "") ? "<a href='javascript:void(0);' onclick='ViewDoc(\"OINV\",".$DataSAP['IVDocEntry'].")'>".$DataSAP['IVDocNum']."</a>" : "-";
                    $inval[$r]['DocDateIV'] = ($DataSAP['IVCreate'] != "") ? date("d/m/Y", strtotime($DataSAP['IVCreate'])) : "-";
                    $inval[$r]['DocDueDateIV'] = ($DataSAP['IVDueDate'] != "") ? date("d/m/Y", strtotime($DataSAP['IVDueDate'])) : "-";
                    $inval[$r]['DocTotalIV'] = ($DataSAP['IVDocTotal'] != "") ? number_format($DataSAP['IVDocTotal'], 2) : "-";
                    $inval[$r]['PaidIV'] = ($DataSAP['PaidToDate'] != "") ? number_format($DataSAP['PaidToDate'], 2) : "-";

                    $inval[$r]['Comments'] = SapTH($DataSAP['Comments']);
                    $inval[$r]['FOC'] = SapTH($DataSAP['FOC']);
                    $inval[$r]['TotalFOC'] = ($DataSAP['TotalFOC'] != "") ? number_format($DataSAP['TotalFOC'], 2) : "-";
    
                    $r++;
                }
            }
        }
    }
}

if($_GET['p'] == "ViewDoc") {
    $Type = $_POST['Type'];
    $DocEntry = $_POST['DocNum'];

    if($Type == 'WBAP') {
        $SQL1 = 
            "SELECT 
                T0.CardCode, T0.CardName, T0.LicTradeNum, T0.DocDate, T0.DocDueDate, T0.GroupNum,
                T0.BilltoAddress, T0.ShiptoAddress, IFNULL(T1.SlpName,'') as 'SlpCode', T0.U_PONo, T0.Comments,
                T0.DiscPcnt, T0.DiscTotal, T0.DocTotal, T0.VatSum, T0.U_SO_Type
            FROM order_header T0
            LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode AND T1.site_id = $SiteID
            WHERE DocEntry = $DocEntry";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
        if($RST1) {
            $RST1 = $RST1[0];
            $U_PONo = ($RST1['U_PONo'] != null) ? $RST1['U_PONo'] : "";

            $SQL_GroupNum = "SELECT TOP 1 T0.[GroupNum], T0.[PymntGroup] FROM OCTG T0 WHERE T0.[GroupNum] = ".$RST1['GroupNum']."";
            $RST_GroupNum = DBConnect("SAP")->query(SQLtoHANA($SQL_GroupNum))->fetchAll()[0];
            $U_SO_Type = "";
            switch($RST1['U_SO_Type']) {
                case '99': $U_SO_Type = "Commercial"; break;
                case '10': $U_SO_Type = "Influencer"; break;
                case '20': $U_SO_Type = "Event"; break;
                case '30': $U_SO_Type = "Promotion Team"; break;
                case '40': $U_SO_Type = "MKT Rewards"; break;
                case '50': $U_SO_Type = "MKT Support"; break;
            }
            $tBody = [
                $RST1['CardCode']." | ".$RST1['CardName'], $RST1['LicTradeNum'],
                date("d/m/Y", strtotime($RST1['DocDate'])), date("d/m/Y", strtotime($RST1['DocDueDate'])),
                SapTH($RST_GroupNum['PymntGroup']), $U_SO_Type,
                $RST1['BilltoAddress'], $RST1['ShiptoAddress'],
                $RST1['SlpCode'], $U_PONo
            ];

            $inval['Comments'] =  $RST1['Comments'];
            $inval['DiscPcnt'] = $RST1['DiscPcnt'];
            $inval['DiscTotal'] = $RST1['DiscTotal'];
            $inval['DocTotal'] = $RST1['DocTotal'];
            $inval['VatSum'] = $RST1['VatSum'];
            $inval['tBody'] = $tBody;

            $SQL2 = 
                "SELECT 
                    T0.ItemCode, T0.CodeBars, T0.ItemName, T0.WhsCode, T0.Quantity, T0.UnitMsr,
                    T0.GrandPrice, T0.Line_Disc0, T0.Line_Disc1, T0.Line_Disc2, T0.Line_Disc3, T0.Line_Disc4,
                    T0.UnitPrice, T0.LineTotal, T0.Line_SP
                FROM order_detail T0
                WHERE T0.LineStatus != 'I' AND T0.DocEntry = $DocEntry
                ORDER BY T0.VisOrder";
            $RST2 = DBConnect("APP")->query($SQL2)->fetchAll(PDO::FETCH_ASSOC);
            $inval['ItemList'] = $RST2;
        }
    }else{
        $SQL1 = 
            "SELECT
                ISNULL(T3.[BeginStr]+CAST(T0.[DocNum] AS VARCHAR),T0.[NumAtCard]) AS [DocNum],
                CONCAT(T0.[CardCode], ' | ', T0.[CardName]) AS CardName, T0.[LicTradNum], T0.[DocDate], T0.[DocDueDate], T1.[PymntGroup],
                CONCAT(T0.[PayToCode], ' ', T0.[Address]) AS [PaytoAddr],  CONCAT(T0.[ShipToCode], ' ', T0.[Address2]) AS [ShiptoAddr],
                T2.[SlpName], T0.[NumAtCard], T0.[Comments], T0.[CANCELED], T0.[DocTotal], T0.[DiscPrcnt], T0.[DiscSum], T0.[VatSum],
                T4.[VisOrder], T4.[ItemCode], T4.[CodeBars], T4.[Dscription], T4.[WhsCode], T4.[Quantity], T4.[unitMsr],
                T4.[PriceBefDi], T4.[U_DiscP1], T4.[U_DiscP2], T4.[U_DiscP3], T4.[U_DiscP4], T4.[U_DiscP5], T4.[PriceAfVAT], T4.[LineTotal], T4.[LineStatus],
                T0.DocStatus
            FROM $Type T0
            LEFT JOIN OCTG T1 ON T0.[GroupNum] = T1.[GroupNum]
            LEFT JOIN OSLP T2 ON T0.[SlpCode] = T2.[SlpCode]
            LEFT JOIN NNM1 T3 ON T0.[Series] = T3.[Series]
            LEFT JOIN ".substr($Type, 1)."1 T4 ON T0.[DocEntry] = T4.[DocEntry]
            WHERE T0.DocEntry = $DocEntry";
        $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll(PDO::FETCH_ASSOC);
        if($RST1) {
            $RST1 = SendTHData($RST1);
            $inval['DataView'] = $RST1;
            $Canceled = "";
            switch ($RST1[0]['CANCELED']) {
                case 'Y': $Canceled = "<span class='badge bg-danger p-1'>ยกเลิก</span>"; break;
                case 'N': 
                    if($RST1[0]['DocStatus'] == 'C') {
                        $Canceled = "<span class='badge bg-success p-1'>เอกสารปิด</span>";
                    }else{
                        $Canceled = "<span class='badge bg-warning p-1'>เอกสารคงค้าง</span>";
                    }
                    break;
            }
            $inval['DataHead'] = 
                [
                    $RST1[0]['DocNum'], $Canceled,
                    $RST1[0]['CardName'], $RST1[0]['LicTradNum'],
                    date("d/m/Y", strtotime($RST1[0]['DocDate'])), date("d/m/Y", strtotime($RST1[0]['DocDueDate'])),
                    $RST1[0]['PymntGroup'], "",
                    $RST1[0]['PaytoAddr'], $RST1[0]['ShiptoAddr'],
                    $RST1[0]['SlpName'], $RST1[0]['NumAtCard'],
                ];
        }
    }
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>