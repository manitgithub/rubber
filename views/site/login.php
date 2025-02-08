<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
    ]); ?>
    <div class="row no-gutters login-row">
        <div class="col align-self-center px-3 text-center">

            <img src="/temp.202302181351/img/logo.png" alt="logo" class="logo" width="400"> <br> <br> <br>
            <?= $form->field($model, 'username')->textInput(['class' => 'form-control form-control-lg text-center', 'placeholder' => 'ชื่อผู้ใช้งาน', 'autofocus' => true])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-lg text-center', 'placeholder' => 'รหัสผ่าน'])->label(false) ?>
        </div>
    </div>

    <div class="row mx-0 bottom-button-container">
        <div class="col">
            <?= Html::submitButton('เข้าสู่ระบบ', ['class' => 'btn btn-info btn-lg btn-rounded shadow btn-block', 'name' => 'login-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>