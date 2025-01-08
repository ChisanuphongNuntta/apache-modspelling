<?php
$DocNum = $_GET['DocNum'];



ob_start();
require('../fpdf/fpdf.php');
$pdf = new FPDF('P','mm', array(210,148));
$pdf->SetMargins(7,11,7,11);
$pdf->SetTitle("ใบปะหน้ากล่อง - HomePro", true);
$pdf->AddPage();

$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.3);

// Row 1
    // Column 1
    $pdf->Cell( 45, 10, $pdf->Image("img/Homepro.gif", $pdf->GetX(), $pdf->GetY(), 45), "LT", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',15);
    $pdf->Cell( 0, 10, iconv( 'UTF-8','cp874' , "ประเภทสินค้า"), "TR", 0, 'L');

    $pdf->Ln();

// Row 2
    // Column 1
    $pdf->Cell( 45, 8, "", "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 16, 8, iconv( 'UTF-8','cp874' , "O Show"), "", 0, 'L');
    // Column 3
    $pdf->Cell( 33, 8, iconv( 'UTF-8','cp874' , "O พรีเมี่ยม/สื่อสิงพิมพ์"), "", 0, 'L');
    // Column 3
    $pdf->Cell( 24, 8, iconv( 'UTF-8','cp874' , "O Non Trade"), "", 0, 'L');
    // Column 4
    $pdf->Cell( 0, 8, iconv( 'UTF-8','cp874' , "O Stock"), "R", 0, 'L');

    $pdf->Ln();

// Row 3
    // Column 1
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',16);
    $pdf->Cell( 0, 12, iconv( 'UTF-8','cp874' , "ใบฝากส่งสินค้า"), "LR", 0, 'C');

    $pdf->Ln();

// Row 4 เลขที่ใบส่งของ
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 22, 6, iconv( 'UTF-8','cp874' , "เลขที่ใบส่งของ :"), "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "660602469"), "R", 0, 'L');
    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',14);
    $pdf->Text(29, 46, "                                                                                                ");

    $pdf->Ln();

// Row 5 เลขที่ PO
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 22, 6, iconv( 'UTF-8','cp874' , "เลขที่ PO :"), "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "PO.4308673341"), "R", 0, 'L');
    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',14);
    $pdf->Text(29, 52, "                                                                                                ");

    $pdf->Ln();

// Row 6 วันทีฝากส่ง
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 22, 6, iconv( 'UTF-8','cp874' , "วันทีฝากส่ง :"), "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "08/07/2023"), "R", 0, 'L');
    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',14);
    $pdf->Text(29, 58, "                                                                                                ");

    $pdf->Ln();

// Row 7
    // Column 1  
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 10, 6, iconv( 'UTF-8','cp874' , "ผู้ส่ง :"), "L", 0, 'L');  
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "รหัสร้านค้า 500395 ชื่อ บริษัท คิงบางกอกอินเตอร์เทรด จํากัด"), "R", 0, 'L');  

    $pdf->Ln();

// Row 8
    // Column 1  
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 10, 6, iconv( 'UTF-8','cp874' , "ที่อยู่ :"), "L", 0, 'L');  
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "541, 543, 545 ซ.39/1 ถนนรามอินทรา แขวงท่าแร้ง เขตบางเขน กทม. 10220"), "R", 0, 'L');  

    $pdf->Ln();

// Row 9
    // Column 1  
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 15, 6, iconv( 'UTF-8','cp874' , "เบอร์โทร :"), "L", 0, 'L');  
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "0-2509-3850 ต่อ 141 , 08-6340-9545"), "R", 0, 'L');  

    $pdf->Ln();

// Row 10 ผู้รับ
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 10, 6, iconv( 'UTF-8','cp874' , "ผู้รับ :"), "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "โฮมโปรดักส์ เซ็นเตอร์ (สาขาวังน้อย)"), "R", 0, 'L');
    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',14);
    $pdf->Text(18, 82, "                                                                                                          ");

    $pdf->Ln();

// Row 11 รายละเอียดสินค้า
    // Column 1
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "รายละเอียดสินค้า"), "LR", 0, 'L');

    $pdf->Ln();

    // Start Loop
    $wUnderText = 94;
    for($r = 1; $r <= 5; $r++) {
        if($r == 1) {
            $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
            $pdf->SetFont('THSarabun Bold','',14);

            $pdf->Cell( 15, 6, "$r. Art", "L", 0, 'R');
            $pdf->Cell( 78, 6, iconv( 'UTF-8','cp874' , "ตะปู F20 EUROX"), "", 0, 'L');
            $pdf->Cell( 17, 6, iconv( 'UTF-8','cp874' , "จำนวน"), "", 0, 'R');
            $pdf->Cell( 13, 6, "2", "", 0, 'R');
            $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "ชิ้น"), "R", 0, 'L');

            $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
            $pdf->SetFont('THSarabun Bold','u',14);
            $pdf->Text(23, $wUnderText, "                                                                          ");
            $pdf->Text(117, $wUnderText, "            ");
            $pdf->Ln();
        }else{
            $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
            $pdf->SetFont('THSarabun Bold','',14);

            $pdf->Cell( 15, 6, "$r. Art", "L", 0, 'R');
            $pdf->Cell( 78, 6, iconv( 'UTF-8','cp874' , ""), "", 0, 'L');
            $pdf->Cell( 17, 6, iconv( 'UTF-8','cp874' , "จำนวน"), "", 0, 'R');
            $pdf->Cell( 13, 6, "", "", 0, 'R');
            $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "ชิ้น"), "R", 0, 'L');

            $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
            $pdf->SetFont('THSarabun Bold','u',14);
            $pdf->Text(23, $wUnderText, "                                                                          ");
            $pdf->Text(117, $wUnderText, "            ");
            $pdf->Ln();
        }
        $wUnderText = $wUnderText+6;
    }
    // End Loop
    $pdf->Cell( 0, 6, "", "LR", 0, 'L');
    $pdf->Ln();

// Row 12
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 25, 6, iconv( 'UTF-8','cp874' , "จำนวนกล่อง"), "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 20, 6, iconv( 'UTF-8','cp874' , "1"), "", 0, 'C');
    $pdf->Cell( 5, 6, iconv( 'UTF-8','cp874' , "/"), "", 0, 'C');
    $pdf->Cell( 20, 6, iconv( 'UTF-8','cp874' , "26"), "", 0, 'C');
    $pdf->Cell( 0, 6, "", "R", 0, 'C');

    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Text(30, 130, "..............................................................");

    $pdf->Ln();

// Row 13
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Cell( 25, 6, iconv( 'UTF-8','cp874' , "พร้อมเอกสารแนบ"), "L", 0, 'L');
    // Column 2
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Cell( 20, 6, iconv( 'UTF-8','cp874' , ""), "", 0, 'C');
    $pdf->Cell( 5, 6, iconv( 'UTF-8','cp874' , "/"), "", 0, 'C');
    $pdf->Cell( 20, 6, iconv( 'UTF-8','cp874' , ""), "", 0, 'C');
    $pdf->Cell( 0, 6, "", "R", 0, 'C');

    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',13);
    $pdf->Text(30, 136, "..............................................................");

    $pdf->Ln();

// Row 14
    // Column 1
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',13);
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "กล่องจะต้องปิดสนิทและมีตราประทับของบริษัทผู้ขนส่งสินค้าด้วย ถ้ามีรอยฉีกขาดโปรดปฏิเสธการรับ"), "LR", 0, 'L');
    $pdf->Ln();

// Row 15
    // Column 1
    $pdf->Cell( 0, 6, iconv( 'UTF-8','cp874' , "ทุกกล่องจะต้องมีป้ายติดชัดเจนเพื่อระบุจำนวนกล่องและสาขา"), "LR", 0, 'L');
    $pdf->Ln();

// Row 16
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',13);

    $pdf->Cell( 40, 6, iconv( 'UTF-8','cp874' , "ผู้รับสินค้า (Rec) :"), "L", 0, 'L');
    $pdf->Cell( 58, 6, iconv( 'UTF-8','cp874' , ""), "", 0, 'L');
    $pdf->Cell( 10, 6, iconv( 'UTF-8','cp874' , "วันที่ :"), "", 0, 'R');
    $pdf->Cell( 20, 6, "", "", 0, 'C');
    $pdf->Cell( 0, 6, "", "R", 0, 'C');

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',14);
    $pdf->Text(41, 154, "                                                         ");
    $pdf->Text(115, 154, "                  ");
    $pdf->Ln();

// Row 17
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',13);

    $pdf->Cell( 40, 6, iconv( 'UTF-8','cp874' , "ผู้รับสินค้า (Vender) :"), "L", 0, 'L');
    $pdf->Cell( 58, 6, iconv( 'UTF-8','cp874' , ""), "", 0, 'L');
    $pdf->Cell( 10, 6, iconv( 'UTF-8','cp874' , "วันที่ :"), "", 0, 'R');
    $pdf->Cell( 20, 6, "08/07/2023", "", 0, 'C');
    $pdf->Cell( 0, 6, "", "R", 0, 'C');

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',14);
    $pdf->Text(41, 160, "                                                         ");
    $pdf->Text(115, 160, "                  ");
    $pdf->Ln();

// Row 18
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',13);

    $pdf->Cell( 40, 6, iconv( 'UTF-8','cp874' , "ผู้รับสินค้า (Shippling) :"), "L", 0, 'L');
    $pdf->Cell( 58, 6, iconv( 'UTF-8','cp874' , ""), "", 0, 'L');
    $pdf->Cell( 10, 6, iconv( 'UTF-8','cp874' , "วันที่ :"), "", 0, 'R');
    $pdf->Cell( 20, 6, "", "", 0, 'C');
    $pdf->Cell( 0, 6, "", "R", 0, 'C');

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',14);
    $pdf->Text(41, 166, "                                                         ");
    $pdf->Text(115, 166, "                  ");
    $pdf->Ln();

// Row 19
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',13);

    $pdf->Cell( 40, 6, iconv( 'UTF-8','cp874' , "ผู้รับสินค้า (GR Store) :"), "L", 0, 'L');
    $pdf->Cell( 58, 6, iconv( 'UTF-8','cp874' , ""), "", 0, 'L');
    $pdf->Cell( 10, 6, iconv( 'UTF-8','cp874' , "วันที่ :"), "", 0, 'R');
    $pdf->Cell( 20, 6, "", "", 0, 'C');
    $pdf->Cell( 0, 6, "", "R", 0, 'C');

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',14);
    $pdf->Text(41, 172, "                                                         ");
    $pdf->Text(115, 172, "                  ");
    $pdf->Ln();

// Row 20
    // Column 1
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',13);

    $pdf->Text(9, 182, iconv( 'UTF-8','cp874' , "หมายเหตุ : ขอความร่วมมือ"));
    $pdf->Text(9, 187, iconv( 'UTF-8','cp874' , "1 กล่องไม่ควรเกิน 5 Art"));
    
    $pdf->SetFillColor(0,0,0);
    $pdf->Code39(51,179,"BX-23070310102",1,10); 
    $pdf->AddFont('THSarabun Bold','','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','',14);
    $pdf->Text(81, 192, "BX-23070310102");

    $pdf->Text( 6.619875, 175.5, "|");
    $pdf->Text( 6.619875, 178.9, "|");
    $pdf->Text( 6.619875, 182.5, "|");
    $pdf->Text( 6.619875, 185.9, "|");
    $pdf->Text( 6.619875, 189.3, "|");
    $pdf->Text( 6.619875, 192.9, "|");
    $pdf->Text( 6.619875, 196.3, "|");

    $pdf->Text( 140.6, 175.5, "|");
    $pdf->Text( 140.6, 178.9, "|");
    $pdf->Text( 140.6, 182.5, "|");
    $pdf->Text( 140.6, 185.9, "|");
    $pdf->Text( 140.6, 189.3, "|");
    $pdf->Text( 140.6, 192.9, "|");
    $pdf->Text( 140.6, 196.3, "|");

    $pdf->AddFont('THSarabun Bold','u','THSarabun Bold.php');
    $pdf->SetFont('THSarabun Bold','u',13);
    $pdf->Text( 7.2, 197, "                                                                                                                                 "); 

$pdf->Output('I');
ob_end_flush();
?>

