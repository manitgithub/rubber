<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    body {
        background: linear-gradient(120deg, #a6c0fe 0%, #f68084 100%);
        min-height: 100vh;
        font-family: 'Prompt', 'Mitr', 'Kanit', sans-serif;
    }
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .login-card {
        width: 100%;
        max-width: 400px;
        border-radius: 2rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.17);
        background: rgba(255,255,255,0.92);
        padding: 2.5rem 2rem 2rem 2rem;
        animation: fadeInUp 1s;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .logo {
        width: 150px;
        margin-bottom: 1.5rem;
        border-radius: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
    }
    .form-control {
        border-radius: 1.5rem !important;
        font-size: 1.15rem;
    }
    .btn-info {
        background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
        color: #fff;
        border-radius: 2rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 16px 0 rgba(24, 90, 157, 0.1);
        transition: background 0.3s;
    }
    .btn-info:hover {
        background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
        color: #fff;
    }
    .login-card .form-group {
        margin-bottom: 1.3rem;
    }
    .bottom-button-container {
        margin-top: 2.5rem;
    }
    @media (max-width: 500px) {
        .login-card {
            padding: 2rem 0.5rem;
        }
        .logo {
            width: 90px;
        }
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
        ]); ?>
        <div class="text-center">
            <img src="/temp.202302181351/img/logo.png" alt="logo" class="logo"><br>
        </div>
        <?= $form->field($model, 'username')
            ->textInput([
                'class' => 'form-control text-center',
                'placeholder' => 'ชื่อผู้ใช้งาน',
                'autofocus' => true
            ])->label(false) ?>
        <?= $form->field($model, 'password')
            ->passwordInput([
                'class' => 'form-control text-center',
                'placeholder' => 'รหัสผ่าน'
            ])->label(false) ?>

        <div class="bottom-button-container">
            <?= Html::submitButton('เข้าสู่ระบบ', [
                'class' => 'btn btn-info btn-lg btn-block shadow', 
                'name' => 'login-button'
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
