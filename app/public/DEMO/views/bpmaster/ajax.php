<?php session_start();
require_once("../../core/functions.core.php");
$JSON  = array();
$inval = array();

if($_GET['p'] == 'GetCardList') {
    $SQL = "SELECT T0.[CardCode], T0.[CardName] FROM OCRD T0 WHERE T0.[CardType] = 'C' ORDER BY T0.[CardCode] ASC";
    $RST = DBConnect("SAP")->query(SQLtoHANA($SQL))->fetchAll();
    $Data = "";
    if(!$RST) {
        $Data = "ERR";
    } else {
        foreach($RST as $RowData) {
            $Data .= "<option value='".$RowData['CardCode']."'>".$RowData['CardCode']." ".SapTH($RowData['CardName'])."</option>";
        }
    }
    $inval['Data'] = $Data;
}

if($_GET['p'] == "GetDetail") {
    if(!isset($_POST['CardCode'])) {
        $inval['Status'] = "ERR";
        $inval['ErrMsg'] = "NO_CUSTOMER_DATA";
    } else {
        $txt_CardCode = $_POST['CardCode'];
        /* Customer Detail Header */
        $SQL1 =
            "SELECT TOP 1
                T0.[CardCode], T0.[CardName], T1.[SlpName], T0.[Balance], T0.[CreditLine],
                T0.[LicTradNum], T2.[PymntGroup], T3.[GroupName],
                REPLACE(T0.[Phone1],'-','') AS [Phone1], REPLACE(T0.[Phone2],'-','') AS [Phone2], REPLACE(T0.[Cellular],'-','') AS [Cellular],
                (T0.[MailAddres]+' '+T0.[MailBlock]+' '+T0.[MailCity]+' '+T0.[MailCounty]+' '+CAST(T0.[MailZipCod] AS VARCHAR)) AS [Address]
            FROM OCRD T0
            LEFT JOIN OSLP T1 ON T0.[SlpCode]   = T1.[SlpCode]
            LEFT JOIN OCTG T2 ON T0.[GroupNum]  = T2.[GroupNum]
            LEFT JOIN OCRG T3 ON T0.[GroupCode] = T3.[GroupCode]
            WHERE T0.[CardCode] = '$txt_CardCode'";
        $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll(PDO::FETCH_ASSOC);
        if(!$RST1) {
            $inval['Status'] = "ERR";
            $inval['ErrMsg'] = "CUSTOMER_NOT_FOUND";
        } else {
            $inval['Status'] = "OK";
            $inval['HEADER'] = SendTHData($RST1)[0];

            /* Sales Amount */
            $this_year = date("Y");
            $prev_year = $this_year - 1;

            for($m = 1; $m <= 12; $m++) {
                $inval['PREV_SALE']['M'.$m] = 0;
                $inval['CURR_SALE']['M'.$m] = 0;
            }

            $SQL1 =
                "SELECT
                    A0.[DocYear],
                    SUM(A0.[M1]) AS [M1], SUM(A0.[M2]) AS [M2], SUM(A0.[M3]) AS [M3],
                    SUM(A0.[M4]) AS [M4], SUM(A0.[M5]) AS [M5], SUM(A0.[M6]) AS [M6],
                    SUM(A0.[M7]) AS [M7], SUM(A0.[M8]) AS [M8], SUM(A0.[M9]) AS [M9],
                    SUM(A0.[M10]) AS [M10], SUM(A0.[M11]) AS [M11], SUM(A0.[M12]) AS [M12]
                FROM (
                    SELECT
                        YEAR(T0.[DocDate]) AS [DocYear],
                        CASE WHEN MONTH(T0.[DocDate]) = 1 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M1],
                        CASE WHEN MONTH(T0.[DocDate]) = 2 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M2],
                        CASE WHEN MONTH(T0.[DocDate]) = 3 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M3],
                        CASE WHEN MONTH(T0.[DocDate]) = 4 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M4],
                        CASE WHEN MONTH(T0.[DocDate]) = 5 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M5],
                        CASE WHEN MONTH(T0.[DocDate]) = 6 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M6],
                        CASE WHEN MONTH(T0.[DocDate]) = 7 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M7],
                        CASE WHEN MONTH(T0.[DocDate]) = 8 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M8],
                        CASE WHEN MONTH(T0.[DocDate]) = 9 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M9],
                        CASE WHEN MONTH(T0.[DocDate]) = 10 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M10],
                        CASE WHEN MONTH(T0.[DocDate]) = 11 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M11],
                        CASE WHEN MONTH(T0.[DocDate]) = 12 THEN SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M12]
                    FROM OINV T0
                    WHERE T0.[CardCode] = '$txt_CardCode' AND YEAR(T0.[DocDate]) BETWEEN $prev_year AND $this_year AND T0.[CANCELED] = 'N'
                    GROUP BY YEAR(T0.[DocDate]), MONTH(T0.[DocDate])
                    UNION ALL
                    SELECT
                        YEAR(T0.[DocDate]) AS [DocYear],
                        CASE WHEN MONTH(T0.[DocDate]) = 1 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M1],
                        CASE WHEN MONTH(T0.[DocDate]) = 2 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M2],
                        CASE WHEN MONTH(T0.[DocDate]) = 3 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M3],
                        CASE WHEN MONTH(T0.[DocDate]) = 4 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M4],
                        CASE WHEN MONTH(T0.[DocDate]) = 5 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M5],
                        CASE WHEN MONTH(T0.[DocDate]) = 6 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M6],
                        CASE WHEN MONTH(T0.[DocDate]) = 7 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M7],
                        CASE WHEN MONTH(T0.[DocDate]) = 8 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M8],
                        CASE WHEN MONTH(T0.[DocDate]) = 9 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M9],
                        CASE WHEN MONTH(T0.[DocDate]) = 10 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M10],
                        CASE WHEN MONTH(T0.[DocDate]) = 11 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M11],
                        CASE WHEN MONTH(T0.[DocDate]) = 12 THEN -SUM(T0.[DocTotal] - T0.[VatSum]) ELSE 0 END AS [M12]
                    FROM ORIN T0
                    WHERE T0.[CardCode] = '$txt_CardCode' AND YEAR(T0.[DocDate]) BETWEEN $prev_year AND $this_year AND T0.[CANCELED] = 'N'
                    GROUP BY YEAR(T0.[DocDate]), MONTH(T0.[DocDate])
                ) A0
                GROUP BY A0.[DocYear]";
            $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll();
            foreach($RST1 as $key=>$data) {
                $Prefix = ($data['DocYear'] == $prev_year) ? "PREV_SALE" : "CURR_SALE" ;
                for($m = 1; $m <= 12; $m++) {
                    $inval[$Prefix]['M'.$m] = $data['M'.$m];
                }
            }

            /* OVERDUE */
            $SQL1 =
                "SELECT
                    T0.[DocEntry], 'OINV' AS [DocType], (ISNULL(T2.[BeginStr],'IV-')+CAST(T0.[DocNum] AS VARCHAR)) AS [DocNum], T0.[DocDate], T0.[DocDueDate], T0.[DocTotal], T0.[PaidToDate], T0.[CardCode], T0.[CardName],
                    DATEDIFF(day,T0.[DocDueDate],GETDATE()) AS [Aging]
                FROM OINV T0 
                LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
                LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
                WHERE (T0.[DocStatus] = 'O' AND T0.[CANCELED] = 'N' AND T0.[DocDueDate] <= GETDATE()) AND (T0.[CardCode] = '$txt_CardCode' OR T1.[FatherCard] = '$txt_CardCode')
                UNION ALL
                SELECT
                    T0.[DocEntry], 'ORIN' AS [DocType], (ISNULL(T2.[BeginStr],'CN-')+CAST(T0.[DocNum] AS VARCHAR)) AS [DocNum], T0.[DocDate], T0.[DocDueDate], -T0.[DocTotal], -T0.[PaidToDate], T0.[CardCode], T0.[CardName],
                    DATEDIFF(day,T0.[DocDueDate],GETDATE()) AS [Aging]
                FROM ORIN T0 
                LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
                LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
                WHERE (T0.[DocStatus] = 'O' AND T0.[CANCELED] = 'N' AND T0.[DocDueDate] <= GETDATE()) AND (T0.[CardCode] = '$txt_CardCode' OR T1.[FatherCard] = '$txt_CardCode')
                ORDER BY [DocType], T0.[DocDueDate]";
            $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll(PDO::FETCH_ASSOC);
            if(!$RST1) {
                $inval['OVERDUE'] = array();
            } else {
                $inval['OVERDUE'] = SendTHData($RST1);
            }

        }
    }
}

if($_GET['p'] == 'GetHisMeeting') {
    $CardCode = $_POST['CardCode'];
    $SQL1 = 
        "SELECT
            T0.TripID, T0.ActualStart, T0.PlanDetail, T0.ActualDetail, CONCAT(T1.TH_uFirstName,' (',T1.uNickName,')') AS 'SlpName'
        FROM routetrip T0
        LEFT JOIN users T1 ON T0.Actual_uKeyCreate = T1.uKey
        WHERE T0.CardCode = '$CardCode' AND (T0.CANCELED = 'N' AND T0.TripStatus != '0')
        ORDER BY T0.TripID DESC";
    $RST1 = DBConnect("APP")->query(SQLtoHANA($SQL1))->fetchAll(PDO::FETCH_ASSOC);
    if(!$RST1) {
        $inval['HisMeeting'] = array();
    } else {
        foreach($RST1 as $k=>$data) {
            $RST1[$k]['View'] = "<a href='javascript:void(0);' onclick='CheckInReport(".$data['TripID'].")'><i class='fas fa-file-alt fa-fw fa-lg'></i></a>";
            $RST1[$k]['ActualStart'] = date("d/m/Y", strtotime($data['ActualStart']))." เวลา ".date("H:m", strtotime($data['ActualStart']))." น.";
        }
        $inval['HisMeeting'] = $RST1;
    }
}

if($_GET['p'] == 'HisItem10') {
    $Data = "";

    for($d = 1; $d <= 10; $d++) {
        $Data .= "
            <tr>
                <td class='text-center'>SR-".rand()."</td>
                <td class='text-center'>15/08/2023</td>
                <td>B55.9-มาริษา สามารถกุล (ษา)</td>
                <td class='text-end'>-55,594.39</td>
            </tr>";
    }

    $inval['Data'] = $Data;
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>