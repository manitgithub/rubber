<?php

use app\models\Checkin;
use app\models\Employee;
use app\models\Leave;
use app\models\Worktime;


if (Yii::$app->request->get('userid') != null) {
    $userid = Yii::$app->request->get('userid');
} else {
    $userid = '';
}
if (Yii::$app->request->get('month') != null) {
    $month = Yii::$app->request->get('month');
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
                    <?php for ($i = 1; $i <= 12; $i++) {
                        echo "<option value='" . ($i) . "' " . ($month == ($i) ? 'selected' : '') . ">" . $month_a[$i] . "</option>";
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
            window.location.href = '<?= Yii::$app->request->baseUrl . '/report/leave-report' ?>' + '?userid=' + userid +
                '&month=' + month + '&year=' + year;
        }
    </script>
    <?php if ($userid != '' && $month != '' && $year != '') { ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                    </div>

                    <div class="col-md-3 text-right">
                        <button onclick="exportTableToExcel()" class="btn btn-success btn-sm float-end">
                            <i class="fas fa-file-excel"></i>
                            Export</button>
                    </div>
                </div>

                <table class="table table-bordered table-striped" id="table-to-export">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="10">รายงานการปฏิบัติงานประจำเดือน <?= $month_a[$month] ?>
                                <?= $year ?></th>
                        </tr>
                        <tr>
                            <th class="text-center" colspan="10"> <?= Employee::findOne($userid)->fullname ?>
                                <?= Employee::findOne($userid)->position ?>
                                <?= Employee::findOne($userid)->department ?></th>
                        </tr>
                        <tr>
                            <th class="text-center">ลำดับที่</th>
                            <th class="text-center">วันที่</th>
                            <th class="text-center">เวลาเข้า</th>
                            <th class="text-center">เวลาออก</th>
                            <th class="text-center">มาสาย</th>
                            <th class="text-center">ออกก่อน</th>
                            <th class="text-center">เวลาทำงาน</th>
                            <th class="text-center">สถานะการทำงาน</th>
                            <th class="text-center">สถานะการลา</th>
                            <th class="text-center">หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $now = date('Y-m-d');
                        $worktime = $worktime = Worktime::find()
                            ->where(['userid' => $userid, 'month' => $month, 'year' => $year])
                            ->all();

                        foreach ($worktime as $key => $wt) {
                            if ($wt->typework == 1) {
                                $late = 0;
                                $out = 0;
                                $checkin = Checkin::find()->where(['userid' => $userid, 'date' => $wt->date, 'type' => 1, 'status' => 1])->orderBy('id DESC')->one();
                                if ($checkin) {

                                    $timecheckin = $checkin->datetime;
                                    $timecheckin = substr($timecheckin, 11, 8);
                                    if ($timecheckin > $wt->timeworkst) {
                                        $wt->statuswork = 2;
                                        $late = 1;
                                    } else {
                                        $wt->statuswork = 1;
                                    }
                                    $wt->timecheckin = $timecheckin;
                                } else {
                                    $timecheckin = '';
                                }
                                $checkout = Checkin::find()->where(['userid' => $userid, 'date' => $wt->date, 'type' => 2, 'status' => 1])->orderBy('id DESC')->one();
                                if ($checkout) {
                                    $timechecout = $checkout->datetime;
                                    $timechecout = substr($timechecout, 11, 8);
                                    if ($timechecout < $wt->timeworkend) {
                                        $out = 1;
                                        $late == 1 ? $wt->statuswork = 4 : $wt->statuswork = 3;
                                    } else {
                                        $late == 1 ? $wt->statuswork = 2 : $wt->statuswork = 1;
                                    }
                                    $wt->timecheckout = $timechecout;
                                } else {
                                    $timechecout = '';
                                }
                                $leave = Leave::find()->where(['userid' => $userid, 'status' => 1])->andWhere(['<=', 'startdate', $wt->date])->andWhere(['>=', 'enddate', $wt->date])->one();
                                //$datalast = date('Y-m-d', strtotime($wt->date . ' -1 day'));
                                // เมื่อวาน 
                                if ($leave) {
                                    $wt->statuswork = 5;
                                    $wt->leaveid = $leave->id;
                                }
                                if ($wt->status == '' and $wt->date < $now) {
                                    if ($leave) {
                                        $wt->statuswork = 5;
                                        $wt->leaveid = $leave->id;
                                    } else {
                                        if ($datalast > $wt->date) {
                                            if (!$wt->timecheckin) {
                                                $wt->statuswork = 7;
                                            }
                                            if (!$wt->timecheckout) {
                                                $wt->statuswork = 8;
                                            }
                                            if ($wt->timecheckin == '' && $wt->timecheckout == '') {
                                                $wt->statuswork = 6;
                                            }
                                            if ($late == 1) {
                                                $wt->statuswork = 2;
                                            }
                                            if ($out == 1) {
                                                $wt->statuswork = 3;
                                            }
                                            if ($late == 1 && $out == 1) {
                                                $wt->statuswork = 4;
                                            }
                                        }
                                    }
                                }
                                $wt->save(false);



                        ?>
                                <?php ?>
                                <tr>
                                    <td class="col1 text-center "><?= $key + 1 ?></td>
                                    <td class="col2"><?= Yii::$app->helpers->Datethai($wt->date) ?>
                                    </td>
                                    <td class="col3 text-right"><?= $wt->statuswork != 5 ? $wt->timecheckin : '' ?></td>
                                    <td class="col4 text-right"><?= $wt->statuswork != 5 ? $wt->timecheckout : '' ?></td>
                                    <td class="col5">
                                        <?= $late == 1 ? Yii::$app->helpers->timeDiff($wt->timeworkst, $wt->timecheckin) : "" ?>
                                    </td>
                                    <td class="col6">
                                        <?= $out == 1 ? Yii::$app->helpers->timeDiff($wt->timecheckout, $wt->timeworkend) : "" ?>
                                    </td>
                                    <td class="col7">
                                        <?= $wt->statuswork != 5 ? $wt->timecheckin && $wt->timecheckout ? Yii::$app->helpers->timeDiff($wt->timecheckin, $wt->timecheckout) : '' : '' ?>
                                    </td>
                                    <td class="col8 text-center">
                                        <?= $wt->status ?>
                                    </td>
                                    <td class="col9 text-center"><?= $wt->leaveid ? $wt->leave->leavetype->name : '-' ?></td>
                                    <td class="col10 text-left"> <?= $wt->note ?> <?= $wt->leaveid ? $wt->leave->note : '' ?>


                                    </td>
                                </tr><?php } else { ?>
                                <tr>
                                    <td class="col1 text-center"><?= $key + 1 ?></td>
                                    <td class="col2"><?= Yii::$app->helpers->Datethai($wt->date) ?></td>
                                    <td class="col3" colspan="9" class="text-center">
                                        <div class="text-center">
                                            --------------วันหยุด-------------</div>
                                    </td>
                                </tr>
                            <?php }  ?>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
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
                        wch: 15
                    } // คอลัมน์ C: กว้าง 15 ตัวอักษร
                    , // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 15
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 15
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 15
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 25
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 15
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 15
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 15
                    }
                ];

                XLSX.writeFile(workbook,
                    '<?= $userid ? Employee::findOne($userid)->fullname : '' ?>-<?= $month_a[$month] ?>.xlsx');
            }
        </script>
    <?php } ?>
</div>