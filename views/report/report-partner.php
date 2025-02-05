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

$this->title = 'รายงานการรับเข้าสินค้าจากคู่ค้า';
Yii::$app->view->params['callBack'] = ['sys/report'];


// $this->registerJsFile('@web/app/report-product.js?v=' . date('YmdHis'), ['depends' => AppAsset::className()]);

$partner_id = Yii::$app->request->get('partner_id');
$startDate = Yii::$app->request->get('start-date');
$endDate = Yii::$app->request->get('end-date');
$department_id = Yii::$app->request->get('department_id');
$total_amount = 0;
if (empty($partner_id)) {
    $partner_id = 0;
}
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
                        <a href="<?= Url::to(['index']) ?>" class="nav-link border-primary rounded-0 active"><?= $this->title ?></a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12  col-md-3 ">
                        <div class="mb-3 field-auditheader-partner_id">
                            <label class="form-label " for="auditheader-partner_id">คู่ค้า</label>
                            <select id="partner_id" class="form-control form-select" name="AuditHeader[partner_id]">
                                <option value="0" <?= $partner_id == 0 ? 'selected' : '' ?>>คู่ค้าทั้งหมด</option>
                                <?php
                                $modelPartner = AuditPartner::find()->orderBy(['name' => SORT_ASC])->asArray()->all();
                                foreach ($modelPartner as $partner) {
                                ?>
                                    <option value="<?= $partner['id'] ?>" <?= $partner_id == $partner['id'] ? 'selected' : '' ?>>
                                        <?= $partner['name'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12  col-md-3 ">
                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">คลัง</label>
                            <select id="department" class="form-control form-select" name="AuditHeader[department]">
                                <option value="0">คลังทั้งหมด</option>
                                <?php
                                $modelDepartment = AuditDepartment::find()->orderBy(['department' => SORT_ASC])->asArray()->all();
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


                    <div class="col-12  col-md-3 ">
                        <div class="mb-3 field-auditheader-department_id">
                            <label class="form-label" for="auditheader-department_id">วันที่เริ่มต้น</label>
                            <input type="text" id="start-date" class="form-control datepicker" name="date" value="<?= $startDate ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12  col-md-3 ">
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
                            var partner_id = document.getElementById('partner_id').value;
                            var startDate = document.getElementById('start-date').value;
                            var endDate = document.getElementById('end-date').value;
                            var department_id = document.getElementById('department').value;

                            window.location = '<?= Url::to(['report-partner']) ?>?partner_id=' + partner_id +
                                '&start-date=' + startDate + '&end-date=' + endDate + '&department_id=' + department_id;
                        }
                    </script>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 pt-4">
                        <h4 class="text-center">
                            <label><?= $this->title ?> ตั้งแต่วันที่ <?= $startDate ?> ถึง <?= $endDate ?></label> <br>
                            <small>คู่ค้า:
                                <?= $partner_id == 0 ? 'ทั้งหมด' : AuditPartner::findOne($partner_id)->name ?>
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
                                        <th class="align-middle text-center">เอกสาร</th>
                                        <th class="align-middle text-center">รับเข้าคลัง</th>
                                        <th class="align-middle text-center">รายการสินค้า</th>
                                        <th class="align-middle text-center">หน่วย</th>
                                        <th class="align-middle text-center">จำนวน</th>
                                        <th class="align-middle text-center">ราคาต่อหน่วย</th>
                                        <th class="align-middle text-center">คิดเป็นมูลค่า</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($partner_id == 0) {
                                        $qureypartner = '';
                                    } else {
                                        $qureypartner = " and audit_header.partner_id = $partner_id ";
                                    }
                                    if ($department_id == 0) {
                                        $querydepartment = '';
                                    } else {
                                        $querydepartment = " and audit_header.department_id = $department_id ";
                                    }
                                    $model = Yii::$app->db->createCommand("SELECT
                                    item_part.part_name,
                                    audit_department.department,
                                    audit_detail.unit_price,
                                    audit_detail.qty,
                                    audit_detail.total_amount,item_uom.uom_name,
audit_header.created_at as date,
audit_header.billno,
audit_header.refno,
audit_header.partner_id,
audit_header.id
                                    FROM
                                    audit_header
                                    INNER JOIN audit_detail ON audit_header.id = audit_detail.audit_id
                                    INNER JOIN item_part ON audit_detail.part_id = item_part.id
                                    INNER JOIN audit_department ON audit_header.department_id = audit_department.id
                                    INNER JOIN item_uom ON audit_detail.uom_id = item_uom.id
                                    where 1=1 $qureypartner $querydepartment and DATE(audit_header.created_at) between '$startDate' and '$endDate' and audit_header.type = 'S'
                                    and audit_header.flag_del = 0
                                    ")->queryAll();
                                    foreach ($model as $key => $value) {
                                        $total_amount += $value['total_amount'];
                                    ?>

                                        <tr onclick="window.open('<?= Url::to(['goods-receipts/update', 'id' => $value['id']]) ?>')">
                                            <td class="align-middle">
                                                <div class="row align-items-center">
                                                    <div class="col align-self-center pr-0">
                                                        <h6 class="font-weight-normal mb-0">
                                                            <?= $value['date'] ?>
                                                        </h6>
                                                        <p class="small mb-0">
                                                            <?= $value['billno'] ?> :
                                                            <?= $value['refno'] ?> :
                                                            <?= AuditPartner::findOne($value['partner_id'])->name ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $value['department'] ?></td>
                                            <td><?= $value['part_name'] ?></td>
                                            <td><?= $value['uom_name'] ?></td>
                                            <td class="text-right"> <?= number_format($value['qty']) ?></td>
                                            <td class="text-right"> <?= number_format($value['unit_price'], 2) ?></td>
                                            <td class="text-right"> <?= number_format($value['total_amount'], 2) ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-right">รวม</td>
                                        <td class="text-right"><?= number_format($total_amount, 2) ?></td>
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