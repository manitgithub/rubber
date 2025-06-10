<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $purchases */
/** @var \app\models\Members $member */
/** @var string $startDate */
/** @var string $endDate */

$this->title = 'รายละเอียดรายการของสมาชิก';
?>

<div class="container py-3">
    <h5 class="mb-3">🧾 รายการของ: <strong><?= Html::encode($member->fullname) ?></strong></h5>
    <p class="text-muted mb-4">ช่วงวันที่ <?= Yii::$app->helpers->DateThai($startDate) ?> - <?= Yii::$app->helpers->DateThai($endDate) ?></p>

    <?php if (!empty($purchases)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>วันที่</th>
                        <th>เลขใบรับ</th>
                        <th class="text-end">น้ำหนัก (กก.)</th>
                        <th class="text-end">เปอร์เซ็นต์</th>
                        <th class="text-end">น้ำหนักแห้ง</th>
                        <th class="text-end">ราคาต่อกก.</th>
                        <th class="text-end">จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $total_weight = 0;
                    $total_dry_weight = 0;
                    $total_amount = 0;
                    foreach ($purchases as $p):
                        $total_weight += $p->weight;
                        $total_dry_weight += $p->dry_weight;
                        $total_amount += $p->total_amount;
                    ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= Yii::$app->helpers->DateThai($p->date) ?></td>
                            <td><?= Html::encode($p->receipt_number) ?></td>
                            <td class="text-end"><?= number_format($p->weight, 2) ?></td>
                            <td class="text-end"><?= number_format($p->percentage, 2) ?></td>
                            <td class="text-end"><?= number_format($p->dry_weight, 2) ?></td>
                            <td class="text-end"><?= number_format($p->price_per_kg, 2) ?></td>
                            <td class="text-end"><?= number_format($p->total_amount, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-warning">
                        <td colspan="3"><strong>รวม</strong></td>
                        <td class="text-end"><strong><?= number_format($total_weight, 2) ?></strong></td>
                        <td></td>
                        <td class="text-end"><strong><?= number_format($total_dry_weight, 2) ?></strong></td>
                        <td></td>
                        <td class="text-end"><strong><?= number_format($total_amount, 2) ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="btn btn-danger btn-block mt-3" onclick="window.close()">ปิด</button>
    <?php else: ?>
        <div class="alert alert-warning">ไม่พบรายการในช่วงวันที่ที่เลือก</div>
    <?php endif; ?>
</div>
