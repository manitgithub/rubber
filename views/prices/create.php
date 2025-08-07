<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Prices $model */

$this->title = 'จัดการราคายาง';
$this->params['breadcrumbs'][] = ['label' => 'ราคายาง', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// คำนวณสถิติราคา
$prices = \app\models\Prices::find()->where(['flagdel' => 0])->orderBy(['date' => SORT_DESC])->all();
$latestPrice = !empty($prices) ? $prices[0] : null;
$avgPrice = \app\models\Prices::find()->where(['flagdel' => 0])->average('price') ?: 0;
$maxPrice = \app\models\Prices::find()->where(['flagdel' => 0])->max('price') ?: 0;
$minPrice = \app\models\Prices::find()->where(['flagdel' => 0])->min('price') ?: 0;
$totalRecords = count($prices);
?>

<style>
.price-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}
.price-card:hover {
    transform: translateY(-3px);
}
.price-number {
    font-size: 1.8rem;
    font-weight: bold;
}
.price-icon {
    font-size: 2rem;
    opacity: 0.8;
}
.form-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.history-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.price-trend-up {
    color: #28a745;
}
.price-trend-down {
    color: #dc3545;
}
</style>

<div class="prices-create">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><?= Html::encode($this->title) ?> 📈</h2>
            <p class="text-muted mb-0">บันทึกและติดตามราคายางรายวัน</p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-chart-line me-2"></i>ดูกราฟ', ['chart'], [
                'class' => 'btn btn-info btn-lg',
                'style' => 'border-radius: 25px;'
            ]) ?>
        </div>
    </div>

    <!-- Price Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card price-card h-100 bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="price-icon me-3">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <div class="price-number"><?= $latestPrice ? number_format($latestPrice->price, 2) : '0.00' ?></div>
                        <div class="small">ราคาล่าสุด (บาท/กก.)</div>
                        <?php if ($latestPrice): ?>
                            <small class="opacity-75"><?php
                                $date = new DateTime($latestPrice->date);
                                $thaiMonths = [
                                    1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
                                    5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
                                    9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
                                ];
                                $day = sprintf('%02d', $date->format('j'));
                                $month = $thaiMonths[(int)$date->format('n')];
                                $year = $date->format('Y') + 543;
                                echo $day . ' ' . $month . ' ' . $year;
                            ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card price-card h-100 bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="price-icon me-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="price-number"><?= number_format($avgPrice, 2) ?></div>
                        <div class="small">ราคาเฉลี่ย (บาท/กก.)</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card price-card h-100 bg-warning text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="price-icon me-3">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div>
                        <div class="price-number"><?= number_format($maxPrice, 2) ?></div>
                        <div class="small">ราคาสูงสุด (บาท/กก.)</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card price-card h-100 bg-danger text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="price-icon me-3">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div>
                        <div class="price-number"><?= number_format($minPrice, 2) ?></div>
                        <div class="small">ราคาต่ำสุด (บาท/กก.)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Price Form -->
    <div class="card form-card mb-4">
        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0"><i class="fas fa-plus me-2"></i>เพิ่มราคาใหม่</h5>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

    <!-- Price History -->
    <div class="card history-card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>ประวัติราคายาง</h5>
            <div class="btn-group" role="group">
                <?= Html::a('<i class="fas fa-download me-1"></i>ส่งออก Excel', ['export'], ['class' => 'btn btn-outline-success btn-sm']) ?>
                <?= Html::a('<i class="fas fa-print me-1"></i>พิมพ์', ['print'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="ค้นหาตามวันที่..." id="priceSearch">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-info fs-6">ทั้งหมด <?= number_format($totalRecords) ?> รายการ</span>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover datatable" id="priceTable">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="width: 150px;">วันที่</th>
                            <th style="width: 120px;">ราคา (บาท/กก.)</th>
                            <th style="width: 100px;">แนวโน้ม</th>
                            <th style="width: 120px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $previousPrice = null;
                        foreach ($prices as $row) {
                            // คำนวณแนวโน้มราคา
                            $trend = '';
                            $trendIcon = '';
                            $trendClass = '';
                            
                            if ($previousPrice !== null) {
                                if ($row->price < $previousPrice) {
                                    $trend = 'เพิ่มขึ้น';
                                    $trendIcon = '<i class="fas fa-arrow-up"></i>';
                                    $trendClass = 'price-trend-up';
                                } elseif ($row->price > $previousPrice) {
                                    $trend = 'ลดลง';
                                    $trendIcon = '<i class="fas fa-arrow-down"></i>';
                                    $trendClass = 'price-trend-down';
                                } else {
                                    $trend = 'คงที่';
                                    $trendIcon = '<i class="fas fa-minus"></i>';
                                    $trendClass = 'text-muted';
                                }
                            } else {
                                $trend = '-';
                                $trendClass = 'text-muted';
                            }
                            ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= $i ?></span></td>
                                <td>
                                    <strong><?php
                                        $date = new DateTime($row->date);
                                        $thaiMonths = [
                                            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
                                            5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
                                            9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
                                        ];
                                        $day = sprintf('%02d', $date->format('j'));
                                        $month = $thaiMonths[(int)$date->format('n')];
                                        $year = $date->format('Y') + 543;
                                        echo $day . ' ' . $month . ' ' . $year;
                                    ?></strong><br>
                                    <small class="text-muted"><?php
                                        $dayOfWeek = date('w', strtotime($row->date));
                                        $thaiDays = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
                                        echo $thaiDays[$dayOfWeek];
                                    ?></small>
                                </td>
                                <td>
                                    <span class="fs-5 fw-bold text-primary"><?= number_format($row->price, 2) ?></span>
                                    <small class="text-muted d-block">บาท</small>
                                </td>
                                <td>
                                    <?php if ($trend !== '-'): ?>
                                        <span class="<?= $trendClass ?>">
                                            <?= $trendIcon ?> <?= $trend ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $row->id], [
                                            'class' => 'btn btn-outline-info btn-sm',
                                            'title' => 'ดูรายละเอียด',
                                            'data-bs-toggle' => 'tooltip'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $row->id], [
                                            'class' => 'btn btn-outline-warning btn-sm',
                                            'title' => 'แก้ไข',
                                            'data-bs-toggle' => 'tooltip'
                                        ]) ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $previousPrice = $row->price;
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (empty($prices)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ยังไม่มีข้อมูลราคา</h5>
                    <p class="text-muted">เริ่มต้นโดยการเพิ่มราคายางรายการแรก</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// เปิดใช้งาน tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // ฟังก์ชันค้นหา
    document.getElementById('priceSearch').addEventListener('keyup', function() {
        var value = this.value.toLowerCase();
        var rows = document.querySelectorAll('#priceTable tbody tr');
        
        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
});

// Animation สำหรับ cards
document.querySelectorAll('.price-card').forEach(function(card) {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});
</script>