<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Connections Monitoring</title>
    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <script src="https://kit.fontawesome.com/3288009746.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../media/favicon/icon.png"/>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1><i class="fas fa-database fa-fw fa-lg mr-1"></i> SQL Connections Monitoring</h1>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-lg-2" style="font-size : 20px">
                <label for="server">เลือกเซิร์ฟเวอร์</label>
            </div>
            <div class="col-lg-4">
                <select id="server" class="form-control form-control-lg">
                    <option value="MAIN" selected>เซิร์ฟเวอร์หลัก (.9)</option>
                    <option value="BKUP">เซิร์ฟเวอร์สำรอง (.13)</option>
                    <option value="SPB1">SAP (.3)</option>
                    <option value="HRMI">HRMI (.3.10)</option>
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 1rem;">
            <div class="col-lg-12" id="showdata"></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            function sqlconnection() {
                var srvrname = $("#server").val();
                //console.log(srvrname);
                $.ajax({
                    url: 'ajax_connection.php',
                    type: 'POST',
                    data: { server:srvrname },
                    success: function(data) {
                        $("#showdata").html(data);
                    }
                });
            }

            $("#server").on("change",function(){
                sqlconnection();
            });

            setInterval(sqlconnection,500);
        });
    </script>
</body>
</html>