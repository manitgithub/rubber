<?php

use app\models\Checkin;
use app\models\Employee;
use app\models\Holiday;
use app\models\Worktime;


if (Yii::$app->request->get('month') != null) {
    $month = Yii::$app->request->get('month');
    $month = $month;
} else {
    $month = '';
}
if (Yii::$app->request->get('year') != null) {
    $year = Yii::$app->request->get('year');
} else {
    $year = '';
}


$month_a = array('1' => 'มกราคม', '2' => 'กุมภาพันธ์', '3' => 'มีนาคม', '4' => 'เมษายน', '5' => 'พฤษภาคม', '6' => 'มิถุนายน', '7' => 'กรกฎาคม', '8' => 'สิงหาคม', '9' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');

?>

<div class="card">
    <div class="card-body">

        <div class="form-group row">

            <label for="month" class="col-sm-1 col-form-label">เดือน</label>
            <div class="col-sm-2">
                <select class="form-control" id="month" name="month">
                    <?php for ($i = 1; $i <= 12; $i++) {
                        echo "<option value='" . ($i) . "' " . ($month == ($i) ? 'selected' : '') . ">" . $month_a[$i] . "</option>";
                    } ?>
                </select>
            </div>
            <label for="year" class="col-sm-1 col-form-label">ปี</label>
            <div class="col-sm-2">
                <select class="form-control" id="year" name="year">
                    <option value="2025">2025</option>
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
        var month = $('#month').val();
        var year = $('#year').val();
        window.location.href = '<?= Yii::$app->request->baseUrl . '/report/monthly-report' ?>' + '?month=' + month +
            '&year=' + year;
    }
    </script>
    <?php
    if ($month == 1) {
        $monthlast = 12;
        $yearlast = (int)$year - 1;
    } else {
        $monthlast = (int)$month - 1;
        $yearlast = (int)$year;
    }

    $yearlast = $yearlast;
    $year = $year;



    ?>
    <?php if ($month != '' && $year != '') { ?>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title htitle">รายงานสรุปการปฏิบัติงานประจำเดือน
                <?= $month_a[$month] . ' ' . ($year) ?></h5>
        </div>
        <div class="card-body table-responsive">

            <div class="row">
                <div class="col-md-9">
                    <div class="alert alert-info" role="alert">
                        <span class="text-muted">ยป</span> = วันประจำปี,
                        <span class="text-muted">ย</span> = วันหยุด,
                        <span class="text-primary">ป</span> = ปกติ,
                        <span class="text-danger">ส</span> = สาย,
                        <span class="text-warning">อ</span> = ออกก่อน,
                        <span class="text-danger">สอ</span> = สาย และ ออกก่อน,
                        <span class="text-info">ลก</span> = ลากิจ,
                        <span class="text-info">ลป</span> = ลาป่วย,
                        <span class="text-info">ล</span> = ลาอื่นๆ,
                        <span class="text-danger">ข</span> = ขาดงาน,
                        <span class="text-danger">มข</span> = ไม่ลงเวลาเข้า,
                        <span class="text-danger">มอ</span> = ไม่ลงเวลาออก
                        <br>ผิดปกติ = ออกก่อน , ไม่ลงเวลาเข้า , ไม่ลงเวลาออก
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <button onclick="exportTableToExcel()" class="btn btn-success btn-sm float-end">
                        <i class="fas fa-file-excel"></i>
                        Export</button>

                </div>
            </div>

            <table class="table table-bordered table-hover" id="table-to-export">

                <tr>
                    <th width="10" rowspan="2">ลำดับ</th>
                    <th width="260" rowspan="2">ชื่อ-สกุล</th>
                    <th colspan="7" class="text-center"><?= $month_a[$monthlast] . ' ' . ($yearlast) ?></th>
                    <th colspan="24" class="text-center"><?= $month_a[$month] . ' ' . ($year) ?></th>
                    <th colspan="8" class="text-center">สรุป</th>
                </tr>
                <tr>
                    <?php
                        $start_date = new DateTime($yearlast . '-' . $monthlast . '-25');
                        $end_date = new DateTime($year . '-' . $month . '-24');
                        $end_date->modify('+1 day'); // Include the end date in the loop
                        $interval = new DateInterval('P1D');
                        $period = new DatePeriod($start_date, $interval, $end_date);

                        foreach ($period as $date) {
                            $gregorian_year = $date->format('Y');
                            $day = $date->format($gregorian_year . 'md');

                            $worktime[$day] = '';
                            echo '<th width="20">' . $date->format('d') . '</th>';
                        }
                        ?>
                    <th width="20">วันทำงาน</th>
                    <th width="20">วันหยุด</th>
                    <th width="20">ผิดปกติ</th>
                    <th width="20">ปกติ</th>
                    <th width="20">ขาดงาน</th>
                    <th width="20">ลา</th>
                    <th width="20">สาย</th>
                    <th width="20">นาทีสาย</th>
                </tr>
                <?php
                    $users = Employee::find()->where(['!=', 'id', '1'])->andwhere(['timework' => 1])->all();
                    $r = 0;
                    foreach ($users as $u) {
                        $r++;
                        $userid = $u->id;
                        $worktimes = Worktime::find()->where(['userid' => $userid])
                            ->andwhere(['between', 'date', $yearlast . '-' . $monthlast . '-25', $year . '-' . $month . '-24'])
                            ->all();
                        $leave = 0;
                        $absent = 0;
                        $normal = 0;
                        $dis = 0;
                        $workc = 0;
                        $holiday = 0;
                        $late = 0;
                        $min = 0;

                        foreach ($worktimes as $wt) {
                            $date = new DateTime($wt->date);
                            $d = $date->format('Ymd');
                            $worktime[$d] = $wt->statussub;
                            if ($wt->statuswork == 2 || $wt->statuswork == 4) {
                                $late++;
                                $latemin = date_diff(date_create($wt->timecheckin), date_create($wt->timeworkst));
                                $min += $latemin->h * 60 + $latemin->i;
                            }
                            if ($wt->statuswork == 6) {
                                $dis++;
                            }
                            if ($wt->statuswork == 5) {
                                $leave++;
                            }
                            if (in_array($wt->statuswork, ['3', '7', '8'])) {
                                $absent++;
                            }
                            if ($wt->statuswork == '1') {
                                $normal++;
                            }
                            if ($wt->typework > 1) {
                                $holiday++;
                            }
                            if ($wt->typework == 1) {
                                $workc++;
                            }
                        }
                    ?>
                <tr>
                    <td><?= $r ?></td>
                    <td><?= $u->fullname ?>-<?= $u->nickname ?>
                    </td>
                    <?php foreach ($worktime as $day => $val) { ?>
                    <td>
                        <?php $date = new DateTime($day);
                                    $holidays = Holiday::find()->where(['date' => $day])->one();
                                    if ($holidays) {
                                        echo '<span class="text-muted">ยป</span>';
                                    } else {
                                        echo $worktime[$day];
                                    } ?>
                    </td>
                    <?php } ?>
                    <td><?= $workc ?></td>
                    <td><?= $holiday ?></td>
                    <td><?= $absent ?></td>
                    <td><?= $normal ?></td>
                    <td><?= $dis ?></td>
                    <td><?= $leave ?></td>
                    <td><?= $late ?></td>
                    <td><?= $min ?></td>
                </tr>
                <?php } ?>

            </table>
        </div>

        <script>
        function exportTableToExcel() {
            const table = document.getElementById('table-to-export');
            const workbook = XLSX.utils.table_to_book(table, {
                sheet: "Sheet1"
            });
            workbook.Sheets["Sheet1"]["!cols"] = [{
                    wch: 5
                }, // คอลัมน์ A: กว้าง 20 ตัวอักษร
                {
                    wch: 20
                }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                {
                    wch: 2
                } // คอลัมน์ C: กว้าง 15 ตัวอักษร
                , // คอลัมน์ B: กว้าง 10 ตัวอักษร
                {
                    wch: 2
                }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                {
                    wch: 2
                }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                {
                    wch: 2
                }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                {
                    wch: 2
                }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                {
                    wch: 2
                }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                {
                    wch: 2
                }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 2
                }, {
                    wch: 5
                }, {
                    wch: 5
                }, {
                    wch: 5
                }, {
                    wch: 5
                }, {
                    wch: 5
                }, {
                    wch: 5
                }, {
                    wch: 5
                }, {
                    wch: 5
                }
            ];

            XLSX.writeFile(workbook,
                'รายงานสรุปเดือน <?= $month_a[$month] ?>.xlsx');
        }
        </script>
        <?php } ?>
    </div>
</div>