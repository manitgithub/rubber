<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptBook */

$this->title = 'เพิ่มเล่มใบเสร็จใหม่';
$this->params['breadcrumbs'][] = ['label' => 'จัดการเล่มใบเสร็จ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-book-create">

    <div class="page-header mb-4">
        <h1 class="page-title">
            <i class="bi bi-journal-plus me-3"></i>
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="page-subtitle">เพิ่มเล่มใบเสร็จใหม่เข้าสู่ระบบ</p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<style>
.page-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(40, 167, 69, 0.2);
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
</style>
