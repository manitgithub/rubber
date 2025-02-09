<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Print</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        .slip-container {
            border: 1px solid #000;
            padding: 20px;
            max-width: 400px;
            margin: auto;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .section {
            margin-top: 10px;
        }

        .section h4 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        .section p {
            margin: 2px 0;
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 10px;
            font-size: 12px;
        }

        .bold {
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="slip-container">
        <div class="center">
            <img src="<?= $model->running->PhotoViewer ?>" class="img-fluid" width="300px">
            <br>
            <span class="title"><?= $model->running->name ?></span>
        </div>

        <div class="section">
            <h4>กิจกรรม / Event:</h4>
            <p><?= $model->running->name ?></p>
            <h4>วันที่จัดกิจกรรม / Date:</h4>
            <p><?= $model->running->date ?></p>
            <h4>หมายเลขบัตร / National ID:</h4>
            <p><?= $model->nationalId ?></p>
            <h4>ชื่อ-นามสกุล / Name:</h4>
            <p><?= $model->first_name ?> <?= $model->last_name ?></p>
        </div>

        <div class="box">
            <h4>อุปกรณ์การแข่งขัน / Equipment:</h4>
            <p> <?= $model->shirt_type ?></p>
            <p>SIZE => <?= $model->shirt ?></p>
        </div>

        <div class="section">
            <h4>ระยะวิ่ง / Race Type:</h4>
            <p><?= $model->race ?></p>
            <h4>รุ่นอายุ / Age Group:</h4>
            <p><?= $model->age_category ?></p>
            <h4>BIB:</h4><!-- แสดงเป็น 4 หลัก -->
            <p><?= str_pad($model->bib_number, 4, '0', STR_PAD_LEFT) ?></p>
            <h4>เวลาเริ่มต้น / Start Time:</h4>
            <p>06:00 น.</p>
        </div>

        <div class="section">
            <p class="bold">
                <span class="center"><?= $model->running->detail ?></span>
            </p>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="slip-container">
        <div class="center">
            <img src="<?= $model->running->PhotoViewer ?>" class="img-fluid" width="300px">
            <br>
            <span class="title"><?= $model->running->name ?></span>
        </div>

        <div class="section">
            <h4>กิจกรรม / Event:</h4>
            <p><?= $model->running->name ?></p>
            <h4>วันที่จัดกิจกรรม / Date:</h4>
            <p><?= $model->running->date ?></p>
            <h4>หมายเลขบัตร / National ID:</h4>
            <p><?= $model->nationalId ?></p>
            <h4>ชื่อ-นามสกุล / Name:</h4>
            <p><?= $model->first_name ?> <?= $model->last_name ?></p>
        </div>

        <div class="box">
            <h4>อุปกรณ์การแข่งขัน / Equipment:</h4>
            <p> <?= $model->shirt_type ?></p>
            <p>SIZE => <?= $model->shirt ?></p>
        </div>

        <div class="section">
            <h4>ระยะวิ่ง / Race Type:</h4>
            <p><?= $model->race ?></p>
            <h4>รุ่นอายุ / Age Group:</h4>
            <p><?= $model->age_category ?></p>
            <h4>BIB:</h4><!-- แสดงเป็น 4 หลัก -->
            <p><?= str_pad($model->bib_number, 4, '0', STR_PAD_LEFT) ?></p>
            <h4>รับของ / Pick Up:</h4>
            <p> <?= $model->picktime ?></p>
        </div>

        <div class="section">
            <p class="bold">
                <span class="center"><?= $model->running->detail ?></span>
            </p>
        </div>
    </div>
</body>

</html>