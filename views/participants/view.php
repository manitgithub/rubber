<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\participants $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="participants-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>
    <div class="row">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'nationalId',
                    'first_name',
                    'last_name',
                    'email:email',
                    'gender',
                    'participant_telephone',
                    'birthDate',
                    'age_category',
                    'bib_number',
                    'health_issues',
                    'emergency_contact',
                    'emergency_contact_relationship',
                    'emergency_contact_phone',
                    'province',


                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'shirt',
                    'shirt_type',
                    'reg_deliver_option',
                    'registration_type',
                    'ticket_type',
                    'race',
                    'price',
                    'user_code',
                    'start_date',
                    'ticket_code',
                    'picktime',
                    [
                        'attribute' => 'status',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->status == '1' ? '<font color="green">รับของแล้ว</font>' : '<font color="red">ยังไม่รับของ</font>';
                        }
                    ],
                ],
            ]) ?>
        </div>
    </div>



</div>