<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var string $date */
/** @var app\models\Purchases[] $purchases */
/** @var float $total_weight */
/** @var float $total_dry_weight */
/** @var float $total_amount */


if(isset($_GET['sdate']) && !empty($_GET['sdate'])) {
    $sdate = $_GET['sdate'];
} else {
    $sdate = date('Y-m-d');
}

if (isset($_GET['edate']) && !empty($_GET['edate'])) {
    $edate = $_GET['edate'];
} else {
    $edate = date('Y-m-d');
}
$showday = true;
if($sdate == $edate) {
    $showday = false;
} else {
    $showday = true;
}

$this->title = $showday ? 'รายงานการรับซื้อน้ำยาง <br> วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'รายงานการรับซื้อน้ำยาง <br> วันที่ ' . Yii::$app->helpers->DateThai($sdate);

?>
<div class="card card-body mb-4">
    <h5><i class="bi bi-file-earmark-text"></i> รายงานสรุปการรับซื้อน้ำยาง</h5>
    

<div class="mb-3">
    <form method="get" action="<?= \yii\helpers\Url::to(['report/daily']) ?>">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">วันที่</label>
                <input type="date" name="sdate" value="<?= $sdate ?>" class="form-control datepicker">
            </div>

            <div class="col-md-3">
                <label class="form-label">ถึงวันที่</label>
                <input type="date" name="edate" value="<?= $edate ?>" class="form-control datepicker">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">ค้นหา</button>
            </div>
        </div>
    </form>
</div>



<?php if (!empty($purchases)): ?>
    <p class="text-muted">
        <?= $showday ? 'รายงานการรับซื้อน้ำยาง <br>วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'แสดงรายงานการรับซื้อน้ำยาง วันที่ ' . Yii::$app->helpers->DateThai($sdate) ?>
    </p>
    <table class="table datatable table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <?= $showday ? '<th>วันที่</th>' : '' ?>
                <th>ชื่อ-สกุล</th>
                <th>เลขทะเบียน</th>
                <th>นน ยางสด(กก.)</th>
                <th>%DRC</th>
                <th>นน ยางแห้ง (กก.)</th>
                <th>ราคา/กก.</th>
                <th>ยอดรวม</th>
                <th>ลายมือชื่อผู้ส่ง</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <?= $showday ? '<td>' . Yii::$app->helpers->DateThai($p->date) . '</td>' : '' ?>
                <td><?= Html::encode($p->members->fullname2) ?></td>
                <td><?= Html::encode($p->members->memberid) ?></td>
                <td><?= number_format($p->weight, 2) ?></td>
                <td><?= number_format($p->percentage, 2) ?></td>
                <td><?= number_format($p->dry_weight, 2) ?></td>
                <td><?= number_format($p->price_per_kg, 2) ?></td>
                <td><?= number_format($p->total_amount, 2) ?></td>
                <td></td>

            </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr class="table-warning">
                <td colspan="<?= $showday ? '4' : '3' ?>" class="text-end"><strong>รวม</strong></td>
                <td class="text-end"><strong><?= number_format($total_weight, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($total_dry_weight, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($total_amount, 2) ?></strong></td>
                                <td></td>

            </tr>
        </tfoot>
    </table>
<?php endif; ?>
