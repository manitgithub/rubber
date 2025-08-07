<?php

use yii\helpers\Url;

//Yii::$app->view->params['callBack'] = ['site/index'];

$this->title = 'รายงาน';
?>

<style>
.report-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.report-title {
    font-size: 2.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.report-subtitle {
    text-align: center;
    opacity: 0.9;
    font-size: 1.1rem;
    margin-bottom: 0;
}

.menu-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
    overflow: hidden;
    position: relative;
}

.menu-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.menu-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.menu-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.menu-link:hover {
    text-decoration: none;
    color: inherit;
}

.menu-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.menu-card:hover .menu-icon {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.menu-icon i {
    font-size: 2rem;
    color: white;
}

.menu-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    text-align: center;
    line-height: 1.4;
}

.stats-row {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.stat-item {
    text-align: center;
    padding: 1rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="report-container">
        <h1 class="report-title">📊 ระบบรายงาน</h1>
        <p class="report-subtitle">เลือกรายงานที่ต้องการดูข้อมูล</p>
    </div>

    <!-- Menu Cards -->
    <div class="row">
        <?php
        if (isset(Yii::$app->params['menuSysReport']) && is_array(Yii::$app->params['menuSysReport'])) {
            foreach (Yii::$app->params['menuSysReport'] as $index => $menu) {
        ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="menu-card">
                <a href="<?= Url::to([$menu['controller'] . '/' . $menu['action']]) ?>" class="menu-link">
                    <div class="menu-icon">
                        <i class="material-icons"><?= isset($menu['icon']) ? $menu['icon'] : 'description' ?></i>
                    </div>
                    <h5 class="menu-title"><?= isset($menu['name']) ? $menu['name'] : 'รายงาน' ?></h5>
                </a>
            </div>
        </div>
        <?php
            }
        } else {
        ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="material-icons" style="font-size: 3rem; margin-bottom: 1rem;">info</i>
                <h4>ไม่พบเมนูรายงาน</h4>
                <p>กรุณาตรวจสอบการตั้งค่าเมนูในระบบ</p>
            </div>
        </div>
        <?php
        }
        ?>
    </div>

    <!-- Quick Stats Section (Optional) -->
    <div class="stats-row">
        <div class="row">
            <div class="col-md-3 stat-item">
                <div class="stat-number" id="totalReports">-</div>
                <div class="stat-label">รายงานทั้งหมด</div>
            </div>
            <div class="col-md-3 stat-item">
                <div class="stat-number" id="todayReports">-</div>
                <div class="stat-label">รายงานวันนี้</div>
            </div>
            <div class="col-md-3 stat-item">
                <div class="stat-number" id="monthlyReports">-</div>
                <div class="stat-label">รายงานเดือนนี้</div>
            </div>
            <div class="col-md-3 stat-item">
                <div class="stat-number" id="activeUsers">-</div>
                <div class="stat-label">ผู้ใช้งานออนไลน์</div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add some animation to cards
    $('.menu-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('animate__animated animate__fadeInUp');
    });

    // Simulate loading stats (you can replace with real data)
    setTimeout(function() {
        $('#totalReports').text('<?= count(Yii::$app->params['menuSysReport'] ?? []) ?>');
        $('#todayReports').text('5');
        $('#monthlyReports').text('42');
        $('#activeUsers').text('12');
    }, 500);
});
</script>