<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Members;

/** @var yii\web\View $this */
/** @var app\models\Purchases $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="purchases-form">

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
    <div class="row">
                <div class="col-md-3">
            <?= $form->field($model, 'receipt_number')->textInput(['maxlength' => true, 'readonly' => true, 'value' => generateRunningNumberFromPurchases(date('Y-m-d'))]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'date')->textInput(['type' => 'date', 'value' => $date, 'class' => 'form-control datepicker' ,'onchange' => 'location.href = "?date=" + this.value']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'member_id')->dropDownList(
                ArrayHelper::map(Members::find()->all(), 'id', 'fullname'),
                ['prompt' => 'เลือกสมาชิก', 'class' => 'form-control select2']
            ) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'weight')->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0, 'id' => 'purchases-weight','class' => 'form-control text-right']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'percentage')->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0, 'max' => 100, 'id' => 'purchases-percentage' ,'class' => 'form-control text-right']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'dry_weight')->textInput(['readonly' => true]) ?>
        </div>

        <div class="col-md-6"> 
            <?php $price = \app\models\Prices::find()->where(['flagdel' => 0])->orderBy(['date' => SORT_DESC])->one();
            $label = $price ? 'ราคาน้ำยางล่าสุด ' . Yii::$app->helpers->DateThai($price->date) : 'ราคาน้ำยางล่าสุด';
            
            ?>
            <?= $form->field($model, 'price_per_kg')->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0, 'id' => 'purchases-price_per_kg', 'class' => 'form-control text-right', 'value' => $price->price])->label($label) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'total_amount')->textInput(['readonly' => true]) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success rounded-0 btn-block']) ?>
    </div>

    

    <?php ActiveForm::end(); ?>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    function calculateDryWeight() {
        var weight = parseFloat(document.getElementById('purchases-weight').value) || 0;
        var percentage = parseFloat(document.getElementById('purchases-percentage').value) || 0;
        var dryWeight = (weight * percentage / 100).toFixed(2);
        document.getElementById('purchases-dry_weight').value = dryWeight;

        var price = parseFloat(document.getElementById('purchases-price_per_kg').value) || 0;
        var total = (dryWeight * price).toFixed(2);
        document.getElementById('purchases-total_amount').value = total;
    }

    const weightInput = document.getElementById('purchases-weight');
    const percentInput = document.getElementById('purchases-percentage');
    const priceInput = document.getElementById('purchases-price_per_kg');

    if (weightInput && percentInput && priceInput) {
        weightInput.addEventListener('input', calculateDryWeight);
        percentInput.addEventListener('input', calculateDryWeight);
        priceInput.addEventListener('input', calculateDryWeight);
    }
});
</script>
