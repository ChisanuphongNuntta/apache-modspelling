<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();

if($_GET['p'] == 'GetSoOutstan') {
    $SQL = 
        "SELECT W0.*
        FROM (
            SELECT T0.[DocEntry] AS [SODocEntry], 'SPING' AS [Company],(T1.[BeginStr]+CAST(T0.[DocNum] AS [VarChar])) AS [SO_DocNum],T0.[DocDate],T0.[DocTime],T0.[CardCode],T0.[CardName],T2.[SlpName],T0.[DocTotal],T0.[Printed],
                   T0.[CreateDate],T0.[CreateTS]
            FROM [SBO_GOLIVE_SHAWPING].[dbo].[ORDR] T0
            LEFT JOIN [SBO_GOLIVE_SHAWPING].[dbo].[NNM1] T1 ON T0.[Series]  = T1.[Series]
            LEFT JOIN [SBO_GOLIVE_SHAWPING].[dbo].[OSLP] T2 ON T0.[SlpCode] = T2.[SlpCode]  
            WHERE T0.[DocStatus] = 'O' AND T0.[SlpCode] != 15 
            UNION ALL
            SELECT T0.[DocEntry] AS [SODocEntry], 'SPET' AS [Company],(T1.[BeginStr]+CAST(T0.[DocNum] AS [VarChar])) AS [SO_DocNum],T0.[DocDate],T0.[DocTime],T0.[CardCode],T0.[CardName],T2.[SlpName],T0.[DocTotal],T0.[Printed],
                   T0.[CreateDate],T0.[CreateTS]
            FROM [SBO_GOLIVE_SHAWPET].[dbo].[ORDR] T0
            LEFT JOIN [SBO_GOLIVE_SHAWPET].[dbo].[NNM1] T1 ON T0.[Series]  = T1.[Series]
            LEFT JOIN [SBO_GOLIVE_SHAWPET].[dbo].[OSLP] T2 ON T0.[SlpCode] = T2.[SlpCode]  
            WHERE T0.[DocStatus] = 'O' AND T0.[SlpCode] != 15 
        ) W0
        ORDER BY W0.[DocDate],W0.[DocTime]";
    $RST = DBConnect("SAP")->query(SQLtoHANA($SQL))->fetchAll(PDO::FETCH_ASSOC);
    $inval['SoOutstan'] = "";
    if($RST) {
        $RST = SendTHData($RST);
        foreach($RST as $k=>$Data) {
            if ($Data['Printed'] == 'Y'){
                $printed = " <span  style='color:#765905'>[P]</span> ";
            }else{
                $printed = " <span  style='color:red'>[N]</span>";
            }
            $Time = "-";
            switch(strlen($Data['CreateTS'])) {
                case 1: $Time = "00:00:0".$Data['CreateTS']; break; 
                case 2: $Time = "00:00:".$Data['CreateTS']; break; 
                case 3: $Time = "00:0".substr($Data['CreateTS'],0,1).":".substr($Data['CreateTS'],1,2); break; 
                case 4: $Time = "00:".substr($Data['CreateTS'],0,2).":".substr($Data['CreateTS'],2,2); break; 
                case 5: $Time = "0".substr($Data['CreateTS'],0,1).":".substr($Data['CreateTS'],1,2).":".substr($Data['CreateTS'],3,2); break; 
                case 6: $Time = substr($Data['CreateTS'],0,2).":".substr($Data['CreateTS'],2,2).":".substr($Data['CreateTS'],4,2); break; 
            }
            $RST[$k]['No'] = ($k+1);
            $RST[$k]['Company'] = ($Data['Company'] == 'SPING') ? "<img src='../assets/images/logo_shawping.png' style='width: 25px;'> Shaw Ping" : "<img src='../assets/images/logo_shawpet.png' style='width: 30px; border-radius: 50%;'> Shaw Pet";
            $RST[$k]['SO_DocNum'] = "<a href='javascript:void(0);' onclick='ViewDoc(".$Data['SODocEntry'].",\"".$Data['Company']."\")'>".$Data['SO_DocNum']."</a> ".$printed."<br/><small style='font-size: 10px;'>".date("d/m/Y", strtotime($Data['CreateDate']))." เวลา ".$Time."</small>";
            $RST[$k]['DocDate'] = date("d/m/Y", strtotime($Data['DocDate']));
            $RST[$k]['DocTotal'] = number_format($Data['DocTotal'],2);
        }
        $inval['SoOutstan'] = $RST;
    }
}

if($_GET['p'] == 'ViewDoc') {
    $DocEntry = $_POST['DocEntry'];
    $Type = $_POST['Type'];
    if ($_POST['DataSite'] == 'SPING'){
        $DataSite = '[SBO_GOLIVE_SHAWPING]';
        $SiteID = 0;
    }else{
        $DataSite = '[SBO_GOLIVE_SHAWPET]';
        $SiteID = 1;
    }
    
    // รายการสินค้า
        $SQL1 = 
            "SELECT
                ISNULL(T3.[BeginStr]+CAST(T0.[DocNum] AS VARCHAR),T0.[NumAtCard]) AS [DocNum],
                CONCAT(T0.[CardCode], ' | ', T0.[CardName]) AS CardName, T0.[LicTradNum], T0.[DocDate], T0.[DocDueDate], T1.[PymntGroup],
                CONCAT(T0.[PayToCode], ' ', T0.[Address]) AS [PaytoAddr],  CONCAT(T0.[ShipToCode], ' ', T0.[Address2]) AS [ShiptoAddr],
                T2.[SlpName], T0.[NumAtCard], T0.[Comments], T0.[CANCELED], T0.[DocTotal], T0.[DiscPrcnt], T0.[DiscSum], T0.[VatSum],
                T4.[VisOrder], T4.[ItemCode], T4.[CodeBars], T4.[Dscription], T4.[WhsCode], T4.[Quantity], T4.[unitMsr],
                T4.[PriceBefDi], T4.[U_DiscP1], T4.[U_DiscP2], T4.[U_DiscP3], T4.[U_DiscP4], T4.[U_DiscP5], T4.[PriceAfVAT], T4.[LineTotal], T4.[LineStatus],
                T0.DocStatus,(SELECT W0.[OnHand]-W0.[IsCommited] FROM OITW W0 WHERE W0.[ItemCode] = T4.[ItemCode] AND W0.[WhsCode] = T4.[WhsCode]) AS [OnHand]
            FROM $DataSite.[dbo].$Type T0
            LEFT JOIN $DataSite.[dbo].OCTG T1 ON T0.[GroupNum] = T1.[GroupNum]
            LEFT JOIN $DataSite.[dbo].OSLP T2 ON T0.[SlpCode] = T2.[SlpCode]
            LEFT JOIN $DataSite.[dbo].NNM1 T3 ON T0.[Series] = T3.[Series]
            LEFT JOIN $DataSite.[dbo].".substr($Type, 1)."1 T4 ON T0.[DocEntry] = T4.[DocEntry]
            WHERE T0.[DocEntry] = $DocEntry";
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
array_push($JSON,$inval);
echo json_encode($JSON);
?>