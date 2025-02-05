<?php

use app\models\AuditDepartment;
use app\models\AuditPartner;
use app\models\Employee;
use app\models\ItemPart;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'รายงานความเคลื่อนไหวสินค้ารายตัว';
Yii::$app->view->params['callBack'] = ['sys/report'];

$modelItem = ItemPart::find()->where(['flag_del' => 0])->all();
$modelPartner = AuditPartner::find()->asArray()->all();
$modelDepartment = AuditDepartment::find()->asArray()->all();

function findPartner($array, $id)
{
    foreach ($array as $item) {
        if ($item['id'] == $id) {
            return 'คู่ค้า:' . $item['name'];
        }
    }
    return null;
}

function findDepartment($array, $id)
{
    foreach ($array as $item) {
        if ($item['id'] == $id) {
            return 'คลัง:' . $item['department'];
        }
    }
    return null;
}

$model = [];
$item_id = Yii::$app->request->get('item_id');
if ($item_id) {
    $itemDetail = ItemPart::findOne(['id' => $item_id]);
    $model = Yii::$app->db->createCommand("SELECT header.billno,header.id
        ,header.date
        ,header.status
        ,header.type
        ,header.updated_user
        ,header.partner_id
        ,header.department_id
        ,log.qty
        ,log.qty_uom
        ,uom.uom_name
        ,log.uom_id
        ,type.name
        FROM audit_detail AS log
        RIGHT JOIN audit_header AS header ON header.id = log.audit_id AND header.flag_del = 0
        LEFT JOIN audit_type AS type ON type.id = header.type
        LEFT JOIN item_uom AS uom ON uom.id = log.uom_id
        WHERE log.flag_del = 0
        AND log.part_id = $item_id
        order by header.date asc")
        ->queryAll();
}

?>
<div class="row">
    <div class="col-12 px-0">
        <div class="card shadow-sm border-0 rounded-0">
            <div class="card-header rounded-0 p-0" style="background-color: #faedf5;">
                <ul class="nav nav-tabs tabs-md nav-justified" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a href="<?= Url::to(['index']) ?>"
                            class="nav-link border-primary rounded-0 active"><?= $this->title ?></a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 pb-2">
                        <?php $form = ActiveForm::begin(['action' => ['report/movement-product'], 'method' => 'get']); ?>
                        <div class="row">
                            <div class="col">
                                <select class="form-control form-select select2" name="item_id">
                                    <?php foreach ($modelItem as $item) { ?>
                                    <option value="<?= $item->id ?>" <?= $item_id == $item->id ? 'selected' : '' ?>>
                                        <?= $item->part_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-auto pl-0"><button type="submit" class="btn btn-sm btn-primary"><i
                                        class="material-icons">search</i></button></div>
                            <div class="col-auto pl-0"><button type="button" class="btn btn-sm btn-outline-success"
                                    onclick="enableScan()"><i class="material-icons">center_focus_strong</i></button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <?php if ($item_id) { ?>
                    <div class="col-12 pt-4">
                        <?php include('_itemProfile.php'); ?>
                    </div>

                    <?php
                        $models = AuditDepartment::find()->asArray()->all();
                        foreach ($models as $depart) {
                            $total_depart = 0;
                            $department_id = $depart['id'];

                            //นำเข้าสินค้า เบิกสินค้า
                            $modelAudit = Yii::$app->db->createCommand("SELECT 
                                SUM(
                                    CASE WHEN header.type = 'S' THEN log.qty 
                                    WHEN header.type = 'R' THEN -log.qty 
                                    WHEN header.type = 'M' THEN log.qty
                                    WHEN header.type = 'T' AND header.department_id = $department_id AND header.department_id != header.partner_id THEN log.qty 
                                    WHEN header.type = 'T' AND header.partner_id = $department_id AND header.department_id != header.partner_id THEN -log.qty 
                                    ELSE 0 END) AS total
                                FROM audit_detail AS log
                                RIGHT JOIN audit_header AS header 
                                ON header.id = log.audit_id 
                                WHERE log.flag_del = 0
                                AND log.part_id = $item_id
                                AND (
                                    (header.type IN ('S','T','M') AND header.department_id = $department_id) 
                                    OR (header.type IN ('R','T') AND header.partner_id = $department_id)
                                    )
                                AND header.status = 1
                                AND header.flag_del = 0
                                GROUP BY log.part_id")
                                ->queryOne();
                            if ($modelAudit) {
                                $total_depart = $total_depart + $modelAudit['total'];
                            }
                            if ($total_depart > 0) {
                        ?>
                    <div class="col-6 col-md-3">
                        <div class="card shadow border-0 bg-template mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <span class="mb-2 font-weight-normal"><?= $depart['department'] ?>
                                            <p class="small">
                                                <?= $itemDetail->uom->id == '24' ? '| ' . ($total_depart / 1000) . ' กก.' : ''; ?>
                                        </span> </p>
                                    </div>
                                    <div class="col-auto text-right">
                                        <span class="font-weight-normal"><?= $total_depart ?></span>
                                        <p class="small">
                                            <?= $itemDetail->uom->uom_name ?>
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                        } ?>
                    <?php } ?>
                    <?php if (isset($_GET['item_id'])) { ?>
                    <div class="col-12 px-1">
                        <?php include('_transaction.php'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>