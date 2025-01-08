<?php 
/*
include('config.core.php');
include('functions.core.php');
date_default_timezone_set('Asia/Bangkok');
*/
$CHkWork = 0;
$timeWork = intval(date("Hi"));
if (isset($noTime)){
    $noTime = 0;
}

$DayWork = date("Y-m-d");
if ((date('N',strtotime($DayWork)) != '7') && (CHKRowDB("SELECT * FROM annual_holiday WHERE Holiday_date = '".$DayWork."'") == 0)) {
    if ($timeWork >= 830 && $timeWork <=1600){
        $CHkWork = 1;
    }else{
        if ($noTime == 1){
            $CHkWork = 1;
        }
    }
}
//echo $CHkWork;
/*
$WeekWork = 1; 
$DayWork = date("Y-m-d");
while (($WeekWork == 1) || date('N',strtotime($DayWork)) == '7'){
    $DayWork =  date("Y-m-d",strtotime("+1 days",strtotime($DayWork)));
    $WeekWork = CHKRowDB("SELECT * FROM annual_holiday WHERE Holiday_date = '".$DayWork."'");
}
*/ 

if ($CHkWork == 1){
    $sql1 = "SELECT uKey,EmpCode FROM users WHERE LvCode = 'LV077' AND UserStatus = 'A'";
    $getPicker = MySQLSelectX($sql1);
    $empList = "('";
    $a=0;
    while ($picker = mysqli_fetch_array($getPicker)){
        $a++;
        $EmpCode[$a] = $picker['EmpCode'];
        $ukey[$EmpCode[$a]] = $picker['uKey'];
        $CHK[$EmpCode[$a]]=0;
        $empList .= $picker['EmpCode']."','";
    }
    $empList = substr($empList,0,-2).")";


    $sql1 = "SELECT T0.EmpCode,T1.DateTimeStamp
                    FROM  emEmployee T0
                        JOIN hrTimeTempImport T1 ON T0.EmpID = T1.EmpID 
                    WHERE T0.WorkingStatus = 'Working' AND DATEADD(dd,0,DATEDIFF(dd,0,T1.DateTimeStamp)) =  DATEADD(dd,0,DATEDIFF(dd,0,GETDATE())) AND T0.EmpCode IN ".$empList; 

    $getHRMI = HRMISelect($sql1);
    while ($HRMI=odbc_fetch_array($getHRMI)){
        $CHK[$HRMI['EmpCode']] = 1;
    }
    $uKeyList = "('";
    for ($i=1;$i<=$a;$i++){
        if ($CHK[$EmpCode[$i]]== 1){
            $uKeyList .= $ukey[$EmpCode[$i]]."','";
        }
    }
    $uKeyList = substr($uKeyList,0,-2).")";

    //echo $uKeyList ."<br/><br/>";
    //$PickType['PickDay'] = 'A1' ;
    switch ($PickType['PickDay']){
        case 'A0' :  //บิลด่วน
        case 'A1' : // บิลเมื่อวาน
        case 'A3' : // บิลเช้า   TT จัดวันนี้
            $sql1 = "SELECT T0.uKey,
                            IFNULL ((SELECT SUM(X0.ItemCount) FROM picker_soheader X0 WHERE X0.UkeyPicker = T0.uKey AND X0.TeamCode IN ('TT2','TT1','OUL','KBI') AND X0.DatePick = DATE(NOW())),0)  AS ItemCount,
                            IFNULL ((SELECT COUNT(X1.ID) FROM picker_soheader X1 WHERE X1.UkeyPicker = T0.uKey AND X1.TeamCode IN ('TT2','TT1','OUL','KBI') AND X1.DatePick = DATE(NOW())),0)  AS Bill 
                    FROM users T0
                    WHERE T0.uKey IN ".$uKeyList." ORDER BY ItemCount,Bill LIMIT 1";  

            $sql2 = "SELECT T0.TableID,
                            IFNULL ((SELECT SUM(X0.ItemCount) FROM picker_soheader X0 WHERE X0.TablePacking = T0.TableID AND X0.TeamCode IN ('TT2','TT1','OUL','KBI') AND X0.PackDate = DATE(NOW())),0)  AS ItemCount,
                            IFNULL ((SELECT COUNT(X1.ID) FROM picker_soheader X1 WHERE X1.TablePacking = T0.TableID AND X1.TeamCode IN ('TT2','TT1','OUL','KBI') AND X1.PackDate = DATE(NOW())),0)  AS Bill 
                    FROM checkertable T0 ORDER BY ItemCount,Bill LIMIT 1";  
            // echo $sql1."<br/><br/>";
            // echo $sql2;
            $MinPicker = MySQLSelect($sql1);
            $MinPacker = MySQLSelect($sql2);
            $WeekEND1=1;
            if ($PickType['PickDay'] == 'A3'){
                $datePack = $SQLToday;
                while (($WeekEND1 == 1) || date('N',strtotime($datePack)) == '7'){
                    $datePack =  date("Y-m-d",strtotime("+1 days",strtotime($datePack)));
                    $WeekEND1 = CHKRowDB("SELECT * FROM annual_holiday WHERE Holiday_date = '".$datePack."'");
                }
            }else{
                $datePack = date("Y-m-d");//วันนี้
            }
            $sqlUpdate = "UPDATE picker_soheader SET DatePick = DATE(NOW()),
                                                    UkeyPicker = '".$MinPicker['uKey']."',
                                                    TablePacking = ".$MinPacker['TableID'].",
                                                    PackDate = '".$datePack."',
                                                    LastUpdate = NOW(),
                                                    LastUkey = '".$_SESSION['ukey']."'";
            break;
        case 'A4' : // MT จัดวันนี้ิ
        case 'B2' : // MT ล่วงหน้า
            $sql1 = "SELECT T0.uKey,
                            IFNULL ((SELECT SUM(X0.ItemCount) FROM picker_soheader X0 WHERE X0.UkeyPicker = T0.uKey AND X0.TeamCode LIKE 'MT%' AND MONTH(X0.DatePick) = MONTH(NOW())  AND YEAR(X0.DatePick) = YEAR(NOW())),0)  AS ItemCount,
                            IFNULL ((SELECT COUNT(X1.ID) FROM picker_soheader X1 WHERE X1.UkeyPicker = T0.uKey AND X1.TeamCode LIKE 'MT%' AND MONTH(X1.DatePick) = MONTH(NOW())  AND YEAR(X1.DatePick) = YEAR(NOW())),0)  AS Bill 
                    FROM users T0
                    WHERE T0.uKey IN ".$uKeyList." ORDER BY ItemCount,Bill LIMIT 1";  

            $sql2 = "SELECT T0.TableID,
                            IFNULL ((SELECT SUM(X0.ItemCount) FROM picker_soheader X0 WHERE X0.TablePacking = T0.TableID AND X0.TeamCode LIKE 'MT%' AND MONTH(X0.PackDate) = MONTH(NOW())  AND YEAR(X0.PackDate) = YEAR(NOW())),0)  AS ItemCount,
                            IFNULL ((SELECT COUNT(X1.ID) FROM picker_soheader X1 WHERE X1.TablePacking = T0.TableID AND X1.TeamCode LIKE 'MT%' AND MONTH(X1.PackDate) = MONTH(NOW())  AND YEAR(X1.PackDate) = YEAR(NOW())),0)  AS Bill 
                    FROM checkertable T0 ORDER BY ItemCount,Bill LIMIT 1";  

            $MinPicker = MySQLSelect($sql1);
            $MinPacker = MySQLSelect($sql2);   
            // echo $sql1."<br/><br/>";
            // echo $sql2;
            $datePack = $SQLToday;
            $WeekEND1=1;
            while (($WeekEND1 == 1) || date('N',strtotime($datePack)) == '7'){
                $datePack =  date("Y-m-d",strtotime("+1 days",strtotime($datePack)));
                $WeekEND1 = CHKRowDB("SELECT * FROM annual_holiday WHERE Holiday_date = '".$datePack."'");
            }
            if ($PickType['PickDay'] == 'A4'){
                $sqlUpdate = "UPDATE picker_soheader SET DatePick = '".$datePack."',
                                                        UkeyPicker = '".$MinPicker['uKey']."',
                                                        TablePacking = ".$MinPacker['TableID'].",
                                                        PackDate = '".$datePack."',
                                                        LastUpdate = NOW(),
                                                        LastUkey = '".$_SESSION['ukey']."'";
            }else{

                $WeekEND2 = 1;
                $Pick3Date =  date("Y-m-d",strtotime("-2 days",strtotime($DataORDR['DocDueDate'])));
                while (($WeekEND2 == 1) || date('N',strtotime($Pick3Date)) == '7'){
                    $Pick3Date =  date("Y-m-d",strtotime("-1 days",strtotime($Pick3Date)));
                    $WeekEND2 = CHKRowDB("SELECT * FROM annual_holiday WHERE Holiday_date = '".$Pick3Date."'");
                }


                $WeekEND3 = 1;
                $Pack1Date = $DataORDR['DocDueDate'];
                while (($WeekEND3 == 1) || date('N',strtotime($Pack1Date)) == '7'){
                    $Pack1Date =  date("Y-m-d",strtotime("-1 days",strtotime($Pack1Date)));
                    $WeekEND3 = CHKRowDB("SELECT * FROM annual_holiday WHERE Holiday_date = '".$Pack1Date."'");
                }

                $sqlUpdate = "UPDATE picker_soheader SET DatePick = '".$Pick3Date."',
                                                        UkeyPicker = '".$MinPicker['uKey']."',
                                                        TablePacking = ".$MinPacker['TableID'].",
                                                        PackDate = '".$Pack1Date."',
                                                        LastUpdate = NOW(),
                                                        LastUkey = '".$_SESSION['ukey']."'";
            //echo $sqlUpdate;

            }
            break;
        case 'B1' : //งานล่วงหน้า TT
            $tomorow = $SQLToday;
            $WeekEND1=1;
            while (($WeekEND1 == 1) || date('N',strtotime($tomorow)) == '7'){
                $tomorow =  date("Y-m-d",strtotime("+1 days",strtotime($tomorow)));
                $WeekEND1 = CHKRowDB("SELECT * FROM annual_holiday WHERE Holiday_date = '".$tomorow."'");
            }

            $sql1 = "SELECT T0.uKey,
                            IFNULL ((SELECT SUM(X0.ItemCount) FROM picker_soheader X0 WHERE X0.UkeyPicker = T0.uKey AND X0.TeamCode IN ('TT2','TT1','OUL','KBI') AND X0.DatePick ='".$tomorow."'),0)  AS ItemCount,
                            IFNULL ((SELECT COUNT(X1.ID) FROM picker_soheader X1 WHERE X1.UkeyPicker = T0.uKey AND X1.TeamCode IN ('TT2','TT1','OUL','KBI') AND X1.DatePick ='".$tomorow."'),0)  AS Bill 
                    FROM users T0
                    WHERE T0.uKey IN ".$uKeyList." ORDER BY ItemCount,Bill LIMIT 1";

            $sql2 = "SELECT T0.TableID,
                            IFNULL ((SELECT SUM(X0.ItemCount) FROM picker_soheader X0 WHERE X0.TablePacking = T0.TableID AND X0.TeamCode IN ('TT2','TT1','OUL','KBI') AND X0.PackDate ='".$tomorow."'),0)  AS ItemCount,
                            IFNULL ((SELECT COUNT(X1.ID) FROM picker_soheader X1 WHERE X1.TablePacking = T0.TableID AND X1.TeamCode IN ('TT2','TT1','OUL','KBI') AND X1.PackDate ='".$tomorow."'),0)  AS Bill 
                    FROM checkertable T0 ORDER BY ItemCount,Bill LIMIT 1";  
            $MinPicker = MySQLSelect($sql1);
            $MinPacker = MySQLSelect($sql2); 

            $sqlUpdate = "UPDATE picker_soheader SET DatePick = '".$tomorow."',
                                                    UkeyPicker = '".$MinPicker['uKey']."',
                                                    TablePacking = ".$MinPacker['TableID'].",
                                                    PackDate = '".$tomorow."',
                                                    LastUpdate = NOW(),
                                                    LastUkey = '".$_SESSION['ukey']."'";
            break;
    }
    //echo $sql1."<br><br>";
    //echo $sql2."<br>";
    //echo $sqlUpdate;
    $sqlUpdate .=" WHERE ID = ".$ID;
    // echo $sqlUpdate;
    MySQLUpdate($sqlUpdate);
}

?>