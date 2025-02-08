<?php

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
//$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, viewport-fit=cover']);
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=430px, viewport-fit=cover']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'author', 'content' => $this->params['meta_author'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/temp.202302181351/img/logo.png')]);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <title><?= Html::encode($this->title) ?> | Pickup System</title>
    <link href="<?= Url::base() ?>/manifest.json" rel="manifest">
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrapper homepage">
        <!-- header -->
        <?= Alert::widget() ?>
        <?= $content ?>

    </div>
    <?php $this->endBody() ?>
</body>

<script>
    $(document).ready(function() {
        window.print();
    });
</script>

</html>
<?php $this->endPage() ?>