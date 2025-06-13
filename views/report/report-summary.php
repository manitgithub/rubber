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
$this->title = 'รายงานสรุปการยอดรับซื้อนำ้ยางรายวัน ระหว่าง วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' - ' . Yii::$app->helpers->DateThai($edate);
$total_a = 0;
$total_w = 0;
$total_dw = 0;
?>
<div class="card card-body mb-4">
<i class="bi bi-file-earmark-text"></i> รายงานสรุปการยอดรับซื้อนำ้ยางรายวัน</h5>
    

<div class="mb-3">
    <form method="get" action="<?= \yii\helpers\Url::to(['report/report-summary']) ?>">
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


<?php if (!empty($sdate)): ?>
    <p class="text-muted">แสดงรายงานการรับซื้อน้ำยาง วันที่ <?= Yii::$app->helpers->DateThai($sdate) ?> ถึง <?= Yii::$app->helpers->DateThai($edate) ?></p>

    <table class="table datatable table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>วันที่</th>
                <th>นน ยางสด(กก.)</th>
                <th>นน ยางแห้ง (กก.)</th>
                <th>จำนวนเงิน (บาท)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 0;
            $sql = "SELECT SUM(weight) AS total_weight, SUM(dry_weight) AS total_dry_weight, SUM(total_amount) AS total_amount,date FROM purchases WHERE date BETWEEN :sdate AND :edate
            GROUP BY DATE(date)"; 
            $command = Yii::$app->db->createCommand($sql);
            $command->bindValue(':sdate', $sdate);
            $command->bindValue(':edate', $edate);
            $result = $command->queryAll();
            
            foreach ($result as $value) {
                $total_weight = $value['total_weight'];
                $total_dry_weight = $value['total_dry_weight'];
                $total_amount = $value['total_amount'];
                $date = $value['date'];
                
                $total_w += $total_weight;
                $total_dw += $total_dry_weight;
                $total_a += $total_amount;
            
            
            ?>
            <tr>
                <td><?= ++$i ?></td>
                <td><?= Yii::$app->helpers->DateThai($date) ?></td>
                <td class="text-end"><?= number_format($total_weight, 2) ?></td>
                <td class="text-end"><?= number_format($total_dry_weight, 2) ?></td>
                <td class="text-end"><?= number_format($total_amount, 2) ?></td>
            </tr>
            <?php } ?>

        </tbody>
        <tfoot>
            <tr class="table-warning">
                <td colspan="2" class="text-center"><strong>รวม</strong></td>
                <td class="text-end"><strong><?= number_format($total_w, 2) ?></strong></td>
                <td class="text-end"><strong><?= number_format($total_dw, 2) ?></strong></td>
                <td class="text-end"><strong><?= number_format($total_a, 2) ?></strong></td>
            

            </tr>
        </tfoot>
    </table>
<?php endif; ?>
