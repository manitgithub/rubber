<?php

use app\models\AuditDepartment;
use app\models\AuditPartner;
use app\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'ประวัติการใช้งานของพนักงาน';
Yii::$app->view->params['callBack'] = ['site/index'];

$model = Employee::find()->where(['status' => 10])->orderBy(['updated_at' => SORT_DESC])->all();
?>
<h6 class="subtitle"><?= $this->title ?></h6>
<div class="row">
    <div class="col-12 px-0">
        <ul class="list-group list-group-flush border-top border-bottom">
            <?php foreach ($model as $item) { ?>
                <li class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col-auto pr-0">
                            <div class="avatar avatar-50 no-shadow border-0">
                                <img src="<?= $item->photoViewer ?>" alt="">
                            </div>
                        </div>
                        <div class="col align-self-center pr-0">
                            <h6 class="font-weight-normal mb-1"><?= $item->fullname ?></h6>
                            <p class="text-mute small text-secondary">
                                <?= $item->position ?> <?= ($item->department != 0) ? '| ' . AuditDepartment::findOne(['id' => $item->department])->department : '' ?>
                            </p>
                        </div>
                        <div class="col-auto">
                            <h6 class="text-success"><?= Yii::$app->helpers->dateCompare($item->updated_at) ?></h6>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>