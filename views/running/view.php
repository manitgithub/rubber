<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Running $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Runnings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="running-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'date',
            'owner',
            'detail',
        ],
    ]) ?>

    <input type="file" id="excelFile" accept=".xls, .xlsx" />
    <br><br>
    <button id="confirmUpload" style="display: none;">ยืนยันอัพโหลด</button>
    <br><br>
    <table id="excelTable" border="1"></table>

    <script>
    const fileInput = document.getElementById('excelFile');
    const confirmUploadButton = document.getElementById('confirmUpload');
    const table = document.getElementById('excelTable');

    // เมื่อผู้ใช้เลือกไฟล์
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // แสดงปุ่มยืนยันอัพโหลด
            confirmUploadButton.style.display = 'block';
        } else {
            // ซ่อนปุ่มยืนยันอัพโหลดถ้าไม่มีไฟล์
            confirmUploadButton.style.display = 'none';
        }
    });

    // เมื่อคลิกปุ่มยืนยันอัพโหลด
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
            fetch('/registration/save-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json' // แจ้งว่าเราต้องการ JSON กลับ
                    },
                    body: JSON.stringify(jsonData) // ส่งข้อมูลตรง ๆ ไม่ต้องใส่ { jsonData }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json(); // แปลงเป็น JSON
                })
                .then(result => {
                    console.log('บันทึกข้อมูลสำเร็จ:', result);
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาด:', error);
                });


            // แสดงข้อความสำเร็จ
            alert(`อัพโหลดสำเร็จ! จำนวนแถว: ${rowCount}`);
        };

        reader.readAsArrayBuffer(file);
    });
    </script>

    <!-- รวมไลบรารี SheetJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


</div>