<?php 
$DocEntry = EDCode('DEntry');

// ORDER HEADER AND FOOTER
    $SQL1 = 
        "SELECT 
            CONCAT(T0.CardCode, ' | ', T0.CardName) AS CardName, CONCAT(T0.DocType, '-', T0.DocNum) AS DocNum,
            T0.LicTradeNum, T0.DocDate, T0.GroupNum, T0.BilltoAddress, T1.SlpName, T0.U_PONo, T0.Comments, T0.DiscPcnt,
            T0.DiscTotal, T0.DocTotal, T0.VatSum, T0.DocNum AS Title
        FROM order_header T0
        LEFT JOIN OSLP T1 ON T0.SlpCode = T1.SlpCode
        WHERE DocEntry = $DocEntry LIMIT 1";
    $RST1 = DBConnect("APP")->query($SQL1)->fetchAll()[0];
    $SQL_GroupNum = "SELECT TOP 1 T0.[GroupNum], T0.[PymntGroup] FROM OCTG T0 WHERE T0.[GroupNum] = ".$RST1['GroupNum']."";
    $RST_GroupNum = DBConnect("SAP")->query(SQLtoHANA($SQL_GroupNum))->fetchAll()[0];

    $CardName = $RST1['CardName'];
    $DocNum = $RST1['DocNum'];
    $LicTradeNum = $RST1['LicTradeNum'];
    $DocDate = date("d/m/Y",strtotime($RST1['DocDate']));
    $GroupNum = SapTH($RST_GroupNum['PymntGroup']);
    $BilltoAddress = $RST1['BilltoAddress'];
    $SlpName = $RST1['SlpName'];
    $U_PONo = $RST1['U_PONo'];
    $Comments = "";
    if($RST1['Comments'] != "") { 
        $LoopComments = ceil(utf8_strlen($RST1['Comments'])/95);
        $tmpComments = 0;
        for($i = 1; $i <= $LoopComments; $i++) {
            $Comments .= mb_substr($RST1['Comments'], $tmpComments, 95, 'UTF-8');
            $Comments .= ($i != $LoopComments) ? "<br>" : "";
            $tmpComments = $tmpComments+95;
        }
    }
    $DiscPcnt = ($RST1['DiscPcnt'] == 0) ? number_format($RST1['DiscTotal'],2)." บาท" : number_format($RST1['DiscPcnt'],2)."%";
    $DocTotalDisVat = number_format($RST1['DocTotal']-$RST1['VatSum'],2);
    $VatSum = number_format($RST1['VatSum'],2);
    $DocTotal = number_format($RST1['DocTotal'],2);
    $Title = $RST1['DocNum'];

// ORDER DETAIL
    $SQL2 = 
        "SELECT 
            T0.ItemName, T0.ItemCode, T0.CodeBars, T0.Quantity, T0.UnitMsr, T0.GrandPrice, T0.WhsCode,
            T0.Line_Disc0, T0.Line_Disc1, T0.Line_Disc2, T0.Line_Disc3, T0.Line_Disc4, T0.LineTotal
        FROM order_detail T0
        WHERE T0.DocEntry = $DocEntry AND T0.LineStatus != 'I'
        ORDER BY T0.VisOrder";
    $RST2 = DBConnect("APP")->query($SQL2)->fetchAll(PDO::FETCH_ASSOC);
    $AllTotal = 0;
    foreach($RST2 as $t) {
        $AllTotal = $AllTotal+$t['LineTotal'];
    }

    $rowsperpage = 10;
    $pages = ceil(count($RST2)/$rowsperpage);
    $row = 0;
?>

<?php for($p = 1; $p <= $pages ;$p++) { ?>
    <div class="page">
        <!-- PAGE HEADER -->
        <table class="table table-borderless table-sm m-0" style="color: #000;">
            <thead>
                <tr>
                    <td width="12%" class="text-center">
                        <img src="../assets/images/<?php echo $_SESSION['SITE']['Logo_Img']; ?>" style='width: 55px;' />
                    </td>
                    <td>
                        <h4><?php echo $_SESSION['SITE']['TH_CompName']." ".$_SESSION['SITE']['EN_CompName']; ?></h4>
                        <small>
                            <?php echo $_SESSION['SITE']['TH_Address']; ?><br/>
                            เลขประจำตัวผู้เสียภาษี: <?php echo $_SESSION['SITE']['TaxID']; ?>
                        </small>
                    </td>
                    <td width="15%" class="align-top text-end">
                        หน้าที่ <?php echo $p; ?> จาก <?php echo $pages; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center"><h5 style="">ใบเสนอราคา / Quotation</h5></td>
                </tr>
            </thead>
        </table>

        <!-- ORDER HEADER -->
        <table class="table table-borderless table-sm" style="color: #000;">
            <tr>
                <th width='14%'>ชื่อลูกค้า:</th>
                <td width='40%'><?php echo $CardName; ?></td>
                <th width='10%'>เลขที่เอกสาร:</th>
                <td width='36%'><?php echo $DocNum; ?></td>
            </tr>
            <tr>
                <th>เลขที่ผู้เสียภาษี:</th>
                <td><?php echo $LicTradeNum; ?></td>
                <th>วันที่เอกสาร:</th>
                <td><?php echo $DocDate; ?></td>
            </tr>
            <tr>
                <th>เงื่อนไขการจ่ายเงิน:</th>
                <td><?php echo $GroupNum; ?></td>
                <th>พนักงานขาย:</th>
                <td><?php echo $SlpName; ?></td>
            </tr>
            <tr>
                <th>ที่อยู่เปิดบิล:</th>
                <td colspan='3'><?php echo $BilltoAddress; ?></td>
            </tr>
        </table>

        <p class="text-center m-0" style="font-weight: 600;">บริษัทฯ ขอเสนอราคาสินค้ารายละเอียดดังต่อไปนี้</p>

        <!-- ORDER DETAIL -->
        <table class="table QuotationList" style="color: #000;">
            <thead class="text-center">
                <tr>
                    <th class='border-dark' scope="col" width="3.5%">ลำดับ</th>
                    <th class='border-dark' scope="col">รายละเอียด</th>
                    <th class='border-dark' scope="col">คลัง</th>
                    <th class='border-dark' scope="col" colspan="2">จำนวน</th>
                    <th class='border-dark' scope="col" width="10%">ราคา<br/>ต่อหน่วย</th>
                    <th class='border-dark' scope="col" width="15%">ส่วนลด</th>
                    <th class='border-dark' scope="col" width="12.5%">ราคารวม</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for($r = 1; $r <= 10; $r++) { 
                    if(isset($RST2[$row])) {
                        $Line_Disc = 0;
                        if($RST2[$row]['Line_Disc0'] != null) {
                            $Line_Disc = number_format($RST2[$row]['Line_Disc0'],2)." บาท";
                        }else{
                            if($RST2[$row]['Line_Disc4'] != null) {
                                $Line_Disc = number_format($RST2[$row]['Line_Disc4'],0)."%+".number_format($RST2[$row]['Line_Disc3'],0)."%+".number_format($RST2[$row]['Line_Disc2'],0)."%+".number_format($RST2[$row]['Line_Disc1'],0)."%";
                            }elseif($RST2[$row]['Line_Disc3'] != null){
                                $Line_Disc = number_format($RST2[$row]['Line_Disc3'],0)."%+".number_format($RST2[$row]['Line_Disc2'],0)."%+".number_format($RST2[$row]['Line_Disc1'],0)."%";
                            }elseif($RST2[$row]['Line_Disc2'] != null){
                                $Line_Disc = number_format($RST2[$row]['Line_Disc2'],0)."%+".number_format($RST2[$row]['Line_Disc1'],0)."%";
                            }elseif($RST2[$row]['Line_Disc1'] != null){
                                $Line_Disc = number_format($RST2[$row]['Line_Disc1'],0)."%";
                            }
                        }
                        $ItemName = "";
                        if($RST2[$row]['ItemName'] != "") { 
                            $LoopItemName = ceil(utf8_strlen($RST2[$row]['ItemName'])/75);
                            $tmpItemName = 0;
                            for($i = 1; $i <= $LoopItemName; $i++) {
                                $ItemName .= mb_substr($RST2[$row]['ItemName'], $tmpItemName, 75, 'UTF-8');
                                $ItemName .= ($i != $LoopItemName) ? "<br>" : "";
                                $tmpItemName = $tmpItemName+75;
                            }
                        }
                        ?>
                    
                        <tr>
                            <td scope="row" class="align-top text-center"><?php echo ($row+1); ?></td>
                            <td class="align-top"><b><?php echo $ItemName; ?></b><br/>รหัสสินค้า: <?php echo $RST2[$row]['ItemCode']; ?> | บาร์โค้ด: <?php echo $RST2[$row]['CodeBars']; ?></td>
                            <td class="align-top"><?php echo $RST2[$row]['WhsCode']; ?></td>
                            <td width="5%" class="align-top text-end"><?php echo number_format($RST2[$row]['Quantity'],0); ?></td>
                            <td width="6%" class="align-top"><?php echo $RST2[$row]['UnitMsr']; ?></td>
                            <td class="align-top text-end"><?php echo number_format($RST2[$row]['GrandPrice'],3); ?></td>
                            <td class="align-top text-center"><?php echo $Line_Disc; ?></td>
                            <td class="align-top fw-bolder text-end"><?php echo number_format($RST2[$row]['LineTotal'],2); ?></td>
                        </tr>
                    <?php 
                    $row++; 
                    }else{ ?>
                        <tr>
                            <td class='border-0'>&nbsp;<br>&nbsp;</td>
                            <td class='border-0'>&nbsp;</td>
                            <td class='border-0'>&nbsp;</td>
                            <td class='border-0'>&nbsp;</td>
                            <td class='border-0'>&nbsp;</td>
                            <td class='border-0'>&nbsp;</td>
                            <td class='border-0'>&nbsp;</td>
                            <td class='border-0'>&nbsp;</td>
                        </tr>
                    <?php 
                    }
                }?>
            </tbody>
            <tfoot>
                <tr>
                    <th class='border-dark border-top border-bottom-0'>เอกสารอ้างอิง:</th>
                    <td class='border-dark border-top border-bottom-0' colspan="4"><?php echo $U_PONo; ?></td>
                    <th class="border-dark border-top border-bottom-0 table-active text-dark text-end" colspan="2">ยอดรวมทุกรายการ:</td>
                    <th class="border-dark border-top border-bottom-0 text-end"><?php echo number_format($AllTotal,2); ?></th>
                </tr>
                <tr>
                    <th class="border-dark align-top" rowspan="3">หมายเหตุ:</th>
                    <td class="border-dark align-top" colspan="4" rowspan="3"><?php echo nl2br(htmlentities($Comments)); ?></td>
                    <th colspan="2" class="border-bottom-0 table-active text-dark text-end">ส่วนลดท้ายบิล:</td>
                    <td class="border-bottom-0 text-end"><?php echo $DiscPcnt; ?></td>
                </tr>
                <tr>
                    <th colspan="2" class="border-bottom-0 table-active text-dark text-end">ยอดสินค้าหลังหักส่วนลด:</th>
                    <td class="border-bottom-0 text-end"><?php echo $DocTotalDisVat; ?></td>
                </tr>
                <tr>
                    <th colspan="2" class="border-dark table-active text-dark text-end">ภาษีมูลค่าเพิ่ม:</th>
                    <td class="border-dark text-end"><?php echo $VatSum; ?></td>
                </tr>
                <tr>
                    <th colspan="5" class="text-center" style='font-style: italic; border-bottom: 3px double #212529 !important;'>(<?php echo ConNumText($DocTotal); ?>)</th>
                    <th colspan="2" class="table-active text-dark text-end" style='border-bottom: 3px double #212529 !important;'>จำนวนเงินรวมสุทธิ:</th>
                    <td class="text-end" style='border-bottom: 3px double #212529 !important;'><?php echo $DocTotal; ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- FOOTER -->
        <table class="table table-bordered border-dark" style="color: #000;">
            <thead class="text-center">
                <tr>
                    <th class='border-dark' style='border-width: 0 1px 2px 1px !important;' width="50%">ผู้เสนอราคา</th>
                    <th class='border-dark' style='border-width: 0 1px 2px 1px !important;' width="50%">ลงนามยืนยันการสั่งซื้อ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="align-bottom text-center" style='padding: 15px !important;'><?php echo $_SESSION['EMPNAME']; ?></td>
                    <td class="align-bottom text-center" style='padding: 15px !important;'>&nbsp;</td>
                </tr>
                <tr>
                    <td class="align-bottom text-center">(<?php echo $_SESSION['EMPNAME']; ?>)</td>
                    <td class="align-bottom text-center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                </tr>
                <tr>
                    <td class="align-bottom text-center">วันที่ <?php echo date("d/m/Y"); ?></td>
                    <td class="align-bottom text-center">วันที่ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
<?php } ?>
<script type="text/javascript">
    document.title = "Print | <?php echo $Title; ?>"; 
    window.print();
</script>