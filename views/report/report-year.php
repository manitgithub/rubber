<style>
    .htitle {
        text-align: center;
        font-size: 20px;
    }

    .hsecondtitle {
        text-align: center;
        font-size: 18px;
    }

    .lblmonth {
        text-align: center;
        font-size: 16px;
    }

    .lblpersonname {
        font-size: 16px;
    }

    .lblposition {
        font-size: 16px;
        padding-left: 10px;
    }

    .tbData {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
        box-sizing: border-box;
    }

    .tbData td,
    th {
        padding: 5px 5px;
        vertical-align: middle;
        border: solid 0.5px #000;
        font-weight: normal;
        font-size: 8pt;
    }

    .trhead {
        vertical-align: middle;
    }

    .colleave {
        width: 6% !important;
    }

    .txtcenter {
        text-align: center;
    }
</style>
<?php

use app\models\Employee;

$userid = Yii::$app->request->get('userid');
$year = Yii::$app->request->get('year');
if ($userid == null) {
    $userid = '';
}
if ($year == null) {
    $year = '';
}
?>
<div class="card">
    <div class="card-body">

        <div class="form-group row">
            <label for="month" class="col-sm-2 col-form-label">พนักงาน</label>
            <div class="col-sm-5">
                <select class="form-control" id="userid">
                    <?php $user = Employee::find()->where(['status' => 10, 'timework' => 1])->all();
                    foreach ($user as $u) {
                        echo "<option value='" . $u->id . "'>" . $u->fullname . "</option>";
                    }
                    ?>
                </select>
            </div>

            <label for="year" class="col-sm-1 col-form-label">ปี</label>
            <div class="col-sm-2">
                <select class="form-control" id="year">
                    <option value="2568">2568</option>

                </select>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="btnSearch()">ค้นหา</button>
                <a href="<?= Yii::$app->request->baseUrl . '/report' ?>" class="btn btn-danger">ย้อนกลับ</a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function btnSearch() {
            var userid = document.getElementById('userid').value;
            var year = document.getElementById('year').value;
            window.location.href = '/report/report-year?userid=' + userid + '&year=' + year;
        }
    </script>
    <?php if ($userid != null && $year != null) { ?>
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

                <table class="tbData" id="table-to-export">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="19">รายงานการปฏิบัติงานรายปี
                                <?= $year ?></th>
                        </tr>
                        <tr>
                            <th class="text-center" colspan="19"> <?= Employee::findOne($userid)->fullname ?>
                                <?= Employee::findOne($userid)->position ?>
                                <?= Employee::findOne($userid)->department ?></th>
                        </tr>

                        <tr class="trhead">
                            <th class="txtcenter" rowspan="2">ลำดับ</th>
                            <th class="txtcenter" rowspan="2">เดือน</th>
                            <th class="txtcenter" colspan="8">ปฏิบัติงาน</th>
                            <th class="txtcenter" colspan="9">ลา</th>
                        </tr>
                        <tr class="trhead">
                            <th class="txtcenter">ทำการ</th>
                            <th class="txtcenter">ทำงาน</th>
                            <th class="txtcenter">มาสาย</th>
                            <th class="txtcenter">ออกก่อน</th>
                            <th class="txtcenter">มาสาย/ออกก่อน</th>
                            <th class="txtcenter">ไม่ลงเวลาเข้า</th>
                            <th class="txtcenter">ไม่ลงเวลาออก</th>
                            <th class="txtcenter">ขาดงาน</th>
                            <th class="txtcenter colleave">ลาป่วย</th>
                            <th class="txtcenter colleave">ลาพักผ่อน</th>
                            <th class="txtcenter colleave">ลาทำหมัน</th>
                            <th class="txtcenter colleave">ลากิจธุระ</th>
                            <th class="txtcenter colleave">ลาเพื่อการฝึกอบรม</th>
                            <th class="txtcenter colleave">ลาอุปสมบท</th>
                            <th class="txtcenter colleave">ลาไม่รับค่าจ้าง</th>
                            <th class="txtcenter colleave">ลาคลอดบุตร</th>
                            <th class="txtcenter colleave">ลาหยุดจากการทำงาน</th>
                        </tr>
                        <?php
                        $sql = "SELECT 
    worktime.month,
    sum(case when worktime.typework = 1 then 1 else 0 end) as work_count,
    sum(case when worktime.statuswork = 1 then 1 else 0 end) as work1,
    sum(case when worktime.statuswork = 2 then 1 else 0 end) as work2,
    sum(case when worktime.statuswork = 3 then 1 else 0 end) as work3,
    sum(case when worktime.statuswork = 4 then 1 else 0 end) as work4,
    sum(case when worktime.statuswork = 6 then 1 else 0 end) as work6,
    sum(case when worktime.statuswork = 7 then 1 else 0 end) as work7,
    sum(case when worktime.statuswork = 8 then 1 else 0 end) as work8,
    sum(case when worktime.statuswork = 5 and `leave`.type = 1 then 1 else 0 end) as leave1,
    sum(case when worktime.statuswork = 5 and `leave`.type = 2 then 1 else 0 end) as leave2,
    sum(case when worktime.statuswork = 5 and `leave`.type = 3 then 1 else 0 end) as leave3,
    sum(case when worktime.statuswork = 5 and `leave`.type = 4 then 1 else 0 end) as leave4,
    sum(case when worktime.statuswork = 5 and `leave`.type = 5 then 1 else 0 end) as leave5,
    sum(case when worktime.statuswork = 5 and `leave`.type = 6 then 1 else 0 end) as leave6,
    sum(case when worktime.statuswork = 5 and `leave`.type = 7 then 1 else 0 end) as leave7,
    sum(case when worktime.statuswork = 5 and `leave`.type = 8 then 1 else 0 end) as leave8,
    sum(case when worktime.statuswork = 5 and `leave`.type = 9 then 1 else 0 end) as leave9
FROM 
    worktime
    LEFT JOIN
    `leave` ON worktime.leaveid = `leave`.id
    WHERE 
    worktime.userid = $userid
    AND worktime.year = $year
GROUP BY 
    worktime.month
";
                        $total = 0;
                        $work1total = 0;
                        $work2total = 0;
                        $work3total = 0;
                        $work4total = 0;
                        $work6total = 0;
                        $work7total = 0;
                        $work8total = 0;
                        $leave1total = 0;
                        $leave2total = 0;
                        $leave3total = 0;
                        $leave4total = 0;
                        $leave5total = 0;
                        $leave6total = 0;
                        $leave7total = 0;
                        $leave8total = 0;
                        $leave9total = 0;


                        //echo $sql;
                        $work = Yii::$app->db->createCommand($sql)->queryAll();
                        //var_dump($work);
                        foreach ($work as $index => $w) {
                            $work_count[$w['month']] = 0;
                            $work1[$w['month']] = 0;
                            $work2[$w['month']] = 0;
                            $work3[$w['month']] = 0;
                            $work4[$w['month']] = 0;
                            $work6[$w['month']] = 0;
                            $work7[$w['month']] = 0;
                            $work8[$w['month']] = 0;
                            $leave1[$w['month']] = 0;
                            $leave2[$w['month']] = 0;
                            $leave3[$w['month']] = 0;
                            $leave4[$w['month']] = 0;
                            $leave5[$w['month']] = 0;
                            $leave6[$w['month']] = 0;
                            $leave7[$w['month']] = 0;
                            $leave8[$w['month']] = 0;
                            $leave9[$w['month']] = 0;






                            $work_count[$w['month']] = $w['work_count'];
                            $work1[$w['month']] = $w['work1'];
                            $work2[$w['month']] = $w['work2'];
                            $work3[$w['month']] = $w['work3'];
                            $work4[$w['month']] = $w['work4'];
                            $work6[$w['month']] = $w['work6'];
                            $work7[$w['month']] = $w['work7'];
                            $work8[$w['month']] = $w['work8'];
                            $leave1[$w['month']] = $w['leave1'];
                            $leave2[$w['month']] = $w['leave2'];
                            $leave3[$w['month']] = $w['leave3'];
                            $leave4[$w['month']] = $w['leave4'];
                            $leave5[$w['month']] = $w['leave5'];
                            $leave6[$w['month']] = $w['leave6'];
                            $leave7[$w['month']] = $w['leave7'];
                            $leave8[$w['month']] = $w['leave8'];
                            $leave9[$w['month']] = $w['leave9'];





                            $total += $work_count[$w['month']];
                            $work1total += $work1[$w['month']];
                            $work2total += $work2[$w['month']];
                            $work3total += $work3[$w['month']];
                            $work4total += $work4[$w['month']];
                            $work6total += $work6[$w['month']];
                            $work7total += $work7[$w['month']];
                            $work8total += $work8[$w['month']];
                            $leave1total += $leave1[$w['month']];
                            $leave2total += $leave2[$w['month']];
                            $leave3total += $leave3[$w['month']];
                            $leave4total += $leave4[$w['month']];
                            $leave5total += $leave5[$w['month']];
                            $leave6total += $leave6[$w['month']];
                            $leave7total += $leave7[$w['month']];
                            $leave8total += $leave8[$w['month']];
                            $leave9total += $leave9[$w['month']];
                        }
                        $month = array(
                            1 => 'มกราคม',
                            2 => 'กุมภาพันธ์',
                            3 => 'มีนาคม',
                            4 => 'เมษายน',
                            5 => 'พฤษภาคม',
                            6 => 'มิถุนายน',
                            7 => 'กรกฎาคม',
                            8 => 'สิงหาคม',
                            9 => 'กันยายน',
                            10 => 'ตุลาคม',
                            11 => 'พฤศจิกายน',
                            12 => 'ธันวาคม'
                        );
                        $total_work = 0;
                        ?>
                        <?php for ($i = 1; $i <= 12; $i++) :
                            //$total_work += $work_count[$i]
                        ?>
                            <tr>
                                <td class="txtcenter"><?= $i ?></td>
                                <td class=""><?= $month[$i] ?> </td>
                                <td class="txtcenter"><?= @$work_count[$i] ?></td>
                                <td class="txtcenter"><?= $work1[$i] ?></td>
                                <td class="txtcenter"><?= $work2[$i] ?></td>
                                <td class="txtcenter"><?= $work3[$i] ?></td>
                                <td class="txtcenter"><?= $work4[$i] ?></td>
                                <td class="txtcenter colleave"><?= $work7[$i] ?></td>
                                <td class="txtcenter colleave"><?= $work8[$i] ?></td>
                                <td class="txtcenter"><?= $work6[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave1[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave2[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave3[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave4[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave5[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave6[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave7[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave8[$i] ?></td>
                                <td class="txtcenter colleave"><?= $leave9[$i] ?></td>
                            </tr>
                        <?php endfor; ?>

                        <tr>
                            <td class="txtcenter" colspan="2">รวม</td>
                            <td class="txtcenter"><?= $total ?></td>
                            <td class="txtcenter"><?= $work1total ?></td>
                            <td class="txtcenter"><?= $work2total ?></td>
                            <td class="txtcenter"><?= $work3total ?></td>
                            <td class="txtcenter"><?= $work4total ?></td>
                            <td class="txtcenter"><?= $work7total ?></td>
                            <td class="txtcenter"> <?= $work8total ?></td>
                            <td class="txtcenter"> <?= $work6total ?></td>
                            <td class="txtcenter"><?= $leave1total ?></td>
                            <td class="txtcenter"><?= $leave2total ?></td>
                            <td class="txtcenter"><?= $leave3total ?></td>
                            <td class="txtcenter"><?= $leave4total ?></td>
                            <td class="txtcenter"><?= $leave5total ?></td>
                            <td class="txtcenter"><?= $leave6total ?></td>
                            <td class="txtcenter"><?= $leave7total ?></td>
                            <td class="txtcenter"><?= $leave8total ?></td>
                            <td class="txtcenter"><?= $leave9total ?></td>
                        </tr>
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
                        wch: 3
                    } // คอลัมน์ C: กว้าง 15 ตัวอักษร
                    , // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }, // คอลัมน์ B: กว้าง 10 ตัวอักษร
                    {
                        wch: 3
                    }
                ];

                XLSX.writeFile(workbook,
                    '<?= $userid ? Employee::findOne($userid)->fullname : '' ?>.xlsx');
            }
        </script>
    <?php } ?>