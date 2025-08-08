<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptBook */

$this->title = 'แก้ไข: ' . $model->book_number;
$this->params['breadcrumbs'][] = ['label' => 'จัดการเล่มใบเสร็จ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->book_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="receipt-book-update">

    <div class="page-header mb-4">
        <h1 class="page-title">
            <i class="bi bi-pencil-square me-3"></i>
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="page-subtitle">แก้ไขข้อมูลเล่มใบเสร็จ</p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<style>
.page-header {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #212529;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(255, 193, 7, 0.2);
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}
</style>
