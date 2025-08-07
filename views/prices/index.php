<?php

use app\models\Prices;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'จัดการราคายาง';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prices-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="bi bi-plus-circle"></i> เพิ่มราคาใหม่', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="bi bi-graph-up-arrow"></i> ดูกราฟราคา', ['chart'], ['class' => 'btn btn-primary']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [ 
                'attribute' => 'date',
                'format' => ['date', 'php:d/m/Y'],
                'contentOptions' => function ($model) {
                    return ['style' => 'text-align: center;'];
                },
            ],
            [
                'attribute' => 'price',
                'format' => ['decimal', 2],
                'contentOptions' => function ($model) {
                    return ['style' => 'text-align: right;'];
                },
            ],
            
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Prices $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
