<?php

use app\models\AuditDepartment;
use app\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'ผู้ใช้งาน';
Yii::$app->view->params['callBack'] = ['site/index'];

?>

<div class="row">
    <div class="col-12 px-0">
        <div class="card shadow-sm rounded-0">
            <div class="card-header rounded-0 p-0" style="background-color: #faedf5;">
                <ul class="nav nav-tabs tabs-md nav-justified" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a class="nav-link border-primary rounded-0 active">ผู้ใช้งาน</a>
                    </li>
                </ul>
            </div>
            <div class="card-header rounded-0">
                <div class="row">
                    <div class="col text-right">
                        <a href="<?= Url::to(['create']) ?>" class="btn btn-sm btn-default"><i
                                class="material-icons">add</i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 px-0">
                        <table class="table table-hover datatable">
                            <thead>
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($model as $model) { ?>
                                    <tr>
                                        <td>
                                            <a href="<?= Url::to(['update', 'id' => $model->id]) ?>">
                                                <div class="row align-items-center">
                                                    <div class="col-auto pr-0">
                                                        <div class="avatar avatar-50 no-shadow border-0">
                                                            <img src="<?= $model->photoViewer ?>" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="col align-self-center pr-0">
                                                        <h6 class="font-weight-normal mb-1"><?= $model->fullname ?></h6>
                                                        <p class="text-mute small text-secondary">
                                                            <?= $model->position ?> <?= $model->department ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <?php if ($model->status == 10) { ?>
                                                            <h6 class="text-success">ใช้งาน</h6>
                                                        <?php } else if ($model->status == 9) { ?>
                                                            <h6 class="text-danger">ไม่ใช้งาน</h6>
                                                        <?php } ?>
                                                        <!-- <div class="btn-group btn-group-sm">
                                            <a class="btn btn-sm btn-default" href="<?= Url::to(['update', 'id' => $model->id]) ?>">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a class="btn btn-sm btn-default" href="<?= Url::to(['delete', 'id' => $model->id]) ?>" data-confirm="Are you sure you want to delete this item?" data-method="post">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </div> -->
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>