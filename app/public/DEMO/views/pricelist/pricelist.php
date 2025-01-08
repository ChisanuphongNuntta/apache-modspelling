<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <button class='btn btn-sm btn-success' onclick='OpenFile();'><i class="fas fa-file-excel"></i> นำเข้าข้อมูล</button>
                    <button class='btn btn-sm btn-outline-secondary' onclick='GetTemplate();'><i class='fas fa-download'></i> ดาวน์โหลด Template</button>
                    <form class="form" id="FormImport" enctype="multipart/form-data"> 
                        <input type="file" class='d-none' name='FileImport' id='FileImport' accept=".xlsx">
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class='table table-sm table-hover table-bordered' style='font-size: 11.5px;' id='TableMain'>
                    <thead class=''>
                        <tr>
                            <th class='text-center border' rowspan='2'>ประเภทราคา</th>
                            <th class='text-center border' rowspan='2'>รหัสสินค้า</th>
                            <th class='text-center border' rowspan='2'>ชื่อสินค้า</th>
                            <th class='text-center border' rowspan='2'>บาร์โค้ด</th>
                            <th class='text-center border' rowspan='2'>ต้นทุน</th>
                            <th class='text-center border' rowspan='2'>Stock<br>ปัจจุบัน</th>
                            <th class='text-center border' colspan='2'>ราคาก่อนส่วนลด</th>
                            <th class='text-center border' colspan='2'>ราคาขายส่ง</th>
                            <th class='text-center border' colspan='2'>ราคาขายปลีก</th>
                            <th class='text-center border' colspan='3'>ราคา Step 1 (S1)</th>
                            <th class='text-center border' colspan='3'>ราคา Step 2 (S2)</th>
                            <th class='text-center border' colspan='3'>ราคา Step 3 (S3)</th>
                            <th class='text-center border' colspan='3'>ราคา Step 4 (S4)</th>
                        </tr>
                        <tr>
                            <th class='text-center'>ราคา</th>
                            <th class='text-center'>GP</th>
                            <th class='text-center'>ราคา</th>
                            <th class='text-center'>GP</th>
                            <th class='text-center'>ราคา</th>
                            <th class='text-center'>GP</th>
                            <th class='text-center'>จำนวน</th>
                            <th class='text-center'>ราคา</th>
                            <th class='text-center'>GP</th>
                            <th class='text-center'>จำนวน</th>
                            <th class='text-center'>ราคา</th>
                            <th class='text-center'>GP</th>
                            <th class='text-center'>จำนวน</th>
                            <th class='text-center'>ราคา</th>
                            <th class='text-center'>GP</th>
                            <th class='text-center'>จำนวน</th>
                            <th class='text-center'>ราคา</th>
                            <th class='text-center'>GP</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>