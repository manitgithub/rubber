<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\participants $model */

$this->title = 'Create Participants';
$this->params['breadcrumbs'][] = ['label' => 'Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="participants-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
