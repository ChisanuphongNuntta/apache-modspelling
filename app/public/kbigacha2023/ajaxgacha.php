<?php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'kbi');
define('DB_PASSWORD', 'p@ssw0rd!');
define('DB_NAME', 'kbigacha'); 

function MySQLQuery($SqlStatement){
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$query = mysqli_query($connectF,$SqlStatement);
	return $query;
}

function MySQLNumRow($SqlStatement){
	$connectF = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	$query = mysqli_query($connectF,$SqlStatement);
	$chk = mysqli_num_rows($query);
	mysqli_close($connectF);
	return $chk;
}

$resultArray = array();
$arrCol = array();

if($_GET['p'] == "GetRewardA") {
    $SQL1 = 
        "SELECT
            T0.RewardID, T0.RewardName, T0.LineNum, T0.EmpCode
        FROM rewards T0
        WHERE T0.MainGroup = 'A' AND T0.RewardStatus = 'A'
        ORDER BY T0.RewardID, T0.LineNum ASC";
    $ROW1 = MySQLNumRow($SQL1);
    if($ROW1 == 0) {
        $arrCol['Row'] = 0;
    } else {
        $arrCol['Row'] = $ROW1;

        $QRY1 = MySQLQuery($SQL1);
        $i = 0;
        $VisOrder = 1;
        while($RST1 = mysqli_fetch_array($QRY1)) {
            $arrCol[$i]['No']         = $VisOrder;
            $arrCol[$i]['ID']         = $RST1['RewardID'];
            $arrCol[$i]['RewardName'] = $RST1['RewardName']." (ท่านที่ ".$RST1['LineNum'].")";
            if($RST1['EmpCode'] == NULL) {
                $arrCol[$i]['EmpCode'] = "";
            } else {
                $arrCol[$i]['EmpCode'] = $RST1['EmpCode'];
            }
            
            $i++;
            $VisOrder++;
        }
    }
}

if($_GET['p'] == "GetRewardB") {
    $SQL1 =
        "SELECT DISTINCT
            T0.SubGroup, T0.RewardName, COUNT(T0.RewardID) AS 'LineNum'
        FROM rewards T0
        WHERE T0.MainGroup = 'B' AND T0.RewardStatus = 'A'
        GROUP BY T0.SubGroup, T0.RewardName";
    $ROW1 = MySQLNumRow($SQL1);
    if($ROW1 == 0) {
        $arrCol['Row'] = 0;
    } else {
        $arrCol['Row'] = $ROW1;
        $QRY1 = MySQLQuery($SQL1);
        $i = 0;
        $VisOrder = 1;
        while($RST1 = mysqli_fetch_array($QRY1)) {
            $arrCol[$i]['No']         = $VisOrder;
            $arrCol[$i]['SubGroup']   = $RST1['SubGroup'];
            $arrCol[$i]['RewardName'] = $RST1['RewardName'];
            $arrCol[$i]['LineNum']    = $RST1['LineNum'];
            
            $i++;
            $VisOrder++;
        }
    }
}

if($_GET['p'] == "WinnerA") {
    $RewardID = $_POST['RewardID'];
    $EmpCode  = $_POST['EmpCode'];

    $SQL1 = "SELECT T0.RewardID FROM rewards T0 WHERE T0.EmpCode = '$EmpCode'";
    $ROW1 = MySQLNumRow($SQL1);

    if($ROW1 > 0) {
        $arrCol['Status'] = "ERR::DUPLICATE";
    } else {
        $SQL2 = "SELECT T0.EmpCode FROM users T0 WHERE T0.EmpCode = '$EmpCode' AND T0.EmpStatus = 'A' AND T0.EmpGroup = 1";
        $ROW2 = MySQLNumRow($SQL2);
        if($ROW2 == 0) {
            $arrCol['Status'] = "ERR::NOUSERS";
        } else {

            $SQL3 = "SELECT T0.RewardCode FROM rewards T0 WHERE T0.RewardID = '$RewardID' LIMIT 1";
            $QRY3 = MySQLQuery($SQL3);
            $RST3 = mysqli_fetch_array($QRY3);

            $RewardCode = $RST3['RewardCode'];

            $SQL4 = "UPDATE rewards SET EmpCode = '$EmpCode' WHERE RewardID = '$RewardID'";
            MySQLQuery($SQL4);
            $SQL5 = "UPDATE users SET RewardCode = '$RewardCode' WHERE EmpCode = '$EmpCode'";
            MySQLQuery($SQL5);
            $arrCol['Status'] = "SUCCESS";
        }
    }
}

if($_GET['p'] == "WinnerB") {
    $SubGroup = $_POST['SubGroup'];

    $SQL1 = "SELECT T0.* FROM rewards T0 WHERE T0.SubGroup = '$SubGroup' AND T0.RewardStatus = 'A' ORDER BY T0.RewardID ASC";
    $ROW1 = MySQLNumRow($SQL1);
    if($ROW1 == 0) {
        $arrCol['Row'] = 0;
    } else {
        $arrCol['Row'] = $ROW1;

        $QRY1 = MySQLQuery($SQL1);

        while($RST1 = mysqli_fetch_array($QRY1)) {
            $RewardID   = $RST1['RewardID'];
            $RewardCode = $RST1['RewardCode'];

            /* SOLUTION RANDOM 1 PERSON */

            // $SQL2 = "SELECT T0.* FROM users T0 WHERE T0.RewardCode IS NULL AND T0.EmpGroup = 1 AND T0.EmpStatus = 'A' ORDER BY RAND() LIMIT 1";
            // $QRY2 = MySQLQuery($SQL2);
            // $RST2 = mysqli_fetch_array($QRY2);

            // $EmpCode = $RST2['EmpCode'];

            /* SOLUTION RANDOM 5 PERSONs */
            $SQL2 = "SELECT T0.* FROM users T0 WHERE T0.RewardCode IS NULL AND T0.EmpGroup = 1 AND T0.EmpStatus = 'A' ORDER BY RAND() LIMIT 5";
            $QRY2 = MySQLQuery($SQL2);
            $EmpArr = array();
            while($RST2 = mysqli_fetch_array($QRY2)) {
                array_push($EmpArr,$RST2['EmpCode']);
            }

            $key = array_rand($EmpArr,1);
            $EmpCode = $EmpArr[$key];

            $SQL3 = "UPDATE rewards SET EmpCode = '$EmpCode' WHERE RewardID = $RewardID";
            MySQLQuery($SQL3);

            $SQL4 = "UPDATE users SET RewardCode = '$RewardCode' WHERE EmpCode = $EmpCode";
            MySQLQuery($SQL4);

        }
    }
}

if($_GET['p'] == "ResetReward") {
    $SQL1 = "UPDATE rewards SET EmpCode = NULL WHERE MainGroup IN ('A','B')";
    MySQLQuery($SQL1);
    $SQL2 = "UPDATE users SET RewardCode = NULL WHERE EmpGroup = 1";
    MySQLQuery($SQL2);

}

if($_GET['p'] == "WinnerList") {
    $SQL1 =
        "SELECT
            T0.RewardCode, T0.SubGroup, T0.RewardName, T0.LineNum, CONCAT(T1.FirstName,' (',T1.NickName,') - ',T1.DeptName,' - [',T1.EmpCode,']') AS 'WinnerName'
        FROM rewards T0
        LEFT JOIN users T1 ON T0.EmpCode = T1.EmpCode
        WHERE T0.MainGroup IN ('A','B') AND T0.RewardStatus = 'A'
        ORDER BY T0.RewardID ASC";
    $ROW1 = MySQLNumRow($SQL1);
    if($ROW1 == 0) {
        $arrCol['Row'] = 0;
    } else {
        $arrCol['Row'] = $ROW1;
        $QRY1 = MySQLQuery($SQL1);
        $i = 0;
        $VisOrder = 1;
        while($RST1 = mysqli_fetch_array($QRY1)) {
            $arrCol[$i]['VisOrder']   = $VisOrder;
            if($RST1['SubGroup'] == "B12") {
                $arrCol[$i]['RewardName'] = $RST1['RewardName']." [สลากเบอร์ ".$RST1['RewardCode']."]";
            } else {
                $arrCol[$i]['RewardName'] = $RST1['RewardName']." (ท่านที่ ".$RST1['LineNum'].")";
            }

            if($RST1['WinnerName'] == NULL) {
                $arrCol[$i]['WinnerName'] = "";
            } else {
                $arrCol[$i]['WinnerName'] = "คุณ".$RST1['WinnerName'];
            }
            $i++;
            $VisOrder++;
        }
    }
}

if($_GET['p'] == "GetWinnerB") {
    $SubGroup = $_POST['SubGroup'];
    $SQL1 =
        "SELECT
            T0.RewardCode, T0.SubGroup, T0.RewardName, T0.LineNum, CONCAT(T1.FirstName,' (',T1.NickName,') - ',T1.DeptName,' - [',T1.EmpCode,']') AS 'WinnerName'
        FROM rewards T0
        LEFT JOIN users T1 ON T0.EmpCode = T1.EmpCode
        WHERE T0.SubGroup = '$SubGroup' AND T0.RewardStatus = 'A'
        ORDER BY T0.RewardID ASC";
    $ROW1 = MySQLNumRow($SQL1);
    if($ROW1 == 0) {
        $arrCol['Row'] = 0;
    } else {
        $arrCol['Row'] = $ROW1;
        $QRY1 = MySQLQuery($SQL1);
        $i = 0;
        $VisOrder = 1;
        while($RST1 = mysqli_fetch_array($QRY1)) {
            $arrCol[$i]['VisOrder']   = $VisOrder;
            if($RST1['SubGroup'] == "B12") {
                $arrCol[$i]['RewardName'] = $RST1['RewardName']." [สลากเบอร์ ".$RST1['RewardCode']."]";
            } else {
                $arrCol[$i]['RewardName'] = $RST1['RewardName']." (ท่านที่ ".$RST1['LineNum'].")";
            }
            
            if($RST1['WinnerName'] == NULL) {
                $arrCol[$i]['WinnerName'] = "";
            } else {
                $arrCol[$i]['WinnerName'] = "คุณ".$RST1['WinnerName'];
            }
            $i++;
            $VisOrder++;
        }
    }
}


array_push($resultArray,$arrCol);
echo json_encode($resultArray);

?>