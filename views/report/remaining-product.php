<?php

use app\models\AuditDepartment;
use app\models\AuditPartner;
use app\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'รายงานสินค้าใกล้หมด (น้อยกว่าขั้นต่ำ)';
Yii::$app->view->params['callBack'] = ['sys/report'];

$category_id = Yii::$app->request->get('category_id', 0);

if ($category_id != 0) {
    $qureyCategory = "AND item.category_id = $category_id";
} else {
    $qureyCategory = "";
}

$model = Yii::$app->db->createCommand("SELECT item.part_name
    ,item.id
    ,item.img
    ,item.min_qty
    ,item.unit_price
    ,COALESCE(SUM(
        CASE WHEN header.type = 'S' THEN log.qty 
        WHEN header.type = 'R' THEN -log.qty 
        WHEN header.type = 'M' THEN log.qty
        ELSE 0 END)
    , 0) AS sum_qty
    ,uom.uom_name
    FROM item_part AS item
    LEFT JOIN audit_detail AS log ON item.id = log.part_id
    LEFT JOIN audit_header AS header ON header.id = log.audit_id
    AND header.type IN ('S','R','M') 
    AND header.status = 1 
    AND header.flag_del = 0
    LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
    WHERE item.flag_del = 0 $qureyCategory
    AND COALESCE(log.flag_del, 0) = 0
    GROUP BY item.id, item.part_name")
    ->queryAll();


?>
<div class="row">
    <div class="col-12 px-0">
        <div class="card shadow-sm border-0 rounded-0">
            <div class="card-header rounded-0 p-0" style="background-color: #faedf5;">
                <ul class="nav nav-tabs tabs-md nav-justified" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a href="<?= Url::to(['index']) ?>" class="nav-link border-primary rounded-0 active">
                            <?= $this->title ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 pt-4">
                        <h4 class="text-center">
                            <label>
                                <?= $this->title ?>
                            </label> <br>
                            <small>ข้อมูล ณ วันที่
                                <?= date('Y-m-d') ?>
                            </small>
                        </h4>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 field-category_id">
                            <label class="form-label" for="category_id">ประเภทสินค้า</label>
                            <select id="category_id" class="form-control form-select" name="category_id" onchange="window.location = '<?= Url::to(['remaining-product']) ?>?category_id=' + this.value;">
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
                    <div class="col-12 px-1">
                        <table class="table table-hover datatable">
                            <thead>
                                <tr>
                                    <th>สินค้า</th>
                                    <th width="15%">หน่วย</th>
                                    <th width="15%">ขั้นต่ำ</th>
                                    <th width="15%">คงคลัง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($model as $item) {
                                    if ($item['sum_qty'] <= $item['min_qty']) {
                                        $img = Yii::getAlias('@web') . (empty($item['img']) ? '/img/item.png' : '/uploads/' . $item['img']);
                                ?>
                                        <tr>
                                            <td>
                                                <a href="<?= Url::to(['report/current-stock-detail', 'item_id' => $item['id']]) ?>" class="text-dark">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto pr-0">
                                                            <div class="avatar avatar-50 no-shadow border-0">
                                                                <img src="<?= $img ?>" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="col align-self-center pr-0">
                                                            <h6 class="font-weight-normal mb-0">
                                                                <?= $item['part_name'] ?>
                                                            </h6>

                                                        </div>
                                                    </div>
                                                </a>

                                            </td>
                                            <td>
                                                <?= $item['uom_name'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $item['min_qty'] ?>
                                            </td>
                                            <td class="text-center  <?= $item['sum_qty'] == 0 ? 'text-danger' : '' ?>">
                                                <?= $item['sum_qty'] ?>
                                            </td>
                                        </tr>
                                <?php }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>