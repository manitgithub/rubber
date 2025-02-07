<?php

use app\models\participants;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Participants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="participants-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Participants', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'first_name',
            'last_name',
            'email:email',
            'gender',
            //'participant_telephone',
            //'birthDate',
            //'age_category',
            //'bib_number',
            //'health_issues',
            //'emergency_contact',
            //'emergency_contact_relationship',
            //'emergency_contact_phone',
            //'province',
            //'nationalId',
            //'shirt',
            //'shirt_type',
            //'reg_deliver_option',
            //'registration_type',
            //'ticket_type',
            //'race',
            //'price',
            //'user_code',
            //'start_date',
            //'ticket_code',
            //'picktime',
            //'runningid',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, participants $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
