<?php

/** @var yii\web\View $this */

use yii\helpers\Url;
use app\models\AuditHeader;
use app\models\AuditDepartment;
use yii\helpers\Html;

$this->title = 'รายการรออนุมัติ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

    <div class="row">
        <div class="col-12 px-0">
            <div class="card shadow-sm border-0 rounded-0">
                <div class="card-header rounded-0 p-0" style="background-color: #faedf5;">
                    <ul class="nav nav-tabs tabs-md nav-justified" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a href="<?= Url::to(['index']) ?>" class="nav-link border-primary rounded-0 active">
                                <?= $this->title ?>
                            </a>
                        </li>
                    </ul>
                </div>


                <div class="card-body">
                    <div class="row">
                        <div class="col-12 px-1">
                            <table class="table table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>เลขเอกสาร</th>
                                        <th>เอกสาร</th>
                                        <th>ผู้ดำเนินการ</th>
                                        <th>เหตุผล</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $models = AuditHeader::find()->where(['status' => 3])->orderBy(['date' => SORT_DESC])->all();
                                    foreach ($models as $model) { ?>
                                        <tr>
                                            <td class="align-middle">
                                                <?= $model->billno ?><br>
                                                <span class="text-muted">วันที่
                                                    <?= Yii::$app->helpers->DatetimeThai($model->date) ?> น.</span>
                                            </td>
                                            <td class="align-middle">
                                                <?= $model->document ?>
                                            </td>
                                            <td class="align-middle">
                                                <?= $model->userUpdate->fullname ?>
                                            </td>
                                            <td class="align-middle">
                                                <?= $model->note ?>
                                            </td>
                                            <td class="align-middle text-right">
                                                <?php if ($model->type == 'S') { ?>
                                                    <?= Html::a('<i class="fas fa-check"></i> รายละเอียด', ['goods-receipts/update', 'id' => $model->id, 'appv' => 1], ['class' => 'btn btn-success btn-sm']) ?>
                                                <?php } else if ($model->type == 'P') { ?>
                                                    <?= Html::a('<i class="fas fa-check"></i> รายละเอียด', ['purchase-orders/update', 'id' => $model->id, 'appv' => 1], ['class' => 'btn btn-success btn-sm']) ?>
                                                <?php } else if ($model->type == 'R') { ?>
                                                    <?= Html::a('<i class="fas fa-check"></i> รายละเอียด', ['requisition/update', 'id' => $model->id, 'appv' => 1], ['class' => 'btn btn-success btn-sm']) ?>
                                                <?php } ?>
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
</div>