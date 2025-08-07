<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Receipt;

/** @var yii\web\View $this */
/** @var app\models\Members $model */

$this->title = $model->getFullname2();
$this->params['breadcrumbs'][] = ['label' => 'สมาชิก', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// คำนวณสถิติของสมาชิกคนนี้
$totalPurchases = \app\models\Purchases::find()
    ->where(['member_id' => $model->id, 'flagdel' => 0])
    ->count();

$totalWeight = \app\models\Purchases::find()
    ->where(['member_id' => $model->id, 'flagdel' => 0])
    ->sum('weight') ?: 0;

$totalValue = \app\models\Purchases::find()
    ->where(['member_id' => $model->id, 'flagdel' => 0])
    ->sum('total_amount') ?: 0;

$avgWeight = $totalPurchases > 0 ? $totalWeight / $totalPurchases : 0;

// ข้อมูลการซื้อล่าสุด
$latestPurchase = \app\models\Purchases::find()
    ->where(['member_id' => $model->id, 'flagdel' => 0])
    ->orderBy(['date' => SORT_DESC])
    ->one();

// ดึงประวัติการซื้อทั้งหมด
$purchaseHistory = \app\models\Purchases::find()
    ->where(['member_id' => $model->id, 'flagdel' => 0])
    ->orderBy(['date' => SORT_DESC])
    ->limit(10) // แสดง 10 รายการล่าสุด
    ->all();

// นับจำนวนใบเสร็จที่ออกแล้ว
$totalReceipts = \app\models\Purchases::find()
    ->where(['member_id' => $model->id, 'flagdel' => 0])
    ->count();

// นับจำนวนที่จ่ายแล้วและยังไม่จ่าย
$paidCount = \app\models\Purchases::find()
    ->where(['member_id' => $model->id, 'flagdel' => 0])
    ->andWhere(['status' => 'PAID'])
    ->count();

$unpaidCount = \app\models\Purchases::find()
    ->where(['member_id' => $model->id, 'flagdel' => 0])
    ->andWhere(['status' => 'UNPAID'])
    ->count();

// คำนวณอายุสมาชิก
$memberSince = '';
if ($model->stdate) {
    try {
        // ตรวจสอบรูปแบบวันที่ก่อน
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $model->stdate)) {
            // รูปแบบ Y-m-d ปกติ
            $startDate = new DateTime($model->stdate);
        } else {
            // รูปแบบอื่นๆ ให้ใช้ strtotime แปลงก่อน
            $timestamp = strtotime($model->stdate);
            if ($timestamp !== false) {
                $startDate = new DateTime('@' . $timestamp);
            } else {
                $startDate = null;
            }
        }
        
        if ($startDate) {
            $currentDate = new DateTime();
            $interval = $currentDate->diff($startDate);
            $memberSince = $interval->y . ' ปี ' . $interval->m . ' เดือน';
        }
    } catch (Exception $e) {
        // หากแปลงวันที่ไม่ได้ ให้ข้าม
        $memberSince = '';
    }
}
?>

<style>
.profile-card {
    border-radius: 20px;
    border: none;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-3px);
}

.info-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.member-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    margin: 0 auto 20px;
}

.info-row {
    border-bottom: 1px solid #eee;
    padding: 12px 0;
}

.info-row:last-child {
    border-bottom: none;
}

.badge-custom {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 500;
}
</style>

<div class="members-view">
    <!-- Header Profile Card -->
    <div class="card profile-card mb-4">
        <div class="card-body text-center py-5">
            <div class="member-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2 class="mb-1"><?= Html::encode($model->getFullname2()) ?></h2>
            <p class="mb-2 opacity-75">รหัสสมาชิก: <?= str_pad($model->memberid, 3, '0', STR_PAD_LEFT) ?></p>
            <?php if ($model->membertype): ?>
                <span class="badge badge-custom bg-light text-dark"><?= Html::encode($model->membertype) ?></span>
            <?php endif; ?>
            <?php if ($memberSince): ?>
                <p class="mt-3 mb-0 opacity-75">
                    <i class="fas fa-calendar-check me-2"></i>สมาชิกมาแล้ว <?= $memberSince ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($totalPurchases) ?></div>
                        <div class="small mt-2">ครั้งที่ขาย</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-weight"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($totalWeight, 1) ?></div>
                        <div class="small mt-2">กิโลกรัม รวม</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-warning text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($totalValue) ?></div>
                        <div class="small mt-2">บาท รวม</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-info text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($avgWeight, 1) ?></div>
                        <div class="small mt-2">กก. เฉลี่ย/ครั้ง</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($paidCount) ?></div>
                        <div class="small mt-2">จ่ายแล้ว (PAID)</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card stats-card h-100 bg-danger text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?= number_format($unpaidCount) ?></div>
                        <div class="small mt-2">ยังไม่จ่าย (UNPAID)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <div class="row">
        <!-- Personal Information -->
        <div class="col-md-6 mb-4">
            <div class="card info-card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว</h5>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">เลขบัตรประชาชน:</div>
                            <div class="col-8 fw-bold"><?= $model->idcard ?: '-' ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">คำนำหน้า:</div>
                            <div class="col-8"><?= Html::encode($model->pername ?: '-') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">ชื่อ:</div>
                            <div class="col-8 fw-bold"><?= Html::encode($model->name) ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">นามสกุล:</div>
                            <div class="col-8 fw-bold"><?= Html::encode($model->surname ?: '-') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">วันที่สมัคร:</div>
                            <div class="col-8">
                                <?php if ($model->stdate): ?>
                                    <?php
                                    try {
                                        // ตรวจสอบรูปแบบวันที่ก่อน
                                        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $model->stdate)) {
                                            // รูปแบบ Y-m-d ปกติ
                                            $date = new DateTime($model->stdate);
                                        } else {
                                            // รูปแบบอื่นๆ ให้ใช้ strtotime แปลงก่อน
                                            $timestamp = strtotime($model->stdate);
                                            if ($timestamp !== false) {
                                                $date = new DateTime('@' . $timestamp);
                                            } else {
                                                echo Html::encode($model->stdate); // แสดงค่าเดิมหากแปลงไม่ได้
                                                $date = null;
                                            }
                                        }
                                        
                                        if ($date) {
                                            $thaiMonths = [
                                                1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
                                                5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
                                                9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
                                            ];
                                            $day = sprintf('%02d', $date->format('j'));
                                            $month = $thaiMonths[(int)$date->format('n')];
                                            $year = $date->format('Y') + 543;
                                            echo $day . ' ' . $month . ' ' . $year;
                                        }
                                    } catch (Exception $e) {
                                        // หากแปลงวันที่ไม่ได้ ให้แสดงค่าเดิม
                                        echo Html::encode($model->stdate);
                                    }
                                    ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Address Information -->
        <div class="col-md-6 mb-4">
            <div class="card info-card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>ข้อมูลติดต่อ</h5>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">เบอร์โทรศัพท์:</div>
                            <div class="col-8 fw-bold">
                                <?php if ($model->phone): ?>
                                    <a href="tel:<?= $model->phone ?>" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i><?= Html::encode($model->phone) ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">อีเมล:</div>
                            <div class="col-8">
                                <?php if ($model->email): ?>
                                    <a href="mailto:<?= $model->email ?>" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i><?= Html::encode($model->email) ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">บ้านเลขที่:</div>
                            <div class="col-8"><?= Html::encode($model->homenum ?: '-') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">หมู่ที่:</div>
                            <div class="col-8"><?= Html::encode($model->moo ?: '-') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">ตำบล:</div>
                            <div class="col-8"><?= Html::encode($model->tumbon ?: '-') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">อำเภอ:</div>
                            <div class="col-8"><?= Html::encode($model->amper ?: '-') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">จังหวัด:</div>
                            <div class="col-8"><?= Html::encode($model->chawat ?: '-') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Farm Information -->
        <div class="col-md-6 mb-4">
            <div class="card info-card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-seedling me-2"></i>ข้อมูลสวนยาง</h5>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">จำนวนพื้นที่:</div>
                            <div class="col-8 fw-bold">
                                <?= $model->farm ? number_format($model->farm) . ' ไร่' : '-' ?>
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">งาน-วา:</div>
                            <div class="col-8"><?= Html::encode($model->work ?: '-') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-4 text-muted">จำนวนหุ้น:</div>
                            <div class="col-8 fw-bold">
                                <?= $model->share ? number_format($model->share) . ' หุ้น' : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Purchase -->
        <div class="col-md-6 mb-4">
            <div class="card info-card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>การซื้อล่าสุด</h5>
                </div>
                <div class="card-body">
                    <?php if ($latestPurchase): ?>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-4 text-muted">วันที่:</div>
                                <div class="col-8 fw-bold">
                                    <?php
                                    try {
                                        // ตรวจสอบรูปแบบวันที่ก่อน
                                        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $latestPurchase->date)) {
                                            // รูปแบบ Y-m-d ปกติ
                                            $date = new DateTime($latestPurchase->date);
                                        } else {
                                            // รูปแบบอื่นๆ ให้ใช้ strtotime แปลงก่อน
                                            $timestamp = strtotime($latestPurchase->date);
                                            if ($timestamp !== false) {
                                                $date = new DateTime('@' . $timestamp);
                                            } else {
                                                echo Html::encode($latestPurchase->date);
                                                $date = null;
                                            }
                                        }
                                        
                                        if ($date) {
                                            $thaiMonths = [
                                                1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
                                                5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
                                                9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
                                            ];
                                            $day = sprintf('%02d', $date->format('j'));
                                            $month = $thaiMonths[(int)$date->format('n')];
                                            $year = $date->format('Y') + 543;
                                            echo $day . ' ' . $month . ' ' . $year;
                                        }
                                    } catch (Exception $e) {
                                        // หากแปลงวันที่ไม่ได้ ให้แสดงค่าเดิม
                                        echo Html::encode($latestPurchase->date);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-4 text-muted">น้ำหนัก:</div>
                                <div class="col-8 fw-bold text-success">
                                    <?= number_format($latestPurchase->weight, 2) ?> กิโลกรัม
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-4 text-muted">ราคา/กก.:</div>
                                <div class="col-8 fw-bold">
                                    <?= number_format($latestPurchase->price_per_kg, 2) ?> บาท
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-4 text-muted">รวมเงิน:</div>
                                <div class="col-8 fw-bold text-primary">
                                    <?= number_format($latestPurchase->total_amount, 2) ?> บาท
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">ยังไม่มีข้อมูลการซื้อ</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase History -->
    <div class="card info-card mb-4">
        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>ประวัติการซื้อ (10 รายการล่าสุด)</h5>
                <div>
                    <!-- <?= Html::a('<i class="fas fa-list me-1"></i>ดูทั้งหมด', ['/purchases/index', 'PurchasesSearch[member_id]' => $model->id], [
                        'class' => 'btn btn-light btn-sm'
                    ]) ?> -->
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($purchaseHistory)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 100px;">วันที่</th>
                                <th style="width: 80px;">น้ำหนัก</th>
                                <th style="width: 80px;">ราคา/กก.</th>
                                <th style="width: 100px;">รวมเงิน</th>
                                <th style="width: 120px;">เล่มที่/เลขที่</th>
                                <th style="width: 100px;">สถานะการจ่าย</th>
                                <th style="width: 100px;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchaseHistory as $purchase): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?php
                                            try {
                                                if (preg_match('/^\d{4}-\d{2}-\d{2}/', $purchase->date)) {
                                                    $date = new DateTime($purchase->date);
                                                } else {
                                                    $timestamp = strtotime($purchase->date);
                                                    if ($timestamp !== false) {
                                                        $date = new DateTime('@' . $timestamp);
                                                    } else {
                                                        echo Html::encode($purchase->date);
                                                        $date = null;
                                                    }
                                                }
                                                
                                                if ($date) {
                                                    $thaiMonths = [
                                                        1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
                                                        5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
                                                        9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
                                                    ];
                                                    $day = sprintf('%02d', $date->format('j'));
                                                    $month = $thaiMonths[(int)$date->format('n')];
                                                    $year = $date->format('Y') + 543;
                                                    echo $day . ' ' . $month . ' ' . $year;
                                                }
                                            } catch (Exception $e) {
                                                echo Html::encode($purchase->date);
                                            }
                                            ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="text-success fw-bold"><?= number_format($purchase->weight, 2) ?></span>
                                        <small class="text-muted d-block">กก.</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?= number_format($purchase->price_per_kg, 2) ?></span>
                                        <small class="text-muted d-block">บาท</small>
                                    </td>
                                    <td>
                                        <span class="text-primary fw-bold"><?= number_format($purchase->total_amount, 2) ?></span>
                                        <small class="text-muted d-block">บาท</small>
                                    </td>
                                    <td>
                                        <?php if (!empty($purchase->receipt_id)): ?>
                                            <?php $receipt = Receipt::findOne($purchase->receipt_id); ?>
                                            
                                            <span class="badge bg-success"> <?= Html::encode($receipt->book_no) ?> / <?= Html::encode($receipt->running_no) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($purchase->status == 'PAID'): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>จ่ายแล้ว
                                            </span>
                                        <?php elseif ($purchase->status == 'UNPAID'): ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-circle me-1"></i>ยังไม่จ่าย
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-question me-1"></i>ไม่ระบุ
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?= Html::a('<i class="fas fa-eye"></i>', ['/purchases/view', 'id' => $purchase->id], [
                                                'class' => 'btn btn-outline-info btn-sm',
                                                'title' => 'ดูรายละเอียด',
                                                'data-bs-toggle' => 'tooltip'
                                            ]) ?>
                                            <?php if (empty($purchase->receipt_id)): ?>
                                                <?= Html::a('<i class="fas fa-receipt"></i>', ['/purchases/print-bill', 'id' => $purchase->receipt_id], [
                                                    'class' => 'btn btn-outline-success btn-sm',
                                                    'title' => 'ออกใบเสร็จ',
                                                    'data-bs-toggle' => 'tooltip'
                                                ]) ?>
                                            <?php else: ?>
                                                <?= Html::a('<i class="fas fa-print"></i>', ['/purchases/print-bill', 'id' => $purchase->receipt_id], [
                                                    'class' => 'btn btn-outline-secondary btn-sm',
                                                    'title' => 'พิมพ์ใบเสร็จ',
                                                    'data-bs-toggle' => 'tooltip',
                                                    'target' => '_blank'
                                                ]) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- สรุปยอดรวม -->
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted mb-1">รวมน้ำหนัก</h6>
                            <h5 class="text-success mb-0"><?= number_format($totalWeight, 2) ?> กก.</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted mb-1">รวมเงิน</h6>
                            <h5 class="text-primary mb-0"><?= number_format($totalValue, 2) ?> บาท</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted mb-1">จ่ายแล้ว</h6>
                            <h5 class="text-success mb-0"><?= number_format($paidCount) ?> รายการ</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted mb-1">ยังไม่จ่าย</h6>
                            <h5 class="text-danger mb-0"><?= number_format($unpaidCount) ?> รายการ</h5>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ยังไม่มีประวัติการซื้อ</h5>
                    <p class="text-muted">เริ่มต้นโดยการเพิ่มรายการซื้อแรก</p>
                    <?= Html::a('<i class="fas fa-plus me-2"></i>เพิ่มการซื้อใหม่', ['/purchases/create', 'member_id' => $model->id], [
                        'class' => 'btn btn-primary'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Animation สำหรับ stats cards
document.querySelectorAll('.stats-card').forEach(function(card) {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) scale(1.02)';
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
