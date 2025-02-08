<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\participants $model */

$this->title = 'Update Participants: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
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