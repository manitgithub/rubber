<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Running $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="running-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
    <div class="row">
        <div class="col-12 col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'date')->textInput() ?>
        </div>
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'owner')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-12 col-md-12">
            <?= $form->field($model, 'detail')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'img')->fileInput() ?>
        </div>


        <div class="form-group text-center col-12">
            <?= Html::submitButton('<i class="material-icons">save</i> บันทึก', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="material-icons">cancel</i> ยกเลิก', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>