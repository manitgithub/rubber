<?php

use yii\helpers\Url;

$role = explode(",", Yii::$app->user->identity->role);
?>

<!-- footer-->
<div class="footer">
    <div class="no-gutters">
        <div class="col-auto mx-auto">
            <div class="row no-gutters justify-content-center">
                <div class="col-auto">
                    <a href="<?= Url::to(['site/index']) ?>"
                        class="btn btn-link-default <?= Yii::$app->controller->id == 'site' ? 'active' : '' ?>">
                        <i class="material-icons">home</i>
                    </a>
                </div>

                <div class="col-auto">
                    <a href="<?= Url::to(['checkin/create']) ?>"
                        class="btn btn-link-default <?= Yii::$app->controller->id == 'checkin' ? 'active' : '' ?>">
                        <i class="material-icons">person_pin</i>
                    </a>
                </div>
                <!--
                <div class="col-auto">
                    <a href="<?= Url::to(['employee/profile']) ?>" class="btn btn-link-default <?= Yii::$app->controller->id == 'employee' ? 'active' : '' ?>">
                        <i class="material-icons">account_circle</i>
                    </a>
                </div>-->
                <div class="col-auto">
                    <a href="<?= Url::to(['leave/index']) ?>"
                        class="btn btn-link-default <?= Yii::$app->controller->id == 'leave' ? 'active' : '' ?>">
                        <i class="material-icons">event_note</i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- footer ends-->