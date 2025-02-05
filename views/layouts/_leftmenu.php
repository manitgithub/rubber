<?php

use yii\helpers\Url;
?>
<div class="sidebar">
    <div class="mt-4 mb-3">
        <div class="row">
            <div class="col-auto">
                <figure class="avatar avatar-60 border-0"><img src="<?= @Yii::$app->user->identity->photoViewer ?>"
                        alt="">
            </div>
            <div class="col pl-0 align-self-center">
                <h5 class="mb-1"><?= @Yii::$app->user->identity->fullname ?></h5>
                <p class="text-mute small"><?= @Yii::$app->user->identity->position ?></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="list-group main-menu">
                <a class="list-group-item list-group-item-action <?= Yii::$app->controller->id == 'site' ? 'active' : '' ?>"
                    href="<?= Url::to(['site/index']) ?>">
                    <i class="material-icons icons-raised">home</i>หน้าแรก
                </a>
                <?php
                $role = explode(",", Yii::$app->user->identity->role);
                foreach (Yii::$app->params['menu'] as $index => $menu) {
                    if (in_array($index, $role)) {
                ?>
                        <!-- <a class="list-group-item list-group-item-action <?= Yii::$app->controller->id == $menu['controller']  ? 'active' : '' ?>" href="<?= Url::to([$menu['controller'] . '/' . $menu['action']]) ?>">
                            <i class="material-icons icons-raised"><?= $menu['icon'] ?></i><?= $menu['name'] ?>
                        </a> -->
                        <a class="list-group-item list-group-item-action"
                            href="<?= Url::to([$menu['controller'] . '/' . $menu['action']]) ?>">
                            <i class="material-icons icons-raised"><?= $menu['icon'] ?></i><?= $menu['name'] ?>
                        </a>
                <?php }
                } ?>
                <a class="list-group-item list-group-item-action" href="<?= Url::to(['site/logout']) ?>">
                    <i class="material-icons icons-raised bg-danger">power_settings_new</i>ออกจากระบบ
                </a>
            </div>
        </div>
    </div>
</div>