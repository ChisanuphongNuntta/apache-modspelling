<?php
include('../../core/config.core.php');
include('../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();

if($_SESSION['UserName'] == NULL ){
	echo '<script>window.location="../ceo"</script>';
    exit;
}

$resultArray = array();
$arrCol = array();

if($_GET['p'] == "SaleKPI") {
    $filt_y = $_POST['y'];
    $filt_m = $_POST['m'];
    $filt_t = $_POST['t'];

    if($filt_y <= 2022) {
        $SlctSlpCode = "SlpCode8";
    } else {
        $SlctSlpCode = "SlpCode";
    }

    switch($filt_t) {
        case "ALL":
            /* TARGET BY MONTH */
            $arrCol['EXP']['ACT']['YEAR'] = 0;
            $TargetSQL = 
                "SELECT
                    A0.TeamCode, GROUP_CONCAT(A0.SlpCode) AS 'SlpCode',
                    SUM(A0.M01) AS 'M01', SUM(A0.M02) AS 'M02', SUM(A0.M03) AS 'M03',
                    SUM(A0.M04) AS 'M04', SUM(A0.M05) AS 'M05', SUM(A0.M06) AS 'M06',
                    SUM(A0.M07) AS 'M07', SUM(A0.M08) AS 'M08', SUM(A0.M09) AS 'M09',
                    SUM(A0.M10) AS 'M10', SUM(A0.M11) AS 'M11', SUM(A0.M12) AS 'M12',
                    SUM(A0.YEAR) AS 'YEAR'
                FROM (
                    SELECT 
                        CASE
                            WHEN T0.TeamCode IN ('MT100')  THEN 'MT1'
                            WHEN T0.TeamCode IN ('EXP101') THEN 'EXP'
                            WHEN T0.TeamCode IN ('MT200')  THEN 'MT2'
                            WHEN T0.TeamCode IN ('TT101')  THEN 'TT1'
                            WHEN T0.TeamCode IN ('TT201','TT202','TT203') THEN 'TT2'
                        ELSE T0.TeamCode END AS 'TeamCode',
                        (SELECT GROUP_CONCAT(P0.$SlctSlpCode) FROM OSLP P0 WHERE P0.TeamCode = T0.TeamCode) AS 'SlpCode',
                        T0.TeamCode AS 'SubTeam',
                        SUM(T0.M01) AS 'M01', SUM(T0.M02) AS 'M02', SUM(T0.M03) AS 'M03',
                        SUM(T0.M04) AS 'M04', SUM(T0.M05) AS 'M05', SUM(T0.M06) AS 'M06',
                        SUM(T0.M07) AS 'M07', SUM(T0.M08) AS 'M08', SUM(T0.M09) AS 'M09',
                        SUM(T0.M10) AS 'M10', SUM(T0.M11) AS 'M11', SUM(T0.M12) AS 'M12',
                        SUM(T0.M01+T0.M02+T0.M03+T0.M04+T0.M05+T0.M06+T0.M07+T0.M08+T0.M09+T0.M10+T0.M11+T0.M12) AS 'YEAR'
                    FROM saletarget T0 
                    WHERE
                        T0.DocYear = $filt_y AND T0.DocStatus = 'A'
                    GROUP BY T0.TeamCode
                ) A0 GROUP BY A0.TeamCode";
            $TargetQRY = MySQLSelectX($TargetSQL);
            $SapSlpCode = "";
            $visorder = 0;
            while($TargetRST = mysqli_fetch_array($TargetQRY)) {
                $TeamCode = $TargetRST['TeamCode'];
                $arrCol[$TeamCode]['TeamCode'] = $TeamCode;
                for($i = 1; $i <= 12; $i++) {
                    if($i <= 9) {
                        $Suffix = "M0".$i;
                    } else {
                        $Suffix = "M".$i;
                    }
                    $arrCol[$TeamCode]['TAR'][$Suffix] = $TargetRST[$Suffix];
                    $arrCol[$TeamCode]['ACT'][$Suffix] = 0;
                }
                $visorder++;

                $SapSlpCode .= $TargetRST['SlpCode'];
                if($visorder != ChkRowDB($TargetSQL)) {
                    $SapSlpCode .= ",";
                }
            }

            /* TARGET BY YEAR */
            $TargetSQL =
                "SELECT
                    CASE
                        WHEN T0.TeamCode IN ('MT100')  THEN 'MT1'
                        WHEN T0.TeamCode IN ('EXP101') THEN 'EXP'
                        WHEN T0.TeamCode IN ('MT200')  THEN 'MT2'
                        WHEN T0.TeamCode IN ('TT101')  THEN 'TT1'
                        WHEN T0.TeamCode IN ('TT201','TT202','TT203') THEN 'TT2'
                    ELSE T0.TeamCode END AS 'TeamCode', SUM(T0.TrgAmount) AS 'TrgAmount'
                FROM teamtarget T0
                WHERE T0.DocYear = $filt_y AND T0.DocStatus = 'A'
                GROUP BY
                    CASE
                        WHEN T0.TeamCode IN ('MT100')  THEN 'MT1'
                        WHEN T0.TeamCode IN ('EXP101') THEN 'EXP'
                        WHEN T0.TeamCode IN ('MT200')  THEN 'MT2'
                        WHEN T0.TeamCode IN ('TT101')  THEN 'TT1'
                        WHEN T0.TeamCode IN ('TT201','TT202','TT203') THEN 'TT2'
                    ELSE T0.TeamCode END";
            $TargetQRY = MySQLSelectX($TargetSQL);
            while($TargetRST = mysqli_fetch_array($TargetQRY)) {
                $TeamCode = $TargetRST['TeamCode'];
                for($i = 1; $i <= 12; $i++) {
                    if($i <= 9) {
                        $Suffix = "M0".$i;
                    } else {
                        $Suffix = "M".$i;
                    }
                    $arrCol[$TeamCode]['TAR']['YEAR'] = $TargetRST['TrgAmount'];
                }
            }

            /* ACTUAL */
            $ActualSQL =
                "SELECT
                    A0.TeamCode,
                    SUM(A0.M01) AS 'M01', SUM(A0.M02) AS 'M02', SUM(A0.M03) AS 'M03',
                    SUM(A0.M04) AS 'M04', SUM(A0.M05) AS 'M05', SUM(A0.M06) AS 'M06',
                    SUM(A0.M07) AS 'M07', SUM(A0.M08) AS 'M08', SUM(A0.M09) AS 'M09',
                    SUM(A0.M10) AS 'M10', SUM(A0.M11) AS 'M11', SUM(A0.M12) AS 'M12',
                    SUM(A0.M01+A0.M02+A0.M03+A0.M04+A0.M05+A0.M06+A0.M07+A0.M08+A0.M09+A0.M10+A0.M11+A0.M12) AS 'YEAR'
                FROM (
                    SELECT
                        T1.U_Dim1 AS 'TeamCode',
                        CASE WHEN MONTH(T0.DocDate) = 1 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M01',
                        CASE WHEN MONTH(T0.DocDate) = 2 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M02',
                        CASE WHEN MONTH(T0.DocDate) = 3 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M03',
                        CASE WHEN MONTH(T0.DocDate) = 4 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M04',
                        CASE WHEN MONTH(T0.DocDate) = 5 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M05',
                        CASE WHEN MONTH(T0.DocDate) = 6 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M06',
                        CASE WHEN MONTH(T0.DocDate) = 7 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M07',
                        CASE WHEN MONTH(T0.DocDate) = 8 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M08',
                        CASE WHEN MONTH(T0.DocDate) = 9 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M09',
                        CASE WHEN MONTH(T0.DocDate) = 10 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M10',
                        CASE WHEN MONTH(T0.DocDate) = 11 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M11',
                        CASE WHEN MONTH(T0.DocDate) = 12 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M12'
                    FROM OINV T0
                    LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
                    WHERE YEAR(T0.DocDate) = $filt_y AND T0.CANCELED = 'N'
                    GROUP BY T1.U_Dim1, YEAR(T0.DocDate), MONTH(T0.DocDate)

                    UNION ALL

                    SELECT
                        T1.U_Dim1 AS 'TeamCode',
                        CASE WHEN MONTH(T0.DocDate) = 1 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M01',
                        CASE WHEN MONTH(T0.DocDate) = 2 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M02',
                        CASE WHEN MONTH(T0.DocDate) = 3 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M03',
                        CASE WHEN MONTH(T0.DocDate) = 4 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M04',
                        CASE WHEN MONTH(T0.DocDate) = 5 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M05',
                        CASE WHEN MONTH(T0.DocDate) = 6 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M06',
                        CASE WHEN MONTH(T0.DocDate) = 7 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M07',
                        CASE WHEN MONTH(T0.DocDate) = 8 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M08',
                        CASE WHEN MONTH(T0.DocDate) = 9 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M09',
                        CASE WHEN MONTH(T0.DocDate) = 10 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M10',
                        CASE WHEN MONTH(T0.DocDate) = 11 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M11',
                        CASE WHEN MONTH(T0.DocDate) = 12 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M12'
                    FROM ORIN T0
                    LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
                    WHERE YEAR(T0.DocDate) = $filt_y AND T0.CANCELED = 'N'
                    GROUP BY T1.U_Dim1, YEAR(T0.DocDate), MONTH(T0.DocDate)
                ) A0
                GROUP BY A0.TeamCode
                ORDER BY A0.TeamCode";
            if($filt_y <= 2022) {
                $ActualQRY = conSAP8($ActualSQL);
            } else {
                $ActualQRY = SAPSelect($ActualSQL);
            }
            // echo $ActualSQL;

            while($ActualRST = odbc_fetch_array($ActualQRY)) {
                $TeamCode = $ActualRST['TeamCode'];
                for($i = 1; $i <= 12; $i++) {
                    if($i <= 9) {
                        $Suffix = "M0".$i;
                    } else {
                        $Suffix = "M".$i;
                    }
                    $arrCol[$TeamCode]['ACT'][$Suffix] = $ActualRST[$Suffix];
                }
                $arrCol[$TeamCode]['ACT']['YEAR'] = $ActualRST['YEAR'];
            }
        break;
        default:
            if($filt_t == 'PTA') {
                $ChkTargetSQL = "CONCAT(T1.uName,' (',T1.uNickName,')') AS 'SlpName',";
                $ChkOSLP = "OSLP_PITA";
            }else{
                $ChkTargetSQL = 
                    "CASE
                        WHEN T0.Ukey = '569ed0bfade926ca16c8fd42b15eNo01' THEN 'โฮมโปร - ฝากขาย'
                        WHEN T0.Ukey = '569ed0bfade926ca16c8fd42b15eNo02' THEN 'ไทวัสดุ - ฝากขาย'
                        WHEN T0.Ukey = '569ed0bfade926ca16c8fd42b15eNo03' THEN 'เมกาโฮม - ฝากขาย'
                        WHEN T0.Ukey = 'a82726eeff10f11797ed9fde004e701a' THEN 'จีรศักดิ์ (ซ่อมหน้าร้าน)'
                    ELSE CONCAT(T1.uName,' (',T1.uNickName,')') END AS 'SlpName',";
                $ChkOSLP = "OSLP";
            }
            /* ACTIVE USERS */
            $TargetSQL =
                "SELECT
                    T0.LogCode, T0.Ukey, 
                    $ChkTargetSQL T0.TeamCode,
                    (SELECT GROUP_CONCAT(P0.$SlctSlpCode) FROM $ChkOSLP P0 WHERE P0.Ukey = T0.Ukey AND P0.TeamCode = T0.TeamCode) AS 'SlpCode',
                    T0.M01, T0.M02, T0.M03, T0.M04, T0.M05, T0.M06,
                    T0.M07, T0.M08, T0.M09, T0.M10, T0.M11, T0.M12,
                    SUM(T0.M01+T0.M02+T0.M03+T0.M04+T0.M05+T0.M06+T0.M07+T0.M08+T0.M09+T0.M10+T0.M11+T0.M12) AS 'YEAR'
                FROM saletarget T0
                LEFT JOIN users T1 ON T0.Ukey = T1.uKey
                LEFT JOIN positions T2 ON T1.LvCode = T2.LvCode
                WHERE T0.DocYear = $filt_y AND T0.DocStatus = 'A' AND T0.TeamCode LIKE '$filt_t%' AND (SELECT GROUP_CONCAT(P0.$SlctSlpCode) FROM $ChkOSLP P0 WHERE P0.Ukey = T0.Ukey AND P0.TeamCode = T0.TeamCode) IS NOT NULL
                GROUP BY T0.Ukey, T0.TeamCode
                ORDER BY T0.TeamCode, IFNULL(T2.LvCode,'LV045')";
             //echo $TargetSQL;
            $TargetQRY = MySQLSelectX($TargetSQL);

            $SumAct = 0;
            while($TargetRST = mysqli_fetch_array($TargetQRY)) {
                $Ukey     = $TargetRST['Ukey'];
                $TeamCode = $TargetRST['TeamCode'];
                $SlpCode  = ($TargetRST['SlpCode'] != '') ? " AND T0.SlpCode IN (".$TargetRST['SlpCode'].")" : '';
                $arrCol[$TeamCode][$Ukey]['Ukey']     = $Ukey;
                $arrCol[$TeamCode][$Ukey]['TeamCode'] = $TeamCode;
                $arrCol[$TeamCode][$Ukey]['SlpName']  = $TargetRST['SlpName'];

                for($i = 1; $i <= 12; $i++) {
                    if($i <= 9) {
                        $Suffix = "M0".$i;
                    } else {
                        $Suffix = "M".$i;
                    }
                    $arrCol[$TeamCode][$Ukey]['TAR'][$Suffix] = $TargetRST[$Suffix];
                    $arrCol[$TeamCode][$Ukey]['ACT'][$Suffix] = 0;
                }

                /* ACTUAL BY ROW */
                $ActualSQL =
                "SELECT
                    A0.Memo,
                    SUM(A0.M01) AS 'M01', SUM(A0.M02) AS 'M02', SUM(A0.M03) AS 'M03',
                    SUM(A0.M04) AS 'M04', SUM(A0.M05) AS 'M05', SUM(A0.M06) AS 'M06',
                    SUM(A0.M07) AS 'M07', SUM(A0.M08) AS 'M08', SUM(A0.M09) AS 'M09',
                    SUM(A0.M10) AS 'M10', SUM(A0.M11) AS 'M11', SUM(A0.M12) AS 'M12',
                    SUM(A0.M01+A0.M02+A0.M03+A0.M04+A0.M05+A0.M06+A0.M07+A0.M08+A0.M09+A0.M10+A0.M11+A0.M12) AS 'YEAR'
                FROM (
                    SELECT
                        T1.Memo AS 'Memo',
                        CASE WHEN MONTH(T0.DocDate) = 1 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M01',
                        CASE WHEN MONTH(T0.DocDate) = 2 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M02',
                        CASE WHEN MONTH(T0.DocDate) = 3 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M03',
                        CASE WHEN MONTH(T0.DocDate) = 4 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M04',
                        CASE WHEN MONTH(T0.DocDate) = 5 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M05',
                        CASE WHEN MONTH(T0.DocDate) = 6 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M06',
                        CASE WHEN MONTH(T0.DocDate) = 7 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M07',
                        CASE WHEN MONTH(T0.DocDate) = 8 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M08',
                        CASE WHEN MONTH(T0.DocDate) = 9 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M09',
                        CASE WHEN MONTH(T0.DocDate) = 10 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M10',
                        CASE WHEN MONTH(T0.DocDate) = 11 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M11',
                        CASE WHEN MONTH(T0.DocDate) = 12 THEN SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M12'
                    FROM OINV T0
                    LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
                    WHERE YEAR(T0.DocDate) = $filt_y AND T0.CANCELED = 'N' $SlpCode
                    GROUP BY T1.Memo, YEAR(T0.DocDate), MONTH(T0.DocDate)
                    UNION ALL
                    SELECT
                        T1.Memo AS 'Memo',
                        CASE WHEN MONTH(T0.DocDate) = 1 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M01',
                        CASE WHEN MONTH(T0.DocDate) = 2 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M02',
                        CASE WHEN MONTH(T0.DocDate) = 3 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M03',
                        CASE WHEN MONTH(T0.DocDate) = 4 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M04',
                        CASE WHEN MONTH(T0.DocDate) = 5 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M05',
                        CASE WHEN MONTH(T0.DocDate) = 6 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M06',
                        CASE WHEN MONTH(T0.DocDate) = 7 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M07',
                        CASE WHEN MONTH(T0.DocDate) = 8 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M08',
                        CASE WHEN MONTH(T0.DocDate) = 9 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M09',
                        CASE WHEN MONTH(T0.DocDate) = 10 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M10',
                        CASE WHEN MONTH(T0.DocDate) = 11 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M11',
                        CASE WHEN MONTH(T0.DocDate) = 12 THEN -SUM(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M12'
                    FROM ORIN T0
                    LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
                    WHERE YEAR(T0.DocDate) = $filt_y AND T0.CANCELED = 'N' $SlpCode
                    GROUP BY T1.Memo, YEAR(T0.DocDate), MONTH(T0.DocDate)
                ) A0
                GROUP BY A0.Memo
                ORDER BY A0.Memo";
                // echo $ActualSQL."</br>";

                if($filt_t == 'PTA') {
                    $SAPRow = ChkRowPITA($ActualSQL);
                }else{
                    if($filt_y <= 2022) {
                        $SAPRow = ChkRowSAP8($ActualSQL);
                    } else {
                        $SAPRow = ChkRowSAP($ActualSQL);
                    }
                }

                if($SAPRow > 0) {
                    if($filt_t == 'PTA') {
                        $ActualQRY = PITASelect($ActualSQL);
                    }else{
                        if($filt_y <= 2022) {
                            $ActualQRY = conSAP8($ActualSQL);
                        } else {
                            $ActualQRY = SAPSelect($ActualSQL);
                        }
                    }
                    
                    while($TargetRST = odbc_fetch_array($ActualQRY)) {
                        for($i = 1; $i <= 12; $i++) {
                            if($i <= 9) {
                                $Suffix = "M0".$i;
                            } else {
                                $Suffix = "M".$i;
                            }
                            $arrCol[$TeamCode][$Ukey]['ACT'][$Suffix] = $TargetRST[$Suffix];
                            $SumAct = $SumAct + $TargetRST[$Suffix];
                        }
                    }
                }
            }

            /* SUMMARY YEARS */
            $TarYSQL = "SELECT SUM(T0.TrgAmount) 'TrgAmount' FROM teamtarget T0 WHERE T0.DocYear = $filt_y AND T0.DocStatus = 'A' AND T0.TeamCode LIKE '$filt_t%'";
            $TarYRST = MySQLSelect($TarYSQL);

            $arrCol['YEAR']['TAR'] = $TarYRST['TrgAmount'];
            $arrCol['YEAR']['ACT'] = $SumAct;

            /* NON ACTIVE USERS */
            $InActSlpSQL = 
                "SELECT
                    GROUP_CONCAT(T1.SlpCode) AS 'SlpCode' 
                FROM saletarget T0
                LEFT JOIN $ChkOSLP T1 ON T0.Ukey = T1.Ukey
                WHERE T0.DocYear = $filt_y AND T0.DocStatus = 'R' AND T0.TeamCode LIKE '$filt_t%'
                GROUP BY T0.Ukey";
            if(ChkRowDB($InActSlpSQL) > 0) {
                $InActSlpRST = MySQLSelect($InActSlpSQL);
                $SlpCode = $InActSlpRST['SlpCode'];

                $InActSAPSQL =
                    "SELECT
                        SUM(A0.M01) AS 'M01', SUM(A0.M02) AS 'M02', SUM(A0.M03) AS 'M03',
                        SUM(A0.M04) AS 'M04', SUM(A0.M05) AS 'M05', SUM(A0.M06) AS 'M06',
                        SUM(A0.M07) AS 'M07', SUM(A0.M08) AS 'M08', SUM(A0.M09) AS 'M09',
                        SUM(A0.M10) AS 'M10', SUM(A0.M11) AS 'M11', SUM(A0.M12) AS 'M12'
                    FROM (
                        SELECT
                            CASE WHEN MONTH(T0.DocDate) = 1 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M01',
                            CASE WHEN MONTH(T0.DocDate) = 2 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M02',
                            CASE WHEN MONTH(T0.DocDate) = 3 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M03',
                            CASE WHEN MONTH(T0.DocDate) = 4 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M04',
                            CASE WHEN MONTH(T0.DocDate) = 5 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M05',
                            CASE WHEN MONTH(T0.DocDate) = 6 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M06',
                            CASE WHEN MONTH(T0.DocDate) = 7 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M07',
                            CASE WHEN MONTH(T0.DocDate) = 8 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M08',
                            CASE WHEN MONTH(T0.DocDate) = 9 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M09',
                            CASE WHEN MONTH(T0.DocDate) = 10 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M10',
                            CASE WHEN MONTH(T0.DocDate) = 11 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M11',
                            CASE WHEN MONTH(T0.DocDate) = 12 THEN (T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M12'
                        FROM OINV T0
                        LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
                        WHERE YEAR(T0.DocDate) = $filt_y AND T0.SlpCode IN ($SlpCode)
                        
                        UNION ALL
                        
                        SELECT
                            CASE WHEN MONTH(T0.DocDate) = 1 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M01',
                            CASE WHEN MONTH(T0.DocDate) = 2 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M02',
                            CASE WHEN MONTH(T0.DocDate) = 3 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M03',
                            CASE WHEN MONTH(T0.DocDate) = 4 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M04',
                            CASE WHEN MONTH(T0.DocDate) = 5 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M05',
                            CASE WHEN MONTH(T0.DocDate) = 6 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M06',
                            CASE WHEN MONTH(T0.DocDate) = 7 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M07',
                            CASE WHEN MONTH(T0.DocDate) = 8 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M08',
                            CASE WHEN MONTH(T0.DocDate) = 9 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M09',
                            CASE WHEN MONTH(T0.DocDate) = 10 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M10',
                            CASE WHEN MONTH(T0.DocDate) = 11 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M11',
                            CASE WHEN MONTH(T0.DocDate) = 12 THEN -(T0.DocTotal - T0.VatSum) ELSE 0 END AS 'M12'
                        FROM ORIN T0
                        LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
                        WHERE YEAR(T0.DocDate) = $filt_y AND T0.SlpCode IN ($SlpCode)
                    ) A0";
                if($filt_t == 'PTA') {
                    $InActSAPQRY = PITASelect($InActSAPSQL);
                }else{
                    $InActSAPQRY = SAPSelect($InActSAPSQL);
                }
                while($InActSAPRST = odbc_fetch_array($InActSAPQRY)) {
                    for($m = 1; $m <= 12; $m++) {
                        if($m <= 9) {
                            $Suffix = "M0".$m;
                        } else {
                            $Suffix = "M".$m;
                        }
                        $arrCol['YEAR']['NOACTIVE'][$Suffix] = $InActSAPRST[$Suffix];
                    }
                }
            } else {
                for($m = 1; $m <= 12; $m++) {
                    if($m <= 9) {
                        $Suffix = "M0".$m;
                    } else {
                        $Suffix = "M".$m;
                    }
                    $arrCol['YEAR']['NOACTIVE'][$Suffix] = 0;
                }
            }
        break;
    }
}

if($_GET['p'] == "RePassword") {
    $NewPass = md5($_POST['pswd']);
    $UpdateSQL = "UPDATE users SET UserPass = '$NewPass' WHERE uKey = '".$_SESSION['ukey']."'";
    echo $UpdateSQL;
    $UpdateQRY = MySQLUpdate($UpdateSQL);
}

if($_GET['p'] == 'GetDataDM') {
    $Yaer = $_POST['y'];
    $Month = $_POST['m'];
    $Team = $_POST['t'];

    switch($Team) {
        case 'MT1': $DeptCode = "DP006"; break;
        case 'MT2': $DeptCode = "DP007"; break;
        case 'OUL': 
        case 'TT1': $DeptCode = "DP008"; break;
        case 'TT2': $DeptCode = "DP005"; break;
    }

    switch($DeptCode) {
		case "DP005": $LvCode = "'LV092'"; break;
		case "DP006": $LvCode = "'LV042','LV043'"; break;
		case "DP007": $LvCode = "'LV048','LV049'"; break;
		case "DP008": $LvCode = "'LV109'"; break;
	}
	$SQL1 = 
		"SELECT T0.uKey, CONCAT(T0.uName,' ',T0.uLastName) AS 'FullName', T0.uNickName 
        FROM users T0 
		WHERE T0.UserStatus = 'A' AND T0.LvCode IN ($LvCode) 
		ORDER BY T0.uName, T0.uLastName";
	$QRY1 = MySQLSelectX($SQL1);
    $Tbody = ""; $AllSaleTarget = 0; $AllSaleActual = 0;
    while($RST1 = mysqli_fetch_array($QRY1)) {
        $SQL2 = 
            "SELECT T0.SaleTarget, T0.SaleActual
            FROM demontarget T0
            WHERE YEAR(T0.CreateDate) = $Yaer AND MONTH(T0.CreateDate) = $Month AND T0.Status = 'A' AND T0.uKey = '".$RST1['uKey']."'
            ORDER BY T0.CreateDate DESC LIMIT 1";
        $RST2 = MySQLSelect($SQL2);
        $SaleTarget = (isset($RST2['SaleTarget'])) ? $RST2['SaleTarget'] : 0;
        $SaleActual = (isset($RST2['SaleActual'])) ? $RST2['SaleActual'] : 0;

        $Tbody .= "
            <tr>
                <td class='d-flex justify-content-between'>
                    <div>".$RST1['FullName']." ".(($RST1['uNickName'] != '') ? "(".$RST1['uNickName'].")" : "")."</div>
                    <div>
                        <a href='javascript:void(0);' onclick='GetDataDM_All(\"".$RST1['uKey']."\")'>
                            <i class='fas fa-clipboard-list fa-fw fa-lg'></i>
                        </a>
                    </div>
                </td>
                <td class='text-right'>".number_format($SaleTarget,0)."</td>
                <td class='text-right'>".number_format($SaleActual,0)."</td>
                <td class='text-center'>".(($SaleTarget != 0) ? number_format(($SaleActual/$SaleTarget)*100,2) : "0.00")."</td>
            </tr>
        ";

        $AllSaleTarget = $AllSaleTarget+$SaleTarget; 
        $AllSaleActual = $AllSaleActual+$SaleActual;
    }

    $TbodyAll = "
        <tr class='bg-danger text-white' style='font-weight: bold;'>
            <td>รวมทุกทีม</td>
            <td class='text-right'>".number_format($AllSaleTarget,0)."</td>
            <td class='text-right'>".number_format($AllSaleActual,0)."</td>
            <td class='text-center'>".(($AllSaleTarget != 0) ? number_format(($AllSaleActual/$AllSaleTarget)*100,2) : "0.00")."</td>
        </tr>
    ";

    $SQL3 = 
        "SELECT SUM(T0.SaleTarget) AS SaleTarget, SUM(T0.SaleActual) AS SaleActual
        FROM demontarget T0
        LEFT JOIN users T1 ON T0.uKey = T1.uKey
        WHERE YEAR(T0.CreateDate) = $Yaer AND T0.Status = 'A' AND T1.LvCode IN ($LvCode) ";
    $RST3 = MySQLSelect($SQL3);
    $SumSaleTarget = (isset($RST3['SaleTarget'])) ? $RST3['SaleTarget'] : 0;
    $SumSaleActual = (isset($RST3['SaleActual'])) ? $RST3['SaleActual'] : 0;
    $Tfoot = "
        <tr class='text-white' style='font-weight: bold; background-color: #9A1118;'>
            <td>รวมทั้งปี $Yaer</td>
            <td class='text-right'>".number_format($SumSaleTarget,0)."</td>
            <td class='text-right'>".number_format($SumSaleActual,0)."</td>
            <td class='text-center'>".(($SumSaleTarget != 0) ? number_format(($SumSaleActual/$SumSaleTarget)*100,2) : "0.00")."</td>
        </tr>
    ";

    $arrCol['Tbody'] = $Tbody;
    $arrCol['TbodyAll'] = $TbodyAll;
    $arrCol['Tfoot'] = $Tfoot;
}

if($_GET['p'] == 'GetDataDM_All') {
    $Ukey = $_POST['Ukey'];
    $Yaer = $_POST['y'];

    $SQL1 = "SELECT T0.uKey, T0.uName, T0.uNickName FROM users T0 WHERE T0.uKey = '$Ukey'";
    $RST1 = MySQLSelect($SQL1);
    $SaleName = ($RST1['uNickName'] != "") ? $RST1['uName']." (".$RST1['uNickName'].")" : $RST1['uName'];

    $SQL = 
        "SELECT A0.Month, SUM(A0.SaleTarget) AS SaleTarget, SUM(A0.SaleActual) AS SaleActual
        FROM (
            SELECT MONTH(CreateDate) AS 'Month', T0.SaleTarget, T0.SaleActual
            FROM demontarget T0
            WHERE YEAR(T0.CreateDate) = $Yaer AND T0.Status = 'A' AND T0.uKey = '$Ukey'
        ) A0
        GROUP BY A0.Month
        ORDER BY A0.MONTH";
    $QRY = MySQLSelectX($SQL);
    for($m = 1; $m <= 12; $m++) { $arrCol["M".$m]['SaleTarget'] = 0; $arrCol["M".$m]['SaleActual'] = 0; }
    while($RST = mysqli_fetch_array($QRY)) {
        $arrCol["M".$RST['Month']]['SaleTarget'] = $RST['SaleTarget'];
        $arrCol["M".$RST['Month']]['SaleActual'] = $RST['SaleActual'];
    }

    $arrCol['SaleName'] = $SaleName;
}

array_push($resultArray,$arrCol);
echo json_encode($resultArray);

?>