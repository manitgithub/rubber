<?php

use app\models\Members;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'สมาชิก';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-index">
<div class="running-create">
    <div class="card shadow-sm rounded-0">
        <div class="card-header rounded-0">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body">
            <p class="text-end">
                <?= Html::a('+ เพิ่มสมาชิก', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
    </div>
</div>

<div class="card shadow-sm rounded-0">
        <div class="card-body">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'idcard',
            'pername',
            'name',
            'surname',
            //'stdate',
            //'address:ntext',
            //'phone',
            //'email:email',
            'membertype',
            //'created_at',
            //'updated_at',
            //'share',
            //'homenum',
            //'moo',
            //'tumbon',
            //'amper',
            //'chawat',
            //'farm',
            //'work',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Members $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div></div>
