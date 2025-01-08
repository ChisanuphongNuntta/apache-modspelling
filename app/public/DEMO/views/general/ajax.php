<?php session_start();
require_once("../../core/functions.core.php");

$JSON  = array();
$inval = array();

function ConfigGroupName($ConfigGroup) {
    switch($ConfigGroup) {
        case "sale_ar" : $txt = "ตั้งค่าเปิดใบสั่งขาย"; break;
        case "routetrip": $txt = "ตั้งค่าระบบเช็คอินร้านค้า"; break;
        default: $txt = ""; break;
    }
    return $txt;
}

if($_GET['p'] == "ConfigList") {
    $SQL1 = "SELECT T0.ConfigID, T0.ConfigGroup, T0.ConfigName, T0.ConfigValue FROM config_general T0 ORDER BY T0.ConfigGroup, T0.ConfigID";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    $inval['Row'] = 0;
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
        foreach($RST1 as $key=>$value) {
            $inval[$key]['ConfigID']    = $value['ConfigID'];
            $inval[$key]['GroupName']   = ConfigGroupName($value['ConfigGroup']);
            $inval[$key]['ConfigName']  = $value['ConfigName'];
            $inval[$key]['ConfigValue'] = $value['ConfigValue'];
            $inval['Row']++;
        }
    }
}

if($_GET['p'] == "SaveConfig") {
    $ConfigID    = $_POST['CID'];
    $ConfigValue = $_POST['CVL'];
    $ssid        = $_SESSION['SSID'];
    $ukey        = $_SESSION['UKEY'];

    $SQL1 = "UPDATE config_general SET ConfigValue = :ConfigValue, UkeyUpdate = :ukey, ssidUpdate = :ssid WHERE ConfigID = :ConfigID";
    $QRY1 = DBConnect("APP")->prepare($SQL1);
    $QRY1->bindparam(":ConfigValue", $ConfigValue);
    $QRY1->bindparam(":ukey", $ukey);
    $QRY1->bindparam(":ssid", $ssid);
    $QRY1->bindparam(":ConfigID", $ConfigID);
    $RST1 = $QRY1->execute();
    $inval['Status'] = (!$RST1) ? "ERR" : "OK";
}

if($_GET['p'] == "SyncData") {
    $SyncType = $_POST['SyncType'];
    $ssid     = $_SESSION['SSID'];
    $ukey     = $_SESSION['UKEY'];
    $SiteID   = $_SESSION['SITE']['site_id'];
    switch($SyncType) {
        case "OSLP":
            $SQL1 = "SELECT GROUP_CONCAT(T0.SlpCode) AS 'SlpCode' FROM OSLP T0 WHERE site_id = $SiteID ORDER BY T0.SlpCode";
            $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
            if($RST1[0]['SlpCode'] == NULL) {
                $SlpArr = array();
            } else {
                $SlpArr = explode(",", $RST1[0]['SlpCode']);
            }
            $SQL2 = "SELECT T0.[SlpCode], T0.[SlpName], T0.[Memo] AS [SlpUkey], T1.[empID], CASE WHEN T0.[Active] = 'Y' THEN 'A' ELSE 'I' END AS [Active] FROM OSLP T0 LEFT JOIN OHEM T1 ON T0.[SlpCode] = T1.[salesPrson] WHERE T0.[SlpCode] > 0 ORDER BY T0.[SlpCode] ASC";
            $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll();
            if(!$RST2) {
                $inval['Status'] = "ERR";
            } else {
                $inval['Status'] = "OK";
                $inval['NEW'] = 0;
                $inval['OLD'] = 0;
                foreach($RST2 as $RowData) {
                    if(!in_array($RowData['SlpCode'], $SlpArr)) {
                        $SQL3 = "INSERT INTO OSLP SET site_id = :SiteID, SlpCode = :SlpCode, SlpName = :SlpName, OwnerCode = NULLIF(:empID,''), uKeyCreate = :uKey, ssidCreate = :ssid, SlpStatus = :Active";
                        $inval['NEW']++;
                    } else {
                        $SQL3 = "UPDATE OSLP SET SlpName = :SlpName, OwnerCode = NULLIF(:empID,''), uKeyUpdate = :uKey, ssidUpdate = :ssid, SlpStatus = :Active WHERE SlpCode = :SlpCode AND site_id = :SiteID";
                        $inval['OLD']++;
                    }
                    $SlpName = SapTH($RowData['SlpName']);
                    $SlpUkey = SapTH($RowData['SlpUkey']);
                    $QRY3 = DBConnect("APP")->prepare($SQL3);
                    $QRY3->bindparam(":SiteID",  $SiteID);
                    $QRY3->bindparam(":SlpCode", $RowData['SlpCode']);
                    $QRY3->bindparam(":SlpName", $SlpName);
                    $QRY3->bindparam(":empID",   $RowData['empID']);
                    $QRY3->bindparam(":uKey",    $ukey);
                    $QRY3->bindparam(":ssid",    $ssid);
                    $QRY3->bindparam(":Active",  $RowData['Active']);
                    $RST3 = $QRY3->execute();
                    $inval['Status'] = (!$RST3) ? "ERR" : "OK";
                }
            }
        break;
    }
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>