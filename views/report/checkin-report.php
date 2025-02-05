<?php

use app\models\Checkin;
use app\models\Employee;
use app\models\Worktime;


if (Yii::$app->request->get('userid') != null) {
    $userid = Yii::$app->request->get('userid');
} else {
    $userid = '';
}
if (Yii::$app->request->get('month') != null) {
    $month = Yii::$app->request->get('month');
    if ($month < 10) {
        $month = '0' . $month;
    }
} else {
    $month = '';
}
if (Yii::$app->request->get('year') != null) {
    $year = Yii::$app->request->get('year');
} else {
    $year = '';
}


$month_a = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

?>

<div class="card">
    <div class="card-body">

        <div class="form-group row">
            <label for="month" class="col-sm-2 col-form-label">พนักงาน</label>
            <div class="col-sm-3">
                <select class="form-control" id="userid" name="userid">
                    <?php $user = Employee::find()->where(['status' => 10, 'timework' => 1])->all();
                    foreach ($user as $u) {
                        echo "<option value='" . $u->id . "' " . ($userid == $u->id ? 'selected' : '') . ">" . $u->fullname . "</option>";
                    }
                    ?>
                </select>
            </div>
            <label for="month" class="col-sm-1 col-form-label">เดือน</label>
            <div class="col-sm-2">
                <select class="form-control" id="month" name="month">
                    <?php for ($i = 0; $i < 12; $i++) {
                        echo "<option value='" . ($i + 1) . "' " . ($month == ($i + 1) ? 'selected' : '') . ">" . $month_a[$i] . "</option>";
                    } ?>
                </select>
            </div>
            <label for="year" class="col-sm-1 col-form-label">ปี</label>
            <div class="col-sm-1">
                <select class="form-control" id="year" name="year">
                    <option value="2568">2568</option>
                </select>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="searchData()">ค้นหา</button>
                <a href="<?= Yii::$app->request->baseUrl . '/report' ?>" class="btn btn-danger">ย้อนกลับ</a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function searchData() {
            var userid = $('#userid').val();
            var month = $('#month').val();
            var year = $('#year').val();
            window.location.href = '<?= Yii::$app->request->baseUrl . '/report/checkin-report' ?>' + '?userid=' + userid +
                '&month=' + month + '&year=' + year;
        }
    </script>
    <?php if ($userid != '' && $month != '' && $year != '') { ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title htitle">รายงานข้อมูลการเข้าออกงาน</h5>
            </div>
            <div class="card-body">
                <table class="table datatable table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">ลำดับที่</th>
                            <th class="text-center">วันที่</th>
                            <th class="text-center">เวลา</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Checkinlist = Checkin::find()->where(['userid' => $userid])->andWhere(['LIKE', 'date', $year - 543 . '-' . $month])->orderBy(['date' => SORT_ASC])->all();
                        foreach ($Checkinlist as $key => $ck) { ?>
                            <tr>
                                <td class="text-center"><?= $key + 1 ?></td>
                                <td class="text-center"><?= Yii::$app->helpers->dateThai($ck->date) ?></td>
                                <td class="text-center"><?= substr($ck->datetime, 10, 10) ?></td>
                                <td class="text-center">
                                    <i class="fa fa-circle" style="color:<?= $ck->type == 1 ? 'green' : 'red' ?>"></i>
                                    <?= $ck->type == 1 ? 'เข้า' : 'ออก' ?>
                                </td>
                                <td class="text-center"><?= $ck->note ?></td>
                            </tr>
                        <?php }  ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>