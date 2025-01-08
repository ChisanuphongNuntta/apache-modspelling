<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
//$connectX = mysqli_connect("192.168.1.9","kbi","passw0rd!","kbidb");

/*
$sql1 = "SELECT CardCode,BName FROM custable WHERE BName != ''";
$queryold = mysqli_query($connectX,$sql1);
$i=0;
while ($row = mysqli_fetch_array($queryold)){
    $i++;
    $sql2 = "UPDATE ocrd SET BName = '".$row['BName']." WHERE CardCode = '".$row['CardCode']."'";
    MySQLUpdate($sql2);
    echo $i."-".$sql2."<br>";
}
    
*/

echo "START Copy <br>";
$sql1 = "SELECT P0.*,P1.OrgUnitCode
FROM (SELECT T1.EmpCode,T1.MemberCardExcept,T0.FirstName,T0.LastName,T0.NickName,T0.FirstNameEng,T0.LastNameEng,T0.BirthDate,
             CASE WHEN T0.Gender = 'Male' THEN 'M' ELSE 'F' END AS Gender,
             T1.WorkingStatus,T1.ShiftID,T1.StartDate,
             (SELECT TOP 1 D1.OrgUnitID 
              FROM hrEmpWorkProfile D1
              WHERE T1.EmpID = D1.EmpID  AND D1.EndDate IS NULL AND  D1.IsDeleted != 'TRUE' AND T1.WorkingStatus = 'Working'  
              ORDER BY D1.ModifiedDate DESC ) AS OrgUnitID,
              CASE WHEN T1.WorkingStatus IN ('Resign','LayOff') THEN 'I' ELSE 'A' END AS EmpStatus
      FROM emPerson T0
           LEFT JOIN emEmployee T1 ON T1.PersonID =  T0.PersonID
      WHERE  T0.IsDeleted != 'TRUE' ) P0
JOIN emOrgUnit P1 ON P0.OrgUnitID = P1.OrgUnitID
WHERE P1.OrgUnitCode NOT LIKE 'K%'";
$getEmp = HRMISelect($sql1);
$updatedata=0;
$newdata=0;
$a=0;
while ($Employee = odbc_fetch_array($getEmp)) {
    /*
    $sql1 = "SELECT user_key,EmpCode,name,lastname,username,password,user_position AS LvCode,user_photo,SignA FROM user WHERE EmpCode = '".$Employee['EmpCode']."' OR (name LIKE '%".conutf8($Employee['FirstName'])."%' AND lastname LIKE '%".conutf8($Employee['LastName'])."%')";
	$queryold = mysqli_query($connectX,$sql1);
    $chk = mysqli_num_rows($queryold);
    
    if ($chk != 0){
        $updatedata++;
        $text5 = "<span style='font-weight: bold;color:#056900'>OLD</span>";
        $row = mysqli_fetch_array($queryold);
        $UserData['uKey'] = $row['user_key'];
        $UserData['uName'] = $row['name'];
        $UserData['uLastName'] = $row['lastname'];
        $UserData['UserName'] = $row['username'];
        $UserData['UserPass'] = $row['password'];
        $UserData['LvCode'] = $row['LvCode'];
        $UserData['UserPhoto'] = $row['user_photo'];
        $UserData['UserSign'] = $row['SignA'];

    }else{
        $newdata++;
        $text5 = "<span style='font-weight: bold;color:#a10e0e'>NEW</span>";
        $uKey = md5(addslashes($Employee['FirstNameEng']).date("YmdHis"));
        $UserData['uKey'] = $uKey;
        $UserData['uName'] = conutf8($Employee['FirstName']);
        $UserData['uLastName'] = conutf8($Employee['LastName']);
        $UserData['UserName'] = strtolower($Employee['FirstNameEng'].".".substr($Employee['LastNameEng'],0,1));
        $NewPass = md5(substr(strtolower($Employee['FirstNameEng']),0,3).date("d",strtotime(dateSQL($Employee['BirthDate']))).date("m",strtotime(dateSQL($Employee['BirthDate']))));
        $UserData['UserPass'] = $NewPass;
        $UserData['LvCode'] = "AAA";
        $UserData['UserPhoto'] = 'noimg.jpg';
        $UserData['UserSign'] = '';


    }
    */
    $text5 = "<span style='font-weight: bold;color:#a10e0e'>NEW</span>";
    $uKey = md5(addslashes($Employee['FirstNameEng']).date("YmdHis"));
    $UserData['uKey'] = $uKey;
    $UserData['uName'] = conutf8($Employee['FirstName']);
    $UserData['uLastName'] = conutf8($Employee['LastName']);
    $UserData['UserName'] = strtolower($Employee['FirstNameEng'].".".substr($Employee['LastNameEng'],0,1));
    $NewPass = md5(substr(strtolower($Employee['FirstNameEng']),0,3).date("d",strtotime(dateSQL($Employee['BirthDate']))).date("m",strtotime(dateSQL($Employee['BirthDate']))));
    $UserData['UserPass'] = $NewPass;
    $UserData['LvCode'] = "AAA";
    $UserData['UserPhoto'] = 'noimg.jpg';
    $UserData['UserSign'] = '';
    $sql2 = "SELECT uKey,EmpCode FROM users WHERE EmpCode = '".$Employee['EmpCode']."' OR (EmpCode = '' AND uName LIKE '%".conutf8($Employee['FirstName'])."%' AND uLastName LIKE '%".conutf8($Employee['LastName'])."%')";
    $chk2 = CHKRowDB($sql2);
    echo $Employee['EmpCode']."-".$chk2."<br>";
    if ($chk2 == 0){
        $a++;
        $NewPass = md5(substr(strtolower($Employee['FirstNameEng']),0,3).date("d",strtotime(dateSQL($Employee['BirthDate']))).date("m",strtotime(dateSQL($Employee['BirthDate']))));
        $sql1 = "INSERT INTO users SET uKey = '".$UserData['uKey']."',
                                       EmpCode = '".$Employee['EmpCode']."',
                                       uName = '".$UserData['uName']."',
                                       uLastName = '".$UserData['uLastName']."',
                                       uNickName = '".conutf8($Employee['NickName'])."',
                                       UserName = '".$UserData['UserName']."',
                                       UserPass = '".$UserData['UserPass']."',
                                       LvCode = '".$UserData['LvCode']."',
                                       UserCreate = 'c37d695c6f1144abdefa8890a921b8fb',
                                       UserUpdate = 'c37d695c6f1144abdefa8890a921b8fb',
                                       UserGender = '".$Employee['Gender']."'";
        MySQLInsert($sql1);
    }else{
        $sql1 = "UPDATE users SET UserPhoto = '".$UserData['UserPhoto']."',
                                  UserSign ='".$UserData['UserSign']."',
                                  UserUpdate = 'c37d695c6f1144abdefa8890a921b8fb'
                 WHERE uKey = '".$UserData['uKey']."'";
        MySQLUpdate($sql1);
    }
}
echo "NEW : ".$newdata."<br>";
echo "OLD : ".$updatedata."<br>";

?>