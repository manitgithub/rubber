<?php

use app\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'author', 'content' => $this->params['meta_author'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/temp.202302181351/img/logo.png')]);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>" class="pink-theme">

<head>
    <title><?= Html::encode($this->title) ?> | Wanderwoods🍃</title>
    <link href="/manifest.json" rel="manifest">
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/js/service-worker.js');
    }
    </script>
    <div class="wrapper homepage">
        <!-- header -->
        <div class="header">
            <div class="row no-gutters">
                <?php include('_loader.php'); ?>
                <?= $content ?>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>