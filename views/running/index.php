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
        <?= Html::a('<i class="material-icons">add</i> เพิ่มกิจกรรม', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="jumbotron  bg-white">
        <h4 class="mb-3">Hello, Visitors and Buyers!</h4>
        <p class="lead">This is default view of framework, Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        <hr class="my-4">
        <p>Fimobile is HTML template based on Bootstrap 4.3.1 framework. This html template can be used in various
            business domains like Manufacturing, inventory, IT, administration etc. for admin panel, system development,
            web applications, even website can be created. This template also considered social pages, ecommerce pages
            and many more.</p>
        <br>
        <a class="btn btn-primary" href="#" role="button">Learn more</a>
    </div>



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


</div>