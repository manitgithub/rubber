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

$this->title = 'รายงานการเบิกสินค้า';
Yii::$app->view->params['callBack'] = ['sys/report'];


// $this->registerJsFile('@web/app/report-product.js?v=' . date('YmdHis'), ['depends' => AppAsset::className()]);

$department_id = Yii::$app->request->get('department_id');
$startDate = Yii::$app->request->get('start-date');
$endDate = Yii::$app->request->get('end-date');
if (empty($department_id)) {
    $department_id = 0;
}
if (empty($startDate)) {
    $startDate = date('Y-m-') . "01";
}

if (empty($endDate)) {
    $days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime(date('now'))), date('Y', strtotime(date('now'))));
    $endDate = date('Y-m-') . $days;
}
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
                    <div class="col-12  col-md-4 ">
                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">คลังสินค้า/แผนก</label>
                            <select id="department_id" class="form-control form-select" name="AuditHeader[department_id]">
                                <option value="0" <?= $department_id == 0 ? 'selected' : '' ?>>คลังสินค้า/แผนกทั้งหมด
                                </option>
                                <?php
                                $modelDepartment = AuditDepartment::find()->asArray()->all();
                                foreach ($modelDepartment as $department) {
                                ?>
                                    <option value="<?= $department['id'] ?>" <?= $department_id == $department['id'] ? 'selected' : '' ?>>
                                        <?= $department['department'] ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12  col-md-4 ">
                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">วันที่เริ่มต้น</label>
                            <input type="text" id="start-date" class="form-control datepicker" name="date" value="<?= $startDate ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12  col-md-4 ">
                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">วันที่สิ้นสุด</label>
                            <input type="text" id="end-date" class="form-control datepicker" name="date" value="<?= $endDate ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-sm btn-primary" onclick="search()"><i class="material-icons">search</i> ค้นหา</button>
                    </div>
                    <script>
                        function search() {
                            var department_id = document.getElementById('department_id').value;
                            var startDate = document.getElementById('start-date').value;
                            var endDate = document.getElementById('end-date').value;
                            window.location = '<?= Url::to(['requisition-product']) ?>?department_id=' + department_id +
                                '&start-date=' + startDate + '&end-date=' + endDate;
                        }
                    </script>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 pt-4">
                        <h4 class="text-center">
                            <label>
                                <?= $this->title ?> ตั้งแต่วันที่
                                <?= $startDate ?> ถึง
                                <?= $endDate ?>
                            </label> <br>
                            <small>คลัง
                                <?= $department_id == 0 ? 'คลังสินค้าทั้งหมด' : AuditDepartment::findOne(['id' => $department_id])->department ?>
                            </small>
                        </h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 px-1 ">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th>เอกสาร/วันที่</th>
                                        <th>เบิกจาก</th>
                                        <th>ไปยัง</th>
                                        <th>รายการสินค้า</th>
                                        <th>ราคาต่อหน่วย</th>
                                        <th>จำนวน</th>
                                        <th>มูลค่าที่เบิก</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($department_id == 0) {
                                        $model = Yii::$app->db
                                            ->createCommand("SELECT header.id,header.date
                                            ,header.type
                                            ,header.billno
                                            ,header.status
                                            ,header.updated_user
                                            ,header.partner_id
                                            ,department.department
                                            ,item.part_name
                                            ,log.qty
                                            ,uom.uom_name
                                            ,item.unit_price
                                            FROM audit_detail AS log
                                            LEFT JOIN item_part AS item ON item.id = log.part_id
                                            LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
                                            LEFT JOIN audit_header AS header ON header.id = log.audit_id
                                            LEFT JOIN audit_department AS department ON department.id = header.department_id
                                            WHERE header.flag_del = 0 and log.flag_del = 0
                                            AND DATE_FORMAT(header.date, '%Y-%m-%d') >= '$startDate'
                                            AND DATE_FORMAT(header.date, '%Y-%m-%d') <= '$endDate'
                                            AND header.type = 'R'
                                            AND header.status = 1")
                                            ->queryAll();
                                    } else {
                                        $model = Yii::$app->db
                                            ->createCommand("SELECT header.id,header.date
                                            ,header.type
                                            ,header.billno
                                            ,header.status
                                            ,header.updated_user
                                            ,header.partner_id
                                            ,department.department
                                            ,item.part_name
                                            ,log.qty
                                            ,uom.uom_name
                                            ,item.unit_price
                                            FROM audit_detail AS log
                                            LEFT JOIN item_part AS item ON item.id = log.part_id
                                            LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
                                            LEFT JOIN audit_header AS header ON header.id = log.audit_id
                                            LEFT JOIN audit_department AS department ON department.id = header.department_id
                                            WHERE header.flag_del = 0 and log.flag_del = 0
                                            AND DATE_FORMAT(header.date, '%Y-%m-%d') >= '$startDate'
                                            AND DATE_FORMAT(header.date, '%Y-%m-%d') <= '$endDate'
                                            AND header.type = 'R'
                                            AND header.department_id = $department_id
                                            AND header.status = 1")
                                            ->queryAll();
                                    }
                                    $amount = 0;
                                    foreach ($model as $item) {
                                        $total = $item['unit_price'] * $item['qty'];
                                        $amount = $amount + $total;
                                    ?>
                                        <tr onclick="window.location = '<?= Url::to(['requisition/update', 'id' => $item['id']]) ?>'">
                                            <td class="align-middle">
                                                <div class="row align-items-center">
                                                    <div class="col align-self-center pr-0">
                                                        <h6 class="font-weight-normal mb-0 <?= $item['type'] == 'M' ? 'text-danger' : '' ?>">
                                                            <?= $item['date'] ?>
                                                        </h6>
                                                        <p class="small <?= $item['type'] == 'M' ? 'text-danger' : 'text-muted' ?> mb-0">
                                                            <?= $item['billno'] ?> :


                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?= @AuditDepartment::findOne(['id' => $item['partner_id']])->department ?>
                                            </td>
                                            <td>
                                                <?= $item['department'] ?>
                                            </td>
                                            <td>
                                                <?= $item['part_name'] ?> (
                                                <?= $item['uom_name'] ?>)
                                            </td>
                                            <td class="text-right">
                                                <?= number_format($item['unit_price'], 3) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= number_format($item['qty'], 0) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= number_format($total, 2) ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="6">มูลค่ารวม</th>
                                        <th class="text-right">
                                            <?= number_format($amount, 2) ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>