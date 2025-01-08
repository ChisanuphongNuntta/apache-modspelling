<?php
include('../../core/config.core.php');
include('../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();
$output = '';

// if ($_GET['a'] == 'read') {
//     $thisYear = $_POST['opY'];
//     $thisMonth = $_POST['opM'];
//     $lastMonth = $thisMonth - 1;
//     $BillPA = " ";
//     $BillSR = " ";

// $sql1 = "SELECT W1.*,W2.[DocNum] AS RefWMS

// FROM
//  (SELECT DISTINCT 'OINV' AS DocType,T0.[DocEntry],T2.[BeginStr] AS BillPrefix,T0.[DocNum] AS BillNo, T0.[NumAtCard] AS RefBill,T1.[BaseEntry],T0.[DocDate] AS BillDate, T0.[CardCode],T0.[CardName],T3.[SlpName] ,T0.[DocTotal] AS BillTotal,'1' AS lnRun,T0.ShipToCode
//              FROM OINV T0
//                  JOIN INV1 T1 ON T0.[DocEntry] = T1.[DocEntry]
//                  LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
//                  JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]

//              WHERE (MONTH(T0.[DocDate]) = '$thisMonth' AND YEAR(T0.[DocDate]) = '" . $thisYear . "') " . $BillSR . " AND T1.[BaseEntry] IS NOT NULL 
//  UNION ALL 
//  SELECT DISTINCT 'ODLN' AS DocType,T0.[DocEntry],T2.[BeginStr] AS BillPrefix,T0.[DocNum] AS BillNo, T0.[NumAtCard] AS RefBill,T1.[BaseEntry],T0.[DocDate] AS BillDate, T0.[CardCode],T0.[CardName],T3.[SlpName] ,T0.[DocTotal] AS BillTotal, '0' AS lnRun,T0.ShipToCode
//              FROM ODLN T0
//                  JOIN DLN1 T1 ON T0.[DocEntry] = T1.[DocEntry]
//                  LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
//                  JOIN OSLP T3 ON T0.[SlpCode] = T3.[SlpCode]

//              WHERE (T2.[BeginStr] LIKE 'PA-%' OR T2.[BeginStr] LIKE 'PB-%' OR T2.[BeginStr] LIKE 'PC-%' OR T2.[BeginStr] LIKE 'PD-%') AND (MONTH(T0.[DocDate]) = '" . $thisMonth . "' AND YEAR(T0.[DocDate]) = '" . $thisYear . "') " . $BillPA . " AND T1.[BaseEntry] IS NOT NULL  
//  ) W1
//  JOIN ORDR W2 ON W1.BaseEntry = W2.DocEntry
// ORDER BY W1.lnRun DESC, W1.BillDate";
//     //echo $sql1;
//     $getSAP = SAPSelect($sql1);
//     $cx = 0;
//     $OINV = "(";
//     $ODLN = "(";
//     while ($DataBill = odbc_fetch_array($getSAP)) {
//         $cx++;
//         $Data_DocNo[$cx] = $DataBill['DocEntry'];
//         $Data_DocType[$cx] = $DataBill['DocType'];

//         $datax[$DataBill['DocType']][$DataBill['DocEntry']];
//         $CHKLoop[$cx] = $datax[$DataBill['DocType']][$DataBill['DocEntry']];

//         /*$Data_DataType[$CHKLoop[$cx]] = $DataBill['DocType'];

//         $Data_DocEntry[$CHKLoop[$cx]] = $CHKLoop[$cx];
//         $Data_NumAtCard[$CHKLoop[$cx]] = $DataBill['RefBill'];
//         $Data_BeginStr[$CHKLoop[$cx]] = $DataBill['BillPrefix'];
//         $Data_DocNum[$CHKLoop[$cx]] = $DataBill['BillNo'];
//         $Data_BillTotal[$CHKLoop[$cx]] = $DataBill['BillTotal'];
//         $Data_CardCode[$CHKLoop[$cx]] = $DataBill['CardCode'];
//         $Data_CardName[$CHKLoop[$cx]] = $DataBill['CardName']; //
//         $Data_SlpName[$CHKLoop[$cx]] = $DataBill['SlpName']; //
//         $Data_BillDate[$CHKLoop[$cx]] = $DataBill['BillDate'];
//         $Data_SONumber[$CHKLoop[$cx]] = $DataBill['RefWMS']; //
//         $Data_ShipTo[$CHKLoop[$cx]] = $DataBill['ShipToCode']; //
//         $Data_LoadDate[$CHKLoop[$cx]] = '';
//         */
//         $Data_CardCode[$CHKLoop[$cx]] = $DataBill['CardCode'];
//         $Data_CardName[$CHKLoop[$cx]] = $DataBill['CardName'];

//         $Data_DataType[$Data_DocNo[$cx]] = $DataBill['DocType'];
//         $Data_DocEntry[$DataBill['DocEntry']] = $DataBill['DocEntry'];
//         $Data_NumAtCard[$DataBill['DocEntry']] = $DataBill['RefBill'];
//         $Data_BeginStr[$DataBill['DocEntry']] = $DataBill['BillPrefix'];
//         $Data_DocNum[$DataBill['DocEntry']] = $DataBill['BillNo'];
//         $Data_BillTotal[$DataBill['DocEntry']] = $DataBill['BillTotal'];
//         //$Data_CardCode[$DataBill['DocEntry']] = $DataBill['CardCode'];
//         //$Data_CardName[$DataBill['DocEntry']] = $DataBill['CardName']; //
//         $Data_SlpName[$DataBill['DocEntry']] = $DataBill['SlpName']; //
//         $Data_BillDate[$DataBill['DocEntry']] = $DataBill['BillDate'];
//         $Data_SONumber[$DataBill['DocEntry']] = $DataBill['RefWMS']; //
//         $Data_ShipTo[$DataBill['DocEntry']] = $DataBill['ShipToCode']; //
//         $Data_LoadDate[$DataBill['DocEntry']] = '';


//         if ($DataBill['lnRun'] == 1) {
//             $Data_Table[$DataBill['DocEntry']] = "OINV";
//             $OINV .= $DataBill['DocEntry'].",";
//             $NameLogi[$Data_Table[$DataBill['DocEntry']]][$DataBill['DocEntry']] = '';

//         } else {
//             $Data_Table[$DataBill['DocEntry']] = "ODLN";
//             $ODLN .= $DataBill['DocEntry'].",";
//             $NameLogi[$Data_Table[$DataBill['DocEntry']]][$DataBill['DocEntry']] = '';
//         }
//     }
//     $OINV = substr($OINV,0,-1).")";
//     $ODLN = substr($ODLN,0,-1).")";
//     $sqlOINV = "SELECT BillEntry,DATE(OutTime) AS DateLogi FROM logi_detail WHERE BillType = 'OINV' AND BillEntry IN ".$OINV;
//     $sqlODLN = "SELECT BillEntry,DATE(OutTime) AS DateLogi FROM logi_detail WHERE BillType = 'ODLN' AND BillEntry IN ".$ODLN;
//     $sqlINV1 = "SELECT T0.DocEntry,T1.loginame FROM billsr T0 LEFT JOIN logistic T1 ON T1.LogiID = T0.logi_ukey WHERE T0.DocEntry IN ".$OINV; 
//     $sqlDLN1 = "SELECT T0.DocEntry,T1.loginame FROM billpa T0 LEFT JOIN logistic T1 ON T1.LogiID = T0.logi_ukey WHERE T0.DocEntry IN ".$ODLN; 


//     ///echo $sqlINV1;
//     $getOINV = MySQLSelectX($sqlOINV);
//     $getODLN = MySQLSelectX($sqlODLN);

//     $getINV1 = MySQLSelectX($sqlINV1);
//     $getDLN1 = MySQLSelectX($sqlDLN1);
//     while ($ListOINV = mysqli_fetch_array($getOINV)){
//         $Data_LoadDate[$ListOINV['BillEntry']] = $ListOINV['DateLogi'];
//     }

//     while ($ListODLN = mysqli_fetch_array($getODLN)){
//         $Data_LoadDate[$ListODLN['BillEntry']] = $ListODLN['DateLogi'];
//     }
//     while ($ListINV1 = mysqli_fetch_array($getINV1)){
//         $NameLogi['OINV'][$ListINV1['DocEntry']] = $ListINV1['loginame'];


//     }


//     while ($ListDLN1 = mysqli_fetch_array($getDLN1)){
//         $NameLogi['ODLN'][$ListDLN1['DocEntry']] = $ListDLN1['loginame'];

//     }



//     $output = "";
//     //echo $cx;
//     for ($i = 1; $i <= $cx; $i++) {

//         // $output .= "<tr>
//         //                 <th scope='row'>
//         //                     <label class='control control--checkbox'>
//         //                         <input type='checkbox'>
//         //                         <div class='control__indicator'></div>
//         //                     </label>
//         //                 </th>
//         //                 <td>1392</td>
//         //                 <td>James Yates</td>
//         //                 <td>Web Designer</td>
//         //                 <td>+63 983 0962 971</td>
//         //                 <td>NY University</td>
//         //                 <td>12/02/2000</td>
//         //                 <td>James Yates</td>
//         //             </tr>";
//         $output .= "<tr>
//         <th scope='row'>
//             <label class='control control--checkbox'>";
//         //$output .= "<td><input type='checkbox' id='" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "' class='chklogi' onclick=\"chkdataX('" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "')\" disabled></td>";

//         "<div class='control__indicator'></div>
//             </label>
//         </th>";
//         if ($Data_NumAtCard[$Data_DocNo[$i]] != '') {
//             $output .= "<td class='txtc'>" . $Data_NumAtCard[$Data_DocNo[$i]] . "</td>";
//         } else {
//             $output .= "<td class='txtc'>" . $Data_BeginStr[$Data_DocNo[$i]] . $Data_DocNum[$Data_DocNo[$i]] . "</td>";
//         }
//         $output .= "<td class='txtc'>" . date('d/m/Y', strtotime($Data_BillDate[$Data_DocNo[$i]])) . "</td>";
//         $output .= "<td class='txtl'>";

//         if (substr($Data_CardCode[$Data_DocNo[$i]], 0, 1) == 'A') {
//             $output .= $Data_CardCode[$CHKLoop[$i]] . " - " . conutf8($Data_CardName[$CHKLoop[$i]]) . " [" . conutf8($Data_ShipTo[$Data_DocNo[$i]]) . "]";
//         } else {
//             $output .= $Data_CardCode[$CHKLoop[$i]] . " - " . conutf8($Data_CardName[$CHKLoop[$i]]);
//         }

//         $output .= "</td>";
//         $output .= "<td class='txtl'>" . conutf8($Data_SlpName[$Data_DocNo[$i]]) . "</td>";
//         $output .= "<td class='txtr'>" . number_format($Data_BillTotal[$Data_DocNo[$i]], 2) . "</td>";



//         if ($Data_LoadDate[$Data_DocNo[$i]] != '') {
//             //$LogiDate = MySQLSelect($sql1);
//             $output .= "<td class='txtc'>";
//             $output .= "<input type='text' name='logidate_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "' id='logidate_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "'  value='" . date('Y-m-d', strtotime($Data_LoadDate[$Data_DocNo[$i]])) . "' class='form-control' text-align:center;' readonly>";
//             $output .= "</td>";
//         } else {
//             $output .= "<td class='txtc'>";
//             $output .= "<input type='date' name='logidate_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "' id='logidate_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "' value='" . date('Y-m-d') . "' class='dateinput2 form-control' style='width:100px; text-align:center;'>";

//             $output .= "</td>";
//         }

//         $output .= "<td width='1'><input id='".$Data_Table[$Data_DocNo[$i]]."_".$Data_DocEntry[$Data_DocNo[$i]]."' type='checkbox' class='' id='date_' value=''></td>";

//         $output .= "<td><span id='nameshow_" . $Data_Table[$Data_DocNo[$i]] . "_" . $Data_DocEntry[$Data_DocNo[$i]] . "'>".$NameLogi[$Data_Table[$Data_DocNo[$i]]][$Data_DocNo[$i]]."</span ></td>


//     </tr> 
//     ";
//     }
// }
// Database
// http://192.168.1.9:8080/phpmyadmin/
// uer: kbi
// pass: p@ssw0rd!
$current_timestamp_by_mktime = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$timestamp = date("Y-m-d h:i:sa", $current_timestamp_by_mktime);

if ($_GET['a'] == 'page1') {
    $stPhone = CheckPhoneNumber($_POST['phone']);
    if ($stPhone != '') {
        $thisName = $_POST['name'];
        $thisEmail = $_POST['email'];
        $thisAge = $_POST['age'];
        $thisPhone =  $stPhone;
        $thisLine = $_POST['line'];
        $thisGender = $_POST['gender'];
        $thisJob = $_POST['job'];
        if ($thisName != '' && $thisAge != '' && $thisPhone) {
            $arrCol['profile_name'] =  $thisName;
            $arrCol['profile_timestamp'] =  $timestamp;

            if (isset($_SESSION['statusPage1'])) {
                // เคย submit แล้ว
                $statusPage1 = $_SESSION['statusPage1'];
                $sql4 = "UPDATE `wrt_profile` SET `profile_name`='$thisName',`profile_email`='$thisEmail',`profile_age`='$thisAge',`profile_phone`='$thisPhone',`profile_lineId`='$thisLine',`profile_gender`='$thisGender',`profile_job`='$thisJob',`profile_timestamp`='$timestamp' WHERE `profile_id` = $statusPage1";
                MySQLUpdate($sql4);
            } else {
                // ยังไม่เคย submit
                $sql1 = "INSERT INTO wrt_profile (profile_id, profile_name, profile_email, profile_age, profile_phone, profile_lineId, profile_gender, profile_job, profile_timestamp)
                    VALUES (NULL, '$thisName', '$thisEmail', '$thisAge', '$thisPhone', '$thisLine', '$thisGender', '$thisJob', '$timestamp')";
                $statusInsert = MySQLInsert($sql1);
                $_SESSION['statusPage1'] = $statusInsert;
            }
        }
        $output = 'success';
    } else {
        $output = '';
    }
}
///////////////////////////////////////////////////////////////////
if ($_GET['a'] == 'page2') {
    $thisDate = $_POST['date'];
    $thisObjective = $_POST['objective'];
    $thisMarket = $_POST['market'];
    $thisBranch = $_POST['branch'];
    if ($thisDate != '' && $thisObjective != '' && $thisMarket != '' && $thisBranch) {
        $arrCol['date'] = $thisDate;
        $arrCol['objective'] = $thisObjective;
        $arrCol['market'] = $thisMarket;
        $arrCol['branch'] = $thisBranch;
        if (isset($_SESSION['statusPage1'])) {
            if (isset($_SESSION['statusPage2'])) {
                // เคย submit แล้ว
                $statusPage2 = $_SESSION['statusPage2'];
                $sql2 = "UPDATE `wrt_buyproducts` SET `buy_date`='$thisDate',`buy_objective`='$thisObjective',`buy_market`='$thisMarket',`buy_branch`='$thisBranch',`buy_timestamp`='$timestamp' WHERE `buy_id` = '$statusPage2'";
                MySQLUpdate($sql2);
            } else {
                // ยังไม่เคย submit
                $statusPage1 = $_SESSION['statusPage1'];
                $sql1 = "INSERT INTO `wrt_buyproducts`(`buy_id`, `profile_id`, `buy_date`, `buy_objective`, `buy_market`, `buy_branch`,`buy_timestamp`) VALUES (Null, '$statusPage1','$thisDate','$thisObjective','$thisMarket','$thisBranch','$timestamp')";
                $statusInsert = MySQLInsert($sql1);
                $_SESSION['statusPage2'] = $statusInsert;
            }
            $output = 'success';
        } else {
            $output = '';
        }
    }
}
///////////////////////////////////////////////////////////////////
if ($_GET['a'] == 'page3') {
    $thisNameProduct = $_POST['nameProduct'];
    $thisSerialNumber = $_POST['serialNumber'];
    $thisProduct = $_POST['product'];

    if ($_POST['brand'] == 'other') {
        $thisBrand = $_POST['otherTxt'];
        if ($thisNameProduct != '' && $thisProduct != '' && $thisBrand != '') {
            if (isset($_SESSION['statusPage2'])) {
                $statusPage2 = $_SESSION['statusPage2'];
                $sql2 = "UPDATE `wrt_buyproducts` SET `buy_brand`='$thisBrand',`buy_product`='$thisProduct',`buy_nameProduct`='$thisNameProduct',`buy_serialNumber`='$thisSerialNumber',`buy_timestamp`='$timestamp' WHERE `buy_id` = '$statusPage2'";
                MySQLUpdate($sql2);
                $arrCol['nameProduct'] = $thisNameProduct;
                $arrCol['serialNumber'] = $thisSerialNumber;
                $arrCol['product'] = $thisProduct;
                $arrCol['brand'] = $thisBrand;
                $output = 'success';
            } else {
                $output = '';
            }
            session_destroy();
        }
    }
}
///////////////////////////////////////////////////////////////////
/*  callBranch */
if ($_GET['a'] == 'callBranch') {
    $sql1 = "SELECT CardCode, case when BName = '' then CardName else BName end as BName FROM `ocrd` WHERE MTGroup = '" . $_POST['branch'] . "' AND CardStatus = 'A'";
    $getName = MySQLSelectX($sql1);
    while ($ShowName = mysqli_fetch_array($getName)) {
        $output .= "<option value='" . $ShowName['CardCode'] . "'>" . $ShowName['BName'] .  " </option>";
    }
}
/*  callProduct*/
if ($_GET['a'] == 'callProduct') {
    $sql = "SELECT T0.Code, T0.Name FROM [dbo].[@ITEMGROUP1] T0 WHERE T0.Code != 'B00002' AND T0.Code NOT LIKE 'B0002%'";
    $SapQRY = SAPSelect($sql);
    while ($SapRST = odbc_fetch_array($SapQRY)) {
        $output .= "<div class='form-check'>" .
                        "<input id=". $SapRST['Code'] ." name='product' type='radio' class='form-check-input' value=" . $SapRST['Code'] . " required=''>
                        <label class='form-check-label'>" . conutf8($SapRST['Name']) . "</label>
                    </div>";
    }
}
/* Check PhoneNumber */
function CheckPhoneNumber($phone)
{
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    $phone_to_check = str_replace("-", "", $filtered_phone_number);
    if (strlen($phone_to_check) != 10) {
        return '';
    } else {
        return $phone_to_check;
    }
}

$arrCol['output'] = $output;
array_push($resultArray, $arrCol);
echo json_encode($resultArray);
