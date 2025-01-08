<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
$OLDCON = mysqli_connect("192.168.1.12","kbi","passw0rd!","kbidb");
$NEWCON = mysqli_connect("localhost","kbi","p@ssw0rd!","kbidb");
echo "<style> body { background-color: #000; color: #00FF00; font-size: 14px; } a { color: #FFF; }</style>";
echo "<pre>";
echo "<h1>TRANSFER DATA WIZARD - TRANSFER PICK AND PACK</h1>";

/* STEP 1 */
$OLDSQL = "SELECT T0.* FROM picker_soheader T0 WHERE (YEAR(T0.DateCreate) = 2022 AND MONTH(T0.DateCreate) = 12) AND T0.StatusDoc BETWEEN 9 AND 13";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
$SODoc = "(";
$WODoc = "(";
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {

        if($OLDRST['DocType'] == "ORDR") {
            $SODoc .= $OLDRST['SODocEntry'].",";
        } else {
            $WODoc .= $OLDRST['SODocEntry'].",";
        }
        
        $InsertSQL =
            "INSERT INTO picker_soheader SET
                OLD_ID = ".$OLDRST['ID'].",
                SODocEntry = ".$OLDRST['SODocEntry'].",
                DocNum = '".$OLDRST['DocNum']."',
                QQst = '".$OLDRST['QQst']."',
                DocType = '".$OLDRST['DocType']."',
                DocDate = '".$OLDRST['DocDate']."',
                OriDate = '".$OLDRST['OriDate']."',
                OriTime = ".$OLDRST['OriTime'].",
                DocDueDate = '".$OLDRST['DocDueDate']."',
                CardCode = '".$OLDRST['CardCode']."', 
                CardName = '".$OLDRST['CardName']."',
                DateCreate = '".$OLDRST['DateCreate']."',
                TimeType = '".$OLDRST['TimeType']."',
                DatePick = '".$OLDRST['DatePick']."',
                TimePick = '".$OLDRST['TimePick']."',
                ItemCount = ".$OLDRST['ItemCount'].", 
                TeamCode = '".$OLDRST['TeamCode']."',
                SlpCode = '".$OLDRST['SlpCode']."', 
                UkeyPicker = '".$OLDRST['UkeyPicker']."',
                StartPick = '".$OLDRST['StartPick']."', 
                UkeyCUT1 = '".$OLDRST['UkeyCUT1']."', 
                TimeCUT1 = '".$OLDRST['TimeCUT1']."', 
                UkeyCUT2 = '".$OLDRST['UkeyCUT2']."', 
                TimeCUT2 = '".$OLDRST['TimeCUT2']."', 
                PickedDate = '".$OLDRST['PickedDate']."', 
                LastUpdate = '".$OLDRST['LastUpdate']."', 
                LastUkey = '".$OLDRST['LastUkey']."', 
                OpenBill = '".$OLDRST['OpenBill']."', 
                TablePacking = ".$OLDRST['TablePacking'].", 
                PackID = '".$OLDRST['PacKID']."', 
                PackDate = '".$OLDRST['PackDate']."', 
                TimePack = '".$OLDRST['TimePack']."', 
                UkeyOpen = '".$OLDRST['UkeyOpen']."', 
                DateOpen = '".$OLDRST['DateOpen']."', 
                StatusDoc = ".$OLDRST['StatusDoc'].", 
                BillLoy = ".$OLDRST['BillLoy']."";
        // echo $InsertSQL."<br/>";
        $InsertID = 0;
        $InsertID = MySQLInsert($InsertSQL);
        if($InsertID != 0) {
            $ADDROW++;
        } else {
            array_push($ERRID,$InsertSQL);
        }
    }
    $SODoc = substr($SODoc,0,-1).")";
    $WODoc = substr($WODoc,0,-1).")";


    if($OLDROW == $ADDROW) {
        echo "1. Import Into picker_soheader Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "1. Import Into picker_soheader Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
        echo "<ol>";
        for($i=0;$i<count($ERRID);$i++) {
            echo "<li>$ERRID[$i]</li>";
        }
        echo "</ol>";
        exit;
    }
}

/* STEP 2 */
$OLDSQL = "SELECT T1.*, (SELECT COUNT(P0.ID) FROM picker_sodetail P0 WHERE P0.DocEntry = T0.SODocEntry AND P0.DocType = T0.DocType) AS 'RowCount' FROM picker_soheader T0 LEFT JOIN picker_sodetail T1 ON T0.SODocEntry = T1.DocEntry AND T0.DocType = T1.DocType WHERE (YEAR(T0.DateCreate) = 2022 AND MONTH(T0.DateCreate) = 12) AND T0.StatusDoc BETWEEN 9 AND 13";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {
        if($OLDRST['ID'] != "") {
            $InsertSQL = 
                "INSERT INTO picker_sodetail SET
                    DocEntry = '".$OLDRST['DocEntry']."',
                    DocType = '".$OLDRST['DocType']."',
                    VisOrder = ".$OLDRST['VisOrder'].",
                    ItemCode = '".$OLDRST['ItemCode']."',
                    BarCode = '".$OLDRST['BarCode']."',
                    ItemName = '".$OLDRST['ItemName']."',
                    WhsCode = '".$OLDRST['WhsCode']."',
                    Qty = ".$OLDRST['Qty'].",
                    OpenQty = ".$OLDRST['OpenQty'].",
                    Remark = '".$OLDRST['Remark']."',
                    Status = ".$OLDRST['Status'].",
                    WaitOP = ".$OLDRST['WaitOP'].",
                    BomItem = ".$OLDRST['BomItem'].",
                    LastRead = '".$OLDRST['LastRead']."'";
            // echo $InsertSQL."<br/>";
            $InsertID = 0;
            $InsertID = MySQLInsert($InsertSQL);
            if($InsertID != 0) {
                $ADDROW++;
            } else {
                array_push($ERRID,$InsertSQL);
            }
        } else {
            echo $OLDSQL."<br>";
        }
    }
    if($OLDROW == $ADDROW) {
        echo "2. Import Into picker_sodetail Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "2. Import Into picker_sodetail Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
        echo "<ol>";
        for($i=0;$i<count($ERRID);$i++) {
            echo "<li>$ERRID[$i]</li>";
        }
        echo "</ol>";
    }
}

/* STEP 3 PACK_HEADER */
$SAPIVSQL = "SELECT DISTINCT T0.TrgetEntry AS 'BillEntry', CASE WHEN T0.TargetType = 13 THEN 'OINV' ELSE 'ODLN' END AS 'BillType' FROM RDR1 T0 WHERE T0.DocEntry IN $SODoc AND T0.TrgetEntry IS NOT NULL";
$SAPIVQRY = conSAP8($SAPIVSQL);
$OINVDoc = "(";
$ODLNDoc = "(";
$OWAXDoc = "(";
while($SAPIVRST = odbc_fetch_array($SAPIVQRY)) {
    if($SAPIVRST['BillType'] == "OINV") {
        $OINVDoc .= $SAPIVRST['BillEntry'].",";
    } else {
        $ODLNDoc .= $SAPIVRST['BillEntry'].",";
    }
}

$OINVDoc = substr($OINVDoc,0,-1).")";
$ODLNDoc = substr($ODLNDoc,0,-1).")";

$OLDSQL = "SELECT T0.* FROM pack_header T0 WHERE (T0.BillEntry IN $OINVDoc AND T0.BillType = 'OINV') OR (T0.BillEntry IN $ODLNDoc AND T0.BillType = 'ODLN') OR (T0.BillEntry IN $WODoc AND T0.BillType LIKE 'OWA%')";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {
        $OLDID = "SELECT T0.ID, T0.OLD_ID FROM picker_soheader T0 WHERE T0.OLD_ID = ".$OLDRST['IDPick'];
        $OLDIDRST = MySQLSelect($OLDID) or die("Get PickHeader ID");
        $NewID = $OLDIDRST['ID'];
        // $NewID = $OLDRST['IDPick'];
        $InsertSQL =
            "INSERT INTO pack_header SET
                OLD_HeadID = ".$OLDRST['ID'].",
                IDPick = ".$NewID.",
                BillEntry = ".$OLDRST['BillEntry'].",
                BillType = '".$OLDRST['BillType']."',
                DocNum = '".$OLDRST['DocNum']."',
                CardCode = '".$OLDRST['CardCode']."',
                Comment = '".$OLDRST['Comment']."',
                TotalPack = ".$OLDRST['TotalPack'].",
                TablePack = ".$OLDRST['TablePack'].",
                DateFinish = '".$OLDRST['DateFinish']."',
                ukeyCreate1 = '".$OLDRST['ukeyCreate1']."',
                ukeyCreate2 = '".$OLDRST['ukeyCreate2']."',
                DateCreate = '".$OLDRST['DateCreate']."',
                Status = '".$OLDRST['Status']."',
                Logi = '".$OLDRST['Logi']."'";
        // echo $InsertSQL."<br/>";
        $InsertID = 0;
        $InsertID = MySQLInsert($InsertSQL);
        if($InsertID != 0) {
            $ADDROW++;
        } else {
            array_push($ERRID,$InsertSQL);
        }
    }
    if($OLDROW == $ADDROW) {
        echo "3. Import Into pack_header Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "3. Import Into pack_header Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
        echo "<ol>";
        for($i=0;$i<count($ERRID);$i++) {
            echo "<li>$ERRID[$i]</li>";
        }
        echo "</ol>";
        exit;
    }
}

/* STEP 4 PACK_BOXLIST */
$OLDSQL = "SELECT T0.* FROM pack_boxlist T0 WHERE (T0.BillEntry IN $OINVDoc AND T0.BillType = 'OINV') OR (T0.BillEntry IN $ODLNDoc AND T0.BillType = 'ODLN') OR (T0.BillEntry IN $WODoc AND T0.BillType LIKE 'OWA%')";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {
        $InsertSQL =
            "INSERT INTO pack_boxlist SET
                Retails = '".$OLDRST['Retails']."',
                BillEntry = ".$OLDRST['BillEntry'].",
                BillType = '".$OLDRST['BillType']."',
                BoxCode = '".$OLDRST['BoxCode']."',
                BoxNo = ".$OLDRST['BoxNo'].",
                DateCreate = '".$OLDRST['DateCreate']."',
                DateEdit = '".$OLDRST['DateEdit']."',
                TableCreate = ".$OLDRST['TableCreate'].",
                TotalItem = ".$OLDRST['TotalItem'].",
                BillInBoxc = ".$OLDRST['BillInBoxc'].",
                Status = '".$OLDRST['Status']."',
                ShipToCode = '".$OLDRST['ShipToCode']."'";
        // echo $InsertSQL."<br/>";
        $InsertID = 0;
        $InsertID = MySQLInsert($InsertSQL);
        if($InsertID != 0) {
            $ADDROW++;
        } else {
            array_push($ERRID,$InsertSQL);
        }        
    }
    if($OLDROW == $ADDROW) {
        echo "4. Import Into pack_boxlist Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "4. Import Into pack_boxlist Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
        echo "<ol>";
        for($i=0;$i<count($ERRID);$i++) {
            echo "<li>$ERRID[$i]</li>";
        }
        echo "</ol>";
        exit;
    }
}

/* STEP 5 PACK_TRAN */
$OLDSQL = "SELECT T0.* FROM pack_tran T0 WHERE (T0.BillEntry IN $OINVDoc AND T0.BillType = 'OINV') OR (T0.BillEntry IN $ODLNDoc AND T0.BillType = 'ODLN') OR (T0.BillEntry IN $WODoc AND T0.BillType LIKE 'OWA%')";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {
        $InsertSQL =
            "INSERT INTO pack_tran SET
                BillEntry = ".$OLDRST['BillEntry'].",
                BillType = '".$OLDRST['BillType']."',
                BoxCode = '".$OLDRST['BoxCode']."',
                BoxNo = ".$OLDRST['BoxNo'].",
                ItemCode = '".$OLDRST['ItemCode']."',
                BarCode = '".$OLDRST['BarCode']."',
                Qty = ".$OLDRST['Qty'].",
                PackTable = ".$OLDRST['PackTable'].",
                DateCreate = '".$OLDRST['DateCreate']."',
                DateUpdate = '".$OLDRST['DateUpdate']."',
                Status = '".$OLDRST['Status']."'";
        // echo $InsertSQL."<br/>";
        $InsertID = 0;
        $InsertID = MySQLInsert($InsertSQL);
        if($InsertID != 0) {
            $ADDROW++;
        } else {
            array_push($ERRID,$InsertSQL);
        }        
    }
    if($OLDROW == $ADDROW) {
        echo "5. Import Into pack_tran Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "5. Import Into pack_tran Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
        echo "<ol>";
        for($i=0;$i<count($ERRID);$i++) {
            echo "<li>$ERRID[$i]</li>";
        }
        echo "</ol>";
        exit;
    }
}

/* STEP 6 PACK_LIST */
$OLDSQL = "SELECT T1.* FROM pack_header T0 LEFT JOIN pack_list T1 ON T0.ID = T1.HeadID WHERE (T0.BillEntry IN $OINVDoc AND T0.BillType = 'OINV') OR (T0.BillEntry IN $ODLNDoc AND T0.BillType = 'ODLN') OR (T0.BillEntry IN $WODoc AND T0.BillType LIKE 'OWA%')";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {
        $OLDHeadSQL = "SELECT T0.ID FROM pack_header T0 WHERE T0.OLD_HeadID = ".$OLDRST['HeadID'];
        $OLDRow = ChkRowDB($OLDHeadSQL);
        if($OLDRow > 0) {
        $OLDHeadRST = MySQLSelect($OLDHeadSQL) or die("Get PackHeader ID");
        $NewID =  $OLDHeadRST['ID'];
        // $NewID =  $OLDRST['HeadID'];
        } else {
            $NewID = $OLDRST['HeadID'];
        }

        $InsertSQL = 
            "INSERT INTO pack_list SET
                HeadID = ".$NewID.",
                VisOrder = ".$OLDRST['VisOrder'].",
                LineNum = ".$OLDRST['LineNum'].",
                ItemCode = '".$OLDRST['ItemCode']."',
                BarCode = '".$OLDRST['BarCode']."',
                ItemName = '".$OLDRST['ItemName']."',
                WhsCode = '".$OLDRST['WhsCode']."',
                Unit = '".$OLDRST['Unit']."',
                Qty = ".$OLDRST['Qty'].",
                OpenQty = ".$OLDRST['OpenQty'].",
                Comments = '".$OLDRST['Comments']."'";
        // echo $InsertSQL."<br/>";
        $InsertID = 0;
        $InsertID = MySQLInsert($InsertSQL);
        if($InsertID != 0) {
            $ADDROW++;
        } else {
            array_push($ERRID,$InsertSQL);
        }
             

    }
    if($OLDROW == $ADDROW) {
        echo "6. Import Into pack_list Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "6. Import Into pack_list Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
        echo "<ol>";
        for($i=0;$i<count($ERRID);$i++) {
            echo "<li>$ERRID[$i]</li>";
        }
        echo "</ol>";
        exit;
    }
}
echo "<br/>";
echo "<br/>";
echo "Click <a href='ST2_CHQRETURN.php'>here</a> to run next step";
echo "</pre>";
?>