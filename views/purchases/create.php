<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Purchases $model */

$this->title = 'บันทึกการซื้อน้ำยาง';
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $date = $_GET['date'];
} else {
    $date = date('Y-m-d');
}
?>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        <?= Html::encode($this->title) ?>
                    </h4>
                </div>
                <div class="card-body bg-light">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data List Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        รายการบันทึกการซื้อน้ำยาง <?=Yii::$app->helpers->DateThai($date) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Statistics Row -->
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3">
                            <div class="alert alert-warning border-left-warning h-100">
                                <div class="d-flex align-items-center">
                                    <div class="alert-icon me-3">
                                        <i class="fas fa-info-circle fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading mb-2"><strong>หมายเหตุ:</strong></h6>
                                        <p class="mb-1">1. น้ำหนักแห้ง = น้ำหนักน้ำยาง × เปอร์เซ็นต์ ÷ 100</p>
                                        <p class="mb-0">2. ยอดรวม = ราคายาง * น้ำหนักแห้ง</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <i class="fas fa-list-ol fa-2x me-2"></i>
                                    </div>
                                    <h6 class="text-uppercase mb-1">จำนวนรายการ</h6>
                                    <h3 class="mb-0 fw-bold"><?= \app\models\Purchases::find()->where(['date' => $date, 'flagdel' => 0])->count() ?></h3>
                                    <small class="opacity-75">รายการ</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <i class="fas fa-weight fa-2x me-2"></i>
                                    </div>
                                    <h6 class="text-uppercase mb-1">น้ำหนักรวม</h6>
                                    <h3 class="mb-0 fw-bold"><?= number_format(\app\models\Purchases::find()->where(['date' => $date, 'flagdel' => 0])->sum('weight'), 2) ?></h3>
                                    <small class="opacity-75">กิโลกรัม</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Stats Row -->
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card bg-warning text-dark h-100">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <i class="fas fa-money-bill-wave fa-2x me-2"></i>
                                    </div>
                                    <h6 class="text-uppercase mb-1">ยอดเงินรวม</h6>
                                    <h3 class="mb-0 fw-bold"><?= number_format(\app\models\Purchases::find()->where(['date' => $date, 'flagdel' => 0])->sum('total_amount'), 2) ?></h3>
                                    <small class="opacity-75">บาท</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card bg-secondary text-white h-100">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <i class="fas fa-chart-line fa-2x me-2"></i>
                                    </div>
                                    <h6 class="text-uppercase mb-1">น้ำหนักแห้งรวม</h6>
                                    <h3 class="mb-0 fw-bold"><?= number_format(\app\models\Purchases::find()->where(['date' => $date, 'flagdel' => 0])->sum('dry_weight'), 1) ?></h3>
                                    <small class="opacity-75">กิโลกรัม</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-3">
                            <div class="card bg-dark text-white h-100">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <i class="fas fa-calculator fa-2x me-2"></i>
                                    </div>
                                    <h6 class="text-uppercase mb-1">ราคาเฉลี่ย</h6>
                                    <?php 
                                    $totalWeight = \app\models\Purchases::find()->where(['date' => $date, 'flagdel' => 0])->sum('dry_weight');
                                    $totalAmount = \app\models\Purchases::find()->where(['date' => $date, 'flagdel' => 0])->sum('total_amount');
                                    $avgPrice = $totalWeight > 0 ? $totalAmount / $totalWeight : 0;
                                    ?>
                                    <h3 class="mb-0 fw-bold"><?= number_format($avgPrice, 2) ?></h3>
                                    <small class="opacity-75">บาท/กก.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Table Section -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">
                                        <i class="fas fa-user me-1"></i>
                                        ชื่อสกุล
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-weight-hanging me-1"></i>
                                        น้ำหนัก (กก.)
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-percentage me-1"></i>
                                        เปอร์เซ็นต์
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-balance-scale me-1"></i>
                                        น้ำหนักแห้ง (กก.)
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-tags me-1"></i>
                                        ราคา (บาท/กก.)
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-money-bill me-1"></i>
                                        ยอดรวม (บาท)
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-cogs me-1"></i>
                                        จัดการ
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $model = \app\models\Purchases::find()->where(['date' => $date, 'flagdel' => 0])->orderBy(['id' => SORT_DESC])->all(); ?>
                                <?php if (empty($model)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty-state">
                                                <div class="empty-icon mb-3">
                                                    <i class="fas fa-clipboard-list text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">ไม่มีข้อมูล</h6>
                                                <p class="text-muted small mb-0">ยังไม่มีการบันทึกการซื้อน้ำยางในวันที่ <?= Yii::$app->helpers->DateThai($date) ?></p>
                                                <small class="text-muted">เริ่มต้นโดยกรอกฟอร์มด้านบน</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($model as $index => $purchase): ?>
                                        <tr class="<?= $index % 2 == 0 ? 'table-light' : '' ?>">
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; font-size: 14px;">
                                                        <?= Html::encode($purchase->members->memberid) ?>
                                                    </div>
                                                    <strong><?= Html::encode($purchase->members->fullname2) ?></strong>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-info fs-6"><?= Html::encode($purchase->weight) ?></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-warning text-dark fs-6"><?= Html::encode($purchase->percentage) ?>%</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-success fs-6"><?= number_format($purchase->dry_weight, 1) ?></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="text-primary fw-bold"><?= number_format($purchase->price_per_kg, 2) ?></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="text-success fw-bold fs-5"><?= number_format($purchase->total_amount, 2) ?></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group" role="group">
                                                    <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $purchase->id, 'date' => $date], [
                                                        'class' => 'btn btn-outline-primary btn-sm',
                                                        'title' => 'แก้ไข',
                                                        'data-bs-toggle' => 'tooltip'
                                                    ]) ?>
                                                    <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $purchase->id], [
                                                        'class' => 'btn btn-outline-danger btn-sm',
                                                        'title' => 'ลบ',
                                                        'data-bs-toggle' => 'tooltip',
                                                        'data' => [
                                                            'confirm' => 'คุณแน่ใจหรือไม่ที่จะลบรายการนี้?',
                                                            'method' => 'post',
                                                        ],
                                                    ]) ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CSS for additional styling -->
<style>
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .btn-group .btn {
        margin: 0 2px;
    }
    
    .badge {
        font-size: 0.8em;
        padding: 0.5em 0.75em;
    }
    
    .empty-state {
        padding: 2rem 1rem;
    }
    
    .empty-state .empty-icon {
        margin-bottom: 1rem;
    }
    
    .empty-state h6 {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .empty-state p {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .empty-state small {
        font-size: 0.8rem;
        font-style: italic;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(Yii::$app->session->hasFlash('success')): ?>
<script>
Swal.fire({
    title: "สำเร็จ!",
    text: "บันทึกการซื้อน้ำยางเรียบร้อยแล้ว",
    icon: "success",
    confirmButtonColor: "#28a745",
    timer: 3000,
    timerProgressBar: true
});
</script>
<?php endif; ?>

<?php if(Yii::$app->session->hasFlash('delete')): ?>
<script>
Swal.fire({
    title: "สำเร็จ!",
    text: "ลบรายการเรียบร้อยแล้ว",
    icon: "success",
    confirmButtonColor: "#28a745",
    timer: 3000,
    timerProgressBar: true
});
</script>
<?php endif; ?>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>