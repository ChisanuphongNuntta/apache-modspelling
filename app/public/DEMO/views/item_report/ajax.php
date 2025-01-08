<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();

if($_GET['p'] == 'GetItemProduct') {
    $Year = $_POST['Year'];
    $Month = $_POST['Month'];

    $SQL = 
        "SELECT 
            (T6.[BeginStr]+CAST(T4.[DocNum] AS [VarChar])) AS [SO_DocNum], T4.[DocDate] AS [SO_DocDate], T4.[CardCode], T4.[CardName],T13.[CardFName],T14.[Descr] AS [Region],T15.[GroupName],
            T5.[SlpName],T0.[ItemCode],T0.[Dscription] AS [ItemName], T2.[Name] AS [BRAND], T3.[Name] AS [SUB_BRAND], T11.[Descr] AS [Pet],
            T12.[Descr] AS [Catagory], T0.[Quantity], T0.[unitMsr], T0.[Price], T0.[DiscPrcnt], T0.[LineTotal],
            (T10.[BeginStr]+CAST(T9.[DocNum] AS [VarChar])) AS [IV_DocNum], T9.[DocDate] AS [IV_DocDate], T9.[DocDueDate] AS [IVDueDate], 
            T8.[Quantity] AS [IVQty], T8.[Price] AS [IVPrice], T8.[DiscPrcnt] AS [IVDiscount], T8.[LineTotal] AS [IVLineTotal] 
        FROM RDR1 T0
        LEFT JOIN OITM T1 ON T0.[ItemCode] = T1.[ItemCode]
        LEFT JOIN [dbo].[@BRANDS] T2 ON T1.[U_BRAND] = T2.[Code]
        LEFT JOIN [dbo].[@SUBBRAND] T3 ON T1.[U_SUB_BRAND] = T3.[Code]
        LEFT JOIN ORDR T4 ON T0.[DocEntry] = T4.[DocEntry]
        LEFT JOIN OSLP T5 ON T4.[SlpCode] = T5.[SlpCode]
        LEFT JOIN NNM1 T6 ON T6.[Series] = T4.[Series]
        LEFT JOIN DLN1  T7 ON T7.[DocEntry]  = T0.[TrgetEntry] AND T7.[BaseLine] = T0.[LineNum] AND T0.[TargetType] = 15
        LEFT JOIN INV1 T8 ON T8.[DocEntry] = T7.[TrgetEntry] AND T8.[BaseLine] = T7.[LineNum]   
        LEFT JOIN OINV T9 ON T8.[DocEntry] = T9.[DocEntry]
        LEFT JOIN NNM1 T10 ON T9.[Series] = T10.[Series]
        LEFT JOIN UFD1 T11 ON T1.[U_PETS] = T11.[FldValue] AND T11.[TableID] = 'OITM' AND T11.[FieldID] = 3
        LEFT JOIN UFD1 T12 ON T1.[U_Catagory] = T12.[FldValue] AND T12.[TableID] = 'OITM' AND T12.[FieldID] = 4
        LEFT JOIN OCRD T13 ON T4.CardCode = T13.CardCode
        LEFT JOIN UFD1 T14 ON T13.[U_REGION] = T14.[FldValue] AND T14.[TableID] = 'OCRD' AND T14.[FieldID] = 2
        LEFT JOIN OCRG T15 ON T13.[GroupCode] = T15.[GroupCode]
        WHERE (YEAR(T4.[DocDate]) = $Year AND MONTH(T4.[DocDate]) = $Month) AND T4.[CANCELED] = 'N'
        ORDER BY T4.[DocNum],T0.[VisOrder]";
    $RST = DBConnect("SAP")->query(SQLtoHANA($SQL))->fetchAll();
    if($RST) {
        $RST = SendTHData($RST);
        foreach($RST as $k=>$Data) {
            $inval[$k]['No'] = ($k+1);
            $inval[$k]['CardName'] = $Data['CardCode']." | ".$Data['CardName']."<br><small>พนักงานขาย: ".$Data['SlpName']."</small>";
            $inval[$k]['Excel_CardCode'] = $Data['CardCode'];
            $inval[$k]['Excel_CardName'] = $Data['CardName'];
            $inval[$k]['Excel_SlpName'] = $Data['SlpName'];
            $inval[$k]['Excel_ItemCode'] = $Data['ItemCode'];
            $inval[$k]['Excel_ItemName'] = $Data['ItemName'];
            $inval[$k]['ItemName'] = $Data['ItemName']."<br><small>รหัสสินค้า: ".$Data['ItemCode']."</small>";
            $inval[$k]['Brand'] = $Data['BRAND'];
            $inval[$k]['SubBrand'] = $Data['SUB_BRAND'];
            $inval[$k]['Pet'] = $Data['Pet'];
            $inval[$k]['Catagory'] = $Data['Catagory'];

            $inval[$k]['CardFName'] = $Data['CardFName'];
            $inval[$k]['Region'] = $Data['Region'];
            $inval[$k]['GroupName'] = $Data['GroupName'];

            $inval[$k]['SO_DocNum'] = $Data['SO_DocNum'];
            $inval[$k]['SO_DocDate'] = date("d/m/Y", strtotime($Data['SO_DocDate']));
            $inval[$k]['SO_Quantity'] = number_format(floatval($Data['Quantity']),0);
            $inval[$k]['SO_Unit'] = $Data['unitMsr'];
            $inval[$k]['SO_Price'] = number_format(floatval($Data['Price']),2);
            $inval[$k]['SO_DisPrcnt'] = number_format(floatval($Data['DiscPrcnt']),2);
            $inval[$k]['SO_LineTotal'] = number_format(floatval($Data['LineTotal']),2);

            $inval[$k]['IV_DocNum'] = $Data['IV_DocNum'];
            $inval[$k]['IV_DocDate'] = ($Data['IV_DocDate'] != "") ? date("d/m/Y", strtotime($Data['IV_DocDate'])) : "";
            $inval[$k]['IV_DocDueDate'] = ($Data['IVDueDate'] != "") ? date("d/m/Y", strtotime($Data['IVDueDate'])) : ""; 
            $inval[$k]['IV_Quantity'] = ($Data['IVQty'] != "") ? number_format(floatval($Data['IVQty']),0) : "";
            $inval[$k]['IV_Unit'] = ($Data['IV_DocNum'] != "") ? $Data['unitMsr'] : "";
            $inval[$k]['IV_Price'] = ($Data['IVPrice'] != "") ? number_format(floatval($Data['IVPrice']),2) : "";
            $inval[$k]['IV_DisPrcnt'] = ($Data['IVDiscount'] != "") ? number_format(floatval($Data['IVDiscount']),2) : "";
            $inval[$k]['IV_LineTotal'] = ($Data['IVLineTotal'] != "") ? number_format(floatval($Data['IVLineTotal']),2) : "";
        }
    }
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>