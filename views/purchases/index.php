<?php

use app\models\Purchases;
use app\models\Members;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\PurchasesSearch $searchModel */

// ถ้ามีการกรองตาม member_id ให้ดึงข้อมูลสมาชิก
$member = null;
$memberId = Yii::$app->request->get('PurchasesSearch')['member_id'] ?? null;
if ($memberId) {
    $member = Members::findOne($memberId);
}

$this->title = $member ? 'ประวัติการซื้อ - ' . $member->getFullname2() : 'ประวัติการซื้อทั้งหมด';
$this->params['breadcrumbs'][] = ['label' => 'การซื้อ', 'url' => ['index']];
if ($member) {
    $this->params['breadcrumbs'][] = ['label' => 'สมาชิก', 'url' => ['/members/index']];
    $this->params['breadcrumbs'][] = ['label' => $member->getFullname2(), 'url' => ['/members/view', 'id' => $member->id]];
}
$this->params['breadcrumbs'][] = $this->title;

// คำนวณสถิติ
$totalPurchases = $dataProvider->getTotalCount();
$allData = $dataProvider->query->all();
$totalWeight = array_sum(array_column($allData, 'weight'));
$totalValue = array_sum(array_column($allData, 'total_amount'));
$avgWeight = $totalPurchases > 0 ? $totalWeight / $totalPurchases : 0;

// นับสถานะ
$paidCount = count(array_filter($allData, function($item) { return $item->status == 'PAID'; }));
$unpaidCount = count(array_filter($allData, function($item) { return $item->status == 'UNPAID'; }));
?>

<style>
.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-3px);
}

.stat-number {
    font-size: 1.8rem;
    font-weight: bold;
}

.stat-icon {
    font-size: 2rem;
    opacity: 0.8;
}

.header-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>

<div class="purchases-index">
    <!-- Header Card -->
    <div class="card header-card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><?= Html::encode($this->title) ?> 📋</h2>
                    <?php if ($member): ?>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-user me-2"></i>รหัสสมาชิก: <?= str_pad($member->memberid, 3, '0', STR_PAD_LEFT) ?>
                        </p>
                    <?php else: ?>
                        <p class="mb-0 opacity-75">รายการซื้อยางทั้งหมดในระบบ</p>
                    <?php endif; ?>
                </div>
                <div class="text-end">
                    <?= Html::a('<i class="fas fa-plus me-2"></i>เพิ่มการซื้อใหม่', ['create'], [
                        'class' => 'btn btn-light btn-lg me-2',
                        'style' => 'border-radius: 25px;'
                    ]) ?>
                    <?php if ($member): ?>
                        <?= Html::a('<i class="fas fa-user me-2"></i>ข้อมูลสมาชิก', ['/members/view', 'id' => $member->id], [
                            'class' => 'btn btn-outline-light btn-lg',
                            'style' => 'border-radius: 25px;'
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($totalPurchases) ?></div>
                        <div class="small mt-1">ครั้งที่ซื้อ</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-weight"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($totalWeight, 1) ?></div>
                        <div class="small mt-1">กิโลกรัม รวม</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-warning text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($totalValue) ?></div>
                        <div class="small mt-1">บาท รวม</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-info text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($avgWeight, 1) ?></div>
                        <div class="small mt-1">กก. เฉลี่ย/ครั้ง</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card stats-card h-100 border-success">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3 text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number text-success"><?= number_format($paidCount) ?></div>
                        <div class="small mt-1 text-muted">รายการจ่ายแล้ว (PAID)</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card stats-card h-100 border-danger">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3 text-danger">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number text-danger"><?= number_format($unpaidCount) ?></div>
                        <div class="small mt-1 text-muted">รายการยังไม่จ่าย (UNPAID)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card table-card">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>รายการซื้อยาง</h5>
                <div class="btn-group" role="group">
                    <?= Html::a('<i class="fas fa-download me-1"></i>ส่งออก Excel', ['export'], ['class' => 'btn btn-outline-success btn-sm']) ?>
                    <?= Html::a('<i class="fas fa-print me-1"></i>พิมพ์', ['print'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'headerRowOptions' => ['class' => 'table-dark'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['style' => 'width: 60px;']
                    ],
                    [
                        'attribute' => 'date',
                        'label' => 'วันที่',
                        'format' => 'raw',
                        'value' => function ($model) {
                            try {
                                if (preg_match('/^\d{4}-\d{2}-\d{2}/', $model->date)) {
                                    $date = new DateTime($model->date);
                                } else {
                                    $timestamp = strtotime($model->date);
                                    if ($timestamp !== false) {
                                        $date = new DateTime('@' . $timestamp);
                                    } else {
                                        return Html::encode($model->date);
                                    }
                                }
                                
                                $thaiMonths = [
                                    1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
                                    5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
                                    9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
                                ];
                                $day = sprintf('%02d', $date->format('j'));
                                $month = $thaiMonths[(int)$date->format('n')];
                                $year = $date->format('Y') + 543;
                                $dayOfWeek = date('w', strtotime($model->date));
                                $thaiDays = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
                                
                                return '<strong>' . $day . ' ' . $month . ' ' . $year . '</strong><br>' .
                                       '<small class="text-muted">' . $thaiDays[$dayOfWeek] . '</small>';
                            } catch (Exception $e) {
                                return Html::encode($model->date);
                            }
                        },
                        'headerOptions' => ['style' => 'width: 120px;']
                    ],
                    [
                        'attribute' => 'member_id',
                        'label' => 'สมาชิก',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->member) {
                                return '<strong>' . Html::encode($model->member->getFullname2()) . '</strong><br>' .
                                       '<small class="text-muted">รหัส: ' . str_pad($model->member->memberid, 3, '0', STR_PAD_LEFT) . '</small>';
                            }
                            return '-';
                        },
                        'visible' => !$member, // ซ่อนคอลัมน์นี้ถ้าดูประวัติสมาชิกคนเดียว
                        'headerOptions' => ['style' => 'width: 200px;']
                    ],
                    [
                        'attribute' => 'weight',
                        'label' => 'น้ำหนัก',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<span class="fw-bold text-success">' . number_format($model->weight, 2) . '</span><br>' .
                                   '<small class="text-muted">กิโลกรัม</small>';
                        },
                        'headerOptions' => ['style' => 'width: 100px;']
                    ],
                    [
                        'attribute' => 'price_per_kg',
                        'label' => 'ราคา/กก.',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<span class="fw-bold">' . number_format($model->price_per_kg, 2) . '</span><br>' .
                                   '<small class="text-muted">บาท</small>';
                        },
                        'headerOptions' => ['style' => 'width: 100px;']
                    ],
                    [
                        'attribute' => 'total_amount',
                        'label' => 'รวมเงิน',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<span class="fw-bold text-primary">' . number_format($model->total_amount, 2) . '</span><br>' .
                                   '<small class="text-muted">บาท</small>';
                        },
                        'headerOptions' => ['style' => 'width: 120px;']
                    ],
                    [
                        'attribute' => 'receipt_no',
                        'label' => 'เลขที่ใบเสร็จ',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->receipt_no)) {
                                return '<span class="badge bg-success">' . Html::encode($model->receipt_no) . '</span>';
                            }
                            return '<span class="text-muted">-</span>';
                        },
                        'headerOptions' => ['style' => 'width: 120px;']
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'สถานะการจ่าย',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->status == 'PAID') {
                                return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>จ่ายแล้ว</span>';
                            } elseif ($model->status == 'UNPAID') {
                                return '<span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i>ยังไม่จ่าย</span>';
                            } else {
                                return '<span class="badge bg-secondary"><i class="fas fa-question me-1"></i>ไม่ระบุ</span>';
                            }
                        },
                        'headerOptions' => ['style' => 'width: 120px;']
                    ],
                    [
                        'class' => ActionColumn::class,
                        'label' => 'จัดการ',
                        'template' => '{view} {update} {receipt} {print}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'class' => 'btn btn-outline-info btn-sm me-1',
                                    'title' => 'ดูรายละเอียด',
                                    'data-bs-toggle' => 'tooltip'
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'class' => 'btn btn-outline-warning btn-sm me-1',
                                    'title' => 'แก้ไข',
                                    'data-bs-toggle' => 'tooltip'
                                ]);
                            },
                            'receipt' => function ($url, $model) {
                                if (empty($model->receipt_no)) {
                                    return Html::a('<i class="fas fa-receipt"></i>', ['receipt', 'id' => $model->id], [
                                        'class' => 'btn btn-outline-success btn-sm me-1',
                                        'title' => 'ออกใบเสร็จ',
                                        'data-bs-toggle' => 'tooltip'
                                    ]);
                                }
                                return '';
                            },
                            'print' => function ($url, $model) {
                                if (!empty($model->receipt_no)) {
                                    return Html::a('<i class="fas fa-print"></i>', ['print-receipt', 'id' => $model->id], [
                                        'class' => 'btn btn-outline-secondary btn-sm',
                                        'title' => 'พิมพ์ใบเสร็จ',
                                        'data-bs-toggle' => 'tooltip',
                                        'target' => '_blank'
                                    ]);
                                }
                                return '';
                            },
                        ],
                        'urlCreator' => function ($action, Purchases $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        },
                        'headerOptions' => ['style' => 'width: 150px;']
                    ],
                ],
                'pager' => [
                    'class' => 'yii\bootstrap5\LinkPager',
                    'options' => ['class' => 'pagination justify-content-center'],
                ],
                'summary' => '<div class="text-muted mb-3">แสดง {begin}-{end} จาก {totalCount} รายการ</div>',
            ]); ?>
        </div>
    </div>
</div>

<script>
// Animation สำหรับ stats cards
document.querySelectorAll('.stats-card').forEach(function(card) {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-3px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// เปิดใช้งาน tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
