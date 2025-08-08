<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptBook */

$this->title = 'เล่มใบเสร็จ: ' . $model->book_number;
$this->params['breadcrumbs'][] = ['label' => 'จัดการเล่มใบเสร็จ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<style>
.page-header {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(111, 66, 193, 0.2);
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.action-buttons {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    text-align: center;
}

.btn {
    border-radius: 12px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    margin: 0 0.5rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    box-shadow: 0 4px 20px rgba(0, 123, 255, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 123, 255, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(40, 167, 69, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    border: none;
    color: #212529;
    box-shadow: 0 4px 20px rgba(255, 193, 7, 0.3);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(255, 193, 7, 0.4);
    color: #212529;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    box-shadow: 0 4px 20px rgba(220, 53, 69, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(220, 53, 69, 0.4);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 35px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}

.detail-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
}

.detail-view {
    margin: 0;
}

.detail-view th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #495057;
    font-weight: 700;
    padding: 1.25rem;
    border: none;
    width: 30%;
}

.detail-view td {
    padding: 1.25rem;
    border: none;
    border-bottom: 1px solid #f1f3f4;
}

.detail-view tr:last-child td {
    border-bottom: none;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.status-active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.status-inactive {
    background: #6c757d;
    color: white;
}

.status-finished {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.progress {
    height: 12px;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 0.5rem;
}

.progress-bar {
    border-radius: 10px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .action-buttons .btn {
        display: block;
        margin: 0.5rem 0;
        width: 100%;
    }
}
</style>

<div class="receipt-book-view">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-journal-bookmark me-3"></i>
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="page-subtitle">รายละเอียดเล่มใบเสร็จ</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon text-info">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-value text-info"><?= number_format($model->end_number - $model->start_number + 1) ?></div>
            <div class="stat-label">ใบเสร็จทั้งหมด</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon text-warning">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div class="stat-value text-warning"><?= number_format($model->getUsedCount()) ?></div>
            <div class="stat-label">ใช้ไปแล้ว</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon text-success">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="stat-value text-success"><?= number_format($model->getRemainingCount()) ?></div>
            <div class="stat-label">เหลืออยู่</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon text-primary">
                <i class="bi bi-percent"></i>
            </div>
            <div class="stat-value text-primary"><?= $model->getUsagePercentage() ?>%</div>
            <div class="stat-label">การใช้งาน</div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mb-4">
        <?php 
        $percentage = $model->getUsagePercentage();
        $color = $percentage < 50 ? 'success' : ($percentage < 80 ? 'warning' : 'danger');
        ?>
        <div class="d-flex justify-content-between mb-2">
            <span><strong>ความคืบหน้าการใช้งาน</strong></span>
            <span><?= $percentage ?>% (<?= number_format($model->getRemainingCount()) ?> ใบเหลือ)</span>
        </div>
        <div class="progress" style="height: 15px;">
            <div class="progress-bar bg-<?= $color ?>" style="width: <?= $percentage ?>%"></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <?php if (!$model->is_active && !$model->isFinished()): ?>
            <?= Html::a('<i class="bi bi-play-circle me-2"></i>เปิดใช้งาน', ['activate', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data-confirm' => 'คุณต้องการเปิดใช้งานเล่มนี้หรือไม่?',
                'data-method' => 'post',
            ]) ?>
        <?php endif; ?>
        
        <?= Html::a('<i class="bi bi-pencil me-2"></i>แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        
        <?php if (!$model->is_active): ?>
            <?= Html::a('<i class="bi bi-trash me-2"></i>ลบ', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => 'คุณต้องการลบเล่มนี้หรือไม่?',
                'data-method' => 'post',
            ]) ?>
        <?php endif; ?>
        
        <?= Html::a('<i class="bi bi-arrow-left me-2"></i>กลับไปรายการ', ['index'], ['class' => 'btn btn-primary']) ?>
    </div>

    <!-- Detail Information -->
    <div class="detail-container">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'detail-view table'],
            'attributes' => [
                [
                    'attribute' => 'book_number',
                    'label' => 'เลขที่เล่ม',
                    'format' => 'raw',
                    'value' => '<strong class="text-primary fs-5">' . Html::encode($model->book_number) . '</strong>',
                ],
                [
                    'label' => 'ช่วงเลขใบเสร็จ',
                    'format' => 'raw',
                    'value' => '<span class="badge bg-secondary me-2">' . number_format($model->start_number) . '</span> ถึง <span class="badge bg-secondary ms-2">' . number_format($model->end_number) . '</span>',
                ],
                [
                    'attribute' => 'current_number',
                    'label' => 'เลขปัจจุบัน',
                    'format' => 'raw',
                    'value' => '<strong class="fs-5">' . number_format($model->current_number) . '</strong>',
                ],
                [
                    'label' => 'เลขใบเสร็จถัดไป',
                    'format' => 'raw',
                    'value' => function($model) {
                        if ($model->isFinished()) {
                            return '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>เล่มหมดแล้ว</span>';
                        }
                        return '<strong class="text-success">' . $model->book_number . sprintf('%06d', $model->current_number) . '</strong>';
                    },
                ],
                [
                    'attribute' => 'is_active',
                    'label' => 'สถานะ',
                    'format' => 'raw',
                    'value' => function($model) {
                        if ($model->isFinished()) {
                            return '<span class="status-badge status-finished"><i class="bi bi-x-circle me-1"></i>หมดแล้ว</span>';
                        } elseif ($model->is_active) {
                            return '<span class="status-badge status-active"><i class="bi bi-check-circle me-1"></i>ใช้งานอยู่</span>';
                        } else {
                            return '<span class="status-badge status-inactive"><i class="bi bi-pause-circle me-1"></i>ไม่ใช้งาน</span>';
                        }
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'วันที่สร้าง',
                    'format' => 'raw',
                    'value' => '<i class="bi bi-calendar-date me-2"></i>' . Yii::$app->formatter->asDatetime($model->created_at, 'dd/MM/yyyy HH:mm'),
                ],
            ],
        ]) ?>
    </div>
</div>
