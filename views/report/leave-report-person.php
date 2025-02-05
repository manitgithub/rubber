<?php

use app\models\Checkin;
use app\models\Employee;
use app\models\Leave;
use app\models\Worktime;

$this->title = 'รายงานข้อมูลการปฏิบัติงาน';

$userid = Yii::$app->user->identity->id;


if (Yii::$app->request->get('year') != null) {
    $year = Yii::$app->request->get('year');
} else {
    $year = date('Y') + 543;
}


$month_a = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

?>
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

<div class="card">
    <div class="card-body">

        <div class="form-group row">

            <label for="year" class="col-sm-1 col-form-label">ปี</label>
            <div class="col-sm-2">
                <select class="form-control" id="year" name="year">
                    <option value="2568">2568</option>
                </select>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-primary" onclick="searchData()">ค้นหา</button>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function searchData() {
        var year = $('#year').val();
        window.location.href = '<?= Yii::$app->request->baseUrl . '/report/leave-report-person' ?>' + '?year=' + year;
    }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'th', // ตั้งค่าภาษาไทย

            initialView: 'dayGridMonth', // แสดงปฏิทินแบบรายเดือน
            headerToolbar: {
                left: 'prev,next today', // ปุ่มด้านซ้าย
                center: 'title', // ชื่อเดือน/ปี
                right: 'dayGridMonth,timeGridWeek,timeGridDay', // ปุ่มเปลี่ยนมุมมอง
            },
            events: [
                <?php
                    $now = date('Y-m-d');
                    $worktime = $worktime = Worktime::find()
                        ->where(['userid' => $userid, 'year' => 2568])
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
                            if ($wt->status == '' and $wt->date < $now) {



                                $leave = Leave::find()->where(['userid' => $userid, 'status' => 1])->andWhere(['<=', 'startdate', $wt->date])->andWhere(['>=', 'enddate', $wt->date])->one();
                                //$datalast = date('Y-m-d', strtotime($wt->date . ' -1 day'));
                                // เมื่อวาน 

                                $datalast = date('Y-m-d', strtotime($now . ' -1 day'));


                                if ($leave) {
                                    $wt->statuswork = 5;
                                    $wt->leaveid = $leave->id;
                                } else {
                                    if ($datalast > $wt->date) {
                                        //    echo 'xxxxxxxxxxxxxxxxxxxxx';
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



                                $wt->save(false);
                            }
                            $show = '';
                            if ($timecheckin != '' && $timechecout != '') {
                                $show = $wt->status . ' ' . $timecheckin . ' - ' . $timechecout;
                            } else {
                                $show = $wt->status;
                            }


                    ?> {

                    title: '<?= $wt->statuswork != '' ? $show : $wt->timeworkst . ' - ' . $wt->timeworkend ?> ',
                    start: '<?= $wt->date ?>',
                    color: '<?= $wt->statuswork == 1 ? 'green' : ($wt->statuswork == 2 ? 'orange' : ($wt->statuswork == 3 ? 'orange' : ($wt->statuswork == 4 ? 'orange' : ($wt->statuswork == 5 ? 'info' : ($wt->statuswork == 6 ? 'pink' : ($wt->statuswork == 7 ? 'purple' : ($wt->statuswork == 8 ? 'gray' : 'gray'))))))) ?>',
                },
                <?php } else { ?> {
                    title: 'วันหยุด',
                    start: '<?= $wt->date ?>',
                    backgroundColor: 'red',
                },
                <?php
                        }
                    } ?>
            ],
            dayCellDidMount: function(info) {
                if (info.date.getDay() === 0) { // วันอาทิตย์
                    info.el.style.backgroundColor = 'lightpink';
                }
                if (info.date.toISOString().startsWith('2025-01-25')) { // วันพิเศษ
                    info.el.style.backgroundColor = 'lightblue';
                }
            },

        });

        calendar.render(); // แสดงผลปฏิทิน
    });
    </script>
    </head>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <body>
        <div id='calendar'></div>
    </body>


</div>