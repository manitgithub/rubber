<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\participants $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="participants-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'participant_telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthDate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'age_category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bib_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'health_issues')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emergency_contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emergency_contact_relationship')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emergency_contact_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nationalId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shirt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shirt_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_deliver_option')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'registration_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ticket_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'race')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ticket_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'picktime')->textInput() ?>

    <?= $form->field($model, 'runningid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
