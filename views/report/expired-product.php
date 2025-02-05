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

$this->title = 'รายงานสินค้าใกล้หมดอายุ';
Yii::$app->view->params['callBack'] = ['sys/report'];


$model = Yii::$app->db->createCommand("SELECT item.part_name
,item.min_qty
,COALESCE(SUM(CASE WHEN header.type = 'S' THEN log.qty ELSE -log.qty END), 0) AS sum_qty
,uom.uom_name
FROM item_part AS item
LEFT JOIN audit_detail AS log ON item.id = log.part_id
LEFT JOIN audit_header AS header ON header.id = log.audit_id AND header.type IN ('S','R')
LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
WHERE item.flag_del = 0 AND COALESCE(log.flag_del, 0) = 0
GROUP BY item.id, item.part_name")
    ->queryAll();


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
                            <small>ข้อมูล ณ วันที่ <?= date('Y-m-d') ?></small>
                        </h4>
                    </div>
                    <div class="col-12 px-1">
                        <table class="table table-hover datatable">
                            <thead>
                                <tr>
                                    <th>สินค้า</th>
                                    <th width="15%">ยอดคงเหลือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($model as $item) { ?>
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col align-self-center pr-0">
                                                    <h6 class="font-weight-normal mb-0"><?= $item['part_name'] ?></h6>
                                                    <p class="text-mute small text-secondary"><?= $item['uom_name'] ?></p>
                                                </div>
                                            </div>

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