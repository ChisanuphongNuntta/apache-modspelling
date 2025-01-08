<?php session_start();
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
require_once("../../core/functions.core.php");
$JSON  = array();
$inval = array();
$SiteID = $_SESSION['SITE']['site_id'];
$UKEY = $_SESSION['UKEY'];
$LVCLASS = $_SESSION['LVCLASS'];
$LvCode = $_SESSION['LVCODE'];
$DeptCode = $_SESSION['DEPTCODE'];
$EmpCode = $_SESSION['EMPCODE'];

if($_GET['p'] == 'ChartSaletarget') {
    $Show = "N";
    $SaleSOMonth = 0;
    $SaleMonth = 0;
    $SaleTarget = 0;
    $SalePer = 0;
    $SQLware = " ";
    $EmpCodeList = " ";
    $SlpList = " ";
    $TeamCode = "TEAM1";
    $SlpCode = " ";
    
    switch(substr($LvCode, 0, 1)) {
        case "I": 
        case "A": 
        case "C": 
        case "M": 
            $Show = "Y";
        break;
    }
    
    switch($DeptCode){
        case 'DP000' :
        case 'DP001' :
        case 'DP004' :
            $Show = "Y";
            break;
        case 'DP002' :
        case 'DP003' :
            $Show = "Y";
            switch($LvCode){
                case 'M0001' :
                case 'M0002' :
                case 'S0006' :
                    $SQL2 = "SELECT T0.EmpCode
                                    FROM users T0
                                    LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode
                                    WHERE T0.UserStatus = 'A' AND EmpCode IS NOT NULL AND T1.DeptCode = '$DeptCode'";
                    $RST2 = DBConnect("APP")->query($SQL2)->fetchAll(PDO::FETCH_ASSOC);
                    $EmpCodeList = "('";
                    foreach($RST2 as $k=>$data) {
                        $EmpCodeList .= $RST2[$k]['EmpCode']."','";
                    }
                    $EmpCodeList = substr($EmpCodeList, 0, -2).")";
                    $SQL3 = "SELECT SalesPrson FROM OHEM WHERE ExtEmpNo IN ".$EmpCodeList;       
                    $RST3 = DBConnect("SAP")->query(SQLtoHANA($SQL3))->fetchAll();
                    
                    if($RST3) {
                        $SlpList = "(";
                        foreach($RST3 as $key => $data) {
                            $SlpList .= $data['SalesPrson'].",";
                        }
                    }
                    $SlpList = substr($SlpList, 0, -1).")";
                    $SQLware = " AND T0.[SlpCode] IN ".$SlpList;
                    break;
                case 'S0002' :
                case 'S0005' :
                    if ($LvCode == 'S0005'){
                        $TeamCode = 'TEAM2';
                    }
                    $SQL2 = "SELECT T0.EmpCode
                                    FROM users T0
                                    LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode
                                    WHERE T0.UserStatus = 'A' AND EmpCode IS NOT NULL AND T1.DeptCode = '$DeptCode' AND T1.TeamCode = '$TeamCode'";
                    $RST2 = DBConnect("APP")->query($SQL2)->fetchAll(PDO::FETCH_ASSOC);
                    $EmpCodeList = "('";
                    foreach($RST2 as $k=>$data) {
                        $EmpCodeList .= $RST2[$k]['EmpCode']."','";
                    }
                    $EmpCodeList = substr($EmpCodeList, 0, -2).")";
                    $SQL3 = "SELECT SalesPrson FROM OHEM WHERE ExtEmpNo IN ".$EmpCodeList;  
                    $RST3 = DBConnect("SAP")->query(SQLtoHANA($SQL3))->fetchAll();
                    
                    if($RST3) {
                        $SlpList = "(";
                        foreach($RST3 as $key => $data) {
                            $SlpList .= $data['SalesPrson'].",";
                        }
                    }
                    $SlpList = substr($SlpList, 0, -1).")";
                    $SQLware = " AND T0.[SlpCode] IN ".$SlpList; 
                    
                    $SQLware = "  AND T0.[SlpCode] IN ".$SlpList;
                    break;
                default:
                    $SQL3 = "SELECT SalesPrson FROM OHEM WHERE ExtEmpNo = '$EmpCode'";
                    $RST3 = DBConnect("SAP")->query(SQLtoHANA($SQL3))->fetchAll();
                    
                    if($RST3) {
                        $SlpList = "(";
                        foreach($RST3 as $key => $data) {
                            $SlpCode .= $data['SalesPrson'];
                        }
                    }
                    //$SlpList = substr($SlpList, 0, -1).")";
                    $SQLware = " AND T0.[SlpCode] = ".$SlpCode;
                break;
            }
            break;
        default :
            $Show = "N";
        break;   
    } 
//echo $EmpCode;
    if($Show == "Y") {
        $SQL1 =
            "SELECT SUM(B0.[DocTotal]) AS [DocTotal] FROM (
                SELECT A0.[CardCode], A1.[CardName], SUM(A0.[DocTotal]) AS [DocTotal] FROM (
                    SELECT
                        T0.[CardCode], T0.[DocEntry], ((T0.[DpmAmnt])+(T0.[DocTotal]-T0.[VatSum])) AS [DocTotal]
                    FROM OINV T0
                    LEFT JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode]
                    WHERE (YEAR(T0.[DocDate]) = YEAR(GETDATE()) AND MONTH(T0.[DocDate]) = MONTH(GETDATE())) AND T0.[CANCELED] = 'N' $SQLware
                    UNION ALL
                    SELECT DISTINCT
                        T0.[CardCode], T0.[DocEntry], -((T0.[DpmAmnt])+(T0.[DocTotal]-T0.[VatSum])) AS [DocTotal]
                    FROM ORIN T0
                    LEFT JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode]
                    LEFT JOIN RIN1 T2 ON T0.[DocEntry] = T2.[DocEntry]
                    WHERE (YEAR(T0.[DocDate]) = YEAR(GETDATE()) AND MONTH(T0.[DocDate]) = MONTH(GETDATE())) AND T0.[CANCELED] = 'N' AND T2.[BaseType] != 203 $SQLware
                ) A0 
            LEFT JOIN OCRD A1 ON A0.[CardCode] = A1.[CardCode]
            GROUP BY A0.[CardCode], A1.[CardName]
            ) B0";
            //echo $SQL1;
        // $SQL1 = 
        //     "SELECT SUM(T0.[DocTotal]-T0.[VatSum]) AS [DocTotal] 
        //     FROM OINV T0
        //     LEFT JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode]
        //     WHERE YEAR(T0.DocDate)=YEAR(GETDATE()) AND MONTH(T0.DocDate)= MONTH(GETDATE()) AND T0.[CANCELED] = 'N' $SQLware ";
         //   echo $SQL1;

        $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll(PDO::FETCH_ASSOC);
        $SaleMonth = ($RST1) ? $RST1[0]['DocTotal'] : 0;

        $SQL3 = "SELECT SUM(T0.[LineTotal]) AS [DocTotal] 
                 FROM RDR1 T0
                 LEFT JOIN ORDR T1 ON T0.[DocEntry] = T1.[DocEntry]
                 LEFT JOIN OHEM T2 ON T1.[SlpCode] = T2.[SalesPrson]
                 WHERE T0.[LineStatus] = 'O' AND (YEAR(T0.[DocDate]) = YEAR(GETDATE()) AND MONTH(T0.[DocDate]) = MONTH(GETDATE())) ".str_replace('T0','T1',$SQLware);
        $RST3 = DBConnect("SAP")->query(SQLtoHANA($SQL3))->fetchAll(PDO::FETCH_ASSOC);   
        $SaleSOMonth = ($RST3) ? $RST3[0]['DocTotal'] : 0;

    }

    $inval['SaleSOMonth'] = number_format($SaleSOMonth,2);
    $inval['SaleMonth'] = ($SaleMonth == 0) ? number_format($SaleMonth,2) : "<a href='javascript:void(0);' onclick='GetListMonth();'>".number_format($SaleMonth,2)."</a>";
    $inval['SaleTarget'] = number_format($SaleTarget,2);
    
    $inval['SalePer'] = number_format($SalePer,2);
}

if($_GET['p'] == 'GetAppDoc') {
    
    $SQL1 = 
        "SELECT
            T0.DocEntry, T0.DocDate, T0.DocDueDate, CONCAT(T0.DocType,'-',T0.DocNum) AS 'DocNum', CONCAT(T0.CardCode,' | ',T0.CardName) AS 'CardName', T0.DocTotal, IFNULL(T1.SlpName,'') AS SlpName
        FROM order_header T0
        LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode AND T1.site_id = $SiteID
        LEFT JOIN order_approve T2 ON T0.DocEntry = T2.DocEntry
        WHERE T0.IntStatus = '2' AND (T2.LvClassReq = '".$LvCode."' AND T2.AppResult = '0') AND (IFNULL((SELECT P0.AppResult FROM order_approve P0 WHERE P0.DocEntry = T0.DocEntry AND P0.StepApprove < T2.StepApprove ORDER BY P0.StepApprove DESC LIMIT 1),'Y') = 'Y') AND T0.site_id = $SiteID";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    $Status = "ERR";
    if(!$RST1) {
        $RST1[0]['StatusData'] = "N";
        $RST1[0]['DocDate'] ="ไม่มีเอกสารรอนุมัติ :)";
        $RST1[0]['DocDueDate'] = "";
        $RST1[0]['DocNum'] = "";
        $RST1[0]['CardName'] = "";
        $RST1[0]['DocTotal'] = "";
        $RST1[0]['SlpName'] = "";
    }else{
        $tmp_r = 0;
        foreach($RST1 as $k=>$data) {
            $RST1[$k]['StatusData'] = "Y";
            $RST1[$k]['DocDate'] = date("d/m/Y", strtotime($data['DocDate']));
            $RST1[$k]['DocDueDate'] = date("d/m/Y", strtotime($data['DocDueDate']));
            $RST1[$k]['DocNum'] = "<a href='javascript:void(0);' onclick='Fn_ViewAppDoc(\"".$data['DocEntry']."\");'>".$data['DocNum']."</a>";
            $RST1[$k]['CardName'] = $data['CardName'];
            $RST1[$k]['DocTotal'] = number_format($data['DocTotal'],2);
            $RST1[$k]['SlpName'] = $data['SlpName'];
        }
    }
    $inval['ListAppDoc'] = $RST1;
}

if($_GET['p'] == 'GetCalendar') {
    $Year = date("Y");
    $Month = date("m");
    $SQL1 = 
        "SELECT DAY(T0.PlanStart) AS 'PlanDate'
        FROM routetrip T0
        WHERE (YEAR(T0.PlanStart) = $Year AND MONTH(T0.PlanStart) = $Month) AND (T0.Plan_uKeyCreate = '$UKEY' AND T0.CANCELED = 'N' AND T0.TripStatus != '0')
        ORDER BY T0.PlanStart";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    foreach($RST1 as $k=>$data) {
        $inval[$data['PlanDate']] = $data['PlanDate'];
    }
}

if($_GET['p'] == 'GetDetailPlan') {
    $Date = date('Y')."-".date('m')."-".$_POST['Day'];
    $SQL1 = 
        "SELECT
            T0.TripID, TIME(T0.PlanStart) AS 'S_Time', TIME(T0.PlanEnd) AS 'E_Time', T0.CardCode, T0.CardName, IFNULL(T0.PlanDetail,'') AS 'PlanDetail', 
            IFNULL(T0.ActualStart,'') AS 'ActualStart', IFNULL(T0.PlanLat,'') AS 'PlanLat', IFNULL(T0.PlanLon,'') AS 'PlanLon', T0.TripStatus
        FROM routetrip T0
        WHERE (DATE(T0.PlanStart) = '$Date' AND T0.Plan_uKeyCreate = '$UKEY' AND T0.TripStatus != '0' AND T0.CANCELED = 'N')
        ORDER BY T0.PlanStart, T0.CardCode";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    $inval['DPLAN'] = ($RST1) ? $RST1 : 0;
}

if($_GET['p'] == 'GetListMonth') {
    $Year = date("Y");
    $Month = date("m");
    $SQL1 = 
        "SELECT P0.* 
        FROM (
            SELECT 'OINV' AS [DocType], T2.[BeginStr], T0.[DocNum], T0.[DocDate], CONCAT(T0.[CardCode], ' ', T0.[CardName]) AS [CardName], 
                (T0.[DocTotal]-T0.[VatSum]) AS [DocTotal], T0.[DocEntry], T1.[SlpName]
            FROM OINV T0
            LEFT JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode]
            LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
            WHERE YEAR(T0.DocDate) = $Year AND MONTH(T0.[DocDate]) = $Month AND T0.[CANCELED] = 'N'
            UNION ALL
            SELECT 'ORIN' AS [DocType], T2.[BeginStr], T0.[DocNum], T0.[DocDate], CONCAT(T0.[CardCode], ' ', T0.[CardName]) AS [CardName], 
                -1*(T0.[DocTotal]-T0.[VatSum]) AS [DocTotal], T0.[DocEntry], T1.[SlpName]
            FROM ORIN T0
            LEFT JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode]
            LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
            WHERE YEAR(T0.[DocDate]) = $Year AND MONTH(T0.[DocDate]) = $Month AND T0.[CANCELED] = 'N'
        ) P0
        ORDER BY P0.[CardName],P0.[DocType],P0.[DocDate],P0.[DocNum]";
    $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll(PDO::FETCH_ASSOC);   
    foreach($RST1 as $k=>$data) {
        $RST1[$k]['DocDate'] = date("d/m/Y", strtotime($data['DocDate']));
        $RST1[$k]['CardName'] = SapTH($data['CardName']);
        $RST1[$k]['SlpName'] = SapTH($data['SlpName']);
        $RST1[$k]['DocTotal'] = number_format($data['DocTotal'],2);
    }
    $inval['DataSaleBill'] = $RST1;

    $SQL2 = 
        "SELECT W0.*
        FROM (
            SELECT T0.[DocEntry] AS [SODocEntry], (T1.[BeginStr]+CAST(T0.[DocNum] AS [VarChar])) AS [SO_DocNum], T0.[DocDate],
                T0.[DocTime], CONCAT(T0.[CardCode], ' ', T0.[CardName]) AS [CardName], T2.[SlpName], T0.[DocTotal], T0.[Printed], T0.[CreateDate], T0.[CreateTS]
            FROM ORDR T0
            LEFT JOIN NNM1 T1 ON T0.[Series]  = T1.[Series]
            LEFT JOIN OSLP T2 ON T0.[SlpCode] = T2.[SlpCode]  
            WHERE T0.[DocStatus] = 'O' AND T0.[SlpCode] != 15 AND YEAR(T0.[DocDate]) = $Year AND MONTH(T0.[DocDate]) = $Month
        ) W0
        ORDER BY W0.[DocDate], W0.[DocTime]";
    $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll(PDO::FETCH_ASSOC);   
    foreach($RST2 as $k=>$data) {
        $RST2[$k]['DocDate'] = date("d/m/Y", strtotime($data['DocDate']));
        $RST2[$k]['CardName'] = SapTH($data['CardName']);
        $RST2[$k]['SlpName'] = SapTH($data['SlpName']);
        $RST2[$k]['DocTotal'] = number_format($data['DocTotal'],2);
    }
    $inval['DataOverdueBill'] = $RST2;
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>