<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Running $model */

$this->title = 'Update Running: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Runnings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="running-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
