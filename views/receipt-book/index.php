<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'จัดการเล่มใบเสร็จ';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.2);
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    text-align: center;
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

.action-buttons {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.btn-create {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 12px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
    color: white;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(40, 167, 69, 0.4);
    color: white;
}

.table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
}

.table thead th {
    background: linear-gradient(135deg, #495057 0%, #343a40 100%);
    color: white;
    border: none;
    font-weight: 700;
    padding: 1.25rem 1rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border: none;
    font-weight: 500;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
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
    height: 8px;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 10px;
}

.btn-sm {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    margin: 0 0.25rem;
}

.btn-activate {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    color: white;
}

.btn-activate:hover {
    color: white;
    transform: translateY(-1px);
}

.btn-view {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
    border: none;
    color: white;
}

.btn-view:hover {
    color: white;
    transform: translateY(-1px);
}

.btn-edit {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    border: none;
    color: #212529;
}

.btn-edit:hover {
    color: #212529;
    transform: translateY(-1px);
}

.btn-delete {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    color: white;
}

.btn-delete:hover {
    color: white;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .action-buttons {
        text-align: center;
    }
}
</style>

<div class="receipt-book-index">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-journals me-3"></i>
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="page-subtitle">จัดการเล่มใบเสร็จและติดตามการใช้งาน</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid" id="stats-container">
        <div class="stat-card">
            <div class="stat-icon text-primary">
                <i class="bi bi-journal-check"></i>
            </div>
            <div class="stat-value text-primary" id="active-book">-</div>
            <div class="stat-label">เล่มที่ใช้งานอยู่</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon text-info">
                <i class="bi bi-journals"></i>
            </div>
            <div class="stat-value text-info" id="total-books">-</div>
            <div class="stat-label">เล่มทั้งหมด</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon text-success">
                <i class="bi bi-journal-plus"></i>
            </div>
            <div class="stat-value text-success" id="available-books">-</div>
            <div class="stat-label">เล่มที่พร้อมใช้</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon text-danger">
                <i class="bi bi-journal-x"></i>
            </div>
            <div class="stat-value text-danger" id="finished-books">-</div>
            <div class="stat-label">เล่มที่หมดแล้ว</div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2 text-primary"></i>
                <strong>รายการเล่มใบเสร็จ</strong>
            </h5>
            <?= Html::a('<i class="bi bi-plus-circle me-2"></i>เพิ่มเล่มใหม่', ['create'], ['class' => 'btn btn-create']) ?>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-container">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table mb-0'],
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'attribute' => 'book_number',
                    'label' => 'เลขที่เล่ม',
                    'format' => 'raw',
                    'value' => function($model) {
                        return '<strong class="text-primary">' . Html::encode($model->book_number) . '</strong>';
                    }
                ],
                [
                    'label' => 'ช่วงเลขใบเสร็จ',
                    'format' => 'raw',
                    'value' => function($model) {
                        return '<span class="badge bg-secondary">' . number_format($model->start_number) . '</span> - <span class="badge bg-secondary">' . number_format($model->end_number) . '</span>';
                    }
                ],
                [
                    'attribute' => 'current_number',
                    'label' => 'เลขปัจจุบัน',
                    'format' => 'raw',
                    'value' => function($model) {
                        return '<strong>' . number_format($model->current_number) . '</strong>';
                    }
                ],
                [
                    'label' => 'การใช้งาน',
                    'format' => 'raw',
                    'value' => function($model) {
                        $percentage = $model->getUsagePercentage();
                        $remaining = $model->getRemainingCount();
                        $color = $percentage < 50 ? 'success' : ($percentage < 80 ? 'warning' : 'danger');
                        
                        return '
                            <div class="mb-1">
                                <small><strong>' . number_format($remaining) . '</strong> ใบเหลือ (' . $percentage . '%)</small>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-' . $color . '" style="width: ' . $percentage . '%"></div>
                            </div>
                        ';
                    }
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
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'วันที่สร้าง',
                    'format' => 'raw',
                    'value' => function($model) {
                        return '<small>' . Yii::$app->formatter->asDate($model->created_at, 'dd/MM/yyyy') . '</small>';
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'จัดการ',
                    'template' => '{activate} {view} {update} {delete}',
                    'buttons' => [
                        'activate' => function ($url, $model, $key) {
                            if (!$model->is_active && !$model->isFinished()) {
                                return Html::a('<i class="bi bi-play-circle"></i>', ['activate', 'id' => $model->id], [
                                    'class' => 'btn btn-activate btn-sm',
                                    'title' => 'เปิดใช้งาน',
                                    'data-confirm' => 'คุณต้องการเปิดใช้งานเล่มนี้หรือไม่?',
                                    'data-method' => 'post',
                                ]);
                            }
                            return '';
                        },
                        'view' => function ($url, $model, $key) {
                            return Html::a('<i class="bi bi-eye"></i>', ['view', 'id' => $model->id], [
                                'class' => 'btn btn-view btn-sm',
                                'title' => 'ดูรายละเอียด',
                            ]);
                        },
                        'update' => function ($url, $model, $key) {
                            return Html::a('<i class="bi bi-pencil"></i>', ['update', 'id' => $model->id], [
                                'class' => 'btn btn-edit btn-sm',
                                'title' => 'แก้ไข',
                            ]);
                        },
                        'delete' => function ($url, $model, $key) {
                            if (!$model->is_active) {
                                return Html::a('<i class="bi bi-trash"></i>', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-delete btn-sm',
                                    'title' => 'ลบ',
                                    'data-confirm' => 'คุณต้องการลบเล่มนี้หรือไม่?',
                                    'data-method' => 'post',
                                ]);
                            }
                            return '';
                        },
                    ],
                    'contentOptions' => ['style' => 'width: 200px; text-align: center;'],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // โหลดสถิติ
    loadStats();
    
    function loadStats() {
        fetch('<?= \yii\helpers\Url::to(['receipt-book/stats']) ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-books').textContent = data.totalBooks;
                document.getElementById('available-books').textContent = data.availableBooks;
                document.getElementById('finished-books').textContent = data.finishedBooks;
                
                if (data.activeBook) {
                    document.getElementById('active-book').textContent = data.activeBook.book_number;
                } else {
                    document.getElementById('active-book').textContent = 'ไม่มี';
                }
            })
            .catch(error => {
                console.error('Error loading stats:', error);
            });
    }
    
    // เพิ่ม animation
    const cards = document.querySelectorAll('.stat-card, .action-buttons, .table-container');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
