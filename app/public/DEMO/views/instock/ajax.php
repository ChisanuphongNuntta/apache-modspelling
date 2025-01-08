<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();
$SiteID = $_SESSION['SITE']['site_id'];

if($_GET['p'] == 'GetWhs') {
    $SQL = "SELECT T0.[WhsCode], T0.[WhsName] FROM OWHS T0";
    $RST = DBConnect("SAP")->query(SQLtoHANA($SQL))->fetchAll(PDO::FETCH_ASSOC);
    $inval['DataWhs'] = SendTHData($RST);
}

if($_GET['p'] == 'GetInstock') {
    $WhsCode = "";
    $WhereOnHand = ($_POST['Getzero'] == 'true') ? "T1.[OnHand] > 0" : "";

    $SQL1 = 
        "SELECT T0.[ItemCode], T0.[CodeBars], T0.[ItemName], T0.[SalUnitMsr], SUM(T1.[OnHand]) AS [OnHand], SUM(T1.[IsCommited]) AS [IsCommited], 
            SUM(T1.[OnOrder]) AS [OnOrder],DATEDIFF(MONTH,T0.[LastPurDat],GETDATE()) AS [Aging], SUM(T1.[OnHand]-T1.[IsCommited]+T1.[OnOrder]) AS [Available]
        FROM OITM T0
        LEFT JOIN OITW T1 ON T0.[ItemCode] = T1.[ItemCode]
        GROUP BY T0.[ItemCode],T0.[ItemName],T0.[CodeBars],T0.[SalUnitMsr],DATEDIFF(MONTH,T0.[LastPurDat],GETDATE())
        ORDER BY T0.[ItemCode]";
    $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll(PDO::FETCH_ASSOC);
    //AND T0.[WhsCode] IN ('FG-SP','ONLINE')
    if($RST1) {
        $WhsCode2 = "";
        $SQL2 = 
            "SELECT T0.ItemCode, SUM(T0.Quantity) AS Quantity
            FROM order_detail T0
            LEFT JOIN order_header T1 ON T0.DocEntry = T1.DocEntry
            WHERE T1.CANCELED = 'N' AND T1.DocStatus = 'P' AND T1.site_id = $SiteID $WhsCode2
            GROUP BY T0.ItemCode";
        $RST2 = DBConnect("APP")->query($SQL2)->fetchAll(PDO::FETCH_ASSOC);
        foreach($RST2 as $k=>$data) {
            $ItemOrder[$data['ItemCode']] = $data['Quantity'];
        }

        foreach($RST1 as $k=>$data2) {
            $Pending = (isset($ItemOrder[$data2['ItemCode']])) ? $ItemOrder[$data2['ItemCode']]['Quantity'] : 0;
            $ForUse = $data2['OnHand'] - ($data2['IsCommited'] + $Pending);
            $RST1[$k]['OnHand'] = number_format($data2['OnHand'],0);
            if((($data2['OnHand']+$data2['OnOrder']) != $data2['Available'])) {
                $RST1[$k]['IsCommited'] = "<a href='javascript:void(0);' onclick='DetailAvailable(\"".$data2['ItemCode']."\");'>".number_format($data2['IsCommited'],0)."</a>";
            }else{
                $RST1[$k]['IsCommited'] = number_format($data2['IsCommited'],0);
            }
            if($data2['OnOrder'] > 0) {
                $RST1[$k]['OnOrder'] = "<a href='javascript:void(0);' onclick='ViewOnOrder(\"".$data2['ItemCode']."\");'>".number_format($data2['OnOrder'],0)."</a>";
            }else{
                $RST1[$k]['OnOrder'] = number_format($data2['OnOrder'],0);
            }
            $RST1[$k]['Pending'] = number_format($Pending,0);
            $RST1[$k]['ForUse'] = ($ForUse < 0) ? "<span class='text-danger fw-bolder'>".number_format($ForUse,0)."</span>" : "<span class='fw-bolder'>".number_format($ForUse,0)."</span>";
        }
        $inval['Instock'] = SendTHData($RST1);
    }
}

if($_GET['p'] == 'DetailAvailable') {
    $ItemCode = $_POST['ItemCode'];
    $WhsCode = ($_POST['WhsCode'] == 'ALL') ? "AND T0.[WhsCode] IN ('FG-SP','ONLINE','FG-SPET')" : "AND T0.[WhsCode] = '".$_POST['WhsCode']."'";

    $SQL = 
        "SELECT (T2.[BeginStr]+CAST(T1.DocNum AS VARCHAR)) AS [DocNum],T1.[DocDate],T1.[DocDueDate],T1.[CardCode],T1.[CardName],T3.[SlpName],T0.[WhsCode],T0.[Quantity], T1.[DocEntry] 
        FROM RDR1 T0
            LEFT JOIN ORDR T1 ON T0.[DocEntry] = T1.[DocEntry]
            LEFT JOIN NNM1 T2 ON T1.[Series] = T2.[Series]
            LEFT JOIN OSLP T3 ON T1.[SlpCode] = T3.[SlpCode]
        WHERE T0.[LineStatus] = 'O' AND T1.[DocStatus] = 'O' AND T1.[CANCELED] = 'N' AND T0.[ItemCode] = '$ItemCode' $WhsCode";
    $RST = DBConnect("SAP")->query(SQLtoHANA($SQL))->fetchAll(PDO::FETCH_ASSOC);
    foreach($RST as $k=>$data) {
        $RST[$k]['CardName'] = SapTH($data['CardName']);
        $RST[$k]['SlpName'] = SapTH($data['SlpName']);
    }
    $inval['DetailAvailable'] = $RST;
}

if($_GET['p'] == 'ViewOnOrder') {
    $ItemCode = $_POST['ItemCode'];

    $SQL = 
        "SELECT
            T0.[DocEntry],
            T1.[ItemCode], T1.[Dscription], T0.[DocDate], T0.[DocDueDate], T2.[BeginStr]+CAST(T0.[DocNum] AS VARCHAR) AS [DocNum],
            T1.[Quantity], T1.[unitMsr], T1.[WhsCode]
        FROM OPOR T0
        LEFT JOIN POR1 T1 ON T0.[DocEntry] = T1.[DocEntry]
        LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
        LEFT JOIN OITM T3 ON T1.[ItemCode] = T3.[ItemCode]
        WHERE T1.[ItemCode] = '$ItemCode' AND T0.[DocStatus] = 'O' AND T1.[LineStatus] = 'O' AND T0.[CANCELED] = 'N' AND T1.[WhsCode] IN ('FG-SP','ONLINE','FG-SPET')
        ORDER BY T0.[DocDueDate] ASC";
    $RST = DBConnect("SAP")->query(SQLtoHANA($SQL))->fetchAll(PDO::FETCH_ASSOC);
    foreach($RST as $k=>$data) {
        $RST[$k]['Dscription'] = SapTH($data['Dscription']);
        $RST[$k]['unitMsr'] = SapTH($data['unitMsr']);
    }
    $inval['DataOnOrder'] = $RST;
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>