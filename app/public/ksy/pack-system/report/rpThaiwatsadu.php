<?php
$DocNum = $_GET['DocNum'];

ob_start();
require('../fpdf/fpdf.php');
$pdf = new FPDF('L','mm', array(210,148));
$pdf->SetMargins(7,7,7,0);
$pdf->SetTitle("ใบปะหน้ากล่อง - ไทวัสดุ", true);
$pdf->AddPage();

$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.3);

// Row 1
    // Column 1
    $pdf->Cell( 40, 10, $pdf->Image("img/Thaiwatsadu.gif", $pdf->GetX(), $pdf->GetY(), 40), "LT", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 10, 5, iconv( 'UTF-8','cp874' , "ส่งไปที่"), "LTRB", 0, 'L');
    // Column 3
    $pdf->Cell( 0, 5, "", "TR", 0, 'L');
    
    $pdf->Ln();

// Row 2
    // Column 1
    $pdf->Cell( 40, 6, "", "L", 0, 'L');
    // Column 2
    $pdf->Cell( 90, 6, iconv( 'UTF-8','cp874' , "สาขาผู้รับ"), "", 0, 'L');
    // Column 3
    $pdf->Cell( 15, 6, iconv( 'UTF-8','cp874' , "รหัสสาขา"), "", 0, 'L');
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',20);
    $pdf->Cell( 6, 6, "6", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "0", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "9", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "8", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "7", "LTRB", 0, 'L');
    $pdf->Cell( 0, 6, "", "R", 0, 'L');

    $pdf->Ln();

// Row 3
    // Column 1
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',15);
    $pdf->Cell( 40, 7, "", "L", 0, 'L');
    // Column 2
    $pdf->Cell( 0, 7, iconv( 'UTF-8','cp874' , "ซีอาร์ ซีไทวสัดุ(สาขากําแพงเพชร)"), "R", 0, 'L');

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',15);
    $pdf->Text(48, 24, "                                                                         ");

    $pdf->Ln();

// Row 4 
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 40, 10, "", "L", 0, 'L');
    // Column 2
    $pdf->Cell( 20, 6, iconv( 'UTF-8','cp874' , "แผนกย่อย"), "", 0, 'L');
    $pdf->Cell( 70, 6, iconv( 'UTF-8','cp874' , ""), "", 0, 'L');
    // Column 3
    $pdf->Cell( 20, 6, iconv( 'UTF-8','cp874' , "รหัสแผนกย่อย"), "", 0, 'L');
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',20);
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 7, 6, iconv( 'UTF-8','cp874' , "ชั้น"), "", 0, 'R');
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , ""), "R", 0, 'L');

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',15);
    $pdf->Text(182, 31, "               ");

    $pdf->Ln();

// Row 5
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 40, 10, "", "LB", 0, 'L');
    // Column 2
    $pdf->Cell( 90, 10, "", "B", 0, 'L');
    // Column 3
    $pdf->Cell( 19, 10, iconv( 'UTF-8','cp874' , "เลขที่เอกสาร"), "B", 0, 'L');
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',20);
    $pdf->Cell( 0, 10, "660602195", "RB", 0, 'L');

    $pdf->Ln();

// Row 6
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 24, 6, iconv( 'UTF-8','cp874' , "สำหรับร้านค้าระบุ"), "LRB", 0, 'L');
    // Column 2
    $pdf->Cell( 0, 6, "", "R", 0, 'L');

    $pdf->Ln();

// Row 7
    // Column 1
    $pdf->Cell( 20, 12, iconv( 'UTF-8','cp874' , "ชื่อบริษัทผู้ส่ง"), "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',20);
    $pdf->Cell( 110, 12, iconv( 'UTF-8','cp874' , "บริษัท คิงบางกอก อินเตอร์เทรด จำกัด"), "", 0, 'L');
    // Column 3
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 10, 12, iconv( 'UTF-8','cp874' , "วันที่"), "", 0, 'L');
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',20);
    $pdf->Cell( 0, 12, "03/07/2023", "R", 0, 'L');

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',15);
    $pdf->Text(7, 56, "                                                                                                           ");
    $pdf->Text(146, 56, "                         ");

    $pdf->Ln();

// Row 8
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 20, 8, iconv( 'UTF-8','cp874' , "เลขที่ Invoice"), "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',20);
    $pdf->Cell( 110, 8, "IV-660703012XD", "", 0, 'L');
    // Column 3
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 15, 8, iconv( 'UTF-8','cp874' , "รหัสบริษัท"), "", 0, 'L');
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',20);
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');
    $pdf->Cell( 0.5, 6, "", "", 0, 'L');
    $pdf->Cell( 6, 6, "", "LTRB", 0, 'L');

    $pdf->Cell( 0, 8, "", "R", 0, 'L');

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',15);
    $pdf->Text(7, 66, "                                                                                                           ");

    $pdf->Ln();

// Row 9
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 20, 10, iconv( 'UTF-8','cp874' , "ยี่ห้อ"), "LB", 0, 'L');
    // Column 2
    $pdf->Cell( 110, 10, iconv( 'UTF-8','cp874' , ""), "B", 0, 'L');
    // Column 3
    $pdf->Cell( 15, 10, iconv( 'UTF-8','cp874' , "กล่องที่"), "B", 0, 'L');
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',20);
    $pdf->Cell( 7, 10, "1", "B", 0, 'C');
    $pdf->Cell( 7, 10, "/", "B", 0, 'C');
    $pdf->Cell( 7, 10, "1", "B", 0, 'C');

    $pdf->Cell( 0, 10, "", "RB", 0, 'L');

    $pdf->Ln();

// Row 10
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    // Start Loop
    for($r = 1; $r <= 6; $r++) {
        if($r <= 3) {
            $pdf->Cell( 35, 5, "8855852006265", "L", 0, 'C');
            $pdf->Cell( 100, 5, iconv( 'UTF-8','cp874' , "สว่านเจาะทำลาย EUROX PH65A III"), "", 0, 'L');
            $pdf->Cell( 40, 5, "1", "", 0, 'R');
            $pdf->Cell( 0, 5, iconv( 'UTF-8','cp874' , "ตัว"), "R", 0, 'L');
            $pdf->Ln();
        }else{
            $pdf->Cell( 35, 5, "", "L", 0, 'C');
            $pdf->Cell( 100, 5, iconv( 'UTF-8','cp874' , ""), "", 0, 'L');
            $pdf->Cell( 40, 5, "", "", 0, 'R');
            $pdf->Cell( 0, 5, iconv( 'UTF-8','cp874' , ""), "R", 0, 'L');
            $pdf->Ln();
        }
    }  
    // End Loop
    $pdf->Cell( 0, 0, "", "B", 0, 'L');
    $pdf->Ln();

// Row 11
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Cell( 24, 6, iconv( 'UTF-8','cp874' , "สำหรับระบุที่ DC"), "LRB", 0, 'L');
    $pdf->Cell( 0, 6, "", "R", 0, 'L');
    $pdf->Ln();

// Row 12
    // Column 1
    $pdf->Cell( 52.5, 6, iconv( 'UTF-8','cp874' , "ปริมาตร"), "LB", 0, 'L');
    $pdf->Cell( 52.5, 6, iconv( 'UTF-8','cp874' , "ลบ.ม."), "B", 0, 'L');
    $pdf->Cell( 52.5, 6, iconv( 'UTF-8','cp874' , "น้ำหนัก"), "B", 0, 'L');
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "กก."), "RB", 0, 'L');
    $pdf->Ln();

// Row 13
    // Column 1
    $pdf->Cell( 0, 8.5, iconv( 'UTF-8','cp874' , "คำแนะนำในการส่งสินค้า"), "LR", 0, 'L');
    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',15);
    $pdf->Text(38, 125.5, "                                                                                                                                       ");
    $pdf->Ln();

// Row 14
    // Column 1
    $pdf->Text(30, 130, $pdf->Image("img/Credit.gif", $pdf->GetX(), $pdf->GetY(), 104));

    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',13);
    $pdf->Text( 6.619875, 130, "|");
    $pdf->Text( 6.619875, 133, "|");
    $pdf->Text( 6.619875, 136, "|");
    $pdf->Text( 6.619875, 139, "|");
    $pdf->Text( 6.619875, 141, "|");

    $pdf->Text( 202.59, 130, "|");
    $pdf->Text( 202.59, 133, "|");
    $pdf->Text( 202.59, 136, "|");
    $pdf->Text( 202.59, 139, "|");
    $pdf->Text( 202.59, 141, "|");

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',13);
    $pdf->Text( 7, 141.6, "                                                                                                                                                                                             ");
    

    $pdf->SetFillColor(0,0,0);
    $pdf->Code39(114,128,"BX-23070310102",1,10); 
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Text(144, 141, "BX-23070310102");

$pdf->Output('I');
ob_end_flush();
?>

