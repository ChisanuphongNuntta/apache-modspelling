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

if($_GET['a'] == 'CallData2') {
    switch ($_SESSION['LvCode']){
        case 'LV008': // หัวหน้าไอที
        case 'LV009': // พนักงานไอที
        case 'LV072': //ผจก.คลัง
        case 'LV073': // หัวหน้าคลัง
            $TxtDis = "  ";
            $showPick = 1;
        break;
        case 'LV076': // เติมสินค้า
        case 'LV078': // พนักงานคลังผลิต
                $TxtDis = "  ";
                $showPick = 0;
        break;
        case 'LV077': // เบิกสินค้า
            $TxtDis = " disabled ";
            $showPick = 0;
        break;
        default :
            $TxtDis = " disabled ";
            $showPick = 0;
        break;
    }
    // echo $_POST['ItemCode']; 01-310-005
    $chkRack = CHKRowDB("SELECT * FROM allwhs WHERE LocationRack = '".$_POST['ItemCode']."'");
    if($chkRack > 0) {
        $FindData = 1;
    }else{
        $chkItem = CHKRowDB("SELECT * 
                             FROM oitm 
                             WHERE (ItemCode = '".$_POST['ItemCode']."' OR BarCode = '".$_POST['ItemCode']."' OR BarCode2 = '".$_POST['ItemCode']."' OR BarCode3 = '".$_POST['ItemCode']."' OR ItemName Like '%".$_POST['ItemCode']."%')");
        if($chkItem > 0) {
            $FindData = 2; //ข้อมูลสินค้า
        }else{
            $FindData = 3; // ไม่พบข้อมูลสินค้า
        }
    }
    $output = "";
    $outputH2 = "";
    $output2 = "";
    // echo $FindData;
    switch ($FindData){
        case 1: 
            $MySQL ="SELECT T0.WhsCode,T0.LocRack,T1.LocationName,T1.DisLoc,
                        (SELECT COUNT(DISTINCT P1.ItemCode) FROM oitw P1 WHERE P1.LocRack = T0.LocRack) AS SKUCount,
                        (SELECT SUM(P2.OnHand-P2.IsCommited) FROM oitw P2 WHERE P2.LocRack = T0.LocRack) AS ItemCount  
                    FROM oitw T0
                        LEFT JOIN allwhs T1 ON T0.LocRack = T1.LocationRack
                    WHERE T0.LocRack = '".$_POST['ItemCode']."' AND T0.OnHand != 0 LIMIT 1";
            $RackData = MySQLSelect($MySQL);
            $output =  "<tr>
                            <td width='25%' class='pb-0 fw-bold text-primary'>คลังสินค้า</td>
                            <td width='75%' class='pb-0 ps-0'>".$RackData['WhsCode']."</td>
                        </tr>
                        <tr>
                            <td class='pb-0 fw-bold text-primary'>ตำแหน่ง</td>
                            <td class='pb-0 ps-0'>".$RackData['LocRack']."</td>
                        </tr>
                        <tr>
                            <td class='pb-0 fw-bold text-primary'>SKU</td>
                            <td class='pb-0 ps-0'>".number_format($RackData['SKUCount'])." รายการ</td>
                        </tr>
                        <tr>
                            <td class='pb-0 fw-bold text-primary'>จำนวน</td>
                            <td class='pb-0 ps-0'>".number_format($RackData['ItemCount'])." ชิ้น</td>
                        </tr>";
            $outputH2 .="<tr class='text-center'>
                            <td>รหัสสินค้า</td>
                            <td>ชื่อสินค้า</td>
                            <td>จำนวน</td>
                        </tr>";
            $MyItem =  "SELECT T0.ItemCode,T1.ItemName,(OnHand-IsCommited) AS OnHand,T0.MinStock,
                               (SELECT DATE(P0.DateRecive) FROM transecdata P0 WHERE P0.ItemCode = T0.ItemCode AND T0.LocRack = P0.LocationRack AND P0.QtyIn != 0 AND P0.StatusTran = 1 ORDER BY P0.DateRecive DESC LIMIT 1 ) AS DateIN
                        FROM oitw T0
                        LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
                        WHERE T0.LocRack = '".$RackData['LocRack']."'";
            $getItem = MySQLSelectX($MyItem);
            while($ItemData = mysqli_fetch_array($getItem)) {
                if ($ItemData['OnHand'] <= $ItemData['MinStock']){
                    $style = "style='color : #000; background-color: #ffe6e6;'";
                }else{
                    $style = "";
                }

                $output2 .= "<tr>
                                <td class='text-center text-primary'>".$ItemData['ItemCode']."</td>";
                if (($RackData['WhsCode']) == 'KSY'){
                    $output2 .= "<td class='ps-0'>".$ItemData['ItemName']."<br><span style='color:#0000e6'>[".date('d/m/Y',strtotime($ItemData['DateIN']))."]</span></td>";
                }else{
                    $output2 .= "<td class='ps-0'>".$ItemData['ItemName']."</td>";
                }
                $output2 .= "    <td class='ps-0 text-right fw-bold' ".$style.">".$ItemData['OnHand']."</td>
                            </tr>";
            }
            if ($RackData['DisLoc'] == 'Y'){
                $output2 .= "<tr>
                                <td colspan='3' class='text-center'>
                                    <span style='color:#0000e6;font-weight:bold'>** ชันวางสินค้านี้ Lock แล้ว **</span>
                                </td>
                            </tr> ";
            }else{
                $output2 .= "<tr>
                                <td colspan='3' class='text-right'>
                                    <button class='btn btn-sm btn-success' onclick=\"LockItem('".$RackData['LocRack']."')\" >Lock</button>
                                </td>
                            </tr>";
            }
            break;
        case 2:
            if ($chkItem == 1) {
                $SQLFindItemCode = "SELECT ItemCode, IsBom, BomGroup
                                    FROM oitm 
                                    WHERE ItemCode = '".$_POST['ItemCode']."' OR BarCode = '".$_POST['ItemCode']."' OR BarCode2 = '".$_POST['ItemCode']."' OR BarCode3 = '".$_POST['ItemCode']."' OR ItemName Like '%".$_POST['ItemCode']."%'";
                $ItemCodeData = MySQLSelect($SQLFindItemCode);
                if ($ItemCodeData['IsBom'] == 0) {
                    /* KSY, KSM, KB4, PM, PM-KSY */
                    $MsItem =  "SELECT P1.ItemCode,P1.CodeBars,P1.ItemName,P1.SalUnitMsr,P1.U_ProductStatus,
                                        (SELECT SUM(T0.[OnHand]) 
                                        FROM OITW T0 
                                        WHERE (T0.[OnHand] !=0 ) AND T0.ItemCode = P1.ItemCode AND T0.WhsCode IN ('KSY','KSM','KB4','PM','PM-KSY')
                                        GROUP BY T0.ItemCode) AS OnHand
                                FROM OITM P1
                                WHERE P1.ItemCode = '".$ItemCodeData['ItemCode']."'";
                    $sqlSAP = SAPSelect($MsItem);
                    $DataHead = odbc_fetch_array($sqlSAP);
                    $StockSQL ="SELECT P0.*,CASE WHEN P0.WhsCode = 'KSY' THEN 0 
                                                WHEN P0.WhsCode = 'KSM' THEN 1 
                                                ELSE 4 END AS ListNo
                                FROM (SELECT T0.WhsCode,SUM(T0.OnHand) AS OnHand 
                                    FROM OITW T0
                                    WHERE T0.ItemCode = '".$ItemCodeData['ItemCode']."' AND T0.WhsCode IN ('KSY','KSM','KB4','PM','PM-KSY')
                                    GROUP BY T0.WhsCode) P0
                                ORDER BY ListNo,WhsCode";
                    // echo $StockSQL;
                    $sqlSAP1 = SAPSelect($StockSQL);
                    $cx=0;
                    $listWhs = "('";
                    while($DataStock = odbc_fetch_array($sqlSAP1)) {
                        $cx++;
                        $WhsCode[$cx] = conutf8($DataStock['WhsCode']);
                        $chkWhs = CHKRowDB("SELECT * FROM allwhs WHERE WhsCode = '".conutf8($DataStock['WhsCode'])."'");
                        if ($chkWhs != 0){
                            // echo $WhsCode[$cx];
                            $Row['Rack'][$WhsCode[$cx]] = conutf8($DataStock['WhsCode'])."-Recive";
                        }else{
                            $Row['Rack'][$WhsCode[$cx]] = "-";
                        }
                        $Row['ItemCount'][conutf8($DataStock['WhsCode'])] = $DataStock['OnHand'];
                        $Rox['tmpCount'][conutf8($DataStock['WhsCode'])] = $DataStock['OnHand'];
                        $listWhs .= $WhsCode[$cx]."','";
                        // echo conutf8($DataStock['WhsCode'])."\n";
                    }
                     //echo $listWhs;

                    $MySQLOnHand = "SELECT WhsCode, SUM(OnHand) AS OnHand FROM oitw WHERE ItemCode = '".$ItemCodeData['ItemCode']."' GROUP BY WhsCode";
                    $sqlOnHand = MySQLSelectX($MySQLOnHand);
                    $ax=0;
                    while ($OnHand = mysqli_fetch_array($sqlOnHand)){
                        // echo $OnHand['WhsCode']."\n";
                        if(isset($Row['ItemCount'][$OnHand['WhsCode']])) {
                            $Row['ItemCount'][$OnHand['WhsCode']] = $Row['ItemCount'][$OnHand['WhsCode']] - $OnHand['OnHand'];
                        }
                        $ax++;
                    }
                    if ($cx != 0){
                        $listWhs = substr($listWhs,0,-2).")";  
                    }else{
                        $listWhs = "('')";
                    }
                    $MySQLOnHand2 =  "SELECT WhsCode, SUM(OnHand) AS OnHand FROM oitw WHERE ItemCode = '".$ItemCodeData['ItemCode']."' AND WhsCode NOT IN ".$listWhs." GROUP BY WhsCode";
                    $sqlOnHand2 = MySQLSelectX($MySQLOnHand2);
                    while ($OnHand2 = mysqli_fetch_array($sqlOnHand2)){
                        $cx++;
                        $WhsCode[$cx] = $OnHand2['WhsCode'];
                        // echo $OnHand2['WhsCode']." ";
                        $Row['Rack'][$WhsCode[$cx]] = $OnHand2['WhsCode']."-Recive";
                        // $Row['Rack'][$WhsCode[$cx]] = $OnHand2['LocRack'];
                        $Row['ItemCount'][$WhsCode[$cx]] = $OnHand2['OnHand'];
                    }
                    $OnMySQL = 0;
                }else{
                    $MySQLHead = "SELECT T0.ItemCode, T0.BarCode AS CodeBars, T0.ItemName, T0.MgrUnit,
                                         (SELECT CASE WHEN SUM(T1.OnHand) > 0 THEN SUM(T1.OnHand) ELSE 0 END FROM oitw T1 WHERE T1.ItemCode = T0.ItemCode) AS OnHand
                                  FROM oitm T0 WHERE T0.ItemCode = '".$ItemCodeData['ItemCode']."'";
                    $ItemHead = MySQLSelect($MySQLHead);
                    $DataHead['ItemName'] = $ItemHead['ItemName'];
                    $DataHead['ItemCode'] = $ItemHead['ItemCode'];
                    $DataHead['SalUnitMsr'] = $ItemHead['MgrUnit'];
                    $DataHead['OnHand'] = $ItemHead['OnHand'];
                    $DataHead['CodeBars'] = $ItemHead['CodeBars'];
                    $OnMySQL = 1;
                    $MySQLOnHand = "SELECT WhsCode, SUM(OnHand) AS OnHand FROM OITW WHERE ItemCode = '".$ItemCodeData['ItemCode']."' GROUP BY WhsCode";
                    $getOnHand = MySQLSelectX($MySQLOnHand);
                    $cx=0;
                    while ($OnHand = mysqli_fetch_array($getOnHand)){
                        $cx++;
                        $WhsCode[$cx] = $OnHand['WhsCode'];
                        $Row['Rack'][$OnHand['WhsCode']] = $OnHand['WhsCode']."-Recive";
                        // $Row['ItemCount'][$WhsCode[$cx]] = $Row['ItemCount'][$WhsCode[$cx]] - $OnHand['OnHand'];
                        $Row['ItemCount'][$WhsCode[$cx]] = $OnHand['OnHand'];
                        $Rox['tmpCount'][$WhsCode[$cx]] = 0;
                    }
                }

                $output =  "<tr>
                                <td width='25%' class='pb-0 fw-bold text-primary'>รหัสสินค้า</td>
                                <td width='75%' class='pb-0 ps-0'>".$DataHead['ItemCode']."</td>
                            </tr>
                            <tr>
                                <td class='pb-0 fw-bold text-primary'>ชื่อสินค้า</td>
                                <td class='pb-0 ps-0'>";
                                    if($OnMySQL == 1) {
                                        $output .= $DataHead['ItemName']." <span style='color:#000'>(สินค้าบอมไม่มีข้อมูลใน SAP)</span>";
                                    }else{
                                        $output .= conutf8($DataHead['ItemName']);
                                    }
                    $output .= "</td>
                            </tr>
                            <tr>
                                <td class='pb-0 fw-bold text-primary'>บาร์โค้ด</td>
                                <td class='pb-0 ps-0'>".$DataHead['CodeBars']."</td>
                            </tr>
                            <tr>
                                <td class='pb-0 fw-bold text-primary'>จำนวน</td>
                                <td class='pb-0 ps-0'>
                                    <div class='d-flex align-items-center'>
                                        <span>".number_format($DataHead['OnHand']);
                                        if($OnMySQL == 1) {
                                            $output .= " ".$DataHead['SalUnitMsr'];
                                        }else{
                                            $output .= " ".conutf8($DataHead['SalUnitMsr']);
                                        }
                                        // echo number_format($DataHead['OnHand']);
                            $output .= "</span>&nbsp;&nbsp;
                                        <button class='btn btn-sm bg-secondary p-1' onclick=\"NewRow('".$ItemCodeData['ItemCode']."')\"; ".$TxtDis.">
                                            <i class='fas fa-plus-circle text-light'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>";

                $outputH2 .=    "<tr class='text-center'>
                                    <th class='fw-bold bg-light'>คลัง</th>
                                    <th class='fw-bold bg-light'>ตำแหน่ง</th>
                                    <th class='fw-bold bg-light'>จำนวน</th>
                                    <th class='fw-bold bg-light'>โอนย้าย</th>
                                </tr>";
                $WhsChk  = array("KSY","KSM","KB4","MT","MT2","TT-C","OUL");
                for ($i=1; $i <= $cx; $i++){
                    // echo $WhsCode[$i]." ";
                    $Warehouse = $WhsCode[$i];
                    $Chck = array_search($Warehouse,$WhsChk);
                    // echo $i."|".$WhsCode[$i]."|".(array_search($Warehouse,$WhsChk) !== false)."<br/>";
                    
                    if(array_search($Warehouse,$WhsChk) !== false) {
                        $output2 .= "<tr>
                                        <td class='text-center text-primary'>".$WhsCode[$i]."</td>
                                        <td class='ps-0'>".$Row['Rack'][$WhsCode[$i]]."</td>
                                        <td class='ps-0 text-right text-primary'>".number_format($Row['ItemCount'][$WhsCode[$i]])."</td>
                                        <td class='ps-0 text-center'>
                                            <button class='btn btn-sm btn-secondary p-1' onclick=\"MoveSKU('".$ItemCodeData['ItemCode']."','".$WhsCode[$i]."')\"; ".$TxtDis.">
                                                <i class='fas fa-sync'></i>
                                            </button>
                                        </td>
                                    </tr>;";
                        // echo $Rox['tmpCount'][$WhsCode[$i]]." ".$Row['ItemCount'][$WhsCode[$i]]."<br>";
                        if(isset($Row['ItemCount'][$WhsCode[$i]]) && isset($Rox['tmpCount'][$WhsCode[$i]])) {
                            if($Rox['tmpCount'][$WhsCode[$i]] != $Row['ItemCount'][$WhsCode[$i]]) {
                                $SQLrack = "SELECT T0.LocRack,SUM((T0.OnHand-T0.IsCommited)) AS OnHand,T0.MinStock,
                                                (SELECT DATE(P1.DateRecive) FROM transecdata P1 WHERE P1.WhsCode = T0.WhsCode AND P1.LocationRack = T0.LocRack AND P1.ItemCode = T0.ItemCode AND P1.QtyIn != 0 AND P1.StatusTran = 1 ORDER BY P1.DateRecive LIMIT 1) AS DateIN
                                            FROM OITW T0 
                                            WHERE T0.ItemCode = '".$ItemCodeData['ItemCode']."' AND T0.WhsCode = '".$WhsCode[$i]."' 
                                                AND ((T0.OnHand-T0.IsCommited) != 0) 
                                            GROUP BY T0.LocRack
                                            ORDER BY DateIN,T0.LocRack";
                                            // echo $SQLrack;
                                $getRack = MySQLSelectX($SQLrack);
                                while($OnRack = mysqli_fetch_array($getRack)) {
                                    if(substr($OnRack['LocRack'],-7) != 'Picking' OR $showPick == 1) {
                                        switch($OnRack['LocRack']){
                                            case 'B1-P0-00-KSY':
                                            case 'B1-P0-00-KSM':
                                            case 'B1-P0-00-MT':
                                            case 'B1-P0-00-OUL':
                                            case 'B1-P0-00-TTC':
                                                $ITSoftWare = "IT";
                                                break;
                                            default :
                                                $output2 .= "<tr>";
                                                    // column 1
                                                    if($WhsCode[$i] != "KB1") {
                                                        $output2 .= "<td class='text-center text-primary'>".$WhsCode[$i]."</td>";
                                                    }
                                                    // column 2
                                                if($WhsCode[$i] != 'KSY') {
                                                    $output2 .= "<td class='ps-0'>".$OnRack['LocRack']."<br><span style='color:#0000e6; font-size: 12px;'>[".date('d/m/Y',strtotime($OnRack['DateIN']))."]</td>";
                                                }else{
                                                    $output2 .= "<td class='ps-0'>".$OnRack['LocRack']."</td>";
                                                }
                                                    // column 3
                                                if ($OnRack['OnHand'] <= $OnRack['MinStock'] AND $OnRack['MinStock'] != 0){
                                                    $output2 .= "<td class='ps-0 text-right fw-bold text-primary' style='color : #000; background-color: #ffe6e6;'>".number_format($OnRack['OnHand'])."</td>";
                                                }else{
                                                    $output2 .= "<td class='ps-0 text-right text-primary'>".number_format($OnRack['OnHand'])."</td>";
                                                }
                                                    // column 4
                                                if($Row['Rack'][$WhsCode[$i]] != '-'){ 
                                                    if (substr($OnRack['LocRack'],-7) != 'Picking'){
                                                        $output2 .= "<td class='ps-0 text-center'>
                                                                        <button class='btn btn-sm btn-secondary p-1' onclick=\"MoveSKU('".$ItemCodeData['ItemCode']."','".$OnRack['LocRack']."')\"; ".$TxtDis.">
                                                                            <i class='fas fa-sync'></i>
                                                                        </button>
                                                                    </td>";
                                                    }else{
                                                        $output2 .= "<td class='ps-0 text-center'>&nbsp;</td>";
                                                    }
                                                    
                                                }else{
                                                    $output2 .= "<td class='ps-0 text-center'>&nbsp;</td>";
                                                }
                                                $output2 .= "</tr>";
                                                break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            break;
    }
    $arrCol['output'] = $output;
    $arrCol['outputH2'] = $outputH2;
    $arrCol['output2'] = $output2;
}

if($_GET['a'] == 'NewRow') {
    $MySQL ="SELECT T0.ItemCode,T0.BarCode,T0.ItemName
            FROM oitm T0
            WHERE T0.ItemCode = '".$_POST['ItemCode']."'";
    $ItemData = MySQLSelect($MySQL);
    $tbody ="<tr>
                <td class='fw-bold text-primary'>ชื่อสินค้า</td>
                <td class='ps-2'>".$ItemData['ItemName']."</td>
            </tr>
            <tr>
                <td class='fw-bold text-primary'>รหัสสินค้า</td>
                <td class='ps-2'>".$ItemData['ItemCode']."</td>
            </tr>
            <tr>
                <td class='fw-bold text-primary'>บาร์โค้ด</td>
                <td class='ps-2'>".$ItemData['BarCode']."</td>
            </tr>
            <tr>
                <td class='fw-bold text-primary'>คลังสินค้า</td>
                <td class='ps-2'>
                    <select name='WhsCode' id='WhsCode' class='form-select form-select-sm'>
                        <option value='KSY' selected>KSY</option>
                        <option value='KB4'>KB4</option>
                        <option value='KSM'>KSM</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class='fw-bold'>
                    <span class='text-primary'>ไปยัง</span>
                    <input class='form-check-input' type='checkbox' name='DftRack' id='DftRack' disabled>
                </td>
                <td class='ps-2'><input class='form-control form-control-sm' type='text' id='NewRack' name='NewRack' placeholder='สแกนบาร์โค้ดชั้นวางใหม่'></td>
            </tr>
            <tr>
                <td class='fw-bold text-primary'>จำนวน</td>
                <td class='ps-2'><input class='form-control form-control-sm' type='number' id='QtrMove' name='QtrMove' placeholder='จำนวน'></td>
            </tr>";

    $M_Fter =  "<button type='button' class='btn btn-sm btn-secondary' data-bs-dismiss='modal'>ปิด</button>
                <button type='button' class='btn btn-sm btn-success' onclick=\"AddRow();\">ยืนยัน</button>
                <input type='hidden' name='OldRack' id='OldRack' value='KSY-Recive'>";

    $arrCol['ItemName'] = $_POST['ItemCode'];
    // $arrCol['WhsCode'] = $ItemData['WhsCode'];
    $arrCol['M_Fter'] = $M_Fter;
    $arrCol['tbody'] = $tbody;
}

if($_GET['a'] == 'MoveItem') {
    $_POST['NewRack'] = strtoupper($_POST['NewRack']);
    $_POST['WhsCode'] = strtoupper($_POST['WhsCode']);
    $sql1 = "SELECT * FROM allwhs WHERE LocationRack = '".$_POST['NewRack']."'";
    $ShowRack = MySQLSelect($sql1);

    $CkRow = CHKRowDB($sql1);
    if($CkRow != 0) {
        if($ShowRack['DisLoc'] == 'Y') {
            if ($_SESSION['LvCode'] == 'LV072' OR $_SESSION['LvCode'] == 'LV073' OR $_SESSION['LvCode'] == 'LV076' OR $_SESSION['LvCode'] == 0){
                $PassMove = 'Y';
            }else{
                $PassMove = 'N';
            }
        }else{
            $sql2 = "SELECT * FROM allwhs WHERE LocationRack = '".strtoupper($_POST['OldRack'])."'";
            $ShowRack2 = MySQLSelect($sql1);
            if ($ShowRack2['DisLoc'] == 'Y'){
                if ($_SESSION['LvCode'] == 'LV072' OR $_SESSION['LvCode'] == 'LV073' OR $_SESSION['LvCode'] == 'LV076' OR $_SESSION['LvCode'] == 0){
                    $PassMove = 'Y';
                }else{
                    $PassMove = 'N';
                }
            }else{
                $PassMove = 'Y';
            }
        }
        // echo $_POST['NewRack']." ".$_POST['WhsCode'];
        if ($PassMove == 'Y') {
            if (substr($_POST['NewRack'],0,1) == 'M') {
                $chkRack = CHKRowDB("SELECT * FROM allwhs WHERE LocationRack = '".$_POST['NewRack']."' AND WhsCode = '".$_POST['WhsCode']."'");
                if ($chkRack == 0) {
                    if ($_POST['WhsCode'] == 'TT-C'){
                        $NewCode = 'TTC';
                    }else{
                        $NewCode = $_POST['WhsCode'];
                    }
                    $NewRack = $_POST['NewRack']."-".$NewCode;
                    MySQLInsert("INSERT INTO allwhs SET WhsCode = '".$_POST['WhsCode']."',LocationRack = '".$NewRack."',Status = 1,AllFree = 'N'");
                }
            }else{
                $chkRack = CHKRowDB("SELECT * FROM allwhs WHERE LocationRack = '".$_POST['NewRack']."' AND WhsCode = '".$_POST['WhsCode']."'");
            }

            if($chkRack == 0) {
                $HeadAlert = "<i class='fas fa-exclamation-circle text-primary' style='font-size: 70px;'></i>";
                $alertBox = "ไม่พบ Location ".$_POST['NewRack']." ในคลัง ".$_POST['WhsCode'];
            }else{
                // ย้ายออก 
                if (substr($_POST['OldRack'],-6) == 'Recive') {
                    $chkRow = CHKRowDB("SELECT * FROM oitw WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode ='".$_POST['WhsCode']."' AND LocRack = '".$_POST['NewRack']."'");
                    if($chkRow != 0) {
                        // ชั้นวาง+คลัง มีของ
                        $SQLOnHand2 = "SELECT OnHand FROM oitw WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode = '".$_POST['WhsCode']."' AND LocRack = '".$_POST['NewRack']."'";
                        $OnHand2 = MySQLSelect($SQLOnHand2);
                        $newValue2 = intval($OnHand2['OnHand'])+intval(ConToInt($_POST['QtyMove']));
                        MySQLUpdate("UPDATE oitw SET OnHand = ".$newValue2." WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode = '".$_POST['WhsCode']."' AND LocRack = '".$_POST['NewRack']."'");
                        MySQLInsert("INSERT INTO transecdata SET WhsCode = '".$_POST['WhsCode']."', LocationRack = '".$_POST['NewRack']."', ItemCode = '".$_POST['ItemCode']."', QtyIn = ".$_POST['QtyMove'].", QtyOut = 0, DateCreate = NOW(), ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, DateRecive=Now()");
                    }else{
                        // ชั้นวาง+คลัง ไม่มีของ
                        MySQLInsert("INSERT INTO oitw SET ItemCode = '".$_POST['ItemCode']."', WhsCode = '".$_POST['WhsCode']."', LocRack = '".$_POST['NewRack']."', OnHand = ".intval(ConToInt($_POST['QtyMove']))."");
                        MySQLInsert("INSERT INTO transecdata SET WhsCode = '".$_POST['WhsCode']."', LocationRack = '".$_POST['NewRack']."', ItemCode = '".$_POST['ItemCode']."', QtyIn = ".$_POST['QtyMove'].", QtyOut = 0, DateCreate = NOW(), ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, DateRecive=Now()");
                    }
                    $HeadAlert = "<i class='fas fa-check-circle text-success' style='font-size: 70px;'></i>";
                    $alertBox = "ย้ายสินค้าเรียบร้อยแล้ว";
                }else{
                    $moveOnRecive = 0;
                    $SQLOnHand = "SELECT OnHand FROM oitw WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode = '".$_POST['WhsCode']."' AND LocRack = '".$_POST['OldRack']."'";
                    $OnHand = MySQLSelect($SQLOnHand);
                    if(isset($OnHand['OnHand'])) {
                        $OnHand['OnHand'] = $OnHand['OnHand'];
                    }else{
                        $OnHand['OnHand'] = 0;
                    }
                    if ($OnHand['OnHand'] >= intval(ConToInt($_POST['QtyMove']))) {
                        MySQLInsert("INSERT INTO transecdata SET WhsCode = '".$_POST['WhsCode']."', LocationRack = '".$_POST['OldRack']."', ItemCode = '".$_POST['ItemCode']."', QtyIn = 0, QtyOut = ".$_POST['QtyMove'].", DateCreate = NOW(), ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1");
                        $sqkRecive = "SELECT DateRecive 
                                    FROM transecdata 
                                    WHERE WhsCode = '".$_POST['WhsCode']."' AND LocationRack = '".$_POST['OldRack']."' AND ItemCode = '".$_POST['ItemCode']."' AND QtyIn != 0 AND StatusTran = 1 ORDER BY DateCreate DESC LIMIT 1";
                        $ReciveDate = MySQLSelect($sqkRecive);   
                        if(isset($ReciveDate['DateRecive'])) {
                            $ReciveDate['DateRecive'] = $ReciveDate['DateRecive'];
                        }else{
                            $ReciveDate['DateRecive'] = null;
                        }
                        MySQLInsert("INSERT INTO transecdata SET WhsCode = '".$_POST['WhsCode']."', LocationRack = '".$_POST['NewRack']."', ItemCode = '".$_POST['ItemCode']."', QtyIn = ".$_POST['QtyMove'].", QtyOut = 0, DateCreate = NOW(), ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, DateRecive = '".$ReciveDate['DateRecive']."'"); 
                        $newValue = $OnHand['OnHand'] - $_POST['QtyMove'];  
                        MySQLUpdate("UPDATE oitw SET OnHand = ".$newValue." WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode = '".$_POST['WhsCode']."' AND LocRack = '".$_POST['OldRack']."'");

                        // เติมสินค้า
                        $chkRow = CHKRowDB("SELECT * FROM oitw WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode ='".$_POST['WhsCode']."' AND LocRack = '".$_POST['NewRack']."'");
                        if($chkRow != 0) {
                            $SQLOnHand2 = "SELECT OnHand FROM oitw WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode = '".$_POST['WhsCode']."' AND LocRack = '".$_POST['NewRack']."'";
                            $OnHand2 = MySQLSelect($SQLOnHand2);
                            $newValue2 = intval(ConToInt($OnHand2['OnHand']))+intval(ConToInt($_POST['QtyMove']));
                            MySQLUpdate("UPDATE oitw SET OnHand = ".$newValue2." WHERE ItemCode = '".$_POST['ItemCode']."' AND WhsCode = '".$_POST['WhsCode']."' AND LocRack = '".$_POST['NewRack']."'");
                        }else{
                            MySQLInsert("INSERT INTO oitw SET ItemCode = '".$_POST['ItemCode']."', WhsCode = '".$_POST['WhsCode']."', LocRack = '".$_POST['NewRack']."', OnHand = ".intval(ConToInt($_POST['QtyMove']))."");
                        }
                        $HeadAlert = "<i class='fas fa-check-circle text-success' style='font-size: 70px;'></i>";
                        $alertBox = "ย้ายสินค้าเรียบร้อยแล้ว";
                    }else{
                        $HeadAlert = "<i class='fas fa-exclamation-circle text-primary' style='font-size: 70px;'></i>";
                        $alertBox = "จำนวนสินค้ามีไม่พอ";
                    }
                }
            }
        }else{
            $alertBox = "ชั้นวางสินค้านี้ไม่สามารถย้ายได้ แจ้งพนักงานเติมสินค้า ";
        }
    }else{
        $HeadAlert = "<i class='fas fa-exclamation-circle text-primary' style='font-size: 70px;'></i>";
        $alertBox = "ไม่พบชั้นวางนี้ในระบบ";
    }
    $arrCol['HeadAlert'] = $HeadAlert;
    $arrCol['alertBox'] = $alertBox;
}

if($_GET['a'] == 'MoveSKU') {
    $chkWhs = CHKRowDB("SELECT * FROM allwhs WHERE WhsCode = '".$_POST['LocRack']."'");
    if ($chkWhs != 0) {
        $MySQL = "SELECT T0.ItemCode,T0.BarCode,T0.ItemName,'".$_POST['LocRack']."-Recive' AS LocRack,'".$_POST['LocRack']."' AS WhsCode 
                  FROM oitm T0
                  WHERE T0.ItemCode = '".$_POST['ItemCode']."'";
    }else{
        $MySQL = "SELECT T0.ItemCode,T1.BarCode,T1.ItemName,T0.LocRack,T0.WhsCode
                  FROM oitw T0
                  JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
                  WHERE T0.ItemCode = '".$_POST['ItemCode']."' AND T0.LocRack = '".$_POST['LocRack']."'";
    }
    $ItemData = MySQLSelect($MySQL);
    $tbody ="<tr>
                <td class='fw-bold text-primary'>ชื่อสินค้า</td>
                <td class='ps-2'>".$ItemData['ItemName']."</td>
            </tr>
            <tr>
                <td class='fw-bold text-primary'>รหัสสินค้า</td>
                <td class='ps-2'>".$ItemData['ItemCode']."</td>
            </tr>
            <tr>
                <td class='fw-bold text-primary'>บาร์โค้ด</td>
                <td class='ps-2'>".$ItemData['BarCode']."</td>
            </tr>
            <tr>
                <td class='fw-bold text-primary'>ย้ายจาก</td>
                <td class='ps-2'>".$ItemData['LocRack']."</td>
            </tr>
            <tr>
                <td class='fw-bold'>
                    <span class='text-primary'>ไปยัง</span>
                    <input class='form-check-input' type='checkbox' name='DftRack' id='DftRack' disabled>
                </td>
                <td class='ps-2'><input class='form-control form-control-sm' type='text' id='NewRack' name='NewRack' placeholder='สแกนบาร์โค้ดชั้นวางใหม่'></td>
            </tr>
            <tr>
                <td class='fw-bold text-primary'>จำนวน</td>
                <td class='ps-2'><input class='form-control form-control-sm' type='number' id='QtrMove' name='QtrMove' placeholder='จำนวน'></td>
            </tr>";

    $M_Fter =  "<button type='button' class='btn btn-sm btn-secondary' data-bs-dismiss='modal'>ปิด</button>
                <button type='button' class='btn btn-sm btn-success' onclick=\"MoveItem();\">ยืนยัน</button>
                <input type='hidden' name='WhsCode' id='WhsCode' value='".$ItemData['WhsCode']."'>
                <input type='hidden' name='OldRack' id='OldRack' value='".$ItemData['LocRack']."'>";

    $arrCol['tbody'] = $tbody;
    $arrCol['M_Fter'] = $M_Fter;
}

if($_GET['a'] == 'LockItem') {
    MySQLUpdate("UPDATE allwhs SET DisLoc = 'Y' WHERE LocationRack = '".$_POST['Rack']."'");
    $alertBox = "Lock ชั้นวางสินค้านี้เรียบร้อยแล้ว";
    $HeadAlert = "<i class='fas fa-check-circle text-success' style='font-size: 70px;'></i>";
    $arrCol['HeadAlert'] = $HeadAlert;
    $arrCol['alertBox'] = $alertBox;
}

if($_GET['a'] == 'SearchData') {
    $chkRack = CHKRowDB("SELECT * FROM allwhs WHERE LocationRack = '".$_POST['Data']."'");
    if($chkRack > 0) {
        $FindData = 1;
        $arrCol['ItemCode'] = $_POST['Data'];
    }else{
        $chkItem = CHKRowDB("SELECT * 
                             FROM oitm 
                             WHERE (ItemCode = '".$_POST['Data']."' OR BarCode = '".$_POST['Data']."' OR BarCode2 = '".$_POST['Data']."' OR BarCode3 = '".$_POST['Data']."' OR ItemName Like '%".$_POST['Data']."%')");
        if($chkItem > 0) {
            $FindData = 2; //ข้อมูลสินค้า
        }else{
            $FindData = 3; // ไม่พบข้อมูลสินค้า
        }
    }

    if($FindData == 2) {
        if ($chkItem == 1){
            $arrCol['ItemCode'] = $_POST['Data'];
        }else{
            $MySQL = "SELECT ItemCode,ItemName FROM oitm WHERE ItemCode = '".$_POST['Data']."' OR BarCode = '".$_POST['Data']."' OR BarCode2 = '".$_POST['Data']."' OR BarCode3 = '".$_POST['Data']."' OR ItemName Like '%".$_POST['Data']."%'";
            $getItemList = MySQLSelectX($MySQL);
            $TB =  "<div class='tableFix'>
                        <table class='table table-sm'>
                            <tbody style='font-size: 13px;'>";
            while($ItemList = mysqli_fetch_array($getItemList)) {
                $TB .= "<tr>".
                            "<td>".
                                "<div class='d-flex align-items-center'>".
                                    "<div class='fw-bold text-black'>".
                                        "<span>".$ItemList['ItemCode']."</span>".
                                    "</div>".
                                "</div>".
                                "<div class=''>".
                                    "<div>".
                                        "<span style='font-size: 11.5px;'>".$ItemList['ItemName']."</span>".
                                    "</div>".
                                "</div>".
                            "</td>".
                            "<td width='30%'>".
                                "<div class='d-flex align-items-center text-primary'>".
                                    "<a href='restooked.php?itemcode=".$ItemList['ItemCode']."'><i class='far fa-plus-square'></i>&nbsp;เลือก</a>".
                                "</div>".
                            "</td>".
                        "</tr>";
            }
            $TB .= "        </tbody>
                        </table>
                    </div>";

            $arrCol['TB'] = $TB;
        }
    }

    $arrCol['FindData'] = $FindData;
    $arrCol['chkItem'] = $chkItem;
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>