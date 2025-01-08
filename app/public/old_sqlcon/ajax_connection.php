<?php
    session_start();
    date_default_timezone_set("Asia/Bangkok");
    function sectoTime($second) {
        $s = $second%60;
        if($s < 10) { $s = "0".$s; } else { $s; }
        $h = floor(($second%86400)/3600);
        if($h < 10) { $h = "0".$h; } else { $h; }
        $m = floor(($second%3600)/60);
        if($m < 10) { $m = "0".$m; } else { $m; }
        $d = floor(($second%2592000)/86400);
        if($d > 0) {
            return "$d วัน $h:$m:$s";
        } else {
            return "$h:$m:$s";
        }
    }
    $server = $_POST['server'];
    /* get client computer name */
    $comp = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    if($server == "MAIN" OR $server == "BKUP") {
        switch($server) {
            case "BKUP":
                $srvrname = '192.168.1.13';
                $dtbsname = 'kbidb';
                $username = 'waiwai';
                $password = 'passw0rd!';
            break;
            default:
                $srvrname = '192.168.1.9';
                $dtbsname = 'kbidb';
                $username = 'kbi';
                $password = 'passw0rd!';
            break;
        }
        $conn = new mysqli($srvrname, $username, $password, $dtbsname);
        $conn->set_charset("utf8");
        /* check connection */
        $sqls = "SELECT '".$_SESSION['name']." ".$_SESSION['lname']."' AS 'Query Name', '".$_SERVER['REMOTE_ADDR']."' AS 'Query IP', t.PROCESSLIST_ID,t.PROCESSLIST_HOST,t.PROCESSLIST_DB,t.PROCESSLIST_COMMAND,t.PROCESSLIST_TIME,t.PROCESSLIST_STATE,t.PROCESSLIST_INFO FROM performance_schema.threads t LEFT OUTER JOIN performance_schema.session_connect_attrs a ON t.processlist_id = a.processlist_id AND (a.attr_name IS NULL OR a.attr_name = 'program_name')  WHERE t.TYPE <> 'BACKGROUND' ORDER BY t.PROCESSLIST_HOST ASC";
        $stmt = $conn->query($sqls);
        /* get rows */
        $rows = $stmt->num_rows-1;
        /* get checker ipaddress */
        $sqlc = "SELECT DISTINCT T0.IPAddress FROM checkertable T0 ORDER BY T0.TableID ASC";
        $stmc = $conn->query($sqlc);
        $checker = array();
        while($ip = $stmc->fetch_assoc()) {
            array_push($checker,$ip['IPAddress']);
        }
        $counter = array();
        $tmpIP = "";
        echo "<div class=\"table-responsive\">";
            echo "<table class=\"table table-bordered table-hover\">";
                echo"<thead>";
                    echo "<tr>";
                        echo "<th style=\"width:5%;\">No</th>";
                        echo "<th style=\"width:15%;\">IP Address</th>";
                        echo "<th style=\"width:10%;\">ชื่อฐานข้อมูล</th>";
                        echo "<th style=\"width:10%;\">คำสั่ง</th>";
                        echo "<th style=\"width:10%;\">ระยะเวลา</th>";
                        echo "<th style=\"width:10%;\">สถานะ</th>";
                        echo "<th >รายละเอียด</th>";
                    echo "</tr>";
                echo"</thead>";
                echo "<tbody>"; 
                $no = 0;
                while($result = $stmt->fetch_assoc()) {
                    if($tmpIP != $result['PROCESSLIST_HOST']) {
                        array_push($counter,$result['PROCESSLIST_HOST']);
                        ${$result['PROCESSLIST_HOST']} = 1;
                        $tmpIP = $result['PROCESSLIST_HOST'];
                    } else {
                        ${$result['PROCESSLIST_HOST']} = ${$result['PROCESSLIST_HOST']}+1;
                    }
                    if($no != 0) {
                        if($comp == $result['PROCESSLIST_HOST']) {
                            echo "<tr class=\"success\">";
                        } else if(in_array($result['PROCESSLIST_HOST'],$checker,TRUE)) {
                            echo "<tr class=\"info\">";
                        } else if($result['PROCESSLIST_HOST'] == "localhost") {
                            echo "<tr class=\"danger\">";
                        } else {
                            echo "<tr>";
                        }
                    } else {
                        echo "<tr class=\"active text-muted\">";
                    }
                        echo "<td class=\"text-center\">".$no."</td>";
                        echo "<td>".$result['PROCESSLIST_HOST']."</td>";
                        echo "<td class=\"text-center\">".$result['PROCESSLIST_DB']."</td>";
                        echo "<td>".$result['PROCESSLIST_COMMAND']."</td>";
                        echo "<td class=\"text-center\">".sectoTime($result['PROCESSLIST_TIME'])."</td>";
                        echo "<td>".$result['PROCESSLIST_STATE']."</td>";
                    if($result['PROCESSLIST_INFO'] != "") {
                        $info = $result['PROCESSLIST_INFO'];
                    } else {
                        $info = NULL;
                    }
                        echo "<td>".$info."</td>";
                    echo "</tr>";
                    $no++;
                }
                    $row = $no-1;
                    echo "<tr>";
                        echo "<td colspan=\"7\"><strong>Total Connection: </strong>".$row."</td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
        echo "<hr/>";
        echo "<div class=\"table-responsive\">";
            echo "<table class=\"table table-bordered table-hover\">";
                echo"<thead>";
                    echo "<tr>";
                        echo "<th style=\"width:50%;\">IP Address</th>";
                        echo "<th >Connections</th>";
                    echo "</tr>";
                echo"</thead>";
                echo "<tbody>";
                foreach($counter as $ct) {
                if($comp == $ct) {
                    echo "<tr class=\"success\">";
                } else if(in_array($ct,$checker,TRUE)) {
                    echo "<tr class=\"info\">";
                } else if($ct == "localhost") {
                    echo "<tr class=\"danger\">";
                } else {
                    echo "<tr>";
                }
                        echo "<td>".$ct."</td>";
                        echo "<td class=\"text-center\">".${$ct}."</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "<tfoot>";
                    echo "<tr class=\"active\">";
                        echo "<th class=\"text-left\">รวมทั้งหมด</th>";
                        echo "<th class=\"text-center\">".$rows."</th>";
                    echo "</tr>";
                echo "</tfoot>";
            echo "</table>";
        echo "</div>";
    } else {
        if ($server == 'SPB1'){
            $srvrname = '192.168.1.9';
            $dtbsname = 'kbidb';
            $username = 'kbi';
            $password = 'passw0rd!';
            $conn = new mysqli($srvrname, $username, $password, $dtbsname);
            /* get checker ipaddress */
            $sqlc = "SELECT DISTINCT T0.IPAddress FROM checkertable T0 ORDER BY T0.TableID ASC";
            $stmc = $conn->query($sqlc);
            $checker = array();
            while($ip = $stmc->fetch_assoc()) {
                array_push($checker,$ip['IPAddress']);
            }
            $conn->set_charset("utf8");
            $sap_drvr = '{SQL Server Native Client 11.0}';
            $sap_drvr = '{SQL Server Native Client 11.0}';
            $sap_host = '192.168.1.3';
            $sap_usnm = 'sa';
            $sap_pswd = 'p@$$w0rd';
            $sap_dbnm = 'KBI_DB';
            $sap_conn = odbc_connect("DRIVER=$sap_drvr;charset=UTF8;SERVER=$sap_host;DATABASE=$sap_dbnm",$sap_usnm,$sap_pswd) or die ("Cannot Connect to SAP Database! #161");
            $sqls = "select '".$_SESSION['name']." ".$_SESSION['lname']."' AS 'Query Name', '".$_SERVER['REMOTE_ADDR']."' AS 'Query IP', r.session_id, r.status, DATEDIFF(second,r.start_time,GETDATE()) AS 'Time', s.login_name, c.client_net_address, s.host_name, s.program_name, st.text from sys.dm_exec_requests r inner join sys.dm_exec_sessions s on r.session_id = s.session_id left join sys.dm_exec_connections c on r.session_id = c.session_id outer apply sys.dm_exec_sql_text(r.sql_handle) st where r.session_id  > 50 AND c.client_net_address NOT IN ('10.0.0.1') ORDER BY DATEDIFF(second,r.start_time,GETDATE()) DESC";
            $stmt = odbc_exec($sap_conn,$sqls);
            $counter = array();
            $tmpIP = "";
            echo "<div class=\"table-responsive\">";
                echo "<table class=\"table table-bordered table-hover\">";
                    echo"<thead>";
                        echo "<tr>";
                            echo "<th style=\"width:5%;\">No</th>";
                            echo "<th style=\"width:10%;\">IP Address</th>";
                            echo "<th style=\"width:15%;\">HOST NAME</th>";
                            echo "<th style=\"width:20%;\">โปรแกรม</th>";
                            echo "<th style=\"width:10%;\">ระยะเวลา</th>";
                            echo "<th style=\"width:7.5%;\">สถานะ</th>";
                            echo "<th >รายละเอียด</th>";
                        echo "</tr>";
                    echo"</thead>";
                    echo "<tbody>";
                    $no = 1;
                    while($result = odbc_fetch_array($stmt)) {
                    if($tmpIP != $result['client_net_address']) {
                        array_push($counter,$result['client_net_address']);
                        ${$result['client_net_address']} = 1;
                        $tmpIP = $result['client_net_address'];
                    } else {
                        ${$result['client_net_address']} = ${$result['client_net_address']}+1;
                    }
                    if($comp == $result['client_net_address']) {
                        echo "<tr class=\"success\">";
                    } else if(in_array($result['client_net_address'],$checker,TRUE)) {
                        echo "<tr class=\"info\">";
                    } else if($result['client_net_address'] == "192.168.1.9") {
                        echo "<tr class=\"danger\">";
                    } else {
                        echo "<tr>";
                    }
                            echo "<td class=\"text-center\">".$no."</td>";
                            echo "<td>".$result['client_net_address']."</td>";
                            echo "<td>".$result['host_name']."</td>";
                            echo "<td>".$result['program_name']."</td>";
                            echo "<td class=\"text-center\">".sectoTime($result['Time'])."</td>";
                            echo "<td>".$result['status']."</td>";
                        if($result['text'] != "") {
                            $info = $result['text'];
                        } else {
                            $info = NULL;
                        }
                            echo "<td>".$info."</td>";
                        echo "</tr>";
                        $no++;
                    }
                        $rows = $no-1;
                        echo "<tr>";
                            echo "<td colspan=\"7\"><strong>Total Connection: </strong>".$rows."</td>";
                        echo "</tr>";
                    echo "</tbody>";
                echo "</table>";
            echo "</div>";
            echo "<hr/>";
            echo "<div class=\"table-responsive\">";
                echo "<table class=\"table table-bordered table-hover\">";
                    echo"<thead>";
                        echo "<tr>";
                            echo "<th style=\"width:50%;\">IP Address</th>";
                            echo "<th >Connections</th>";
                        echo "</tr>";
                    echo"</thead>";
                    echo "<tbody>";
                    $conn_total = 0;
                    foreach($counter as $ct) {
                    if($comp == $ct) {
                        echo "<tr class=\"success\">";
                    } else if(in_array($ct,$checker,TRUE)) {
                        echo "<tr class=\"info\">";
                    } else if($ct == "192.168.1.9") {
                        echo "<tr class=\"danger\">";
                    } else {
                        echo "<tr>";
                    }
                            echo "<td>".$ct."</td>";
                            echo "<td class=\"text-center\">".${$ct}."</td>";
                        echo "</tr>";
                        $conn_total = $conn_total+${$ct};
                    }
                    echo "</tbody>";
                    echo "<tfoot>";
                        echo "<tr class=\"active\">";
                            echo "<th class=\"text-left\">รวมทั้งหมด</th>";
                            echo "<th class=\"text-center\">".$conn_total."</th>";
                        echo "</tr>";
                    echo "</tfoot>";
                echo "</table>";
            echo "</div>";
        }else{
            /*
            $srvrname = '192.168.1.9';
            $dtbsname = 'kbidb';
            $username = 'kbi';
            $password = 'passw0rd!';
            $conn = new mysqli($srvrname, $username, $password, $dtbsname);
            /* get checker ipaddress */
            /*
            $sqlc = "SELECT DISTINCT T0.IPAddress FROM checkertable T0 ORDER BY T0.TableID ASC";
            $stmc = $conn->query($sqlc);
            $checker = array();
            while($ip = $stmc->fetch_assoc()) {
                array_push($checker,$ip['IPAddress']);
            }
            */
            //$conn->set_charset("utf8");
            //$sap_drvr = '{SQL Server Native Client 11.0}';
            $sap_drvr = '{SQL Server Native Client 11.0}';
            $sap_host = '192.168.3.10\SERVER2';
            $sap_usnm = 'sa';
            $sap_pswd = 'p@ssw0rd';
            $sap_dbnm = 'db_hrmi_2022';
            $sap_conn = odbc_connect("DRIVER=$sap_drvr;charset=UTF8;SERVER=$sap_host;DATABASE=$sap_dbnm",$sap_usnm,$sap_pswd) or die ("Cannot Connect to SAP Database! #161");
            $sqls = "select '".$_SESSION['name']." ".$_SESSION['lname']."' AS 'Query Name', '".$_SERVER['REMOTE_ADDR']."' AS 'Query IP', r.session_id, r.status, DATEDIFF(second,r.start_time,GETDATE()) AS 'Time', s.login_name, c.client_net_address, s.host_name, s.program_name, st.text from sys.dm_exec_requests r inner join sys.dm_exec_sessions s on r.session_id = s.session_id left join sys.dm_exec_connections c on r.session_id = c.session_id outer apply sys.dm_exec_sql_text(r.sql_handle) st where r.session_id  > 50 AND c.client_net_address NOT IN ('10.0.0.1') ORDER BY c.client_net_address, r.start_time ASC";
            $stmt = odbc_exec($sap_conn,$sqls);
            $counter = array();
            $tmpIP = "";
            echo "<div class=\"table-responsive\">";
                echo "<table class=\"table table-bordered table-hover\">";
                    echo"<thead>";
                        echo "<tr>";
                            echo "<th style=\"width:5%;\">No</th>";
                            echo "<th style=\"width:10%;\">IP Address</th>";
                            echo "<th style=\"width:15%;\">HOST NAME</th>";
                            echo "<th style=\"width:20%;\">โปรแกรม</th>";
                            echo "<th style=\"width:10%;\">ระยะเวลา</th>";
                            echo "<th style=\"width:7.5%;\">สถานะ</th>";
                            echo "<th >รายละเอียด</th>";
                        echo "</tr>";
                    echo"</thead>";
                    echo "<tbody>";
                    $no = 1;
                    while($result = odbc_fetch_array($stmt)) {
                    if($tmpIP != $result['client_net_address']) {
                        array_push($counter,$result['client_net_address']);
                        ${$result['client_net_address']} = 1;
                        $tmpIP = $result['client_net_address'];
                    } else {
                        ${$result['client_net_address']} = ${$result['client_net_address']}+1;
                    }
                    if($comp == $result['client_net_address']) {
                        echo "<tr class=\"success\">";
                    } else if(in_array($result['client_net_address'],$checker,TRUE)) {
                        echo "<tr class=\"info\">";
                    } else if($result['client_net_address'] == "192.168.1.9") {
                        echo "<tr class=\"danger\">";
                    } else {
                        echo "<tr>";
                    }
                            echo "<td class=\"text-center\">".$no."</td>";
                            echo "<td>".$result['client_net_address']."</td>";
                            echo "<td>".$result['host_name']."</td>";
                            echo "<td>".$result['program_name']."</td>";
                            echo "<td class=\"text-center\">".sectoTime($result['Time'])."</td>";
                            echo "<td>".$result['status']."</td>";
                        if($result['text'] != "") {
                            $info = $result['text'];
                        } else {
                            $info = NULL;
                        }
                            echo "<td>".$info."</td>";
                        echo "</tr>";
                        $no++;
                    }
                        $rows = $no-1;
                        echo "<tr>";
                            echo "<td colspan=\"7\"><strong>Total Connection: </strong>".$rows."</td>";
                        echo "</tr>";
                    echo "</tbody>";
                echo "</table>";
            echo "</div>";
            echo "<hr/>";
            echo "<div class=\"table-responsive\">";
                echo "<table class=\"table table-bordered table-hover\">";
                    echo"<thead>";
                        echo "<tr>";
                            echo "<th style=\"width:50%;\">IP Address</th>";
                            echo "<th >Connections</th>";
                        echo "</tr>";
                    echo"</thead>";
                    echo "<tbody>";
                    $conn_total = 0;
                    foreach($counter as $ct) {
                    if($comp == $ct) {
                        echo "<tr class=\"success\">";
                    } else if(in_array($ct,$checker,TRUE)) {
                        echo "<tr class=\"info\">";
                    } else if($ct == "192.168.1.9") {
                        echo "<tr class=\"danger\">";
                    } else {
                        echo "<tr>";
                    }
                            echo "<td>".$ct."</td>";
                            echo "<td class=\"text-center\">".${$ct}."</td>";
                        echo "</tr>";
                        $conn_total = $conn_total+${$ct};
                    }
                    echo "</tbody>";
                    echo "<tfoot>";
                        echo "<tr class=\"active\">";
                            echo "<th class=\"text-left\">รวมทั้งหมด</th>";
                            echo "<th class=\"text-center\">".$conn_total."</th>";
                        echo "</tr>";
                    echo "</tfoot>";
                echo "</table>";
            echo "</div>";
        }
    }
?>