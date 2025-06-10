<?php

use app\models\Running;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'กิจกรรม';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="running-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-right">

    </p>
    <row class="row">

        <div class="col-md-4">
            <div class="jumbotron  bg-white">
                <a class="btn btn-success btn-block" href="/running/create"><i class="material-icons">add</i> เพิ่มกิจกรรม</a>
            </div>
        </div>

        <?php foreach ($dataProvider->getModels() as $model) { ?>
            <div class="col-md-4">
                <div class="jumbotron  bg-white">
                    <div class="text-center">
                <img src="<?= $model->PhotoViewer ?>" class="img-thumbnail" ></div>
                    <h4><?= $model->name ?>!</h4>
                        <p class="lead"><?= $model->date ?> <br>ผู้จัด: <?= $model->owner ?>
                        </p>
                        <hr class="my-4">
                        <?= $model->detail ?><br>
                        <div>
                            <a class="btn btn-primary btn-block" href="<?= Url::to(['view', 'id' => $model->id]) ?>"
                                role="button"><i class="material-icons">visibility</i> จัดการ</a>
                        </div>
                </div>
            </div>
        <?php } ?>


        <!--
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'date',
                'owner',
                'detail',
                //'flag_del',
                //'created_id',
                //'updated_id',
                //'created_at',
                //'updated_at',
                //'img',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Running $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
-->

</div>