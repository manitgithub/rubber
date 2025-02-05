<?php

use yii\helpers\Url;

Yii::$app->view->params['callBack'] = ['site/index'];

?>

<div class="row  mt-2 text-center mt-4">
    <?php
    foreach (Yii::$app->params['menuSysReport'] as $index => $menu) {
    ?>
    <div class="col-6 col-md-4">
        <div class="card shadow border-0 mb-3">
            <div class="card-body">
                <a href="<?= Url::to([$menu['controller'] . '/' . $menu['action']]) ?>">
                    <div class="avatar avatar-60 no-shadow border-0">
                        <div class="overlay bg-template"></div>
                        <i class="material-icons vm md-36 text-template"><?= $menu['icon'] ?></i>
                    </div>
                    <h5 class="mt-3 mb-0 font-weight-normal text-dark"><?= $menu['name'] ?></h5>
                </a>
            </div>
        </div>
    </div>
    <?php
    } ?>
</div>