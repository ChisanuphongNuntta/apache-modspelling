<?php
include('../../../core/config.core.php');
include('../../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();

$resultArray = array();
$arrCol = array();

$LvCode = $_SESSION['LvCode'];

if($_GET['p'] == "GetData") {
    $y = $_POST['y'];
    $m = $_POST['m'];
    if(isset($_POST['t'])) {
        $t = $_POST['t'];
    }
    
    $u = $_SESSION['ukey'];

    $loopday = cal_days_in_month(CAL_GREGORIAN, $m, $y);

    if($LvCode == "LV077") {
        for($i = 1; $i <= $loopday; $i++) {
            $WeekDate = date("w",strtotime($y."-".$m."-".$i));

            $arrCol[$i]['Date'] = $i;
            $arrCol[$i]['WeekDate'] = $WeekDate;
            $arrCol[$i]['TargetSO']    = null;
            $arrCol[$i]['TargetSKU']   = null;
            $arrCol[$i]['ONTIME_SO']   = null;
            $arrCol[$i]['ONTIME_SKU']  = null;
            $arrCol[$i]['AFTER_SO']    = null;
            $arrCol[$i]['AFTER_SKU']   = null;
            $arrCol[$i]['CANCELED_SO'] = null;
        }

        switch($t) {
            case "MT":
            case "TT": $WhrSQL = "WHERE A0.TeamCode = '$t'"; break;
            default:   $WhrSQL = NULL; break;
        }

        $SQL1 =
            "SELECT
                B0.PlanDay,
                SUM(B0.TargetSO) AS 'TargetSO', SUM(B0.TargetSKU) AS 'TargetSKU',
                SUM(B0.ONTIME_SO) AS 'ONTIME_SO', SUM(B0.ONTIME_SKU) AS 'ONTIME_SKU',
                SUM(B0.AFTER_SO) AS 'AFTER_SO', SUM(B0.AFTER_SKU) AS 'AFTER_SKU',
                SUM(B0.CANCELED_SO) AS 'CANCELED_SO'
            FROM (
                SELECT
                    DAY(A0.DatePick) AS 'PlanDay',
                    COUNT(A0.ID) AS 'TargetSO',	SUM(A0.ItemCount) AS 'TargetSKU',
                    CASE WHEN A0.PickType = 'ONTIME' THEN COUNT(A0.ID) ELSE 0 END AS 'ONTIME_SO',
                    CASE WHEN A0.PickType = 'ONTIME' THEN SUM(A0.ItemCount) ELSE 0 END AS 'ONTIME_SKU',
                    CASE WHEN A0.PickType = 'AFTER' THEN COUNT(A0.ID) ELSE 0 END AS 'AFTER_SO',
                    CASE WHEN A0.PickType = 'AFTER' THEN SUM(A0.ItemCount) ELSE 0 END AS 'AFTER_SKU',
                    CASE WHEN A0.CANCELED = 'Y' THEN COUNT(A0.ID) ELSE 0 END AS 'CANCELED_SO'
                FROM (
                    SELECT
                        T0.DatePick, IFNULL(DATE(T0.StartPick), DATE(T0.PickedDate)) AS 'StartPick', T0.ID, T0.DocType,
                        CASE
                            WHEN (T0.DatePick = IFNULL(DATE(T0.StartPick), DATE(T0.PickedDate)) OR T0.DatePick > IFNULL(DATE(T0.StartPick), DATE(T0.PickedDate))) THEN 'ONTIME'
                            WHEN (T0.DatePick < IFNULL(DATE(T0.StartPick), DATE(T0.PickedDate)) AND IFNULL(DATE(T0.StartPick), DATE(T0.PickedDate)) IS NOT NULL) THEN 'AFTER'
                        ELSE 'NOPICK' END AS 'PickType',
                        CASE WHEN T0.TeamCode LIKE 'MT%' THEN 'MT' ELSE 'TT' END AS 'TeamCode',
                        T0.ItemCount, T0.StatusDoc, CASE WHEN T0.StatusDoc = 0 THEN 'Y' ELSE 'N' END AS 'CANCELED'
                    FROM picker_soheader T0
                    LEFT JOIN users T1 ON T0.UkeyPicker = T1.ukey
                    WHERE T0.UkeyPicker = '$u' AND (YEAR(T0.DatePick) = $y AND MONTH(T0.DatePick) = $m)
                ) A0
                $WhrSQL
                GROUP BY A0.DatePick, A0.PickType
            ) B0
            GROUP BY B0.PlanDay
            ORDER BY B0.PlanDay";
        // echo $SQL1;
        $QRY1 = MySQLSelectX($SQL1);
        while($RST1 = mysqli_fetch_array($QRY1)) {
            $arrCol[$RST1['PlanDay']]['TargetSO']    = number_format($RST1['TargetSO'],0);
            $arrCol[$RST1['PlanDay']]['TargetSKU']   = number_format($RST1['TargetSKU'],0);
            $arrCol[$RST1['PlanDay']]['ONTIME_SO']   = number_format($RST1['ONTIME_SO'],0);
            $arrCol[$RST1['PlanDay']]['ONTIME_SKU']  = number_format($RST1['ONTIME_SKU'],0);
            $arrCol[$RST1['PlanDay']]['AFTER_SO']    = number_format($RST1['AFTER_SO'],0);
            $arrCol[$RST1['PlanDay']]['AFTER_SKU']   = number_format($RST1['AFTER_SKU'],0);
            $arrCol[$RST1['PlanDay']]['CANCELED_SO'] = number_format($RST1['CANCELED_SO'],0);
        }

        $arrCol['Template'] = 1;
    } else {

        for($i = 1; $i <= $loopday; $i++) {
            $WeekDate = date("w",strtotime($y."-".$m."-".$i));

            $arrCol[$i]['Date']     = $i;
            $arrCol[$i]['WeekDate'] = $WeekDate;
            $arrCol[$i]['Refill']   = null;
            $arrCol[$i]['Transfer'] = null;
        }

        $SQL1 = 
            "SELECT
                A0.Date, SUM(A0.CountRefill) AS 'CountRefill', SUM(A0.CountTransfer) AS 'CountTransfer'
            FROM (
                SELECT
                    DAY(T0.DateCreate) AS 'Date',
                    CASE WHEN (T0.QtyIn > 0 AND T0.LocationRack LIKE 'A%' OR T0.LocationRack LIKE 'B%' OR T0.LocationRack LIKE 'C6%' OR T0.LocationRack LIKE 'C7%' OR T0.LocationRack LIKE 'C8%') THEN 1 ELSE 0 END AS 'CountRefill',
                    CASE WHEN (T0.QtyOut > 0 AND trnCode LIKE 'WH%') THEN 1 ELSE 0 END AS 'CountTransfer'
                FROM transecdata T0
                LEFT JOIN users T1 ON T0.ukeyUpdate = T1.uKey
                WHERE (T1.LvCode = 'LV076' AND T0.StatusTran = 1) AND (YEAR(T0.DateCreate) = $y AND MONTH(T0.DateCreate) = $m) AND T0.ukeyUpdate = '$u'
            ) A0
            GROUP BY A0.Date
            ORDER BY A0.Date";
        $QRY1 = MySQLSelectX($SQL1);
        while($RST1 = mysqli_fetch_array($QRY1)) {
            $arrCol[$RST1['Date']]['Refill']    = number_format($RST1['CountRefill'],0);
            $arrCol[$RST1['Date']]['Transfer']  = number_format($RST1['CountTransfer'],0);
        }

        $arrCol['Template'] = 2;
    }

    $arrCol['LoopDay'] = $loopday;

}



array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>