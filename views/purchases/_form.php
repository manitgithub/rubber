<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Members;

/** @v                <!-- Price and Total -->
                <div class="col-md-6">
                    <?php 
                    $price = \app\models\Prices::find()->where(['flagdel' => 0])->orderBy(['date' => SORT_DESC])->one();
                    $label = $price ? 'ราคา (' . Yii::$app->helpers->DateThai($price->date) . ')' : 'ราคาน้ำยาง';
                    ?>
                    <label class="form-label text-primary fw-semibold mb-1">
                        <i class="fas fa-tags me-1"></i><?= $label ?>
                    </label>
                    <div class="input-group">
                        <?= $form->field($model, 'price_per_kg', ['template' => '{input}'])->textInput([
                            'type' => 'number', 
                            'step' => '0.01', 
                            'min' => 0, 
                            'id' => 'purchases-price_per_kg', 
                            'class' => 'form-control text-end',
                            'value' => $price ? $price->price : '',
                            'placeholder' => '0.00'
                        ]) ?>
                        <span class="input-group-text bg-primary text-white">บาท/กก.</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-success fw-semibold mb-1">
                        <i class="fas fa-money-bill-wave me-1"></i>ยอดรวม
                    </label>
                    <div class="input-group">
                        <?= $form->field($model, 'total_amount', ['template' => '{input}'])->textInput([
                            'readonly' => true,
                            'class' => 'form-control bg-light text-end fw-bold text-success fs-5',
                            'id' => 'purchases-total_amount'
                        ]) ?>
                        <span class="input-group-text bg-success text-white fw-bold">บาท</span>
                    </div>
                </div>View $this */
/** @var app\models\Purchases $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="purchases-form">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">
            <?php $form = ActiveForm::begin(); ?>
<?php 
function generateRunningNumberFromPurchases($runDate) {
    $date = new \DateTime($runDate);
    $yy = $date->format('y')+43; // Convert to Thai year
    $mm = $date->format('m');
    $dd = $date->format('d');

    $sql = "SELECT COUNT(*) as total FROM purchases WHERE DATE(`date`) = :runDate";
    $count = Yii::$app->db->createCommand($sql)
        ->bindValue(':runDate', $runDate)
        ->queryScalar();

    $sequence = $count + 1;
    $run = str_pad($sequence, 4, '0', STR_PAD_LEFT);
    return $yy . $mm . $dd . $run;
}

if(isset($_GET['date']) && !empty($_GET['date'])) {
    $date = $_GET['date'];
} else {
    $date = date('Y-m-d');
}
?>
            
            <div class="row g-3">
                <!-- Receipt Number and Date -->
                <div class="col-md-6">
                    <label class="form-label text-primary fw-semibold mb-1">
                        <i class="fas fa-receipt me-1"></i>เลขที่ใบเสร็จ
                    </label>
                    <?= $form->field($model, 'receipt_number', ['template' => '{input}'])->textInput([
                        'maxlength' => true, 
                        'readonly' => true, 
                        'value' => generateRunningNumberFromPurchases(date('Y-m-d')),
                        'class' => 'form-control bg-light text-center fw-bold'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-primary fw-semibold mb-1">
                        <i class="fas fa-calendar me-1"></i>วันที่
                    </label>
                    <?= $form->field($model, 'date', ['template' => '{input}'])->textInput([
                        'type' => 'date', 
                        'value' => $date, 
                        'class' => 'form-control datepicker',
                        'onchange' => 'location.href = "?date=" + this.value'
                    ]) ?>
                </div>

                <!-- Member, Weight, Percentage -->
                <div class="col-md-6">
                    <label class="form-label text-success fw-semibold mb-1">
                        <i class="fas fa-user me-1"></i>สมาชิก
                    </label>
                    <?= $form->field($model, 'member_id', ['template' => '{input}'])->dropDownList(
                        ArrayHelper::map(Members::find()->all(), 'id', 'fullname'),
                        ['prompt' => 'เลือกสมาชิก', 'class' => 'form-control select2']
                    ) ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-info fw-semibold mb-1">
                        <i class="fas fa-weight-hanging me-1"></i>น้ำหนัก (กก.)
                    </label>
                    <?= $form->field($model, 'weight', ['template' => '{input}'])->textInput([
                        'type' => 'number', 
                        'step' => '0.01', 
                        'min' => 0, 
                        'id' => 'purchases-weight',
                        'class' => 'form-control text-end',
                        'placeholder' => '0.00'
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-warning fw-semibold mb-1">
                        <i class="fas fa-percentage me-1"></i>เปอร์เซ็นต์
                    </label>
                    <?= $form->field($model, 'percentage', ['template' => '{input}'])->textInput([
                        'type' => 'number', 
                        'step' => '0.01', 
                        'min' => 0, 
                        'max' => 100, 
                        'id' => 'purchases-percentage',
                        'class' => 'form-control text-end',
                        'placeholder' => '0.00'
                    ]) ?>
                </div>

                <!-- Dry Weight -->
                <div class="col-md-4">
                    <label class="form-label text-secondary fw-semibold mb-1">
                        <i class="fas fa-balance-scale me-1"></i>น้ำหนักแห้ง (กก.)
                    </label>
                    <?= $form->field($model, 'dry_weight', ['template' => '{input}'])->textInput([
                        'readonly' => true,
                        'class' => 'form-control bg-light text-end fw-bold',
                        'id' => 'purchases-dry_weight'
                    ]) ?>
                </div>

                <!-- Price and Total -->
                <div class="col-md-4">
                    <?php 
                    $price = \app\models\Prices::find()->where(['flagdel' => 0])->orderBy(['date' => SORT_DESC])->one();
                    $label = $price ? 'ราคา (' . Yii::$app->helpers->DateThai($price->date) . ')' : 'ราคาน้ำยาง';
                    ?>
                    <label class="form-label text-primary fw-semibold mb-1">
                        <i class="fas fa-tags me-1"></i><?= $label ?>
                    </label>
                    <?= $form->field($model, 'price_per_kg', ['template' => '{input}'])->textInput([
                        'type' => 'number', 
                        'step' => '0.01', 
                        'min' => 0, 
                        'id' => 'purchases-price_per_kg', 
                        'class' => 'form-control text-end',
                        'value' => $price ? $price->price : '',
                        'placeholder' => '0.00'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-success fw-semibold mb-1">
                        <i class="fas fa-money-bill-wave me-1"></i>ยอดรวม
                    </label>
                    <?= $form->field($model, 'total_amount', ['template' => '{input}'])->textInput([
                        'readonly' => true,
                        'class' => 'form-control bg-light text-end fw-bold text-success fs-5',
                        'id' => 'purchases-total_amount'
                    ]) ?>
                </div>

                <!-- Submit Button -->
                <div class="col-12 mt-4">
                    <?= Html::submitButton('<i class="fas fa-save me-2"></i>บันทึกข้อมูล', [
                        'class' => 'btn btn-success btn-lg w-100 shadow-sm',
                        'style' => 'height: 55px; font-size: 1.1rem; border-radius: 10px;'
                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<!-- Compact Styling -->
<style>
    .purchases-form .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    
    .purchases-form .form-control {
        border: 1px solid #dee2e6;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        font-size: 0.95rem;
    }
    
    .purchases-form .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .purchases-form .input-group-text {
        font-size: 0.85rem;
        font-weight: 600;
        min-width: 70px;
        border: 1px solid #dee2e6;
    }
    
    .purchases-form .card {
        border-radius: 12px;
        border: 1px solid #e3e6f0;
    }
    
    .purchases-form .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
    }
    
    .purchases-form .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    }
    
    .purchases-form .btn-success:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
    }
    
    .purchases-form .bg-light {
        background-color: #f8f9fa !important;
        border-style: dashed !important;
    }
    
    .row.g-3 {
        row-gap: 1rem;
    }
    
    @media (max-width: 768px) {
        .purchases-form .btn-lg {
            height: 50px !important;
            font-size: 1rem !important;
        }
        
        .purchases-form .input-group-text {
            font-size: 0.8rem;
            min-width: 50px;
        }
        
        .purchases-form .card-body {
            padding: 1rem !important;
        }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function calculateDryWeight() {
        var weight = parseFloat(document.getElementById('purchases-weight').value) || 0;
        var percentage = parseFloat(document.getElementById('purchases-percentage').value) || 0;
        var dryWeight = (weight * percentage / 100).toFixed(2);
        
        var dryWeightField = document.getElementById('purchases-dry_weight');
        dryWeightField.value = dryWeight;

        var price = parseFloat(document.getElementById('purchases-price_per_kg').value) || 0;
        var total = (dryWeight * price).toFixed(2);
        
        var totalField = document.getElementById('purchases-total_amount');
        if (total > 0) {
            totalField.value = parseFloat(total).toLocaleString('th-TH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } else {
            totalField.value = '';
        }
    }

    const weightInput = document.getElementById('purchases-weight');
    const percentInput = document.getElementById('purchases-percentage');
    const priceInput = document.getElementById('purchases-price_per_kg');

    if (weightInput && percentInput && priceInput) {
        weightInput.addEventListener('input', calculateDryWeight);
        percentInput.addEventListener('input', calculateDryWeight);
        priceInput.addEventListener('input', calculateDryWeight);
        
        // Auto format on blur
        [weightInput, percentInput, priceInput].forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value && !isNaN(this.value)) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });
        });
    }
});
</script>
