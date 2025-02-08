<?php

use app\models\Participants;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Running $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Runnings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="running-view">


    <div class="jumbotron  bg-white">
        <h4 class="mb-3"><?= $model->name ?>!
            <div class="float-right">
                <a class="btn btn-primary" href="<?= Url::to(['running/pickup', 'id' => $model->id]) ?>"
                    role="button"><i class="material-icons">add</i> รับของ</a>
                <a class="btn btn-warning" href="<?= Url::to(['update', 'id' => $model->id]) ?>" role="button"><i
                        class="material-icons">edit</i> แก้ไข</a>
                <a class="btn btn-warning" href="<?= Url::to(['index']) ?>" role="button"><i
                        class="material-icons">arrow_back</i> กลับ</a>
            </div>
        </h4>
        <hr class="my-4">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'img',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::img($model->PhotoViewer, ['class' => 'img-thumbnail', 'style' => 'width: 200px;']);
                    }
                ],
                'date',
                'owner',
                'detail',
            ],
        ]) ?>
        อัพโหลดไฟล์ Excel เพื่อเพิ่มข้อมูล
        <input type="file" id="excelFile" accept=".xls, .xlsx" />
        <br><br>
        <button id="confirmUpload" style="display: none;">ยืนยันอัพโหลด</button>
        <br><br>

        <table id="excelTable"></table>
        <div class="table-responsive">
            <h4>รายชื่อผู้เข้าร่วม</h4>
            <table class="table table-striped table-bordered datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>เลขบัตรประชาชน</th>
                        <th>ชื่อสกุล</th>
                        <th>เพศ</th>
                        <th>เบอร์โทร</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $participants = Participants::find()->where(['runningid' => $model->id])->all();
                    foreach ($participants as $index => $participant) {


                        $status = $participant->status == 1 ? '<font class="text text-success">รับของแล้ว</font>' : '<font class="text text-warning">รอรับของ</font>';
                    ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $participant->nationalId ?></td>
                            <td><?= $participant->first_name ?> <?= $participant->last_name ?></td>
                            <td><?= $participant->gender == "male" ? "ชาย" : "หญิง" ?></td>
                            <td><?= $participant->participant_telephone ?></td>
                            <td>
                                <div class="text-center"> <?= $status ?> </div>
                            </td>
                            <td>
                                <div class="text-center">

                                    <a href="<?= Url::to(['participants/view', 'id' => $participant->id]) ?>"
                                        class="btn btn-primary"><i class="material-icons">visibility</i> ดู</a>
                                    <a href="<?= Url::to(['participants/update', 'id' => $participant->id]) ?>"
                                        class="btn btn-warning"><i class="material-icons">edit</i> แก้ไข</a>
                                </div>
                            </td>

                        <?php

                    }
                        ?>

                </tbody>
            </table>
        </div>
    </div>



    <script>
        const fileInput = document.getElementById('excelFile');
        const confirmUploadButton = document.getElementById('confirmUpload');
        const table = document.getElementById('excelTable');

        // เมื่อผู้ใช้เลือกไฟล์
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            confirmUploadButton.style.display = file ? 'block' : 'none';
        });

        // เมื่อคลิกปุ่มยืนยันอัพโหลด
        confirmUploadButton.addEventListener('click', function() {
            const file = fileInput.files[0];
            if (!file) {
                alert('กรุณาเลือกไฟล์ Excel');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });

                // เลือกชีตแรก
                const sheetName = workbook.SheetNames[0];
                const sheet = workbook.Sheets[sheetName];

                // แปลงชีตเป็น JSON
                const jsonData = XLSX.utils.sheet_to_json(sheet, {
                    header: 1
                });

                // นับจำนวนแถว
                const rowCount = jsonData.length;

                // แสดงจำนวนรวมในตาราง
                table.innerHTML = `<tr><td>จำนวนแถวทั้งหมด: ${rowCount}</td></tr>`;



                // ส่งข้อมูลไปยังเซิร์ฟเวอร์
                sendDataToServer(jsonData, rowCount);
            };

            reader.readAsArrayBuffer(file);
        });

        // ฟังก์ชันส่งข้อมูลไปยังเซิร์ฟเวอร์



        function sendDataToServer(data, rowCount) {
            fetch('/registration/save-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json' // แจ้งว่าเราต้องการ JSON กลับ
                    },
                    body: JSON.stringify({
                        rows: data,
                        running: <?= $model->id ?> // ส่ง ID ของกิจกรรมไปด้วย
                    }) // ห่อข้อมูลใน key "rows"
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json(); // แปลงเป็น JSON
                })
                .then(result => {
                    if (result.status === 'success') {
                        alert(`อัพโหลดสำเร็จ! จำนวนแถว: ${rowCount}`);

                        // ให้ Refresh หน้าเว็บหลังจากอัพโหลดสำเร็จ
                        window.location.reload();

                    } else {
                        console.error('ข้อผิดพลาดจากเซิร์ฟเวอร์:', result.errors);
                        alert(`เกิดข้อผิดพลาดในการบันทึกข้อมูล: ${JSON.stringify(result.errors)}`);
                    }
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาด:', error);
                    alert(`เกิดข้อผิดพลาดในการอัพโหลด: ${error.message}`);
                });
        }
    </script>

    <!-- รวมไลบรารี SheetJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


</div>