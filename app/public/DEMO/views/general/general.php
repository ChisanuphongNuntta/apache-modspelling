<style>

</style>
<section>
    <div class="card">
        <h5 class="card-header"><?php echo $MenuTitle; ?></h5>
        <div class="card-body">
            <div class="row pt-2">
                <div class="col">
                    <small class='text-danger'>* นำเม้าส์คลิกบริเวณที่ว่างเพื่อบันทึกค่า (การเปลี่ยนแปลงการตั้งค่าจะส่งผลต่อทั้งบริษัทที่อยู่ในระบบนี้ทั้งหมด)</small>
                    <table class="table table-bordered table-hover table-sm" id="ConfigList">
                        <thead class='text-center'>
                            <tr>
                                <th width='80%'>รายละเอียด</th>
                                <th width='20%'>ค่าที่กำหนด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class='text-center' colspan='2'>ไม่มีข้อมูล :(</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr/>
            <div class="row pt-2">
                <div class="col">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class='text-center'>
                            <tr>
                                <th width='90%'>รายละเอียด</th>
                                <th width='10%'>ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" class="fw-bolder table-success">Sync ข้อมูล</td>
                            </tr>
                            <tr>
                                <td>Sync Sales Employees</td>
                                <td><button class="btn btn-outline-secondary btn-sm w-100" onclick="SyncData('OSLP');"><i class="fas fa-sync fa-fw fa-1x"></i> Sync</button></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Sync Business Partner Master Data</td>
                                <td><button class="btn btn-outline-secondary btn-sm w-100" onclick="SyncData('OCRD');" disabled><i class="fas fa-sync fa-fw fa-1x"></i> Sync</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>