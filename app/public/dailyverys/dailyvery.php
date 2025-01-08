<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>ระบบรับส่งบิลขนส่ง </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Timeline CSS -->
    <link href="css/plugins/timeline.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/bootstrap-combobox.css" rel="stylesheet">
    <link href="css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">
    <!-- Custom Fonts
    <script src="https://kit.fontawesome.com/05e9ebeb03.js"></script>-->
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <link href="css/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/fontawesome-free-5.9.0/css/fontawesome.min.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="media/favicon/<?php echo @$system_info->site_favicon; ?>" />
    <link rel="stylesheet" href="css/selectize.default.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> -->
    <script src="js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="dashboard/vansale/js/ajax.js"></script>
    <!---<script src="http://code.jquery.com/jquery-latest.js"></script> --->
    <script src="js/jquery/jquery-latest.js"></script>
    <script type="text/javascript" src="dashboard/vansale/js/ajax-dynamic-list.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <script src="js/bootstrap-combobox.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/latest/typeahead.bundle.js"></script>
    <script src="js/standalone/selectize.js"></script>
    <script src="js/jquery.sticky-kit.min.js"></script>

    <style>
        body {
            padding-left: 2rem;
        }

        .htxtdisable2 {
            font-weight: bold;
            color: #900;
            text-align: left;
            background: #F7E4EE;
            vertical-align: top !important;
        }

        .htxtdisable3 {
            vertical-align: top !important;
        }

        .butinput {
            font-weight: bold;
            color: #FFF;
            text-align: left;
            background: #CC0033;
        }

        .fixinput {
            width: 300px !important;
            color: #900;
            text-align: left;
            background: #F7E4EE;
            font-size: 16px;
        }

        .butbill {
            font-size: 17px;
            color: #FFF;
            text-align: left;
            background: #FA852E;
        }

        .txtdisable {
            font-size: 18px;
        }

        .txtdisable2 {
            font-size: 22px !important;
            font-weight: bold;
        }

        .txtnomal {
            font-size: 16px;
            font-weight: normal;
        }

        .txtdbhead {
            border: 1px solid #FFFFFF;
            font-weight: bold;
            text-align: center;
            color: #FFFFFF;
            background-color: #F947A3;
            vertical-align: top !important;
        }

        .txtc {
            text-align: center !important;
        }

        .txtl {
            text-align: left !important;
        }

        .txtr {
            text-align: right !important;
        }

        .txtmidle {
            vertical-align: middle !important;
        }

        .txttop {
            vertical-align: top !important;
        }

        .fixwb {
            width: 1350px !important;
        }

        .dateinput2 {
            height: 30px;
            border-radius: 5px;
            padding-right: 1px;
            padding-left: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 2px;
            margin: 1px 1px 1px 1px;
            border: 1px solid #cccccc;
            background: #F9CEE5;
        }

        .dateinput1 {
            height: 30px;
            border-radius: 5px;
            padding-right: 1px;
            padding-left: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 2px;
            margin: 1px 1px 1px 1px;
            border: 0px solid #cccccc;
            color: #ff0000;
            background: #fff;
        }

        .inputf {
            text-shadow: 1px 1px 1px #fff;
            border-radius: 5px;
            padding-right: 1px;
            padding-left: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 2px;
            margin: 1px 1px 1px 1px;
            border: 1px solid #ff99ce;
            background: #ffe6f3;
            box-shadow: inset 0px 0px 2px #ffe6f3;
            -moz-box-shadow: inset 0px 0px 2px #FFE5E5;
            -webkit-box-shadow: inset 0px 0px 2px #FFE5E5;
        }

        input[type="checkbox"] {
            display: inline-block;
            width: 19px;
            height: 35px;
            margin: 0 2px 0 0;
            vertical-align: middle;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fab fa-houzz"></i> รายงาน รับ/ส่ง บิลขาย</h1>
        </div>
    </div>
    <ol class="breadcrumb">
        <li class="active"> รายงาน รับ/ส่ง บิลขาย</li>
    </ol>
    <input type=hidden id="xMonth" name="xMonth" value="2022">
    <table class="table fixwb" border="0">
        <tr>
            <form method="POST">
                <td class="txtmidle" width="1%"></td>
                <td class="txtmidle">
                    <select name="ThisYear" id="ThisYear" class="form-control">
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                    </select>
                </td>
                <td class="txtmidle" width="1%"></td>
                <td colspan="5" class="txtmidle" width="30%">&nbsp;
                    <select name="opMounth" id="opMounth" class="form-control" onchange="CallOldMonth();">
                        <option value="1">มกราคม</option>
                        <option value="2">กุมภาพันธ์</option>
                        <option value="3">มีนาคม</option>
                        <option value="4">เมษายน</option>
                        <option value="5">พฤษภาคม</option>
                        <option value="6">มิถุนายน</option>
                        <option value="7">กรกฎาคม</option>
                        <option value="8">สิงหาคม</option>
                        <option value="9">กันยายน</option>
                        <option value="10">ตุลาคม</option>
                        <option value="11">พฤศจิกายน</option>
                        <option value="12">ธันวาคม</option>
                    </select><br>
                </td>
                <td style="font-weight:bold; color:#FF0000; text-align:left; background:#FFFFFF; font-size:18px">&nbsp;</td>
            </form>
            <td width="20%"></td>
            <td>
                <select name="employee" id="employee" class="form-control">
                    <option value="" disabled selected>ระบุชื่อพนักงานส่งบิล</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select>
            </td>
        </tr>
    </table>
    <!-- <div class="row">
        <div class="col-lg-12 text-center" id="loading">
            <p><img src="dashboard/loading.gif" height="55px" /></p>
        </div>
    </div> -->
    <table border="0" class="table fixwb">
        <tr>
            <th width="95">&nbsp;ชื่อผู้ส่งบิล</th>
            <td width="300"><input class="form-control" type="text" id="nBill" name="nBill" value="" readonly></td>
            <input type="hidden" id="xkey" name="xkey" value="">
            <input type="hidden" id="xName2" name="xName2" value="">
            <td width="10"></td>
            <td width="120"><button type="submit" class="btn btn-primary" id="signout" onclick="signout();"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</button></td>
            <td>** กรุณาออกจากระบบทุกครังเพื่อป้องกันการแก้ไขบิลที่ส่งแล้ว</td>
            <th width="90">วันที่ส่งบิล</th>
            <td width="220"><input class="form-control" type="text" id="nDate" value="" readonly></td>
        </tr>
    </table>
    <div id="grid-view">
        <table class="table fixwb table-hover" border="0">
            <head>
                <tr>
                    <th class="txtc txtb" width="90" colspan="2">เลขที่บิล </th>
                    <th>
                        <div><input class="form-control" type="text" id="BillNoX" name="BillNoX" value="" readonly></div>
                    </th>
                    <th class="txtc txtb" colspan="1"><button type="submit" name="checklist" class="btn btn-success btn-sm" onclick="showModal()"><i class="fas fa-tasks"></i> ตรวจสอบ</button></th>
                    <th class="txtr" colspan="5"><input id="myInput" type="text" placeholder="&nbsp;ค้นหาข้อมูล เลขที่บิล/ร้านค้า/พนักงานขาย/วันที่" class="inputf" style="width:350px;"></th>
                </tr>
                <tr>
                    <th class="txtc txtb">เลขที่บิล</th>
                    <th class="txtc txtb" width="110">วันที่</th>
                    <th class="txtc txtb">ลูกค้า</th>
                    <th class="txtc txtb" width="260">พนักงานขาย</th>
                    <th class="txtc txtb" width="100">ยอดรวม</th>
                    <th class="txtc txtb" width="110">วันที่ส่งของ</th>
                    <th class="txtc txtb" colspan="2" width="150">ผู้คืนบิล</th>
                </tr>
            </head>
            <tbody id="newBillList">
            </tbody>
        </table>
        
    </div>
    <!-- <div id="dataModal" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span style="font-size:24px;font-weight:bold;">ไม่พบข้อมูลในระบบ</span>
                </div>
                <div class="modal-body" id="newData">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="saveNew" class="btn btn-primary btn-sm" onclick="addnewX()"><i class="fa fa-save fa-fw"></i>บันทึก</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="chkmodal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <span style="font-size:24px;font-weight:bold;">รายงานการส่งบิล วันที่</span>
                </div>
                <div class="modal-body" id="chklist">
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="billx" id="billx" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> -->
</body>

</html>