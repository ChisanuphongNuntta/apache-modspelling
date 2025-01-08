<?
$url = "https://192.168.1.11:4433/api/Documents/ORDR";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$data= "{

    'POSNumber': '11113337',
    'CardCode': 'C-00918',
    'CardName': 'WHAI_TEST',
    'DocDate': '2022-12-09',
    'DocDueDate': '2022-12-30',
    'TaxDate': '2022-12-30',
    'NumAtCard': 'NumAtCard_TEST',
    'CntctCode': 0,
    'SalesEmployee': 282,
    'OwnerCode': 50,
    'Comments': 'Comments-TEST1111',
    'ShipToCode': '',
    'PayToCode': '',
    'U_ShippingType': '002',

    'Lines': [
        {
            'ItemCode': '01-001-010',
            'ItemDescription': 'AAAF50_Test',
            'FreeText': 'FreeText_Test',
            'Quantity': 10,
            'ShipDate': '2022-12-30',
            'WhsCode': 'KSY',
            'UnitPrice': 10,
            'VatGroup': 'S07',
            'UomCode': 'Manual',
            'U_Disct1': 12.1,
            'U_Disct2': 13.1,
            'U_Disct3': 14.1,
            'U_Disct4': 15.1,
            'U_Disct5': 16.1
        },
        {
            'ItemCode': '01-001-010',
            'ItemDescription': 'dddKING_Test',
            'FreeText': 'FreeText_Test',
            'Quantity': 20,
            'ShipDate': '2022-12-30',
            'WhsCode': 'KSY',
            'UnitPrice': 10,
            'VatGroup': 'S07',
            'UomCode': 'Manual',
            'U_Disct1': 12.1,
            'U_Disct2': 13.1,
            'U_Disct3': 14.1,
            'U_Disct4': 15.1,
            'U_Disct5': 16.1
        }
    ]
}

";


curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$resp = curl_exec($curl);
curl_close($curl);
echo $data;
//var_dump($resp);
