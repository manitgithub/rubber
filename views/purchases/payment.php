<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $groupedPayments */
/** @var string|null $startDate */
/** @var string|null $endDate */

$this->title = 'รันเลขใบเสร็จ';
$this->params['breadcrumbs'][] = $this->title;

// ตั้งค่าวันที่เริ่มต้นถ้ายังไม่มีการเลือก
if (empty($startDate)) {
    $startDate = date('Y-m-d', strtotime('-7 days'));
}
if (empty($endDate)) {
    $endDate = date('Y-m-d', strtotime('-1 days'));
}

if (empty($printDate)) {
    $printDate = date('Y-m-d', strtotime('+1 days'));
}
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

/* Main container */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Page Header */
.page-header {
    margin-bottom: 2.5rem;
    text-align: center;
}

.page-title {
    color: #2c3e50;
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 0;
}

/* Search Card */
.search-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.2);
    overflow: hidden;
    margin-bottom: 2rem;
}

.search-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
}

.search-card .card-body {
    padding: 2.5rem;
    position: relative;
}

.search-card h5 {
    color: white;
    font-weight: 600;
    margin-bottom: 2rem;
    font-size: 1.3rem;
}

.form-control {
    border-radius: 12px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    padding: 0.875rem 1.25rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
    color: #333;
    font-weight: 500;
}

.form-control:focus {
    border-color: rgba(255, 255, 255, 0.8);
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
    background: rgba(255, 255, 255, 0.9);
}

.form-label {
    color: white !important;
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.btn-search {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 12px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
    color: white;
    font-size: 1rem;
}

.btn-search:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    color: white;
}

/* Stats Card */
.stats-card {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    border: none;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 12px 40px rgba(23, 162, 184, 0.25);
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 10px,
        rgba(255, 255, 255, 0.05) 10px,
        rgba(255, 255, 255, 0.05) 20px
    );
    animation: float 20s linear infinite;
}

@keyframes float {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

.stats-card .icon {
    font-size: 3rem;
    margin-right: 1.5rem;
    opacity: 0.9;
}

.stats-card h4 {
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.stats-card p {
    font-size: 1.1rem;
    opacity: 0.9;
    position: relative;
    z-index: 2;
}

/* Header actions */
.header-actions {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.btn-run-all {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    border-radius: 12px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 123, 255, 0.3);
    color: white;
    font-size: 1rem;
}

.btn-run-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 123, 255, 0.4);
    color: white;
}

/* Data Table */
.data-table {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 700;
    color: #495057;
    padding: 1.5rem 1.25rem;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #fff5f5 100%);
    transform: scale(1.005);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.table tbody td {
    padding: 1.5rem 1.25rem;
    vertical-align: middle;
    border: none;
    font-weight: 500;
}

.table tbody tr:last-child {
    border-bottom: none;
}

/* Member Avatar */
.member-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
    margin-right: 1rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

/* Badges */
.badge-count {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    font-size: 1rem;
    padding: 0.625rem 1.25rem;
    border-radius: 25px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
}

/* Action Buttons */
.btn-view {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
    border: none;
    border-radius: 10px;
    padding: 0.625rem 1.25rem;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(111, 66, 193, 0.4);
    color: white;
}

/* Alerts */
.alert-custom {
    border: none;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.alert-warning-custom {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border-left: 5px solid #ffc107;
}

.alert-warning-custom i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.8;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem 0.5rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .search-card .card-body {
        padding: 1.5rem;
    }
    
    .stats-card {
        padding: 1.5rem;
    }
    
    .member-avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .table tbody td {
        padding: 1rem 0.75rem;
        font-size: 0.9rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out;
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-receipt-cutoff me-3"></i>
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="page-subtitle">จัดการและรันเลขใบเสร็จสำหรับสมาชิก</p>
    </div>

    <!-- Search Card -->
    <div class="search-card no-print">
        <div class="card-body">
            <h5><i class="bi bi-calendar-range me-2"></i>เลือกช่วงวันที่เพื่อรันเลขใบเสร็จ</h5>

            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['purchases/payment']]); ?>
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <?= Html::label('วันที่เริ่มต้น', 'start_date', ['class' => 'form-label']) ?>
                    <?= Html::input('date', 'start_date', $startDate, ['class' => 'form-control datepicker']) ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= Html::label('วันที่สิ้นสุด', 'end_date', ['class' => 'form-label']) ?>
                    <?= Html::input('date', 'end_date', $endDate, ['class' => 'form-control datepicker']) ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= Html::label('วันที่ใบเสร็จ', 'receipt_date', ['class' => 'form-label']) ?>
                    <?= Html::input('date', 'receipt_date', Yii::$app->request->get('receipt_date', $printDate), [
                        'class' => 'form-control datepicker',
                        'title' => 'วันที่ที่จะบันทึกในใบเสร็จ'
                    ]) ?>
                    <small class="form-text text-light">วันที่ที่จะบันทึกในใบเสร็จ</small>
                </div>
                <div class="col-md-3 mb-3">
                    <?= Html::submitButton('<i class="bi bi-search me-2"></i>ค้นหา', ['class' => 'btn btn-search w-100']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
    </div>
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
    // Sort by memberid
    uasort($unprintedGroups, function($a, $b) {
        $memberA = $a[0]->members;
        $memberB = $b[0]->members;
        return strcmp($memberA->memberid, $memberB->memberid);
    });
    ?>

    <?php if (!empty($unprintedGroups)): ?>
        <!-- Stats Card -->
        <div class="stats-card animate-fade-in">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill icon"></i>
                <div>
                    <h4 class="mb-1">พบ <span class="badge-count"><?= count($unprintedGroups) ?></span> สมาชิก</h4>
                    <p class="mb-0">ที่ยังไม่มีเลขใบเสร็จในช่วงวันที่ที่เลือก</p>
                </div>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="header-actions animate-fade-in">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2 text-primary"></i>
                    <strong>รายการสมาชิกที่ต้องรันใบเสร็จ</strong>
                </h5>
                <?= Html::a('<i class="bi bi-play-circle-fill me-2"></i>รันเลขใบเสร็จทั้งหมด', 
                    ['purchases/run-all-receipts', 
                     'start_date' => $startDate, 
                     'end_date' => $endDate,
                     'receipt_date' => Yii::$app->request->get('receipt_date', date('Y-m-d'))
                    ], [
                    'class' => 'btn btn-run-all',
                    'style' => 'color: white !important;',
                    'data-confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการรันเลขใบเสร็จทั้งหมด?'
                ]) ?>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-table animate-fade-in">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-2"></i>รหัสสมาชิก</th>
                            <th><i class="bi bi-person-fill me-2"></i>ชื่อสมาชิก</th>
                            <th class="text-center"><i class="bi bi-list-ol me-2"></i>จำนวนรายการ</th>
                            <th class="text-end"><i class="bi bi-speedometer me-2"></i>น้ำหนักรวม (กก.)</th>
                            <th class="text-end"><i class="bi bi-currency-dollar me-2"></i>ยอดรวม (บาท)</th>
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
                                <td>
                                            <div class="member-avatar">
                                            <?= $member->memberid ?>
                                        </div>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong><?= Html::encode($member->fullname2) ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info rounded-pill"><?= count($purchases) ?> รายการ</span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-dark"><?= number_format($totalWeight, 2) ?></strong>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success fs-5">฿<?= number_format($totalAmount, 2) ?></strong>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-view btn-sm" 
                                            onclick="openReceiptDetail('<?= Url::to(['purchases/view-member-items', 'member_id' => $memberId, 'start_date' => $startDate, 'end_date' => $endDate, 'receipt_date' => Yii::$app->request->get('receipt_date', date('Y-m-d'))]) ?>')">
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
        <div class="alert alert-warning-custom alert-custom animate-fade-in">
            <i class="bi bi-info-circle-fill mb-3"></i>
            <h5 class="mb-3">ไม่พบรายการ</h5>
            <p class="mb-0 fs-6">ไม่พบรายการที่ยังไม่ได้รันใบเสร็จในช่วงวันที่ที่เลือก</p>
            <small class="text-muted mt-2 d-block">กรุณาเลือกช่วงวันที่ใหม่หรือตรวจสอบข้อมูลอีกครั้ง</small>
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
    // เปิด popup window
    window.open(url, 'receiptDetail', 'width=1200,height=800,scrollbars=yes,resizable=yes,menubar=no,toolbar=no,location=no,status=no');
}

// Enhanced animations and effects
document.addEventListener('DOMContentLoaded', function() {
    // Animate elements on load
    const animatedElements = document.querySelectorAll('.search-card, .stats-card, .header-actions, .data-table, .alert-custom');
    
    animatedElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 150);
    });

    // Add loading animation to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!this.classList.contains('loading')) {
                this.classList.add('loading');
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>กำลังดำเนินการ...';
                
                setTimeout(() => {
                    this.classList.remove('loading');
                    this.innerHTML = originalText;
                }, 2000);
            }
        });
    });

    // Smooth scroll to search results
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            setTimeout(() => {
                const results = document.querySelector('.stats-card, .alert-custom');
                if (results) {
                    results.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }, 100);
        });
    }

    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
