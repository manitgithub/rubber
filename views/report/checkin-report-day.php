<?php

use app\models\Checkin;
use app\models\Employee;
use app\models\Worktime;

$this->title = 'รายงานการเข้างาน';

if (Yii::$app->request->get('d') != null) {
    $d = Yii::$app->request->get('d');
} else {
    $d = '';
}


$month_a = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

?>

<div class="card">
    <div class="card-body">

        <div class="form-group row">
            <label for="month" class="col-sm-2 col-form-label">วันที่</label>
            <div class="col-sm-3">
                <input type="text" id="d" class="form-control datepicker flatpickr-input" name="d" readonly="readonly">
            </div>

            <div class="col-sm-1">
                <button type="button" class="btn btn-primary" onclick="searchData()">ค้นหา</button>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function searchData() {
        var d = $('#d').val();
        window.location.href = '<?= Yii::$app->request->baseUrl . '/report/checkin-report-day' ?>' + '?d=' + d;
    }
    </script>
    <?php if ($d != '') { ?>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title htitle">รายงานข้อมูลการเข้าออกงาน</h5>
        </div>
        <div class="card-body">
            <table class="table datatable table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">ลำดับที่</th>
                        <th class="text-center">ชื่อ-สกุล</th>
                        <th class="text-center">เวลา</th>
                        <th class="text-center">เข้า/ออก</th>
                        <th class="text-center">หมายเหตุ</th>
                        <th class="text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $Checkinlist = Checkin::find()->where(['date' => $d])->orderBy('userid DESC')->all();
                        foreach ($Checkinlist as $key => $ck) { ?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td>
                        <td><?= $ck->user->fullname ?>-<?= $ck->user->nickname ?></td>
                        <td class="text-center"><?= substr($ck->datetime, 10, 10) ?></td>
                        <td class="text-center">
                            <i class="fa fa-circle" style="color:<?= $ck->type == 1 ? 'green' : 'red' ?>"></i>
                            <?= $ck->type == 1 ? 'เข้า' : 'ออก' ?>
                        </td>
                        <td class="text"><?= $ck->note ?></td>
                        <td class="text-center">
                            <?= $ck->status == 1 ? '' :  ' รออนุมัติ -' . $ck->boss->fullname   ?>
                            <?= $ck->status == 3 ? 'ไม่อนุมัติ' : '' ?>

                        </td>
                    </tr>
                    <?php }  ?>

                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
</div>