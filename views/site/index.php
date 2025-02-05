<?php

use app\models\AuditDepartment;
use yii\helpers\Url;
use app\models\News;

$this->title = 'ยินดีต้อนรับสู่ระบบบริหารงานบุคลากร 👋';
?>
<div class="card bg-template shadow mt-4">
    <div class="card-body">
        <div class="row">
            <div class="col-auto">
                <figure class="avatar avatar-60"><img src="<?= Yii::$app->user->identity->photoViewer ?>" alt="">
                </figure>
            </div>
            <div class="col pl-0 align-self-center">
                <h5 class="mb-1"><?= Yii::$app->user->identity->fullname ?></h5>
                <p class="text-mute small"><?= Yii::$app->user->identity->position ?>
                    <br><?= Yii::$app->user->identity->department ?>
                </p>


                </p>
            </div>
        </div>
    </div>
</div>


<div class="row  mt-2 text-center mt-4">
    <div class="col-6 col-md-3" onclick="window.location='<?= Url::to(['employee/profile']) ?>'">

        <div class="card shadow border-0 mb-3">
            <div class="card-body">

                <div class="avatar avatar-60 no-shadow border-0">
                    <div class="overlay bg-template"></div>
                    <i class="material-icons vm md-36 text-template">person</i>
                </div>
                <h5 class="mt-3 mb-0 font-weight-normal text-dark">ข้อมูลส่วนตัว</h5>

            </div>
        </div>
    </div>
    <?php
    $role = explode(",", Yii::$app->user->identity->role);
    foreach (Yii::$app->params['menu'] as $index => $menu) {
        if (in_array($index, $role)) {
    ?>
            <div class="col-6 col-md-3"
                onclick="window.location='<?= Url::to([$menu['controller'] . '/' . $menu['action']]) ?>'">

                <div class="card shadow border-0 mb-3">
                    <div class="card-body">

                        <div class="avatar avatar-60 no-shadow border-0">
                            <div class="overlay bg-template"></div>
                            <i class="material-icons vm md-36 text-template"><?= $menu['icon'] ?></i>
                        </div>
                        <h5 class="mt-3 mb-0 font-weight-normal text-dark"><?= $menu['name'] ?></h5>

                    </div>
                </div>
            </div>
    <?php }
    } ?>
</div>