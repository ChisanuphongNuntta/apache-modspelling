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

function notify_message($message){
    //$token = "okPm42wUsply17yZAE8iEHLndSrzjWGPLS1iAQqlTxq";// Login Notify
    $token = "hqHAIrjNKpyLxr55bOpvfC9kWtV9bMj6j6NKe7drVI9";
    
    $queryData = array('message' => $message);
    $queryData = http_build_query($queryData,'','&');
    $headerOptions = array( 
            'http'=>array(
               'method'=>'POST',
               'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                         ."Authorization: Bearer ".$token."\r\n"
                         ."Content-Length: ".strlen($queryData)."\r\n",
               'content' => $queryData
            ),
    );
    $context = stream_context_create($headerOptions);
    $result = file_get_contents(LINE_API,FALSE,$context);
    $res = json_decode($result);
    return ($res);
}

if($_GET['a'] == 'Call') {
    $sqlList = "SELECT T0.DocNum, T1.uName, T1.uNickName, T0.Status 
                FROM tranwhs T0 JOIN users T1 ON T0.ukeyCreate = T1.uKey 
                WHERE (curdate()<date_add(T0.DateCreate,interval 7 day)) 
                ORDER BY trnID DESC";
    $getList = MySQLSelectX($sqlList);
    $cx = 0;
    $tr = "";
    while($DataList = mysqli_fetch_array($getList)) {
        $cx++;
        switch($DataList['Status']) {
            case 0:
                $Status = "<i class='fas fa-ban fa-fw fa-1x'></i> เอกสารยกเลิก";
                $Color = "#f5f5f5";
                $text = "text-muted";
                break;
            case 1:
                $Status = "<i class='fas fa-file-alt fa-fw fa-1x'></i> เอกสารใหม่";
                $Color = "#fff";
                $text = "";
                break;
            case 2:
                $Status = "<i class='fas fa-clock fa-fw fa-1x'></i> เอกสารรออนุมัติ";
                $Color = "rgba(49, 112, 143, 0.29)";
                $text = "";
                break;
            case 3:
                $Status = "<i class='fas fa-check fa-fw fa-1x'></i> เอกสารอนุมัติเรียบร้อย";
                // $Color = "#fcf8e3";
                $Color = "#FFF3CD";
                $text = "";
                break;
            case 4:
                $Status = "<i class='fas fa-check fa-fw fa-1x'></i> เอกสารสมบูรณ์";
                $Color = "#E1FADB";
                $text = "";
                break;
        }
        $tr .= "<tr>".
                    "<td class='pb-0'>".
                        "<div class='ps-2 pe-2 pt-2 border border-1' style='border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px ".$Color."; background-color: ".$Color.";'>".
                            "<div class='d-flex justify-content-between'>".
                                "<div class='".$text."'>".
                                    "<span class='fw-bolder'>เลขที่ใบโอนย้าย</span> <a class='fw-bold' href='javascript:void(0);' onclick=\"TransFer('".$DataList['DocNum']."')\">".$DataList['DocNum']."</a>".
                                "</div>".
                            "</div>".
                        "</div>".
                        "<div class='p-2 border border-1' style='border-radius: 0px 0px 10px 10px; box-shadow: 1px 1px ".$Color.";'>".
                            "<div class='d-flex justify-content-aroundent'>".
                                "<div class='".$text."' style='width: 45%'>".
                                    "<span class='fw-bolder'>ผู้เบิก</span> <span>".$DataList['uName']." (".$DataList['uNickName'].")</span>".
                                "</div>".
                                "<div class='".$text."' style='width: 55%'>".
                                    "<span class='fw-bolder'>สถานะ:</span> <span>".$Status."</span>".
                                "</div>".
                            "</div>".
                        "</div>".
                    "</td>".
                "</tr>";
    }
    $arrCol['tr'] = $tr;
}

if($_GET['a'] == 'DelItem') {
    MySQLUpdate("UPDATE transecdata SET AppTran = 'C', ukeyCancel = '".$_SESSION['ukey']."', StatusTran = 0 WHERE tranSectId = '".$_POST['trnSecID']."'");
    $ItemList = MySQLSelect("SELECT WhsCode, LocationRack, QtyOut, ItemCode FROM transecdata WHERE tranSectId = '".$_POST['trnSecID']."'");
    $OnHand = MySQLSelect("SELECT ID, OnHand FROM oitw WHERE ItemCode = '".$ItemList['ItemCode']."' AND WhsCode = '".$ItemList['WhsCode']."' AND LocRack = '".$ItemList['LocationRack']."'");
    $RetunValue = $ItemList['QtyOut'] + $OnHand['OnHand'];
    MySQLUpdate("UPDATE oitw SET OnHand = '".$RetunValue."' WHERE ID = '".$OnHand['ID']."'");
    $output = "ลบรายการเรียบร้อยแล้ว";
    $arrCol['output'] = $output;
}

if($_GET['a'] == 'TransFer') {
    if($_POST['trnID'] == 'New') {
        $sqlNew = "SELECT DocNum FROM tranwhs ORDER BY DocNum DESC LIMIT 1";
        $MySQLdata = MySQLSelect($sqlNew);
        $yearAdd = substr((date('Y')+543),2);
        if(isset($MySQLdata['DocNum'])) {
            if($yearAdd == substr($MySQLdata['DocNum'],3,2)) {
                if (substr($MySQLdata['DocNum'],5,2) < date("m")){
                    $runNum = 1;
                }else{
                    $runNum = substr($MySQLdata['DocNum'],7)+1;
                }
                if ($runNum <= 9){
                    $docNum = "WH-".$yearAdd.date('m')."00".$runNum;
                }else{
                    if ($runNum<=99){
                        $docNum = "WH-".$yearAdd.date('m')."0".$runNum;
                    }else{
                        $docNum = "WH-".$yearAdd.date('m').$runNum;
                    }
                }
            }else{
                $docNum = "WH-".$yearAdd.date('m')."001";
            }
        }else{
            $docNum = "WH-".$yearAdd.date('m')."001";
        }
        $trnID = $docNum;
    }else{
        $trnID = $_POST['trnID'];
    }
    $arrCol['trnID'] = $trnID;
}

if($_GET['a'] == 'AddItem') {
    $LineSMS = "";
    $userShow = MySQLSelect("SELECT uName, uLastName, uNickName FROM users WHERE uKey = '".$_SESSION['ukey']."'");
    if ($_SESSION['LvCode'] == 'LV072' OR $_SESSION['LvCode'] == 'LV073'){
        $InAppKey = 1;
    }else{
        $InAppKey = 0;
    }
    switch ($_POST['fun']) {
        case 0:
            $ModalShow = 0;
            $arrCol['alert'] = "";
            $output = "";
            $target = 0;
            $chkID = CHKRowDB("SELECT * FROM tranwhs WHERE DocNum = '".$_POST['trnID']."'");
            if($chkID == 0) {
                MySQLInsert("INSERT INTO tranwhs SET DocNum = '".$_POST['trnID']."',ukeyCreate = '".$_SESSION['ukey']."', DateCreate = NOW()");
                $nameCrate = $_SESSION['uName']." (".$_SESSION['UserName'].")";
                $nameAPP = "  -  "; 
                $arrCol['stDOC'] = "1";
            }else{
                $sqlHead = "SELECT T0.DocNum, T0.sourceWHS, T0.TargetWHS, T1.uName, T1.uNickName, T0.Status,
                                T2.uName AS AppName, T2.uNickName AS AppNnick
                            FROM tranwhs T0
                                JOIN users T1 ON T1.uKey = T0.ukeyCreate
                                LEFT JOIN users T2 ON T2.uKey = T0.ukeyApp
                            WHERE T0.DocNum = '".$_POST['trnID']."'";
                $HeadData = MySQLSelect($sqlHead);     
                $target = $HeadData['TargetWHS'];  
                $nameCrate = $HeadData['uName']." (".$HeadData['uNickName'].")";   
                $nameAPP = "  -  ";  
                switch($HeadData['Status']) {
                    case 0 :
                        $textHead = "เอกสารยกเลิก";
                        $nameAPP = $HeadData['AppName']." (".$HeadData['AppNnick'].")";
                        break;
                    case 1 :
                        $textHead = "เอกสารใหม่";
                        break;
                    case 2 :
                        $textHead = "เอกสารรออนุมัติ";
                        break;
                    case 3 :
                        $textHead = "เอกสารอนุมัติแล้ว";
                        $nameAPP = $HeadData['AppName']." (".$HeadData['AppNnick'].")";
                        break;
                    case 4 :
                        $textHead = "เอกสารสมบูรณ์";
                        $nameAPP = $HeadData['AppName']." (".$HeadData['AppNnick'].")";
                    break;
                }

                if ($HeadData['sourceWHS'] != "" AND $HeadData['sourceWHS'] != ""){
                    $textHead .= " ย้ายจาก ".$HeadData['sourceWHS']." ไปยัง ".$HeadData['TargetWHS'];
                }
                $arrCol['stDOC'] = $HeadData['Status'];
                $arrCol['textH'] = $textHead;

                $sqlList = "SELECT T0.tranSectID,T3.ItemCode,T3.ItemName,T0.WhsCode,T0.LocationRack,T0.QtyOut,T0.StatusTran,T0.AppTran
                            FROM transecdata T0
                            JOIN oitm T3 ON T0.ItemCode = T3.ItemCode
                            WHERE T0.trnCode = '".$_POST['trnID']."'
                            ORDER BY T0.tranSectID DESC";
                $getList = MySQLSelectX($sqlList);
                $getList_Row = 0;
                while($ItemData = mysqli_fetch_array($getList)) {
                    $getList_Row++;
                    $tranSectID = "";
                    $tranSectID = $ItemData['tranSectID'];
                    if ($ItemData['AppTran'] == 'C'){
                        $delRow = "color : #C1C1C1;";
                    }else{
                        $delRow = "";
                    }
                    $output .= "<tr style='".$delRow."'>";
                        $output .= "<td>";
                            $output .= "<div class='d-flex align-items-center'>";
                                $output .= "<a style='width: 30%;' class='fw-bold' data-bs-toggle='collapse' href='#CollaT_".$ItemData['tranSectID']."' role='button' aria-expanded='false' aria-controls='CollaT_".$ItemData['tranSectID']."'>".$ItemData['ItemCode']."</a>";
                                $output .= "<span class='fw-bold' style='width: 30%;'>";
                                    $output .= "คลัง <span class='fw-bold text-primary'>".$ItemData['WhsCode']."</span>";
                                $output .= "</span>";
                                $output .= "<span class='fw-bold' style='width: 30%;'>";
                                    $output .= "จำนวน <span class='fw-bold text-primary'>".number_format($ItemData['QtyOut'])."</span>";
                                $output .= "</span>";
                            if($HeadData['Status'] == 1 OR ($HeadData['Status'] == 2 AND $InAppKey == 1) AND $ItemData['AppTran'] != 'C') {
                                $output .= "<a style='width: 10%; color: #777;' href='javascript:void(0);' onclick=\"DelList('".$ItemData['tranSectID']."','".$ItemData['ItemName']."')\">";
                                    $output .= "<i class='fas fa-trash text-danger'></i>";
                                $output .= "</a>";
                            }else{
                                $output .= "<a style='width: 10%; color: #777;' href='javascript:void(0);'>";
                                    $output .= "<i class='fas fa-trash text-muted'></i>";
                                $output .= "</a>";
                            }
                            $output .= "</div>";

                            $output .= "<div class='d-flex align-items-center justify-content-between' style='padding-top: 2px;'>";
                                $output .= "<span>".$ItemData['ItemName']."</span>";
                            $output .= "</div>";
                            $output .= "<div class='collapse' id='CollaT_".$ItemData['tranSectID']."' style='background-color: #e6eaee; border-radius: 0px 0px 5px 5px;'>";
                                $output .= "<div class='d-flex align-items-center ps-2 pe-2'>";
                                    $output .= "<span class='fw-bold' style='width: 60%;'>";
                                        $output .= "ชั้นวาง <span class='fw-bold text-primary'>".$ItemData['LocationRack']."</span>";
                                    $output .= "</span>";
                                    $output .= "<span class='fw-bold' style='width: 40%;'>";
                                        $output .= "จำนวน <span class='fw-bold text-primary'>".number_format($ItemData['QtyOut'])."</span>";
                                    $output .= "</span>";
                                $output .= "</div>";
                            $output .= "</div>";
                        $output .= "</td>";
                    $output .= "</tr>";
                }
                // echo $getList_Row;
                if($getList_Row == 0) {
                    $output .= "<tr style=''>
                                    <td class='text-center'>ไม่มีรายการ</td>
                                </tr>";
                }
            }
            $arrCol['nameTRN'] = $nameCrate;
            $arrCol['AppTRN'] = $nameAPP;
            $arrCol['tg'] = $target;
            break;
        case 1:
            $mySQL ="SELECT T0.ItemCode,T1.ItemName,T0.LocRack,T0.OnHand 
                    FROM oitw T0
                        LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
                    WHERE (T1.ItemCode = '".$_POST['ItemCode']."' OR T1.ItemName = '".$_POST['ItemCode']."' OR T1.BarCode = '".$_POST['ItemCode']."' OR T1.BarCode2 = '".$_POST['ItemCode']."' OR T1.BarCode3 = '".$_POST['ItemCode']."') AND
                            OnHand != 0 AND LocRack = '".$_POST['LocRack']."'";
            $getItem = MySQLSelectX($mySQL);
            $cx = 0;
            while($ItemData = mysqli_fetch_array($getItem)) {
                $cx++;
                $ItemCode[$cx] = $ItemData['ItemCode'];
                $ItemName[$cx] = $ItemData['ItemName'];
            }
            switch ($cx) {
                case 0 : // ไม่พบสินค้า
                    $ModalShow = 1;
                    $alert ="<tr>
                                <td class='text-center'>ไม่พบสินค้าในชั้นวางนี้</td>
                            </tr>";
                    $arrCol['alert']  = $alert;
                    break;
                case 1:
                    $ModalShow = 2;
                    $ShowItem = "<tr class='RowAdd'>
                                    <td class='text-left fw-bold' style='color : #000; background-color: #b3d9ff; border-radius: 5px 5px 5px 5px;'>
                                        <div class='d-flex align-items-center' >
                                            <span style='width: 30%;'>".$ItemCode[$cx]."</span>
                                            <span style='width: 70%;'>".$ItemName[1]."</span>
                                        </div>
                                    </td>
                                </tr>";
                    $DataShowItem = "<tr class='RowAdd'>
                                    <td class='text-left fw-bold' style='color : #000; background-color: #b3d9ff; border-radius: 5px 5px 5px 5px;'>
                                        <div class='d-flex align-items-center' >
                                            <span style='width: 30%;'>".$ItemCode[$cx]."</span>
                                            <span style='width: 70%;'>".$ItemName[1]." <i class='fas fa-check text-success'></i></span>
                                        </div>
                                    </td>
                                </tr>";
                    // $arrCol['RowAdd'] = $output;
                    $sqlList = "SELECT T0.tranSectID,T3.ItemCode,T3.ItemName,T0.WhsCode,T0.QtyOut,T0.StatusTran,T0.AppTran,T0.LocationRack
                            FROM transecdata T0
                            JOIN oitm T3 ON T0.ItemCode = T3.ItemCode
                            WHERE T0.trnCode = '".$_POST['trnID']."'
                            ORDER BY T0.tranSectID DESC";
                    $getList = MySQLSelectX($sqlList);
                    $getList_Row = 0;
                    while($ItemData = mysqli_fetch_array($getList)) {
                        $getList_Row++;
                        if ($ItemData['AppTran'] == 'C'){
                            $delRow = "color : #C1C1C1;";
                        }else{
                            $delRow = "";
                        }
                        $output .= "<tr style='".$delRow."'>
                                        <td>
                                            <div class='d-flex align-items-center'>
                                                <a style='width: 30%;' class='fw-bold' data-bs-toggle='collapse' href='#CollaT_".$ItemData['tranSectID']."' role='button' aria-expanded='false' aria-controls='CollaT_".$ItemData['tranSectID']."'>".$ItemData['ItemCode']."</a>
                                                <span class='fw-bold' style='width: 30%;'>คลัง <span class='fw-bold text-primary'>".$ItemData['WhsCode']."</span></span>
                                                <span class='fw-bold' style='width: 30%;'>จำนวน <span class='fw-bold text-primary'>".number_format($ItemData['QtyOut'])."</span></span>";
                                    if($ItemData['AppTran'] != 'C') {
                                        $output .= "<a style='width: 10%; color: #777;' href='javascript:void(0);' onclick=\"DelList('".$ItemData['tranSectID']."','".$ItemData['ItemName']."')\"><i class='fas fa-trash text-danger'></i></a>";
                                    }else{
                                        $output .= "<a style='width: 10%; color: #777;' href='javascript:void(0);'><i class='fas fa-trash text-muted'></i></a>";
                                    }
                                $output .= "</div>
                                            <div class='d-flex align-items-center justify-content-between' style='padding-top: 2px;'>
                                                <span>".$ItemData['ItemName']."</span>
                                            </div>
                                            <div class='collapse' id='CollaT_".$ItemData['tranSectID']."' style='background-color: #e6eaee; border-radius: 0px 0px 5px 5px;'>
                                                <div class='d-flex align-items-center ps-2 pe-2'>";
                                        $output .= "<span class='fw-bold' style='width: 60%;'>ชั้นวาง <span class='fw-bold text-primary'>".$ItemData['LocationRack']."</span></span>
                                                    <span class='fw-bold' style='width: 40%;'>จำนวน <span class='fw-bold text-primary'>".number_format($ItemData['QtyOut'])."</span></span>";
                                    $output .= "</div>
                                            </div>
                                        </td>
                                    </tr>";
                    }
                    $arrCol['ShowItem'] = $ShowItem;
                    $arrCol['DataShowItem'] = $DataShowItem;
                    break;
                default : // พบสินค้ามากกว่า 1 รายการ
                    $ModalShow = 3;
                    $MySQL = "SELECT ItemCode,ItemName FROM oitm WHERE ItemCode = '".$_POST['ItemCode']."' OR BarCode = '".$_POST['ItemCode']."' OR BarCode2 = '".$_POST['ItemCode']."' OR BarCode3 = '".$_POST['ItemCode']."' OR ItemName Like '%".$_POST['ItemCode']."%' AND Status != 'I'";
                    $getItemList = MySQLSelectX($MySQL);
                    $SeItem = "";
                    while($ItemList = mysqli_fetch_array($getItemList)) {
                        $SeItem .="<tr>
                                    <td width='38%' class='fw-bold text-center' style=''><input class='form-check-input' type='radio' name='BoxItem' id='BoxItem' value='".$ItemList['ItemCode']."'>&nbsp&nbsp".$ItemList['ItemCode']."</td>
                                    <td class='fw-bold' style=''>".$ItemList['ItemName']."</td>
                                </tr>";
                    }
                    $arrCol['SeItem1'] = $SeItem;
                    break;
            }
            break;
        case 3: //เพิ่มจำนวน
            $ModalShow = 4;
            $WhsData = MySQLSelect("SELECT WhsCode FROM allwhs WHERE LocationRack = '".$_POST['LocRack']."'");
            // $getItem = MySQLSelectX("SELECT ItemCode FROM oitm WHERE (ItemCode = '".$_POST['ItemCode']."' OR BarCode = '".$_POST['ItemCode']."' OR BarCode2 = '".$_POST['ItemCode']."' OR BarCode3 = '".$_POST['ItemCode']."') AND OnHand != 0 AND LocRack = '".$_POST['LocRack']."' AND ItemStatus != 'I'");
            $mySQL =
                "SELECT T0.ItemCode,T1.ItemName,T0.LocRack,T0.OnHand 
                FROM oitw T0
                LEFT JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
                WHERE (T1.ItemCode = '".$_POST['ItemCode']."' OR T1.ItemName = '".$_POST['ItemCode']."' OR T1.BarCode = '".$_POST['ItemCode']."' OR T1.BarCode2 = '".$_POST['ItemCode']."' OR T1.BarCode3 = '".$_POST['ItemCode']."') 
                    AND T0.OnHand != 0 AND T0.LocRack = '".$_POST['LocRack']."'";
            $getItem = MySQLSelectX($mySQL);
            $rw = 0;
            while($ItemData = mysqli_fetch_array($getItem)) {
                $rw++;
                $ItemCode[$rw] = $ItemData['ItemCode'];
            }

            switch($rw) {
                case 0:
                    $ModalShow = 1;
                    $alert ="<tr>
                                <td class='text-center'>ไม่พบสินค้าในชั้นวางนี้</td>
                            </tr>";
                    $arrCol['alert']  = $alert;
                    break;
                case 1:
                    $ItemQTY = MySQLSelect("SELECT OnHand FROM oitw WHERE ItemCode = '".$ItemCode[$rw]."' AND LocRack = '".$_POST['LocRack']."'");
                    if ($ItemQTY['OnHand'] >= $_POST['Qty']) {
                        $newOnHand = ConToInt($ItemQTY['OnHand']) - ConToInt($_POST['Qty']);
                        $QtyTRN = MySQLSelect("SELECT QtyOut FROM transecdata WHERE LocationRack = '".$_POST['LocRack']."'AND ItemCode = '".$ItemCode[$rw]."' AND  StatusTran = 1 AND trnCode = '".$_POST['trnID']."' AND AppTran = 'Y'");
                        if(isset($QtyTRN['QtyOut'])){
                            $QtyTRN['QtyOut'] = $QtyTRN['QtyOut'];
                        }else{
                            $QtyTRN['QtyOut'] = 0;
                        }
                        if ($QtyTRN['QtyOut'] >= 1) {
                            $NewQty = $QtyTRN['QtyOut'] + $_POST['Qty']; 
                            MySQLUpdate("UPDATE transecdata SET QtyOut = '".$NewQty."', DateCreate = NOW() WHERE LocationRack = '".$_POST['LocRack']."'AND ItemCode = '".$ItemCode[$rw]."' AND  StatusTran = 1 AND trnCode = '".$_POST['trnID']."'");
                        }else{
                            MySQLInsert("INSERT INTO transecdata SET WhsCode = '".$WhsData['WhsCode']."', LocationRack = '".$_POST['LocRack']."', ItemCode = '".$ItemCode[$rw]."', QtyOut = '".$_POST['Qty']."', DateCreate = NOW(), ukeyUpdate = '".$_SESSION['ukey']."', StatusTran = 1, trnCode = '".$_POST['trnID']."'");
                        }

                        MySQLUpdate("UPDATE oitw SET OnHand = '".$newOnHand."' WHERE ItemCode = '".$ItemCode[$rw]."' AND LocRack = '".$_POST['LocRack']."'");
                        $sqlList = "SELECT T0.tranSectID, T0.ItemCode, T1.ItemName, T0.WhsCode, T0.QtyOut, T0.StatusTran, T0.AppTran, T0.LocationRack
                                    FROM transecdata T0
                                        JOIN oitm T1 ON T0.ItemCode = T1.ItemCode
                                    WHERE T0.trnCode = '".$_POST['trnID']."'
                                    ORDER BY T0.tranSectID DESC";
                                    // echo $sqlList;
                        $getList = MySQLSelectX($sqlList);
                        while($ItemData = mysqli_fetch_array($getList)) {
                            if ($ItemData['AppTran'] == 'C'){
                                $delRow = "color : #C1C1C1;";
                            }else{
                                $delRow = "";
                            }
                            $output .= "<tr style='".$delRow."'>
                                        <td>
                                            <div class='d-flex align-items-center'>
                                                <a style='width: 30%;' class='fw-bold' data-bs-toggle='collapse' href='#CollaT_".$ItemData['tranSectID']."' role='button' aria-expanded='false' aria-controls='CollaT_".$ItemData['tranSectID']."'>".$ItemData['ItemCode']."</a>
                                                <span class='fw-bold' style='width: 30%;'>คลัง <span class='fw-bold text-primary'>".$ItemData['WhsCode']."</span></span>
                                                <span class='fw-bold' style='width: 30%;'>จำนวน <span class='fw-bold text-primary'>".number_format($ItemData['QtyOut'])."</span></span>";
                                    if($ItemData['AppTran'] != 'C') {
                                        $output .= "<a style='width: 10%; color: #777;' href='javascript:void(0);' onclick=\"DelList('".$ItemData['tranSectID']."','".$ItemData['ItemName']."')\"><i class='fas fa-trash text-danger'></i></a>";
                                    }else{
                                        $output .= "<a style='width: 10%; color: #777;' href='javascript:void(0);'><i class='fas fa-trash text-muted'></i></a>";
                                    }
                                $output .= "</div>
                                            <div class='d-flex align-items-center justify-content-between' style='padding-top: 2px;'>
                                                <span>".$ItemData['ItemName']."</span>
                                            </div>
                                            <div class='collapse' id='CollaT_".$ItemData['tranSectID']."' style='background-color: #e6eaee; border-radius: 0px 0px 5px 5px;'>
                                                <div class='d-flex align-items-center ps-2 pe-2'>";
                                        $output .= "<span class='fw-bold' style='width: 60%;'>ชั้นวาง <span class='fw-bold text-primary'>".$ItemData['LocationRack']."</span></span>
                                                    <span class='fw-bold' style='width: 40%;'>จำนวน <span class='fw-bold text-primary'>".number_format($ItemData['QtyOut'])."</span></span>";
                                    $output .= "</div>
                                            </div>
                                        </td>
                                    </tr>";
                        }
                    }else{
                        $ModalShow = 1;
                        $alert ="<tr>
                                    <td class='text-center'>จำนวนสินค้าในชั้นนี้ไม่พอ</td>
                                </tr>";
                        $arrCol['alert']  = $alert;
                    }
                    break;
                default :
                    $ModalShow = 3;
                    $MySQL = "SELECT ItemCode,ItemName FROM oitm WHERE ItemCode = '".$_POST['ItemCode']."' OR BarCode = '".$_POST['ItemCode']."' OR BarCode2 = '".$_POST['ItemCode']."' OR BarCode3 = '".$_POST['ItemCode']."' OR ItemName Like '%".$_POST['ItemCode']."%' AND ItemStatus != 'I'";
                    $getItemList = MySQLSelectX($MySQL);
                    $SeItem = "";
                    while($ItemList = mysqli_fetch_array($getItemList)) {
                        $SeItem .=
                            "<tr>
                                <td width='38%' class='fw-bold text-center' style=''><input class='form-check-input' type='radio' name='BoxItem' id='BoxItem' value='".$ItemList['ItemCode']."'>&nbsp&nbsp".$ItemList['ItemCode']."</td>
                                <td class='fw-bold' style=''>".$ItemList['ItemName']."</td>
                            </tr>";
                    }
                    $arrCol['SeItem2'] = $SeItem;
                    break;
            }
            break;
        case 4:
            $ModalShow = 5;
            $WhsData = MySQLSelect("SELECT DISTINCT WhsCode FROM transecdata WHERE trnCode = '".$_POST['trnID']."'");
            if($_POST['targetX'] == "") {
                $arrCol['alert'] = 0;
            }else{
                if(isset($WhsData['WhsCode'])) {
                    $arrCol['alert'] = 1; 
                    MySQLUpdate("UPDATE tranwhs SET sourceWHS = '".$WhsData['WhsCode']."', TargetWHS = '".$_POST['targetX']."', Status = 2, LastUpdate = NOW() WHERE DocNum = '".$_POST['trnID']."'");
                    $LineSMS .= "\nเรื่อง   : `โอนย้ายสินค้าไปยังคลัง ".$_POST['targetX']."`";
                    $LineSMS .= "\nเลขที่เอกสาร : `".$_POST['trnID']."`";
                    $LineSMS .= "\nการดำเนินการ : `บันทึกการโอนย้าย รออนุมัติ`";
                    $LineSMS .= "\nผู้บันทึก : `".$userShow['uName']." ".$userShow['uLastName']." (".$userShow['uNickName'].")`";
                    $LineSMS .= "\nEurox Force : http://www.euroxforce.com:8080/ksy";
                    // notify_message($LineSMS);
                }else{
                    $arrCol['alert'] = 2; 
                }
            }
            break;
        case 5:
            $ModalShow = 5;
            MySQLUpdate("UPDATE tranwhs SET Status = 3, DateApp = NOW(), ukeyApp = '".$_SESSION['ukey']."' WHERE DocNum = '".$_POST['trnID']."'");
            $alert = "อนุมัติการโอนยายเรียบร้อย";
            $arrCol['alert'] = $alert;
            $LineSMS .= "\nเรื่อง   : `โอนย้ายสินค้าไปยังคลัง`";
            $LineSMS .= "\nเลขที่เอกสาร : `".$_POST['trnID']."`";
            $LineSMS .= "\nการดำเนินการ : `อนุมัติการโอนยายเรียบร้อย`";
            $LineSMS .= "\nผู้บันทึก : `".$userShow['uName']." ".$userShow['uLastName']." (".$userShow['uNickName'].")`";
            $LineSMS .= "\nEurox Force : http://www.euroxforce.com:8080/ksy";
            // notify_message($LineSMS);
            break;
        case 6 :
            $ModalShow = 5;
            MySQLUpdate("UPDATE tranwhs SET Status = 0, DateApp = NOW(), ukeyApp = '".$_SESSION['ukey']."' WHERE DocNum = '".$_POST['trnID']."'");
            $alert = "ยกเลิกเอกสารโอนย้ายเรียบร้อย";
            $arrCol['alert'] = $alert;
            $LineSMS .= "\nเรื่อง   : `โอนย้ายสินค้าไปยังคลัง`";
            $LineSMS .= "\nเลขที่เอกสาร : `".$_POST['trnID']."`";
            $LineSMS .= "\nการดำเนินการ : `ยกเลิกเอกสารโอนย้ายเรียบร้อย`";
            $LineSMS .= "\nผู้บันทึก : `".$userShow['uName']." ".$userShow['uLastName']." (".$userShow['uNickName'].")`";
            $LineSMS .= "\nEurox Force : http://www.euroxforce.com:8080/ksy";
            // notify_message($LineSMS);
            break;
    }
    $arrCol['Modal'] = $ModalShow;
    $arrCol['output'] = $output;
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>