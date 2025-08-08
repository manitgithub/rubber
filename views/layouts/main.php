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

$currentController = Yii::$app->controller->id;
$currentAction = Yii::$app->controller->action->id;

$isPurchasesActive = $currentController === 'purchases ' && $currentAction === 'create';
$isMembersActive = $currentController === 'members';
$isPricesActive = $currentController === 'prices';
$isReportsActive = $currentController === 'report';
$isPaymentActive = $currentController === 'purchases' && $currentAction === 'payment';
$isBillActive = $currentController === 'purchases' && $currentAction === 'bill';
$isHomeActive = $currentController === 'site' && $currentAction === 'index';


?>
<?php $this->beginPage() ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!doctype html>
<html lang="<?= Yii::$app->language ?>" class="blue-theme">

<head>
        <?php $this->head() ?>
    <title><?= Html::encode($this->title) ?> | สหกรณ์กองทุนสวนยางฉลองน้ำขาวพัฒนา </title>
    <link href="<?= Url::base() ?>/manifest.json" rel="manifest">

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
      <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">

    <a href="javascript:void(0)" class="closesidemenu"><i class="material-icons icons-raised bg-dark ">close</i></a>
    <div class="wrapper homepage">
        <!-- header -->
           <title>ระบบรับซื้อน้ำยาง</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: 'Sarabun', sans-serif;
      margin: 0;
    }

    .navbar {
      display: flex;
      align-items: center;
      background-color: #f8f9fa;
      padding: 10px 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .navbar-logo {
      display: flex;
      align-items: center;
      font-weight: bold;
      color: #157347;
      margin-right: 30px;
    }
    .navbar-logo i {
      margin-right: 5px;
    }
    .navbar a {
      text-decoration: none;
      color: #333;
      margin-right: 20px;
      display: flex;
      align-items: center;
      transition: color 0.2s;
    }
    .navbar a:hover {
      color: #0d6efd;
    }
    .navbar a i {
      margin-right: 6px;
      color: #6c757d;
    }
    .navbar .active {
      color: #198754;
      font-weight: bold;
    }
    .navbar .user {
      margin-left: auto;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="header">
<div class="navbar">
  <div class="navbar-logo">
    <i class="fas fa-fire"></i> ระบบรับซื้อน้ำยาง 
  </div>
  <a href="<?= Url::to(['site/index']) ?>" class="<?= $isHomeActive ? 'active' : '' ?>"><i class="fas fa-home"></i> หน้าหลัก</a>
  <a href="<?= Url::to(['purchases/create']) ?>" class="<?= $isPurchasesActive ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i> รับซื้อน้ำยาง</a>
  <a href="<?= Url::to(['members/index']) ?>" class="<?= $isMembersActive ? 'active' : '' ?>"><i class="fas fa-users"></i> สมาชิก</a>
  <a href="<?= Url::to(['prices/create']) ?>" class="<?= $isPricesActive ? 'active' : '' ?>"><i class="fas fa-tags"></i> ราคาน้ำยาง</a>
  <a href="<?= Url::to(['report/index']) ?>" class="<?= $isReportsActive ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> รายงาน</a>
  <a href="<?= Url::to(['purchases/payment']) ?>" class="<?= $isPaymentActive ? 'active' : '' ?>"><i class="fas fa-list"></i> รันเลขใบเสร็จทั้งหมด</a>
  <a href="<?= Url::to(['purchases/bill']) ?>" class="<?= $isBillActive ? 'active' : '' ?>"><i class="fas fa-money-bill-wave"></i> ใบเสร็จ</a>
  <a href="<?= Url::to(['receipt-book/index']) ?>" class="<?= $currentController === 'receipt-book' ? 'active' : '' ?>"><i class="fas fa-book"></i> เล่มใบเสร็จ</a>
  <div class="user">
     <a href="<?= Url::to(['site/logout']) ?>" data-method="post"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
  </div>
</div>
        
            <div class="row no-gutters">
                <div class="col-auto">
                
                </div>

                <div class="col-auto">
                    <?php if (!empty($this->params['callBack'])) { ?>
                    <a href="<?= Url::to($this->params['callBack']) ?>" class="btn  btn-link text-dark"><i
                            class="material-icons">navigate_before</i></a>
                    <?php } else { ?>
                    <div style="width:54px;"></div>
                    <?php } ?>
                </div>
                
            </div>
        </div>
        <!-- header ends -->
<?php
        $alert = Yii::$app->session;
        if ($alert->hasFlash('success')) {
            $this->registerJs(
                "Swal.fire({
            icon: 'success',
            title: 'บันทึกเรียบร้อย',
            showConfirmButton: false,
            timer: 1500
          })"
            );
        }
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
        if ($alert->hasFlash('delete')) {
            $this->registerJs(
                "Swal.fire({
            icon: 'success',
            title: 'ลบเรียบร้อย',
            showConfirmButton: false,
            timer: 1500
          })"
            );
        }

        ?>
    
        <div class="container">
            <?= $content ?>
        </div>
        <?php // include('_footer.php');
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php $this->endBody() ?>
        

</body>

</html>
<?php $this->endPage() ?>