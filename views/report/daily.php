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

$this->title = 'รายงานสรุปการรับซื้อน้ำยางรายวัน';
?>
<div class="card card-body mb-4">
    <h5><i class="bi bi-file-earmark-text"></i> รายงานสรุปการรับซื้อน้ำยาง</h5>
    

<div class="mb-3">
    <form method="get" action="<?= \yii\helpers\Url::to(['report/daily']) ?>">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">วันที่</label>
                <input type="date" name="date" value="<?= $date ?>" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">ค้นหา</button>
            </div>
        </div>
    </form>
</div>


<?php if (!empty($purchases)): ?>
    <p class="text-muted">แสดงรายงานการรับซื้อน้ำยางประจำวันที่ <?= Yii::$app->formatter->asDate($date, 'php:d/m/Y') ?></p>

    <table class="table datatable">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อสมาชิก</th>
                <th>น้ำหนัก (กก.)</th>
                <th>เปอร์เซ็นต์</th>
                <th>น้ำหนักแห้ง (กก.)</th>
                <th>ราคาต่อกก.</th>
                <th>ยอดรวม</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= Html::encode($p->members->fullname) ?></td>
                <td><?= number_format($p->weight, 2) ?></td>
                <td><?= number_format($p->percentage, 2) ?></td>
                <td><?= number_format($p->dry_weight, 2) ?></td>
                <td><?= number_format($p->price_per_kg, 2) ?></td>
                <td><?= number_format($p->total_amount, 2) ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr class="table-warning">
                <td colspan="2" class="text-center"><strong>รวม</strong></td>
                <td class="text-end"><strong><?= number_format($total_weight, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($total_dry_weight, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($total_amount, 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>
