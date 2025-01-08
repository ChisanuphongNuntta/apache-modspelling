<?php
//header("Content-Type: application/vnd.ms-excel");
//header('Content-Disposition: attachment; filename="'.$_GET['sDoc'].'.xlsx"');
//header("Content-Type: application/force-download"); 
//header("Content-Type: application/octet-stream"); 
//header("Content-Type: application/download"); 
//header("Content-Transfer-Encoding: binary"); 
//header("Content-Length: ".filesize("myexcel.xls"));   
//@readfile($filename);  

date_default_timezone_set('Asia/Bangkok');
require("../core/Main.core.php");
require("../../".MainPathKSY()."/core/config.core.php");
require("../../".MainPathKSY()."/core/connect.core.php");
require("../../".MainPathKSY()."/core/functions.core.php");
require("../../".MainPathKSY()."/core/coresap.php");
session_start();
$getdata = new clear_db();
$connect = $getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
//$sapfqry = odbc_exec($sapconn,$_POST['sqlexp']);

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

require_once "/../../".MainPathKSY()."/PHPExcel/Classes/PHPExcel.php";
$thisYear = date("Y");
$lastYear = $thisYear-1;
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
                             ->setCategory("Test result file");
$styleArrayHtxt = array(
'font'  => array(
    'bold'  => true,
    'color' => array('rgb' => 'FF0000'),
));
$styleArrayHtxt2 = array(
    'font'  => array(
        'bold'  => true,
));
$styleArrayHtxt3 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '0A6319'),
));

$objPHPExcel->setActiveSheetIndex(0)
                             ->setCellValue('A1', 'NO')
                             ->setCellValue('B1', 'รหัสสินค้า')
                             ->setCellValue('C1', 'ชื่อสินค้า')
                             ->setCellValue('D1', 'SAP')
                             ->setCellValue('E1', 'นับจริง')
                             ->setCellValue('F1', 'สินค้าขาด')
                             ->setCellValue('G1', 'สินค้าเกิน');

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1')->applyFromArray($styleArrayHtxt2); 

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$WHScode = $_GET['WhsCode'];
$output = "";
$sqlOnKsy = "SELECT P1.ItemCode,P2.ItemName,P1.OnHand 
             FROM (SELECT T0.ItemCode,T0.WhsCode,SUM(T0.OnHand) AS OnHand 
                   FROM OITW T0 
                   WHERE T0.WhsCode = '".$WHScode."' 
                   GROUP BY T0.ItemCode,T0.WhsCode) P1 
                  JOIN OITM P2 ON P1.ItemCode = P2.ItemCode
             WHERE P1.ItemCode NOT LIKE '00-%'
             ORDER BY P1.ItemCode"; 


$sqlRack = "SELECT T0.ItemCode,T0.WhsCode,SUM(T0.OnHand) AS OnRack 
            FROM OITW T0 
            WHERE T0.WhsCode = '".$WHScode."' 
            GROUP BY T0.ItemCode,T0.WhsCode";
$getRack = $getdata->MySQL_SelectX($sqlRack);

$sapfqry = odbc_exec($sapconn,$sqlOnKsy);
$ax=0;
$DataOnSAP = "('";
while ($DataShow = odbc_fetch_array($sapfqry)){
    $ax++;
    $ItemCode[$ax] = $DataShow['ItemCode'];
    $ItemName[$ItemCode[$ax]] = conutf8($DataShow['ItemName']);
    $OnHand[$ItemCode[$ax]] = $DataShow['OnHand'];
    $newOnHand[$ItemCode[$ax]] = $OnHand[$ItemCode[$ax]];
    $dataGreen[$ItemCode[$ax]] = 0;
    $OnRack[$ItemCode[$ax]] = 0;
    $DataOnSAP .= $DataShow['ItemCode']."','";
    //$SAPOnHand[$ItemCode[$ax]] =  $DataShow['OnHand'];
}

while ($DataRack = mysql_fetch_object($getRack)){
    $OnRack[$DataRack->ItemCode] = $DataRack->OnRack;
    $newOnHand[$DataRack->ItemCode] = $OnHand[$DataRack->ItemCode] - $DataRack->OnRack;
    $dataGreen[$DataRack->ItemCode] = 1;
    //echo $DataRack->ItemCode;
}
$ax++;
$Row=1;
for ($i=2;$i<=$ax;$i++){
    $cx=$i-1;
    $Row++;
    $run=$Row-1;
    if ($newOnHand[$ItemCode[$cx]] != 0){
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$Row,$run)    
                    ->setCellValue('B'.$Row,$ItemCode[$cx])    
                    ->setCellValue('C'.$Row,$ItemName[$ItemCode[$cx]])
                    ->setCellValue('D'.$Row,$OnHand[$ItemCode[$cx]])
                    ->setCellValue('E'.$Row,$OnRack[$ItemCode[$cx]]);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.$Row)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.$Row)->getNumberFormat()->setFormatCode('#,##0');
        if ($newOnHand[$ItemCode[$cx]] > 0){
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('F'.$Row,$newOnHand[$ItemCode[$cx]]);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('F'.$Row)->getNumberFormat()->setFormatCode('#,##0');
        }else{
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('G'.$Row,$newOnHand[$ItemCode[$cx]]);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('G'.$Row)->getNumberFormat()->setFormatCode('#,##0');
        }
        if ($dataGreen[$ItemCode[$cx]] == 1){
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$Row.':G'.$Row)->applyFromArray($styleArrayHtxt); 
        }
    }else{
        $Row--;
    }
}
$DataOnSAP = substr($DataOnSAP,0,($DataOnSAP-2)).")";
$sql2 = "SELECT T0.ItemCode,T1.ItemName,T0.WhsCode,SUM(T0.OnHand) AS OnRack 
            FROM OITW T0 
            LEFT JOIN itemdata T1 ON T0.ItemCode = T1.ItemCode    
            WHERE T0.WhsCode = '".$WHScode."' AND T0.ItemCode NOT IN ".$DataOnSAP."
            GROUP BY T0.ItemCode,T0.WhsCode";
            //echo $sql2;
$get2 = $getdata->MySQL_SelectX($sql2);
while ($Data2 = mysql_fetch_object($get2)){
    $Row++;
    $run++;
    $objPHPExcel->setActiveSheetIndex(0) 
                ->setCellValue('A'.$Row,$run)    
                ->setCellValue('B'.$Row,$Data2->ItemCode)    
                ->setCellValue('C'.$Row,$Data2->ItemName)
                ->setCellValue('D'.$Row,0)
                ->setCellValue('E'.$Row,$Data2->OnRack)
                ->setCellValue('G'.$Row,-1*($Data2->OnRack));
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.$Row)->getNumberFormat()->setFormatCode('#,##0');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.$Row)->getNumberFormat()->setFormatCode('#,##0');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('G'.$Row)->getNumberFormat()->setFormatCode('#,##0');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$Row.':G'.$Row)->applyFromArray($styleArrayHtxt3); 
}


$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true); 
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true); 
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true); 
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true); 
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);

$filename = "KSM".date("YmdHi");
$callStartTime = microtime(true);
$getdata->my_sql_insert("ex_log","ukey = '".$_SESSION['ukey']."',file_log = '".$filename.".xlsx'");
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($filename.".xlsx");
$objWriter->save('php://output');
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;


?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<script langquage='javascript'>
    window.location="<?echo $filename;?>.xlsx";
</script>
<script>
    window.close();
</script>
</body>
</html>
