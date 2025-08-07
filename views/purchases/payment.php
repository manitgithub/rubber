<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $groupedPayments */
/** @var string|null $start<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title">
                <i class="bi bi-receipt-cutoff"></i> 
                <?= Html::encode($this->title) ?>
            </h2>
        </div>
    </div>

    <div class="card search-card mb-4 no-print">
        <div class="card-body">
            <h5><i class="bi bi-calendar-range me-2"></i> เลือกช่วงวันที่เพื่อรันเลขใบเสร็จ</h5>

            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['purchases/payment']]); ?>
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <?= Html::label('วันที่เริ่มต้น', 'start_date', ['class' => 'form-label fw-bold']) ?>
                    <?= Html::input('date', 'start_date', $startDate, ['class' => 'form-control']) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= Html::label('วันที่สิ้นสุด', 'end_date', ['class' => 'form-label fw-bold']) ?>
                    <?= Html::input('date', 'end_date', $endDate, ['class' => 'form-control']) ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= Html::submitButton('<i class="bi bi-search me-2"></i>ค้นหา', ['class' => 'btn btn-search w-100']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>ring|null $endDate */

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

/* Custom styling for better UI */
.search-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.search-card .card-body {
    padding: 2rem;
}

.search-card h5 {
    color: white;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.form-control {
    border-radius: 10px;
    border: 2px solid #e3e6f0;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-search {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.stats-card {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    border: none;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 32px rgba(23, 162, 184, 0.2);
}

.stats-card .icon {
    font-size: 2rem;
    margin-right: 1rem;
}

.btn-run-all {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.btn-run-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

.data-table {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
    transform: scale(1.01);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-color: #e9ecef;
}

.btn-view {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-view:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
    color: white;
}

.alert-custom {
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.alert-info-custom {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.alert-warning-custom {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border-left: 4px solid #ffc107;
}

.page-title {
    color: #495057;
    font-weight: 700;
    margin-bottom: 2rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.badge-count {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
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
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill icon"></i>
                <div>
                    <h4 class="mb-1">พบ <span class="badge-count"><?= count($unprintedGroups) ?></span> สมาชิก</h4>
                    <p class="mb-0">ที่ยังไม่มีเลขใบเสร็จในช่วงวันที่ที่เลือก</p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>รายการสมาชิกที่ต้องรันใบเสร็จ</h5>
            <?= Html::a('<i class="bi bi-play-circle-fill me-2"></i>รันเลขใบเสร็จทั้งหมด', 
                ['purchases/run-all-receipts', 'start_date' => $startDate, 'end_date' => $endDate], [
                'class' => 'btn btn-run-all',
                'data-confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการรันเลขใบเสร็จทั้งหมด?'
            ]) ?>
        </div>

        <div class="data-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-2"></i>รหัสสมาชิก</th>
                            <th><i class="bi bi-person-fill me-2"></i>ชื่อสมาชิก</th>
                            <th><i class="bi bi-list-ol me-2"></i>จำนวนรายการ</th>
                            <th><i class="bi bi-speedometer me-2"></i>น้ำหนักรวม (กก.)</th>
                            <th><i class="bi bi-currency-dollar me-2"></i>ยอดรวม (บาท)</th>
                            <th class="no-print text-center"><i class="bi bi-eye me-2"></i>ดูรายการ</th>
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
                                <td><strong><?= Html::encode($memberId) ?></strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px; color: white; font-weight: bold;">
                                            <?= strtoupper(substr($member->fullname, 0, 1)) ?>
                                        </div>
                                        <span><?= Html::encode($member->fullname) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info rounded-pill"><?= count($purchases) ?> รายการ</span>
                                </td>
                                <td class="text-end">
                                    <strong><?= number_format($totalWeight, 2) ?></strong>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success"><?= number_format($totalAmount, 2) ?></strong>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-view btn-sm" 
                                            onclick="openReceiptDetail('<?= Url::to(['purchases/view-member-items', 'member_id' => $memberId, 'start_date' => $startDate, 'end_date' => $endDate]) ?>')">
                                        <i class="bi bi-eye me-1"></i>ดูรายละเอียด
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning-custom alert-custom text-center">
            <i class="bi bi-info-circle-fill me-2" style="font-size: 1.5rem;"></i>
            <h5 class="mb-2">ไม่พบรายการ</h5>
            <p class="mb-0">ไม่พบรายการที่ยังไม่ได้รันใบเสร็จในช่วงวันที่ที่เลือก</p>
        </div>
    <?php endif; ?>
<?php endif; ?>

</div>

<?php Modal::begin([
    'title' => '<h5 class="modal-title"><i class="bi bi-receipt me-2"></i>รายละเอียดรายการ</h5>',
    'id' => 'detail-modal',
    'size' => Modal::SIZE_LARGE,
    'options' => ['tabindex' => false],
    'clientOptions' => ['backdrop' => true, 'keyboard' => true]
]); ?>
<div id="modal-content"></div>
<?php Modal::end(); ?>

<script>
function openReceiptDetail(url) {
    // เพิ่ม loading effect
    const modal = document.getElementById('detail-modal');
    const content = document.getElementById('modal-content');
    
    // แสดง loading spinner
    content.innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">กำลังโหลดข้อมูล...</p>
        </div>
    `;
    
    // เปิด popup window
    window.open(url, 'receiptDetail', 'width=1000,height=700,scrollbars=yes,resizable=yes');
}

// เพิ่ม smooth scroll effect
document.addEventListener('DOMContentLoaded', function() {
    // เพิ่มการเคลื่อนไหวเมื่อโหลดหน้า
    const cards = document.querySelectorAll('.card, .data-table, .stats-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
