<?php session_start();
require_once("../../core/functions.core.php");
if(!isset($_SESSION['UKEY'])) { echo "<script type='text/javascript'>window.location=\"../../\"</script>"; exit(); }
$JSON  = array();
$inval = array();

if($_GET['p'] == "MainMenu") {
    $SQL1 = "SELECT T0.MenuKey, T0.MenuName FROM menulists T0 WHERE T0.MenuLevel = '0' AND T0.MenuStatus = 'A' ORDER BY T0.MenuSort";
    $RST2 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST2) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
        $inval['Row'] = 0;
        foreach($RST2 as $key => $Row) {
            $inval[$key]['MenuKey']  = $Row['MenuKey'];
            $inval[$key]['MenuName'] = $Row['MenuName'];
            $inval['Row']++;
        }
    }
}

if($_GET['p'] == "GetMenuSort") {
    $MenuLevel = ($_POST['mlv'] == "null") ? "0" : $_POST['mlv'];
    $SQL1WHR1  = ($_POST['mhd'] == "null") ? "" : "AND T0.HeadMenuKey = '".$_POST['mhd']."'";

    $SQL1 = "SELECT IFNULL(MAX(T0.MenuSort)+1,0) AS 'Sort' FROM menulists T0 WHERE T0.MenuLevel = '$MenuLevel' $SQL1WHR1 AND T0.MenuSort < 900 LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    foreach($RST1 as $Row) {
        $inval['MenuSort'] = $Row['Sort'];
    }
}

if($_GET['p'] == "SaveMenu") {
    $txt_MenuKey = ($_POST['txt_MenuKey'] != "") ? $_POST['txt_MenuKey'] : "";

    $MenuKey     = md5($_POST['txt_MenuLink']);
    $MenuName    = ($_POST['txt_MenuName'] != "") ? $_POST['txt_MenuName'] : "" ;
    $MenuLevel   = ($_POST['txt_MenuLevel'] != "") ? $_POST['txt_MenuLevel'] : "" ;
    $HeadMenuKey = (isset($_POST['txt_HeadMenuKey']) != "") ? $_POST['txt_HeadMenuKey'] : "" ;
    $MenuCase    = ($_POST['txt_MenuCase'] != "") ? $_POST['txt_MenuCase'] : "" ;
    $MenuLink    = ($_POST['txt_MenuLink'] != "") ? $_POST['txt_MenuLink'] : "" ;
    $MenuIcon    = ($_POST['txt_MenuIcon'] != "") ? $_POST['txt_MenuIcon'] : "" ;
    $MenuSort    = ($_POST['txt_MenuSort'] != "") ? $_POST['txt_MenuSort'] : "" ;
    $uKey        = $_SESSION['UKEY'];
    $MenuClass   = "0000000000000000000";

    if(!file_exists("../".$MenuLink)) {
        mkdir("../".$MenuLink, 0777, true);

        $SQL1 = "SELECT MAX(T0.LvClass) AS 'MaxClass' FROM positions T0 LIMIT 1";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();

        $LvClass = "1";

        if($RST1) {
            foreach($RST1 as $Row) {
                $ClassLoop = $Row['MaxClass'];
            }

            for($i = 1; $i < $ClassLoop; $i++) {
                $LvClass .= "0";
            }
        }

        if($txt_MenuKey == "") {
            $SQL2 =
                "INSERT INTO menulists SET
                    MenuKey = :MenuKey,
                    HeadMenuKey = NULLIF(:HeadMenuKey,''),
                    MenuLevel = :MenuLevel,
                    MenuName = :MenuName,
                    MenuIcon = :MenuIcon,
                    MenuCase = :MenuCase,
                    MenuLink = NULLIF(:MenuLink,''),
                    MenuClass = :MenuClass,
                    MenuType = 'L',
                    MenuSort = :MenuSort,
                    uKeyCreate = :uKeyCreate,
                    DateCreate = NOW()";
        } else {
            $SQL2 =
                "UPDATE menulists SET
                    HeadMenuKey = NULLIF(:HeadMenuKey,''),
                    MenuLevel = :MenuLevel,
                    MenuName = :MenuName,
                    MenuIcon = :MenuIcon,
                    MenuSort = :MenuSort,
                    uKeyUpdate = :uKeyUpdate,
                    DateUpdate = NOW()
                WHERE MenuKey = :MenuKey";
        }

        $QRY2 = DBConnect("APP")->prepare($SQL2);

        ($txt_MenuKey == "") ? $QRY2->bindparam(":MenuKey", $MenuKey) : $QRY2->bindparam(":MenuKey", $txt_MenuKey);
        ($txt_MenuKey == "") ? $QRY2->bindparam(":MenuCase", $MenuCase) : null ;
        ($txt_MenuKey == "") ? $QRY2->bindparam(":MenuLink", $MenuLink) : null ;
        ($txt_MenuKey == "") ? $QRY2->bindparam(":MenuClass", $MenuClass) : null ;
        ($txt_MenuKey == "") ? $QRY2->bindparam(":uKeyCreate", $uKey) : $QRY2->bindparam(":uKeyUpdate", $uKey);

        $QRY2->bindparam(":HeadMenuKey", $HeadMenuKey);
        $QRY2->bindparam(":MenuLevel", $MenuLevel);
        $QRY2->bindparam(":MenuName", $MenuName);
        $QRY2->bindparam(":MenuIcon", $MenuIcon);
        $QRY2->bindparam(":MenuSort", $MenuSort);
        
        $RST2 = $QRY2->execute();
        
        $inval['Status'] = (!$RST2) ? "ERR : Insert Menu ไม่สำเร็จ" : "OK";

        if($inval['Status'] == 'OK') {
            $CreateMenu = fopen("../".$MenuLink."/".$MenuLink.".php", 'w');
            $dataC = file_get_contents("./FileCreate/menu.txt");
            fwrite($CreateMenu, $dataC);
            fclose($CreateMenu);

            $CreateMenu = fopen("../".$MenuLink."/app.js", 'w');
            $dataC = file_get_contents("./FileCreate/app.txt");
            fwrite($CreateMenu, $dataC);
            fclose($CreateMenu);

            $CreateMenu = fopen("../".$MenuLink."/ajax.php", 'w');
            $dataC = file_get_contents("./FileCreate/ajax.txt");
            fwrite($CreateMenu, $dataC);
            fclose($CreateMenu);

            $CreateMenu = fopen("../".$MenuLink."/style.css", 'w');
            $dataC = file_get_contents("./FileCreate/style.txt");
            fwrite($CreateMenu, $dataC);
            fclose($CreateMenu);
        }
    }else{
        if($txt_MenuKey != "") {
            $SQL2 =
                "UPDATE menulists SET
                    HeadMenuKey = NULLIF(:HeadMenuKey,''),
                    MenuLevel = :MenuLevel,
                    MenuName = :MenuName,
                    MenuIcon = :MenuIcon,
                    MenuSort = :MenuSort,
                    uKeyUpdate = :uKeyUpdate,
                    DateUpdate = NOW()
                WHERE MenuKey = :MenuKey";
            $QRY2 = DBConnect("APP")->prepare($SQL2);
            $QRY2->bindparam(":HeadMenuKey", $HeadMenuKey);
            $QRY2->bindparam(":MenuLevel", $MenuLevel);
            $QRY2->bindparam(":MenuName", $MenuName);
            $QRY2->bindparam(":MenuIcon", $MenuIcon);
            $QRY2->bindparam(":MenuSort", $MenuSort);
            $QRY2->bindparam(":MenuKey", $txt_MenuKey);
            $QRY2->bindparam(":uKeyUpdate", $uKey);
            $RST2 = $QRY2->execute();
            $inval['Status'] = (!$RST2) ? "ERR : Insert Menu ไม่สำเร็จ" : "OK";
        }else{
            $inval['Status'] = "ERR : MenuLink นี้มีในระบบแล้ว";
        }
    }
}

if($_GET['p'] == 'GetMenus') {
    $MenuClass = ($_SESSION['LVCLASS'] != '0') ? "SUBSTRING(T0.MenuClass, ".$_SESSION['LVCLASS'].", 1) = '1' AND" : "";
    $SQL = 
        "SELECT
            T0.MenuKey, T0.HeadMenuKey, T0.MenuLevel, T0.MenuName, T0.MenuIcon, T0.MenuCase, T0.MenuLink, T0.MenuSort, T0.MenuStatus,
            (SELECT COUNT(P0.MenuKey) FROM menulists P0 WHERE P0.HeadMenuKey = T0.MenuKey AND P0.MenuLevel = '1') AS 'SubMenuQty'
        FROM menulists T0
        WHERE $MenuClass T0.HeadMenuKey IS NULL
        ORDER BY T0.HeadMenuKey, T0.MenuSort";
    $RST = DBConnect("APP")->query($SQL)->fetchAll();
    $Tbody = "";
    foreach($RST as $key=>$Data) {
        $SubMenu = ($Data['SubMenuQty'] != 0) ? $Data['MenuCase'] : "";
        $Logo_HideShow = ($Data['MenuStatus'] == 'A') ? "<i class='fas fa-eye'></i>" : "<i class='fas fa-eye-slash'></i>";
        if($SubMenu == "") {
            $Tbody .= 
            "<tr>
                <td>
                    <div class='d-flex' style='width: 100%'>
                        <div style='width: 10%' class='text-center'>".($key+1)."</div>
                        <div style='width: 5%' class='text-end pe-3'></div>
                        <div style='width: 65%;'>".$Data['MenuIcon']." ".$Data['MenuName']."</div>
                        <div class='text-center width-btn-custom'>
                            <button class='btn btn-sm btn-success' id='Menu".$Data['MenuKey']."' onclick='Manager(\"HideShow\", \"".$Data['MenuKey']."||".$Data['MenuStatus']."\");'>$Logo_HideShow</button>
                            <button class='btn btn-sm btn-info' onclick='Manager(\"Edit\", \"".$Data['MenuKey']."\");'><i class='fas fa-edit'></i></button>
                            <button class='btn btn-sm btn-secondary' onclick='Manager(\"Permission\", \"".$Data['MenuKey']."\");'><i class='fas fa-tasks'></i></button>
                            <button class='btn btn-sm btn-danger' onclick='Manager(\"Delete\", \"".$Data['MenuKey']."\");'><i class='fas fa-trash-alt'></i></button>
                        </div>
                    </div>
                </td>
            </tr>";
        }else{
            $SQL_Sub = 
                "SELECT
                    T0.MenuKey, T0.HeadMenuKey, T0.MenuLevel, T0.MenuName, T0.MenuIcon, T0.MenuCase, T0.MenuLink, T0.MenuSort, T0.MenuStatus,
                    (SELECT COUNT(P0.MenuKey) FROM menulists P0 WHERE P0.HeadMenuKey = T0.MenuKey AND P0.MenuLevel = '1') AS 'SubMenuQty'
                FROM menulists T0
                WHERE $MenuClass T0.HeadMenuKey = '".$Data['MenuKey']."'
                ORDER BY T0.HeadMenuKey, T0.MenuSort";
            $RST_Sub = DBConnect("APP")->query($SQL_Sub);
            $Tbody .= 
            "<tr>
                <td>
                    <div class='d-flex' style='width: 100%'>
                        <div style='width: 10%' class='text-center'>".($key+1)."</div>
                        <div style='width: 5%' class='text-end pe-3'>
                            <a data-bs-toggle='collapse' href='#$SubMenu' role='button' aria-expanded='false' aria-controls='$SubMenu'>
                                <i class='far fa-plus-square'></i>
                            </a>
                        </div>
                        <div style='width: 65%'>".$Data['MenuIcon']." ".$Data['MenuName']."</div>
                        <div class='text-center width-btn-custom'>
                            <button class='btn btn-sm btn-success' id='Menu".$Data['MenuKey']."' onclick='Manager(\"HideShow\", \"".$Data['MenuKey']."||".$Data['MenuStatus']."\");'>$Logo_HideShow</button>
                            <button class='btn btn-sm btn-info' onclick='Manager(\"Edit\", \"".$Data['MenuKey']."\");'><i class='fas fa-edit'></i></button>
                            <button class='btn btn-sm btn-secondary' onclick='Manager(\"Permission\", \"".$Data['MenuKey']."\");'><i class='fas fa-tasks'></i></button>
                            <button class='btn btn-sm btn-danger' onclick='Manager(\"Delete\", \"".$Data['MenuKey']."\");'><i class='fas fa-trash-alt'></i></button>
                        </div>
                    </div>
                    <div class='collapse' id='$SubMenu'>
                        <table class='table talbe-sm table-hover table-borderless m-0'>";
                            foreach($RST_Sub as $Data_Sub) {
                                $LogoSub_HideShow = ($Data_Sub['MenuStatus'] == 'A') ? "<i class='fas fa-eye'></i>" : "<i class='fas fa-eye-slash'></i>";
                                $Tbody .= "
                                <tr>
                                    <td class='p-0 pt-2'>
                                        <div class='d-flex' style='width: 100%'>
                                            <div style='width: 10%' class='text-center'></div>
                                            <div style='width: 7%' class='text-end pe-3'>-</div>
                                            <div style='width: 63%'>".$Data_Sub['MenuIcon']." ".$Data_Sub['MenuName']."</div>
                                            <div class='text-center width-btn-custom'>
                                                <button class='btn btn-sm btn-success' id='Menu".$Data_Sub['MenuKey']."' onclick='Manager(\"HideShow\", \"".$Data_Sub['MenuKey']."||".$Data_Sub['MenuStatus']."\");'>$LogoSub_HideShow</button>
                                                <button class='btn btn-sm btn-info' onclick='Manager(\"Edit\", \"".$Data_Sub['MenuKey']."\");'><i class='fas fa-edit'></i></button>
                                                <button class='btn btn-sm btn-secondary' onclick='Manager(\"Permission\", \"".$Data_Sub['MenuKey']."\");'><i class='fas fa-tasks'></i></button>
                                                <button class='btn btn-sm btn-danger' onclick='Manager(\"Delete\", \"".$Data_Sub['MenuKey']."\");'><i class='fas fa-trash-alt'></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>";
                            }
                        $Tbody .= "   
                        </table>
                    </div>
                </td>
            </tr>";
        }
    }
    $inval['Tbody'] = $Tbody;
}

if($_GET['p'] == 'HideShow') {
    $MenuKey = $_POST['MenuKey'];
    $MenuStatus = ($_POST['MenuStatus'] == 'A') ? "I" : "A";
    $uKey = $_SESSION['UKEY'];
    
    $SQL = "UPDATE menulists SET MenuStatus = :MenuStatus, uKeyUpdate = :uKeyUpdate, dateUpdate = NOW() WHERE MenuKey = '$MenuKey'";
    $QRY = DBConnect("APP")->prepare($SQL);
    $QRY->bindparam(":MenuStatus", $MenuStatus);
    $QRY->bindparam(":uKeyUpdate", $uKey);
    $QRY->execute();
}

if($_GET['p'] == 'GetMenuInfo') {
    $MenuKey = $_POST['mnk'];
    $SQL1 = "SELECT * FROM menulists T0 WHERE T0.MenuKey = '$MenuKey' LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";

        $inval['MenuKey']     = $RST1[0]['MenuKey'];
        $inval['HeadMenuKey'] = $RST1[0]['HeadMenuKey'];
        $inval['MenuLevel']   = $RST1[0]['MenuLevel'];
        $inval['MenuName']    = $RST1[0]['MenuName'];
        $inval['MenuIcon']    = $RST1[0]['MenuIcon'];
        $inval['MenuCase']    = $RST1[0]['MenuCase'];
        $inval['MenuLink']    = $RST1[0]['MenuLink'];
        $inval['MenuSort']    = $RST1[0]['MenuSort'];
    }
}

if($_GET['p'] == "AppList") {
    $MenuKey = $_POST['MenuKey'];
    $MenuType = $_POST['MenuType'];
    $DeptCode = (isset($_POST['DeptCode'])) ? $_POST['DeptCode'] : "";

    switch($MenuType) {
        case "A":
            $SQL1 = "SELECT T0.DeptCode AS 'Value', T0.DeptName AS 'Text', T0.DeptCode AS 'DeptCode' FROM departments T0 ORDER BY T0.DeptCode ASC";
            $MenuClass = '1111111111111111111';
            $uKey = $_SESSION['UKEY'];
            $UPDATE1 = "UPDATE menulists SET MenuClass = :MenuClass, MenuType = :MenuType, uKeyUpdate = :uKeyUpdate, DateUpdate = NOW() WHERE MenuKey = '$MenuKey'";
            $QRY1 = DBConnect("APP")->prepare($UPDATE1);
            $QRY1->bindparam(":MenuClass", $MenuClass);
            $QRY1->bindparam(":MenuType", $MenuType);
            $QRY1->bindparam(":uKeyUpdate", $uKey);
            $QRY1->execute();
        break;
        case "D":
            $SQL1 = "SELECT T0.DeptCode AS 'Value', T0.DeptName AS 'Text', T0.DeptCode AS 'DeptCode' FROM departments T0 ORDER BY T0.DeptCode ASC";
        break;
        case "L":
            $SQL1 = "SELECT T0.LvCode AS 'Value', T0.LvName AS 'Text', T0.LvClass AS 'Class', T0.DeptCode AS 'DeptCode' FROM positions T0 WHERE T0.DeptCode = '$DeptCode' ORDER BY T0.LvClass";

            $SQL2 = "SELECT MenuClass, MenuType FROM menulists WHERE MenuKey = '$MenuKey'";
            $RST2 = DBConnect("APP")->query($SQL2)->fetchAll();
            $LvClass = null;
            for($i = 0; $i < strlen($RST2[0]['MenuClass']); $i++)  { 
                $c = $i+1;
                if(substr($RST2[0]['MenuClass'],$i,1) == 1) {
                    $SQL3 = "SELECT LvCode FROM positions WHERE LvClass = '$c'";
                    $RST3 = DBConnect("APP")->query($SQL3)->fetchAll();
                    foreach($RST3 as $key=>$Row) {
                        $LvClass .= $RST2[0]['MenuType']."_".$Row['LvCode']."||";
                    }
                }
            }
            $inval['LvClass'] = $LvClass;
        break;
    }

    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
    if(!$RST1) {
        $inval['Status'] = "ERR";
    } else {
        $inval['Status'] = "OK";
        $inval['Row'] = 0;
        foreach($RST1 as $key=>$Row) {
            $inval[$key]['Value']    = $Row['Value'];
            $inval[$key]['Text']     = $Row['Text'];
            $inval[$key]['DeptCode'] = $Row['DeptCode'];
            $inval[$key]['Class']    = ($MenuType == "L" || $MenuType == "C") ? $Row['Class'] : "" ;
            $inval['Row']++;
        }
    }

}


if($_GET['p'] == "DeleteMenu") {
    $MenuKey = $_POST['mnk'];
    $SQL1 = "SELECT IFNULL(COUNT(T0.MenuKey),0) AS 'Row' FROM menulists T0 WHERE T0.HeadMenuKey = '$MenuKey' LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();

    if($RST1[0]['Row'] == 0) {
        $SQL2 = "DELETE FROM menulists WHERE MenuKey = :MenuKey";
        $QRY2 = DBConnect("APP")->prepare($SQL2);
        $QRY2->bindparam(":MenuKey", $MenuKey);
        $RST2 = $QRY2->execute();
        $inval['Status'] = (!$RST2) ? "ERR::CANNOT_DELETE" : "OK";
    } else {
        $inval['Status'] = "ERR::CLEAR_SUBMENU";
    }
}

if($_GET['p'] == 'GetPermissions') {
    $MenuKey = $_POST['MenuKey'];
    $SQL = "SELECT MenuClass, MenuType FROM menulists WHERE MenuKey = '$MenuKey'";
    $RST = DBConnect("APP")->query($SQL)->fetchAll();
    $DeptCode = null;
    $LvClass = null;
    if($RST[0]['MenuType'] == 'L') {
        for($i = 0; $i < strlen($RST[0]['MenuClass']); $i++)  { 
            $c = $i+1;
            if(substr($RST[0]['MenuClass'],$i,1) == 1) {
                $SQL2 = "SELECT LvCode FROM positions WHERE LvClass = '$c'";
                $RST2 = DBConnect("APP")->query($SQL2)->fetchAll();
                foreach($RST2 as $key=>$Row) {
                    $LvClass .= $RST[0]['MenuType']."_".$Row['LvCode']."||";
                }
            }
            if($DeptCode == null) {
                switch($c) {
                    // Management
                    case "1": 
                        $DeptCode = (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP001" : null;
                    break;
                    // End Management
    
                    // Marketing
                    case "2": 
                    case "5": 
                    case "8": 
                    case "11": 
                        $DeptCode = (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP002" : null;
                    break;
                    // End Marketing
        
                    // Sale
                    case "3": 
                    case "6": 
                    case "9": 
                    case "12": 
                    case "14": 
                    case "15": 
                    case "16": 
                        $DeptCode = (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP003" : null;
                    break;
                    // End Sale
    
                    // Account
                    case "4": 
                    case "7": 
                    case "10": 
                    case "13": 
                        $DeptCode = (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP004" : null;
                    break;
                    // End Account

                    // Where House
                    case "17": 
                    case "18": 
                    case "19": 
                        $DeptCode = (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP005" : null;
                    break;
                    // End Where House
                }
            }
        }

        if($DeptCode == null) { $DeptCode = "DP000"; }
        if($LvClass != null) { $LvClass = substr($LvClass,0,-2); }
    }else if ($RST[0]['MenuType'] == 'D') {
        for($i = 0; $i < strlen($RST[0]['MenuClass']); $i++)  { 
            $c = $i+1;
            if(substr($RST[0]['MenuClass'],$i,1) == 1) {
                $SQL2 = "SELECT LvCode FROM positions WHERE LvClass = '$c'";
                $RST2 = DBConnect("APP")->query($SQL2)->fetchAll();
                foreach($RST2 as $key=>$Row) {
                    $LvClass .= $RST[0]['MenuType']."_".$Row['LvCode']."||";
                }
            }
            switch($c) {
                // Management
                case "1": 
                    $DeptCode .= (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP001||" : null;
                break;
                // End Management

                // Marketing
                case "2": 
                case "5": 
                case "8": 
                case "11": 
                    $DeptCode .= (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP002||" : null;
                break;
                // End Marketing
    
                // Sale
                case "3": 
                case "6": 
                case "9": 
                case "12": 
                case "14": 
                case "15": 
                case "16": 
                    $DeptCode .= (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP003||" : null;
                break;
                // End Sale

                // Account
                case "4": 
                case "7": 
                case "10": 
                case "13": 
                    $DeptCode .= (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP004||" : null;
                break;
                // End Account

                // Where House
                case "17": 
                case "18": 
                case "19": 
                    $DeptCode .= (substr($RST[0]['MenuClass'],$i,1) == 1) ? "DP005" : null;
                break;
                // End Where House
            }
        }
    }

    $inval['DeptCode'] = $DeptCode;
    $inval['LvClass'] = $LvClass;
    $inval['MenuType'] = $RST[0]['MenuType'];
    
}

if($_GET['p'] == 'SaveClass') {
    $MenuType = $_POST['MenuType'];
    $DeptCode = $_POST['DeptCode'];
    $LvClass = $_POST['LvClass'];
    $MenuKey = $_POST['MenuKey'];
    $uKey = $_SESSION['UKEY'];

    if($MenuType == 'D') {
        $SQL1 = "SELECT MenuClass, MenuType FROM menulists WHERE MenuKey = '$MenuKey'";
        $RST1 = DBConnect("APP")->query($SQL1)->fetchAll();
        if($RST1[0]['MenuType'] != 'D') {
            $MenuClass = '0000000000000000000';
            $UPDATE0 = "UPDATE menulists SET MenuClass = :MenuClass, MenuType = :MenuType, uKeyUpdate = :uKeyUpdate, DateUpdate = NOW() WHERE MenuKey = '$MenuKey'";
            $QRY0 = DBConnect("APP")->prepare($UPDATE0);
            $QRY0->bindparam(":MenuClass", $MenuClass);
            $QRY0->bindparam(":MenuType", $MenuType);
            $QRY0->bindparam(":uKeyUpdate", $uKey);
            $QRY0->execute();
        }
        $MenuClass = null;
        $SQL2 = "SELECT MenuClass FROM menulists WHERE MenuKey = '$MenuKey'";
        $RST2 = DBConnect("APP")->query($SQL2)->fetchAll();
        for($i = 0; $i < strlen($RST2[0]['MenuClass']); $i++)  { 
            $c = $i+1;
            switch($c) {
                // Management
                case "1": 
                    $MenuClass .= ($DeptCode == 'DP001') ? $LvClass : substr($RST2[0]['MenuClass'],$i,1);
                break;
                // End Management
    
                // Marketing
                case "2": 
                case "5": 
                case "8": 
                case "11": 
                    $MenuClass .= ($DeptCode == 'DP002') ? $LvClass : substr($RST2[0]['MenuClass'],$i,1);
                break;
                // End Marketing
    
                // Sale
                case "3": 
                case "6": 
                case "9": 
                case "12": 
                case "14": 
                case "15": 
                case "16": 
                    $MenuClass .= ($DeptCode == 'DP003') ? $LvClass : substr($RST2[0]['MenuClass'],$i,1);
                break;
                // End Sale
    
                // Account
                case "4": 
                case "7": 
                case "10": 
                case "13": 
                    $MenuClass .= ($DeptCode == 'DP004') ? $LvClass : substr($RST2[0]['MenuClass'],$i,1);
                break;
                // End Account

                // Where House
                case "17": 
                case "18": 
                case "19": 
                    $MenuClass .= ($DeptCode == 'DP005') ? $LvClass : substr($RST2[0]['MenuClass'],$i,1);
                break;
                // End Where House
            }
        }
        
        $UPDATE1 = "UPDATE menulists SET MenuClass = :MenuClass, MenuType = :MenuType, uKeyUpdate = :uKeyUpdate, DateUpdate = NOW() WHERE MenuKey = '$MenuKey'";
        $QRY1 = DBConnect("APP")->prepare($UPDATE1);
        $QRY1->bindparam(":MenuClass", $MenuClass);
        $QRY1->bindparam(":MenuType", $MenuType);
        $QRY1->bindparam(":uKeyUpdate", $uKey);
        $QRY1->execute();
    }else{
        $LvCode = $DeptCode;
        $SQL2 = "SELECT LvClass, DeptCode FROM positions WHERE LvCode = '$LvCode' LIMIT 1";
        $RST2 = DBConnect("APP")->query($SQL2)->fetchAll();
        $DeptCode = $RST2[0]['DeptCode'];
        $rowLvClass = $RST2[0]['LvClass'];

        $SQL4 = "SELECT MenuType FROM menulists WHERE MenuKey = '$MenuKey'";
        $RST4 = DBConnect("APP")->query($SQL4)->fetchAll();
        if($RST4[0]['MenuType'] != 'L') {
            $MenuClass = '0000000000000000000';
            $UPDATE0 = "UPDATE menulists SET MenuClass = :MenuClass, MenuType = :MenuType, uKeyUpdate = :uKeyUpdate, DateUpdate = NOW() WHERE MenuKey = '$MenuKey'";
            $QRY0 = DBConnect("APP")->prepare($UPDATE0);
            $QRY0->bindparam(":MenuClass", $MenuClass);
            $QRY0->bindparam(":MenuType", $MenuType);
            $QRY0->bindparam(":uKeyUpdate", $uKey);
            $QRY0->execute();
        }

        $SQL3 = "SELECT MenuClass FROM menulists WHERE MenuKey = '$MenuKey'";
        $RST3 = DBConnect("APP")->query($SQL3)->fetchAll();

        $MenuClass = null;
        for($i = 0; $i < strlen($RST3[0]['MenuClass']); $i++)  { 
            $c = $i+1;
            switch($c) {
                // Management
                case "1": 
                    if($c == $rowLvClass) {
                        $MenuClass .= $LvClass;
                    }else{
                        $MenuClass .= (substr($RST3[0]['MenuClass'],$i,1) == 1) ? "1" : "0";
                    }
                break;
                // End Management
    
                // Marketing
                case "2": 
                case "5": 
                case "8": 
                case "11": 
                    if($c == $rowLvClass) {
                        $MenuClass .= $LvClass;
                    }else{
                        $MenuClass .= (substr($RST3[0]['MenuClass'],$i,1) == 1) ? "1" : "0";
                    }
                break;
                // End Marketing
    
                // Sale
                case "3": 
                case "6": 
                case "9": 
                case "12": 
                case "14": 
                case "15": 
                case "16": 
                    if($c == $rowLvClass) {
                        $MenuClass .= $LvClass;
                    }else{
                        $MenuClass .= (substr($RST3[0]['MenuClass'],$i,1) == 1) ? "1" : "0";
                    }
                break;
                // End Sale
    
                // Account
                case "4": 
                case "7": 
                case "10": 
                case "13": 
                    if($c == $rowLvClass) {
                        $MenuClass .= $LvClass;
                    }else{
                        $MenuClass .= (substr($RST3[0]['MenuClass'],$i,1) == 1) ? "1" : "0";
                    }
                break;
                // End Account

                // Where House
                case "17": 
                case "18": 
                case "19": 
                    if($c == $rowLvClass) {
                        $MenuClass .= $LvClass;
                    }else{
                        $MenuClass .= (substr($RST3[0]['MenuClass'],$i,1) == 1) ? "1" : "0";
                    }
                break;
                // End Where House
            }
        }
        $UPDATE2 = "UPDATE menulists SET MenuClass = :MenuClass, MenuType = :MenuType, uKeyUpdate = :uKeyUpdate, DateUpdate = NOW() WHERE MenuKey = '$MenuKey'";
        $QRY2 = DBConnect("APP")->prepare($UPDATE2);
        $QRY2->bindparam(":MenuClass", $MenuClass);
        $QRY2->bindparam(":MenuType", $MenuType);
        $QRY2->bindparam(":uKeyUpdate", $uKey);
        $QRY2->execute();
    }
}

if($_GET['p'] == 'GetMenuCase') {
    $MenuKey = $_POST['MenuKey'];
    $SQL = "SELECT T0.MenuCase FROM menulists T0 WHERE MenuKey = '$MenuKey'";
    $RST = DBConnect("APP")->query($SQL)->fetchAll();
    $inval['MenuCase'] = ($RST) ? $RST[0]['MenuCase'] : "";
}

array_push($JSON,$inval);
echo json_encode($JSON);
?>