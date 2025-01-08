<?php

    include('core/config.core.php');
    include('core/functions.core.php');
    date_default_timezone_set('Asia/Bangkok');
    //$DocEntry = $_GET['docety'];
    $DocEntry = 72998;
    $GetSQL = 
        "SELECT
            T0.DocNum, T1.ItemCode, T1.BarCode, T1.ItemName
        FROM picker_soheader T0
        LEFT JOIN picker_sodetail T1 ON T0.SODocEntry = T1.DocEntry
        WHERE T0.ID = $DocEntry";
    $Rows   = ChkRowDB($GetSQL);
    $GetQRY = MySQLSelectX($GetSQL);
    $vod    = 0;
    $BarArr = "";
    while($GetRST = mysqli_fetch_array($GetQRY)) {
        $DocNum = $GetRST['DocNum'];
        ${"ItemCode_".$vod} = $GetRST['ItemCode'];
        ${"BarCode_".$vod} = $GetRST['BarCode'];
        ${"ItemName_".$vod} = $GetRST['ItemName'];
        
        $BarArr .= $GetRST['BarCode'];
        if($vod < $Rows-1) {
            $BarArr .= ",";
        }
        $vod++;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/main/app.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <link href="image/logo/favicon_96.jpg" rel="shortcut icon" type="image/png" />
    <title>WO BarCode Generator</title>
    <style rel="stylesheet" type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@200;300;400;500;600&display=swap');
        html, body {
            background-color: #FFFFFF;
            font-family: 'Sarabun';
            font-weight: 200;
            color: #000 !important;
            font-size: 11px;
        }
        
        h1,h2,h3,h4,h5,h6 {
            color: #000;
            padding: 0;
            margin: 0;
            font-weight: 600;
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
    <script src="js/JsBarcode.all.min.js" type="text/javascript"></script>
</head>
<body onload="window.print()">

<div class="page">
    <h1 class="text-center mt-4">บาร์โค้ดสำหรับสินค้าของใบสั่งขายเลขที่: <?php echo $DocNum; ?></h1>
    <table class="table table-bordered border-dark table-sm mt-4">
        <thead class="text-center">
            <tr>
                <th width="7.5%">รายการที่</th>
                <th width="12.5%">ItemCode</th>
                <th width="12.5%">BarCode</th>
                <th>ItemName</th>
                <th width="25%">Scan</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        for($i = 0; $i < $Rows; $i++) {
            echo "<tr>";
                echo "<td class='text-right'>$no</td>";
                echo "<td class='text-center'>".${"ItemCode_".$i}."</td>";
                echo "<td class='text-center'>".${"BarCode_".$i}."</td>";
                echo "<td>".${"ItemName_".$i}."</td>";
                echo "<td class='text-center'><svg id=\"sobarcode_".$i."\"></svg></td>";
            echo "</tr>";
            $no++;
        }
        ?>
        </tbody>
    </table>
</div>
    <script type="text/javascript">
        var BarArr = '<?php echo $BarArr; ?>';
        var BarSet = BarArr.split(",");
        var count  = BarSet.length;

        var vod = 0;

        for(i=1;i<=count;i++) {
            JsBarcode("#sobarcode_"+vod, BarSet[vod], { width: 1.25, height: 24, fontSize: 12, marginTop: 0, marginBottom: 0, text: BarSet[vod] });
            vod++;
        }
    </script>
</body>
</html>