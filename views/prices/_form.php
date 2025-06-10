<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Prices $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prices-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'date')->textInput(['type' => 'date', 'class' => 'form-control datepicker', 'readonly' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'price')->textInput(['maxlength' => true, 'class' => 'form-control text-right', 'onkeyup' => 'this.value = this.value.replace(/[^0-9.]/g, "");']) ?>
        </div>
    </div>




    <div class="form-group text-center">
        <?= Html::submitButton('บันทึก', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('ย้อนกลับ', ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
