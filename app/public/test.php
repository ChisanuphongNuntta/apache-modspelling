<?php session_start(); date_default_timezone_set("Asia/Bangkok");
echo gethostbyaddr($_SERVER['REMOTE_ADDR'])."<br/>";
echo gethostbyname($_SERVER['REMOTE_ADDR']);
?>