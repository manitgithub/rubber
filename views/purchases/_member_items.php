<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $purchases */
/** @var \app\models\Members $member */
/** @var string $startDate */
/** @var string $endDate */

$this->title = 'รายละเอียดรายการของสมาชิก';
?>

<style>
body {
    font-family: 'Sarabun', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

.container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    margin: 2rem auto;
    max-width: 1200px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.header-section {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    color: white;
    padding: 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
}

.header-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.header-member {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #f8f9fa;
}

.header-date {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 400;
}

.content-section {
    padding: 2rem;
}

.stats-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    margin-bottom: 2rem;
}

.table {
    margin-bottom: 0;
    font-size: 0.95rem;
}

.table thead th {
    background: linear-gradient(135deg, #495057 0%, #343a40 100%);
    color: white;
    border: none;
    font-weight: 700;
    padding: 1.2rem 1rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #fff5f5 100%);
    transform: scale(1.002);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border: none;
    font-weight: 500;
}

/* จัดตัวเลขให้ชิดขวา */
.table td:nth-child(4),  /* น้ำหนัก */
.table td:nth-child(5),  /* เปอร์เซ็นต์ */
.table td:nth-child(6),  /* น้ำหนักแห้ง */
.table td:nth-child(7),  /* ราคาต่อกก */
.table td:nth-child(8) { /* จำนวนเงิน */
    text-align: right !important;
}

.table tbody tr:last-child {
    border-bottom: none;
}

.summary-row {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
    border-top: 3px solid #ffc107;
    font-weight: 700;
}

.summary-row:hover {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
    transform: none !important;
}

.summary-row td {
    padding: 1.5rem 1rem;
    font-size: 1.05rem;
}

.btn-close {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem 3rem;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(220, 53, 69, 0.3);
    display: block;
    margin: 0 auto;
}

.btn-close:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(220, 53, 69, 0.4);
    color: white;
}

.btn-close:active {
    transform: translateY(0);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.6;
}

.empty-state h5 {
    margin-bottom: 1rem;
    color: #495057;
}

.alert-custom {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: none;
    border-left: 5px solid #ffc107;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(255, 193, 7, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        margin: 1rem;
        border-radius: 15px;
    }
    
    .header-section {
        padding: 1.5rem;
    }
    
    .header-title {
        font-size: 1.5rem;
    }
    
    .header-member {
        font-size: 1.25rem;
    }
    
    .content-section {
        padding: 1rem;
    }
    
    .stats-summary {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
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

<div class="container animate-fade-in">
    <!-- Header Section -->
    <div class="header-section">
        <h1 class="header-title">
            <i class="bi bi-receipt-cutoff me-3"></i>
            รายละเอียดรายการของสมาชิก
        </h1>
        <div class="header-member">
            <i class="bi bi-person-circle me-2"></i>
            <?= Html::encode($member->fullname) ?>
        </div>
        <div class="header-date">
            <i class="bi bi-calendar-range me-2"></i>
            ช่วงวันที่ <?= Yii::$app->helpers->DateThai($startDate) ?> - <?= Yii::$app->helpers->DateThai($endDate) ?>
        </div>
    </div>

    <!-- Content Section -->
    <div class="content-section">
        <?php if (!empty($purchases)): ?>
            <?php
            // คำนวณสรุปข้อมูล
            $total_weight = 0;
            $total_dry_weight = 0;
            $total_amount = 0;
            $total_items = count($purchases);
            
            foreach ($purchases as $p) {
                $total_weight += $p->weight;
                $total_dry_weight += $p->dry_weight;
                $total_amount += $p->total_amount;
            }
            ?>
            
            <!-- Summary Statistics -->
            <div class="stats-summary">
                <div class="stat-card">
                    <div class="stat-value"><?= $total_items ?></div>
                    <div class="stat-label">รายการทั้งหมด</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($total_weight, 2) ?></div>
                    <div class="stat-label">น้ำหนักรวม (กก.)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= number_format($total_dry_weight, 1) ?></div>
                    <div class="stat-label">น้ำหนักแห้ง (กก.)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">฿<?= number_format($total_amount, 2) ?></div>
                    <div class="stat-label">ยอดเงินรวม</div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%"><i class="bi bi-calendar-date me-2"></i>วันที่</th>
                                <th width="15%"><i class="bi bi-receipt me-2"></i>เลขใบรับ</th>
                                <th width="12%" class="text-end"><i class="bi bi-speedometer me-2"></i>น้ำหนัก (กก.)</th>
                                <th width="10%" class="text-end"><i class="bi bi-percent me-2"></i>เปอร์เซ็นต์</th>
                                <th width="12%" class="text-end"><i class="bi bi-moisture me-2"></i>น้ำหนักแห้ง</th>
                                <th width="13%" class="text-end"><i class="bi bi-currency-dollar me-2"></i>ราคาต่อกก.</th>
                                <th width="18%" class="text-end"><i class="bi bi-cash-stack me-2"></i>จำนวนเงิน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $running_total = 0;
                            foreach ($purchases as $p):
                                $running_total += $p->total_amount;
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill"><?= $i++ ?></span>
                                    </td>
                                    <td>
                                        <strong><?= Yii::$app->helpers->DateThai($p->date) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= Html::encode($p->receipt_number) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-dark"><?= number_format($p->weight, 2) ?></strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-warning"><?= number_format($p->percentage, 2) ?>%</span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-info"><?= number_format($p->dry_weight, 1) ?></strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-secondary">฿<?= number_format($p->price_per_kg, 2) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success fs-6">฿<?= number_format($p->total_amount, 2) ?></strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            
                            <!-- Summary Row -->
                            <tr class="summary-row">
                                <td colspan="3" class="text-center">
                                    <i class="bi bi-calculator me-2"></i>
                                    <strong>รวมทั้งหมด</strong>
                                </td>
                                <td class="text-end">
                                    <strong><?= number_format($total_weight, 2) ?></strong>
                                </td>
                                <td class="text-center">
                                    <i class="bi bi-dash-circle text-muted"></i>
                                </td>
                                <td class="text-end">
                                    <strong><?= number_format($total_dry_weight, 2) ?></strong>
                                </td>
                                <td class="text-center">
                                    <i class="bi bi-dash-circle text-muted"></i>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success">฿<?= number_format($total_amount, 2) ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <button class="btn btn-close" onclick="window.close()">
                <i class="bi bi-x-circle me-2"></i>ปิดหน้าต่าง
            </button>
            
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>ไม่พบรายการ</h5>
                <p>ไม่พบรายการในช่วงวันที่ที่เลือก</p>
                <button class="btn btn-close mt-3" onclick="window.close()">
                    <i class="bi bi-x-circle me-2"></i>ปิดหน้าต่าง
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>
