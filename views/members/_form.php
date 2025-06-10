<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Members $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="members-form">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'row']]); ?>
    <!-- คอลัมน์ซ้าย -->
    <div class="col-md-6">
        <div class="mb-4">
            <h5 class="text-primary">ข้อมูลสมาชิก</h5>
            <div class="row">
                <div class="col-sm-6">
            <?= $form->field($model, 'memberid')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
            <?= $form->field($model, 'idcard')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'pername')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-5">
            <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
                </div></div>
            <?= $form->field($model, 'stdate')->textInput(['type' => 'date']) ?>
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'membertype')->dropDownList([
                'สมาชิก' => 'สมาชิก',
                'ไม่เป็นสมาชิก' => 'ไม่เป็นสมาชิก',
            ], ['prompt' => '-- เลือกประเภทสมาชิก --']) ?>
        </div>
    </div>

    <!-- คอลัมน์ขวา -->
    <div class="col-md-6">
        <div class="mb-4">
            <h5 class="text-primary">ที่อยู่</h5>
            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($model, 'homenum')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($model, 'moo')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($model, 'tumbon')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'amper')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'chawat')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
                </div>
            </div>
        </div>

        <div>
            <h5 class="text-primary">ข้อมูลเพิ่มเติม</h5>
            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($model, 'share')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($model, 'farm')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($model, 'work')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-3">
        <div class="form-group text-center">
            <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success btn-lg px-5']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
