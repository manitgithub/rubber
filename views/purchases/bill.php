<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $receipts */
/** @var string|null $filterDate */
/** @var string|null $bookNo */
/** @var string|null $runNo */
/** @var string|null $memberId */

$this->title = 'พิมพ์ใบเสร็จ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card card-body mb-4">
    <h5><i class="bi bi-funnel"></i> ค้นหาใบเสร็จ</h5>

    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['purchases/bill']]); ?>
    <div class="row g-3">
        <div class="col-md-3">
            <?= Html::label('วันที่ออกใบเสร็จ', 'filter_date') ?>
            <?= Html::input('date', 'filter_date', $filterDate, ['class' => 'form-control']) ?>
        </div>
        <div class="col-md-2">
            <?= Html::label('เล่มที่', 'book_no') ?>
            <?= Html::textInput('book_no', $bookNo, ['class' => 'form-control']) ?>
        </div>
        <div class="col-md-2">
            <?= Html::label('เลขที่', 'run_no') ?>
            <?= Html::textInput('run_no', $runNo, ['class' => 'form-control']) ?>
        </div>
        <div class="col-md-3">
            <?= Html::label('รหัสสมาชิก', 'member_id') ?>
            <?= Html::textInput('member_id', $memberId, ['class' => 'form-control']) ?>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <?= Html::submitButton('ค้นหา', ['class' => 'btn btn-success w-100']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php if (!empty($receipts)): ?>
<?= Html::a('<i class="bi bi-printer"></i> พิมพ์ทั้งหมด', [
    'purchases/print-all-bills',
    'filter_date' => $filterDate,
    'book_no' => $bookNo,
    'run_no' => $runNo,
    'member_id' => $memberId,
], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>


    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>วันที่</th>
                    <th>เล่ม/เลขที่</th>
                    <th>รหัสสมาชิก</th>
                    <th>ชื่อสมาชิก</th>
                    <th class="text-end">จำนวนเงิน</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receipts as $receipt): ?>
                    <tr>
                        <td><?= Yii::$app->helpers->DateThai($receipt->receipt_date) ?></td>
                        <td><?= Html::encode($receipt->book_no) ?>/<?= str_pad($receipt->running_no, 4, '0', STR_PAD_LEFT) ?></td>
                        <td><?= Html::encode($receipt->member_id) ?></td>
                        <td><?= Html::encode($receipt->member->fullname) ?></td>
                        <td class="text-end"><?= number_format($receipt->total_amount, 2) ?> บาท</td>
                        <td class="text-center">
                            <?= Html::a('พิมพ์', ['purchases/print-bill', 'id' => $receipt->id], [
                                'class' => 'btn btn-sm btn-outline-primary',
                                'target' => '_blank'
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-warning">ไม่พบใบเสร็จตามเงื่อนไขที่เลือก</div>
<?php endif; ?>
