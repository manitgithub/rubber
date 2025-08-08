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

<style>
@media print {
    .no-print {
        display: none !important;
    }
}

/* Custom styling for better UI */
.search-card {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.search-card .card-body {
    padding: 2rem;
}

.search-card h5 {
    color: white !important;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.form-label {
    color: white !important;
    font-weight: 600 !important;
    margin-bottom: 0.5rem;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.form-control {
    border-radius: 10px;
    border: 2px solid #e3e6f0;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background-color: white;
    color: #495057;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    background-color: white;
    color: #495057;
}

.btn-search {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    color: white !important;
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    color: white !important;
}

.stats-card {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
    color: white;
    border: none;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 32px rgba(111, 66, 193, 0.2);
}

.stats-card .icon {
    font-size: 2rem;
    margin-right: 1rem;
}

.btn-print-all {
    background: linear-gradient(135deg, #fd7e14 0%, #e55100 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(253, 126, 20, 0.3);
    color: white !important;
    text-decoration: none !important;
}

.btn-print-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(253, 126, 20, 0.4);
    color: white !important;
    text-decoration: none !important;
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

.btn-print {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    color: white !important;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none !important;
}

.btn-print:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
    color: white !important;
    text-decoration: none !important;
}

.alert-custom {
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

.receipt-card {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 0.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.receipt-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.receipt-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.receipt-number {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
}

.amount-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 15px;
    font-weight: bold;
}
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title">
                <i class="bi bi-printer-fill"></i> 
                <?= Html::encode($this->title) ?>
            </h2>
        </div>
    </div>

    <div class="card search-card mb-4 no-print">
        <div class="card-body">
            <h5><i class="bi bi-funnel-fill me-2"></i> ค้นหาใบเสร็จ</h5>

            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['purchases/bill']]); ?>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <?= Html::label('วันที่ออกใบเสร็จ', 'filter_date', ['class' => 'form-label fw-bold']) ?>
                    <?= Html::input('date', 'filter_date', $filterDate, ['class' => 'form-control']) ?>
                </div>
                <div class="col-md-2">
                    <?= Html::label('เล่มที่', 'book_no', ['class' => 'form-label fw-bold']) ?>
                    <?= Html::textInput('book_no', $bookNo, ['class' => 'form-control', 'placeholder' => 'เช่น 001']) ?>
                </div>
                <div class="col-md-2">
                    <?= Html::label('เลขที่', 'run_no', ['class' => 'form-label fw-bold']) ?>
                    <?= Html::textInput('run_no', $runNo, ['class' => 'form-control', 'placeholder' => 'เช่น 0001']) ?>
                </div>
                <div class="col-md-3">
                    <?= Html::label('รหัสสมาชิก', 'memberid', ['class' => 'form-label fw-bold']) ?>
                    <?= Html::textInput('memberid', $memberId, ['class' => 'form-control', 'placeholder' => 'กรอกรหัสสมาชิก']) ?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('<i class="bi bi-search me-2"></i>ค้นหา', ['class' => 'btn btn-search w-100']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if (!empty($receipts)): ?>
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <i class="bi bi-receipt-cutoff icon"></i>
                <div>
                    <h4 class="mb-1">พบ <span class="badge bg-light text-dark rounded-pill"><?= count($receipts) ?></span> ใบเสร็จ</h4>
                    <p class="mb-0">ตามเงื่อนไขที่ค้นหา</p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>รายการใบเสร็จ</h5>
            <?= Html::a('<i class="bi bi-printer-fill me-2"></i>พิมพ์ทั้งหมด', [
                'purchases/print-all-bills',
                'filter_date' => $filterDate,
                'book_no' => $bookNo,
                'run_no' => $runNo,
                'member_id' => $memberId,
            ], ['class' => 'btn btn-print-all', 'target' => '_blank']) ?>
        </div>

        <div class="data-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-calendar-date me-2"></i>วันที่</th>
                            <th><i class="bi bi-journal-bookmark me-2"></i>เล่ม/เลขที่</th>
                            <th><i class="bi bi-person-fill me-2"></i>ชื่อสมาชิก</th>
                            <th class="text-end"><i class="bi bi-currency-dollar me-2"></i>จำนวนเงิน</th>
                            <th class="text-center no-print"><i class="bi bi-gear me-2"></i>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($receipts as $receipt): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 text-primary me-2"></i>
                                        <span><?= Yii::$app->helpers->DateThai($receipt->receipt_date) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="receipt-number">
                                        <?= Html::encode($receipt->book_no) ?>/<?= str_pad($receipt->running_no, 4, '0', STR_PAD_LEFT) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    
                                        <span><?= Html::encode($receipt->member->fullname) ?></span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="amount-badge">
                                        <?= number_format($receipt->total_amount, 2) ?> บาท
                                    </span>
                                </td>
                                <td class="text-center no-print">
                                    <?= Html::a('<i class="bi bi-printer me-1"></i>พิมพ์', ['purchases/print-bill', 'id' => $receipt->id], [
                                        'class' => 'btn btn-print btn-sm',
                                        'target' => '_blank',
                                        'title' => 'พิมพ์ใบเสร็จ'
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning-custom alert-custom text-center">
            <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.5rem;"></i>
            <h5 class="mb-2">ไม่พบใบเสร็จ</h5>
            <p class="mb-0">ไม่พบใบเสร็จตามเงื่อนไขที่เลือก กรุณาปรับเงื่อนไขการค้นหาใหม่</p>
        </div>
    <?php endif; ?>

</div>

<script>
// เพิ่ม smooth scroll effect และ animation
document.addEventListener('DOMContentLoaded', function() {
    // เพิ่มการเคลื่อนไหวเมื่อโหลดหน้า
    const cards = document.querySelectorAll('.card, .data-table, .stats-card, .alert-custom');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // เพิ่ม hover effect สำหรับแถวตาราง
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9ff';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    // เพิ่ม loading effect เมื่อคลิกปุ่มพิมพ์
    const printButtons = document.querySelectorAll('.btn-print, .btn-print-all');
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>กำลังเตรียม...';
            this.disabled = true;
            
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 2000);
        });
    });
});
</script>