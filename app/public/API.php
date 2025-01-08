<?php
$url = "http://192.168.1.11/dev/api/Documents/ORDR";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Content-Type: application/json",
);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$SAP[0]['POSNumber']     = "2";
$SAP[0]['CardCode']     = "C-00918";
$SAP[0]['CardName']     = "เรืองแสงไทย";
$SAP[0]['DocDate']     = "2022-12-09";
$SAP[0]['DocDueDate']     = "2022-12-10";
$SAP[0]['TaxDate']     = "2022-12-10";
$SAP[0]['NumAtCard']     = "";
$SAP[0]['CntctCode']     = 0;
$SAP[0]['SalesEmployee']     = -1;
$SAP[0]['OwnerCode']     = 8;
$SAP[0]['Comments']     = "Test Comment";
$SAP[0]['ShipToCode']     = "";
$SAP[0]['PayToCode']     = "";
$SAP[0]['U_ShippingType']     = "";



$SAP[0]['Lines'][0]['ItemCode']        = "02-065-010";
$SAP[0]['Lines'][0]['ItemDescription'] = "ปืนยิงตะปู F30";
$SAP[0]['Lines'][0]['FreeText'] = "";
$SAP[0]['Lines'][0]['Quantity'] = 10;
$SAP[0]['Lines'][0]['ShipDate'] = "2022-12-10";
$SAP[0]['Lines'][0]['WhsCode'] = "KSY";
$SAP[0]['Lines'][0]['UnitPrice'] = 1250.000;
$SAP[0]['Lines'][0]['VatGroup'] = "S07";
$SAP[0]['Lines'][0]['UomCode'] = "Manual";
$SAP[0]['Lines'][0]['U_Disct1'] = 0;
$SAP[0]['Lines'][0]['U_Disct2'] = 0;
$SAP[0]['Lines'][0]['U_Disct3'] = 0;
$SAP[0]['Lines'][0]['U_Disct4'] = 0;


$myJSON = json_encode($SAP);
//echo $myJSON;

//echo $curl;
//$data = $curl;

curl_setopt($curl, CURLOPT_POSTFIELDS, $myJSON);
/*
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
*/
$resp = curl_exec($curl);

curl_close($curl);
//echo $resp;
var_dump($resp);

?>