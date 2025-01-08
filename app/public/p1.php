<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="10">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    date_default_timezone_set('Asia/Bangkok');
    require("core/config.core.php");
    include("core/functions.core.php");

    $SQL0 = "SELECT Chk FROM p1 LIMIT 1";
    $RST1 = MySQLSelect($SQL0);
    if($RST1['Chk'] < 10) {
        $server = 0;
        exec("ping -n 2 8.8.8.8", $output, $status);
        if ($status == 0){
            // echo "success";
            $chk = $RST1['Chk']+1;
            $SQL1 = "UPDATE p1 SET Chk = ".$chk;
            MySQLUpdate($SQL1);
            if ($chk == 5){
                LineNoti('WaiWai',"Internet ใช้งานได้แล้วววววว");
                echo "Internet ใช้งานได้แล้วววววว";
            }else{
                echo $chk."/5";    
            }
        } else {
            $SQL1 = "UPDATE p1 SET Chk = 0";
            MySQLUpdate($SQL1);
            echo "unsuccess at ".date("Y-m-d H:i:s");
        }
    }
    if ($RST1['Chk'] > 30) {
        $SQL1 = "UPDATE p1 SET Chk = 0";
        MySQLUpdate($SQL1);
    }
    

?>
</body>
</html>