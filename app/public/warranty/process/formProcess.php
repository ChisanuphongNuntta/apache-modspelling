<?php
$msg = "";
$errorMSG = "";
/* INDEX */
if ($_GET['a'] == 'index') {
    if (empty($_POST["name"])) {
        $errorMSG = "ชื่อนามสกุล";
    } else {
        if (empty($_POST['age'])) {
            $errorMSG = "อายุ";
        } else {
            if (empty($_POST['phone'])) {
                $errorMSG = "เบอร์โทรศัพท์";
            } else {
                if ($statusPhone) {
                    $msg = $statusPhone;
                    if (empty($_POST["email"])) {
                        $errorMSG .= "";
                    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                        $errorMSG .= "อีเมลให้ถูกต้อง";
                    } else {
                    }
                } else {
                    $errorMSG .= "หมายเลขโทรศัพท์ให้ถูกต้อง";
                }
            }
        }
    }
}
/* PAGE1 */
if ($_GET['a'] == 'info2') {
    if (empty($_POST["date"])) {
        $errorMSG .= "วันที่";
    } else {
        if ($_POST["market"] == 0) {
            if (empty($_POST["otherTxt"])) {
                $errorMSG .= "ชื่อห้าง";
            } else {
                $errorMSG .= "";
            }
        } else {
            if (empty($_POST["branch"])) {
                $errorMSG .= "สาขา";
            } else {
                $errorMSG .= "";
            }
        }
        
    }
    
    
}

if (empty($errorMSG)) {

    echo json_encode(['code' => 200, 'output' => $msg]);
    exit;
}
echo json_encode(['code' => 404, 'output' => $errorMSG]);