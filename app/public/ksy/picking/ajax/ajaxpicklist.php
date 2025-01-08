<?php
include('../../../core/config.core.php');
include('../../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
session_start();
$resultArray = array();
$arrCol = array();
$output = "";
if($_SESSION['UserName']==NULL ){
	echo '<script>window.location="../../../"</script>';
}

if($_GET['a'] == 'CheckID') {
	$sql = "SELECT T0.ID, T0.SODocEntry, T0.QQst, T0.DocNum, T0.DocType, T0.StatusDoc, T0.TeamCode
			FROM picker_soheader T0
			WHERE T0.ID = '".$_POST['DocEntry']."'";
	$result = MySQLSelect($sql);
	$result['SODocEntry'] = $result['SODocEntry'];
	switch($result['TeamCode']){
		case 'TT2' :
			$CH = 'TTC';
			break;
		case 'TT1' :
		case 'OUL' :
			$CH = 'OUL';
			break;
		default :
			$CH = $result['TeamCode'];
			break;
	}
	$arrCol['CH'] = $CH;

	if ($result['QQst'] == 'Y'){
		$txtQQ = "(บิลด่วน)";
	}else{
		$txtQQ = "";
	}
	$DocNum = $result['DocNum']." ".$txtQQ;
	$DocNumX = $result['DocNum'];
	$arrCol['DocNum'] = $DocNum;
	$arrCol['DocNumX'] = $DocNumX;
	$arrCol['SODocEntry'] = $result['SODocEntry'];
	$arrCol['DocType'] = $result['DocType'];
	switch ($result['StatusDoc']){
		case '0' :
		case '4' :
		case '7' :
		case '8' :
		case '9' :
		case '10' :
		case '11' :
		case '12' :
		case '13' : 
			$StatusDoc = 'N'; 
			$arrCol['StatusDoc'] = $StatusDoc; break;
		default : 
			$StatusDoc = 'Y'; 
			$arrCol['StatusDoc'] = $StatusDoc; break;
	}
	$sqlCHK =  "SELECT SUM(P0.RowItem) AS RowItem,SUM(P0.FinishItem) AS FinishItem
				FROM (
					SELECT 1 AS RowItem,
						CASE WHEN T0.Qty = T0.OpenQty THEN 1 ELSE 0 END AS FinishItem
					FROM  picker_sodetail T0
					WHERE T0.DocEntry = '".$result['SODocEntry']."' AND T0.DocType = '".$result['DocType']."'
				) P0";
	$FinishData = MySQLSelect($sqlCHK);
	$arrCol['qtyShow'] = $FinishData['FinishItem']."/".$FinishData['RowItem'];

	$CkRow = CHKRowDB("SELECT * FROM picker_sodetail WHERE DocEntry = '".$result['SODocEntry']."' AND DocType = '".$result['DocType']."' AND Status != 0");
	// echo $CkRow." ".$result['SODocEntry'];
	if ($CkRow == 0){ //เพิ่มข้อมูลรายการใหม่
		switch ($result['DocType']){
			case 'ORDR' : // ใบสั่งขาย
				$sqlSAP ="SELECT T0.DocEntry,T0.VisOrder,T0.ItemCode,T0.Dscription AS ItemName,T0.CodeBars,T0.Quantity,T0.WhsCode
							FROM RDR1 T0
							WHERE T0.DocEntry = '".$result['SODocEntry']."'
							ORDER BY T0.VisOrder";
				$sqlSAPQRY = SAPSelect($sqlSAP);
				while ($resultSAP = odbc_fetch_array($sqlSAPQRY)){
					$sql_INSERT = "INSERT INTO picker_sodetail 
					               SET 	DocEntry = '".$result['SODocEntry']."', DocType = 'ORDR', VisOrder = '".$resultSAP['VisOrder']."', ItemCode = '".$resultSAP['ItemCode']."', 
								    	BarCode = '".$resultSAP['CodeBars']."', ItemName = '".conutf8($resultSAP['ItemName'])."', WhsCode = '".$resultSAP['WhsCode']."', 
										Qty = '".$resultSAP['Quantity']."'";
					MySQLInsert($sql_INSERT);

					$sql_OITM = "SELECT IsBom,BomGroup FROM oitm WHERE ItemCode = '".$resultSAP['ItemCode']."'";
					$sql_OITM_QRY = MySQLSelectX($sql_OITM);
					while($result_OITM = mysqli_fetch_array($sql_OITM_QRY)){
						if($result_OITM['IsBom'] == 0 AND $result_OITM['BomGroup'] != 0) {
							$SelectMSI2 = "SELECT T0.ItemCode,T1.ItemName,T0.Qty,T1.BarCode 
											FROM bomgroup T0 
											JOIN oitm T1 ON T0.ItemCode = T1.ItemCode 
											WHERE T0.BomGroup = ".$result_OITM['BomGroup']." AND T0.ItemStatus = 'A'";
							$sqlMSI2QRY = MySQLSelectX($SelectMSI2);
							while($resultMSI2 = mysqli_fetch_array($sqlMSI2QRY)) {
								$NewQty = $resultSAP['Quantity'] * $resultMSI2['Qty'];
								$ItemCode = $resultMSI2['ItemCode'];
								$sql_INSERT2 = "INSERT INTO picker_sodetail
												SET DocEntry = '".$result['SODocEntry']."', DocType = 'ORDR', VisOrder = '".$resultSAP['VisOrder']."', ItemCode = '".$ItemCode."',
													BarCode = '".$resultMSI2['BarCode']."', ItemName = '".$resultMSI2['ItemName']."', WhsCode = '".$resultSAP['WhsCode']."', 
													Qty = '".$NewQty."', BomItem = '1'";
								MySQLInsert($sql_INSERT2);
							}
						}
					}
				}
				break;
			case 'OWAB' ://ใบเบิกสินค้า
			case 'OWAS' :
				$sqlWAS1 = "SELECT lnNum, ItemCode, BarCode, ItemName, Qty, WhsCode, Remark FROM was1 WHERE DocEntry = '".$result['SODocEntry']."' AND StatusDoc = 1 ";
				$sqlWAS1QRY = MySQLSelectX($sqlWAS1);
				while($resultWAS1 = mysqli_fetch_array($sqlWAS1QRY)){
					$sql_INSERT = "INSERT INTO picker_sodetail
									SET DocEntry = '".$result['SODocEntry']."', DocType = '".$result['DocType']."', VisOrder = '".$resultWAS1['lnNum']."', ItemCode = '".$resultWAS1['ItemCode']."',
										BarCode = '".$resultWAS1['BarCode']."', ItemName = '".$resultWAS1['ItemName']."', WhsCode = '".$resultWAS1['WhsCode']."', Qty = '".$resultWAS1['Qty']."',
										Remark = '".$resultWAS1['Remark']."'";
					MySQLInsert($sql_INSERT);				   
					$sql_oitm = "SELECT IsBom, BomGroup FROM oitm WHERE ItemCode = '".$resultWAS1['ItemCode']."'";
					$sql_oitmQRY = MySQLSelectX($sql_oitm);
					while($result_oitm = mysqli_fetch_array($sql_oitmQRY)){
						$IsBomb = $result_oitm['IsBom'];
						if($IsBomb == 0 AND $result_oitm['BomGroup'] != 0 ) {
							$sql_bom = "SELECT T0.ItemCode, T1.ItemName, T0.Qty, T1.BarCode 
											FROM bomgroup T0 
											JOIN oitm T1 ON T0.ItemCode = T1.ItemCode 
											WHERE T0.BomGroup = ".$result_oitm['BomGroup']." AND T0.xActive = 1";
							$sql_bomQRY = MySQLSelectX($sql_bom);
							while($result_bom = mysqli_fetch_array($sql_bomQRY)) {
								$NewQty = $resultWAS1['Qty'] * $result_bom['Qty'];
								$ItemCode = $result_bom['ItemCode'];
								$sql_INSERT2 = "INSERT INTO picker_sodetail
												SET DocEntry = '".$result['SODocEntry']."', DocType = '".$result['DocType']."', VisOrder = '".$resultWAS1['lnNum']."', ItemCode = '".$ItemCode."',
													BarCode = '".$result_bom['BarCode']."', ItemName = '".$result_bom['ItemName']."', WhsCode = '".$resultWAS1['WhsCode']."', Qty = '".$NewQty."', BomItem = '1'";
								MySQLInsert($sql_INSERT2);	
							}
						}
					}
				}
				break;
		}
	}

	$NotLocation = " N1.LocRack NOT LIKE 'M%'  
					AND N1.LocRack NOT LIKE 'B1-P%' 
					AND N1.LocRack NOT LIKE '%Picking'  
					AND (N1.LocRack NOT LIKE 'C________03%' AND N1.LocRack NOT LIKE 'C________04%' AND N1.LocRack NOT LIKE 'C________05%')";
	// echo "DocType : ".$result['DocType']."\n";
	switch ($result['DocType']) {
		case 'ORDR':
			$sql_ORDR ="SELECT T1.Beginstr, T0.DocNum, T2.LastName, T2.FirstName, T0.CardCode, T0.CardName, T0.Comments
						FROM ORDR T0
								LEFT JOIN NNM1 T1 ON T0.Series = T1.Series
								LEFT JOIN OHEM T2 ON T0.OwnerCode = T2.empID
						WHERE T0.DocEntry = '".$result['SODocEntry']."'";
			$sql_ORDR_QRY = SAPSelect($sql_ORDR);
			$result_ORDR = odbc_fetch_array($sql_ORDR_QRY);
			$DataHead['CoName'] = conutf8($result_ORDR['LastName'])." ".conutf8($result_ORDR['FirstName']); 
			$DataHead['Customer'] = $result_ORDR['CardCode']." ".conutf8($result_ORDR['CardName']);
			$DataHead['Remark'] = conutf8($result_ORDR['Comments']);
			$Detail = 
				"SELECT T0.ID, T0.DocEntry, T0.DocType, T0.VisOrder, T0.ItemCode, T0.BarCode,
					CASE WHEN T0.ItemName IS NULL OR T0.ItemName = '' THEN T1.ItemName ELSE T0.ItemName END AS ItemName,
					T0.WhsCode, T0.Qty, T0.OpenQty, T0.Remark, T0.Status, T0.WaitOP, T0.BomItem,
					IFNULL(
						CASE
							WHEN T0.WhsCode IN ('KSY') 
								THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND $NotLocation ORDER BY N1.OnHand ASC LIMIT 1)
							WHEN T0.WhsCode = 'KB4' 
								THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND $NotLocation AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand ASC LIMIT 1)  
							ELSE 'RECIVE'
						END ,
						CASE
							WHEN T0.WhsCode IN ('KSY') 
								THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand <= T0.Qty AND $NotLocation ORDER BY N1.OnHand DESC LIMIT 1)
							WHEN T0.WhsCode = 'KB4' 
								THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand <= T0.Qty AND $NotLocation AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand DESC LIMIT 1)  
							ELSE 'RECIVE'
						END
					) AS DftLocation, 
					CASE
						WHEN T0.Qty = T0.OpenQty AND T0.LastRead = 'N' THEN 3
						WHEN T0.OpenQty != 0 AND T0.Qty > T0.OpenQty AND T0.LastRead = 'N' THEN 2
						WHEN T0.OpenQty = 0 AND T0.LastRead = 'N' THEN 1
						WHEN T0.LastRead = 'Y' THEN 0
					END AS LastRead,
					(SELECT SUM(P1.OnHand) FROM oitw P1 WHERE P1.WhsCode = T0.WhsCode AND P1.ItemCode = T0.ItemCode ) AS OnHand
				FROM picker_sodetail T0 
				LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode  
				WHERE T0.DocEntry = '".$result['SODocEntry']."' AND T0.DocType = '".$result['DocType']."'";
			// echo $Detail;
			break;
		case 'OWAB' :
		case 'OWAS' :
			$sql_owas = "SELECT T0.CusCode, T0.CusName, T1.uName, T1.uLastName, T1.uNickName, T0.Remark 
						FROM owas T0 
						LEFT JOIN  users T1 ON T0.UserCreate = T1.uKey
						WHERE T0.DocEntry = '".$result['SODocEntry']."'";
			$sqlQRY_owas = MySQLSelectX($sql_owas);
			$result_owas = mysqli_fetch_array($sqlQRY_owas);
			$DataHead['CoName'] = $result_owas['uName']." ".$result_owas['uLastName']."(".$result_owas['uNickName'].")";
			$DataHead['Customer'] = $result_owas['CusCode']." ".$result_owas['CusName'];
			$DataHead['Remark'] = $result_owas['Remark'];
			$Detail = 
				"SELECT DISTINCT T0.ID, T0.DocEntry, T0.DocType, T0.VisOrder, T0.ItemCode, T0.BarCode, T1.ItemCode AS ItemCode,
					CASE WHEN T0.ItemName IS NULL THEN T1.ItemName ELSE T0.ItemName END AS ItemName,
					T0.WhsCode,T0.Qty,T0.OpenQty,T0.Remark,T0.Status,T0.WaitOP,T0.BomItem,
					IFNULL(
						CASE
							WHEN T0.WhsCode IN ('KSY') 
								THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND $NotLocation ORDER BY N1.OnHand ASC LIMIT 1)
							WHEN T0.WhsCode = 'KB4' 
								THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND $NotLocation AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand ASC LIMIT 1)  
						ELSE 'RECIVE' END ,
						CASE
							WHEN T0.WhsCode IN ('KSY') 
								THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand <= T0.Qty AND $NotLocation ORDER BY N1.OnHand DESC LIMIT 1)
							WHEN T0.WhsCode = 'KB4' 
								THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand <= T0.Qty AND $NotLocation AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand DESC LIMIT 1)  
						ELSE 'RECIVE' END
					) AS DftLocation,
					CASE
						WHEN T0.Qty = T0.OpenQty AND T0.LastRead = 'N' THEN 3
						WHEN T0.OpenQty != 0 AND T0.Qty > T0.OpenQty AND T0.LastRead = 'N' THEN 2
						WHEN T0.OpenQty = 0 AND T0.LastRead = 'N' THEN 1
						WHEN T0.LastRead = 'Y' THEN 0
					END AS LastRead,
					(SELECT SUM(P1.OnHand) FROM oitw P1 WHERE P1.WhsCode = T0.WhsCode AND P1.ItemCode = T0.ItemCode ) AS OnHand
				FROM picker_sodetail T0 
				LEFT JOIN was1 T1 ON T0.ItemCode = T1.ItemCode AND T1.StatusDoc =  1 
				WHERE T0.DocEntry = '".$result['SODocEntry']."' AND T0.DocType = '".$result['DocType']."'";
			break;
	}
	$arrCol['Customer'] = $DataHead['Customer'];
	$arrCol['CoSale'] = $DataHead['CoName'];

	if($_POST['DocEntry'] != "56180") {
		$arrCol['Remark'] = $DataHead['Remark'];
	} else {
		$arrCol['Remark'] = "";
	}

	if ($_POST['Sort'] == '1'){
		$Detail .= " ORDER BY T0.VisOrder,T0.ItemCode";
	}else{
		$Detail .= " ORDER BY LastRead,DftLocation,T0.ItemCode";
	}
	$sqlQRY_Detail = MySQLSelectX($Detail);
	$ax=0;
	while ($result_Detail = mysqli_fetch_array($sqlQRY_Detail)){ //DetailList
		$ax++;
		$RowID[$ax] = $result_Detail['ID'];
		$ListDetail['ItemCode'][$RowID[$ax]] =  $result_Detail['ItemCode'];
		$ListDetail['VisOrder'][$RowID[$ax]] =  $result_Detail['VisOrder'];
		$ListDetail['BarCode'][$RowID[$ax]] =  $result_Detail['BarCode'];
		$ListDetail['ItemName'][$RowID[$ax]] =  $result_Detail['ItemName'];
		$ListDetail['BomItem'][$RowID[$ax]] =  $result_Detail['BomItem'];
		$ListDetail['QTY'][$RowID[$ax]] =  $result_Detail['Qty'];
		$ListDetail['WHS'][$RowID[$ax]] =  $result_Detail['WhsCode'];
		switch($result_Detail['WhsCode']){
			case 'KSY' :
			case 'KSM' :
				$WhsCode = " ('KSY','KSM') ";
				break;
			default :
				$WhsCode = "('".$result_Detail['WhsCode']."')";
				break;
		}
		/* ดึงคงคลังจาก SAP */
		// $sql1 = "SELECT SUM(OnHand) AS OnHand, SUM(ISCommited) AS Commited FROM oitw WHERE ItemCode = '".$result_Detail['ItemCode']."' AND WhsCode IN ".$WhsCode;
		// $sql1_QRY = SAPSelect($sql1);
		// $result_sql1 = odbc_fetch_array($sql1_QRY); //ReadHand
		// if ($ListDetail['BomItem'][$RowID[$ax]] == 1 ){
		// 	$result_sql1['OnHand'] = $result_Detail['OnHand'];
		// }

		// $sql2 ="SELECT SUM(T0.OpenQty) AS CoMit 
		// 		FROM picker_sodetail T0 
		// 		JOIN picker_soheader T1 ON T0.DocEntry = T1.SODocEntry AND T1.DocType = T0.DocType 
		// 		WHERE  T0.ItemCode = '".$result_Detail['ItemCode']."' AND T0.WhsCode IN ".$WhsCode." AND T1.StatusDoc BETWEEN 1 AND 7" ;
		// $result_sql2 = MySQLSelect($sql2);
		// $result_sql1['OnHand'] = $result_sql1['OnHand'] - $result_sql2['CoMit'];

		$ListDetail['Open'][$RowID[$ax]] =  $result_Detail['OpenQty'];
		$ListDetail['Remark'][$RowID[$ax]] =  $result_Detail['Remark'];   
		$ListDetail['Status'][$RowID[$ax]] =  $result_Detail['Status'];
		$ListDetail['WaitOP'][$RowID[$ax]] =  $result_Detail['WaitOP'];

		if (($result_Detail['DftLocation'] != '' OR  $result_Detail['DftLocation'] != NULL) AND $result['DocType'] != 'OWAS'){
			$ListDetail['NameShow'][$RowID[$ax]] =  $result_Detail['DftLocation'];
		}else{
			$ListDetail['NameShow'][$RowID[$ax]] =  $result_Detail['BarCode'];
		}

		// if ($result_sql1['OnHand'] < $ListDetail['QTY'][$RowID[$ax]] AND $ListDetail['BomItem'][$RowID[$ax]] == 0 ){
		// 	$ListDetail['OnHand'][$RowID[$ax]] =  "<span class='fw-bold text-primary'>".number_format($result_sql1['OnHand'],0)."</span>";
		// }else{
		// 	$ListDetail['OnHand'][$RowID[$ax]] =  "<span class='fw-bold text-dark'>".number_format($result_sql1['OnHand'],0)."</span>";
		// }
		$ListDetail['DftLoc'][$RowID[$ax]] =  $result_Detail['DftLocation'];
		//WAI - if ($result_Detail['WhsCode'] == 'KSY' OR $result_Detail['WhsCode'] == 'MT' OR $result_Detail['WhsCode'] == 'MT2' OR $result_Detail['WhsCode'] == 'OUL' OR $result_Detail['WhsCode'] == 'TT-C') {
		if ($result_Detail['WhsCode'] == 'KSY' ) {
			$NotLocation2 =  str_replace("N1","T0",$NotLocation);
			$sql3 = "SELECT T0.LocRack,T0.OnHand,
							CASE WHEN T0.OnHand = ".$result_Detail['Qty']." THEN 0
								WHEN T0.OnHand > ".$result_Detail['Qty']."  THEN 1
							ELSE 2 END AS OrderBy  
					FROM oitw T0 
					WHERE ItemCode = '".$result_Detail['ItemCode']."' AND (T0.WhsCode IN ('KSY') ) AND ".$NotLocation2." AND T0.OnHand > 0
					ORDER BY T0.OnHand";
			$sql3_QRY = MySQLSelectX($sql3);
			$cx = 0;
			$A0 = 0;
			$A1 = 0;
			$A2 = 0;
			$OnHand[0] = 0;
			$LocRack[0] = 0;
			$OnHand[1] = 0;
			$LocRack[1] = 0;
			$OnHand[2] = 0;
			$LocRack[2] = 0;
			while ($result3 = mysqli_fetch_array($sql3_QRY)){ //DetaRack
				if ($result3['OrderBy'] == 0){
					$OnHand[0] = $result3['OnHand'];
					$LocRack[0] = $result3['LocRack'];
					$A0++;
				}
				if ($result3['OrderBy'] == 1){
					if ($A1 == 0){
						$OnHand[1] = $result3['OnHand'];
						$LocRack[1] = $result3['LocRack'];
					}
					$A1++;
				}
				if ($result3['OrderBy'] == 2){
					$OnHand[2] = $result3['OnHand'];
					$LocRack[2] = $result3['LocRack'];
					$A2++;
				}
				$cx++;
			}
			if ($cx > 0){
				if ($A0 > 0){
					//ใส่ค่าใหม่
					$ListDetail['DftLoc'][$RowID[$ax]] = $LocRack[0];
				}else{
					if ($A1 > 0){
						//ใส่ค่าใหม่
						$ListDetail['DftLoc'][$RowID[$ax]] = $LocRack[1];
					}else{
						$ListDetail['DftLoc'][$RowID[$ax]] = $LocRack[2];
					}
				}
			}

		}
		if (($ListDetail['DftLoc'][$RowID[$ax]]!= '' OR  $ListDetail['DftLoc'][$RowID[$ax]] != NULL) AND $result['DocType'] != 'OWAS'){
			$ListDetail['NameShow'][$RowID[$ax]] =  $ListDetail['DftLoc'][$RowID[$ax]];
		}else{
			$ListDetail['NameShow'][$RowID[$ax]] =  $result_Detail['BarCode'];
		}
	}

	$runNo = 0;
	$runNo2 = 0;
	$ShowNo;
	$Tbody = "";
	for ($i = 1; $i <= $ax; $i++){
		// $MaxOpen = สี เบิกแล้ว
		if ($ListDetail['Open'][$RowID[$i]] == $ListDetail['QTY'][$RowID[$i]]){
			$MaxOpen = "fw-bold text-success";
			$sqlUpdate = "UPDATE picker_sodetail SET Status = 1 WHERE ID = ".$RowID[$i]."";
			MySQLUpdate($sqlUpdate);
		}else{
			if ($ListDetail['Open'][$RowID[$i]] != 0){
				$MaxOpen = "fw-bold";
			}else{
				$MaxOpen = ""; //text-muted
			} 
		}

		// Status
		switch ($ListDetail['Status'][$RowID[$i]]){
			case 2 :
				$BGColorAndTextColor = "background-color: #FFFF99; color: #000000;";//ส่งเท่าทีมี (เช็คหมายเหตุด้วยว่า ตัดออกเอาเท่าไหร) เหลือง
				break;
			case 3 :
				$BGColorAndTextColor = "background-color: #EE7F7F; color: #FFFFFF;";//ตัดหมด แดง
				break;
			case 9 :
				$BGColorAndTextColor = "background-color: #D4B6E0; color: #FFFFFF;";//เบิกเพิ่ม ม่วง
				break;
			default :
				if  ($ListDetail['WaitOP'][$RowID[$i]]  != 0 OR  $ListDetail['Remark'][$RowID[$i]] != ''){
					$BGColorAndTextColor = "background-color: #CB9536; ";//รอสินค้า มีหมายเหตุให้อ่านก่อน
				}else{
					$BGColorAndTextColor = "";//รายการปกติ 
				}
			
				break;
	
		}
		// echo $ListDetail['BomItem'][$RowID[$i]];
		if ($ListDetail['BomItem'][$RowID[$i]] == 0){
			$runNo++; 
			$ShowNo = $runNo;
			if($ShowNo < 10) {
				$paddingShowNo = "16px";
			}else{
				$paddingShowNo = "13px";
			}
		}else{
			$runNo2++;
			$ShowNo = $runNo.".".$runNo2;
			if($ShowNo < 10) {
				$paddingShowNo = "11px";
			}else{
				$paddingShowNo = "7px";
			}
		}
		// echo $ShowNo." | ".$paddingShowNo."\n";

		$Tbody.="<tr>";
		if($ListDetail['WHS'][$RowID[$i]] != 'KSY') {		
			$Tbody.="<td class='p-1' colspan='2'>".
						"<div class='d-flex align-items-center' style='".$BGColorAndTextColor." border-radius: 10px 10px 0px 0px; box-shadow: 1px 0px #bbb;'>".
							"<a class='fw-bold' style='padding-left: 40px;' data-bs-toggle='collapse' href='#CollaT".$RowID[$i]."' role='button' aria-expanded='false' aria-controls='CollaT".$RowID[$i]."'>
								<span style='color: blue';>[".$ListDetail['WHS'][$RowID[$i]]."]</span> ".$ListDetail['NameShow'][$RowID[$i]]."
							</a>".
						"</div>".
						"<div class='d-flex' style='".$BGColorAndTextColor." box-shadow: 1px 0px #bbb;'>".
							"<span class='fw-bold' style='padding: 0px ".$paddingShowNo.";'>".$ShowNo."</span>".
							"<span>".$ListDetail['ItemName'][$RowID[$i]]."</span>".
						"</div>";
		}else{
			$Tbody.="<td class='p-1' colspan='2'>".
						"<div class='d-flex align-items-center' style='".$BGColorAndTextColor."border-radius: 10px 10px 0px 0px; box-shadow: 1px 0px #bbb;'>".
							"<a class='fw-bold' style='padding-left: 40px;' data-bs-toggle='collapse' href='#CollaT".$RowID[$i]."' role='button' aria-expanded='false' aria-controls='CollaT".$RowID[$i]."'>
								<span style='color: blue';>[".$ListDetail['WHS'][$RowID[$i]]."]</span> ".$ListDetail['NameShow'][$RowID[$i]]."
							</a>".
						"</div>".
						"<div class='d-flex' style='".$BGColorAndTextColor."box-shadow: 1px 0px #bbb;'>".
							"<span class='fw-bold' style='padding: 0px ".$paddingShowNo.";'>".$ShowNo."</span>".
							"<span>".$ListDetail['ItemName'][$RowID[$i]]."</span>".
						"</div>";
		}
				$Tbody.="<div class='d-flex align-items-center justify-content-around w-100' style='".$BGColorAndTextColor." padding-left: 40px; border-radius: 0px 0px 10px 10px; box-shadow: 1px 1px #bbb;'>".
							"<span style='width: 30%;'>สั่งซื้อ <span class='text-primary'>".number_format($ListDetail['QTY'][$RowID[$i]],0)."</span></span>".
							"<span style='width: 30%;'>เบิกแล้ว <span id='NewQty_".$RowID[$i]."' class='".$MaxOpen."'>".number_format($ListDetail['Open'][$RowID[$i]],0)."</span></span>".
							"<span style='width: 30%;'>คงคลัง <a href='javascript:void(0);' onclick=\"ShowOnHand(this,'".str_replace(" ","",$ListDetail['ItemCode'][$RowID[$i]])."','".$ListDetail['WHS'][$RowID[$i]]."','".$ListDetail['QTY'][$RowID[$i]]."','".$ListDetail['BomItem'][$RowID[$i]]."');\" class='ViewQty'><i class='far fa-eye fa-fw fa-lg'></i></a></span>".
							"<a class='text-danger' style='width: 10%;' href='javascript:void(0);' onclick=\"CallFunction('DelRow',".$RowID[$i].")\"><i class='fas fa-trash'></i></a>".
						"</div>";

		// CollaT
		// $sqlCollaT = "SELECT T0.ID,T0.DocEntry,T0.VisOrder,T0.ItemCode,T0.BarCode,T0.Qty,
		// 				CASE WHEN T0.ItemName IS NULL OR T0.ItemName = '' THEN T1.ItemName ELSE T0.ItemName END AS ItemName,
		// 				T0.Remark,T0.WaitOP,T0.WhsCode,
		// 				CASE WHEN T0.WhsCode IN ('KSY') THEN (SELECT N1.LocRack 
		// 																			FROM oitw N1
		// 																			WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand >= T0.Qty) AND ".$NotLocation." ORDER BY N1.OnHand ASC LIMIT 1) 
		// 					WHEN T0.WhsCode = 'KB4' THEN (SELECT N1.LocRack 
		// 												FROM oitw N1
		// 												WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND ".$NotLocation." AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand ASC LIMIT 1)
		// 					ELSE 'RECIVE' END AS DftLocation,
		// 				CASE WHEN T0.WhsCode IN ('KSY') THEN (SELECT N1.OnHand 
		// 																			FROM oitw N1
		// 																			WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND ".$NotLocation." ORDER BY N1.OnHand ASC LIMIT 1) 
		// 					ELSE (SELECT N1.OnHand 
		// 						FROM oitw N1
		// 						WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND ".$NotLocation." AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand ASC LIMIT 1)
		// 					END AS OnShelf
		// 			FROM picker_sodetail T0
		// 				LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
		// 			WHERE T0.ID = '".$RowID[$i]."'";
		$sqlCollaT = 
			"SELECT
				T0.ID,T0.DocEntry,T0.VisOrder,T0.ItemCode,T0.BarCode,T0.Qty,T0.Remark,T0.WaitOP,T0.WhsCode, T0.DocType,
				CASE WHEN T0.ItemName IS NULL OR T0.ItemName = '' THEN T1.ItemName ELSE T0.ItemName END AS ItemName,
				IFNULL(
					CASE
						WHEN T0.WhsCode IN ('KSY')
							THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand >= T0.Qty) AND $NotLocation ORDER BY N1.OnHand ASC LIMIT 1) 
						WHEN T0.WhsCode = 'KB4'
							THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand >= T0.Qty) AND $NotLocation AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand ASC LIMIT 1)
						ELSE 'RECIVE'
					END ,
					CASE
						WHEN T0.WhsCode IN ('KSY')
							THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand <= T0.Qty) AND $NotLocation ORDER BY N1.OnHand DESC LIMIT 1) 
						WHEN T0.WhsCode = 'KB4'
							THEN (SELECT N1.LocRack FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand <= T0.Qty) AND $NotLocation AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand DESC LIMIT 1)
						ELSE 'RECIVE'
					END
				) AS DftLocation,
				IFNULL(
					CASE
						WHEN T0.WhsCode IN ('KSY')
							THEN (SELECT N1.OnHand FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand >= T0.Qty) AND $NotLocation ORDER BY N1.OnHand ASC LIMIT 1) 
						ELSE (SELECT N1.OnHand FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand >= T0.Qty) AND $NotLocation AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand ASC LIMIT 1)
					END,
					CASE
						WHEN T0.WhsCode IN ('KSY')
							THEN (SELECT N1.OnHand FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand <= T0.Qty) AND $NotLocation ORDER BY N1.OnHand DESC LIMIT 1) 
						ELSE (SELECT N1.OnHand FROM oitw N1 WHERE N1.ItemCode = T0.ItemCode AND (N1.OnHand <= T0.Qty) AND $NotLocation AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand DESC LIMIT 1)
					END
				) AS OnShelf
			FROM picker_sodetail T0
			LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
			WHERE T0.ID = '".$RowID[$i]."'";
		// echo $sqlCollaT;
		$resultCollaT = MySQLSelect($sqlCollaT);
		$DetailData['Remark'] = $resultCollaT['Remark'];
		$DetailData['DocEntry'] = $resultCollaT['DocEntry'];
		// $DetailData['InCommit'] = number_format($resultCollaT['InCommit']); ??
		$DetailData['OnShelf'] = $resultCollaT['OnShelf'];
		$DetailData['CodeBars'] = $resultCollaT['BarCode'];
		$DetailData['ItemCode'] = $resultCollaT['ItemCode'];
		$DetailData['WhsCode'] = $resultCollaT['WhsCode'];
		$DetailData['DocType'] = $resultCollaT['DocType'];

		$DetailData['Location'] =  $resultCollaT['DftLocation'];
		//WAI - if ($resultCollaT['WhsCode'] == 'KSY' OR $resultCollaT['WhsCode'] == 'MT' OR $resultCollaT['WhsCode'] == 'MT2' OR $resultCollaT['WhsCode'] == 'OUL' OR $resultCollaT['WhsCode'] == 'TT-C') {
		if ($resultCollaT['WhsCode'] == 'KSY' ) {
			$sqlCol1 = "SELECT T0.LocRack,T0.OnHand,
							CASE WHEN T0.OnHand = ".$resultCollaT['Qty']." THEN 0
								WHEN T0.OnHand > ".$resultCollaT['Qty']."  THEN 1
							ELSE 2 END AS OrderBy  
					FROM oitw T0 
					WHERE ItemCode = '".$resultCollaT['ItemCode']."' AND (T0.WhsCode IN ('KSY') ) AND ".$NotLocation2." AND T0.OnHand > 0
					ORDER BY T0.OnHand";
			// echo $sqlCol1;
			$resultCol1 = MySQLSelectX($sqlCol1);
			$cx_Col = 0;
			$A0_Col = 0;
			$A1_Col = 0;
			$A2_Col = 0;
			$OnHand_Col[0] = 0;
			$LocRack_Col[0] = 0;
			$OnHand_Col[1] = 0;
			$LocRack_Col[1] = 0;
			$OnHand_Col[2] = 0;
			$LocRack_Col[2] = 0;
			while ($result_Col = mysqli_fetch_array($resultCol1)){ //DetaRack
				if ($result_Col['OrderBy'] == 0){
					$OnHand_Col[0] = $result_Col['OnHand'];
					$LocRack_Col[0] = $result_Col['LocRack'];
					$A0_Col++;
				}
				if ($result_Col['OrderBy'] == 1){
					if ($A1_Col == 0){
						$OnHand_Col[1] = $result_Col['OnHand'];
						$LocRack_Col[1] = $result_Col['LocRack'];
					}
					$A1_Col++;
				}
				if ($result_Col['OrderBy'] == 2){
					$OnHand_Col[2] = $result_Col['OnHand'];
					$LocRack_Col[2] = $result_Col['LocRack'];
					$A2_Col++;
				}
				$cx_Col++;
			}
			if ($cx_Col > 0){
				if ($A0_Col > 0){
					//ใส่ค่าใหม่
					$DetailData['OnShelf'] = $OnHand_Col[0];
					$DetailData['Location'] = $LocRack_Col[0];
				}else{
					if ($A1_Col > 0){
						//ใส่ค่าใหม่
						$DetailData['OnShelf'] = $OnHand_Col[1];
						$DetailData['Location'] = $LocRack_Col[1];
					}else{
						$DetailData['OnShelf'] = $OnHand_Col[2];
						$DetailData['Location'] = $LocRack_Col[2];
					}
				}
			}
		}

		$DetailData['ItemName'] = $resultCollaT['ItemName'];
		$OP1 = ""; $OP2 = ""; $OP3 = ""; $OP4 = ""; $OP5 = ""; $OP6 = "";
		switch ($resultCollaT['WaitOP']){
			case  1 :
				$OP1 = "selected";
			break;
			case  2 :
				$OP2 = "selected";
			break;
			case  3 :
				$OP3 = "selected";
			break;
			case  4 :
				$OP4 = "selected";
			break;
			case  5 :
				$OP5 = "selected";
			break;
			case  6 :
				$OP6 = "selected";
			break;
		}
		if  ($DetailData['WhsCode'] == 'KSY'){
			if (substr($DetailData['Location'],0,4) == 'B1-P'){
				$remark = "<span style='color:blue'> ไม่สามารถเบิกได้ </span>";
			}else{
				$remark = ""; 
			}
		}else{
			$remark = "";
		}
		if ($DetailData['WhsCode'] == 'KSM'){
			$remark = "<span style='color:blue'> เปิดคลังผิดกรุณาแก้ SO </span>";
		}
		$Tbody.="<div class='collapse ps-2 pe-2' id='CollaT".$RowID[$i]."' style='padding-top: 1px;'>".
					"<table class='table table-sm table-borderless text-dark' style='font-size: 13px; border-radius: 0px 0px 10px 10px; background-color: #e6eaee;'>".
						"<tr>".
							"<td class=''>รหัสสินค้า</td>".
							"<td>".$DetailData['ItemCode']."</td>".
						"</tr>".
						"<tr>".
							"<td class=''>บาร์โค้ด</td>".
							"<td>".$DetailData['CodeBars']."</td>".
						"</tr>".
						"<tr>".
							"<td class=''>ชื่อสินค้า</td>".
							"<td>".$DetailData['ItemName']."</td>".
						"</tr>".
						"<tr>".
							"<td class=''>Location</td>".
							"<td><span onclick=\"CallLocation('".$DetailData['ItemCode']."')\">".$DetailData['Location'].$remark."</span></td>".
						"</tr>".
						"<tr>".
							"<td class=''>On Shelf</td>".
							"<td>".$DetailData['OnShelf']."</td>".
						"</tr>".
						"<tr>".
							"<td class=''>รอสินค้า</td>".
							"<td>".
								"<select class='form-select form-select-sm' id='WaitOP".$i."_".$DetailData['DocEntry']."_".$RowID[$i]."' name='WaitOP".$i."_".$DetailData['DocEntry']."_".$RowID[$i]."' onchange=\"AddRemark('".$i."','".$DetailData['DocEntry']."','".$RowID[$i]."','".$DetailData['DocType']."')\">".
									"<option value=''></option>".
									"<option value='1' ".$OP1.">รอสินค้าจาก KBI</option>".
									"<option value='2' ".$OP2.">รอประกอบสินค้า</option>".
									"<option value='3' ".$OP3.">รอแปลงสินค้า</option>".
									"<option value='4' ".$OP4.">รอถอดอะไหล่</option>".
									"<option value='5' ".$OP5.">รอสินค้าเข้า</option>".
									"<option value='6' ".$OP6.">รอสินค้า</option>".
								"</select>".
							"</td>".
						"</tr>".
						"<tr>".
							"<td class=''>หมายเหตุ</td>".
							"<td>".
								"<textarea class='form-control form-control-sm' name='Remark".$i."_".$DetailData['DocEntry']."_".$RowID[$i]."' id='Remark".$i."_".$DetailData['DocEntry']."_".$RowID[$i]."' value='".$DetailData['Remark']."' onfocusout=\"AddRemark('".$i."','".$DetailData['DocEntry']."','".$RowID[$i]."','".$DetailData['DocType']."')\">"
									.$DetailData['Remark'].
								"</textarea>".
							"</td>".
						"</tr>".
					"</table>".
				"</div>".
			"</td>".
		"</tr>";
		// echo $_POST['DocEntry']."_".$RowID[$i]."_".$DetailData['Remark']."\n";
	}
	$arrCol['Tbody'] = $Tbody;
}

if($_GET['a'] == 'CallLocation') {
	$tbl = "";
	$sql = "SELECT WhsCode, LocRack, SUM(OnHand) AS OnHand 
			FROM oitw 
			WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode IN ('KSY','KSM','TT-C','MT','MT2','OUL','ONL','KB4') AND OnHand != 0 GROUP BY WhsCode,LocRack";
	$sqlQRY = MySQLSelectX($sql);
	while($result = mysqli_fetch_array($sqlQRY)) {
		$tbl .="<tr>
					<td class='text-center'>".$result['WhsCode']."</td>
					<td>".$result['LocRack']."</td>
					<td class='text-right'>".number_format($result['OnHand'])."</td>
				</tr>";
	}
	$arrCol['tbl'] = $tbl;
}

if($_GET['a'] == 'AddRemarkAndWaitOP') {
	$Update1 = "UPDATE picker_sodetail SET Remark = '".$_POST['Remark']."', WaitOP = '".$_POST['WaitOP']."' WHERE DocEntry = '".$_POST['DocEntry']."' AND ID = '".$_POST['RowID']."'";
	MySQLUpdate($Update1);
	if ($_POST['WaitOP'] != 0){
		$Update2 = "UPDATE picker_sodetail SET Status = 4 WHERE DocEntry = '".$_POST['DocEntry']."' AND ID = '".$_POST['RowID']."'";
		MySQLUpdate($Update2);
		$Update3 = "UPDATE picker_soheader SET StatusDoc = 6 WHERE SODocEntry = '".$_POST['DocEntry']."' AND DocType = '".$_POST['DocType']."'";
		MySQLUpdate($Update3);
	}
}

if($_GET['a'] == 'DelRow') {
	// echo $_POST['DocEntry']." | ".$_POST['RowID'];
	$result = MySQLSelect("SELECT * FROM picker_sodetail WHERE ID = ".$_POST['RowID']."");
	$Insert = "INSERT INTO transecdata 
	 		   SET WhsCode = '".$result['WhsCode']."', LocationRack = '".$result['WhsCode']."-Picking', ItemCode = '".$result['ItemCode']."', QtyOut = '".$result['OpenQty']."', 
			   	   DateCreate = NOW(), ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, trnCode = '".$result['DocEntry']."', ukeyCancel = '".$result['ID']."' ";
	MySQLInsert($Insert);

	$result2 = MySQLSelect("SELECT OnHand FROM oitw WHERE ItemCode = '".$result['ItemCode']."' AND WhsCode = '".$result['WhsCode']."' AND LocRack = '".$result['WhsCode']."-Picking'");
	if(isset($result2['OnHand'])) {
		$NewOhHand = $result2['OnHand'] - $result['OpenQty'];
		$Update2 = "UPDATE oitw SET OnHand = ".$NewOhHand." WHERE ItemCode = '".$result['ItemCode']."' AND WhsCode = '".$result['WhsCode']."' AND LocRack = '".$result['WhsCode']."-Picking''";
		MySQLUpdate($Update2);

		$Update1 = "UPDATE picker_sodetail SET OpenQty = 0 WHERE ID = ".$_POST['RowID']."";
		MySQLUpdate($Update1);
		$text = "ยกเลิกรายการเรียบร้อย";
	}else{
		$text = "DataBase ยังไม่มีข้อมูล";
	}

	$arrCol['text'] = $text;
}

if($_GET['a'] == 'TxtQty') {
	$sqlCk = "SELECT T0.ItemCode, T0.ItemName FROM OITM T0 WHERE (T0.ItemCode = '".$_POST['ItemCode']."' OR T0.BarCode = '".$_POST['ItemCode']."'  OR T0.BarCode2 = '".$_POST['ItemCode']."' OR T0.BarCode3 = '".$_POST['ItemCode']."')";
	$CkRow = CHKRowDB($sqlCk);
	if($CkRow == 1) {
		$sql = "SELECT 	T0.ID, T0.ItemCode, T1.ItemName, T0.Qty, T0.OpenQty,
						CASE WHEN T0.Qty = T0.OpenQty THEN 1 ELSE 0 END AS OrderRow
				FROM picker_sodetail T0
					LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
				WHERE T0.DocEntry = '".$_POST['SODocEntry']."' AND T0.DocType = '".$_POST['DocType']."' AND (T0.ItemCode = '".$_POST['ItemCode']."' OR T1.BarCode = '".$_POST['ItemCode']."'  OR T1.BarCode2 = '".$_POST['ItemCode']."' OR T1.BarCode3 = '".$_POST['ItemCode']."')
				ORDER BY OrderRow, T0.OpenQty DESC, T0.Qty DESC";
		//echo $sql;
		$CkRowSql = CHKRowDB($sql);
		if($CkRowSql != 0) {
			$result = MySQLSelect($sql);
			$Update1 = "UPDATE picker_sodetail SET LastRead = 'N' WHERE DocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."'";
			MySQLUpdate($Update1);
			$Update2 = "UPDATE picker_sodetail SET LastRead = 'Y' WHERE ID = ".$result['ID']."";
			MySQLUpdate($Update2);

			$arrCol['ItemName'] = $result['ItemName'];
			$arrCol['OpenQty'] = $result['OpenQty'];
			$arrCol['Qty'] = $result['Qty'];
			$arrCol['CkRow'] = $CkRow;
			$Alert = "N";
			$arrCol['Alert'] = $Alert;
		}else{
			$arrCol['CkRow'] = $CkRow;
			$Alert = "Y";
			$arrCol['Alert'] = $Alert;
		}
	}else{
		if($CkRow == 0) {
			// แจ้งเตือน IT ให้เพิ่มรหัสสินค้า
			// $_POST['ItemCode']
			$arrCol['CkRow'] = $CkRow;
		}else{
			// แจ้งเตือนคนเบิกสินค้าว่า 1 บาร์โค้ดมีหลายรายการ
			$sqlQRY = MySQLSelectX($sqlCk);
			$i = 0;
			while($result2 = mysqli_fetch_array($sqlQRY)) {
				++$i;
				$arrCol['ItemCode'.$i] = $result2['ItemCode'];
				$arrCol['ItemName'.$i] = $result2['ItemName'];
			}
			$sql1 = "SELECT T0.ItemCode FROM picker_sodetail T0 WHERE (T0.ItemCode = '".$_POST['ItemCode']."' OR T0.BarCode = '".$_POST['ItemCode']."') AND T0.DocEntry = ".$_POST['SODocEntry']."";
			// echo $sql1;
			$CkRowSql = CHKRowDB($sql1);
			if($CkRowSql != 0) {
				$MainItem = MySQLSelect($sql1);
				$arrCol['ItemMain'] = $MainItem['ItemCode'];
				$arrCol['CkRow'] = $CkRow;
				$Alert = "N";
				$arrCol['Alert'] = $Alert;
			}else{
				$arrCol['CkRow'] = $CkRow;
				$Alert = "Y";
				$arrCol['Alert'] = $Alert;
			}
		}
	}
}

if($_GET['a'] == 'AddItem') {
	$NotLocation = "N1.LocRack NOT LIKE 'M%'  
					AND N1.LocRack NOT LIKE 'B1-P%' 
					AND N1.LocRack NOT LIKE '%Picking'  
					AND (N1.LocRack NOT LIKE 'C________03%' AND N1.LocRack NOT LIKE 'C________04%' AND N1.LocRack NOT LIKE 'C________05%')";
	$sql ="SELECT ID,DocType,ItemCode,WhsCode,Qty,OpenQty,
                 CASE WHEN Qty = OpenQty THEN 1 ELSE 0 END AS OrderBy
			FROM picker_sodetail 
			WHERE (BarCode = '".$_POST['TxtCodeBars']."' OR ItemCode = '".$_POST['TxtCodeBars']."') AND DocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."' AND Status != 0   
			ORDER BY OrderBy,VisOrder,Qty DESC ,OpenQty DESC
			LIMIT 1";
	$CHKRow = CHKRowDB($sql);


	if($CHKRow != 0) {
		$result = MySQLSelect($sql);
		$txtMAX = 0;
		//WAI
		switch ($result['WhsCode']){
			case 'KB4' :
			case 'KSY' :
			// case 'PM-KSY' :
				$ReciveRack = 0;
				break;
			default :
				$ReciveRack = 1;
				break;
		} 
		if($result['Qty'] == 0) {
			$st = 0;
		}else{
			//WAI
			if($result['DocType'] == 'OWAS' OR ($result['ItemCode'] == '00-000-010' OR $result['ItemCode'] == '00-000-001') OR ($ReciveRack == 1)) {
				$newOpen = $result['OpenQty'] + $_POST['TxtQty'];
				if($newOpen > $result['Qty']) {
					$st = 4;
				}else{
					if (strtoupper($_POST['RacKBar']) == 'RECIVE'){
						$Update1 = "UPDATE picker_sodetail SET OpenQty = '".$newOpen."' WHERE DocEntry = '".$_POST['SODocEntry']."' AND (BarCode = '".$_POST['TxtCodeBars']."' OR ItemCode = '".$_POST['TxtCodeBars']."') AND ID = ".$result['ID']."";
						MySQLUpdate($Update1);

						$Insert1 = "INSERT INTO transecdata 
									SET WhsCode = '".$result['WhsCode']."', LocationRack = 'RECIVE', ItemCode = '".$result['ItemCode']."', QtyOut = ".intval($_POST['TxtQty']).", DateCreate=NOW(), 
									ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, trnCode = '".$_POST['SODocEntry']."', AppTran = ''";
						MySQLInsert($Insert1);

						$sql1 ="SELECT T1.StatusDoc, SUM(T0.OpenQty) AS OpenQty 
								FROM picker_sodetail T0 JOIN picker_soheader T1 ON T0.DocEntry = T1.SODocEntry AND T0.DocType = T1.DocType 
								WHERE T0.DocEntry = '".$_POST['SODocEntry']."' AND T0.DocType = '".$_POST['DocType']."'";
						$result1 = MySQLSelect($sql1);
						if ($result1['OpenQty'] != 0 AND $result1['StatusDoc'] == 2){
							$Update2 = "UPDATE picker_soheader SET StatusDoc = 3, StartPick = NOW() WHERE SODocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."'";
							MySQLUpdate($Update2);
						}
						$st = 2;
					}else{
						$st = 5;
					}
				}
			}else{
				if($result['Qty'] <= $result['OpenQty']) {
					$st = 1;
				}else{
					// $result['WhsCode'] == 'PM-KSY'
					if ($result['WhsCode'] == 'KSY' OR $result['WhsCode'] == 'TT-C' OR $result['WhsCode'] == 'MT' OR $result['WhsCode'] == 'MT2' OR $result['WhsCode'] == 'OUL' OR $result['WhsCode'] == 'KB4'){
						$WhsOpen = 'Y';
					}else{
						$WhsOpen = 'N';
					}
					if (strtoupper($_POST['RacKBar']) == 'RECIVE' AND $WhsOpen == 'N' ){
						$newOpen = $result['OpenQty'] + $_POST['TxtQty'];
						$Update1 = "UPDATE picker_sodetail SET OpenQty = '".$newOpen."' WHERE DocEntry = '".$_POST['SODocEntry']."' AND (BarCode = '".$_POST['TxtCodeBars']."' OR ItemCode = '".$_POST['TxtCodeBars']."') AND ID = ".$result['ID']."";
						MySQLUpdate($Update1);

						$Insert1 = "INSERT INTO transecdata 
									SET WhsCode = '".$result['WhsCode']."', LocationRack = 'RECIVE', ItemCode = '".$result['ItemCode']."', QtyOut = ".intval($_POST['TxtQty']).", DateCreate=NOW(), 
										ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, trnCode = '".$_POST['SODocEntry']."', AppTran = ''";
						MySQLInsert($Insert1);

						$sql1 ="SELECT T1.StatusDoc, SUM(T0.OpenQty) AS OpenQty 
								FROM picker_sodetail T0 JOIN picker_soheader T1 ON T0.DocEntry = T1.SODocEntry AND T0.DocType = T1.DocType 
								WHERE T0.DocEntry = '".$_POST['SODocEntry']."' AND T0.DocType = '".$_POST['DocType']."'";
						$result1 = MySQLSelect($sql1);
						if ($result1['OpenQty'] != 0 AND $result1['StatusDoc'] == 2){
							$Update2 = "UPDATE picker_soheader SET StatusDoc = 3, StartPick = NOW() WHERE SODocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."'";
							MySQLUpdate($Update2);
						}
						$st = 2;
					}else{
						$ReadSQL = "SELECT T0.ID,T0.VisOrder,T0.Qty, T0.OpenQty,T0.ItemCode,T0.WhsCode,T0.WaitOP,T0.BomItem,
										IFNULL(
											CASE WHEN T0.WhsCode IN ('KSY') THEN 
											          (SELECT N1.LocRack FROM oitw N1 
													   WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND ".$NotLocation." ORDER BY N1.OnHand ASC LIMIT 1)
												WHEN T0.WhsCode = 'KB4' THEN 
												       (SELECT N1.LocRack FROM oitw N1
														WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND ".$NotLocation." AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand ASC LIMIT 1)  
											ELSE 'RECIVE' END,
											CASE WHEN T0.WhsCode IN ('KSY') THEN 
											           (SELECT N1.LocRack FROM oitw N1
														WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand <= T0.Qty AND ".$NotLocation." ORDER BY N1.OnHand ASC LIMIT 1)
												WHEN T0.WhsCode = 'KB4' THEN 
														(SELECT N1.LocRack FROM oitw N1
														WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand <= T0.Qty AND ".$NotLocation." AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand DESC LIMIT 1)  
											ELSE 'RECIVE' END
										) AS DftLocation, 
										IFNULL(
											CASE WHEN T0.WhsCode IN ('KSY') THEN 
														(SELECT N1.OnHand FROM oitw N1
														WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND ".$NotLocation." AND N1.WhsCode IN ('KSY') ORDER BY N1.OnHand ASC LIMIT 1) 
													ELSE (SELECT N1.OnHand FROM oitw N1
														WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand >= T0.Qty AND ".$NotLocation." AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand ASC LIMIT 1)
											END,
											CASE WHEN T0.WhsCode IN ('KSY') THEN 
														(SELECT N1.OnHand FROM oitw N1
														WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand <= T0.Qty AND ".$NotLocation." AND N1.WhsCode IN ('KSY') ORDER BY N1.OnHand ASC LIMIT 1) 
													ELSE (SELECT N1.OnHand FROM oitw N1
														WHERE N1.ItemCode = T0.ItemCode AND N1.OnHand <= T0.Qty AND ".$NotLocation." AND N1.WhsCode = T0.WhsCode ORDER BY N1.OnHand DESC LIMIT 1)
											END
										) AS OnHand
									FROM picker_sodetail T0 
										LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
									WHERE T0.DocEntry = '".$_POST['SODocEntry']."' AND (T0.BarCode = '".$_POST['TxtCodeBars']."' OR T0.ItemCode = '".$_POST['TxtCodeBars']."') AND T0.Status != 0 AND T0.Qty != T0.OpenQty ORDER BY T0.VisOrder LIMIT 1";
						// echo $ReadSQL;
						$OpenData = MySQLSelect($ReadSQL);
						$DetailData['OnShelf'] = $OpenData['OnHand'];
						$DetailData['Location'] = $OpenData['DftLocation'];
						// $OpenData['PM-KSY']
						if ($OpenData['WhsCode'] == 'KSY' OR $OpenData['WhsCode'] == 'MT' OR $OpenData['WhsCode'] == 'MT2' OR $OpenData['WhsCode'] == 'OUL' OR $OpenData['WhsCode'] == 'TT-C'){
							$NotLocation2 =  str_replace("N1","T0",$NotLocation);
							$sql2 = "SELECT T0.LocRack,T0.OnHand,
											CASE WHEN T0.OnHand = ".$OpenData['Qty']." THEN 0
												WHEN T0.OnHand > ".$OpenData['Qty']."  THEN 1
											ELSE 2 END AS OrderBy  
									FROM oitw T0 
									WHERE ItemCode = '".$OpenData['ItemCode']."' AND (T0.WhsCode IN ('KSY') ) AND ".$NotLocation2." AND T0.OnHand > 0
									ORDER BY T0.OnHand";
							// echo $sql2;
							$sql2QRY = MySQLSelectX($sql2);
							$cx = 0;
							$A0 = 0;
							$A1 = 0;
							$A2 = 0;
							$OnHand[0] = 0;
							$LocRack[0] = 0;
							$OnHand[1] = 0;
							$LocRack[1] = 0;
							$OnHand[2] = 0;
							$LocRack[2] = 0;
							while($DetaRack = mysqli_fetch_array($sql2QRY)) {
								if ($DetaRack['OrderBy'] == 0){
									$OnHand[0] = $DetaRack['OnHand'];
									$LocRack[0] = $DetaRack['LocRack'];
									$A0++;
								}
								if ($DetaRack['OrderBy'] == 1){
									if ($A1 == 0){
										$OnHand[1] = $DetaRack['OnHand'];
										$LocRack[1] = $DetaRack['LocRack'];
									}
									$A1++;
								}
								if ($DetaRack['OrderBy'] == 2){
									$OnHand[2] = $DetaRack['OnHand'];
									$LocRack[2] = $DetaRack['LocRack'];
									$A2++;
								}
								$cx++;
							}
							if ($cx > 0){
								if ($A0 > 0){
									//ใส่ค่าใหม่
									$DetailData['OnShelf'] = $OnHand[0];
									$DetailData['Location'] = $LocRack[0];
								}else{
									if ($A1 > 0){
										//ใส่ค่าใหม่
										$DetailData['OnShelf'] = $OnHand[1];
										$DetailData['Location'] = $LocRack[1]; 
									}else{
										//echo $LocRack[2];
										$DetailData['OnShelf'] = $OnHand[2];
										$DetailData['Location'] = $LocRack[2];
									}
								}
							}
						}

						$newOpen = $OpenData['OpenQty'] + intval($_POST['TxtQty']);
						if ($OpenData['WaitOP'] == 0 OR $_POST['RacKBar'] == $DetailData['Location'] ){
							#ตั้งกำหนด Location สินค้าขายปกติมีในระบบ
							if ($DetailData['Location'] != "" AND $DetailData['OnShelf'] >= intval($_POST['TxtQty']) AND $DetailData['Location'] == $_POST['RacKBar'] AND $OpenData['Qty'] > $OpenData['OpenQty']){
								if ($newOpen > $OpenData['Qty']){
									$st = 4;
								}else{
									$sqlOnSAp = "SELECT SUM(OnHand) AS OnHand FROM OITW WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."'";
									$sapfqry1 = SAPSelect($sqlOnSAp);
									$OnSAP = odbc_fetch_array($sapfqry1);
									// echo $OnSAP['OnHand'];
									$sql3 ="SELECT SUM(T0.OpenQty) AS CoMit 
											FROM picker_sodetail T0 JOIN picker_soheader T1 ON T0.DocEntry = T1.SODocEntry AND T1.DocType = T0.DocType 
											WHERE  T0.ItemCode = '".$OpenData['ItemCode']."' AND T0.WhsCode = '".$OpenData['WhsCode']."' AND T1.StatusDoc BETWEEN 1 AND 7" ;
									$Deta3 = MySQLSelect($sql3);	
									$OnSAP['OnHand'] = $OnSAP['OnHand'] - $Deta3['CoMit'];
									$sqlBom = "SELECT IsBom FROM oitm WHERE ItemCode = '".$OpenData['ItemCode']."'";
									$OpenBom = MySQLSelect($sqlBom);
									if ($OpenBom['IsBom'] == 1){
										$wai = 0; 
									}else{
										$wai = 1;
									}
									if ($OnSAP['OnHand'] <  intval($_POST['TxtQty']) AND $wai == 1){
										$sqlgetKSM = "SELECT SUM(OnHand) AS OnHand FROM OITW WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode = 'KSM'";
										$getKSM = SAPSelect($sqlgetKSM);
										$DataKSM = odbc_fetch_array($getKSM);
										if ($DataKSM['OnHand'] == 0){
											$txtKSM = "สินค้าในระบบไม่พอเบิก ";
										}else{
											$txtKSM = "สินค้าในระบบไม่พอเบิก มีของที่ KSM ".number_format($DataKSM['OnHand'])." ชิ้น";
										}
										$st= 8; 
									}else{ 
										// กำหนด โค้วต้า
										$NewOnHand = $DetailData['OnShelf'] - intval($_POST['TxtQty']);
										if ($OpenData['WhsCode'] == 'TT-C'){
											$RackPICK = "TTC-Picking";
										}else{
											$RackPICK = $OpenData['WhsCode']."-Picking";
										}
										// $OpenData['WhsCode'] == 'PM-KSY'
										if (($OpenData['WhsCode'] == 'KSY' || $OpenData['WhsCode'] == 'KB4') && $wai == 1 ) {
											$sql9 ="SELECT T1.ItemCode, T1.BarCode, T0.CH, SUM(T0.OnHand) AS OnHand 
													FROM whsquota T0
													LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode 
													WHERE (T1.ItemCode = '".$_POST['TxtCodeBars']."' OR T1.BarCode = '".$_POST['TxtCodeBars']."') AND T0.CH = '".$_POST['CHEntry']."'";
											$ShowRes = MySQLSelect($sql9);	
											if($ShowRes['OnHand'] >= intval($_POST['TxtQty'])) {
												$Run = 1;
												// ลดคลังจอง
												$newRes = $ShowRes['OnHand'] - intval($_POST['TxtQty']);
												$Insert_trn =  "INSERT INTO whsquota_trn 
																SET trnType = '',
																	WhsTarget = 'PIC',
																	WhsSource = '".$_POST['CHEntry']."',
																	trnDate = NOW(),
																	ItemCode = '".$ShowRes['ItemCode']."',
																	QtyOut = '".intval($_POST['TxtQty'])."',
																	StatusDoc = 1,
																	DocNum = '".$_POST['DocNumX']."'";
												MySQLInsert($Insert_trn);

												$Update_whsquota = "UPDATE whsquota SET OnHand = ".$newRes.", LastUpdate = NOW(), LastUkey = '".$_SESSION['ukey']."' WHERE ItemCode = '".$ShowRes['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."' AND CH = '".$_POST['CHEntry']."'";
												MySQLUpdate($Update_whsquota);
											}else{
												$sql5 = "SELECT ItemCode FROM  picker_sodetail WHERE DocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."' AND (ItemCode = '".$_POST['TxtCodeBars']."' OR BarCode = '".$_POST['TxtCodeBars']."') LIMIT 1";
												$ShowItem = MySQLSelect($sql5);
												$sql6 = "SELECT SUM(OnHand) AS OnHand FROM whsquota WHERE ItemCode = '".$ShowItem['ItemCode']."'";
												$ShowAllRes = MySQLSelect($sql6);
												// 'PM-KSY'
												$sql7 = "SELECT SUM(OnHand) AS OnHand FROM OITW WHERE ItemCode = '".$ShowItem['ItemCode']."' AND WhsCode IN ('KSY','KSM','KB4')";
												$sapfqry7 = SAPSelect($sql7);
												$CHCenter = odbc_fetch_array($sapfqry7);
												
												// Aum Edit df( $TotalPick = ($CHCenter['OnHand'] - $ShowAllRes['OnHand'])+$ShowRes['OnHand']; )
												if($CHCenter['OnHand'] >= $ShowAllRes['OnHand']) {
													$TotalPick = ($CHCenter['OnHand'] - $ShowAllRes['OnHand'])+$ShowRes['OnHand'];
												}else{
													$TotalPick = ($ShowAllRes['OnHand'] - $CHCenter['OnHand'])+$ShowRes['OnHand'];
												}

												if($TotalPick >= intval($_POST['TxtQty'])) {
													$Run = 1;
													// ลดคลังจอง
													$newRes = 0;
													if ($ShowRes['OnHand'] > 0){
														$InTrn = "INSERT INTO whsquota_trn 
																SET trnType = '',
																	WhsTarget = 'PIC',
																	WhsSource = '".$_POST['CHEntry']."',
																	trnDate = NOW(),
																	ItemCode = '".$ShowRes['ItemCode']."',
																	QtyOut = '".$ShowRes['OnHand']."',
																	StatusDoc = 1,
																	DocNum = '".$_POST['DocNumX']."'";
														MySQLInsert($InTrn);

														$UpdateWhs = "UPDATE whsquota SET OnHand = ".$newRes.", LastUpdate = NOW(), LastUkey = '".$_SESSION['ukey']."' WHERE ItemCode = '".$ShowRes['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."' AND CH = '".$_POST['CHEntry']."'";
														MySQLUpdate($UpdateWhs);
													}
												}else{
													$Run = 0;
													$sql5 = "SELECT ItemCode FROM  picker_sodetail WHERE DocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."' AND (ItemCode = '".$_POST['TxtCodeBars']."' OR BarCode = '".$_POST['TxtCodeBars']."') LIMIT 1";
													$ShowItem = MySQLSelect($sql5);
													$sql6 = "SELECT  CH, SUM(OnHand) AS OnHand FROM whsquota WHERE ItemCode = '".$ShowItem['ItemCode']."' AND OnHand > 0 GROUP BY CH";
													$sql6QRY = MySQLSelectX($sql6);
													if ($TotalPick <= 0) {
														$MaxPic = " แจ้งตัดหมด ";
													}else{
														$MaxPic = " เบิก ".$TotalPick." ชิ้น แล้วตัดหมด ";
													}
													$ac=0;
													$ListCH = "";
													while ($ShowCH = mysqli_fetch_array($sql6QRY)){
														if ($ac == 0){
															$MaxPic .= "ให้ขอโอนย้ายจาก \n";
															$ac++;
															$ListCH .= $ShowCH['CH']."-[".number_format($ShowCH['OnHand'],0)."] ตัว,";
														}
														$MaxPic .= " คลัง ".$ShowCH['CH']." : มีของ ".number_format($ShowCH['OnHand'],0)." ชิ้น \n";
													}
												}
											}
										}else{
											$Run = 1;
										}

										if($Run == 1) {
											//เพิ่มจำนวนสินค้า
											$Update = "UPDATE picker_sodetail SET OpenQty = '".$newOpen."' WHERE DocEntry = '".$_POST['SODocEntry']."' AND (BarCode = '".$_POST['TxtCodeBars']."' OR ItemCode = '".$_POST['TxtCodeBars']."') AND ID = ".$OpenData['ID']."";
											MySQLUpdate($Update);

											$sql3 ="SELECT T1.StatusDoc, SUM(T0.OpenQty) AS OpenQty 
													FROM picker_sodetail T0 JOIN picker_soheader T1 ON T0.DocEntry = T1.SODocEntry AND T0.DocType = T1.DocType 
													WHERE T0.DocEntry = '".$_POST['SODocEntry']."' AND T0.DocType = '".$_POST['DocType']."'";
											$OpenDataST = MySQLSelect($sql3);
											if ($OpenDataST['OpenQty'] != 0 AND $OpenDataST['StatusDoc'] == 2){
												$Update = "UPDATE picker_soheader SET StatusDoc = 3, StartPick = NOW() WHERE SODocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."'";
												MySQLUpdate($Update);
											}
											//ลดจำนวนOnHand
											if($OpenData['WhsCode'] == "KSY" || $OpenData['WhsCode'] == "MT" || $OpenData['WhsCode'] == "MT2" || $OpenData['WhsCode'] == "OUL" || $OpenData['WhsCode'] == "TT-C") {
												MySQLUpdate("UPDATE oitw SET OnHand = ".$NewOnHand." WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode IN ('KSY') AND LocRack = '".$_POST['RacKBar']."'");
											} else {
												MySQLUpdate("UPDATE oitw SET OnHand = ".$NewOnHand." WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode =  '".$OpenData['WhsCode']."' AND LocRack = '".$_POST['RacKBar']."'");
											}
											// MySQLUpdate("UPDATE oitw SET OnHand = ".$NewOnHand." WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode =  '".$OpenData['WhsCode']."' AND LocRack = '".$_POST['RacKBar']."'");
											
											//เพิ่มจำนวน Zone PICK
											$chkRowOITW  = CHKRowDB("SELECT * FROM oitw WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."' AND LocRack = '".$RackPICK."'");
											if ($chkRowOITW != 0){
												$OnPick = MySQLSelect("SELECT OnHand FROM oitw WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."' AND LocRack = '".$RackPICK."'");
												$newOnPick = $OnPick['OnHand'] + intval($_POST['TxtQty']);
												MySQLUpdate("UPDATE oitw SET OnHand = ".$newOnPick." WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."' AND LocRack = '".$RackPICK."'");
											}else{
												MySQLInsert("INSERT INTO oitw SET ItemCode = '".$OpenData['ItemCode']."', WhsCode = '".$OpenData['WhsCode']."', LocRack= '".$RackPICK."', OnHand = ".intval($_POST['TxtQty'])."");
											}
											//เพิ่ม transecdata
											MySQLInsert("INSERT INTO transecdata SET WhsCode = '".$OpenData['WhsCode']."', LocationRack = '".$_POST['RacKBar']."', ItemCode = '".$OpenData['ItemCode']."', QtyOut = ".intval($_POST['TxtQty']).", DateCreate=NOW(), ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, trnCode = '".$_POST['SODocEntry']."' , AppTran = ''");
											MySQLInsert("INSERT INTO transecdata SET WhsCode = '".$OpenData['WhsCode']."', LocationRack = '".$RackPICK."', ItemCode = '".$OpenData['ItemCode']."', QtyIN = ".intval($_POST['TxtQty']).", DateCreate=NOW(), ukeyUpdate = '".$_SESSION['ukey']."' ,StatusTran = 1, trnCode = '".$_POST['SODocEntry']."' , AppTran = ''");
											$st = 2;
										}else{
											$st = 9;
											$sql8 = "Remark = 'KSY/KSM เป็น สินค้าติดจองของทีม (".$ListCH.")'";
											MySQLUpdate("UPDATE picker_sodetail SET ".$sql8." WHERE DocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."' AND (ItemCode = '".$_POST['TxtCodeBars']."' OR BarCode = '".$_POST['TxtCodeBars']."')");
										}
									}
								}
							}else{
								if ($DetailData['Location'] == ""){
									$st = 7;
								}else{
									if ($DetailData['Location'] != $_POST['RacKBar']){
										if ($_SESSION['UserName'] == 'waiwai'){
											echo $DetailData['Location']."/".$_POST['RacKBar'];
										}
										$st = 5;
									}else{
										if ($DetailData['OnShelf'] <= intval($_POST['TxtQty'])){
											$st = 3;
										}else{
											if  ($OpenData['Qty'] == $OpenData['OpenQty']){
												$st = 1;
											}
										}
									}
								}
							}
						}else{
							// เคสสินค้ารอต่างๆ
							if ($OpenData['WhsCode'] == 'TT-C'){
								$RackPICK = "TTC-Picking";
							}else{
								$RackPICK = $OpenData['WhsCode']."-Picking";
							}
							if (intval($_POST['TxtQty']) == $OpenData['Qty']){
								$SETupdate = "OpenQty = '".$newOpen."',WaitOP = 0,Remark = '".$OpenData['WaitOP']."-ได้รับสินค้าเรียบร้อย'";
							}else{
								$SETupdate = "OpenQty = '".$newOpen."'";
							}
							MySQLUpdate("UPDATE picker_sodetail SET ".$SETupdate." WHERE DocEntry = '".$_POST['SODocEntry']."' AND (BarCode = '".$_POST['TxtCodeBars']."' OR ItemCode = '".$_POST['TxtCodeBars']."') AND ID = ".$OpenData['ID']."");
							$sql3 ="SELECT T1.StatusDoc, SUM(T0.OpenQty) AS OpenQty 
									FROM picker_sodetail T0 JOIN picker_soheader T1 ON T0.DocEntry = T1.SODocEntry AND T0.DocType = T1.DocType 
									WHERE T0.DocEntry = '".$_POST['SODocEntry']."' AND T0.DocType = '".$_POST['DocType']."'";
							$OpenDataST = MySQLSelect($sql3);

							if ($OpenDataST['OpenQty'] != 0 AND $OpenDataST['StatusDoc'] == 2){
								MySQLUpdate("UPDATE picker_soheader SET StatusDoc = 3, StartPick = NOW() WHERE SODocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."'");
							}
							$chkRowOITW = CHKRowDB("SELECT * FROM oitw WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."' AND LocRack = '".$RackPICK."'");
							if($chkRowOITW != 0) {
								$OnPick = MySQLSelect("SELECT OnHand FROM oitw WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."' AND LocRack = '".$RackPICK."'");
								$newOnPick = $OnPick['OnHand'] + intval($_POST['TxtQty']);
								MySQLUpdate("UPDATE oitw SET OnHand = ".$newOnPick." WHERE ItemCode = '".$OpenData['ItemCode']."' AND WhsCode = '".$OpenData['WhsCode']."' AND LocRack = '".$RackPICK."'");
							}else{
								MySQLInsert("INSERT INTO oitw SET ItemCode = '".$OpenData['ItemCode']."', WhsCode = '".$OpenData['WhsCode']."', LocRack= '".$RackPICK."', OnHand = ".intval($_POST['TxtQty'])."");
							}
							MySQLInsert("INSERT INTO transecdata SET WhsCode = '".$OpenData['WhsCode']."', LocationRack = '".$RackPICK."', ItemCode = '".$OpenData['ItemCode']."', QtyIN = ".intval($_POST['TxtQty']).", DateCreate=NOW(), ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, trnCode = '".$_POST['SODocEntry']."', AppTran = ''");
							$st = 2;
						}
					}
				}
			}
		}
		$sqlCHK =  "SELECT SUM(P0.RowItem) AS RowItem, SUM(P0.FinishItem) AS FinishItem
					FROM (
						SELECT 1 AS RowItem,
							CASE WHEN T0.Qty = T0.OpenQty THEN 1 ELSE 0 END AS FinishItem
						FROM  picker_sodetail T0
						WHERE T0.DocEntry = '".$_POST['SODocEntry']."' AND T0.DocType = '".$_POST['DocType']."'
					) P0";
		$FinishData = MySQLSelect($sqlCHK);
		$arrCol['qtyShow'] = $FinishData['FinishItem']."/".$FinishData['RowItem'];

		switch ($st){
			case 0 : $txtST = "ไม่พบสินค้าที่ต้องการค้นหา"; break;
			case 1 : $txtST = "สินค้าครบจำนวนแล้ว"; break;
			case 2 : $txtST = "เบิกสินค้าปกติ"; break;
			case 3 : $txtST = "สินค้า Location ไม่พอ ให้เติมสินค้าก่อน"; break;
			case 4 : $txtST = "สินค้าเกินจำนวนที่ระบุ"; break;
			case 5 : $txtST = "ระบุชั้นวางไม่ถูกต้อง"; break;
			case 6 : $txtST = "ไม่พบชั้นวางนี้ในคลังสินค้าที่ระบุ"; break;
			case 7 :
				$txtST = "ใบสั่งขาย = ".$_POST['SODocEntry']."\n";
				$txtST .= "ItemCode = ".$OpenData['ItemCode']."\n";
				$txtST .= "แจ้ง ฝ่าย IT ดำเนินการต่อ";
				break;
			case 8 : $txtST = $txtKSM; break;
			case 9 : $txtST = $MaxPic; break;
		}
		
		if(isset($newOpen)) {
			$arrCol['NewQty'] = number_format($newOpen);
		}
		// $arrCol['newOpen'] = $newOpen;
		$arrCol['Status'] = $st;
		$arrCol['txtST'] = $txtST;
		$arrCol['txtMAX'] = $txtMAX;
		$arrCol['VisOrder'] = $result['ID'];

		$arrCol['CHKRow'] = $CHKRow;
	}else{
		$arrCol['CHKRow'] = $CHKRow;
	}
}

if($_GET['a'] == 'CutAmount') {
	$Update = "UPDATE picker_soheader 
			   SET StatusDoc = 4, LastUpdate = NOW(), LastUkey = '".$_SESSION['ukey']."', UkeyCUT1 = '".$_SESSION['ukey']."', TimeCUT1 = NOW() 
			   WHERE SODocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."'";
	MySQLUpdate($Update);

	/*---*/
	$sql1 = "SELECT SODocEntry,DocNum,CardCode,CardName,TeamCode FROM picker_soheader WHERE SODocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."'";
	$TeamCode = MySQLSelect($sql1);
	if ($TeamCode['TeamCode'] == 'MT1'){
		$sms1 = "\nแจ้งตัดใบสั่งขาย เลขที่ : `".$TeamCode['DocNum']."`\n";
		$sms1 .= "ร้านค้า `".$TeamCode['CardCode']."-".$TeamCode['CardName']."`\n";
		LineNoti('SOMT1',$sms1);
	}
	
}

if($_GET['a'] == 'updateSO') {
	$sql = "SELECT T1.DocEntry,T2.Beginstr,T1.DocNum,T3.LastName,T3.FirstName,T1.CardCode,T4.U_Dim1,T1.CardName,T1.Comments,
					T0.VisOrder,T0.ItemCode,T0.CodeBars,T0.Quantity,T0.WhsCode,T0.Dscription AS ItemName,
					(SELECT SUM(P0.OnHand) 
					FROM OITW P0
					WHERE P0.WhsCode = T0.WhsCode AND P0.ItemCode = T0.ItemCode
					) AS OnHand
			FROM RDR1 T0
					LEFT JOIN ORDR T1 ON T0.DocEntry = T1.DocEntry
					LEFT JOIN NNM1 T2 ON T1.Series = T2.Series
					LEFT JOIN OHEM T3 ON T1.OwnerCode = T3.empID
					LEFT JOIN OSLP T4 ON T4.SlpCode = T1.SlpCode
			WHERE T1.DocEntry = '".$_POST['SODocEntry']."'
			ORDER BY T0.VisOrder";
	$sqlQRY = SAPSelect($sql);
	while($result = odbc_fetch_array($sqlQRY)) {
		$ChkRow = CHKRowDB("SELECT * FROM picker_sodetail WHERE DocEntry = '".$_POST['SODocEntry']."' AND ItemCode LIKE '".$result['ItemCode']."%'");
		if($ChkRow == 0) {
			$Insert = "INSERT INTO picker_sodetail 
					   SET DocEntry ='".$_POST['SODocEntry']."', DocType='ORDR', VisOrder = ".$result['VisOrder'].", ItemCode ='".$result['ItemCode']."', 
					   	   ItemName = '".conutf8($result['ItemName'])."', WhsCode = '".$result['WhsCode']."', Qty = ".$result['Quantity'].", OpenQty = 0";
			MySQLInsert($Insert);
		}else{
			$ChkRow2 = CHKRowDB("SELECT * FROM picker_sodetail WHERE DocEntry = '".$_POST['SODocEntry']."' AND ItemCode LIKE '".$result['ItemCode']."%' AND OpenQty = 0");
			if($ChkRow2 != 0) {
				$Update = "UPDATE picker_sodetail 
						   SET WhsCode = '".$result['WhsCode']."', Qty='".$result['Quantity']."', ItemName = '".conutf8($result['ItemName'])."' 
						   WHERE DocEntry = '".$_POST['SODocEntry']."' AND DocType = 'ORDR' AND ItemCode = '".$result['ItemCode']."' AND VisOrder = '".$result['VisOrder']."'";
						//    echo $Update;
				MySQLUpdate($Update);		   
			}
		}
	}
	$Text = "ปรับปรุงคลังเรียบร้อยแล้ว";
	$arrCol['Text'] = $Text;
}

if($_GET['a'] == 'SaveSO') {
	$Update = "UPDATE picker_soheader 
		  	   SET LastUpdate = NOW(), LastUkey = '".$_SESSION['ukey']."', PickedDate = NOW(), StatusDoc = 7 
			   WHERE SODocEntry = '".$_POST['SODocEntry']."' AND DocType = '".$_POST['DocType']."'"; 
	MySQLUpdate($Update);
	$arrCol['DocNumX'] = $_POST['DocNumX'];
}

if($_GET['a'] == "ShowOnHand") {
	$ItemCode = $_POST['ItemCode'];
	$WhsCode  = $_POST['WhsCode'];
	$Quantity = $_POST['Qty'];
	$BomItem  = $_POST['Bom'];

	if($WhsCode != "RC") {
		switch($WhsCode) {
			case "KSY":
			case "KSM":
				$WhsSQL = "('KSY','KSM')";
			break;
			default:
				$WhsSQL = "('$WhsCode')";
			break;
		}

		/* GET DATA FROM SAP */
		$SQL1 = "SELECT SUM(T0.OnHand) AS 'OnHand' FROM OITW T0 WHERE T0.ItemCode = '$ItemCode' AND T0.WhsCode IN $WhsSQL";
		$QRY1 = SAPSelect($SQL1);
		$RST1 = odbc_fetch_array($QRY1);

		$OnHand = $RST1['OnHand'];

		/* GET DATA FROM EUROX FORCE */
		$SQL2 =
			"SELECT SUM(T0.OpenQty) AS 'Committed'
			FROM picker_sodetail T0
			LEFT JOIN picker_soheader T1 ON T0.DocEntry = T1.SODocEntry AND T0.DocType = T1.DocType
			WHERE T0.ItemCode = '$ItemCode' AND T0.WhsCode IN $WhsSQL AND T1.StatusDoc BETWEEN 1 AND 7";
		$RST2 = MySQLSelect($SQL2);

		$OnHand = $OnHand - $RST2['Committed'];

		if($OnHand < $Quantity && $BomItem == 0) {
			$output = "<span class='fw-bold text-primary'>".number_format($OnHand,0)."</span>";
		} else {
			$output = "<span class='fw-bold text-dark'>".number_format($OnHand,0)."</span>";
		}
	} else {
		$output = "<span class='fw-bold text-dark'>".number_format(0,0)."</span>";
	}
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>