<?php

use app\models\AuditDepartment;
use app\models\AuditPartner;
use app\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\assets\AppAsset;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'รายงานสรุปสินค้าคงคลังทั้งหมด ณ ปัจจุบัน';
Yii::$app->view->params['callBack'] = ['sys/report'];

// $this->registerJsFile('@web/app/current-stock.js?v=' . date('YmdHis'), ['depends' => AppAsset::className()]);
$department_id = Yii::$app->request->get('department_id');
$category_id = Yii::$app->request->get('category_id');
$date = Yii::$app->request->get('date');
if (empty($date)) {
    $date = date('Y-m-d');
}
$qureyCategory = "";

if (empty($department_id)) {
    $department_id = 0;
}
if (empty($category_id)) {
    $category_id = 0;
}
if ($category_id != 0) {
    $qureyCategory = "AND item.category_id = $category_id";
} else {
    $qureyCategory = "";
}
if (empty($_GET['typepart'])) {
    $typepart = 0;
} else {
    $typepart = $_GET['typepart'];
}
if ($typepart != 0) {
    $qureyCategory .= " AND item.typepart = $typepart";
}
$dateq = date('Y-m-d', strtotime($date . ' +1 day'));
$qureyCategory .= " AND date(header.created_at) < '$dateq'";

if ($department_id == 0) {
    $model = Yii::$app->db->createCommand("SELECT item.part_name
    ,item.id
    ,item.img
    ,item.status
    ,item.min_qty
    ,item.unit_price
    ,COALESCE(SUM(
        CASE WHEN header.type = 'S' THEN log.qty 
        WHEN header.type = 'R' THEN -log.qty 
        WHEN header.type = 'M' THEN log.qty
        ELSE 0 END)
    , 0) AS sum_qty
    ,uom.uom_name
    ,uom.id AS uom_id
    FROM item_part AS item
    LEFT JOIN audit_detail AS log ON item.id = log.part_id
    LEFT JOIN audit_header AS header ON header.id = log.audit_id
    AND header.type IN ('S','R','M') 
    AND header.status = 1 
    AND header.flag_del = 0
    LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
    WHERE item.flag_del = 0 $qureyCategory 
    AND COALESCE(log.flag_del, 0) = 0
    GROUP BY item.id, item.part_name 
    HAVING sum_qty != 0
    ")
        ->queryAll();
} else {
    $model = Yii::$app->db->createCommand("SELECT item.part_name
    ,item.id
    ,item.img
    ,item.status
    ,item.min_qty
    ,item.unit_price
    ,COALESCE(SUM(
        CASE WHEN header.type = 'S' THEN log.qty 
        WHEN header.type = 'R' THEN -log.qty 
        WHEN header.type = 'M' THEN log.qty
        WHEN header.type = 'T' AND header.department_id = $department_id AND header.department_id != header.partner_id THEN log.qty 
        WHEN header.type = 'T' AND header.partner_id = $department_id AND header.department_id != header.partner_id THEN -log.qty 
        ELSE 0 END)
    , 0) AS sum_qty
    ,uom.uom_name
    ,uom.id AS uom_id
    FROM item_part AS item
    LEFT JOIN audit_detail AS log ON item.id = log.part_id
    LEFT JOIN audit_header AS header ON header.id = log.audit_id 
    AND (
        (header.type IN ('S','T','M') AND header.department_id = $department_id) 
        OR (header.type IN ('R','T') AND header.partner_id = $department_id)
        )
    AND header.status = 1 
    AND header.flag_del = 0
    LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
    WHERE item.flag_del = 0 $qureyCategory
    AND COALESCE(log.flag_del, 0) = 0
    GROUP BY item.id, item.part_name
    HAVING sum_qty != 0
    ")
        ->queryAll();
}



?>
<div class="row">
    <div class="col-12 px-0">
        <div class="card shadow-sm border-0 rounded-0">
            <div class="card-header rounded-0 p-0" style="background-color: #faedf5;">
                <ul class="nav nav-tabs tabs-md nav-justified" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a href="<?= Url::to(['index']) ?>" class="nav-link border-primary rounded-0 active"><?= $this->title ?></a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 pt-4">

                        <h4 class="text-center">
                            <label><?= $this->title ?></label> <br>
                            <small>
                            </small>
                        </h4>
                    </div>

                    <div class="col-6">
                        <label class="form-label">ข้อมูล ณ วันที่</label>
                        <input type="text" class="form-control datepicker" name="date" value="<?= $date ?>" autocomplete="off" onchange="window.location = '<?= Url::to(['current-stock']) ?>?department_id=<?= $department_id ?>&category_id=<?= $category_id ?>&date=' + this.value;">
                    </div>
                    <div class="col-6">

                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">คลังสินค้า/สตอร์</label>
                            <select id="auditheader-department_id" class="form-control form-select" name="AuditHeader[department_id]" onchange="window.location = '<?= Url::to(['current-stock']) ?>?category_id=<?= $category_id ?>&department_id=' + this.value+ '&typepart=<?= $typepart ?>&date=<?= $date ?>';">
                                <option value="0" <?= $department_id == 0 ? 'selected' : '' ?>>คลังสินค้าทั้งหมด
                                </option>
                                <?php
                                $modelDepartment = AuditDepartment::find()->asArray()->all();
                                foreach ($modelDepartment as $department) {
                                ?>
                                    <option value="<?= $department['id'] ?>" <?= $department_id == $department['id'] ? 'selected' : '' ?>>
                                        <?= $department['department'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 field-category_id">
                            <label class="form-label" for="category_id">ประเภทสินค้า</label>
                            <select id="category_id" class="form-control form-select" name="category_id" onchange="window.location = '<?= Url::to(['current-stock']) ?>?department_id=<?= $department_id ?>&category_id=' + this.value+ '&typepart=<?= $typepart ?>&date=<?= $date ?>';">
                                <option value="0" <?= $category_id == 0 ? 'selected' : '' ?>>ทั้งหมด</option>
                                <?php $modelCategory = \app\models\ItemMinorGroup::find()->where(['flag_del' => 0])->all();
                                foreach ($modelCategory as $category) {
                                    $count = \app\models\ItemPart::find()->where(['category_id' => $category->id, 'flag_del' => 0])->count();
                                ?>
                                    <option value="<?= $category->id ?>" <?= $category_id == $category->id ? 'selected' : '' ?>><?= $category->name ?>
                                        (<?= $count ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label">ของสด/ของแห้ง</label>
                        <select class="form-control" id="typepart" name="typepart" onchange="window.location = '<?= Url::to(['current-stock']) ?>?department_id=<?= $department_id ?>&typepart=' + this.value + '&category_id=<?= $category_id ?>&date=<?= $date ?>';">
                            <option value="0" <?= $typepart == 0 ? 'selected' : '' ?>>ทั้งหมด</option>
                            <option value="1" <?= $typepart == 1 ? 'selected' : '' ?>>ของแห้ง</option>
                            <option value="2" <?= $typepart == 2 ? 'selected' : '' ?>>ของสด</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 px-1">
                        <table class="table table-hover datatable">
                            <thead>
                                <tr>
                                    <th>สินค้า</th>
                                    <th width="10%">หน่วยนับ</th>
                                    <th width="15%">ยอดคงเหลือ</th>
                                    <th width="10%">มูลค่าโดยประมาณ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($model as $item) {
                                    $img = Yii::getAlias('@web') . (empty($item['img']) ?  '/img/item.png' : '/uploads/' . $item['img']);
                                ?>
                                    <tr>
                                        <td>

                                            <a href="<?= Url::to(['current-stock-detail', 'item_id' => $item['id']]) ?>">
                                                <div class="row align-items-center">
                                                    <div class="col-auto pr-0">
                                                        <div class="avatar avatar-50 no-shadow border-0">
                                                            <img src="<?= $img ?>" alt="">
                                                        </div>
                                                    </div>

                                                    <div class="col align-self-center pr-0">
                                                        <h6 class="font-weight-normal mb-0"><?= $item['part_name'] ?>
                                                        </h6> <?php if ($item['status'] == 0) { ?>
                                                            <span class="badge badge-danger">ยกเลิกการใช้งาน</span>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="text-center">

                                            <?= $item['uom_name'] ?>
                                            <?= $item['uom_id'] == '24' ? '| ' . ($item['sum_qty'] / 1000) . ' กก.' : ''; ?>

                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($item['sum_qty'] <= 0) {
                                                echo '<span class="text-danger">' . $item['sum_qty'] . '</span>';
                                            } else if ($item['sum_qty'] <= $item['min_qty']) {
                                                echo '<span class="text-warning">' . $item['sum_qty'] . '</span>';
                                            } else {
                                                echo '<span class="text-success">' . $item['sum_qty'] . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php if ($item['sum_qty'] <= 0) {
                                                echo '0.00';
                                            } else {
                                                $total = $total + $item['sum_qty'] * $item['unit_price'];
                                                echo number_format($item['sum_qty'] * $item['unit_price'], 2, '.', ',');
                                            } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-right" colspan="3"><b>มูลค่ารวมโดยประมาณ</b></td>
                                    <td class="text-right"><b><?= number_format($total, 2, '.', ',') ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>