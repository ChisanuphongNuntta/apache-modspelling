<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();

if($_GET['p'] == 'GetData') {
    /*===== SALE EMPLOYEE =====*/
    $UKEY     = $_SESSION['UKEY'];
    $LVCODE   = $_SESSION['LVCODE'];
    $DEPTCODE = $_SESSION['DEPTCODE'];


    $LVPrefix = substr($LVCODE,0,1);

    switch($LVPrefix) {
        case "I":
        case "C": $WHR1 = ""; break;
        case "M":
        case "A":
        case "S": $WHR1 = "AND T1.DeptCode = '$DEPTCODE'"; break;
        default : $WHR1 = "AND T0.uKey = '$UKEY'"; break;
    }

    $SQL1 = 
        "SELECT
            T0.uKey, CONCAT(T0.TH_uFirstName,' ',T0.TH_uLastName) AS 'SalesName', T2.DeptName 
        FROM users T0
        LEFT JOIN positions T1 ON T0.LvCode = T1.LvCode 
        LEFT JOIN departments T2 ON T1.DeptCode = T2.DeptCode 
        WHERE T0.UserStatus = 'A' $WHR1
        ORDER BY T1.DeptCode, T1.LvClass, T0.TH_uFirstName, T0.TH_uLastName";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    $inval['SALE_EMP'] = $RST1;
    $inval['UKEY'] = $UKEY;
    /*===== CUSTOMERS =====*/
    $SQL2 = "SELECT T0.[CardCode], T0.[CardName] FROM OCRD T0 WHERE T0.[CardType] = 'C' ORDER BY T0.[CardCode]";
    $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll(PDO::FETCH_ASSOC);
    $inval['CARD_LIST'] = SendTHData($RST2);
}

if($_GET['p'] == "SavePlan") {
    $UKEY   = $_SESSION['UKEY'];
    $SSID   = $_SESSION['SSID'];
    $SiteID = $_SESSION['SITE']['site_id'];

    $txt_CardCode     = $_POST['txt_CardCode'];
    $txt_CardName     = $_POST['txt_CardName'];
    $txt_PlanStart    = (isset($_POST['txt_PlanStart']) != "") ? $_POST['txt_PlanStart'] : "";
    $txt_PlanEnd      = (isset($_POST['txt_PlanEnd']) != "") ? $_POST['txt_PlanEnd'] : "";
    $txt_ContactName  = (isset($_POST['txt_ContactName']) != "") ? $_POST['txt_ContactName'] : "";
    $txt_ContactPhone = (isset($_POST['txt_ContactPhone']) != "") ? $_POST['txt_ContactPhone'] : "";
    $txt_ContactEmail = (isset($_POST['txt_ContactEmail']) != "") ? $_POST['txt_ContactEmail'] : "";
    $txt_ContactLINE  = (isset($_POST['txt_ContactLINE']) != "") ? $_POST['txt_ContactLINE'] : "";
    $txt_PlanDetail   = (isset($_POST['txt_PlanDetail']) != "") ? $_POST['txt_PlanDetail'] : "";

    $SQL0 = "SELECT TOP 1 ISNULL(T0.[U_CardLat],'') AS [U_CardLat], ISNULL(T0.[U_CardLon],'') AS [U_CardLon] FROM OCRD T0 WHERE T0.[CardCode] = '$txt_CardCode'";
    $RST0 = DBConnect("SAP")->query(SQLtoHANA($SQL0))->fetchAll(PDO::FETCH_ASSOC)[0];

    $PlanLat = $RST0['U_CardLat'];
    $PlanLon = $RST0['U_CardLon'];

    $CON1 = DBConnect("APP");
    $SQL1 =
        "INSERT INTO routetrip SET
            site_id         = :SiteID,
            PlanType        = 'P',
            CardCode        = :CardCode,
            CardName        = :CardName,
            PlanStart       = NULLIF(:PlanStart,''),
            PlanEnd         = NULLIF(:PlanEnd,''),
            PlanDetail      = NULLIF(:PlanDetail,''),
            PlanLat         = NULLIF(:PlanLat,''),
            PlanLon         = NULLIF(:PlanLon,''),
            ContactName     = NULLIF(:ContactName,''),
            ContactPhone    = NULLIF(:ContactPhone,''),
            ContactEmail    = NULLIF(:ContactEmail,''),
            ContactLINE     = NULLIF(:ContactLINE,''),
            Plan_uKeyCreate = :UKEY,
            Plan_DateCreate = NOW(),
            Plan_ssidCreate = :SSID";
    $QRY1 = $CON1->prepare($SQL1);
    $QRY1->bindparam(":SiteID",       $SiteID);
    $QRY1->bindparam(":CardCode",     $txt_CardCode);
    $QRY1->bindparam(":CardName",     $txt_CardName);
    $QRY1->bindparam(":PlanStart",    $txt_PlanStart);
    $QRY1->bindparam(":PlanEnd",      $txt_PlanEnd);
    $QRY1->bindparam(":PlanDetail",   $txt_PlanDetail);
    $QRY1->bindparam(":PlanLat",      $PlanLat);
    $QRY1->bindparam(":PlanLon",      $PlanLon);
    $QRY1->bindparam(":ContactName",  $txt_ContactName);
    $QRY1->bindparam(":ContactPhone", $txt_ContactPhone);
    $QRY1->bindparam(":ContactEmail", $txt_ContactEmail);
    $QRY1->bindparam(":ContactLINE",  $txt_ContactLINE);
    $QRY1->bindparam(":UKEY",         $UKEY);
    $QRY1->bindparam(":SSID",         $SSID);
    if($QRY1->execute() === FALSE) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
    }

}

if($_GET['p'] == 'GetTrip') {
    $TripYear  = $_POST['y'];
    $TripMonth = $_POST['m'];
    $SaleUkey  = $_POST['uKey'];
    $ViewType  = $_POST['v'];

    if($ViewType == "GRID") {
        $SQL1 = 
            "SELECT
                DATE(T0.PlanStart) AS 'PlanDate', TIME(T0.PlanStart) AS 'PlanTime', T0.TripID, T0.CardCode, T0.CardName, T0.TripStatus
            FROM routetrip T0
            WHERE (YEAR(T0.PlanStart) = $TripYear AND MONTH(T0.PlanStart) = $TripMonth) AND (T0.Plan_uKeyCreate = '$SaleUkey' AND T0.CANCELED = 'N' AND T0.TripStatus != '0')
            ORDER BY T0.PlanStart";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
        $PlanDate = "";
        $k = 0;
        foreach($RST1 as $key => $data) {
            if($PlanDate != date("Y-m-d", strtotime($data['PlanDate']))) {
                $PlanDate = date("Y-m-d", strtotime($data['PlanDate']));
                $k = 0;
            }

            $inval['DataTrip'][date("Y-m-d", strtotime($data['PlanDate']))][$k]['TripID']     = $data['TripID'];
            $inval['DataTrip'][date("Y-m-d", strtotime($data['PlanDate']))][$k]['PlanDate']   = date("Y-m-d", strtotime($data['PlanDate']));
            $inval['DataTrip'][date("Y-m-d", strtotime($data['PlanDate']))][$k]['PlanTime']   = date("H:i", strtotime($data['PlanTime']));
            $inval['DataTrip'][date("Y-m-d", strtotime($data['PlanDate']))][$k]['CardCode']   = $data['CardCode'];
            $inval['DataTrip'][date("Y-m-d", strtotime($data['PlanDate']))][$k]['CardName']   = $data['CardName'];
            $inval['DataTrip'][date("Y-m-d", strtotime($data['PlanDate']))][$k]['TripStatus'] = $data['TripStatus'];
            $k++;
        }
    } else {
        $SQL1 =
            "SELECT
                T0.TripID, DATE(T0.PlanStart) AS 'Plan_D', TIME(T0.PlanStart) AS 'Plan_S', TIME(T0.PlanEnd) AS 'Plan_E', T0.CardCode, T0.CardName, T0.ActualStart AS 'Actual_D', T0.TripStatus,
                CASE WHEN (T0.CANCELED = 'N' AND T0.PlanType = 'P' AND T0.ActualStart IS NULL) THEN 1 ELSE 0 END AS 'Chk_None',
                CASE WHEN (T0.CANCELED = 'N' AND T0.PlanType = 'P' AND T0.ActualStart IS NOT NULL AND DATE(T0.PlanStart) > DATE(T0.ActualStart)) THEN 1 ELSE 0 END 'Chk_BFP',
                CASE WHEN (T0.CANCELED = 'N' AND T0.PlanType = 'P' AND T0.ActualStart IS NOT NULL AND DATE(T0.planStart) = DATE(T0.ActualStart)) THEN 1 ELSE 0 END 'Chk_PLN',
                CASE WHEN (T0.CANCELED = 'N' AND T0.PlanType = 'P' AND T0.ActualStart IS NOT NULL AND DATE(T0.PlanStart) < DATE(T0.ActualStart)) THEN 1 ELSE 0 END 'Chk_ATP',
                CASE WHEN (T0.CANCELED = 'N' AND T0.PlanType = 'N' AND T0.ActualStart IS NOT NULL) THEN 1 ELSE 0 END 'Chk_NOPLAN',
                CASE WHEN (T0.CANCELED = 'N' AND T0.ActualStart IS NOT NULL AND (T0.PlanLat IS NOT NULL AND T0.PlanLon IS NOT NULL) AND T0.ChkDistance < T0.TarDistance) THEN 1 ELSE 0 END AS 'In_Range',
                CASE WHEN (T0.CANCELED = 'N' AND T0.ActualStart IS NOT NULL AND (T0.PlanLat IS NOT NULL AND T0.PlanLon IS NOT NULL) AND T0.ChkDistance >= T0.TarDistance) THEN 1 ELSE 0 END AS 'Out_Range',
                CASE WHEN (T0.CANCELED = 'N' AND T0.ActualStart IS NOT NULL AND (T0.PlanLat IS NULL AND T0.PlanLon IS NULL)) THEN 1 ELSE 0 END AS 'No_Range'
            FROM routetrip T0
            WHERE (YEAR(T0.PlanStart) = $TripYear AND MONTH(T0.PlanStart) = $TripMonth) AND (T0.Plan_uKeyCreate = '$SaleUkey' AND T0.CANCELED = 'N' AND T0.TripStatus != '0')
            ORDER BY CASE WHEN T0.ActualStart IS NULL THEN 1 ELSE 2 END, T0.PlanStart ASC";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
        $inval['DataTrip'] = $RST1;
    }
}

if($_GET['p'] == 'GetPlan') {
    $Date    = $_POST['Date'];
    $SlpUkey = $_POST['uKey'];
    $SQL1 = 
        "SELECT
            T0.TripID, TIME(T0.PlanStart) AS 'S_Time', TIME(T0.PlanEnd) AS 'E_Time', T0.CardCode, T0.CardName, IFNULL(T0.PlanDetail,'') AS 'PlanDetail', 
            IFNULL(T0.ActualStart,'') AS 'ActualStart', IFNULL(T0.PlanLat,'') AS 'PlanLat', IFNULL(T0.PlanLon,'') AS 'PlanLon', T0.TripStatus
        FROM routetrip T0
        WHERE (DATE(T0.PlanStart) = '$Date' AND T0.Plan_uKeyCreate = '$SlpUkey' AND T0.TripStatus != '0' AND T0.CANCELED = 'N')
        ORDER BY T0.PlanStart, T0.CardCode";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    $inval['DPLAN'] = $RST1;
    $inval['Status'] = "OK";
}

if($_GET['p'] == 'GetTripLIST') {
    // [DataLIST][วัน][จุดที่]
    for($day = 1; $day <= 31; $day++) {
        switch($day) {
            case 7:
                $inval['DataLIST'][$day][0]['CardName'] = "C-00187 ซีทูล ฮาร์ดแวร์";
                $inval['DataLIST'][$day][0]['Province'] = "สิงห์บุรี";
                $inval['DataLIST'][$day][0]['DetailPlan'] = "เสนอโปรสินค้ารายการอื่น";
                $inval['DataLIST'][$day][0]['SomeTotal'] = "8,000.00";
                $inval['DataLIST'][$day][0]['Total'] = "560.70	";
                $inval['DataLIST'][$day][0]['Bill'] = "17,030.71";
                $inval['DataLIST'][$day][0]['Status'] = '<span class="badge w-100 p-2 text-white MeetType1"><i class="fas fa-street-view fa-fw fa-1x"></i> เข้าพบ (ในพื้นที่)</span>';
                $inval['DataLIST'][$day][0]['Setting'] = 
                    '<div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="inside">
                            <i class="fas fa-cog fa-fw fa-1x"></i>
                        </button>
                        <ul class="dropdown-menu position-fixed">
                            <li><a class="dropdown-item disabled" href="javascript:void(0);" onclick="EditTrip()"><i class="fas fa-edit fa-fw fa-lg"></i> แก้ไขแผนงาน</a></li>
                            <li><a class="dropdown-item" href="#" target="_blank"><i class="fas fa-directions fa-fw fa-lg text-success"></i> นำทาง</a></li>
                            <li><a class="dropdown-item disabled" href="javascript:void(0);" onclick="CheckIn("")"><i class="fas fa-map-marker-alt fa-fw fa-lg text-primary"></i> เช็คอิน</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="CheckInReport()"><i class="fas fa-file-alt fa-fw fa-lg"></i> รายงานการเข้าพบ</a></li>
                        </ul>
                    </div>';
            break;
            case 9:
                for($list = 0; $list <= 2; $list++) {
                    $inval['DataLIST'][$day][$list]['CardName'] = "C-00187 ซีทูล ฮาร์ดแวร์";
                    $inval['DataLIST'][$day][$list]['Province'] = "สิงห์บุรี";
                    $inval['DataLIST'][$day][$list]['DetailPlan'] = "เสนอโปรสินค้ารายการอื่น";
                    $inval['DataLIST'][$day][$list]['SomeTotal'] = "8,000.00";
                    $inval['DataLIST'][$day][$list]['Total'] = "560.70	";
                    $inval['DataLIST'][$day][$list]['Bill'] = "17,030.71";
                    $inval['DataLIST'][$day][$list]['Status'] = '<span class="badge w-100 p-2 text-white MeetType1"><i class="fas fa-street-view fa-fw fa-1x"></i> เข้าพบ (ในพื้นที่)</span>';
                    $inval['DataLIST'][$day][$list]['Setting'] = 
                        '<div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="inside">
                                <i class="fas fa-cog fa-fw fa-1x"></i>
                            </button>
                            <ul class="dropdown-menu ">
                                <li><a class="dropdown-item disabled" href="javascript:void(0);" onclick="EditTrip()"><i class="fas fa-edit fa-fw fa-lg"></i> แก้ไขแผนงาน</a></li>
                                <li><a class="dropdown-item" href="#" target="_blank"><i class="fas fa-directions fa-fw fa-lg text-success"></i> นำทาง</a></li>
                                <li><a class="dropdown-item disabled" href="javascript:void(0);" onclick="CheckIn("")"><i class="fas fa-map-marker-alt fa-fw fa-lg text-primary"></i> เช็คอิน</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="CheckInReport()"><i class="fas fa-file-alt fa-fw fa-lg"></i> รายงานการเข้าพบ</a></li>
                            </ul>
                        </div>';
                }
            break;
            case 21:
                for($list = 0; $list <= 1; $list++) {
                    $inval['DataLIST'][$day][$list]['CardName'] = "C-00187 ซีทูล ฮาร์ดแวร์";
                    $inval['DataLIST'][$day][$list]['Province'] = "สิงห์บุรี";
                    $inval['DataLIST'][$day][$list]['DetailPlan'] = "เสนอโปรสินค้ารายการอื่น";
                    $inval['DataLIST'][$day][$list]['SomeTotal'] = "8,000.00";
                    $inval['DataLIST'][$day][$list]['Total'] = "560.70	";
                    $inval['DataLIST'][$day][$list]['Bill'] = "17,030.71";
                    $inval['DataLIST'][$day][$list]['Status'] = '<span class="badge w-100 p-2 text-white MeetType1"><i class="fas fa-street-view fa-fw fa-1x"></i> เข้าพบ (ในพื้นที่)</span>';
                    $inval['DataLIST'][$day][$list]['Setting'] = 
                        '<div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="inside">
                                <i class="fas fa-cog fa-fw fa-1x"></i>
                            </button>
                            <ul class="dropdown-menu ">
                                <li><a class="dropdown-item disabled" href="javascript:void(0);" onclick="EditTrip()"><i class="fas fa-edit fa-fw fa-lg"></i> แก้ไขแผนงาน</a></li>
                                <li><a class="dropdown-item" href="#" target="_blank"><i class="fas fa-directions fa-fw fa-lg text-success"></i> นำทาง</a></li>
                                <li><a class="dropdown-item disabled" href="javascript:void(0);" onclick="CheckIn("")"><i class="fas fa-map-marker-alt fa-fw fa-lg text-primary"></i> เช็คอิน</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="CheckInReport()"><i class="fas fa-file-alt fa-fw fa-lg"></i> รายงานการเข้าพบ</a></li>
                            </ul>
                        </div>';
                }
            break;
        }
    }
}

if($_GET['p'] == "GetLocation") {
    $TripID = $_POST['TripID'];
    
    /* Find CardCode */
    $SQL1 = "SELECT T0.CardCode, T0.CardName FROM routetrip T0 WHERE T0.TripID = $TripID LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC)[0];
    $CardCode = $RST1['CardCode'];
    $CardName = $RST1['CardName'];

    /* Find GPS Location In SAP */
    $SQL2 = "SELECT TOP 1 ISNULL(T0.[U_CardLat],'') AS [U_CardLat], ISNULL(T0.[U_CardLon],'') AS [U_CardLon] FROM OCRD T0 WHERE T0.[CardCode] = '$CardCode'";
    $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll(PDO::FETCH_ASSOC)[0];
    $CardLat = $RST2['U_CardLat'];
    $CardLon = $RST2['U_CardLon'];

    /* Find Target Distance */
    $SQL3 = "SELECT (T0.ConfigValue/1000) AS 'TarRange' FROM config_general T0 WHERE T0.ConfigID = 2 LIMIT 1";
    $RST3 = DBConnect("APP")->query($SQL3)->fetchAll(PDO::FETCH_ASSOC)[0];
    $TarRange = $RST3['TarRange'];

    $inval['CardCode'] = $CardCode;
    $inval['CardName'] = $CardName;
    $inval['CardLat']  = $CardLat;
    $inval['CardLon']  = $CardLon;
    $inval['TarDistance'] = $TarRange;
}

if($_GET['p'] == "AddCheckIn") {
    $TripID      = (isset($_POST['TripID']) == "") ? "" : $_POST['TripID'];
    $CardCode    = (isset($_POST['CardCode']) == "") ? "" : $_POST['CardCode'];
    $PlanLat     = (isset($_POST['PlanLat']) == "") ? "" : $_POST['PlanLat'];
    $PlanLon     = (isset($_POST['PlanLon']) == "") ? "" : $_POST['PlanLon'];
    $ActualLat   = (isset($_POST['ActualLat']) == "") ? "" : $_POST['ActualLat'];
    $ActualLon   = (isset($_POST['ActualLon']) == "") ? "" : $_POST['ActualLon'];
    $ChkDistance = (isset($_POST['ChkDistance']) == "") ? "" : $_POST['ChkDistance'];
    $TarDistance = (isset($_POST['TarDistance']) == "") ? "" : $_POST['TarDistance'];

    $UKEY = $_SESSION['UKEY'];
    $SSID = $_SESSION['SSID'];

    $SQL1 =
        "UPDATE routetrip SET
            PlanLat           = NULLIF(:PlanLat,''),
            PlanLon           = NULLIF(:PlanLon,''),
            ActualStart       = NOW(),
            ActualLat         = NULLIF(:ActualLat,''),
            ActualLon         = NULLIF(:ActualLon,''),
            TarDistance       = NULLIF(:TarDistance,''),
            ChkDistance       = NULLIF(:ChkDistance,''),
            Actual_uKeyCreate = :UKEY,
            Actual_ssidCreate = :SSID,
            TripStatus        = '2'
        WHERE TripID = :TripID";
    $QRY1 = DBConnect("APP")->prepare($SQL1);
    $QRY1->bindparam(":PlanLat",     $PlanLat);
    $QRY1->bindparam(":PlanLon",     $PlanLon);
    $QRY1->bindparam(":ActualLat",   $ActualLat);
    $QRY1->bindparam(":ActualLon",   $ActualLon);
    $QRY1->bindparam(":TarDistance", $TarDistance);
    $QRY1->bindparam(":ChkDistance", $ChkDistance);
    $QRY1->bindparam(":UKEY",        $UKEY);
    $QRY1->bindparam(":SSID",        $SSID);
    $QRY1->bindparam(":TripID",      $TripID);
    if($QRY1->execute() === FALSE) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
    }
}

if($_GET['p'] == "AddChkDetail") {
    $TripID = $_POST['TripID'];
    $ActualDetail = (isset($_POST['ActualDetail']) == "") ? "" : $_POST['ActualDetail'];
    $UKEY = $_SESSION['UKEY'];
    $SSID = $_SESSION['SSID'];

    $SQL1 = 
        "UPDATE routetrip SET
            ActualDetail = NULLIF(:ActualDetail,''),
            uKeyUpdate   = :UKEY,
            ssidUpdate   = :SSID,
            DateUpdate   = NOW(),
            TripStatus   = '3'
        WHERE TripID = :TripID";
    $QRY1 = DBConnect("APP")->prepare($SQL1);
    $QRY1->bindparam(":ActualDetail", $ActualDetail);
    $QRY1->bindparam(":UKEY",         $UKEY);
    $QRY1->bindparam(":SSID",         $SSID);
    $QRY1->bindparam(":TripID",       $TripID);
    if($QRY1->execute() === FALSE) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
    }
}

if($_GET['p'] == 'CheckInReport') {
    $TripID = $_POST['TripID'];

    $SQL1 = 
        "SELECT 
            CONCAT(T0.CardCode, ' | ', T0.CardName) AS CardName, T0.PlanDetail, 
            T0.PlanStart, T0.PlanEnd, T0.PlanType, T0.ChkDistance, T0.PlanLat, T0.PlanLon,
            T0.ActualLat, T0.ActualLon, T0.TarDistance, T0.ActualStart, T0.ActualDetail,
            T0.ContactName, T0.ContactPhone, T0.ContactEmail, T0.ContactLINE, T0.TripStatus
        FROM routetrip T0
        WHERE T0.TripID = $TripID LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    $inval['Head'] = $RST1[0];
}

if($_GET['p'] == 'CancelTrip') {
    $TripID = $_POST['TripID'];
    $SSID   = $_SESSION['SSID'];
    $UKEY   = $_SESSION['UKEY'];
    $SQL1 = 
        "UPDATE routetrip SET 
            CANCELED = 'Y',
            TripStatus = '0',
            uKeyUpdate = :UKEY,
            ssidUpdate = :SSID,
            DateUpdate = NOW()
        WHERE TripID = $TripID";
    $QRY1 = DBConnect("APP")->prepare($SQL1);
    $QRY1->bindparam(":UKEY", $UKEY);
    $QRY1->bindparam(":SSID", $SSID);
    if($QRY1->execute() === FALSE) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
    }
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>