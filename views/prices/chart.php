<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Prices[] $prices */

$this->title = 'กราฟแสดงราคายาง';

// Get price data for chart
$priceData = [];
$dateLabels = [];

// Get data from last 30 days or custom range
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$prices = \app\models\Prices::find()
    ->where(['between', 'date', $startDate, $endDate])
    ->andWhere(['!=', 'flagdel', 1])
    ->orderBy('date ASC')
    ->all();

foreach ($prices as $price) {
    $dateLabels[] = Yii::$app->helpers->DateThai($price->date, 'd/m');
    $priceData[] = (float)$price->price;
}

// Get statistics
$maxPrice = !empty($priceData) ? max($priceData) : 0;
$minPrice = !empty($priceData) ? min($priceData) : 0;
$avgPrice = !empty($priceData) ? array_sum($priceData) / count($priceData) : 0;
$currentPrice = !empty($priceData) ? end($priceData) : 0;
$priceChange = count($priceData) > 1 ? end($priceData) - $priceData[count($priceData)-2] : 0;
$priceChangePercent = count($priceData) > 1 && $priceData[count($priceData)-2] != 0 ? 
    (($priceChange / $priceData[count($priceData)-2]) * 100) : 0;

?>

<style>
.chart-header {
    background: linear-gradient(135deg, #e74c3c 0%, #f39c12 100%);
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2);
}

.chart-header h5 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    text-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.chart-header i {
    margin-right: 0.5rem;
    font-size: 1.4rem;
}

.filter-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    margin-bottom: 1.5rem;
}

.chart-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
    position: relative;
    height: 500px;
}

.stats-row {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
}

.stat-card {
    text-align: center;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.stat-card.current {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.stat-card.max {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.stat-card.min {
    background: linear-gradient(135deg, #27ae60, #229954);
    color: white;
}

.stat-card.avg {
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: white;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.9;
}

.price-change {
    font-size: 0.8rem;
    margin-top: 0.5rem;
}

.price-up {
    color: #27ae60;
}

.price-down {
    color: #e74c3c;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 0.6rem 1rem;
}

.form-control:focus {
    border-color: #e74c3c;
    box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

#priceChart {
    width: 100% !important;
    height: 400px !important;
}
</style>

<div class="chart-header">
    <h5><i class="bi bi-graph-up-arrow"></i> กราฟแสดงราคายาง</h5>
</div>

<!-- Filter Form -->
<div class="filter-card">
    <form method="get" action="<?= Url::to(['prices/chart']) ?>">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">วันที่เริ่มต้น</label>
                <input type="date" name="start_date" value="<?= $startDate ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">วันที่สิ้นสุด</label>
                <input type="date" name="end_date" value="<?= $endDate ?>" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> ค้นหา
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary w-100" onclick="setQuickFilter('7')">
                    7 วัน
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary w-100" onclick="setQuickFilter('30')">
                    30 วัน
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="stats-row">
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card current">
                <div class="stat-number"><?= number_format($currentPrice, 2) ?></div>
                <div class="stat-label">ราคาปัจจุบัน (บาท/กก.)</div>
                <?php if ($priceChange != 0): ?>
                <div class="price-change <?= $priceChange > 0 ? 'price-up' : 'price-down' ?>">
                    <i class="bi bi-<?= $priceChange > 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                    <?= number_format(abs($priceChange), 2) ?> (<?= number_format(abs($priceChangePercent), 2) ?>%)
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card max">
                <div class="stat-number"><?= number_format($maxPrice, 2) ?></div>
                <div class="stat-label">ราคาสูงสุด (บาท/กก.)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card min">
                <div class="stat-number"><?= number_format($minPrice, 2) ?></div>
                <div class="stat-label">ราคาต่ำสุด (บาท/กก.)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card avg">
                <div class="stat-number"><?= number_format($avgPrice, 2) ?></div>
                <div class="stat-label">ราคาเฉลี่ย (บาท/กก.)</div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Container -->
<div class="chart-container">
    <canvas id="priceChart"></canvas>
</div>

<!-- Recent Price Table -->
<div class="stats-row">
    <h5 class="mb-3"><i class="bi bi-table"></i> ราคายางล่าสุด</h5>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr class="table-dark">
                    <th>วันที่</th>
                    <th class="text-end">ราคา (บาท/กก.)</th>
                    <th class="text-center">การเปลี่ยนแปลง</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $recentPrices = array_slice(array_reverse($prices), 0, 10);
                $prevPrice = null;
                foreach ($recentPrices as $price): 
                    $change = $prevPrice ? $price->price - $prevPrice : 0;
                    $changePercent = $prevPrice && $prevPrice != 0 ? (($change / $prevPrice) * 100) : 0;
                ?>
                <tr>
                    <td><?= Yii::$app->helpers->DateThai($price->date) ?></td>
                    <td class="text-end"><?= number_format($price->price, 2) ?></td>
                    <td class="text-center">
                        <?php if ($change != 0): ?>
                        <span class="badge <?= $change > 0 ? 'bg-success' : 'bg-danger' ?>">
                            <i class="bi bi-<?= $change > 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                            <?= number_format(abs($change), 2) ?> (<?= number_format(abs($changePercent), 2) ?>%)
                        </span>
                        <?php else: ?>
                        <span class="badge bg-secondary">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    $prevPrice = $price->price;
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data
const chartData = {
    labels: <?= json_encode($dateLabels) ?>,
    datasets: [{
        label: 'ราคายาง (บาท/กก.)',
        data: <?= json_encode($priceData) ?>,
        borderColor: '#e74c3c',
        backgroundColor: 'rgba(231, 76, 60, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#e74c3c',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 6,
        pointHoverRadius: 8,
        pointHoverBackgroundColor: '#c0392b',
        pointHoverBorderColor: '#ffffff',
        pointHoverBorderWidth: 3
    }]
};

// Chart configuration
const config = {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: '#e74c3c',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return 'ราคา: ' + context.parsed.y.toLocaleString('th-TH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) + ' บาท/กก.';
                    }
                }
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'วันที่',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    maxTicksLimit: 10
                }
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'ราคา (บาท/กก.)',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('th-TH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) + ' ฿';
                    }
                }
            }
        },
        elements: {
            point: {
                hoverBackgroundColor: '#c0392b'
            }
        }
    }
};

// Create chart
const ctx = document.getElementById('priceChart').getContext('2d');
const priceChart = new Chart(ctx, config);

// Quick filter functions
function setQuickFilter(days) {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(endDate.getDate() - days);
    
    document.querySelector('input[name="start_date"]').value = startDate.toISOString().split('T')[0];
    document.querySelector('input[name="end_date"]').value = endDate.toISOString().split('T')[0];
    
    // Submit form
    document.querySelector('form').submit();
}

// Add animation
document.addEventListener('DOMContentLoaded', function() {
    // Animate stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>
