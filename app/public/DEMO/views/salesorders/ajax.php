<?php session_start();
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
require_once("../../core/functions.core.php");
$JSON  = array();
$inval = array();

$SiteID = $_SESSION['SITE']['site_id'];
$LvCode = $_SESSION['LVCODE'];

if($_GET['p'] == "GetAppData") {
    $SQL1 = "SELECT T0.[CardCode], T0.[CardName] FROM OCRD T0 WHERE (T0.[CardType] = 'C' AND T0.[CardName] IS NOT NULL AND T0.[validFor] = 'Y') ORDER BY T0.[CardCode]";
    $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll();

    if(!$RST1) {
        $inval['Status'] = "ERR1";
    } else {
        $inval['Status'] = "OK";
        $inval['OCRD']['Row'] = 0;
        foreach($RST1 as $key => $value) {
            $inval['OCRD'][$key]['CardCode'] = SapTH($value['CardCode']);
            $inval['OCRD'][$key]['CardName'] = SapTH($value['CardName']); 
            $inval['OCRD']['Row']++;
        }
    }

    $SQL2 = "SELECT T0.[SlpCode], T0.[SlpName] FROM OSLP T0 WHERE T0.[Active] = 'Y' ORDER BY T0.[SlpName], T0.[SlpCode]";
    $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll();

    if(!$RST2) {
        $inval['Status'] = "ERR2";
    } else {
        $inval['Status'] = "OK";
        $inval['OSLP']['Row'] = 0;
        foreach($RST2 as $key => $value) {
            $inval['OSLP'][$key]['SlpCode'] = SapTH($value['SlpCode']);
            $inval['OSLP'][$key]['SlpName'] = SapTH($value['SlpName']);
            $inval['OSLP']['Row']++;
        }
    }

    $SQL3 = "SELECT T0.[GroupNum], T0.[PymntGroup] FROM OCTG T0 ORDER BY T0.[GroupNum]";
    $RST3 = DBConnect("SAP")->query(SQLtoHANA($SQL3))->fetchAll();
    if(!$RST3) {
        $inval['Status'] = "ERR3";
    } else {
        $inval['Status'] = "OK";
        $inval['OCTG']['Row'] = 0;
        foreach($RST3 as $key => $value) {
            $inval['OCTG'][$key]['GroupNum']   = SapTH($value['GroupNum']);
            $inval['OCTG'][$key]['PymntGroup'] = SapTH($value['PymntGroup']);
            $inval['OCTG']['Row']++;
        }
    }
    if ($LvCode == 'O0007'){
        $ifOnline = " ";
    }else{
        $ifOnline = " AND T0.[QryGroup1] = 'N' ";
    }

    $SQL4 = "SELECT T0.[ItemCode], T0.[CodeBars], T0.[ItemName],   T1.[UomCode] AS [SalUnitMsr]  FROM OITM T0 LEFT JOIN OUOM T1 ON T1.UomEntry = T0. SUoMEntry   WHERE T0.[ValidFor] = 'Y' AND T0.[ItemName] IS NOT NULL ".$ifOnline." ORDER BY T0.[ItemCode] ASC";
    $RST4 = DBConnect("SAP")->query(SQLtoHANA($SQL4))->fetchAll();
    if(!$RST4) {
        $inval['Status'] = "ERR4";
    } else {
        $inval['Status'] = "OK";
        $inval['OITM']['Row'] = 0;
        foreach($RST4 as $key => $value) {
            $inval['OITM'][$key]['ItemCode'] = SapTH($value['ItemCode']);
            $inval['OITM'][$key]['CodeBars'] = SapTH($value['CodeBars']);
            // $inval['OITM'][$key]['ItemName'] = str_replace("'", "'", SapTH($value['ItemName']));
            $inval['OITM'][$key]['ItemName'] = SapTH($value['ItemName']);
            $inval['OITM'][$key]['UnitMsr']  = SapTH($value['SalUnitMsr']);
            $inval['OITM']['Row']++;
        }
    }

    // $SQL5 = "SELECT T0.[FldValue], T0.[Descr], CASE WHEN T0.[FldValue] = '99' THEN 'Y' ELSE 'N' END AS [OptDft] FROM UFD1 T0 WHERE T0.[TableID] = 'ORDR' AND T0.[FieldID] = 30 ORDER BY T0.[IndexID]";
    // $RST5 = DBConnect("SAP")->query(SQLtoHANA($SQL5))->fetchAll();
    // if(!$RST5) {
    //     $inval['Status'] = "ERR5";
    // } else {
    //     $inval['Status'] = "OK";
    //     $inval['SO_TYPE']['Row'] = 0;
    //     foreach($RST5 as $key => $value) {
    //         $inval['SO_TYPE'][$key]['Value']   = $value['FldValue'];
    //         $inval['SO_TYPE'][$key]['Text']    = SapTH($value['Descr']);
    //         $inval['SO_TYPE'][$key]['Default'] = $value['OptDft'];
    //         $inval['SO_TYPE']['Row']++;
    //     }
    // }
}

if($_GET['p'] == "GetCardInfo") {
    $CardCode = $_POST['CardCode'];
    $SQL1 =
        "SELECT 
            T0.[CardCode], T0.[AdresType] AS [AddressType], T0.[Address] AS [AddressID], (T0.[Street]+' '+T0.[Block]+' '+T0.[City]+' '+T0.[County]+' '+CAST(T0.[ZipCode] AS VARCHAR)) AS [FullAddress],
            CASE WHEN T0.[AdresType] = 'B' THEN T1.[BilltoDef] ELSE T1.[ShiptoDef] END AS [AddressDef], T1.[SlpCode], T1.[LicTradNum], T1.[GroupNum], T1.[CreditLine], T1.[Balance],
            CASE WHEN T1.[Country] = 'TH' THEN 'SD' ELSE 'SO' END AS [CardType],
            CASE WHEN T1.[ECVatGroup] = 'S07' THEN T1.[ECVatGroup] ELSE 'S00' END   AS [VatGroup],T0.[Address3]
        FROM CRD1 T0
        LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
        WHERE T0.[CardCode] = '$CardCode'
        ORDER BY T0.[AdresType], T0.[LineNum]";
    $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll();
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
        $outputBD = "";
        $outputSD = "";
        $outputB = "";
        $outputS = "";
        $outputSlp = "";
        $outputTaxID = "";
        $GroupNum = "";
        $VatGroup  ="";

        foreach($RST1 as $value) {
            if($value['AddressType'] == "B") {
                $BillDefault = SapTH($value['AddressDef']);
                if(SapTH($value['AddressID']) == $BillDefault) {
                    $class = " class='fw-bolder'";
                    $text = " (ค่าเริ่มต้น)";
                } else {
                    $class = null;
                    $text = null;
                }
                $outputB .= "<option value='".SapTH($value['AddressID'])."'".$class.">[".SapTH($value['Address3'])."] ".SapTH($value['FullAddress']).$text."</option>";
			    $outputBD = SapTH($value['AddressDef']);
            } else {
                $ShipDefault = SapTH($value['AddressDef']);
                if(SapTH($value['AddressID']) == $ShipDefault) {
                    $class = " class='fw-bolder'";
                    $text = " (ค่าเริ่มต้น)";
                } else {
                    $class = null;
                    $text = null;
                }
                $outputS .= "<option value='".SapTH($value['AddressID'])."'".$class.">[".SapTH($value['Address3'])."] ".SapTH($value['FullAddress']).$text."</option>";
			    $outputSD = SapTH($value['AddressDef']);
            }
            $outoutSlp   = $value['SlpCode'];
		    $outputTaxID = $value['LicTradNum'];
            $GroupNum    = $value['GroupNum'];
            $CardType    = $value['CardType'];
            $VatGroup    = $value['VatGroup'];
            $Balance     = number_format($value['Balance'],0);
            $CreditLine  = number_format($value['CreditLine'],0);

        }
        $inval['outputB']    = $outputB;
        $inval['outputS']    = $outputS;
        $inval['BilltoCode'] = $outputBD;
        $inval['ShiptoCode'] = $outputSD;
        $inval['SlpCode']    = $outoutSlp;
        $inval['TaxID']      = $outputTaxID;
        $inval['GroupNum']   = $GroupNum;
        $inval['CardType']   = $CardType;
        $inval['Balance']    = $Balance;
        $inval['CreditLine'] = $CreditLine;
        $inval['VatGroup'] = $VatGroup;
    }
}

if($_GET['p'] == "GetItemDetail") {
    $CardCode = $_POST['CardCode'];
    $ItemCode = $_POST['ItemCode'];
    $Quantity = $_POST['Quantity'];
    
    /* Item History */
    $SQL1 = 
        "SELECT TOP 3
            T1.[DocDate], T0.[Quantity], T0.[UnitMsr], T0.[PriceBefDi], 
            T0.[U_DiscP1], T0.[U_DiscP2], T0.[U_DiscP3], T0.[U_DiscP4], T0.[U_DiscP5], 
            T0.[Price], T0.[VatSum]
        FROM INV1 T0 
        LEFT JOIN OINV T1 ON T0.[DocEntry] = T1.[DocEntry] 
        WHERE T0.[ItemCode] = '$ItemCode' AND T1.[CardCode] = '$CardCode' AND T0.[PriceAfVat] > 0
        ORDER BY T1.[DocEntry] DESC";
    $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll();
    if(!$RST1) {
        $inval['History']['Row'] = 0;
    } else {
        $inval['History']['Row'] = 0;
        foreach($RST1 as $key => $value) {
            $Discount = 0;
			if ($value['U_DiscP5'] != NULL && $value['U_DiscP5'] != "" && $value['U_DiscP5'] != 0.00) {
				$Discount = number_format($value['U_DiscP1'], 2) . "%+" . number_format($value['U_DiscP2'], 2) . "%+" . number_format($value['U_DiscP3'], 2) . "%+" . number_format($value['U_DiscP4'], 2) . "%+" . number_format($value['U_DiscP5'], 2) . "%";
			} elseif ($value['U_DiscP4'] != NULL && $value['U_DiscP4'] != "" && $value['U_DiscP4'] != 0.00) {
				$Discount = number_format($value['U_DiscP1'], 2) . "%+" . number_format($value['U_DiscP2'], 2) . "%+" . number_format($value['U_DiscP3'], 2) . "%+" . number_format($value['U_DiscP4'], 2) . "%";
			} elseif ($value['U_DiscP3'] != NULL && $value['U_DiscP3'] != "" && $value['U_DiscP3'] != 0.00) {
				$Discount = number_format($value['U_DiscP1'], 2) . "%+" . number_format($value['U_DiscP2'], 2) . "%+" . number_format($value['U_DiscP3'], 2) . "%";
			} elseif ($value['U_DiscP2'] != NULL && $value['U_DiscP2'] != "" && $value['U_DiscP2'] != 0.00) {
				$Discount = number_format($value['U_DiscP1'], 2) . "%+" . number_format($value['U_DiscP2'], 2) . "%";
			} elseif ($value['U_DiscP1'] != NULL && $value['U_DiscP1'] != "" && $value['U_DiscP1'] != 0.00) {
				$Discount = number_format($value['U_DiscP1'], 2) . "%";
			} else {
				$Discount = "";
			}

            $inval['History'][$key]['DocDate']    = date("d/m/Y",strtotime($value['DocDate']));
            $inval['History'][$key]['Quantity']   = number_format($value['Quantity'],0);
            $inval['History'][$key]['PriceBefDi'] = number_format($value['PriceBefDi'],2);
            $inval['History'][$key]['Discount']   = $Discount;
            $inval['History'][$key]['Price']      = number_format($value['Price'],2);
            $inval['History'][$key]['VatSum']     = ($value['Quantity'] != 0) ? number_format($value['VatSum']/$value['Quantity'],2) : '0.00';
            $inval['History']['Row']++;
        }
    }

    /* Warehouse */
    $WHSE_OptTxt = "<option value='' selected disabled>กรุณาเลือก</option>";
    $SQL2 =
        "SELECT
            T0.[WhsCode], T1.[WhsName], 
                CASE WHEN T0.[OnHand]-T0.[Iscommited] >= 0 THEN T0.[OnHand]-T0.[Iscommited] ELSE 0 END AS OnHand, T1.[Location], T3.[Location] AS [LocationName], ISNULL(T2.[DfltWH], 'KSY') AS [DfltWH], T4.[UomCode] AS [SalUnitMsr], T0.[Locked] 
        FROM OITW T0
        LEFT JOIN OWHS T1 ON T0.[WhsCode] = T1.[WhsCode]
        LEFT JOIN OITM T2 ON T0.[ItemCode] = T2.[ItemCode]
        LEFT JOIN OLCT T3 ON T1.[Location] = T3.[Code]
        LEFT JOIN OUOM T4 ON T4.[UomEntry] = T2.[SUoMEntry]

        WHERE (T0.[ItemCode] = '$ItemCode') AND T1.[InActive] = 'N'
        ORDER BY T1.[Location], T0.[WhsCode]";
    $RST2 = DBConnect("SAP")->query(SQLtoHANA($SQL2))->fetchAll();
    if(!$RST2) {
        $inval['WHSE'] = $WHSE_OptTxt;
    } else {
        $DefWhse = "";
	    $LoGroup = "";

        foreach($RST2 as $value) {
            if ($LoGroup != $value['LocationName']) {
                if ($LoGroup != "") {
                    $WHSE_OptTxt .= "</optgroup>";
                }
                $WHSE_OptTxt .= "<optgroup label='".SapTH($value['LocationName'])."'>";
            }
            $DefWhse = SapTH($value['DfltWH']);
            if(SapTH($value['WhsCode']) == $DefWhse) {
                $OptionClass = " class='fw-bolder'";
                $OptionText = " (ค่าเริ่มต้น)";
            } else {
                $OptionClass = "";
                $OptionText  = "";
            }
            if($value['Locked'] == "Y" || $value['OnHand'] == 0) {
                $ItemPrefix = substr($ItemCode,0,3);
                if($ItemPrefix != "RV-") {
                    $OptionDis = "disabled style='color: #cccccc;'";
                } else {
                    $OptionDis = "";
                }
            } else {
                $ItemPrefix = substr($ItemCode,0,3);
                if($ItemPrefix == "RV-") {
                    $OptionDis = "disabled style='color: #cccccc;'";
                } else {
                    $OptionDis = "";
                }
            }
            $WHSE_OptTxt .= "<option".$OptionClass." value='".SapTH($value['WhsCode'])."' $OptionDis>".SapTH($value['WhsCode'])." - ".SapTH($value['WhsName'])." (คงเหลือ ".number_format($value['OnHand'],0)." ".SapTH($value['SalUnitMsr']).") ".$OptionText."</option>";
		    $LoGroup = $value['LocationName'];
        }
        $WHSE_OptTxt .= "</optgroup>";
        $inval['WHSE']['Opt'] = $WHSE_OptTxt;
        $inval['WHSE']['Def'] = $DefWhse;
        $inval['WHSE']['Row'] = 1;
    }

    /*  Default Price */
    $DP = GetPrice($_POST['CardCode'],$_POST['ItemCode'],$_POST['Quantity']);
	$inval['DefaultPrice'] = base64_encode(AddDecimal($DP,2));

    /* Cost */
    $SQL3 = "SELECT TOP 1 T0.[LastPurPrc] FROM OITM T0 WHERE T0.[ItemCode] = '$ItemCode'";
    $RST3 = DBConnect("SAP")->query(SQLtoHANA($SQL3))->fetchAll();
    if(!$RST3) {
        $inval['CXST'] = base64_encode(0);
    } else {
        foreach($RST3 as $value) {
            $inval['CXST'] = base64_encode($value['LastPurPrc']);
        }
    }

    $inval['DPCODE'] = $_SESSION['DEPTCODE'];
}

if($_GET['p'] == "SaveDoc") {
    $SaveType     = $_POST['SaveType'];
    $txt_DocEntry = $_POST['txt_DocEntry'];
    $ItemRow      = $_POST['ItemRow'];
    $UKEY         = $_SESSION['UKEY'];
    $SSID         = $_SESSION['SSID'];

    $APP0 = 0;
    $APP1 = 0;
    $APP2 = 0;
    $APP3 = 0;
    $APP4 = 0;

    $txt_CardCode    = $_POST['txt_CardCode'];
    $txt_CardName    = $_POST['txt_CardName'];
    $txt_LicTradeNum = base64_decode($_POST['txt_LicTradeNum']);
    $txt_DocType     = $_POST['txt_DocType'];
    $txt_TaxType     = $_POST['txt_TaxType'];
    $txt_Billto      = $_POST['txt_Billto'];
    $txt_BillAddr    = str_replace(" (ค่าเริ่มต้น)", "", $_POST['txt_BillAddr']);
    $txt_Shipto      = $_POST['txt_Shipto'];
    $txt_ShipAddr    = str_replace(" (ค่าเริ่มต้น)", "", $_POST['txt_ShipAddr']);
    $txt_SlpCode     = $_POST['txt_SlpCode'];
    $txt_GroupNum    = $_POST['txt_GroupNum'];
    $txt_DocDate     = date("Y-m-d",strtotime($_POST['txt_DocDate']));
    $txt_DocDueDate  = date("Y-m-d",strtotime($_POST['txt_DocDueDate']));
    $txt_U_PONo      = (isset($_POST['txt_U_PONo']) != "") ? $_POST['txt_U_PONo'] : "" ;
    // $txt_U_SO_TYPE   = (isset($_POST['txt_U_SO_TYPE']) != "") ? $_POST['txt_U_SO_TYPE'] : "99" ;
    $txt_comments    = (isset($_POST['txt_comments']) != "") ? $_POST['txt_comments'] : "" ;

    $All_Total       = base64_decode($_POST['All_Total']);
    $All_Discount    = base64_decode($_POST['All_Discount']);
    $All_DiscUnit    = base64_decode($_POST['All_DiscUnit']);
    $Doc_Discount    = base64_decode($_POST['Doc_Discount']);
    $Doc_VatSum      = base64_decode($_POST['Doc_VatSum']);
    $Doc_Total       = base64_decode($_POST['Doc_Total']);
    $Doc_Profit      = base64_decode($_POST['Doc_Profit']);

    if($All_DiscUnit == "%") {
        $DiscPcnt     = $All_Discount;
    } else {
        $DiscPcnt     = 0;
    }

    if ($SiteID == 0){
        $Doc_Discount = $All_Total - $Doc_Discount;
    }else{
        $Doc_Discount = ($Doc_Total-$Doc_VatSum)-$Doc_Discount;
    }   

    $IntStatus = "1" ;

    if($txt_DocEntry == "-1") {
        /* Create New Document Number */
        switch($txt_DocType) {
            case "SD": $Suffix = 1; $YDocNum = substr(date("Y")+543,-2); break;
            case "SO": $Suffix = 2; $YDocNum = substr(date("Y")+543,-2); break;
        }
        $MDocNum = date("m");
        $DocPrefix = $YDocNum.$MDocNum;
        $SQL0 = "SELECT T0.DocNum FROM order_header T0 WHERE T0.DocType = '$txt_DocType' AND T0.DocNum LIKE '".$DocPrefix.$Suffix."%' AND T0.site_id = $SiteID ORDER BY T0.DocEntry DESC LIMIT 1";
        $RST0 = DBConnect("APP")->query($SQL0)->fetchAll();
        if(!$RST0) {
            $NewDocNum = $DocPrefix.$Suffix."0001";
        } else {
            $LastDocNum = intval(substr($RST0[0]['DocNum'],-4));
            $NextDocNum = $LastDocNum+1;
            if($NextDocNum <= 9) {
                $NewSuffix = "000".$NextDocNum;
            } elseif($NextDocNum >= 10 && $NextDocNum <= 99) {
                $NewSuffix = "00".$NextDocNum;
            } elseif($NextDocNum >= 100 && $NextDocNum <= 999) {
                $NewSuffix = "0".$NextDocNum;
            } else {
                $NewSuffix = $NextDocNum;
            }
            $NewDocNum = $DocPrefix.$Suffix.$NewSuffix;
        }

        /* INSERT NEW HEADER */
        $CON_HD = DBConnect("APP");
        $SQL_HD = 
            "INSERT INTO order_header SET
                site_id       = :SiteID,
                DocNum        = :DocNum,
                DocType       = :DocType,
                TaxType       = :TaxType,
                DocDate       = :DocDate,
                DocDueDate    = :DocDueDate,
                CardCode      = :CardCode,
                CardName      = :CardName,
                LicTradeNum   = :LicTradeNum,
                SlpCode       = :SlpCode,
                GroupNum      = :GroupNum,
                BilltoCode    = :BilltoCode,
                BilltoAddress = NULLIF(:BilltoAddress,''),
                ShiptoCode    = :ShiptoCode,
                ShiptoAddress = NULLIF(:ShiptoAddress,''),
                DiscPcnt      = NULLIF(:DiscPcnt,''),
                DiscTotal     = NULLIF(:DiscTotal, ''),
                DocTotal      = :DocTotal,
                VatSum        = :VatSum,
                GrossProfit   = :GrossProfit,
                U_PONo        = NULLIF(:U_PONo, ''),
                Comments      = :Comments,
                uKeyCreate    = :UKEY,
                DateCreate    = NOW(),
                ssidCreate    = :SSID,
                IntStatus     = :IntStatus";
                // U_SO_Type     = :U_SO_TYPE,
        $QRY_HD = $CON_HD->prepare($SQL_HD);
        $QRY_HD->bindparam(":SiteID",        $SiteID);
        $QRY_HD->bindparam(":DocNum",        $NewDocNum);
        $QRY_HD->bindparam(":DocType",       $txt_DocType);
        $QRY_HD->bindparam(":TaxType",       $txt_TaxType);
        $QRY_HD->bindparam(":DocDate",       $txt_DocDate);
        $QRY_HD->bindparam(":DocDueDate",    $txt_DocDueDate);
        $QRY_HD->bindparam(":CardCode",      $txt_CardCode);
        $QRY_HD->bindparam(":CardName",      $txt_CardName);
        $QRY_HD->bindparam(":LicTradeNum",   $txt_LicTradeNum);
        $QRY_HD->bindparam(":SlpCode",       $txt_SlpCode);
        $QRY_HD->bindparam(":GroupNum",      $txt_GroupNum);
        $QRY_HD->bindparam(":BilltoCode",    $txt_Billto);
        $QRY_HD->bindparam(":BilltoAddress", $txt_BillAddr);
        $QRY_HD->bindparam(":ShiptoCode",    $txt_Shipto);
        $QRY_HD->bindparam(":ShiptoAddress", $txt_ShipAddr);
        $QRY_HD->bindparam(":DiscPcnt",      $DiscPcnt);
        $QRY_HD->bindparam(":DiscTotal",     $Doc_Discount);
        $QRY_HD->bindparam(":DocTotal",      $Doc_Total);
        $QRY_HD->bindparam(":VatSum",        $Doc_VatSum);
        $QRY_HD->bindparam(":GrossProfit",   $Doc_Profit);
        $QRY_HD->bindparam(":U_PONo",        $txt_U_PONo);
        // $QRY_HD->bindparam(":U_SO_TYPE",     $txt_U_SO_TYPE);
        $QRY_HD->bindparam(":Comments",      $txt_comments);
        $QRY_HD->bindparam(":UKEY",          $UKEY);
        $QRY_HD->bindparam(":SSID",          $SSID);
        $QRY_HD->bindparam(":IntStatus",     $IntStatus); 
        if($QRY_HD->execute() === FALSE) {
            $inval['Status'] = "ERR";
        } else {
            $DocEntry = $CON_HD->lastInsertId();
            /* INSERT NEW ITEM LIST */
            $CON_DT = DBConnect("APP");
            for($v = 0; $v < $ItemRow; $v++) {
			    $ItemArr = explode("::", $_POST['ItemList'][$v]);

                $VisOrder   = $ItemArr[0];
                $ItemCode   = $ItemArr[1];
                $CodeBars   = $ItemArr[2];
                $ItemName   = $ItemArr[3];
                $WhsCode    = $ItemArr[4];
                $Quantity   = $ItemArr[5];
                $UnitMsr    = $ItemArr[6];
                $GrandPrice = base64_decode($ItemArr[8]);
                $UnitPrice  = base64_decode($ItemArr[10]);
                $LineTotal  = base64_decode($ItemArr[11]);
                $Line_SP     = $ItemArr[12];

                $Cost       = base64_decode($ItemArr[7]);
                $UnitVat    = ($txt_TaxType == "S07") ? ($UnitPrice * 7) / 100 : 0 ;
                $LineVatSum = ($txt_TaxType == "S07") ? ($UnitVat * $Quantity) : 0 ;
                $LineProfit = $LineTotal - ($Cost * $Quantity);

                $Line_Disc0 = "";
                $Line_Disc1 = "";
                $Line_Disc2 = "";
                $Line_Disc3 = "";
                $Line_Disc4 = "";
                $Line_Disc5 = "";

                if($ItemArr[9] != "") {
                    $ChkDisc = substr($ItemArr[9],0,1);
                    if($ChkDisc != "*") {
                        $Discount = explode("-", $ItemArr[9]);
						$Line = 1;
                        for($d = 0; $d < sizeof($Discount); $d++) {
							if($Discount[$d] != 0 || $Discount[$d] != "") {
								${"Line_Disc".$Line} = $Discount[$d];
								$Line++;
							}
						}
                    } else {
                        $Line_Disc0 = substr($ItemArr[9],1);
                    }
                }
                $SQL_DT =
                    "INSERT INTO order_detail SET
                        DocEntry   = :DocEntry,
                        VisOrder   = :VisOrder,
                        ItemCode   = :ItemCode,
                        CodeBars   = NULLIF(:CodeBars,''),
                        ItemName   = :ItemName,
                        WhsCode    = :WhsCode,
                        Quantity   = :Quantity,
                        UnitMsr    = NULLIF(:UnitMsr,''),
                        GrandPrice = :GrandPrice,
                        Line_Disc0 = NULLIF(:Line_Disc0,''),
                        Line_Disc1 = NULLIF(:Line_Disc1,''),
                        Line_Disc2 = NULLIF(:Line_Disc2,''),
                        Line_Disc3 = NULLIF(:Line_Disc3,''),
                        Line_Disc4 = NULLIF(:Line_Disc4,''),
                        Line_Disc5 = NULLIF(:Line_Disc5,''),
                        UnitPrice  = :UnitPrice,
                        UnitVat    = :UnitVat,
                        LineTotal  = :LineTotal,
                        LineVatSum = :LineVatSum,
                        LineProfit = :LineProfit,
                        Line_SP    = :Line_SP,
                        uKeyCreate = :UKEY,
                        ssidCreate = :SSID,
                        DateCreate = NOW()";
                $QRY_DT = $CON_DT->prepare($SQL_DT);
                $QRY_DT->bindparam(":DocEntry",   $DocEntry);
                $QRY_DT->bindparam(":VisOrder",   $VisOrder);
                $QRY_DT->bindparam(":ItemCode",   $ItemCode);
                $QRY_DT->bindparam(":CodeBars",   $CodeBars);
                $QRY_DT->bindparam(":ItemName",   $ItemName);
                $QRY_DT->bindparam(":WhsCode",    $WhsCode);
                $QRY_DT->bindparam(":Quantity",   $Quantity);
                $QRY_DT->bindparam(":UnitMsr",    $UnitMsr);
                $QRY_DT->bindparam(":GrandPrice", $GrandPrice);
                $QRY_DT->bindparam(":Line_Disc0", $Line_Disc0);
                $QRY_DT->bindparam(":Line_Disc1", $Line_Disc1);
                $QRY_DT->bindparam(":Line_Disc2", $Line_Disc2);
                $QRY_DT->bindparam(":Line_Disc3", $Line_Disc3);
                $QRY_DT->bindparam(":Line_Disc4", $Line_Disc4);
                $QRY_DT->bindparam(":Line_Disc5", $Line_Disc5);
                $QRY_DT->bindparam(":UnitPrice",  $UnitPrice);
                $QRY_DT->bindparam(":UnitVat",    $UnitVat);
                $QRY_DT->bindparam(":LineTotal",  $LineTotal);
                $QRY_DT->bindparam(":LineVatSum", $LineVatSum);
                $QRY_DT->bindparam(":LineProfit", $LineProfit);
                $QRY_DT->bindparam(":Line_SP",    $Line_SP);
                $QRY_DT->bindparam(":UKEY",       $UKEY);
                $QRY_DT->bindparam(":SSID",       $SSID);
                if($QRY_DT->execute() === FALSE) {
                    $inval['Status'] = "ERR";
                } else {
                    $inval['Status'] = "OK";
                }
            }
        }

        /* INSERT Attachment */
        if(isset($_FILES['DocAttach']['name'])) {
            $Totals = count($_FILES['DocAttach']['name']);
            for($i = 0; $i < $Totals; $i++) {
                $FileProcess  = explode(".",basename($_FILES['DocAttach']['name'][$i]));
                $countProcess = count($FileProcess);
                if($countProcess == 2) {
                    $FileOriName = $FileProcess[0];
                    $FileExt     = $FileProcess[1];
                } else {
                    $FileOriName = "";
                    $FileExt     = $FileProcess[$countProcess-1];
                    for($n = 0; $n <= $countProcess-2; $n++) {
                        $FileOriName .= $FileProcess[$n].".";
                    }
                    $FileOriName = substr($FileOriName,0,-1);
                }
                $tmpFilePath = $_FILES['DocAttach']['tmp_name'][$i];
                if($tmpFilePath != "") {
                    $NewFileName = $txt_DocType."-".$NewDocNum."-".$i.".".$FileExt;
                    $NewFilePath = "../../FileAttach/SO/".$NewFileName;
                    move_uploaded_file($tmpFilePath, $NewFilePath);

                    $CON_AT = DBConnect("APP");
                    $SQL_AT =
                        "INSERT INTO order_attach SET
                            DocEntry    = :DocEntry,
                            VisOrder    = :VisOrder,
                            FileOriName = :FileOriName,
                            FileDirName = :FileDirName,
                            FileExt     = :FileExt,
                            uKeyCreate  = :UKEY";
                    $QRY_AT = $CON_DT->prepare($SQL_AT);
                    $FileDirName = $txt_DocType."-".$NewDocNum."-".$i;
                    $QRY_AT->bindparam(":DocEntry",    $DocEntry);
                    $QRY_AT->bindparam(":VisOrder",    $i);
                    $QRY_AT->bindparam(":FileOriName", $FileOriName);
                    $QRY_AT->bindparam(":FileDirName", $FileDirName);
                    $QRY_AT->bindparam(":FileExt",     $FileExt);
                    $QRY_AT->bindparam(":UKEY",        $UKEY);
                    if($QRY_AT->execute() === FALSE) {
                        $inval['Status'] = "ERR";
                    } else {
                        $inval['Status'] = "OK";
                    }
                }
            }
        }
    } else {
        /* UPDATE */
        $SQL0 = "SELECT CONCAT(T0.DocType,'-',T0.DocNum) AS 'DocNum' FROM order_header T0 WHERE T0.DocEntry = $txt_DocEntry LIMIT 1";
        $RST0 = DBConnect("APP")->query($SQL0)->fetchAll()[0];
        $DocNum = $RST0['DocNum'];

        /* UPDATE EXISTING HEADER */
        $SQL_HD = 
            "UPDATE order_header SET
                DocDate       = :DocDate,
                DocDueDate    = :DocDueDate,
                CardCode      = :CardCode,
                CardName      = :CardName,
                LicTradeNum   = :LicTradeNum,
                SlpCode       = :SlpCode,
                GroupNum      = :GroupNum,
                BilltoCode    = :BilltoCode,
                BilltoAddress = NULLIF(:BilltoAddress,''),
                ShiptoCode    = :ShiptoCode,
                ShiptoAddress = NULLIF(:ShiptoAddress,''),
                DiscPcnt      = NULLIF(:DiscPcnt,''),
                DiscTotal     = NULLIF(:DiscTotal, ''),
                DocTotal      = :DocTotal,
                VatSum        = :VatSum,
                GrossProfit   = :GrossProfit,
                U_PONo        = NULLIF(:U_PONo, ''),
                Comments      = :Comments,
                uKeyUpdate    = :UKEY,
                DateUpdate    = NOW(),
                ssidUpdate    = :SSID,
                IntStatus     = :IntStatus
            WHERE DocEntry = :DocEntry";
            // U_SO_Type     = :U_SO_TYPE,
        $QRY_HD = DBConnect("APP")->prepare($SQL_HD);
        $QRY_HD->bindparam(":DocDate",       $txt_DocDate);
        $QRY_HD->bindparam(":DocDueDate",    $txt_DocDueDate);
        $QRY_HD->bindparam(":CardCode",      $txt_CardCode);
        $QRY_HD->bindparam(":CardName",      $txt_CardName);
        $QRY_HD->bindparam(":LicTradeNum",   $txt_LicTradeNum);
        $QRY_HD->bindparam(":SlpCode",       $txt_SlpCode);
        $QRY_HD->bindparam(":GroupNum",      $txt_GroupNum);
        $QRY_HD->bindparam(":BilltoCode",    $txt_Billto);
        $QRY_HD->bindparam(":BilltoAddress", $txt_BillAddr);
        $QRY_HD->bindparam(":ShiptoCode",    $txt_Shipto);
        $QRY_HD->bindparam(":ShiptoAddress", $txt_ShipAddr);
        $QRY_HD->bindparam(":DiscPcnt",      $DiscPcnt);
        $QRY_HD->bindparam(":DiscTotal",     $Doc_Discount);
        $QRY_HD->bindparam(":DocTotal",      $Doc_Total);
        $QRY_HD->bindparam(":VatSum",        $Doc_VatSum);
        $QRY_HD->bindparam(":GrossProfit",   $Doc_Profit);
        $QRY_HD->bindparam(":U_PONo",        $txt_U_PONo);
        // $QRY_HD->bindparam(":U_SO_TYPE",     $txt_U_SO_TYPE);
        $QRY_HD->bindparam(":Comments",      $txt_comments);
        $QRY_HD->bindparam(":UKEY",          $UKEY);
        $QRY_HD->bindparam(":SSID",          $SSID);
        $QRY_HD->bindparam(":IntStatus",     $IntStatus);
        $QRY_HD->bindparam(":DocEntry",      $txt_DocEntry);
        if($QRY_HD->execute() === FALSE) {
            $inval['Status'] = "ERR";
        } else {
            /* INACTIVE OLD DETAIL */
            $SQL_DT = "UPDATE order_detail SET LineStatus = 'I', uKeyUpdate = :UKEY, DateUpdate = NOW(), ssidUpdate = :SSID WHERE DocEntry = :DocEntry";
            $QRY_DT = DBConnect("APP")->prepare($SQL_DT);
            $QRY_DT->bindparam(":UKEY",     $UKEY);
            $QRY_DT->bindparam(":SSID",     $SSID);
            $QRY_DT->bindparam(":DocEntry", $txt_DocEntry);
            $QRY_DT->execute();

            /* INSERT NEW ITEM LIST */
            $CON_DT = DBConnect("APP");
            for($v = 0; $v < $ItemRow; $v++) {
			    $ItemArr = explode("::", $_POST['ItemList'][$v]);

                $VisOrder   = $ItemArr[0];
                $ItemCode   = $ItemArr[1];
                $CodeBars   = $ItemArr[2];
                $ItemName   = $ItemArr[3];
                $WhsCode    = $ItemArr[4];
                $Quantity   = $ItemArr[5];
                $UnitMsr    = $ItemArr[6];
                $GrandPrice = base64_decode($ItemArr[8]);
                $UnitPrice  = base64_decode($ItemArr[10]);
                $LineTotal  = base64_decode($ItemArr[11]);
                $Line_SP     = $ItemArr[12];

                $Cost       = base64_decode($ItemArr[7]);
                $UnitVat    = ($UnitPrice * 7) / 100;
                $LineVatSum = $UnitVat * 7;
                $LineProfit = $LineTotal - ($Cost * $Quantity);

                $Line_Disc0 = "";
                $Line_Disc1 = "";
                $Line_Disc2 = "";
                $Line_Disc3 = "";
                $Line_Disc4 = "";

                if($ItemArr[9] != "") {
                    $ChkDisc = substr($ItemArr[9],0,1);
                    if($ChkDisc != "*") {
                        $Discount = explode("-", $ItemArr[9]);
						$Line = 1;
                        for($d = 0; $d < sizeof($Discount); $d++) {
							if($Discount[$d] != 0 || $Discount[$d] != "") {
								${"Line_Disc".$Line} = $Discount[$d];
								$Line++;
							}
						}
                    } else {
                        $Line_Disc0 = substr($ItemArr[9],1);
                    }
                }
                $SQL_DT =
                    "INSERT INTO order_detail SET
                        DocEntry   = :DocEntry,
                        VisOrder   = :VisOrder,
                        ItemCode   = :ItemCode,
                        CodeBars   = NULLIF(:CodeBars,''),
                        ItemName   = :ItemName,
                        WhsCode    = :WhsCode,
                        Quantity   = :Quantity,
                        UnitMsr    = NULLIF(:UnitMsr,''),
                        GrandPrice = :GrandPrice,
                        Line_Disc0 = NULLIF(:Line_Disc0,''),
                        Line_Disc1 = NULLIF(:Line_Disc1,''),
                        Line_Disc2 = NULLIF(:Line_Disc2,''),
                        Line_Disc3 = NULLIF(:Line_Disc3,''),
                        Line_Disc4 = NULLIF(:Line_Disc4,''),
                        UnitPrice  = :UnitPrice,
                        UnitVat    = :UnitVat,
                        LineTotal  = :LineTotal,
                        LineVatSum = :LineVatSum,
                        LineProfit = :LineProfit,
                        Line_SP    = :Line_SP,
                        uKeyCreate = :UKEY,
                        ssidCreate = :SSID,
                        DateCreate = NOW()";
                $QRY_DT = $CON_DT->prepare($SQL_DT);
                $QRY_DT->bindparam(":DocEntry",   $txt_DocEntry);
                $QRY_DT->bindparam(":VisOrder",   $VisOrder);
                $QRY_DT->bindparam(":ItemCode",   $ItemCode);
                $QRY_DT->bindparam(":CodeBars",   $CodeBars);
                $QRY_DT->bindparam(":ItemName",   $ItemName);
                $QRY_DT->bindparam(":WhsCode",    $WhsCode);
                $QRY_DT->bindparam(":Quantity",   $Quantity);
                $QRY_DT->bindparam(":UnitMsr",    $UnitMsr);
                $QRY_DT->bindparam(":GrandPrice", $GrandPrice);
                $QRY_DT->bindparam(":Line_Disc0", $Line_Disc0);
                $QRY_DT->bindparam(":Line_Disc1", $Line_Disc1);
                $QRY_DT->bindparam(":Line_Disc2", $Line_Disc2);
                $QRY_DT->bindparam(":Line_Disc3", $Line_Disc3);
                $QRY_DT->bindparam(":Line_Disc4", $Line_Disc4);
                $QRY_DT->bindparam(":UnitPrice",  $UnitPrice);
                $QRY_DT->bindparam(":UnitVat",    $UnitVat);
                $QRY_DT->bindparam(":LineTotal",  $LineTotal);
                $QRY_DT->bindparam(":LineVatSum", $LineVatSum);
                $QRY_DT->bindparam(":LineProfit", $LineProfit);
                $QRY_DT->bindparam(":Line_SP",    $Line_SP);
                $QRY_DT->bindparam(":UKEY",       $UKEY);
                $QRY_DT->bindparam(":SSID",       $SSID);
                if($QRY_DT->execute() === FALSE) {
                    $inval['Status'] = "ERR";
                } else {
                    $inval['Status'] = "OK";
                }
            }
        }

        /* INSERT Attachment */
        if(isset($_FILES['DocAttach']['name'])) {
            $Totals = count($_FILES['DocAttach']['name']);
            $SQL_VIS = "SELECT MAX(T0.VisOrder) AS 'VisOrder' FROM order_attach T0 WHERE T0.DocEntry = '$txt_DocEntry'";
            $RST_VIS = DBConnect("APP")->query($SQL_VIS)->fetchAll()[0];
            if(!$RST_VIS) {
                $VisOrder = 0;
            } else {
                $VisOrder = $RST_VIS['VisOrder']+1;
            }
            for($i = 0; $i < $Totals; $i++) {
                $FileProcess  = explode(".",basename($_FILES['DocAttach']['name'][$i]));
                $countProcess = count($FileProcess);
                if($countProcess == 2) {
                    $FileOriName = $FileProcess[0];
                    $FileExt     = $FileProcess[1];
                } else {
                    $FileOriName = "";
                    $FileExt     = $FileProcess[$countProcess-1];
                    for($n = 0; $n <= $countProcess-2; $n++) {
                        $FileOriName .= $FileProcess[$n].".";
                    }
                    $FileOriName = substr($FileOriName,0,-1);
                }
                $tmpFilePath = $_FILES['DocAttach']['tmp_name'][$i];
                if($tmpFilePath != "") {
                    $NewFileName = $DocNum."-".$VisOrder.".".$FileExt;
                    $NewFilePath = "../../FileAttach/SO/".$NewFileName;
                    move_uploaded_file($tmpFilePath, $NewFilePath);

                    $CON_AT = DBConnect("APP");
                    $SQL_AT =
                        "INSERT INTO order_attach SET
                            DocEntry    = :DocEntry,
                            VisOrder    = :VisOrder,
                            FileOriName = :FileOriName,
                            FileDirName = :FileDirName,
                            FileExt     = :FileExt,
                            uKeyCreate  = :UKEY";
                    $QRY_AT = $CON_DT->prepare($SQL_AT);
                    $FileDirName = $DocNum."-".$VisOrder;
                    $QRY_AT->bindparam(":DocEntry",    $txt_DocEntry);
                    $QRY_AT->bindparam(":VisOrder",    $VisOrder);
                    $QRY_AT->bindparam(":FileOriName", $FileOriName);
                    $QRY_AT->bindparam(":FileDirName", $FileDirName);
                    $QRY_AT->bindparam(":FileExt",     $FileExt);
                    $QRY_AT->bindparam(":UKEY",        $UKEY);
                    if($QRY_AT->execute() === FALSE) {
                        $inval['Status'] = "ERR";
                    } else {
                        $inval['Status'] = "OK";
                    }
                }
                $VisOrder++;
            }
        }
    }

    /* Add Order */
    if($_POST['SaveType'] == "1" || $_POST['SaveType'] == 1) {
        $DocID = ($txt_DocEntry == "-1") ? $DocEntry : $txt_DocEntry;
        /* 
            App 0 = เครดิตวงเงิน
            App 1 = หนี้เกินกำหนด
            App 2 = เช็คคืนยังไม่เคลียร์
            App 3 = ราคาพิเศษ
            App 4 = ขายต่ำกว่าราคาที่กำหนด
        */
        /*=========== APP 0 ===========*/
        $APP0 = "N";
        $SQL1 = "SELECT TOP 1 T0.[CreditLine], T0.[Balance] FROM OCRD T0 WHERE T0.[CardCode] = '$txt_CardCode'";
        $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll()[0];
        $NewBalance = $RST1['Balance'] + $Doc_Total;
        $APP0 = ($NewBalance > $RST1['CreditLine']) ? "Y" : "N" ;

        /*=========== APP 1 ===========*/
        $APP1 = "N";
        $SQL1 = 
            "SELECT
                T0.[DocEntry], 'OINV' AS [DocType], (ISNULL(T2.[BeginStr],'IV-')+CAST(T0.[DocNum] AS VARCHAR)) AS [DocNum], T0.[DocDate], T0.[DocDueDate], T0.[DocTotal], T0.[PaidToDate], T0.[CardCode], T0.[CardName]
            FROM OINV T0 
            LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
            LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
            WHERE (T0.[DocStatus] = 'O' AND T0.[CANCELED] = 'N' AND T0.[DocDueDate] <= GETDATE()) AND (T0.[CardCode] = '$txt_CardCode' OR T1.[FatherCard] = '$txt_CardCode')
            UNION ALL
            SELECT
                T0.[DocEntry], 'ORIN' AS [DocType], (ISNULL(T2.[BeginStr],'CN-')+CAST(T0.[DocNum] AS VARCHAR)) AS [DocNum], T0.[DocDate], T0.[DocDueDate], -T0.[DocTotal], -T0.[PaidToDate], T0.[CardCode], T0.[CardName]
            FROM ORIN T0 
            LEFT JOIN OCRD T1 ON T0.[CardCode] = T1.[CardCode]
            LEFT JOIN NNM1 T2 ON T0.[Series] = T2.[Series]
            WHERE (T0.[DocStatus] = 'O' AND T0.[CANCELED] = 'N' AND T0.[DocDueDate] <= GETDATE()) AND (T0.[CardCode] = '$txt_CardCode' OR T1.[FatherCard] = '$txt_CardCode')
            ORDER BY [DocType], T0.[DocDueDate]";
        $RST1 = DBConnect("SAP")->query(SQLtoHANA($SQL1))->fetchAll();
        if($RST1) {
            $APP1 = "Y";
            $SQL_INREF = "UPDATE order_appdue SET RefStatus = 'I', uKeyUpdate = :UKEY, ssidUpdate = :SSID WHERE DocEntry = :DocEntry AND RefStatus = 'A'";
            $QRY_INREF = DBConnect("APP")->prepare($SQL_INREF);
            $QRY_INREF->bindparam(":UKEY",     $UKEY);
            $QRY_INREF->bindparam(":SSID",     $SSID);
            $QRY_INREF->bindparam(":DocEntry", $DocID);
            $QRY_INREF->execute();

            foreach($RST1 as $key => $data) {
                $RST1_CardName = SapTH($data['CardName']);

                $REFSQL =
                    "INSERT INTO order_appdue SET
                        DocEntry   = :DocEntry,
                        BillType   = :BillType,
                        BillEntry  = :BillEntry,
                        DocNum     = :DocNum,
                        DocDate    = :DocDate,
                        DocDueDate = :DocDueDate,
                        DocTotal   = :DocTotal,
                        PaidtoDate = :PaidToDate,
                        CardCode   = :CardCode,
                        CardName   = :CardName,
                        uKeyCreate = :UKEY,
                        ssidCreate = :SSID";
                $REFQRY = DBConnect("APP")->prepare($REFSQL);
                $REFQRY->bindparam(":DocEntry",     $DocID);
                $REFQRY->bindparam(":BillType",     $data['DocType']);
                $REFQRY->bindparam(":BillEntry",    $data['DocEntry']);
                $REFQRY->bindparam(":DocNum",       $data['DocNum']);
                $REFQRY->bindparam(":DocDate",      $data['DocDate']);
                $REFQRY->bindparam(":DocDueDate",   $data['DocDueDate']);
                $REFQRY->bindparam(":DocTotal",     $data['DocTotal']);
                $REFQRY->bindparam(":PaidToDate",   $data['PaidToDate']);
                $REFQRY->bindparam(":CardCode",     $data['CardCode']);
                $REFQRY->bindparam(":CardName",     $RST1_CardName);
                $REFQRY->bindparam(":UKEY",   $UKEY);
                $REFQRY->bindparam(":SSID",   $SSID);
                $REFQRY->execute();
            }
        }
        /*=========== APP 2 ===========*/
        $APP2 = "N";

        /*=========== APP 3 ===========*/
        $APP3 = "N";
        for($v = 0; $v < $ItemRow; $v++) {
            if($APP3 == "N") {
                $ItemArr = explode("::", $_POST['ItemList'][$v]);
                $Line_SP = $ItemArr[12];
                $APP3 = ($Line_SP == "Y") ? "Y" : "N";
            }
        }
        /*=========== APP 4 ===========*/
        $SQL4 = "SELECT T0.ConfigValue FROM config_general T0 WHERE T0.ConfigID = 1 LIMIT 1";
        $RST4 = DBConnect("APP")->query($SQL4)->fetchAll();
        $SetPrice = $RST4[0]['ConfigValue'];
        $APP4 = (intval($Doc_Total) <= intval($SetPrice)) ? "Y" : "N";
        /*========ผู้อนุมัตื 3 4 =*/
        switch($_SESSION['DEPTCODE']) {
            case "DP002":
                switch($_SESSION['LVCODE']) {
                    case "M0001":
                    case "O0007": 
                        $APP0 = "N"; $APP1 = "N"; $APP2 = "N"; $APP3 = "N"; $APP4 = "N";
                    break;
                    default :
                        $LvApp = 'M0001';
                    break;
                }
            break;
            case "DP003":
                switch($_SESSION['LVCODE']) {
                    case 'M0002' :
                    case 'O0004' :
                        $APP0 = "N"; $APP1 = "N"; $APP2 = "N"; $APP3 = "N"; $APP4 = "N";
                        break;
                    case 'O0002':
                        $LvApp = 'S0002';
                        break;
                    case 'O0008':
                        $LvApp = 'S0005';
                        break;

                    default : 
                        $LvApp = 'M0002';
                        break;
                }
                break;
            case "DP005":
                $APP0 = "N"; $APP1 = "N"; $APP2 = "N"; $APP3 = "N"; $APP4 = "N";
                break;
            default :
                 $SQL7 = "SELECT T0.LvCode FROM positions T0 WHERE T0.DeptCode = '".$_SESSION['DEPTCODE']."' AND T0.LvCode LIKE 'M%' LIMIT 1";
                 $RST7 = DBConnect("APP")->query($SQL7)->fetchAll()[0];
                 $LvApp = $RST7['LvCode'];
            break;

        }
        //$APP0 = "N";$APP1= "N";$APP2= "N";$APP3="N";$APP4 = "N";
        $Step2App = 1;
        if (($APP0 == "Y" || $APP1 == "Y" || $APP2 == "Y") AND $_SESSION['DEPTCODE'] == 'DP003'){
            $Step2App = 2;
            $AppMoney = 'S0006';
        }else{
            $Step2App = 1;
            $AppMoney = "";
        }
        

        if($APP0 == "Y" || $APP1 == "Y" || $APP2 == "Y" || $APP3 == "Y" || $APP4 == "Y") {
            $IntStatus = "2";

            $SQL5 = "UPDATE order_header SET IntStatus = :IntStatus, uKeyUpdate = :UKEY, ssidUpdate = :SSID, DateUpdate = NOW() WHERE DocEntry = :DocEntry";
            $QRY5 = DBConnect("APP")->prepare($SQL5);
            $QRY5->bindparam(":UKEY",      $UKEY);
            $QRY5->bindparam(":SSID",      $SSID);
            $QRY5->bindparam(":IntStatus", $IntStatus);
            $QRY5->bindparam(":DocEntry",  $DocID);
            $QRY5->execute();
            // Waiwai
            $SQL6 = 
                "INSERT INTO order_approve SET
                    DocEntry = :DocEntry,
                    StepApprove = 0,
                    APP0 = :APP0,
                    APP1 = :APP1,
                    APP2 = :APP2,
                    APP3 = :APP3,
                    APP4 = :APP4,
                    LvClassReq = '".$LvApp."',
                    uKeyCreate = :UKEY";
            $QRY6 = DBConnect("APP")->prepare($SQL6);
            $QRY6->bindparam(":DocEntry", $DocID);
            $QRY6->bindparam(":APP0", $APP0);
            $QRY6->bindparam(":APP1", $APP1);
            $QRY6->bindparam(":APP2", $APP2);
            $QRY6->bindparam(":APP3", $APP3);
            $QRY6->bindparam(":APP4", $APP4);
            $QRY6->bindparam(":UKEY", $UKEY);
            $QRY6->execute();
            if ($Step2App == 2){
                $SQL7 = 
                    "INSERT INTO order_approve SET
                        DocEntry = :DocEntry,
                        StepApprove = 1,
                        APP0 = :APP0,
                        APP1 = :APP1,
                        APP2 = :APP2,
                        APP3 = :APP3,
                        APP4 = :APP4,
                        LvClassReq = '".$AppMoney."',
                        uKeyCreate = :UKEY";
                $QRY7 = DBConnect("APP")->prepare($SQL7);
                $QRY7->bindparam(":DocEntry", $DocID);
                $QRY7->bindparam(":APP0", $APP0);
                $QRY7->bindparam(":APP1", $APP1);
                $QRY7->bindparam(":APP2", $APP2);
                $QRY7->bindparam(":APP3", $APP3);
                $QRY7->bindparam(":APP4", $APP4);
                $QRY7->bindparam(":UKEY", $UKEY);
                $QRY7->execute();
            }

            $inval['Status']  = "OK";
            $inval['Message'] = "บันทึกข้อมูลสำเร็จ";
        } else {
            $IntStatus = "3";
            $SQL5 = "UPDATE order_header SET IntStatus = :IntStatus, uKeyUpdate = :UKEY, ssidUpdate = :SSID, DateUpdate = NOW() WHERE DocEntry = :DocEntry";
            $QRY5 = DBConnect("APP")->prepare($SQL5);
            $QRY5->bindparam(":UKEY",      $UKEY);
            $QRY5->bindparam(":SSID",      $SSID);
            $QRY5->bindparam(":IntStatus", $IntStatus);
            $QRY5->bindparam(":DocEntry",  $DocID);
            $QRY5->execute();

            $ImportSAP = ImportSAP($_SESSION['SITE']['site_id'], "ORDR", $DocID);

            $inval['Status']  = $ImportSAP['Status'];
            $inval['Message'] = $ImportSAP['Message'];
        }
    }
}

if($_GET['p'] == "OrderList") {
    $DocY = $_POST['DocY'];
    $DocM = $_POST['DocM'];
    $UKEY = $_SESSION['UKEY'];
    $DEPT = $_SESSION['DEPTCODE'];
    if($_SESSION['LVCLASS'] == 12) {
        $WHR1 = "AND T0.uKeyCreate = '$UKEY'";
    } else {
        switch($DEPT) {
            case "DP000":
            case "DP001":
            case "DP004": $WHR1 = ""; break;
            case "DP003":
                $WHR1 = "AND T4.DeptCode = '$DEPT'";                 /*
                switch($_SESSION['LVCODE']) {
                    case "S0002": $WHR1 = "AND T4.LvCode IN ('S0002','O0002')"; break;
                    case "S0005": $WHR1 = "AND T4.LvCode IN ('S0005','O0008')"; break;
                    default: $WHR1 = "AND T4.DeptCode = '$DEPT'"; break;
                }
                */

            break;
            default: $WHR1 = "AND T4.DeptCode = '$DEPT'"; break;
        }
    }

    $SQL1 = 
        "SELECT
            T0.DocEntry, T0.DocDate, T0.DocDueDate, CONCAT(T0.DocType,'-',T0.DocNum) AS ' DocNum', 
            T0.CardCode, T0.CardName, T0.U_PONo, (T0.DocTotal-T0.VatSum) AS 'DocTotal', T1.SlpName,
            CONCAT(T3.TH_uFirstName,' ',T3.TH_uLastName) AS 'NameCreate', T0.ImportEntry,
            T0.IntStatus, T2.IntStatusName, T2.IntStatusIcon, T0.ImportDocNum, T0.DateUpdate, T0.DateImport
        FROM order_header T0
        LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode AND T1.site_id = $SiteID
        LEFT JOIN order_docstate T2 ON T0.IntStatus = T2.IntStatus
        LEFT JOIN users T3 ON T0.uKeyCreate = T3.uKey
        LEFT JOIN positions T4 ON T3.LvCode = T4.LvCode
        WHERE (YEAR(T0.DocDate) = $DocY AND MONTH(T0.DocDate) = $DocM) AND T0.site_id = $SiteID $WHR1
        ORDER BY
            CASE
                WHEN T0.IntStatus = 1 THEN 1
                WHEN T0.IntStatus = 2 THEN 2
                WHEN T0.IntStatus = 3 THEN 3
                WHEN T0.IntStatus = 4 THEN 4
                WHEN T0.IntStatus = 5 THEN 5
            ELSE 6 END";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        foreach($RST1 as $key => $Row) {
            $dis_edit = "disabled";
            $dis_impt = "disabled";
            $dis_prnt = "disabled";
            $dis_cncl = "disabled";
            switch($Row['IntStatus']) {
                case "0":
                    $intClass = "bg-secondary";
                    $dis_prnt = "";
                    break;
                case "1":
                    $intClass = "bg-info";
                    $dis_edit = "";
                    $dis_cncl = "";
                    $dis_prnt = "";
                    break;
                case "2":
                    $intClass = "bg-warning";
                    $dis_cncl = "";
                    $dis_impt = "";
                    $dis_prnt = "";
                    break;
                case "3":
                    $intClass = "bg-success";
                    $dis_cncl = "";
                    $dis_impt = "";
                    $dis_prnt = "";
                    break;
                case "4":
                    $intClass = "bg-danger";
                    $dis_cncl = "";
                    $dis_prnt = "";
                    break;
                case "5":
                    $intClass = "bg-white text-success";
                    $dis_cncl = "";
                    $dis_prnt = "";
                    break;
            }
            $DateUpdate = ($Row['DateUpdate'] != '') ? "<br/><small style='font-size: 10px;'>".date("d/m/Y", strtotime($Row['DateUpdate']))." เวลา ".date("H:i:s", strtotime($Row['DateUpdate']))."</small>" : "";
            $DateImport = ($Row['DateImport'] != '') ? "<br/><small style='font-size: 10px;'>".date("d/m/Y", strtotime($Row['DateImport']))." เวลา ".date("H:i:s", strtotime($Row['DateImport']))."</small>" : "";

            $inval[$key]['No']         = $key+1;
            $inval[$key]['DocEntry']   = $Row['DocEntry'];
            $inval[$key]['DocDate']    = date("d/m/Y",strtotime($Row['DocDate']));
            $inval[$key]['DocDueDate'] = date("d/m/Y",strtotime($Row['DocDueDate']));
            $inval[$key]['DocNum']     = "<a href='javascript:void(0);' onclick='ViewDoc(".$Row['DocEntry'].");'>".$Row['DocNum']."</a>".$DateUpdate;
            $inval[$key]['CardCode']   = $Row['CardCode']." | ".$Row['CardName']."<br/><small>ผู้จัดทำ: ".$Row['NameCreate']."</small>";
            $inval[$key]['U_PONo']     = $Row['U_PONo'];
            $inval[$key]['DocTotal']   = number_format($Row['DocTotal'],2);
            $inval[$key]['SlpName']    = $Row['SlpName'];
            $inval[$key]['SAP']        = $Row['ImportDocNum'].$DateImport;
            $inval[$key]['IntStatus']  = $Row['IntStatus'];
            $inval[$key]['Status']     = "<span class='badge py-2 w-100 $intClass'>".$Row['IntStatusIcon']." ".$Row['IntStatusName']."</span>";
            $inval[$key]['BTN']        = 
                '<button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="inside"><i class="fas fa-cog fa-fw fa-1x"></i></button>
                <div class="dropdown" style="position: absolute !important;">
                    <ul class="dropdown-menu" style="font-size: 12px;">
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="ViewDoc('.$Row['DocEntry'].');"><i class="fas fa-info fa-fw fa-1x"></i> รายละเอียด</a></li>
                        <li><a class="dropdown-item '.$dis_edit.'" href="javascript:void(0);" onclick="EditDoc('.$Row['DocEntry'].');"><i class="fas fa-edit fa-fw fa-1x"></i> แก้ไขใบสั่งขาย</a></li>
                        <li><a class="dropdown-item '.$dis_prnt.'" href="javascript:void(0);" onclick="PrintDoc('.$Row['DocEntry'].','.$Row['IntStatus'].');"><i class="fas fa-print fa-fw fa-1x"></i> พิมพ์ใบสั่งขาย/ใบเสนอราคา</a></li>
                        <li><a class="dropdown-item '.$dis_impt.'" href="javascript:void(0);" onclick="ImportDoc('.$Row['DocEntry'].');"><i class="fas fa-share-square fa-fw fa-1x"></i> Import to SAP&reg;</a></li>
                        <li><a class="dropdown-item '.$dis_cncl.'" href="javascript:void(0);" onclick="CancelDoc('.$Row['DocEntry'].');"><i class="fas fa-ban fa-fw fa-1x"></i> ยกเลิกใบสั่งขาย</a></li>
                    </ul>
                </div>';
        }
    }
}

if($_GET['p'] == 'ViewDoc') {
    $DocEntry = $_POST['DocEntry'];
    $SiteID = $_SESSION['SITE']['site_id'];
    // รายการสินค้า
    $SQL1 = 
        "SELECT 
            T0.CardCode, T0.CardName, T0.LicTradeNum, T0.DocDate, T0.DocDueDate, T0.GroupNum,
            T0.BilltoAddress, T0.ShiptoAddress, IFNULL(T1.SlpName,'') as 'SlpCode', T0.U_PONo, T0.Comments,
	        T0.DiscPcnt, T0.DiscTotal, T0.DocTotal, T0.VatSum, T0.U_SO_Type
        FROM order_header T0
        LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode AND T1.site_id = $SiteID
        WHERE DocEntry = $DocEntry";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if($RST1) {
        $RST1 = $RST1[0];
        $U_PONo = ($RST1['U_PONo'] != null) ? $RST1['U_PONo'] : "";

        $SQL_GroupNum = "SELECT TOP 1 T0.[GroupNum], T0.[PymntGroup] FROM OCTG T0 WHERE T0.[GroupNum] = ".$RST1['GroupNum']."";
        $RST_GroupNum = DBConnect("SAP")->query(SQLtoHANA($SQL_GroupNum))->fetchAll()[0];
        $U_SO_Type = "";
        switch($RST1['U_SO_Type']) {
            case '99': $U_SO_Type = "Commercial"; break;
            case '10': $U_SO_Type = "Influencer"; break;
            case '20': $U_SO_Type = "Event"; break;
            case '30': $U_SO_Type = "Promotion Team"; break;
            case '40': $U_SO_Type = "MKT Rewards"; break;
            case '50': $U_SO_Type = "MKT Support"; break;
        }
        $tBody = [
            $RST1['CardCode']." | ".$RST1['CardName'], $RST1['LicTradeNum'],
            date("d/m/Y", strtotime($RST1['DocDate'])), date("d/m/Y", strtotime($RST1['DocDueDate'])),
            SapTH($RST_GroupNum['PymntGroup']), $U_SO_Type,
            $RST1['BilltoAddress'], $RST1['ShiptoAddress'],
            $RST1['SlpCode'], $U_PONo
        ];

        $inval['Comments'] =  $RST1['Comments'];
        $inval['DiscPcnt'] = $RST1['DiscPcnt'];
        $inval['DiscTotal'] = $RST1['DiscTotal'];
        $inval['DocTotal'] = $RST1['DocTotal'];
        $inval['VatSum'] = $RST1['VatSum'];
        $inval['tBody'] = $tBody;

        $SQL2 = 
            "SELECT 
                T0.ItemCode, T0.CodeBars, T0.ItemName, T0.WhsCode, T0.Quantity, T0.UnitMsr,
                T0.GrandPrice, T0.Line_Disc0, T0.Line_Disc1, T0.Line_Disc2, T0.Line_Disc3, T0.Line_Disc4,
                T0.UnitPrice, T0.LineTotal, T0.Line_SP
            FROM order_detail T0
            WHERE T0.LineStatus != 'I' AND T0.DocEntry = $DocEntry
            ORDER BY T0.VisOrder";
        $RST2 = DBConnect("APP")->query($SQL2)->fetchAll(PDO::FETCH_ASSOC);
        $inval['ItemList'] = $RST2;
    }

    // สถานะการอนุมัติ
    $StatusTabApp = 'N';
    $SQL3 = 
        "SELECT
            T0.AppID, T1.LvName, T0.APP0, T0.APP1, T0.APP2, T0.APP3, T0.APP4,
            T0.AppResult, IFNULL(T0.AppRemark,'') AS 'AppRemark', IFNULL(CONCAT(T2.TH_uFirstName,' ',T2.TH_uLastName),'') AS 'ApproveName', T0.DateApproved
        FROM order_approve T0
        LEFT JOIN positions T1 ON T0.LvClassReq = T1.LvCode
        LEFT JOIN users T2 ON T0.uKeyApproved = T2.uKey
        WHERE T0.DocEntry = $DocEntry";
    $RST3 = DBConnect("APP")->query($SQL3)->fetchAll(PDO::FETCH_ASSOC);
    if($RST3) {
        $StatusTabApp = 'Y';
        $inval['ItemApprove'] = $RST3;
    }
    $inval['StatusTabApp'] = $StatusTabApp;
}

if($_GET['p'] == 'DocAttach') {
    $DocEntry = $_POST['DocEntry'];

    $SQL1 = 
        "SELECT T0.AttachID, T0.FileOriName, T0.FileDirName, T0.FileExt, T0.DateCreate
        FROM order_attach T0 
        WHERE T0.DocEntry = $DocEntry AND T0.FileStatus = 'A'
        ORDER BY T0.VisOrder";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll(PDO::FETCH_ASSOC);
    $inval['ItemAttach'] = $RST1;
}

if($_GET['p'] == 'ActiveBTN') {
    $AttachID = $_POST['ID'];
    $uKey = $_SESSION['UKEY'];
    $FileStatus = "I";
    $SQL = "UPDATE order_attach SET FileStatus = :FileStatus, uKeyUpdate = :uKeyUpdate, DateUpdate = NOW() WHERE AttachID = :AttachID";
    $QRY = DBConnect("APP")->prepare($SQL);
    $QRY->bindparam(":FileStatus", $FileStatus);
    $QRY->bindparam(":uKeyUpdate", $uKey);
    $QRY->bindparam(":AttachID", $AttachID);
    $RST = $QRY->execute();
    $inval['Status'] = (!$RST) ? "ERR" : "OK";
}

if($_GET['p'] == 'UploadsFile') {
    $DocEntry = $_POST['DocEntry'];
    $UKEY = $_SESSION['UKEY'];
    $SQL1 = "SELECT T0.VisOrder, T0.FileDirName FROM order_attach T0 WHERE T0.DocEntry = $DocEntry ORDER BY T0.VisOrder DESC LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if($RST1) {
        $RST1 = $RST1[0];
        $FileDirName = explode('-',$RST1['FileDirName']);
        $NewOrder = ($RST1['VisOrder']+1);
        $FileDirName = $FileDirName[0]."-".$FileDirName[1]."-".$NewOrder;
        if(isset($_FILES['AttachOrder']['name'])) { 
            $FileProcess  = explode(".",basename($_FILES['AttachOrder']['name']));
            $countProcess = count($FileProcess);
            if($countProcess == 2) {
                $FileOriName = $FileProcess[0];
                $FileExt     = $FileProcess[1];
            } else {
                $FileOriName = "";
                $FileExt     = $FileProcess[$countProcess-1];
                for($n = 0; $n <= $countProcess-2; $n++) {
                    $FileOriName .= $FileProcess[$n].".";
                }
                $FileOriName = substr($FileOriName,0,-1);
            }

            $tmpFilePath = $_FILES['AttachOrder']['tmp_name'];
            if($tmpFilePath != "") {
                $NewFileName = $FileDirName.".".$FileExt;
                $NewFilePath = "../../FileAttach/SO/".$NewFileName;
                move_uploaded_file($tmpFilePath, $NewFilePath);

                $SQL2 =
                    "INSERT INTO order_attach SET
                        DocEntry    = :DocEntry,
                        VisOrder    = :VisOrder,
                        FileOriName = :FileOriName,
                        FileDirName = :FileDirName,
                        FileExt     = :FileExt,
                        uKeyCreate  = :UKEY";
                $QRY2 = DBConnect("APP")->prepare($SQL2);
                $QRY2->bindparam(":DocEntry",    $DocEntry);
                $QRY2->bindparam(":VisOrder",    $NewOrder);
                $QRY2->bindparam(":FileOriName", $FileOriName);
                $QRY2->bindparam(":FileDirName", $FileDirName);
                $QRY2->bindparam(":FileExt",     $FileExt);
                $QRY2->bindparam(":UKEY",        $UKEY);
                if($QRY2->execute() === FALSE) {
                    $inval['Status'] = "ERR";
                } else {
                    $inval['Status'] = "OK";
                }
            }
        }
    }else{
        $NewOrder = 0;
        $SQL3 = "SELECT T0.DocType, T0.DocNum  FROM order_header T0 WHERE T0.DocEntry = $DocEntry";
        $RST3 = DBConnect("APP")->query($SQL3)->fetchAll()[0];
        $FileDirName = $RST3['DocType']."-".$RST3['DocNum']."-".$NewOrder;
        if(isset($_FILES['AttachOrder']['name'])) { 
            $FileProcess  = explode(".",basename($_FILES['AttachOrder']['name']));
            $countProcess = count($FileProcess);
            if($countProcess == 2) {
                $FileOriName = $FileProcess[0];
                $FileExt     = $FileProcess[1];
            } else {
                $FileOriName = "";
                $FileExt     = $FileProcess[$countProcess-1];
                for($n = 0; $n <= $countProcess-2; $n++) {
                    $FileOriName .= $FileProcess[$n].".";
                }
                $FileOriName = substr($FileOriName,0,-1);
            }

            $tmpFilePath = $_FILES['AttachOrder']['tmp_name'];
            if($tmpFilePath != "") {
                $NewFileName = $FileDirName.".".$FileExt;
                $NewFilePath = "../../FileAttach/SO/".$NewFileName;
                move_uploaded_file($tmpFilePath, $NewFilePath);

                $SQL2 =
                    "INSERT INTO order_attach SET
                        DocEntry    = :DocEntry,
                        VisOrder    = :VisOrder,
                        FileOriName = :FileOriName,
                        FileDirName = :FileDirName,
                        FileExt     = :FileExt,
                        uKeyCreate  = :UKEY";
                $QRY2 = DBConnect("APP")->prepare($SQL2);
                $QRY2->bindparam(":DocEntry",    $DocEntry);
                $QRY2->bindparam(":VisOrder",    $NewOrder);
                $QRY2->bindparam(":FileOriName", $FileOriName);
                $QRY2->bindparam(":FileDirName", $FileDirName);
                $QRY2->bindparam(":FileExt",     $FileExt);
                $QRY2->bindparam(":UKEY",        $UKEY);
                if($QRY2->execute() === FALSE) {
                    $inval['Status'] = "ERR";
                } else {
                    $inval['Status'] = "OK";
                }
            }
        }
    }
}

if($_GET['p'] == "CancelDoc") {
    $DocEntry = $_POST['DocEntry'];
    $UKEY     = $_SESSION['UKEY'];
    $SSID     = $_SESSION['SSID'];
    $WebCancel = "N";
    /* Check Import Entry */
    $SQL0 = "SELECT T0.ImportEntry FROM order_header T0 WHERE T0.DocEntry = $DocEntry LIMIT 1";
    $RST0 = DBConnect("APP")->query($SQL0)->fetchAll()[0];
    if($RST0['ImportEntry'] > 0 ) {
        $SQL8 = "SELECT T0.[CANCELED] FROM ORDR T0 WHERE T0.[DocEntry] = ".$RST0['ImportEntry'];
        $RST8 = DBConnect("SAP")->query(SQLtoHANA($SQL8))->fetchAll();
        if ($RST8['CANCELED'] == "Y"){
            $WebCancel = "Y";
        }
    } else{
        $WebCancel = "Y";
    }
    
    if ($WebCancel == "Y") {
        $SQL1 = 
            "UPDATE order_header SET
                CANCELED  = 'Y',
                DocStatus = 'C',
                uKeyCancel = :UKEY,
                DateCancel = NOW(),
                ssidCancel = :SSID,
                uKeyUpdate = :UKEY,
                DateUpdate = NOW(),
                ssidUpdate = :SSID,
                IntStatus  = 0
            WHERE DocEntry = :DocEntry";
        $QRY1 = DBConnect("APP")->prepare($SQL1);
        $QRY1->bindparam(":UKEY",     $UKEY);
        $QRY1->bindparam(":SSID",     $SSID);
        $QRY1->bindparam(":DocEntry", $DocEntry);
        $RST1 = $QRY1->execute();

        if($RST1) {
            $inval['Status']  = "OK";
            $inval['Message'] = "ยกเลิกใบสั่งขายสำเร็จ";
        } else {
            $inval['Status'] = "ERR";
            $inval['Message'] = "ไม่สามารถยกเลิกได้<br/>กรุณาติดต่อผู้ดูแลระบบ";
        }
    }else{
        $SiteID    = $_SESSION['SITE']['site_id'];
        $DocType   = "ORDR";
        $DocEntry  = $RST0[0]['ImportEntry'];
        $inval = CancelSAP($SiteID, $DocType, $DocEntry);

    }
    // $SQL1 = 
    //     "UPDATE order_header SET
    //         CANCELED  = 'Y',
    //         DocStatus = 'C',
    //         uKeyCancel = :UKEY,
    //         DateCancel = NOW(),
    //         ssidCancel = :SSID,
    //         uKeyUpdate = :UKEY,
    //         DateUpdate = NOW(),
    //         ssidUpdate = :SSID,
    //         IntStatus  = 0
    //     WHERE DocEntry = :DocEntry";
    // $QRY1 = DBConnect("APP")->prepare($SQL1);
    // $QRY1->bindparam(":UKEY",     $UKEY);
    // $QRY1->bindparam(":SSID",     $SSID);
    // $QRY1->bindparam(":DocEntry", $DocEntry);
    // $RST1 = $QRY1->execute();
    // $inval['Status'] = ($RST1) ? "OK" : "ERR" ;
}

if($_GET['p'] == "EditDoc") {
    if(isset($_GET['ImportItem'])) {
        $DocNum = substr($_POST['ImportSearchInput'],3);
        $SQL1 = 
            "SELECT
                T0.DocEntry,
                T0.DocType, T0.TaxType, T0.U_PONo, T0.U_SO_Type, T0.Comments,
                T0.DocDate, T0.DocDueDate, T0.CardCode, T0.LicTradeNum,
                T0.SlpCode, T0.GroupNum, T0.BilltoCode, T0.ShiptoCode,
                T0.DiscPcnt, T0.DiscTotal, T0.VatSum, T0.GrossProfit,

                T1.TransID,
                T1.VisOrder, T1.ItemCode, T1.CodeBars, T1.ItemName,
                T1.WhsCode, T1.Quantity, T1.UnitMsr, T1.GrandPrice, T1.UnitPrice, T1.UnitVat,
                T1.Line_Disc0, T1.Line_Disc1, T1.Line_Disc2, T1.Line_Disc3, T1.Line_Disc4,
                T1.LineTotal, T1.LineVatSum, T1.LineProfit, T1.Line_SP
            FROM order_header T0
            LEFT JOIN order_detail T1 ON T0.DocEntry = T1.DocEntry
            WHERE T0.DocNum = '$DocNum' AND T1.LineStatus != 'I'
            ORDER BY T1.VisOrder ASC";
    }else{
        $DocEntry = $_POST['DocEntry'];
        $SQL1 = 
            "SELECT
                T0.DocEntry,
                T0.DocType, T0.TaxType, T0.U_PONo, T0.U_SO_Type, T0.Comments,
                T0.DocDate, T0.DocDueDate, T0.CardCode, T0.LicTradeNum,
                T0.SlpCode, T0.GroupNum, T0.BilltoCode, T0.ShiptoCode,
                T0.DiscPcnt, T0.DiscTotal, T0.VatSum, T0.GrossProfit,

                T1.TransID,
                T1.VisOrder, T1.ItemCode, T1.CodeBars, T1.ItemName,
                T1.WhsCode, T1.Quantity, T1.UnitMsr, T1.GrandPrice, T1.UnitPrice, T1.UnitVat,
                T1.Line_Disc0, T1.Line_Disc1, T1.Line_Disc2, T1.Line_Disc3, T1.Line_Disc4,
                T1.LineTotal, T1.LineVatSum, T1.LineProfit, T1.Line_SP
            FROM order_header T0
            LEFT JOIN order_detail T1 ON T0.DocEntry = T1.DocEntry
            WHERE T0.DocEntry = $DocEntry AND T1.LineStatus != 'I'
            ORDER BY T1.VisOrder ASC";
    }
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();

    if($RST1) {
        $inval['Status'] = "OK";
        $DocEntry = "";
        foreach($RST1 as $key => $data) {
            /* Header */
            if($DocEntry == "") {
                $DocEntry = $data['DocEntry'];
                $inval['HD']['DocEntry']    = $DocEntry;
                $inval['HD']['CardCode']    = $data['CardCode'];
                $inval['HD']['DocType']     = $data['DocType'];
                $inval['HD']['TaxType']     = $data['TaxType'];
                $inval['HD']['U_PONo']      = $data['U_PONo'];
                $inval['HD']['U_SO_TYPE']   = $data['U_SO_Type'];
                $inval['HD']['Comments']    = $data['Comments'];
                $inval['HD']['DocDate']     = $data['DocDate'];
                $inval['HD']['DocDueDate']  = $data['DocDueDate'];
                $inval['HD']['LicTradeNum'] = $data['LicTradeNum'];
                $inval['HD']['SlpCode']     = $data['SlpCode'];
                $inval['HD']['GroupNum']    = $data['GroupNum'];
                $inval['HD']['BilltoCode']  = $data['BilltoCode'];
                $inval['HD']['ShiptoCode']  = $data['ShiptoCode'];
                $inval['HD']['DiscPcnt']    = $data['DiscPcnt'];
                $inval['HD']['DiscTotal']   = $data['DiscTotal'];
                $inval['HD']['VatSum']      = $data['VatSum'];
                $inval['HD']['GrossProfit'] = $data['GrossProfit'];
            }

            /* ItemList */
            $VisOrder = $data['VisOrder'];
            $Line_SPIcon = ($data['Line_SP'] == "Y") ? "<i class='fas fa-check fa-fw fa-1x'></i>" : "" ;
            if($data['Line_Disc0'] != NULL) {
                $Discount = "*".$data['Line_Disc0'];
            } else {
                if($data['Line_Disc4'] != NULL) {
                    $Discount = $data['Line_Disc1']."%+".$data['Line_Disc2']."%+".$data['Line_Disc3']."%+".$data['Line_Disc4']."%";
                }else if($data['Line_Disc3'] != NULL){
                    $Discount = $data['Line_Disc1']."%+".$data['Line_Disc2']."%+".$data['Line_Disc3']."%";
                }else if($data['Line_Disc2'] != NULL){
                    $Discount = $data['Line_Disc1']."%+".$data['Line_Disc2']."%";
                }else if($data['Line_Disc1'] != NULL){
                    $Discount = $data['Line_Disc1']."%";
                } else {
                    $Discount = "";
                }
            }

            $inval['BD'][$VisOrder]['ItemCode']     = $data['ItemCode'];
            $inval['BD'][$VisOrder]['CodeBars']     = $data['CodeBars'];
            $inval['BD'][$VisOrder]['ItemName']     = $data['ItemName'];
            $inval['BD'][$VisOrder]['ItemWhse']     = $data['WhsCode'];
            $inval['BD'][$VisOrder]['ItemQuantity'] = $data['Quantity'];
            $inval['BD'][$VisOrder]['UnitMsr']      = $data['UnitMsr'];
            $inval['BD'][$VisOrder]['Cost']         = $data['UnitPrice']- ($data['LineProfit']/$data['Quantity']);
            $inval['BD'][$VisOrder]['GrandPrice']   = $data['GrandPrice'];
            $inval['BD'][$VisOrder]['Discount']     = $Discount;
            $inval['BD'][$VisOrder]['UnitPrice']    = $data['UnitPrice'];
            $inval['BD'][$VisOrder]['LineTotal']    = $data['LineTotal'];
            $inval['BD'][$VisOrder]['SPPrice_Icon'] = $Line_SPIcon;
            $inval['BD'][$VisOrder]['SPPrice']      = $data['Line_SP'];
        }
    } else {
        $inval['Status'] = "ERR";
    }
}

if($_GET['p'] == "ImportSAP") {
    $SiteID    = $_SESSION['SITE']['site_id'];
    $DocType   = "ORDR";
    $DocEntry  = $_POST['DocEntry'];
    $ImportSAP = ImportSAP($SiteID,$DocType,$DocEntry);
    $inval['Status']   = $ImportSAP['Status'];
    $inval['Message']  = $ImportSAP['Message'];
}

if($_GET['p'] == 'ImportItem') {
    $ImportSearchInput = $_POST['[ImportSearchInput'];
    

}

array_push($JSON,$inval);
echo json_encode($JSON);
?>