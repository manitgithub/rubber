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

$this->title = 'รายงานสรุปประจำเดือน';
Yii::$app->view->params['callBack'] = ['sys/report'];


$department_id = Yii::$app->request->get('department_id');
$month = Yii::$app->request->get('month');
if (empty($department_id)) {
    $department_id = 0;
}
if (empty($month)) {
    $month = date('Y-m');
}

$days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), date('Y', strtotime($month)));
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
                    <div class="col-12  col-md-6 ">
                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">คลังสินค้า/สตอร์</label>
                            <select id="department_id" class="form-control form-select"
                                name="AuditHeader[department_id]">
                                <option value="0" <?= $department_id == 0 ? 'selected' : '' ?>>คลังสินค้าทั้งหมด
                                </option>
                                <?php
                                $modelDepartment = AuditDepartment::find(['flag_del' => 0])->asArray()->all();
                                foreach ($modelDepartment as $department) {
                                ?>
                                <option value="<?= $department['id'] ?>"
                                    <?= $department_id == $department['id'] ? 'selected' : '' ?>>
                                    <?= $department['department'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12  col-md-6 ">
                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">ประจำเดือน</label>
                            <input type="text" id="month" class="form-control datepicker-month" name="date"
                                value="<?= $month ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-sm btn-primary" onclick="search()"><i
                                class="material-icons">search</i> ค้นหา</button>
                    </div>
                    <script>
                    function search() {
                        var department_id = document.getElementById('department_id').value;
                        var month = document.getElementById('month').value;
                        window.location = '<?= Url::to(['monthly']) ?>?department_id=' + department_id + '&month=' +
                            month;
                    }
                    </script>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 pt-4">
                        <h4 class="text-center">
                            <label><?= $this->title ?> <?= $month ?></label> <br>
                            <small>คลัง
                                <?= $department_id == 0 ? 'คลังสินค้าทั้งหมด' : AuditDepartment::findOne(['id' => $department_id])->department ?></small>
                        </h4>
                    </div>
                </div>
                <div class="row py-4">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="custom-control custom-radio">
                            <input type="radio" name="option" class="custom-control-input" id="selectAll" value="1"
                                checked="">
                            <label class="custom-control-label" for="selectAll">แสดงสินค้าทั้งหมด</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="custom-control custom-radio">
                            <input type="radio" name="option" class="custom-control-input" id="selectValuable"
                                value="0">
                            <label class="custom-control-label" for="selectValuable">แสดงเฉพาะสินค้าที่มีสต๊อค</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 px-1">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center" colspan="2" width="10%">วันที่</th>
                                    <th class="align-middle text-center">จำนวนรายการ</th>
                                    <th class="align-middle text-center">มูลค่า</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                function filterArray($inputArray, $filterDate, $filterType)
                                {
                                    foreach ($inputArray as $item) {
                                        if ($item['log_date'] == $filterDate && $item['type'] == $filterType) {
                                            return $item;
                                        }
                                    }
                                    return null;
                                }

                                if ($department_id  == 0) {
                                    $model = Yii::$app->db->createCommand("SELECT header.type
                                    ,DATE_FORMAT(header.date, '%Y-%m-%d') AS log_date
                                    ,count(header.id) AS total
                                    ,COALESCE(SUM(CASE 
                                        WHEN header.type = 'S' THEN log.unit_price * log.qty 
                                        ELSE item.unit_price * log.qty END)
                                    , 0) AS amount
                                    FROM audit_detail AS log
                                    LEFT JOIN item_part AS item ON item.id = log.part_id
                                    RIGHT JOIN audit_header AS header ON header.id = log.audit_id 
                                    AND header.status = 1 
                                    AND header.flag_del = 0
                                    AND DATE_FORMAT(header.date, '%Y-%m') = '$month'
                                    WHERE item.flag_del = 0 
                                    AND log.flag_del = 0
                                    GROUP BY header.type,log_date
                                    ")
                                        ->queryAll();
                                } else {
                                    $model = Yii::$app->db->createCommand("SELECT DATE_FORMAT(header.date, '%Y-%m-%d') AS log_date
                                    ,count(header.id) AS total
                                    ,COALESCE(SUM(CASE 
                                        WHEN header.type = 'S' THEN log.unit_price * log.qty 
                                        ELSE item.unit_price * log.qty END)
                                    , 0) AS amount
                                    ,CASE 
                                        WHEN header.type = 'T' AND header.department_id = $department_id AND header.department_id != header.partner_id THEN 'Tin' 
                                        WHEN header.type = 'T' AND header.partner_id = $department_id AND header.department_id != header.partner_id THEN 'Tout' 
                                        ELSE header.type 
                                    END AS type
                                    FROM audit_detail AS log
                                    LEFT JOIN item_part AS item ON item.id = log.part_id
                                    RIGHT JOIN audit_header AS header ON header.id = log.audit_id 
                                    AND header.status = 1 
                                    AND header.flag_del = 0
                                    AND (
                                        (header.type IN ('S','T','M') AND header.department_id = $department_id) 
                                        OR (header.type IN ('R','T') AND header.partner_id = $department_id)
                                        )
                                    AND DATE_FORMAT(header.date, '%Y-%m') = '$month'
                                    WHERE item.flag_del = 0 
                                    AND log.flag_del = 0
                                    GROUP BY header.type,log_date
                                    ")
                                        ->queryAll();
                                }


                                $summary_total = 0;
                                $summary_amount = 0;
                                for ($i = 1; $i <= $days; $i++) {
                                    $filterDate = date('Y-m-d', strtotime($month . '-' . $i));
                                    $resultS = filterArray($model, $filterDate, 'S'); //นำเข้า
                                    $resultR = filterArray($model, $filterDate, 'R'); //เบิก
                                    $resultM = filterArray($model, $filterDate, 'M'); //เบิก

                                    if ($department_id  == 0) {
                                        $resultTin = null;
                                        $resultTout = null;
                                    } else {
                                        $resultTin = filterArray($model, $filterDate, 'Tin');;
                                        $resultTout = filterArray($model, $filterDate, 'Tout');;
                                    }


                                    $total_list = 0;
                                    $total_amount = 0;
                                    if ($resultS) {
                                        $total_list =  $total_list + $resultS['total'];
                                        $total_amount =  $total_amount + $resultS['amount'];
                                    }
                                    if ($resultR) {
                                        $total_list =  $total_list + $resultR['total'];
                                        $total_amount =  $total_amount - $resultR['amount'];
                                    }
                                    if ($resultM) {
                                        $total_list =  $total_list + $resultM['total'];
                                        $total_amount =  $total_amount + $resultM['amount'];
                                    }

                                    if ($resultTin) {
                                        $total_list =  $total_list + $resultTin['total'];
                                        $total_amount =  $total_amount + $resultTin['amount'];
                                    }

                                    if ($resultTout) {
                                        $total_list =  $total_list + $resultTout['total'];
                                        $total_amount =  $total_amount - $resultTout['amount'];
                                    }

                                    $summary_total = $summary_total + $total_list;
                                    $summary_amount = $summary_amount + $total_amount;

                                ?>
                                <tr
                                    onclick="window.open('<?= Url::to(['report-partner', 'partner_id' => 0, 'start-date' => $filterDate, 'end-date' => $filterDate, 'department_id' => $department_id]) ?>')">
                                    <td nowrap rowspan="5" class="align-middle">
                                        <?= Yii::$app->helpers->dateENtoTH($filterDate) ?></td>
                                    <td nowrap>รับสินค้า</td>
                                    <td nowrap class="text-right">
                                        <?= isset($resultS['total']) ? $resultS['total'] : '0' ?></td>
                                    <td nowrap class="text-right">
                                        <?= isset($resultS['amount']) ? number_format($resultS['amount'], 2) : '0.00' ?>
                                    </td>
                                </tr>
                                <tr
                                    onclick="window.open('<?= Url::to(['requisition-product', 'department_id' => $department_id, 'start-date' => $filterDate, 'end-date' => $filterDate]) ?>')">
                                    <td nowrap>เบิกสินค้า</td>
                                    <td nowrap class="text-right">
                                        <?= isset($resultR['total']) ? $resultR['total'] : '0' ?></td>
                                    <td nowrap class="text-right">
                                        <?= isset($resultR['amount']) ? '-' . number_format($resultR['amount'], 2) : '0.00' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap>ย้ายสินค้าเข้า</td>
                                    <?php if ($department_id  == 0) { ?>
                                    <td nowrap class="text-right">-</td>
                                    <td nowrap class="text-right">-</td>
                                    <?php } else { ?>
                                    <td nowrap class="text-right">
                                        <?= isset($resultTin['total']) ? $resultTin['total'] : '0' ?></td>
                                    <td nowrap class="text-right">
                                        <?= isset($resultTin['amount']) ?  number_format($resultTin['amount'], 2) : '0.00' ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td nowrap>ย้ายสินค้าออก</td>
                                    <?php if ($department_id  == 0) { ?>
                                    <td nowrap class="text-right">-</td>
                                    <td nowrap class="text-right">-</td>
                                    <?php } else { ?>
                                    <td nowrap class="text-right">
                                        <?= isset($resultTout['total']) ? $resultTout['total'] : '0' ?></td>
                                    <td nowrap class="text-right">
                                        <?= isset($resultTout['amount']) ? '-' . number_format($resultTout['amount'], 2) : '0.00' ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td nowrap>ปรับสต๊อค</td>
                                    <td nowrap class="text-right">
                                        <?= isset($resultM['total']) ? $resultM['total'] : '0' ?></td>
                                    <td nowrap class="text-right">
                                        <?= isset($resultM['amount']) ? number_format($resultM['amount'], 2) : '0.00' ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right" colspan="2">สรุปทั้งหมด</th>
                                    <th class="text-right"><?= $summary_total ?></th>
                                    <th class="text-right"><?= number_format($summary_amount, 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>