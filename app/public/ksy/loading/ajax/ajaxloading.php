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
    $sql = "SELECT ID, LogiNum, DriverName, LogiLine, Status
            FROM logi_head 
            WHERE (CURDATE()<DATE_ADD(CreateDate,interval 4 day)) AND Status <= 3 ORDER BY ID DESC";
    $getList = MySQLSelectX($sql);
    $output = "";
    while($DataList = mysqli_fetch_array($getList)) {
        switch($DataList['Status']) {
            case 0:
            case 1:
            case 2:
                $Status = "<i class='fas fa-hourglass-half'></i>";
                $Color = "#fff";
                break;
            case 3:
                $Status = "<i class='fas fa-check text-success'></i>";
                $Color = "#dff0d8";
                break;
        }
        $output .= "<tr>".
                        "<td class='pb-0'>".
                            "<div class='ps-2 pe-2 pt-2 border border-1' style='border-radius: 10px 10px 0px 0px; box-shadow: 1px 1px ".$Color."; background-color: ".$Color.";'>".
                                "<div class='d-flex justify-content-aroundent'>".
                                    "<div class='' style='width: 75%'>".
                                        "<span class='fw-bolder'>เลขที่โหลด</span> <a class='fw-bold' href='loadlist.php?IDLogi=".$DataList['ID']."'>".$DataList['LogiNum']."</a>".
                                    "</div>".
                                    "<div class='' style='width: 25%'>".
                                        "<span class='fw-bolder'>สถานะ</span>&nbsp;&nbsp;".$Status.
                                    "</div>".
                                "</div>".
                            "</div>".
                            "<div class='p-2 border border-1' style='border-radius: 0px 0px 10px 10px; box-shadow: 1px 1px ".$Color.";'>".
                                "<div class='d-flex justify-content-aroundent'>".
                                    // "<div class='' style='width: 75%'>".
                                    "<div class='' style='width: 100%'>".
                                        "<span class='fw-bolder'>ชื่อคนขับ</span> <span>".$DataList['DriverName']."</span>".
                                    "</div>".
                                    // "<div class='' style='width: 25%'>".
                                        // "<span class='fw-bolder'>สายส่ง</span> <span>".$DataList['LogiLine']."</span>".
                                    // "</div>".
                                "</div>".
                            "</div>".
                        "</td>".
                    "</tr>";
    }
    $arrCol['output'] = $output;
}

if($_GET['a'] == 'NewLoad') {
    $tomorow = date("d/m/Y");
    $thisMonth = date("m");
    $dataDocNum = MySQLSelect("SELECT LogiNum FROM logi_head WHERE MONTH(CreateDate) = '".$thisMonth."' AND LogiNum != '' ORDER BY ID DESC limit 1");
    if (date("Y") <= 2500){
        $yearAdd = (date("Y")+543);
        $yearAdd = substr($yearAdd,2);
    }

    if(isset($dataDocNum['LogiNum'])) {
        if (substr($dataDocNum['LogiNum'],6,2) < $thisMonth){
            $runNum = 1;
        }else{
            $runNum = intval(substr($dataDocNum['LogiNum'],8))+1;
        }

        if ($runNum <= 9){
            $docNum = "LDN-".$yearAdd.date("m")."00".$runNum;
        }else{
            if ($runNum <= 99){
                $docNum = "LDN-".$yearAdd.date("m")."0".$runNum;
            }else{
                $docNum = "LDN-".$yearAdd.date("m").$runNum;
            }
        }
    }else{
        $docNum = "LDN-".$yearAdd.date("m")."001";
    }
    
    $lastid = MySQLInsert("INSERT INTO logi_head SET LogiNum = '".$docNum."', LogiLine = '', CreateDate = NOW(), ukeyCreate = '".$_SESSION['ukey']."', ukeyLastEdit = '".$_SESSION['ukey']."', OutDate = '".dateSQL($tomorow)."'");
    $arrCol['lastid'] = $lastid;
}

$arrCol['output'] = $output;
array_push($resultArray,$arrCol);
echo json_encode($resultArray);
?>