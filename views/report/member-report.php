<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Members[] $members */
/** @var string|null $keyword */
/** @var string|null $membertype */
/** @var string|null $sdate */
/** @var string|null $edate */

$this->title = 'รายงานสมาชิกสหกรณ์';
$total_members = is_array($members) ? count($members) : 0;
?>

<style>
.report-header {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    border-radius: 15px;
    padding: 1.2rem 1.5rem;
    color: white;
    margin-bottom: 1.2rem;
    box-shadow: 0 4px 15px rgba(37, 117, 252, 0.25);
}

.report-header h5 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.search-card {
    background: white;
    border-radius: 12px;
    padding: 1.2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    margin-bottom: 1.2rem;
}

.form-label { font-weight: 600; color: #495057; }
.form-control { border-radius: 8px; border: 2px solid #e9ecef; }
.form-control:focus { border-color: #2575fc; box-shadow: 0 0 0 0.2rem rgba(37,117,252,.15); }

.btn-primary { background: linear-gradient(135deg,#007bff,#6610f2); border:none; border-radius:8px; }
.btn-success { background: linear-gradient(135deg,#28a745,#20c997); border:none; border-radius:8px; }

.table thead th { background: linear-gradient(135deg,#343a40,#495057); color:white; text-align:center; }
.table-warning { background: linear-gradient(135deg, #fff3cd, #ffeaa7) !important; font-weight:600; }

.badge-type { background:#e9f2ff; color:#0d6efd; font-weight:600; }
</style>

<div class="report-header">
    <h5><i class="bi bi-people"></i> รายงานสมาชิกสหกรณ์</h5>
</div>

<div class="search-card">
    <form method="get" action="<?= Url::to(['report/member-report']) ?>">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">คำค้น (ชื่อ/นามสกุล/เลขสมาชิก/โทร)</label>
                <input type="text" name="keyword" value="<?= Html::encode((string)$keyword) ?>" class="form-control" placeholder="พิมพ์คำค้น...">
            </div>
            <div class="col-md-2">
                <label class="form-label">ประเภทสมาชิก</label>
                <input type="text" name="membertype" value="<?= Html::encode((string)$membertype) ?>" class="form-control" placeholder="เช่น ปกติ">
            </div>
            <div class="col-md-2">
                <label class="form-label">วันที่สมัคร (ตั้งแต่)</label>
                <input type="date" name="sdate" value="<?= Html::encode((string)$sdate) ?>" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">ถึงวันที่</label>
                <input type="date" name="edate" value="<?= Html::encode((string)$edate) ?>" class="form-control">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-success me-2"><i class="bi bi-search"></i> ค้นหา</button>
                <?php if ($total_members > 0): ?>
                <button type="button" class="btn btn-primary" onclick="printReport()"><i class="bi bi-printer"></i> พิมพ์</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?php if ($total_members > 0): ?>
    <div class="mb-2 text-muted">พบสมาชิกทั้งหมด <?= number_format($total_members) ?> ราย</div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover datatable">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>เลขสมาชิก</th>
                    <th>ชื่อ - นามสกุล</th>
                    <th>บัตรประชาชน</th>
                    <th>เบอร์โทร</th>
                    <th>ประเภท</th>
                    <th>วันที่สมัคร</th>
                    <th>จำนวนไร่</th>
                    <th>ที่อยู่</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $i => $m): ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td class="text-center"><?= Html::encode($m->memberid) ?></td>
                    <td><?= Html::encode($m->fullname2) ?></td>
                    <td class="text-center"><?= Html::encode($m->idcard) ?></td>
                    <td class="text-center"><?= Html::encode($m->phone) ?></td>
                    <td class="text-center"><span class="badge badge-type"><?= Html::encode($m->membertype) ?></span></td>
                    <td class="text-center"><?= $m->stdate ? Yii::$app->helpers->DateThai($m->stdate) : '-' ?></td>
                    <td class="text-end"><?= $m->farm !== null ? number_format($m->farm) : '-' ?></td>
                    <td>
                        <?= Html::encode(trim(($m->homenum ? 'บ้านเลขที่ '.$m->homenum.' ' : '')
                            .($m->moo ? 'หมู่ '.$m->moo.' ' : '')
                            .($m->tumbon ? 'ต.' . $m->tumbon . ' ' : '')
                            .($m->amper ? 'อ.' . $m->amper . ' ' : '')
                            .($m->chawat ? 'จ.' . $m->chawat : ''))) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-warning">
                    <td colspan="9" class="text-center"><strong>รวมสมาชิก <?= number_format($total_members) ?> ราย</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info text-center">
        ไม่พบข้อมูลสมาชิกตามเงื่อนไขที่ค้นหา
    </div>
<?php endif; ?>

<!-- Print-only content -->
<div id="printContent" class="d-none">
    <div class="text-center mb-3">
        <h2>สหกรณ์การกองทุนยางฉลองน้ำขาวพัฒนา จำกัด</h2>
        <h2>รายงานสมาชิกสหกรณ์</h2>
        <?php if ($sdate || $edate): ?>
        <h2>ช่วงวันที่ <?= $sdate ? Yii::$app->helpers->DateThai($sdate) : '-' ?> ถึง <?= $edate ? Yii::$app->helpers->DateThai($edate) : '-' ?></h2>
        <?php endif; ?>
        <hr style="border:1px solid #000;margin:10px 0;">
    </div>
    <?php if ($total_members > 0): ?>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width:6%">ลำดับ</th>
                <th style="width:10%">เลขสมาชิก</th>
                <th style="width:23%">ชื่อ - นามสกุล</th>
                <th style="width:16%">บัตรประชาชน</th>
                <th style="width:12%">โทร</th>
                <th style="width:11%">ประเภท</th>
                <th style="width:11%">วันที่สมัคร</th>
                <th style="width:11%">ไร่</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $i => $m): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= Html::encode($m->memberid) ?></td>
                <td style="text-align:left;padding-left:6px;"><?= Html::encode($m->fullname2) ?></td>
                <td><?= Html::encode($m->idcard) ?></td>
                <td><?= Html::encode($m->phone) ?></td>
                <td><?= Html::encode($m->membertype) ?></td>
                <td><?= $m->stdate ? Yii::$app->helpers->DateThai($m->stdate) : '-' ?></td>
                <td style="text-align:right;padding-right:6px;"><?= $m->farm !== null ? number_format($m->farm) : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="8" style="text-align:center;font-weight:bold;">รวมสมาชิก <?= number_format($total_members) ?> ราย</td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>
</div>

<style>
@media print {
  body * { visibility: hidden; }
  #printContent, #printContent * { visibility: visible; }
  #printContent { position:absolute; left:0; top:0; width:100%; display:block !important; }
  @page { size: A4 portrait; margin: 12mm; }
  .print-table { width:100%; border-collapse:collapse; border:2px solid #000; font-size:14px; }
  .print-table th, .print-table td { border:1px solid #000; padding:6px 4px; text-align:center; }
  .print-table th { background:#f0f0f0; font-weight:700; }
  .d-none { display:none !important; }
}
#printContent { display:none; }
</style>

<script>
function printReport(){
  const pc = document.getElementById('printContent');
  pc.style.display = 'block';
  window.print();
  setTimeout(()=>{ pc.style.display = 'none'; }, 500);
}
</script>
