<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>การยิงบาร์โค้ด</title>
<script type="text/javascript">
 function check_enter(e){//กำหนดให้  function check_enter ทำงานเมื่อ มีการกด keyboard
    var datax = $('#MM').val();
    console.log(e.keyCode);
    /*
     if (e.keyCode =! 13 && data.length <=2 ) { //ถ้า e.keyCode เป็น 13 แสดงว่า user กด enter
        $('#MM').val("");
    }else{
        $('#f4').focus();
    }
    */
 }
</script>
</head>

<body>
<form method="post" action="get_serial.php">

<table width="200" border="1" align="center">
  <tr>
    <td>ลำดับที่</td>
    <td>หมายเลขบาร์โค้ด</td>
  </tr>
  <tr>
    <td><input id='MM' onkeypress="check_enter(event)" value=""> </td>
    <td><input id='f4' type="text" value=""></td>
    
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>