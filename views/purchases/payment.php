<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $groupedPayments */
/** @var string|null $startDate */
/** @var string|null $endDate */

\yii\bootstrap5\BootstrapAsset::register($this);
\yii\bootstrap5\BootstrapPluginAsset::register($this);
\yii\widgets\ActiveFormAsset::register($this);

$this->title = 'รันเลขใบเสร็จ';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .page-break {
        page-break-after: always;
    }
}

.modal-backdrop.show {
    opacity: 0.3 !important;
    background-color: #000 !important;
    z-index: 1040 !important;
}
.modal {
    z-index: 1055 !important;
    background-color: #fff;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
}
</style><?php
$this->registerJsFile(
    '@web/assets/js/yii.activeForm.js',  // หรือเปลี่ยนเป็น path ที่ใช้จริง
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>


<h4><?= Html::encode($this->title) ?></h4>

<div class="card card-body mb-4 no-print">
    <h5><i class="bi bi-calendar-range"></i> เลือกช่วงวันที่เพื่อรันเลขใบเสร็จ</h5>

    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['purchases/payment']]); ?>
    <div class="row">
        <div class="col-md-4">
            <?= Html::label('วันที่เริ่มต้น', 'start_date') ?>
            <?= Html::input('date', 'start_date', $startDate, ['class' => 'form-control datepicker']) ?>
        </div>
        <div class="col-md-4">
            <?= Html::label('วันที่สิ้นสุด', 'end_date') ?>
            <?= Html::input('date', 'end_date', $endDate, ['class' => 'form-control datepicker']) ?>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <?= Html::submitButton('ค้นหา', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php if (!empty($startDate) && !empty($endDate)): ?>
    <?php
    $unprintedGroups = [];
    foreach ($groupedPayments as $memberId => $list) {
        $unpaid = array_filter($list, fn($p) => $p->status != 'PAID' && empty($p->receipt_id));
        if (!empty($unpaid)) {
            $unprintedGroups[$memberId] = $unpaid;
        }
    }
    ?>

    <?php if (!empty($unprintedGroups)): ?>
        <div class="alert alert-info">พบ <?= count($unprintedGroups) ?> สมาชิกที่ยังไม่มีเลขใบเสร็จ</div>
        <?= Html::a('รันเลขใบเสร็จทั้งหมด', ['purchases/run-all-receipts', 'start_date' => $startDate, 'end_date' => $endDate], [
            'class' => 'btn btn-primary mb-3',
            'data-confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการรันเลขใบเสร็จทั้งหมด?'
        ]) ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>รหัสสมาชิก</th>
                        <th>ชื่อสมาชิก</th>
                        <th>จำนวนรายการ</th>
                        <th>น้ำหนักรวม</th>
                        <th>ยอดรวม</th>
                        <th class="no-print">ดูรายการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unprintedGroups as $memberId => $purchases): ?>
                        <?php
                        $member = $purchases[0]->members;
                        $totalWeight = array_sum(array_map(fn($p) => $p->weight, $purchases));
                        $totalAmount = array_sum(array_map(fn($p) => $p->total_amount, $purchases));
                        ?>
                        <tr>
                            <td><?= Html::encode($memberId) ?></td>
                            <td><?= Html::encode($member->fullname) ?></td>
                            <td><?= count($purchases) ?> รายการ</td>
                            <td class="text-end"><?= number_format($totalWeight, 2) ?></td>
                            <td class="text-end"><?= number_format($totalAmount, 2) ?></td>
<td class="text-center">
    <button class="btn btn-outline-info btn-sm" onclick="openReceiptDetail('<?= Url::to(['purchases/view-member-items', 'member_id' => $memberId, 'start_date' => $startDate, 'end_date' => $endDate]) ?>')">ดู</button>
</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">ไม่พบรายการที่ยังไม่ได้รันใบเสร็จในช่วงวันที่ที่เลือก</div>
    <?php endif; ?>
<?php endif; ?>

<?php Modal::begin([
    'title' => '<h5 class="modal-title">รายละเอียดรายการ</h5>',
    'id' => 'detail-modal',
    'size' => Modal::SIZE_LARGE,
    'options' => ['tabindex' => false],
    'clientOptions' => ['backdrop' => true, 'keyboard' => true]
]); ?>
<div id="modal-content"></div>
<?php Modal::end(); ?>

<script>
function openReceiptDetail(url) {
    window.open(url, 'receiptDetail', 'width=1000,height=700,scrollbars=yes,resizable=yes');
}
</script>
