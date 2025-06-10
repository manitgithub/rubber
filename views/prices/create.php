<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Prices $model */

$this->title = 'เพิ่มราคารายวัน';
$this->params['breadcrumbs'][] = ['label' => 'Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="running-create">
    <div class="card shadow-sm rounded-0">
        <div class="card-header rounded-0">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>

<div class="running-create">
    <div class="card shadow-sm rounded-0">
        <div class="card-header rounded-0">
            <h4> รายการบันทึกราคา</h4>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
        
                </div>
            </div>
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th style="width: 10px;">#</th>
                        <th style="width: 100px;">วันที่</th>
                        <th style="width: 100px;">ราคา</th>
                        <th style="width: 50px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $model = \app\models\Prices::find()->where(['flagdel' => 0])->orderBy(['date' => SORT_DESC])->all();
                    foreach ($model as $row) {
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= Yii::$app->helpers->DateThai($row['date']) ?></td>
                            <td><?= number_format($row['price'], 2) ?></td>
                            <td><a href="<?= \yii\helpers\Url::to(['prices/update', 'id' => $row['id']]) ?>" class="btn btn-warning btn-sm">Edit</a></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>