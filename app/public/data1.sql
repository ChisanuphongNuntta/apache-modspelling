<?php
include('core/config.core.php');
include('core/functions.core.php');
date_default_timezone_set('Asia/Bangkok');
//$connectX = mysqli_connect("192.168.1.9","kbi","passw0rd!","kbidb");

$df = disk_free_space("H:");
$df = ((($df/1024)/1024)/1024);
number_format(($DataSale/$alltar)*100,2);
echo number_format($df,2)." GB";


?>