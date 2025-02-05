<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="row no-gutters text-center">
    <div class="col align-self-center px-3">
        <img src="/temp.202302181351/img/information-graphic1.png" alt="" class="mx-100 my-5">
        <div class="row">
            <div class="container mb-5">
                <h4><?= Html::encode($this->title) ?></h4>
                <p class="text-danger"><?= nl2br(Html::encode($message)) ?></p>
            </div>
        </div>
    </div>
</div>