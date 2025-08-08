<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var string $sdate */
/** @var string $edate */
/** @var array $results */
/** @var array $overall */

$this->title = 'รายงานคุณภาพน้ำยาง';
?>

<style>
.report-header {background:linear-gradient(135deg,#20c997,#0dcaf0);border-radius:15px;padding:1.2rem 1.5rem;color:#fff;margin-bottom:1.2rem;}
.table thead th {background:linear-gradient(135deg,#343a40,#495057);color:#fff;text-align:center}
.table-warning{background:linear-gradient(135deg,#fff3cd,#ffeaa7)!important;font-weight:600}
</style>

<div class="report-header">
  <h5><i class="bi bi-patch-check"></i> รายงานคุณภาพน้ำยาง</h5>
</div>

<div class="search-card">
  <form method="get" action="<?= Url::to(['report/quality-report']) ?>">
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label">วันที่</label>
        <input type="date" name="sdate" value="<?= Html::encode($sdate) ?>" class="form-control"/>
      </div>
      <div class="col-md-3">
        <label class="form-label">ถึงวันที่</label>
        <input type="date" name="edate" value="<?= Html::encode($edate) ?>" class="form-control"/>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-success w-100" type="submit">ค้นหา</button>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100" type="button" onclick="printReport()"><i class="bi bi-printer"></i> พิมพ์</button>
      </div>
    </div>
  </form>
</div>

<?php if (!empty($results)): ?>
<div class="mb-2 text-muted">ช่วงวันที่ <?= Yii::$app->helpers->DateThai($sdate) ?> ถึง <?= Yii::$app->helpers->DateThai($edate) ?></div>
<table class="table table-striped table-bordered table-hover datatable">
  <thead>
    <tr>
      <th>วันที่</th>
      <th>จำนวนรายการ</th>
      <th>%DRC เฉลี่ย</th>
      <th>%DRC ต่ำสุด</th>
      <th>%DRC สูงสุด</th>
      <th>ยางสดรวม (กก.)</th>
      <th>ยางแห้งรวม (กก.)</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results as $r): ?>
    <tr>
      <td class="text-center"><?= Yii::$app->helpers->DateThai($r['date']) ?></td>
      <td class="text-end"><?= number_format($r['count']) ?></td>
      <td class="text-end"><?= number_format($r['avg_percentage'], 2) ?></td>
      <td class="text-end"><?= number_format($r['min_percentage'], 2) ?></td>
      <td class="text-end"><?= number_format($r['max_percentage'], 2) ?></td>
      <td class="text-end"><?= number_format($r['sum_weight'], 2) ?></td>
      <td class="text-end"><?= number_format($r['sum_dry_weight'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr class="table-warning">
      <td class="text-center"><strong>รวม</strong></td>
      <td class="text-end"><strong><?= number_format($overall['count']) ?></strong></td>
      <td class="text-end"><strong><?= number_format($overall['avg_percentage'], 2) ?></strong></td>
      <td class="text-end"><strong><?= number_format($overall['min_percentage'], 2) ?></strong></td>
      <td class="text-end"><strong><?= number_format($overall['max_percentage'], 2) ?></strong></td>
      <td class="text-end"><strong><?= number_format($overall['sum_weight'], 2) ?></strong></td>
      <td class="text-end"><strong><?= number_format($overall['sum_dry_weight'], 2) ?></strong></td>
    </tr>
  </tfoot>
</table>
<?php else: ?>
<div class="alert alert-info text-center">ไม่พบข้อมูลในช่วงวันที่ที่เลือก</div>
<?php endif; ?>

<div id="printContent" class="d-none">
  <div class="text-center mb-3">
    <h2>สหกรณ์การกองทุนยางฉลองน้ำขาวพัฒนา จำกัด</h2>
    <h2>รายงานคุณภาพน้ำยาง</h2>
    <h2>ช่วงวันที่ <?= Yii::$app->helpers->DateThai($sdate) ?> ถึง <?= Yii::$app->helpers->DateThai($edate) ?></h2>
    <hr style="border:1px solid #000;margin:10px 0;">
  </div>
  <?php if (!empty($results)): ?>
  <table class="print-table">
    <thead>
      <tr>
        <th>วันที่</th>
        <th>จำนวน</th>
        <th>%DRC เฉลี่ย</th>
        <th>%DRC ต่ำสุด</th>
        <th>%DRC สูงสุด</th>
        <th>ยางสดรวม</th>
        <th>ยางแห้งรวม</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($results as $r): ?>
      <tr>
        <td><?= Yii::$app->helpers->DateThai($r['date']) ?></td>
        <td><?= number_format($r['count']) ?></td>
        <td><?= number_format($r['avg_percentage'], 2) ?></td>
        <td><?= number_format($r['min_percentage'], 2) ?></td>
        <td><?= number_format($r['max_percentage'], 2) ?></td>
        <td><?= number_format($r['sum_weight'], 2) ?></td>
        <td><?= number_format($r['sum_dry_weight'], 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<style>
@media print { body *{visibility:hidden} #printContent,#printContent *{visibility:visible} #printContent{position:absolute;left:0;top:0;width:100%;display:block!important} @page{size:A4 portrait;margin:12mm} .print-table{width:100%;border-collapse:collapse;border:2px solid #000;font-size:14px} .print-table th,.print-table td{border:1px solid #000;padding:6px 4px;text-align:center} .print-table th{background:#f0f0f0;font-weight:700} .d-none{display:none!important} }
#printContent{display:none}
.search-card{background:#fff;border-radius:12px;padding:1.2rem;box-shadow:0 2px 10px rgba(0,0,0,.08);border:1px solid #e9ecef;margin-bottom:1.2rem}
.btn-primary{background:linear-gradient(135deg,#007bff,#6610f2);border:none;border-radius:8px}
.btn-success{background:linear-gradient(135deg,#28a745,#20c997);border:none;border-radius:8px}
</style>

<script>
function printReport(){
  const pc = document.getElementById('printContent');
  pc.style.display='block';
  window.print();
  setTimeout(()=>{pc.style.display='none';},500);
}
</script>
