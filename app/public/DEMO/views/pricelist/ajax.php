<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
require '../../assets/spreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
\PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());
$JSON  = array();
$inval = array();

$SiteID = $_SESSION['SITE']['site_id'];

if($_GET['p'] == 'GetItemList') {
    $SQL1 = "SELECT T0.* FROM price_detail T0 LEFT JOIN price_header T1 ON T0.ListID = T1.ListID WHERE T0.PriceStatus = 'A' AND T1.site_id = $SiteID";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST1) {

    }else{
        $SQL2 = "SELECT T0.[ItemCode], T0.[CodeBars], T0.[ItemName], T0.[LastPurPrc], T0.[OnHand] FROM OITM T0 ORDER BY T0.[ItemCode] ASC";
        $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll();
        foreach($RST2 as $ItemData) {
            $ItemList[$ItemData['ItemCode']]['CodeBars']   = SapTH($ItemData['CodeBars']);
            $ItemList[$ItemData['ItemCode']]['ItemName']   = SapTH($ItemData['ItemName']);
            $ItemList[$ItemData['ItemCode']]['LastPurPrc'] = $ItemData['LastPurPrc'];
            $ItemList[$ItemData['ItemCode']]['OnHand']     = $ItemData['OnHand'];
        }
        foreach($RST1 as $r=>$Data) {
            switch($Data['PriceType']) {
                case 'STD': $PriceType = "ราคามาตรฐาน"; break;
                default: $PriceType = ""; break;
            }

            
            $inval[$r]['PriceType'] = $PriceType;
            $inval[$r]['ItemCode'] = $Data['ItemCode'];
            $inval[$r]['ItemName'] = $ItemList[$Data['ItemCode']]['ItemName'];
            $inval[$r]['BarCode'] = $ItemList[$Data['ItemCode']]['CodeBars'];
            $inval[$r]['Cost'] = number_format($ItemList[$Data['ItemCode']]['LastPurPrc'],2);
            $inval[$r]['Stock'] = number_format($ItemList[$Data['ItemCode']]['OnHand'],0);

            $GPDis    = ($Data['Prc_Grand'] > 0)     ? (($Data['Prc_Grand'] - $ItemList[$Data['ItemCode']]['LastPurPrc']) / $Data['Prc_Grand']) * 100         : 0;
            $GPWSale  = ($Data['Prc_Wholesale'] > 0) ? (($Data['Prc_Wholesale'] - $ItemList[$Data['ItemCode']]['LastPurPrc']) / $Data['Prc_Wholesale']) * 100 : 0;
            $GPRetail = ($Data['Prc_Retail'] > 0)    ? (($Data['Prc_Retail'] - $ItemList[$Data['ItemCode']]['LastPurPrc']) / $Data['Prc_Retail']) * 100       : 0;

            $GPS1 = ($Data['Prc_Step1'] > 0) ? (($Data['Prc_Step1'] - $ItemList[$Data['ItemCode']]['LastPurPrc']) / $Data['Prc_Step1']) * 100 : 0;
            $GPS2 = ($Data['Prc_Step2'] > 0) ? (($Data['Prc_Step2'] - $ItemList[$Data['ItemCode']]['LastPurPrc']) / $Data['Prc_Step2']) * 100 : 0;
            $GPS3 = ($Data['Prc_Step3'] > 0) ? (($Data['Prc_Step3'] - $ItemList[$Data['ItemCode']]['LastPurPrc']) / $Data['Prc_Step3']) * 100 : 0;
            $GPS4 = ($Data['Prc_Step4'] > 0) ? (($Data['Prc_Step4'] - $ItemList[$Data['ItemCode']]['LastPurPrc']) / $Data['Prc_Step4']) * 100 : 0;
            
            $inval[$r]['PriceDis']    = number_format($Data['Prc_Grand'],2);
            $inval[$r]['GPDis']       = number_format($GPDis,2)."%";

            $inval[$r]['PriceWSale']  = number_format($Data['Prc_Wholesale'],2);
            $inval[$r]['GPWSale']     = number_format($GPWSale,2)."%";

            $inval[$r]['PriceRetail'] = number_format($Data['Prc_Retail'],2);
            $inval[$r]['GPRetail']    = number_format($GPRetail,2)."%";

            $inval[$r]['ValueS1'] = number_format($Data['Qty_Step1'],0);
            $inval[$r]['PriceS1'] = number_format($Data['Prc_Step1'],2);
            $inval[$r]['GPS1']    = number_format($GPS1,2)."%";

            $inval[$r]['ValueS2'] = number_format($Data['Qty_Step2'],0);
            $inval[$r]['PriceS2'] = number_format($Data['Prc_Step2'],2);
            $inval[$r]['GPS2']    = number_format($GPS2,2)."%";

            $inval[$r]['ValueS3'] = number_format($Data['Qty_Step3'],0);
            $inval[$r]['PriceS3'] = number_format($Data['Prc_Step3'],2);
            $inval[$r]['GPS3'] = number_format($GPS3,2)."%";

            $inval[$r]['ValueS4'] = number_format($Data['Qty_Step4'],0);
            $inval[$r]['PriceS4'] = number_format($Data['Prc_Step4'],2);
            $inval[$r]['GPS4']    = number_format($GPS4,2)."%";
        }
    }
}

if($_GET['p'] == 'ImportFile') {
    move_uploaded_file($_FILES["FileImport"]["tmp_name"],"../../FileImport/import_pricelist.xlsx");
	$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	$reader->setReadDataOnly(true);
	$spreadsheet = $reader->load("../../FileImport/import_pricelist.xlsx");
	$sheet = $spreadsheet->getSheet(1);
	$data = $sheet->toArray();
	$getData = array(); $r = 0; $v = 0;
	foreach ($data as $cell) {
		if($cell[0] != "") {
			$r++; $v = 1;
			foreach ($cell as $value) {
				$Col = $sheet->getCellByColumnAndRow($v, $r)->getParent()->getCurrentCoordinate(); $v++;
				$getData[$r][substr($Col,0,1)] = $value;
			}
		}
	}

    for($i = 2; $i <= $r; $i++) {
        // Check price_header
        $SQL1 = "SELECT T0.ListID, T0.GroupCode FROM price_header T0 WHERE T0.GroupCode = '".$getData[$i]['A']."' AND T0.site_id = $SiteID LIMIT 1";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
        if(!$RST1) {
            // INSERT price_header
            $SQL_INSERT1 = 
                "INSERT INTO price_header 
                SET site_id = :SiteID,
                    GroupCode = :GroupCode,
                    uKeyCreate = '".$_SESSION['UKEY']."',
                    DateCreate = NOW(),
                    ssidCreate = '".$_SESSION['SSID']."'";
            $CON_INSERT1 = DBConnect("APP");
            $QRY_INSERT1 = $CON_INSERT1->prepare($SQL_INSERT1);
            $QRY_INSERT1->bindparam(":SiteID", $SiteID);
            $QRY_INSERT1->bindparam(":GroupCode", $getData[$i]['A']);
            $RST_INSERT1 = $QRY_INSERT1->execute();

            $ListID = $CON_INSERT1->lastInsertId();
        } else {
            $ListID = $RST1[0]['ListID'];
        }

        // Check price_detail
        $SQL2 = "SELECT T0.PriceType FROM price_detail T0 LEFT JOIN price_header T1 ON T0.ListID = T1.ListID WHERE T0.PriceType = '".$getData[$i]['A']."' AND T1.site_id = $SiteID AND T0.ListID = $ListID AND T0.ItemCode = '".$getData[$i]['B']."' AND T0.PriceStatus = 'A'";
        $RST2 = DBConnect("APP")->query($SQL2)->fetchAll();
        if($RST2) {
            $SQL_UPDATE1 = 
            "UPDATE price_detail T0 SET
                T0.PriceStatus = 'I', T0.uKeyUpdate = '".$_SESSION['UKEY']."', T0.DateUpdate = NOW(), T0.ssidCreate = '".$_SESSION['SSID']."' 
            WHERE T0.PriceType = '".$getData[$i]['A']."' AND T0.ListID = $ListID AND T0.ItemCode = '".$getData[$i]['B']."' AND T0.PriceStatus = 'A'";
            $QRY_UPDATE1 = DBConnect("APP")->prepare($SQL_UPDATE1);
            $QRY_UPDATE1->execute();
        }

        // INSERT price_detail
        $SQL_INSERT2 = 
            "INSERT INTO price_detail 
            SET ListID = :ListID,
                PriceType = :PriceType,
                ItemCode = :ItemCode,
                Prc_Grand = NULLIF(:Prc_Grand,'0'),
                Prc_Retail = NULLIF(:Prc_Retail,'0'),
                Prc_Wholesale = NULLIF(:Prc_Wholesale,'0'),
                Prc_Step1 = NULLIF(:Prc_Step1,'0'),
                Prc_Step2 = NULLIF(:Prc_Step2,'0'),
                Prc_Step3 = NULLIF(:Prc_Step3,'0'),
                Prc_Step4 = NULLIF(:Prc_Step4,'0'),
                Qty_Step1 = NULLIF(:Qty_Step1,'0'),
                Qty_Step2 = NULLIF(:Qty_Step2,'0'),
                Qty_Step3 = NULLIF(:Qty_Step3,'0'),
                Qty_Step4 = NULLIF(:Qty_Step4,'0'),
                StartDate = NULLIF(:StartDate,''),
                EndedDate = NULLIF(:EndedDate,''),
                uKeyCreate = '".$_SESSION['UKEY']."',
                DateCreate = NOW(),
                ssidCreate = '".$_SESSION['SSID']."'";
        $QRY_INSERT2 = DBConnect("APP")->prepare($SQL_INSERT2);
        $QRY_INSERT2->bindparam(":ListID", $ListID);
        $QRY_INSERT2->bindparam(":PriceType", $getData[$i]['A']);
        $QRY_INSERT2->bindparam(":ItemCode", $getData[$i]['B']);
        $QRY_INSERT2->bindparam(":Prc_Grand", $getData[$i]['C']);
        $QRY_INSERT2->bindparam(":Prc_Retail", $getData[$i]['D']);
        $QRY_INSERT2->bindparam(":Prc_Wholesale", $getData[$i]['E']);
        $QRY_INSERT2->bindparam(":Prc_Step1", $getData[$i]['F']);
        $QRY_INSERT2->bindparam(":Prc_Step2", $getData[$i]['G']);
        $QRY_INSERT2->bindparam(":Prc_Step3", $getData[$i]['H']);
        $QRY_INSERT2->bindparam(":Prc_Step4", $getData[$i]['I']);
        $QRY_INSERT2->bindparam(":Qty_Step1", $getData[$i]['J']);
        $QRY_INSERT2->bindparam(":Qty_Step2", $getData[$i]['K']);
        $QRY_INSERT2->bindparam(":Qty_Step3", $getData[$i]['L']);
        $QRY_INSERT2->bindparam(":Qty_Step4", $getData[$i]['M']);
        $StartDate = ($getData[$i]['N'] != "") ? date("Y-m-d",(($getData[$i]['N']-25569)*86400)) : "";
        $EndedDate = ($getData[$i]['O'] != "") ? date("Y-m-d",(($getData[$i]['O']-25569)*86400)) : "";
        $QRY_INSERT2->bindparam(":StartDate", $StartDate);
        $QRY_INSERT2->bindparam(":EndedDate", $EndedDate);
        $RST_INSERT2 = $QRY_INSERT2->execute();
    }
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>