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

if($_GET['a'] == 'CallData') {
	$sql = "SELECT DISTINCT T0.LogiNum, T1.BillEntry, T1.BillType, T2.DocNum, T3.CardName, T1.NewLoad, 
				COUNT(IF(T1.Status=2,1,NULL)) AS OnLoad,
				COUNT(T1.BoxCode) AS TotalBox,
				CASE WHEN T1.OnLoad = 'Y' THEN 0 ELSE 1 END AS LineRun
			FROM logi_head T0
			LEFT JOIN logi_detail T1 ON T0.LogiNum = T1.LogiNum
			LEFT JOIN pack_header T2 ON T1.BillEntry = T2.BillEntry AND T1.BillType = T2.BillType
			LEFT JOIN ocrd T3 ON T2.CardCode = T3.CardCode
			WHERE T0.LogiNum = '".$_POST['DocID']."' AND (T1.BillType IN ('OWAB','OWAS','OINV','ODLN'))
			GROUP BY T0.LogiNum, T1.BillEntry, T1.BillType, T2.DocNum, T3.CardName
			ORDER BY LineRun,T1.ID";
	$getDetail = MySQLSelectX($sql);
	$cx=0;
	while($DataDetail = mysqli_fetch_array($getDetail)) {
		$cx++;
		$DocEntry[$cx] = $DataDetail['BillEntry'];
        $BillType[$DocEntry[$cx]] = $DataDetail['BillType'];
        $OnLoad[$DocEntry[$cx]] = $DataDetail['OnLoad'];
        $TotalBox[$DocEntry[$cx]] = $DataDetail['TotalBox'];
        $BillNo[$DocEntry[$cx]] = $DataDetail['DocNum'];
        $NewLoad[$DocEntry[$cx]] = $DataDetail['NewLoad'];
        $CardName[$DocEntry[$cx]] = $DataDetail['CardName'];
	}

	$output = "";
	for ($i = 1; $i <= $cx; $i++) {
		if ($OnLoad[$DocEntry[$i]] == $TotalBox[$DocEntry[$i]]){
            $Color = "#e6ffe6" ; 
        }else{
            $Color = "#fff" ; 
        }
        if ($NewLoad[$DocEntry[$i]] == 'Y'){
            $Color = "rgba(255, 128, 128, 0.7)" ;
        }
		$output .= "<tr>".
						"<td class='pb-0' colspan='2'>".
							"<div class='ps-2 pe-2 pt-2 border border-1' style='border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px ".$Color."; background-color: ".$Color.";'>".
								"<div class='d-flex justify-content-aroundent'>".
									"<div class='' style='width: 10%'>".
										"<span class='fw-bolder bg-light' style='border-radius: 50%; padding: 0px 6px 0px 6px'>".$i."</span>".
									"</div>".
									"<div class='' style='width: 61%'>".
										"<span class='fw-bolder'>เลขที่ใบเสร็จ</span>&nbsp;".
										"<a class='fw-bold' data-bs-toggle='collapse' href='#CollaT".$i."' role='button' aria-expanded='false' aria-controls='CollaT".$i."'>".
											$BillNo[$DocEntry[$i]].
										"</a>".
									"</div>".
									"<div class='' style='width: 29%'>".
										"<span class='fw-bold'>".number_format($OnLoad[$DocEntry[$i]])."</span>/".number_format($TotalBox[$DocEntry[$i]])."</span>".
									"</div>".
								"</div>".
							"</div>".
							"<div class='p-2 border border-1' style='border-radius: 0px 0px 10px 10px; box-shadow: 1px 1px ".$Color.";'>".
								"<div class='d-flex justify-content-aroundent'>".
									"<div class='' style='width: 10%'>".
										"<span class='fw-bold'></span>".
									"</div>".
									"<div class='' style='width: 85%'>".
										"<span class='fw-bold'>".$CardName[$DocEntry[$i]]."</span>".
									"</div>".
									"<div class='' style='width: 5%'>".
										"<a href='javascript:void(0);' class='fw-bolder' onclick=\"DelIV('".$BillType[$DocEntry[$i]]."','".$DocEntry[$i]."','".$BillNo[$DocEntry[$i]]."')\"><i class='fas fa-trash text-danger'></i></a>".
									"</div>".
								"</div>".
							"</div>".
							"<div class='collapse ps-3 pe-3' id='CollaT".$i."' style='padding-top: 1px;'>".
								"<table class='table bg-light-secondary mb-0' style='font-size: 13px; border-radius: 0px 0px 10px 10px;'>".
									"<tr class='text-center'>".
										"<td class='fw-blod pt-1 pb-1'>No.</td>".
										"<td class='fw-blod pt-1 pb-1'>รหัสกล่อง</td>".
										"<td class='fw-blod pt-1 pb-1'>สถานะ</td>".
									"</tr>";
						$mySQL ="SELECT T1.LogiNum,T0.BoxCode,T1.Status 
								FROM pack_boxlist T0
										LEFT JOIN logi_detail T1 ON T0.BoxCode = T1.BoxCode
								WHERE T0.BillEntry = '".$DocEntry[$i]."' AND T0.BillType = '".$BillType[$DocEntry[$i]]."'  AND T1.LogiNum = '".$_POST['DocID']."'
								ORDER BY T1.Status DESC,T1.OutTime DESC,T0.BoxCode";
						$getList = MySQLSelectX($mySQL);
						$x=0;
						while($DataList = mysqli_fetch_array($getList)) {
							$x++;
							if ($DataList['LogiNum'] != $_POST['DocID']){
								$textLoad = "<i class='fas fa-times'></i>";
							}else{
								switch ($DataList['Status']){
									case '1': $textLoad = "<i class='fas fa-minus'></i>"; break;
									case '2': $textLoad = "<i class='fas fa-check'></i>"; break;
									default: $textLoad = " "; break;
								}
							}
							$output .= "<tr>".
											"<td class='text-center pt-1 pb-1'>".$x."</td>".
											"<td class='pt-1 pb-1'>".$DataList['BoxCode']."</td>".
											"<td class='text-center pt-1 pb-1'>".$textLoad."</td>".
										"</tr>";
						}
					$output .= "</table>".
							"</div>".
						"</td>".
					"</tr>";
	}
	$sql3 = "SELECT SUM(P0.Total) AS Total,SUM(P0.ThisLoad) AS ThisLoad
			FROM (
				SELECT 1 AS Total, CASE WHEN Status = 2 THEN 1 ELSE 0 END AS ThisLoad
				FROM logi_detail 
				WHERE LogiNum = '".$_POST['DocID']."'
			) P0";
	$DataLoad = MySQLSelect($sql3);
	$arrCol['Load'] = $DataLoad['ThisLoad']."/".$DataLoad['Total'];

	$arrCol['output'] = $output;
} 

if($_GET['a'] == 'EditData') {
	if ($_POST['fun'] == 'Name') {
		MySQLUpdate("UPDATE logi_Head SET DriverName = '".$_POST['DriverName']."' WHERE LogiNum = '".$_POST['LogiNum']."'");
	}else{
		MySQLUpdate("UPDATE logi_Head SET LcCar = '".$_POST['LcCar']."' WHERE LogiNum = '".$_POST['LogiNum']."'");
	}
	// $text = "อัพเดทข้อมูลเรียบร้อย";
	// $arrCol['text'] = $text;
}

if($_GET['a'] == 'CallSubmit') {
	$ar = 0;
	$ac = 0;
	$Arert = "";
	if($_POST['ChkEcom'] == 'true') {
		$sql1 = "SELECT DocEntry FROM OINV WHERE U_PONo = '".$_POST['TxtCodeBars']."'";
		$getPO = SAPSelect($sql1);
		$DataPO = odbc_fetch_array($getPO);
		$sql2 = "SELECT BoxCode FROM pack_boxlist WHERE BillEntry = '".$DataPO['DocEntry']."' AND BillType = 'OINV'";
		$getBoxSP = MySQLSelectX($sql2);
		while($DataShoppee = mysqli_fetch_array($getBoxSP)) {
			$ac++;
			$txtLoop[$ac] = $DataShoppee['BoxCode'];
		}
	}else{
		$ac++;
    	$txtLoop[$ac] = $_POST['TxtCodeBars']; 
	}
	
	// echo $_POST['DocID'];
	for($i = 1; $i <= $ac; $i++) {
		$txtFind = $txtLoop[$i];
		// echo substr($txtFind,0,2);
		if(substr($txtFind,0,2) != "BX") {
			$sqlCkSO = "SELECT DISTINCT T0.LogiNum 
						FROM logi_detail T0 
						LEFT JOIN pack_header T1 ON T0.BillEntry = T1.BillEntry AND T0.BillType = T1.BillType 
						LEFT JOIN picker_soheader T2 ON T1.IDPick = T2.ID
						WHERE T2.DocNum LIKE '%".$txtFind."'";
			$chkSO = CHKRowDB($sqlCkSO);
			if($chkSO != 0) { //พบเลข DocNum
				$Arert = "สินค้ามีเลขใบขนส่งแล้ว";
				$ar = 1;
			}else{
				$sql1 ="SELECT T0.*,T1.CardCode
                        FROM pack_boxlist T0
                        JOIN pack_header T1 ON T0.BillEntry = T1.BillEntry AND T0.BillType = T1.BillType
                        JOIN picker_soheader T2 ON T1.IDPick = T2.ID
                        WHERE (T2.DocNum = '".$txtFind."') AND T1.Status = 'Y' AND T1.Logi = 'N'";
				$getNewBox = MySQLSelectX($sql1);
				$ax = 0;
				while($DataNewBox = mysqli_fetch_array($getNewBox)) {
					if ($ax == 0){
						$data1 = 'Y';
						MySQLUpdate("UPDATE logi_detail SET OnLoad = NULL WHERE LogiNum = '".$_POST['DocID']."'");
                    }else{
                        $data1 = " ";
                    }
					$ax++;

					$addNew = "INSERT INTO logi_detail 
							   SET 	LogiNum = '".$_POST['DocID']."',
									BoxCode = '".$DataNewBox['BoxCode']."',
									CardCode = '".$DataNewBox['CardCode']."',
									BillEntry = '".$DataNewBox['BillEntry']."',
									BillType = '".$DataNewBox['BillType']."',
									BoxNo = '".$DataNewBox['BoxNo']."',
									OnLoad = '".$data1."'";
					MySQLInsert($addNew);

				}
				if($ax > 0) {
					$ar = 0.5;
					$Arert = "เพิ่มรายการสินค้าเรียบร้อย";
				}else{
					$ar = 1;
					$Arert = "ไม่พบข้อมูลที่ระบุ<br/>กรุณาตรวจสอบว่าได้ยืนยันการแพ็กสินค้าเรียบร้อยแล้ว";
				}
			}
		}else{
			$chk = CHKRowDB("SELECT * FROM logi_detail WHERE LogiNum = '".$_POST['DocID']."' AND BoxCode = '".$txtFind."'");
			if($chk != 0) {
				$DataBox = MySQLSelect("SELECT ID, LogiNum, BoxCode, Status, BillEntry, BillType FROM logi_Detail WHERE LogiNum = '".$_POST['DocID']."' AND BoxCode = '".$txtFind."'");
				if($_POST['ChkLoad'] == 'Load') {
					switch($DataBox['Status']) {
						case 2:
							$Arert = "กล่องนี้ถูกโหลดขึ้นรถแล้ว";
                            $ar = 1;
							break;
						case 1:
						case 0:
						case 4:
							$chkOnload = CHKRowDB("SELECT * FROM logi_detail WHERE LogiNum = '".$_POST['DocID']."' AND OnLoad = 'Y'");
							if($chkOnload == 0) {
								MySQLUpdate("UPDATE logi_detail SET OutTime = NOW(), Status = 2, ukeyOut = '".$_SESSION['ukey']."', OnLoad = 'Y' WHERE ID = ".$DataBox['ID']."");
							}else{
								$sql1 ="SELECT T0.BillEntry, T0.BillType, T1.DocNum 
										FROM logi_detail T0 
										JOIN pack_header T1 ON T0.BillEntry = T1.BillEntry AND T0.BillType = T1.BillType  
										WHERE T0.OnLoad = 'Y' AND T0.LogiNum = '".$_POST['DocID']."'";
								$Data1 = MySQLSelect($sql1);
								$sql2 ="SELECT T0.BillEntry,T0.BillType,T1.DocNum 
                                        FROM logi_detail T0
										JOIN pack_header T1 ON T0.BillEntry = T1.BillEntry AND T0.BillType = T1.BillType  
                                        WHERE T0.BoxCode = '".$txtFind."'";
								$Data2 = MySQLSelect($sql2);
								if ($Data1['BillEntry'] == $Data2['BillEntry'] AND $Data1['BillType'] == $Data2['BillType']) {
									MySQLUpdate("UPDATE logi_detail SET OutTime = NOW(), Status = 2, ukeyOut = '".$_SESSION['ukey']."', OnLoad = 'Y' WHERE ID = ".$DataBox['ID']."");

									$chkFullBill = CHKRowDB("SELECT * FROM logi_detail WHERE BillEntry = '".$DataBox['BillEntry']."' AND BillType = '".$DataBox['BillType']."' AND LogiNum = '".$_POST['DocID']."' AND Status IN (0,1,4)");
									if($chkFullBill == 0) {
										MySQLUpdate("UPDATE logi_detail SET OnLoad = NULL WHERE LogiNum = '".$_POST['DocID']."'");
									}
								}else{
									$ar = 1;
                                	$Arert = "โหลดสินค้า บิลเลขที่ ".$Data1['DocNum']." ยังไม่ครบจำนวน";
								}
							}
							break;
						default: 
							$Arert = "กล่องนี้ถูกจัดส่งเรียบร้อยแล้ว"; 
							$ar=1; 
							break;
					}
				}else{
					MySQLUpdate("UPDATE logi_detail SET Status = 1, ukeyOut = '".$_SESSION['ukey']."' WHERE ID = ".$DataBox['ID']."");
				}
			}else{
				$sqlCHK =  "SELECT T0.BoxCode,
                            CASE WHEN (SELECT COUNT(*) FROM logi_detail P0 WHERE P0.BoxCode = T0.BoxCode) > 0 THEN
                                        (SELECT P1.Status FROM logi_detail P1 WHERE P1.BoxCode = T0.BoxCode) ELSE 'N' END AS NewStatus    
                            FROM pack_boxlist T0 
                            WHERE T0.BoxCode = '".$txtFind."' AND T0.Status = 'C'";
				$DataBox = MySQLSelect($sqlCHK);
				switch($DataBox['NewStatus']) {
					case '0': // สินค้าโดนลบจากใบส่งของ
					case '1': // สินค้ายังไม่ได้โหลดขึ้นรถ
						MySQLUpdate("UPDATE logi_detail SET LogiNum = '".$_POST['DocID']."', Status = 2, OutTime = NOW(), ukeyOut = '".$_SESSION['ukey']."', NewLoad = 'Y' WHERE BoxCode = '".$txtFind."'");
						$Arert = "สินค้ากล่องนี้มีการย้ายใบขนส่ง";
                    	$ar = 2;
						break;
					case '4':
						MySQLUpdate("UPDATE logi_detail SET LogiNum = '".$_POST['DocID']."', Status = 2, OutTime = NOW(), ukeyOut = '".$_SESSION['ukey']."', NewLoad = 'Y' WHERE BoxCode = '".$txtFind."'");
						$Arert = "สินค้ากล่องนี้มีการย้ายใบขนส่ง/โหลดใหม่";
						$ar = 2;
						break;
					case 'N':
						$ReadBox = "SELECT T1.CardCode,T0.BillEntry,T0.BillType,T0.BoxNo,T1.DocNum
									FROM pack_boxlist T0
									JOIN pack_header T1 ON T0.BillEntry = T1.BillEntry AND T0.BillType = T1.BillType
									WHERE T0.BoxCode = '".$txtFind."'";
						$BoxData = MySQLSelect($ReadBox);
						switch($BoxData['BillType']) {
							case 'OINV' :
							case 'ODLN' :
								$sqlSAP =  "SELECT (ISNULL(T1.[BeginStr],'IV-')+CAST(T0.[DocNum] AS VARCHAR)) AS 'DocNum', T0.[DocDate], T0.[CardCode], T0.[CardName], T2.[U_Name]
											FROM ".$BoxData['BillType']." T0
											LEFT JOIN NNM1 T1 ON T0.[Series] = T1.[Series]
											LEFT JOIN [dbo].[@SHIPPINGTYPE] T2 ON T0.[U_ShippingType] = T2.[Code]
											WHERE T0.[DocEntry] = '".$BoxData['BillEntry']."'";
								$getSAP = SAPSelect($sqlSAP);
								$DataSAP = odbc_fetch_array($getSAP);

								$Customer = conutf8($DataSAP['CardName']);
								$ListDocDate =  $DataSAP['DocDate'];
								$ListDocNum = $DataSAP['DocNum'];
								$LogiName = conutf8($DataSAP['U_Name']);
								break;
							case 'OWAR':
							case 'OWAB':
							case 'OWAS':
								$sqlOWALL ="SELECT T0.DocNum,T0.CusCode,T0.CusName,DATE(T0.DateCreate) AS DocDate,T0.LogiName
											FROM owas T0 
											WHERE T0.DocEntry = '".$BoxData['BillEntry']."' LIMIT 1";
								$getOWALL = MySQLSelect($sqlOWALL);

								$Customer = $getOWALL['CusName'];
								$ListDocDate =  $getOWALL['DocDate'];
								$ListDocNum = $getOWALL['DocNum'];
								$LogiName = $getOWALL['LogiName'];
								break;
						}

						$InsertNew = "INSERT INTO logi_detail
									  SET LogiNum = '".$_POST['DocID']."',
										BoxCode = '".$txtFind."',
										CardCode = '".$BoxData['CardCode']."',
										BillEntry = '".$BoxData['BillEntry']."',
										BillType = '".$BoxData['BillType']."',
										BoxNo = '".$BoxData['BoxNo']."',
										OutTime =  NOW(),
										Status = 2,
										UkeyOut = '".$_SESSION['ukey']."',
										OnLoad = 'Y',
										NewLoad = 'Y'";
						MySQLInsert($InsertNew);
						$Arert = "เพิ่มสินค้าเพิ่มเติมจากรายการใบนำออก เรียบร้อยแล้ว";
                    	$ar = 2.5;
						break;
					default : // สินค้าไม่สามารถโหลดขึ้นคันใหม่ได้ st >= 2
						$Arert = "ไม่สามารถโหลดกล่องนี้ กรุณาตรวจสอบใหม่" ; 
						$ar = 1;
						break;
				}
			}
		}
	}
	$arrCol['ar'] = $ar;
	$arrCol['Arert'] = $Arert;
}

if($_GET['a'] == 'DelIV') {
	MySQLUpdate("UPDATE logi_detail SET Status = 0 WHERE LogiNum = '".$_POST['DocID']."' AND BillEntry = '".$_POST['BillEntry']."' AND BillType = '".$_POST['BillType']."'");
	$Alert = "ยกเลิกการโหลดสินค้ารายการนี้เรียบร้อยแล้ว";
	$arrCol['Arert'] = $Alert;
}

if($_GET['a'] == 'saveLoad') {
	MySQLUpdate("UPDATE logi_head SET LoadDate = NOW(), ukeyLoad = '".$_SESSION['ukey']."', Status = 3 WHERE LogiNum = '".$_POST['DocID']."'");
	$ax=0;
	$MySQL ="SELECT DISTINCT T0.ID
			FROM picker_soheader T0
			JOIN pack_header T1 ON T1.IDPick = T0.ID
			JOIN logi_detail T2 ON T2.BillEntry = T1.BillEntry AND T2.BillType = T1.BillType
			WHERE T2.LogiNum = '".$_POST['DocID']."'";
	$getID = MySQLSelectX($MySQL);
	$IDList = "";
	while($DataSO = mysqli_fetch_array($getID)) {
		$IDList .= $DataSO['ID'].",";
   	 	$ax++;
	}
	$IDList = substr($IDList,0,-1);
	MySQLUpdate("UPDATE picker_soheader SET LastUpdate = NOW(), LastUkey = '".$_SESSION['ukey']."', StatusDoc = 12 WHERE ID IN (".$IDList.")");
	$Alert = "ยืนยันโหลดสินค้าเรียบร้อยแล้ว";
	$arrCol['Arert'] = $Alert;
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>