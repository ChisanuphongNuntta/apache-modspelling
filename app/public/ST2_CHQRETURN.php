<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
$OLDCON = mysqli_connect("192.168.1.12","kbi","passw0rd!","kbidb");
$NEWCON = mysqli_connect("localhost","kbi","p@ssw0rd!","kbidb");
echo "<style> body { background-color: #000; color: #00FF00; font-size: 14px; } a { color: #FFF; }</style>";
echo "<pre>";
echo "<h1>TRANSFER DATA WIZARD - TRANSFER CHQ RETURN</h1>";

/* STEP 1 */
$OLDSQL =
    "SELECT
        T0.ChqID, T0.DocNum, T0.CHQ_No, T0.CHQ_Amount, T0.CHQ_DateReturn, T0.CHQ_SaleReciv, T0.CardCode, T0.CardName, T0.SaleKey, T0.DateCreate, T0.userCreate, T0.Status, T0.Remark,
        CASE
            WHEN CauseReturn = '01' THEN 1
            WHEN CauseReturn = '02' THEN 2
            WHEN CauseReturn = '03' THEN 3
            WHEN CauseReturn = '04' THEN 4
            WHEN CauseReturn = '05' THEN 5
            WHEN CauseReturn = '06' THEN 6
            WHEN CauseReturn = '07' THEN 7
            WHEN CauseReturn = '08' THEN 8
            WHEN CauseReturn = '09' THEN 9
            WHEN CauseReturn = '10' THEN 10
            WHEN CauseReturn = '11' THEN 11
            WHEN CauseReturn = '12' THEN 12
            WHEN CauseReturn = '13' THEN 13
            WHEN CauseReturn = '19' THEN 14
        ELSE 1 END AS 'CauseReturn'
    FROM chq_return T0 ORDER BY T0.ChqID ASC";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {
        $InsertSQL =
            "INSERT INTO chq_return SET
                DocNum = '".$OLDRST['DocNum']."',
                CHQ_No = '".$OLDRST['CHQ_No']."',
                CHQ_Amount = '".$OLDRST['CHQ_Amount']."',
                CHQ_DateReturn = '".$OLDRST['CHQ_DateReturn']."',
                CHQ_SaleReceive = '".$OLDRST['CHQ_SaleReciv']."',
                CardCode = '".$OLDRST['CardCode']."',
                CardName = '".$OLDRST['CardName']."',
                SaleUkey = '".$OLDRST['SaleKey']."',
                CreateDate = '".$OLDRST['DateCreate']."',
                CreateUkey = '".$OLDRST['userCreate']."',
                Status = '".$OLDRST['Status']."',
                Remark = '".$OLDRST['Remark']."',
                CauseReturn = '".$OLDRST['CauseReturn']."'";
        // echo $InsertSQL."<br/>";
        $InsertID = 0;
        $InsertID = MySQLInsert($InsertSQL);
        if($InsertID != 0) {
            $ADDROW++;
        } else {
            $ADDROW++;
            array_push($ERRID,$OLDRST['ChqID']);
        }
    }
    
    if($OLDROW == $ADDROW) {
        echo "1. Import Into chq_return Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "1. Import Into chq_return Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
        echo "<ol>";
        for($i=0;$i<count($ERRID);$i++) {
            echo "<li>$ERRID[$i]</li>";
        }
        echo "</ol>";
        exit;
    }
}


/* STEP 2 */
$OLDSQL = "SELECT T0.* FROM chq_detail T0 ORDER BY T0.ID ASC";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {
        $InsertSQL =
            "INSERT INTO chq_detail SET
                DocNum = '".$OLDRST['ChqDocNum']."',
                DatePaid = '".$OLDRST['DatePaid']."',
                UpdateUkey = '".$OLDRST['UserUpdate']."',
                UpdateDate = NOW(),
                Amount = '".$OLDRST['Amount']."',
                Remark = '".$OLDRST['Remark']."'";
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
        echo "2. Import Into chq_detail Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "2. Import Into chq_detail Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
        echo "<ol>";
        for($i=0;$i<count($ERRID);$i++) {
            echo "<li>$ERRID[$i]</li>";
        }
        echo "</ol>";
        exit;
    }
}

/* STEP 3 */
$OLDSQL = "SELECT T0.* FROM chq_remark T0 ORDER BY T0.ID ASC";
$OLDQRY = mysqli_query($OLDCON,$OLDSQL);
$OLDROW = mysqli_num_rows($OLDQRY);
$ADDROW = 0;
$ERRID  = array();
if($OLDROW > 0) {
    while($OLDRST = mysqli_fetch_array($OLDQRY)) {
        $InsertSQL =
            "INSERT INTO chq_remark SET
                DocNum = '".$OLDRST['ChqDocNum']."',
                Remark = '".$OLDRST['Remark']."',
                CreateDate = '".$OLDRST['DateCreate']."',
                CreateUkey = '".$OLDRST['UserCreate']."',
                Status = '".$OLDRST['Status']."'";
        //echo $InsertSQL."<br/>";
        $InsertID = 0;
        $InsertID = MySQLInsert($InsertSQL);
        if($InsertID != 0) {
            $ADDROW++;
        } else {
            array_push($ERRID,$InsertSQL);
        }
    }

    if($OLDROW == $ADDROW) {
        echo "3. Import Into chq_remark Completed! (".$ADDROW." Records)<br/>";
    } else {
        echo "3. Import Into chq_remark Completed! (".$ADDROW." from ".$OLDROW." Records)<br/>Error ID is:";
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
echo "TRANSFER COMPLETE CONGRATULATIONS KRUB. 🎉🎉🎉🎉";
echo "</pre>";
?>