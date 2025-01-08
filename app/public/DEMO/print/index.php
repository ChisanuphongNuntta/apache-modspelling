<?php 
session_start();
include("../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../\"</script>"; exit(); }
function EDCode($n) {
    return base64_url_decode($_GET[base64_url_encode($n)]);
}

$menu = base64_url_decode($_GET['fpage']);
$print = base64_url_decode($_GET['fname']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print</title>
    <link rel="stylesheet" href="../assets/css/hope-ui.min.css?v=4.0.0">
    <link rel="shortcut icon" href="../assets/images/<?php echo $_SESSION['SITE']['Logo_Img']; ?>">
    <link rel="stylesheet" href="a4<?php echo $_GET['fstyle']; ?>/style.css">
</head>
<body>
    <?php
    require("../views/$menu/print/$print.php"); 
    ?> 
</body>
</html>