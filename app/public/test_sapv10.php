<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
define('MS_DRV','{SQL Server Native Client 11.0}');
define('SAP_HOST', 'SERVER2\SERVER2');
define('SAP_USERNAME', 'sa');
define('SAP_PASSWORD', 'p@ssw0rd');
define('SAP_NAME', 'SBO_KBI');


function SAPSelect($SqlStatement){
	$sap_conn = odbc_connect("DRIVER=".MS_DRV.";charset=UTF-8;SERVER=".SAP_HOST.";DATABASE=".SAP_NAME ,SAP_USERNAME, SAP_PASSWORD) or die ("Cannot Connect to SAP Database!");
	$sap_qury = odbc_exec($sap_conn, $SqlStatement);
	return $sap_qury;
}

function conutf8($ThaiText) { /* แสดงผลภาษาไทย */
    // return iconv("ISO-IR-166", "UTF-8", $ThaiText); มีปัญหาเรื่องแสดงผล Space Bar ภาษาไทย
    return str_replace("?","",iconv("ISO-8859-11", "UTF-8", $ThaiText));
}

function SapTHSearch($UTF8Text) { /* แปลง UTF-8 เป็น ISO-8859-11 เพื่อค้นหาด้วยภาษาไทย */
    return iconv("UTF-8", "ISO-8859-11", $UTF8Text);
}

$SAPSQL = "SELECT CardCode, CardName FROM OCRD WHERE CardCode LIKE 'C-%' ORDER BY CardCode";
// $SAPSQL = "SELECT CardCode, CardName FROM OCRD WHERE CardName LIKE N'%".SapTHSearch("เรืองแสง")."%' ORDER BY CardCode";
$SAPQRY = SAPSelect($SAPSQL);

echo $SAPSQL."<br/>";

while($result = odbc_fetch_array($SAPQRY)) {
    echo $result['CardCode']." ".conutf8($result['CardName'])."<br/>";
}

?>
</body>
</html>
