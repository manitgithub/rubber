<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\participants $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="participants-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="participant-form">
        <div class="row">
            <!-- ชื่อและข้อมูลส่วนตัว -->
            <div class="col-md-6">
                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'gender')->dropDownList(['male' => 'ชาย', 'female' => 'หญิง'], ['prompt' => 'เลือกเพศ']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'birthDate')->textInput(['type' => 'date']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'age_category')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <hr>

        <!-- ข้อมูลการแข่งขัน -->
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'bib_number')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'race')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'ticket_type')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'registration_type')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <hr>

        <!-- ข้อมูลฉุกเฉิน -->
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'health_issues')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'emergency_contact')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'emergency_contact_relationship')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'emergency_contact_phone')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <hr>

        <!-- ข้อมูลเสื้อ -->
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'shirt')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'shirt_type')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <hr>

        <!-- ข้อมูลอื่น ๆ -->
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'nationalId')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'user_code')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <hr>

        <!-- ข้อมูลระบบ -->
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'start_date')->textInput(['type' => 'date']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'picktime')->textInput(['type' => 'datetime-local']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'ticket_code')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropDownList(['1' => 'รับของแล้ว', '0' => 'ยังไม่รับของ']) ?>

            </div>
        </div>

        <div class="form-group mt-4">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>