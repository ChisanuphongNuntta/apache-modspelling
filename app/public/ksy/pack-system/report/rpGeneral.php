<?php
$DocNum = $_GET['DocNum'];

$Company = "บริษัท วนาวัฒน์วัสดุ จำกัด";
$CardCode = "C-00935";
$Phone = "080-0801542";
$DocDate = "03/07/2023";
$DueDate = "09/01/2023";
$Adddress1 = "125/1 หมู่ที่ 6 ถนนลพบุรีเมศวร์";
$Adddress2 = "ตำบล น้ำน้อย อำเภอ หาดใหญ่ จังหวัด สงขลา 100110";
$NumBox = "2 เวลา 09:45:12";
$Box1 = "1";
$Box2 = "1";
$Chacker = "ธนา นิมิต";
$Tran1 = "ปิยวัฒน์ทรานสปอร์(สงขลาปิยะวัฒน์ขนส่ง)";
$Tran2 = "สาย 5 (ชานชาลา9 ล็อค25-26)";
$PhoneTran = "068-4816348,086-6921992";
$BarCode = 'BX-23011120008';
$DocEntry = "655100666";
$DocIV = "IV-655112063";


ob_start();
require('../fpdf/fpdf.php');
$pdf = new FPDF('L','mm', array(210,148));
$pdf->SetMargins(3,5,3,0);
$pdf->SetTitle("ใบปะหน้ากล่อง", true);
$pdf->AddPage();

$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.3);

$SetCol_H = [23,96,22,37,5,0];
$SetCol_B = [14,35,100,35,0];
for($p = 1; $p <= 1; $p++) {
// Row 1
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun Bold.php');
    $pdf->SetFont('THSarabun','b',26);
    $pdf->Cell($SetCol_H[0],5,iconv( 'UTF-8','cp874' , "กรุณาส่ง"));
    $pdf->AddFont('THSarabun','bu','THSarabun Bold.php');
    $pdf->SetFont('THSarabun','bu',22);
    $pdf->Cell($SetCol_H[1],5,iconv( 'UTF-8','cp874' , $Company));

    // Column 2
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);
    $pdf->Cell($SetCol_H[2], 6, iconv( 'UTF-8','cp874' , "รหัสลูกค้า"), "LTB", 0, 'L');
    $pdf->Cell($SetCol_H[3], 6, iconv( 'UTF-8','cp874' , $CardCode), "RTB", 0, 'L');
    $pdf->Cell($SetCol_H[4], 6, "", "");
    $pdf->Cell($SetCol_H[5], 6, iconv( 'UTF-8','cp874' , "ลังเลขที่"), "LTRB", 0, 'C');
    
    $pdf->Ln();
    
// Row 2
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',15);
    $pdf->Cell($SetCol_H[0],8,iconv( 'UTF-8','cp874' , "เบอร์ลูกค้า"));
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);
    $pdf->Cell($SetCol_H[1],8,iconv( 'UTF-8','cp874' , $Phone));

    // Column 2
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',15);
    $pdf->Cell($SetCol_H[2], 8, iconv( 'UTF-8','cp874' , "วันที่จัดสินค้า"), "L", 0, 'L');
    $pdf->Cell($SetCol_H[3], 8, iconv( 'UTF-8','cp874' , $DocDate), "R", 0, 'L');
    $pdf->Cell($SetCol_H[4], 8, "", "");
    $pdf->AddFont('THSarabun','b','THSarabun Bold.php');
    $pdf->SetFont('THSarabun','b',40);
    $pdf->Cell($SetCol_H[5], 8, $Box1, "LR",0,'C');
    
    $pdf->Ln();

// Row 3
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',15);
    $pdf->Cell($SetCol_H[0],6,iconv( 'UTF-8','cp874' , "ที่อยู่จัดส่ง"));
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);
    $pdf->Cell($SetCol_H[1],6,iconv( 'UTF-8','cp874' , $Adddress1));

    // Column 2
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',15);
    $pdf->Cell($SetCol_H[2], 6, iconv( 'UTF-8','cp874' , "กล่องเลขที่"), "LB", 0, 'L');
    $pdf->Cell($SetCol_H[3], 6, iconv( 'UTF-8','cp874' , $NumBox), "RB", 0, 'L');
    $pdf->Cell($SetCol_H[4], 6, "", "");
    $pdf->Cell($SetCol_H[5], 6, "", "LBR");

    $pdf->Ln();

// Row 4
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);
    $pdf->Cell($SetCol_H[0],6,'');
    $pdf->Cell($SetCol_H[1],6,iconv( 'UTF-8','cp874' , $Adddress2));

    // Column 2
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);
    $pdf->Cell($SetCol_H[2], 8, iconv( 'UTF-8','cp874' , "กำหนดส่ง"), "L", 0, 'L');
    $pdf->Cell($SetCol_H[3], 8, iconv( 'UTF-8','cp874' , $DueDate), "R", 0, 'L');
    $pdf->Cell($SetCol_H[4], 8, "", "");
    $pdf->AddFont('THSarabun','b','THSarabun Bold.php');
    $pdf->SetFont('THSarabun','b',40);
    $pdf->Cell($SetCol_H[5], 8, $Box2, "LR",0,'C');

    $pdf->Ln();

// Row 4
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',15);
    $pdf->Cell($SetCol_H[0],6,iconv( 'UTF-8','cp874' , "ขนส่งโดย"));
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);
    $pdf->Cell($SetCol_H[1],6,iconv( 'UTF-8','cp874' , $Tran1));

    // Column 2
    $pdf->Cell($SetCol_H[2], 6, iconv( 'UTF-8','cp874' , "ชื่อผู้ตรวจสอบ"), "LTB", 0, 'L');
    $pdf->Cell($SetCol_H[3], 6, iconv( 'UTF-8','cp874' , $Chacker), "RTB", 0, 'L');
    $pdf->Cell($SetCol_H[4], 6, "", "");
    $pdf->Cell($SetCol_H[5], 6, "", "LBR");

    $pdf->Ln();
    
// Row 5
    // Column 1
    $pdf->Cell($SetCol_H[0],6,'');
    $pdf->Cell($SetCol_H[1],6,iconv( 'UTF-8','cp874' , $Tran2));

    $pdf->Ln();

// Row 6
    // Column 1
    $pdf->AddFont('THSarabun','','THSarabun.php');
    $pdf->SetFont('THSarabun','',15);
    $pdf->Cell($SetCol_H[0],6,iconv( 'UTF-8','cp874' , "เบอร์ขนส่ง"));
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);
    $pdf->Cell($SetCol_H[1],6,iconv( 'UTF-8','cp874' , $PhoneTran));

    // Column 2
    $pdf->SetFillColor(0,0,0);
    $pdf->Code39(122,41,$BarCode,1,10); 
    $pdf->AddFont('THSarabun','b','THSarabun Bold.php');
    $pdf->SetFont('THSarabun','b',13);
    $pdf->Text(155, 54.5, $BarCode);

    $pdf->Ln();

// Row 7
    // Column 1
    $pdf->AddFont('THSarabun Bold Italic','','THSarabun Bold Italic.php');
    $pdf->SetFont('THSarabun Bold Italic','',24);
    $pdf->Cell(0,10,iconv( 'UTF-8','cp874' , "อย่าโยน ระวังสินค้าแตก!!!"),"", 0, 'C');

    $pdf->Ln();

// Row 8
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',18);
    $pdf->Cell(0, 8, iconv( 'UTF-8','cp874' , "รายการสินค้าภายในกล่อง (ระบุ)"), "LTR", 0, 'C');

    $pdf->Ln();

// Row 9
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);

    // Start Loop
    for($r = 1; $r <= 11; $r++) {
        if($r <= 5) {
            // Column 1
            $pdf->Cell($SetCol_B[0], 4.5, "$r.", "L", 0, 'R');
            // Column 2
            $pdf->Cell($SetCol_B[1], 4.5, "8855852009259", "", 0, 'C');
            // Column 3
            $pdf->Cell($SetCol_B[2], 4.5, iconv( 'UTF-8','cp874' , "หัวเติมลมสั่น EUROX"), "", 0, 'L');
            // Column 4
            $pdf->Cell($SetCol_B[3], 4.5, "20", "", 0, 'R');
            // Column 5
            $pdf->Cell($SetCol_B[4], 4.5, iconv( 'UTF-8','cp874' , "ตัว"), "R", 0, 'L');
            $pdf->Ln();
        }else{
            // Column 1
            $pdf->Cell($SetCol_B[0], 4.5, "", "L", 0, 'R');
            // Column 2
            $pdf->Cell($SetCol_B[1], 4.5, "", "", 0, 'C');
            // Column 3
            $pdf->Cell($SetCol_B[2], 4.5, "", "", 0, 'L');
            // Column 4
            $pdf->Cell($SetCol_B[3], 4.5, "", "", 0, 'R');
            // Column 5
            $pdf->Cell($SetCol_B[4], 4.5, "", "R", 0, 'L');
            $pdf->Ln();
        }
    }
    // End Loop

// Row 10
    // Column 1
    $pdf->Cell(50, 8, iconv( 'UTF-8','cp874' , "เอกสาร $DocEntry"), "LB", 0, 'C');
    // Column 2
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',25);
    $pdf->Cell(98, 8, iconv( 'UTF-8','cp874' , "***มีบิล***"), "B", 0, 'C');
    // Column 2
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',14.5);
    $pdf->Cell(0, 8, iconv( 'UTF-8','cp874' , "เอกสารอ้างอิง $DocIV"), "BR", 0, 'C');

    $pdf->Ln();

// Row 11
    // Column 1
    $pdf->AddFont('THSarabun','b','THSarabun.php');
    $pdf->SetFont('THSarabun','b',21);
    $pdf->Text(3, 135, iconv( 'UTF-8','cp874' , "ผู้ฝากบริษัท คิงบางกอกอินเตอร์เทรด จำกัด โทร. 02-509-3850"));
}

$pdf->Output('I');
ob_end_flush();
?>

