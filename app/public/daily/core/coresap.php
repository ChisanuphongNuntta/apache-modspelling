<?php
// ** THIS FILE IN 192.168.1.120 *** ///
$mssqldriver = '{SQL Server Native Client 11.0}';
//$mssqldriver = '{SQL Server Native Client 10.0}';//ON 13
$srvrname = '192.168.1.3';
$dtbsname = 'KBI_DB';
$username = 'sa';
$password = 'p@$$w0rd';
$kbiname = 'บจ.คิงบางกอกอินเตอร์เทรด';
$sapconn = odbc_connect("DRIVER=$mssqldriver;charset=UTF8;SERVER=$srvrname;DATABASE=$dtbsname",$username,$password) or die ("cannot connect to database");

$srvrname2 = '192.168.3.7\SQLExpress';
$dtbsname2 = 'KBI_WMS';
//$wmsconn = odbc_connect("DRIVER=$mssqldriver;charset=UTF8;SERVER=$srvrname2;DATABASE=$dtbsname2",$username,$password) or die ("cannot connect to database");

?>