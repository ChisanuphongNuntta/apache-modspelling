<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();

if($_GET['p'] == 'GetSaleKPI') {
    $Year = $_POST['Year'];
    $Month = $_POST['Month'];

    $SQL = 
        "SELECT 
            T0.Ukey,T1.DeptCode,T1.LvCode,T1.TeamCode,T0.EmpCode,
            CASE WHEN T0.uNickName IS NULL 
                THEN CONCAT(T0.TH_uFirstName,' ',T0.TH_uLastName) 
                ELSE CONCAT(T0.TH_uFirstName,' ',T0.TH_uLastName,' (',T0.uNickName,')') 
            END AS SlpName
        FROM users T0
        LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode
        WHERE T1.DeptCode IN ('DP001','DP002','DP003')  AND T0.UserStatus = 'A'
        ORDER BY T1.DeptCode,T1.TeamCode";
    $RST = DBConnect("APP")->query($SQL)->fetchAll(PDO::FETCH_ASSOC);


    // $RST = DBConnect("SAP")->query(SQLtoHANA($SQL))->fetchAll(PDO::FETCH_ASSOC);
    if($RST) {
        foreach($RST as $k=>$data) {
            $EmpCode = $data['EmpCode'];
            $SQL2 =
            "SELECT SUM(B0.[DocTotal]) AS [DocTotal] FROM (
                SELECT A0.[CardCode], A1.[CardName], SUM(A0.[DocTotal]) AS [DocTotal] FROM (
                    SELECT
                        T0.[CardCode], T0.[DocEntry], ((T0.[DpmAmnt])+(T0.[DocTotal]-T0.[VatSum])) AS [DocTotal]
                    FROM OINV T0
                    LEFT JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode]
                    LEFT JOIN OHEM T2 ON T1.[SlpCode] = T2.[SalesPrson]
                    WHERE (YEAR(T0.[DocDate]) = YEAR(GETDATE()) AND MONTH(T0.[DocDate]) = MONTH(GETDATE())) AND T0.[CANCELED] = 'N'  AND T2.[ExtEmpNo] = '$EmpCode'
                    UNION ALL
                    SELECT DISTINCT
                        T0.[CardCode], T0.[DocEntry], -((T0.[DpmAmnt])+(T0.[DocTotal]-T0.[VatSum])) AS [DocTotal]
                    FROM ORIN T0
                    LEFT JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode]
                    LEFT JOIN RIN1 T2 ON T0.[DocEntry] = T2.[DocEntry]
                    LEFT JOIN OHEM T3 ON T1.[SlpCode] = T3.[SalesPrson]
                    WHERE (YEAR(T0.[DocDate]) = YEAR(GETDATE()) AND MONTH(T0.[DocDate]) = MONTH(GETDATE())) AND T0.[CANCELED] = 'N' AND T2.[BaseType] != 203 AND T3.[ExtEmpNo] = '$EmpCode'
                ) A0 
            LEFT JOIN OCRD A1 ON A0.[CardCode] = A1.[CardCode]
            GROUP BY A0.[CardCode], A1.[CardName]
            ) B0";
            //echo $SQL2."<br>";
            $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll(PDO::FETCH_ASSOC);
            $SQL3 = "SELECT SUM(T0.[LineTotal]) AS [DocTotal] 
                     FROM RDR1 T0
                     LEFT JOIN ORDR T1 ON T0.[DocEntry] = T1.[DocEntry]
                     LEFT JOIN OHEM T2 ON T1.[SlpCode] = T2.[SalesPrson]
                     WHERE T0.[LineStatus] = 'O' AND (YEAR(T0.[DocDate]) = YEAR(GETDATE()) AND MONTH(T0.[DocDate]) = MONTH(GETDATE())) AND T2.[ExtEmpNo] = '$EmpCode'";
            $RST3 = DBConnect("SAP")->query(SQLtoHANA($SQL3))->fetchAll(PDO::FETCH_ASSOC);         

            $RST[$k]['SaleTarget'] = 0;
            $RST[$k]['wApp'] = "-";
            $RST[$k]['SO'] = $RST3[0]['DocTotal'];
            $RST[$k]['DocTotal'] =  $RST2[0]['DocTotal'];
            $RST[$k]['Amount'] = 0;
        }
    }else{
        $RST = "";
    }
    $inval['Data'] = $RST;
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>