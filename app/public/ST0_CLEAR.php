<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
$OLDCON = mysqli_connect("192.168.1.12","kbi","passw0rd!","kbidb");
$NEWCON = mysqli_connect("localhost","kbi","p@ssw0rd!","kbidb");
echo "<style> body { background-color: #000; color: #00FF00; font-size: 14px; } a { color: #FFF; }</style>";
echo "<pre>";
echo "<h1>TRANSFER DATA WIZARD - CLEANING TABLE</h1>";

/* DROP */
$DropID = ["tmpprice","tmppricelist"];
for($i=0;$i<count($DropID);$i++) {
    $DropSQL = "DROP TABLE IF EXISTS $DropID[$i]";
    mysqli_query($NEWCON,$DropSQL) or die("0. Cannot DROP Table $DropID[$i]!<br/>");
}

/* STEP 0 */
$TableID = [
    "picker_soheader", "picker_sodetail",
    "pack_header", "pack_list", "pack_boxlist", "pack_tran",
    "apporder", "billpa", "billsr", "chq_return", "chq_detail", "chq_remark",
    "collect_remark","crapp","loglogin",
    "docacc_header", "docacc_remark", "docwho_header", "docwho_remark",
    "logi_head", "logi_detail", "log_acreceipt", "log_actarget",
    "memo_header", "memo_attach", "memo_approve", "note_billing",
    "order_header", "order_detail", "order_attach",
    "owas", "was1", "was2", "was3", "packer_online",
    "purreq_attach", "purreq_detail", "purreq_header",
    "route_action", "route_checkin", "route_planner", "route_survey",
    "sa04_approve", "sa04_detaila", "sa04_detailb", "sa04_detailc",
    "sa04_header", "ship_detail", "ship_header", "whsequota_header","tranwhs",
    "oitw","whsquota","whsquota_trn","transecdata"
];

// $TableID = ["ship_detail","ship_header"];
for($i=0;$i<count($TableID);$i++) {
    $ClearSQL = "TRUNCATE TABLE $TableID[$i]";
    mysqli_query($NEWCON,$ClearSQL) or die("0. Cannot TRUNCATE Table $TableID[$i]!<br/>");
}
echo "0. TRUNCATE TABLE Completed! (".count($TableID)." Tables)<br/>";
echo "<br/>";
echo "Click <a href='ST1_PICKPACK.php'>here</a> to run next step";
echo "</pre>";

?>