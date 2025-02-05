<?php

use app\models\AuditDepartment;
use app\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'ผู้ใช้งาน';
Yii::$app->view->params['callBack'] = ['site/index'];
if (isset($_GET['id']) && isset($_GET['approverleaveid'])) {
    $setapp = Employee::find()->where(['id' => $_GET['id']])->one();
    $setapp->approverleaveid = $_GET['approverleaveid'];
    $setapp->save(false);
    echo "<script>
    alert('บันทึกข้อมูลเรียบร้อย');window.location.href='" . Url::to(['employee/setboss']) . "'</script>";
}
if (isset($_GET['id']) && isset($_GET['approvercheckinid'])) {
    $setapp = Employee::find()->where(['id' => $_GET['id']])->one();
    $setapp->approvercheckinid = $_GET['approvercheckinid'];
    $setapp->save(false);
    echo "<script>
    alert('บันทึกข้อมูลเรียบร้อย');window.location.href='" . Url::to(['employee/setboss']) . "'</script>";
}

if (isset($_GET['id']) && isset($_GET['timework'])) {
    $setapp = Employee::find()->where(['id' => $_GET['id']])->one();
    $setapp->timework = $_GET['timework'];
    $setapp->save(false);
    echo "<script>
    alert('บันทึกข้อมูลเรียบร้อย');window.location.href='" . Url::to(['employee/setboss']) . "'</script>";
}


?>

<div class="row">
    <div class="col-12 px-0">
        <div class="card shadow-sm rounded-0">
            <div class="card-header bg-template rounded-0">
                <h5 class="card-title text-white">จัดการข้อมูลผู้อนุมัติ</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 px-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>รูป</th>
                                    <th>ชื่อ-สกุล</th>
                                    <th>ตำแหน่ง</th>
                                    <th>สถานะ</th>
                                    <th>ผู้อนุมัติลา</th>
                                    <th>ผู้อนุมัติเกี่ยวกับเวลา </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $r = 0;

                                foreach ($model as $model) {
                                    $r++ ?>
                                    <tr>
                                        <td><?= $model->id ?></td>
                                        <td>
                                            <div class="avatar avatar-50 no-shadow border-0">
                                                <img src="<?= $model->photoViewer ?>" alt="">
                                            </div>
                                        </td>
                                        <td>

                                            <?= $model->fullname ?>
                                        </td>
                                        <td><?= @$model->position ? $model->position : 'ยังไม่ได้ระบุตำแหน่งในสัญญา' ?><br>
                                            <?= @$model->department ? $model->department : '' ?>

                                        </td>
                                        <td> <select class="form-control" name="timework"
                                                onchange="location.href='<?= Url::to(['employee/setboss', 'id' => $model->id, 'timework' => '']) ?>' + this.value">
                                                <option value="">ยังไม่ได้กำหนด</option>
                                                <option value="1" <?= $model->timework == 1 ? 'selected' : '' ?>>
                                                    ต้องเช็คอิน</option>
                                                <option value="0" <?= $model->timework == 0 ? 'selected' : '' ?>>
                                                    ไม่ต้องเช็คอิน</option>
                                            </select>
                                        <td>
                                            <select class="form-control" name="approverleaveid" id="approverleaveid"
                                                onchange="location.href='<?= Url::to(['employee/setboss', 'id' => $model->id, 'approverleaveid' => '']) ?>' + this.value">
                                                <option value="">ยังไม่ได้กำหนด</option>
                                                <?php foreach (Employee::find()->where(['!=', 'id', $model->id])->all() as $employee) { ?>
                                                    <option value="<?= $employee->id ?>"
                                                        <?= $model->approverleaveid == $employee->id ? 'selected' : '' ?>>
                                                        <?= $employee->fullname ?></option>
                                                <?php } ?>
                                            </select>



                                        </td>
                                        <td>
                                            <select class="form-control" name="approvercheckinid" id="approvercheckinid"
                                                onchange="location.href='<?= Url::to(['employee/setboss', 'id' => $model->id, 'approvercheckinid' => '']) ?>' + this.value">
                                                <option value="">ยังไม่ได้กำหนด</option>
                                                <?php foreach (Employee::find()->where(['!=', 'id', $model->id])->all() as $employee) { ?>
                                                    <option value="<?= $employee->id ?>"
                                                        <?= $model->approvercheckinid == $employee->id ? 'selected' : '' ?>>
                                                        <?= $employee->fullname ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>

                                    </tr>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>