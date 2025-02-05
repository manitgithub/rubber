<?php

use app\assets\AppAsset;
use app\models\Leave;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use app\models\Checkin;

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
<html lang="<?= Yii::$app->language ?>" class="blue-theme">

<head>
    <title><?= Html::encode($this->title) ?> | The Pinpoint Marketing Agency </title>
    <link href="<?= Url::base() ?>/manifest.json" rel="manifest">
    <?php $this->head() ?>
    <style type="text/css">
        .help-block {
            color: red;
        }
    </style>
</head>

<body>
    <?php $this->beginBody() ?>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?= Url::base() ?>/js/service-worker.js');
        }
    </script>
    <?php //include('_loader.php'); 
    ?>
    <?php include('_leftmenu.php'); ?>
    <a href="javascript:void(0)" class="closesidemenu"><i class="material-icons icons-raised bg-dark ">close</i></a>
    <div class="wrapper homepage">
        <!-- header -->
        <div class="header">
            <div class="row no-gutters">
                <div class="col-auto">
                    <button class="btn btn-link text-dark menu-btn">
                        <i class="material-icons">menu</i>
                        <!-- <span class="new-notification"></span> -->
                    </button>
                </div>

                <div class="col-auto">
                    <?php if (!empty($this->params['callBack'])) { ?>
                        <a href="<?= Url::to($this->params['callBack']) ?>" class="btn  btn-link text-dark"><i
                                class="material-icons">navigate_before</i></a>
                    <?php } else { ?>
                        <div style="width:54px;"></div>
                    <?php } ?>
                </div>
                <div class="col text-center">
                    <a href="<?= Url::to(['site/index']) ?>">
                        <img src="/temp.202302181351/img/logo-header.png" alt="" class="header-logo">
                    </a>
                </div>
            </div>
        </div>
        <!-- header ends -->

        <?php
        $alert = Yii::$app->session;
        if ($alert->hasFlash('approve')) {
            $this->registerJs(
                "Swal.fire({
            icon: 'success',
            title: 'อนุมัติเรียบร้อย',
            showConfirmButton: false,
            timer: 1500
          })"
            );
        }
        if ($alert->hasFlash('close')) {
            $this->registerJs(
                "Swal.fire({
            icon: 'error',
            title: 'ไม่อนุมัติเรียบร้อย',
            showConfirmButton: false,
            timer: 1500
          })"
            );
        }
        if ($alert->hasFlash('disble')) {
            $this->registerJs(
                "Swal.fire({
            icon: 'success',
            title: 'ยกเลิกเรียบร้อย',
            showConfirmButton: false,
            timer: 1500
          })"
            );
        }

        ?>

        <div class="container">
            <?= $content ?>
        </div>
        <?php include('_footer.php');
        ?>
    </div>

    <!--
    <div class="notification bg-white shadow-sm border-primary">
        <div class="row">
            <div class="col-auto align-self-center pr-0">
                <i class="material-icons text-primary md-36">fullscreen</i>
            </div>
            <div class="col">
                <h6>Viewing in Phone?</h6>
                <p class="mb-0 text-secondary">Double tap to enter into fullscreen mode for each page.</p>
            </div>
            <div class="col-auto align-self-center pl-0">
                <button class="btn btn-link closenotification"><i
                        class="material-icons text-secondary text-mute md-18 ">close</i></button>
            </div>
        </div>
    </div>-->
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>