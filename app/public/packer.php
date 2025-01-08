<!DOCTYPE html>
<html>
<style>
.parentDiv{
    height: 100vh;
    overflow: hidden;
}
.parentDiv .innerDiv{
    height: 100%;
    overflow: auto; 
}
@media screen and (max-width: 1024px) {
    .parentDiv { height: 100%; }
}
body{
    color:black;
    padding-top: 2rem;
    padding-left: 2rem;
    padding-right: 2rem;
    
}
.txtH1{
    color:#FFF;
    background-color:#b30000;
}
.txtH2{
    color:#FFF;
    background-color:#ff9999;
}
.txtRow{
    color:black;
}
.view_data:hover{
    cursor:pointer;
}
.txtdbhead{
    border: 1px solid #FFFFFF;
    font-weight:bold;
    text-align:center;
    color:#FFFFFF;
    background-color: #F947A3;
    vertical-align:top !important;
}
.txtdisable {
    display: block;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border: 1px solid #FFFFFF;
    padding: 2px;
    background-color: #F7E4EE;
}
.htxtinput{
  font-weight:bold;
  color:#FFF;
  text-align:left;
  background:#F947A3;
}
.NotY{
  font-weight:bold  !important;
  color:#FFF !important;
  background:#f70542  !important;
}
.MinLate{
  font-weight:bold;
  background-color:#F3F5CA !important;
}
</style>
<?php
require("core/Main.core.php");
require("../".MainPathKSY()."/core/config.core.php");
require("../".MainPathKSY()."/core/connect.core.php");
require("../".MainPathKSY()."/core/functions.core.php");
require("../".MainPathKSY()."/core/coresap.php");
$getdata = new clear_db();
$connect = $getdata->my_sql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
$getdata->my_sql_set_utf8();
/*
$userdata = $getdata->my_sql_query("T0.*, T1.PositionName, T1.Point_Class, T1.DeptCode, T2.DeptName","user T0 JOIN position T1 ON T0.user_position = T1.LvCode JOIN departments T2 ON T2.DeptCode = T1.DeptCode","user_key='".$_SESSION['ukey']."'");
$system_info = $getdata->my_sql_query(NULL,"system_info",NULL);
date_default_timezone_set('Asia/Bangkok');
require("../mk/core/online.core.php");
*/
?>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>KingBangkok</title>
    <link href="../<?echo MainPathKSY();?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="../<?echo MainPathKSY();?>/css/datepicker.css" rel="stylesheet">
    <link href="../<?echo MainPathKSY();?>/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../<?echo MainPathKSY();?>/css/plugins/timeline.css" rel="stylesheet">
    <link href="../<?echo MainPathKSY();?>/css/sb-admin-2.css" rel="stylesheet">
    <link href="../<?echo MainPathKSY();?>/css/bootstrap-combobox.css" rel="stylesheet">
    <link href="../<?echo MainPathKSY();?>/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="../<?echo MainPathKSY();?>/css/plugins/morris.css" rel="stylesheet">
    <link href="../<?echo MainPathKSY();?>/css/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../<?echo MainPathKSY();?>/css/fontawesome-free-5.9.0/css/fontawesome.min.css" rel="stylesheet" type="text/css">
    <link href="../<?echo MainPathKSY();?>/css/iconset/ios7-set-filled-1/flaticon.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="../<?echo MainPathKSY();?>/media/favicon/<?php echo @$system_info->site_favicon;?>"/>
    <link rel="stylesheet" href="../<?echo MainPathKSY();?>/css/selectize.default.css">
    <link rel="stylesheet" type="text/css" href="multiple-select.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.css">
    <script src="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.js"></script>
    <script src="multiple-select.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/jquery/jquery.min.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/bootstrap.min.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/bootstrap-datepicker.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/plugins/metisMenu/metisMenu.min.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/plugins/morris/raphael.min.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/plugins/morris/morris.min.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/sb-admin-2.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/bootstrap-combobox.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/bootstrap-colorpicker.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/latest/typeahead.bundle.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/standalone/selectize.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/jquery.sticky-kit.min.js"></script>
    <script src="../<?echo MainPathKSY();?>/js/jquery.number.min.js"></script>
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <?
    $sql3 = "SELECT TableID,IPAddress FROM checkertable WHERE IPAddress = '".$_SERVER['REMOTE_ADDR']."'";
    $getTB = $getdata->MySQL_SelectX($sql3);
    $DataTable = mysql_fetch_object($getTB);
    switch ($DataTable->TableID){
        case '1' :
            $selAll = "";
            $sel1 = " selected ";
            $sel2 = "  ";
            $sel3 = "  ";
            $sel4 = "  ";
            $sel5 = "  ";
            break;
        case '2' :
            $selAll = "  ";
            $sel1 = "  ";
            $sel2 = " selected ";
            $sel3 = "  ";
            $sel4 = "  ";
            $sel5 = "  ";
            break;
        case '3' :
            $selAll = "  ";
            $sel1 = "  ";
            $sel2 = "  ";
            $sel3 = " selected ";
            $sel4 = "  ";
            $sel5 = "  ";
            break;
        case '4' :
            $selAll = "  ";
            $sel1 = "  ";
            $sel2 = "  ";
            $sel3 = "  ";
            $sel4 = " selected ";
            $sel5 = "  ";
            break;
        case '5' :
            $selAll = "  ";
            $sel1 = "  ";
            $sel2 = "  ";
            $sel3 = "  ";
            $sel4 = "  ";
            $sel5 = " selected ";
            break;
        default :
            $selAll = " selected ";
            $sel1 = "  ";
            $sel2 = "  ";
            $sel3 = "  ";
            $sel4 = "  ";
            $sel5 = "  ";
            break;

    }
    
    ?>

</head>
<body style="font-size:22px;">
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12"><h2 style="font-weight:bold;"><i class="fas fa-box-open fa-fw fa-lg"></i> สรุปรายการแพ็กสินค้าตาม SO</h2></div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-lg-1">
            <label for="filt_Table" class="form-control-label"><i class="fas fa-desktop fa-fw fa-lg"></i> เลือกโต๊ะจัด:</label>
        </div>
        <div class="col-lg-2">
            <select id="filt_Table" name="filt_Table" class="form-control form-control-lg" onchange="CallData()">
                <option value="ALL"<?echo $selAll;?>>เลือกทุกโต๊ะ</option>
                <option value="1" <?echo $sel1;?> >โต๊ะ 1</option>
                <option value="2" <?echo $sel2;?>>โต๊ะ 2</option>
                <option value="3" <?echo $sel3;?>> โต๊ะ 3</option>
                <option value="4" <?echo $sel4;?>>โต๊ะ 4</option>
                <option value="5" <?echo $sel5;?>>โต๊ะ 5</option>
            </select>
        </div>
        <!---
        <div class="col-lg-2">
            <select id="WaitOP" class="form-control form-control-lg" multiple="multiple">
                <option value="0">กำลังเบิกสินค้า</option>
                <option value="1">รอ CoSale ตอบ</option>
                <option value="2">รอเปิดบิล</option>
                <option value="3">รอแพ็คสินค้า</option>
                <option value="4">กำลังแพคสินค้า</option>
                <option value="5">สินค้าพร้อมส่ง</option>
                <option value="6">ยังไม่กดยืนยันรายการ</option>

            </select>
        
        </div>
-->
        <div class="col-lg-2 col-lg-offset-7">
            <input type="text" id="myInput" class="form-control form-control-lg" placeholder="กรองข้อมูล..." />
        </div>
    </div>
    <div class="row" style="margin-top: 2rem;">
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                    <tr class="txtH1">
                        <th colspan="10">รายการแพ็กที่ต้องส่งพรุ่งนี้</th>
                    </tr>
                    <tr class="txtH2">
                        <th width="2.5%">&nbsp;</th>
                        <th width="5%">ทีม</th>
                        <th width="10%">เลขที่เอกสาร</th>
                        <th>ชื่อร้านค้า</th>
                        <th width="7.5%">วันที่เปิดเอกสาร</th>
                        <th width="7.5%">กำหนดส่ง</th>
                        <th width="7.5%">จำนวนรายการ</th>
                        <th width="15%">พนักงานเบิก</th>
                        <th width="15%">สถานะของงาน</th>
                        <th width="5%">โต๊ะจัด</th>
                    </tr>
                </thead>
                <tbody id="tbl_1"></tbody>

                <thead>
                    <tr class="txtH1">
                        <th colspan="10">รายการแพ็กที่ต้องส่งล่วงหน้า</th>
                    </tr>
                    <tr class="txtH2">
                        <th>&nbsp;</th>
                        <th>ทีม</th>
                        <th>เลขที่เอกสาร</th>
                        <th>ชื่อร้านค้า</th>
                        <th>วันที่เปิดเอกสาร</th>
                        <th>กำหนดส่ง</th>
                        <th>จำนวนรายการ</th>
                        <th>พนักงานเบิก</th>
                        <th>สถานะของงาน</th>
                        <th>โต๊ะจัด</th>
                    </tr>
                </thead>
                <tbody id="tbl_2"></tbody>
            </table>
        </div>
    </div>
</div>
<div id="dataModal" class="modal fade">  
      <div class="modal-dialog modal-lg">  
           <div class="modal-content">  
                <div class="modal-header">  
                     <button type="button" class="close" data-dismiss="modal">&times;</button>  
                     <span id="HModal" style="font-size:24px;font-weight:bold;">รายละเอียดใบสั่งขาย</span>
                </div>  
                <div class="modal-body" id="SO_detail">  
                </div>  
                <div class="modal-footer">  
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
           </div>  
      </div>  
 </div>
</body>
<script>
    $(document).ajaxStart(function(){
        $('#loading').show();
    }).ajaxStop(function(){
        $('#loading').hide();
    });
 </script>
 <script type="text/javascript">
    $(document).ready(function(){
        CallData();
    });
</script>

<script type="text/javascript">  
    function CallData() { // Call inStock Data
        $.ajax({
                url: "ajax/ajaxPicker.php" ,
                type: "POST",
                data:{tableP:$('#filt_Table').val(),}
        })
        .success(function(result) { 
            var obj = jQuery.parseJSON(result);
            $.each(obj, function(key, inval) {
                $('#tbl_1').html(inval['Table1']);
                $('#tbl_2').html(inval['Table2']);
            });
        });
    } 
</script>
<script>
	$(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tbl_1 tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            $("#tbl_2 tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
	});
</script>
<script>  
    function CallModal(x,y){
		$.ajax({  
			url:"ajax/ajaxModalSO.php",  
			method:"post",  
			data:{docEntry:y,
                  Func:x},  
			success:function(data){ 
				$('#SO_detail').html(data);  
				$('#dataModal').modal("show");  
			}  
		});  
	}
 </script>
<script>
  function ShowLine(x){
        $("#"+x).toggleClass('hidden');
        
    }
</script>
<script>
    // Initialize multiple select on your regular select
    $("#WaitOP").multipleSelect({
        filter: true
    });
</script>
