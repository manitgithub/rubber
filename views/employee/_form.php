<?php

use app\models\AuditDepartment;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = $model->isNewRecord ? 'เพิ่มผู้ใช้งานใหม่' : 'แก้ไขผู้ใช้งาน';
Yii::$app->view->params['callBack'] = ['index'];

$action = Yii::$app->controller->action->id;

?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<div class="text-center">
    <div class="figure-profile shadow my-4">
        <figure id="uploaded-image">
            <img src="<?= $model->getPhotoViewer(); ?>" alt="Uploaded Image">

        </figure>
        <div class="btn btn-dark text-white floating-btn">
            <i class="material-icons">camera_alt</i>
            <?= $form->field($model, 'img')->fileInput(['class' => 'float-file', 'onchange' => 'previewImage(this)'])->label(false) ?>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'username')->textInput(['class' => 'form-control', 'maxlength' => true, 'disabled' => $action == 'profile']) ?>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group field-signupform-password required">
            <label for="signupform-password"
                class="form-label"><?= $model->isNewRecord ? 'Password' : 'Reset Password' ?></label>
            <input type="password" id="password" class="form-control" name="password" autocomplete="new-password"
                aria-required="true" <?= $model->isNewRecord ? 'required' : '' ?>>
            <div class="invalid-feedback"></div>
        </div>
    </div>
</div>
<h6 class="subtitle">ข้อมูลทั่วไป</h6>
<div class="row">
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'fullname')->textInput(['class' => 'form-control', 'maxlength' => true]) ?>
    </div>
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'maxlength' => true, 'required' => 'required']) ?>
    </div>
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'position')->textInput(['class' => 'form-control', 'maxlength' => true, 'disabled' => $action == 'profile']) ?>
    </div>
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'department')->textInput(['class' => 'form-control', 'maxlength' => true, 'disabled' => $action == 'profile']) ?>
    </div>


    <?php if ($action != 'profile') { ?>
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'status')->dropdownList([10 => 'ใช้งาน', 9 => 'ไม่ใช้งาน'], ['class' => 'form-control', 'maxlength' => true]) ?>
        </div>
    <?php } ?>
</div>
<?php if ($action != 'profile') { ?>
    <h6 class="subtitle">สิทธิการใช้งาน</h6>

    <div class="row mt-3">
        <div class="col-12 px-0">
            <ul class="list-group list-group-flush mb-4">
                <?php
                $role = explode(",", $model->role);
                foreach (Yii::$app->params['menu'] as $index => $menu) {
                ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col">
                                <h6 class="text-dark mb-1"><?= $menu['name'] ?></h6>
                                <p class="text-secondary mb-0 small"><?= $menu['description'] ?></p>
                            </div>
                            <div class="col-2 pl-0 align-self-center text-right">
                                <div class="custom-control custom-switch">
                                    <input name="sel_<?= $index ?>" type="checkbox" class="custom-control-input"
                                        id="customSwitch<?= $index ?>" <?= in_array($index, $role) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="customSwitch<?= $index ?>"></label>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col p-1">
        <?= Html::submitButton('<i class="material-icons">save</i> บันทึก', ['class' => 'btn btn-lg btn-default text-white btn-block btn-rounded shadow']) ?>
    </div>
    <?php if (!$model->isNewRecord && $action != 'profile') { ?>
        <div class="col p-1">
            <a class="btn btn-lg btn-danger text-white btn-block btn-rounded shadow"
                href="<?= Url::to(['delete', 'id' => $model->id]) ?>" data-confirm="ยืนยัน?" data-method="post">
                <i class="material-icons">delete</i> ลบ
            </a>
        </div>
    <?php } ?>
</div>


<?php ActiveForm::end(); ?>

<script>
    function previewImage(input) {
        var preview = document.querySelector('#uploaded-image img');
        var file = document.querySelector('input[type=file]').files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
</script>