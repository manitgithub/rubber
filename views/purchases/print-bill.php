<?php
use yii\helpers\Html;

/** @var \app\models\Receipt $receipt */
/** @var \app\models\Members $member */

$this->title = 'พิมพ์ใบเสร็จ';
$this->registerCss('@media print { .no-print { display: none; } }');
$this->registerCss("
    .table {
        font-family: 'Courier New', monospace;
    }
");

?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="text-start">
    <strong>เล่มที่:</strong> <?= Html::encode($receipt->book_no) ?>
    </div>            
            
            <div class="text-end">
                <strong>เลขที่:</strong> <?= str_pad($receipt->running_no, 4, '0', STR_PAD_LEFT) ?><br>
            </div>
        </div>
    <div class="text-center mb-4">
     <h5>   สหกรณ์กองทุนสวนยางฉลองน้ำขาวพัฒนา จำกัด 92 หมู่ 5 ตำบลฉลอง อำเภอสิชล จังหวัดนครศรีฯ </h5> 
        <h4>ใบจ่ายเงินเจ้าหนี้ค่าน้ำยาง</h4>
            <strong>วันที่:</strong> <?= Yii::$app->helpers->DateThai($receipt->date) ?><br>
            <strong>ระหว่างวันที่:</strong> <?= Yii::$app->helpers->DateThai($receipt->start_date) ?> ถึง <?= Yii::$app->helpers->DateThai($receipt->end_date) ?><br>    
    </div>

    <div class="mb-3">
        <strong>ชื่อสมาชิก:</strong> <?= Html::encode($receipt->member->fullname) ?>
        <strong>เบอร์โทร:</strong> <?= Html::encode($receipt->member->phone) ?><br>
        <br> <strong>ที่อยู่:</strong> <?= Html::encode($receipt->member->address) ?>
    </div>
</div>
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
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
            $totalWeight = 0;
            $totalDry = 0;
            foreach ($receipt->purchases as $p):
                $totalWeight += $p->weight;
                $totalDry += $p->dry_weight;
            ?>
            <tr>
                <td><?= Yii::$app->helpers->DateThai($p->date) ?></td>
                <td><?= Html::encode($p->receipt_number) ?></td>
                <td class="text-end"><div style="align: right;"><?= number_format($p->weight, 2) ?></div></td>
                <td class="text-end"><?= number_format($p->percentage, 2) ?></td>
                <td class="text-end"><?= number_format($p->dry_weight, 2) ?></td>
                <td class="text-end"><?= number_format($p->price_per_kg, 2) ?></td>
                <td class="text-end"><?= number_format($p->total_amount, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-warning">
            <tr>
                <td colspan="2"><strong>รวม</strong></td>
                <td class="text-end"><strong><?= number_format($totalWeight, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($totalDry, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($receipt->total_amount, 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="text-start mt-4">
        <strong>รวมเป็นเงินทั้งสิ้น:</strong> <?= Yii::$app->helpers->Convert($receipt->total_amount) ?> (<?= number_format($receipt->total_amount, 2) ?> บาท)
    </div>

    <div class="row mt-5">
            <div class="col-6 text-center">
            <strong>ผู้จ่ายเงิน</strong><br><br><br>
            (....................................)<br>
            <?= Html::encode(Yii::$app->user->identity->fullname ?? '') ?>
        </div>
        <div class="col-6 text-center">
            <strong>ผู้รับเงิน</strong><br><br><br>
            (....................................)<br>
            <?= Html::encode($receipt->member->fullname) ?>
        </div>
    
    </div>

</div>
