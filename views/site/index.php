<?php

use app\models\AuditDepartment;
use app\models\Purchases;
use app\models\Members;
use yii\helpers\Url;
use app\models\News;

$this->title = 'ยินดีต้อนรับสู่ระบบบริหาร 👋';

// ดึงข้อมูลสถิติจากฐานข้อมูล
$totalWeight = Purchases::find()->where(['flagdel' => 0])->sum('dry_weight') ?: 0;
$totalAmount = Purchases::find()->where(['flagdel' => 0])->sum('total_amount') ?: 0;
$avgPrice = $totalWeight > 0 ? ($totalAmount / $totalWeight) : 0;
$totalRecords = Purchases::find()->where(['flagdel' => 0])->count();
$totalMembers = Members::find()->count();

// ดึงข้อมูลรายปีงบประมาณ (เมษายน - มีนาคม)
$currentMonth = date('n'); // เดือนปัจจุบัน (1-12)
$currentYear = date('Y');

// หาปีงบประมาณปัจจุบัน
if ($currentMonth >= 4) {
    // เมษายน-ธันวาคม = ปีงบประมาณปีนี้
    $currentBudgetYear = $currentYear + 543;
} else {
    // มกราคม-มีนาคม = ปีงบประมาณปีที่แล้ว
    $currentBudgetYear = $currentYear + 542;
}

$yearlyData = [];
for ($i = 2; $i >= 0; $i--) {
    $budgetYear = $currentBudgetYear - $i;
    $startYear = $budgetYear - 543;
    $endYear = $startYear + 1;
    
    // ปีงบประมาณ เริ่ม 1 เมษายน ถึง 31 มีนาคม ปีถัดไป
    $yearCondition = ['>=', 'date', $startYear . '-04-01'];
    $yearConditionEnd = ['<', 'date', $endYear . '-04-01'];
    
    $yearWeight = Purchases::find()
        ->where(['flagdel' => 0])
        ->andWhere($yearCondition)
        ->andWhere($yearConditionEnd)
        ->sum('dry_weight') ?: 0;
    
    $yearAmount = Purchases::find()
        ->where(['flagdel' => 0])
        ->andWhere($yearCondition)
        ->andWhere($yearConditionEnd)
        ->sum('total_amount') ?: 0;
    
    $yearCount = Purchases::find()
        ->where(['flagdel' => 0])
        ->andWhere($yearCondition)
        ->andWhere($yearConditionEnd)
        ->count();
    
    $yearlyData[$budgetYear] = [
        'weight' => $yearWeight,
        'amount' => $yearAmount,
        'count' => $yearCount,
        'avg_price' => $yearWeight > 0 ? ($yearAmount / $yearWeight) : 0
    ];
}

// ดึงข้อมูลรายเดือนของปีงบประมาณปัจจุบัน
$monthlyData = [];
$startYear = $currentBudgetYear - 543;
$endYear = $startYear + 1;

// สร้างรายชื่อเดือนภาษาไทย
$thaiMonths = [
    4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
    7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.',
    10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.',
    1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.'
];

for ($month = 4; $month <= 15; $month++) {
    $actualMonth = $month > 12 ? $month - 12 : $month;
    $yearForMonth = $month <= 12 ? $startYear : $endYear;
    
    $monthCondition = ['>=', 'date', $yearForMonth . '-' . sprintf('%02d', $actualMonth) . '-01'];
    $nextMonth = $actualMonth == 12 ? 1 : $actualMonth + 1;
    $nextYear = $actualMonth == 12 ? $yearForMonth + 1 : $yearForMonth;
    $monthConditionEnd = ['<', 'date', $nextYear . '-' . sprintf('%02d', $nextMonth) . '-01'];
    
    $monthWeight = Purchases::find()
        ->where(['flagdel' => 0])
        ->andWhere($monthCondition)
        ->andWhere($monthConditionEnd)
        ->sum('dry_weight') ?: 0;
    
    $monthAmount = Purchases::find()
        ->where(['flagdel' => 0])
        ->andWhere($monthCondition)
        ->andWhere($monthConditionEnd)
        ->sum('total_amount') ?: 0;
    
    $monthCount = Purchases::find()
        ->where(['flagdel' => 0])
        ->andWhere($monthCondition)
        ->andWhere($monthConditionEnd)
        ->count();
    
    $monthlyData[] = [
        'month' => $thaiMonths[$actualMonth],
        'weight' => $monthWeight,
        'amount' => $monthAmount,
        'count' => $monthCount,
        'avg_price' => $monthWeight > 0 ? ($monthAmount / $monthWeight) : 0
    ];
}
?>

<style>
.dashboard-card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    border: none;
}

.dashboard-card:hover {
    transform: translateY(-5px);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #2c3e50;
}

.stat-label {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.bg-primary-gradient { background: linear-gradient(135deg, #3498db, #2980b9); }
.bg-success-gradient { background: linear-gradient(135deg, #2ecc71, #27ae60); }
.bg-warning-gradient { background: linear-gradient(135deg, #f39c12, #e67e22); }
.bg-danger-gradient { background: linear-gradient(135deg, #e74c3c, #c0392b); }
.bg-info-gradient { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
.bg-dark-gradient { background: linear-gradient(135deg, #34495e, #2c3e50); }

.chart-container {
    height: 300px;
    position: relative;
}
</style>

<!-- Header Stats -->
<div class="row mb-3">
    <div class="col-md-4 col-6 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-primary-gradient me-3">
                    <i class="fas fa-weight"></i>
                </div>
                <div>
                    <div class="stat-number"><?= number_format($totalWeight, 0) ?></div>
                    <div class="stat-label">น้ำหนักรวม (กก.)</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-6 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-success-gradient me-3">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div>
                    <div class="stat-number"><?= number_format($totalAmount, 0) ?></div>
                    <div class="stat-label">มูลค่ารวม (บาท)</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-6 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-warning-gradient me-3">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="stat-number"><?= number_format($avgPrice, 2) ?></div>
                    <div class="stat-label">ราคาเฉลี่ย (บาท/กก.)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 col-6 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-info-gradient me-3">
                    <i class="fas fa-list"></i>
                </div>
                <div>
                    <div class="stat-number"><?= number_format($totalRecords) ?></div>
                    <div class="stat-label">รายการทั้งหมด</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-6 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-danger-gradient me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-number"><?= number_format($totalMembers) ?></div>
                    <div class="stat-label">สมาชิกทั้งหมด</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-6 mb-3">
        <div class="card dashboard-card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="icon-box bg-dark-gradient me-3">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <div class="stat-number"><?= $currentBudgetYear ?></div>
                    <div class="stat-label">ปีงบประมาณ</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent">
                <h5><i class="fas fa-chart-bar me-2"></i>สรุปรายเดือนปีงบ <?= $currentBudgetYear ?></h5>
            </div>
            <div class="card-body">
                <canvas id="yearlyChart" class="chart-container"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent">
                <h5><i class="fas fa-chart-pie me-2"></i>สัดส่วนรายปี</h5>
            </div>
            <div class="card-body">
                <canvas id="pieChart" class="chart-container"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Yearly Summary Cards -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent">
                <h5><i class="fas fa-calendar-alt me-2"></i>สรุปข้อมูลรายปี</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <div class="icon-box bg-primary-gradient mx-auto mb-3">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <h4 class="text-primary"><?= $currentBudgetYear ?></h4>
                                <p class="mb-1 fw-bold">สรุปข้อมูลประจำปีงบ <?= $currentBudgetYear ?></p>
                                <hr>
                                <div class="row text-start">
                                    <div class="col-6">น้ำหนักรวม:</div>
                                    <div class="col-6 fw-bold"><?= number_format($yearlyData[$currentBudgetYear]['weight'], 2) ?> กก.</div>
                                    <div class="col-6">มูลค่ารวม:</div>
                                    <div class="col-6 fw-bold text-success"><?= number_format($yearlyData[$currentBudgetYear]['amount'], 2) ?> บาท</div>
                                    <div class="col-6">ราคาเฉลี่ย:</div>
                                    <div class="col-6 fw-bold text-warning"><?= number_format($yearlyData[$currentBudgetYear]['avg_price'], 2) ?> บาท/กก.</div>
                                    <div class="col-6">จำนวนรายการ:</div>
                                    <div class="col-6 fw-bold text-info"><?= number_format($yearlyData[$currentBudgetYear]['count']) ?> รายการ</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <div class="icon-box bg-success-gradient mx-auto mb-3">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <h4 class="text-success"><?= $currentBudgetYear-1 ?></h4>
                                <p class="mb-1 fw-bold">สรุปข้อมูลประจำปีงบ <?= $currentBudgetYear-1 ?></p>
                                <hr>
                                <div class="row text-start">
                                    <div class="col-6">น้ำหนักรวม:</div>
                                    <div class="col-6 fw-bold"><?= number_format($yearlyData[$currentBudgetYear-1]['weight'], 2) ?> กก.</div>
                                    <div class="col-6">มูลค่ารวม:</div>
                                    <div class="col-6 fw-bold text-success"><?= number_format($yearlyData[$currentBudgetYear-1]['amount'], 2) ?> บาท</div>
                                    <div class="col-6">ราคาเฉลี่ย:</div>
                                    <div class="col-6 fw-bold text-warning"><?= number_format($yearlyData[$currentBudgetYear-1]['avg_price'], 2) ?> บาท/กก.</div>
                                    <div class="col-6">จำนวนรายการ:</div>
                                    <div class="col-6 fw-bold text-info"><?= number_format($yearlyData[$currentBudgetYear-1]['count']) ?> รายการ</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <div class="icon-box bg-info-gradient mx-auto mb-3">
                                    <i class="fas fa-award"></i>
                                </div>
                                <h4 class="text-info"><?= $currentBudgetYear-2 ?></h4>
                                <p class="mb-1 fw-bold">สรุปข้อมูลประจำปีงบ <?= $currentBudgetYear-2 ?></p>
                                <hr>
                                <div class="row text-start">
                                    <div class="col-6">น้ำหนักรวม:</div>
                                    <div class="col-6 fw-bold"><?= number_format($yearlyData[$currentBudgetYear-2]['weight'], 2) ?> กก.</div>
                                    <div class="col-6">มูลค่ารวม:</div>
                                    <div class="col-6 fw-bold text-success"><?= number_format($yearlyData[$currentBudgetYear-2]['amount'], 2) ?> บาท</div>
                                    <div class="col-6">ราคาเฉลี่ย:</div>
                                    <div class="col-6 fw-bold text-warning"><?= number_format($yearlyData[$currentBudgetYear-2]['avg_price'], 2) ?> บาท/กก.</div>
                                    <div class="col-6">จำนวนรายการ:</div>
                                    <div class="col-6 fw-bold text-info"><?= number_format($yearlyData[$currentBudgetYear-2]['count']) ?> รายการ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<!-- 
<div class="row mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-transparent">
                <h5><i class="fas fa-bolt me-2"></i>เมนูด่วน</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-plus fa-2x mb-2"></i>
                            <span>เพิ่มข้อมูล</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-file-export fa-2x mb-2"></i>
                            <span>ส่งออกข้อมูล</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <span>รายงาน</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-cog fa-2x mb-2"></i>
                            <span>ตั้งค่า</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-search fa-2x mb-2"></i>
                            <span>ค้นหา</span>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#" class="btn btn-outline-dark w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <span>จัดการผู้ใช้</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// เตรียมข้อมูลจาก PHP
const yearlyData = <?= json_encode($yearlyData) ?>;
const monthlyData = <?= json_encode($monthlyData) ?>;

// ข้อมูลรายเดือน
const months = monthlyData.map(item => item.month);
const monthWeights = monthlyData.map(item => item.weight);
const monthAmounts = monthlyData.map(item => item.amount);

// ข้อมูลรายปี (สำหรับ pie chart)
const years = Object.keys(yearlyData);
const weights = years.map(year => yearlyData[year].weight);

// Monthly Bar Chart
const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
new Chart(yearlyCtx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'น้ำหนัก (กก.)',
            data: monthWeights,
            backgroundColor: 'rgba(52, 152, 219, 0.8)',
            borderColor: 'rgba(52, 152, 219, 1)',
            borderWidth: 1,
            yAxisID: 'y'
        }, {
            label: 'มูลค่า (บาท)',
            data: monthAmounts,
            backgroundColor: 'rgba(46, 204, 113, 0.8)',
            borderColor: 'rgba(46, 204, 113, 1)',
            borderWidth: 1,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'น้ำหนัก (กก.)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'มูลค่า (บาท)'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'สรุปข้อมูลรายเดือน ปีงบประมาณ <?= $currentBudgetYear ?>'
            }
        }
    }
});

// Pie Chart (ยังคงใช้ข้อมูลรายปี)
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: years,
        datasets: [{
            data: weights,
            backgroundColor: [
                'rgba(155, 89, 182, 0.8)',
                'rgba(52, 152, 219, 0.8)',
                'rgba(46, 204, 113, 0.8)'
            ],
            borderColor: [
                'rgba(155, 89, 182, 1)',
                'rgba(52, 152, 219, 1)',
                'rgba(46, 204, 113, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>