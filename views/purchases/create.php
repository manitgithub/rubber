<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Purchases $model */

$this->title = 'บันทึกการซื้อน้ำยาง';
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
        </div></div>
        <div class="running-create">
    <div class="card shadow-sm rounded-0">
        <div class="card-header rounded-0">
            <h4> รายการบันทึกการซื้อน้ำยาง <?=Yii::$app->helpers->DateThai(date('Y-m-d'))?></h4>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
            <div class="alert alert-warning">
                <strong>หมายเหตุ:</strong> <br>
                1. น้ำหนักแห้ง = น้ำหนัก * เปอร์เซ็นต์ / 100 <br>
                2. ราคาต่อกิโลกรัม = ราคาน้ำยาง / น้ำหนักแห้ง
            </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-primary  text-right">
                        <h2>
                        <strong>จำนวนรายการ:</strong> <?= \app\models\Purchases::find()->where(['date' => date('Y-m-d'), 'flagdel' => 0])->count() ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-info text-right">
                        <h2>
                        <strong>น้ำหนักรวม:</strong> <?= number_format(\app\models\Purchases::find()->where(['date' => date('Y-m-d'), 'flagdel' => 0])->sum('weight'), 2) ?> 
                        </h2>
                        <h2>
                        <strong>ยอดเงิน:</strong> <?= number_format(\app\models\Purchases::find()->where(['date' => date('Y-m-d'), 'flagdel' => 0])->sum('total_amount'), 2) ?>
                        </h2>
                    </div>
                </div>
            </div>
                    <div class="table-responsive">

            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ชื่อสกุล</th>
                        <th>น้ำหนัก</th>
                        <th>เปอร์เซ็นต์</th>
                        <th>น้ำหนักแห้ง</th>
                        <th>ราคา</th>
                        <th>ยอดรวม</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $model = \app\models\Purchases::find()->where(['date' => date('Y-m-d'), 'flagdel' => 0])->orderBy(['id' => SORT_DESC])->all(); ?>
                    <?php if (empty($model)): ?>
                        <tr>
                            <td colspan="7" class="text-center">ไม่มีข้อมูล</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($model as $purchase): ?>
                        <tr>
                            <td><?= Html::encode($purchase->members->fullname) ?></td>
                            <td><?= Html::encode($purchase->weight) ?></td>
                            <td><?= Html::encode($purchase->percentage) ?></td>
                            <td><?= Html::encode($purchase->dry_weight) ?></td>
                            <td><?= Html::encode($purchase->price_per_kg) ?></td>
                            <td><?= number_format($purchase->total_amount, 2) ?></td>
                            <td class="text-center">
                                <?= Html::a('แก้ไข', ['update', 'id' => $purchase->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                <?= Html::a('ลบ', ['delete', 'id' => $purchase->id], [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
</div>

        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(Yii::$app->session->hasFlash('success')): ?>
<script>
Swal.fire({
  title: "บันทึกการซื้อน้ำยางเรียบร้อยแล้ว",
  icon: "success"
});
</script>
<?php endif; ?>

<?php if(Yii::$app->session->hasFlash('delete')): ?>

<script>
Swal.fire({
    title: "ลบรายการเรียบร้อยแล้ว",
    icon: "success"
    });
</script>
<?php endif; ?>    
</div>
</div>