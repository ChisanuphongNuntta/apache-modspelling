<?php session_start();
include('../../../core/config.core.php');
include('../../../core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');

if($_SESSION['UserName'] == NULL){
	echo '<script type="text/javascript">alert("ไม่สามารถดำเนินการใด ๆ ได้ เนื่องจาก Session หมดอายุ กรุณาเข้าสู่ระบบใหม่อีกครั้ง"); window.location="../../../../"; </script>';
} else { 
    ?>
 
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../../../../image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />
        <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
        <link href="../../../../css/main/app.css" rel="stylesheet" />
        <title>รายงานการโอนย้ายคลัง</title>
        <style rel="stylesheet" type="text/css">
            @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@200;300;400;500;600&display=swap');
            html, body {
                background-color: #FFFFFF;
                font-family: 'Sarabun';
                font-weight: 400;
                color: #000 !important;
                font-size: 11px;
            }

            .page {
                /* margin: 3mm;
                width: 204mm;
                height: 291mm; */
                /* border: 1px dashed #000; */
                width: 210mm;
                height: 297mm;
                display: block;
                margin: 3mm auto;
                padding: 3mm;
                box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
            }
            .table {
                color: #000 !important;
            }
            @page {
                size: A4;
                margin: 0;
            }
            @media print {
                .page {
                    /* margin: 3mm;
                    width: 204mm;
                    height: 291mm;
                    page-break-after: always; */
                    height: initial;
                    margin: 0mm auto;
                    box-shadow: 0 0 0;
                    /* border: 1px dotted #000; */
                    page-break-after: always;
                }
            }
        </style>
    </head>
    
    <body>
    <?php 
    $trnID = $_GET['trnID'];

    $ukey = $_SESSION['ukey'];

    $SQL0 = "UPDATE tranwhs SET Status = 4, ukeyPrint = '$ukey', DatePrint = NOW() WHERE DocNum = '$trnID'";
    // echo $SQL0;
    MySQLUpdate($SQL0);

    $target = 0;
    $sqlHead = "
        SELECT T0.DateCreate, T0.DocNum, T0.sourceWHS, T0.TargetWHS, T1.uName, T1.uLastName, T1.uNickName, T0.Status, T2.uName AS AppName, T2.uLastName AS AppLName, T2.uNickName AS AppNnick
        FROM tranwhs T0
            JOIN users T1 ON T1.uKey = T0.ukeyCreate
            LEFT JOIN users T2 ON T2.uKey = T0.ukeyApp
        WHERE T0.DocNum = '$trnID'";
    $HeadData = MySQLSelect($sqlHead);     
    $target = $HeadData['TargetWHS']; 
    $nameCrate = $HeadData['uName']." ".$HeadData['uLastName'];   
    $DateCreate = date("d/m/Y",strtotime($HeadData['DateCreate']));
    switch($HeadData['Status']) {
        case 3 :
            $textHead = "เอกสารอนุมัติแล้ว";
            $nameAPP = $HeadData['AppName']." ".$HeadData['AppLName'];
            break;
        case 4 :
            $textHead = "เอกสารสมบูรณ์";
            $nameAPP = $HeadData['AppName']." ".$HeadData['AppLName'];
        break;
    }
    if($HeadData['sourceWHS'] != "" AND $HeadData['sourceWHS'] != ""){
        $textHead .= " ย้ายจาก ".$HeadData['sourceWHS']." ไปยัง ".$HeadData['TargetWHS'];
    }

    $sqlList = "
        SELECT T0.tranSectID,T3.ItemCode,T3.ItemName,T3.MgrUnit,T0.WhsCode,T0.LocationRack,T0.QtyOut,T0.StatusTran,T0.AppTran
        FROM transecdata T0
        JOIN oitm T3 ON T0.ItemCode = T3.ItemCode
        WHERE T0.trnCode = '$trnID'
        ORDER BY T0.tranSectID DESC";
    $getList = MySQLSelectX($sqlList);
    $getRow = CHKRowDB($sqlList);
    
    $output = "";
    if($getRow == 0) {
        $output .= "<tr style=''><td class='text-center'>ไม่มีรายการ</td></tr>";
    }

    $rowsperpage = 35;
    $pages = ceil($getRow/$rowsperpage);
    $row = 0;
    for($p = 1; $p <= $pages; $p++) {
    ?>
        <div class="page">
            <table class="table table-borderless table-sm" style="color: #000;">
                <thead>
                    <tr>
                        <td width="20%" class="text-center">
                            <img src="../../../../image/logo/kbi_logo.png" class="img-fluid" />
                        </td>
                        <td>
                            <h4 class='text-black'>บริษัท คิงบางกอก อินเตอร์เทรด จำกัด</h4>
                            <small>
                                541,543,545 ซอย 39/1 แขวงท่าแร้ง เขตบางเขน กรุงเทพมหานคร 10220<br/>
                                เลขประจำตัวผู้เสียภาษี: 0105545012035 สำนักงานใหญ่ | โทรศัพท์: 02-509-3850 | โทรสาร: 02-509-3856
                            </small>
                        </td>
                        <td width="15%" class="align-top text-right"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-center"><h5 style="margin-top: 1rem;" class='text-black'>รายงานการโอนย้ายคลัง</h5></td>
                    </tr>
                </thead>
            </table>
            <table class='table table-sm table-borderless'>
                <thead>
                    <tr>
                        <td width='23%' class='fw-bold pb-1'><?php echo "เลขที่เอกสาร : ".$trnID; ?></td>
                        <td class='fw-bold pb-1'><?php echo $textHead; ?></td>
                    </tr>
                    <tr>
                        <td width='23%' class='fw-bold pb-1'><?php echo "ผู้โอนย้าย : ".$nameCrate; ?></td>
                        <td class='fw-bold pb-1'><?php echo "วันที่ : ".$DateCreate; ?></td>
                    </tr>
                    <tr>
                        <td colspan='2' class='fw-bold pb-1'><?php echo "ผู้อนุมัติ : ".$nameAPP; ?></td>
                    </tr>
                </thead>
            </table>
            <table class='table table-sm table-bordered border-dark'>
                <tbody>
                    <tr class='text-center'>
                        <th width='13%'>รหัสสินค้า</th>
                        <th width=''>ชื่อสินค้า</th>
                        <th width='12%'>จากคลัง</th>
                        <th width='12%'>ไปคลัง</th>
                        <th width='13%'>จำนวน</th>
                        <th width='8%'>หน่วย</th>
                    </tr>
                    <?php 
                    while($ItemData = mysqli_fetch_array($getList)) {
                        if($ItemData['AppTran'] != 'C') {
                            echo"<tr style='font-size: 10px;'>
                                    <td class='text-center'>".$ItemData['ItemCode']."</td>
                                    <td>".$ItemData['ItemName']."</td>
                                    <td class='text-center'>".$ItemData['WhsCode']."</td>
                                    <td class='text-center'>".$target."</td>
                                    <td class='text-right'>".number_format($ItemData['QtyOut'])."</td>
                                    <td>".$ItemData['MgrUnit']."</td>
                                </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <script type="text/javascript">
        // setTimeout(() => {
            window.print();
        // }, 500);
    </script>
    </body>
    </html>
    
<?php } ?>