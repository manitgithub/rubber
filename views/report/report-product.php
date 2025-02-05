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

$this->title = 'รายงานความเคลื่อนไหวสินค้าทั้งหมด';
Yii::$app->view->params['callBack'] = ['sys/report'];


// $this->registerJsFile('@web/app/report-product.js?v=' . date('YmdHis'), ['depends' => AppAsset::className()]);

$department_id = Yii::$app->request->get('department_id');
$startDate = Yii::$app->request->get('start-date');
$endDate = Yii::$app->request->get('end-date');
if (empty ($department_id)) {
    $department_id = 0;
}
if (empty ($startDate)) {
    $startDate = date('Y-m-') . "01";
}

if (empty ($endDate)) {
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
                            <select id="department_id" class="form-control form-select"
                                name="AuditHeader[department_id]">
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
                            <input type="text" id="start-date" class="form-control datepicker" name="date"
                                value="<?= $startDate ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12  col-md-4 ">
                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">วันที่สิ้นสุด</label>
                            <input type="text" id="end-date" class="form-control datepicker" name="date"
                                value="<?= $endDate ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-sm btn-primary" onclick="search()"><i
                                class="material-icons">search</i> ค้นหา</button>
                    </div>
                    <script>
                        function search() {
                            var department_id = document.getElementById('department_id').value;
                            var startDate = document.getElementById('start-date').value;
                            var endDate = document.getElementById('end-date').value;
                            window.location = '<?= Url::to(['report-product']) ?>?department_id=' + department_id + '&start-date=' + startDate + '&end-date=' + endDate;
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
                                        <th class="align-middle text-center" rowspan="2">รายการสินค้า</th>
                                        <th class="align-middle text-center" rowspan="2">หน่วย</th>
                                        <th class="align-middle text-center" rowspan="2">ราคาต่อหน่วย</th>
                                        <th class="align-middle text-center" colspan="5">รายการดำเนินการ</th>
                                        <th class="align-middle text-center" rowspan="2">ผลรวม</th>
                                        <th class="align-middle text-center" rowspan="2">คิดเป็นมูลค่า</th>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center">รับ</th>
                                        <th class="align-middle text-center">เบิก</th>
                                        <th class="align-middle text-center">ย้ายเข้า</th>
                                        <th class="align-middle text-center">ย้ายออก</th>
                                        <th class="align-middle text-center">ปรับ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($department_id == 0) {
                                        $model = Yii::$app->db
                                            ->createCommand("SELECT item.part_name
                                        ,item.id
                                        ,item.img
                                        ,item.unit_price
                                        ,COALESCE(SUM(
                                            CASE WHEN header.type = 'S' THEN log.qty
                                            ELSE 0 END)
                                        , 0) AS import
                                        ,COALESCE(SUM(
                                            CASE WHEN header.type = 'R' THEN -log.qty
                                            ELSE 0 END)
                                        , 0) AS export
                                        ,COALESCE(SUM(
                                            CASE WHEN header.type = 'M' THEN log.qty
                                            ELSE 0 END)
                                        , 0) AS reset
                                        ,0 AS move_in
                                        ,0 AS move_out
                                        ,uom.uom_name
                                        FROM item_part AS item
                                        LEFT JOIN audit_detail AS log ON item.id = log.part_id
                                        LEFT JOIN audit_header AS header ON header.id = log.audit_id
                                        AND header.type IN ('S','R','M') 
                                        AND header.status = 1 
                                        AND header.flag_del = 0
                                        AND DATE_FORMAT(header.date, '%Y-%m-%d') >= '$startDate'
                                        AND DATE_FORMAT(header.date, '%Y-%m-%d') <= '$endDate'
                                        LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
                                        WHERE item.flag_del = 0 
                                        AND COALESCE(log.flag_del, 0) = 0
                                        GROUP BY item.id, item.part_name
                                        HAVING import != 0 
                                        OR export != 0 
                                        OR reset != 0
                                        OR move_in != 0
                                        OR move_out != 0
                                        ORDER BY item.id")
                                            ->queryAll();
                                    } else {
                                        $model = Yii::$app->db
                                            ->createCommand("SELECT item.part_name
                                        ,item.id
                                        ,item.img
                                        ,item.unit_price
                                        ,COALESCE(SUM(
                                            CASE WHEN header.type = 'S' THEN log.qty
                                            ELSE 0 END)
                                        , 0) AS import
                                        ,COALESCE(SUM(
                                            CASE WHEN header.type = 'R' THEN -log.qty
                                            ELSE 0 END)
                                        , 0) AS export
                                        ,COALESCE(SUM(
                                            CASE WHEN header.type = 'M' THEN log.qty
                                            ELSE 0 END)
                                        , 0) AS reset
                                        ,COALESCE(SUM(
                                            CASE WHEN header.type = 'T' AND header.department_id = $department_id AND header.department_id != header.partner_id THEN log.qty
                                            ELSE 0 END)
                                        , 0) AS move_in
                                        ,COALESCE(SUM(
                                            CASE WHEN header.type = 'T' AND header.partner_id = $department_id AND header.department_id != header.partner_id THEN -log.qty 
                                            ELSE 0 END)
                                        , 0) AS move_out
                                        ,uom.uom_name
                                        FROM item_part AS item
                                        LEFT JOIN audit_detail AS log ON item.id = log.part_id
                                        LEFT JOIN audit_header AS header ON header.id = log.audit_id
                                        AND (
                                            (header.type IN ('S','T','M') AND header.department_id = $department_id) 
                                            OR (header.type IN ('R','T') AND header.partner_id = $department_id)
                                        )
                                        AND header.status = 1 
                                        AND header.flag_del = 0
                                        AND DATE_FORMAT(header.date, '%Y-%m-%d') >= '$startDate'
                                        AND DATE_FORMAT(header.date, '%Y-%m-%d') <= '$endDate'
                                        LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
                                        WHERE item.flag_del = 0 
                                        AND COALESCE(log.flag_del, 0) = 0
                                        GROUP BY item.id, item.part_name
                                        HAVING import != 0 
                                        OR export != 0 
                                        OR reset != 0
                                        OR move_in != 0
                                        OR move_out != 0
                                        ORDER BY item.id")
                                            ->queryAll();
                                    }
                                    $amount = 0;
                                    foreach ($model as $item) {
                                        $total = $item['import'] + $item['export'] + $item['move_in'] + $item['move_out'] + $item['reset'];
                                        $sum = $item['unit_price'] * $total;
                                        $amount = $amount + $sum;
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $item['part_name'] ?>
                                            </td>
                                            <td>
                                                <?= $item['uom_name'] ?>
                                            </td>
                                            <td class="text-right">
                                                <?= number_format($item['unit_price'], 2) ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $item['import'] ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $item['export'] ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $department_id == 0 ? '-' : $item['move_in'] ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $department_id == 0 ? '-' : $item['move_out'] ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $item['reset'] ?>
                                            </td>
                                            <td class="text-right">
                                                <?= $total ?>
                                            </td>
                                            <td class="text-right">
                                                <?= number_format($sum, 2) ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="9">มูลค่ารวม</th>
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