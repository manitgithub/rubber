<?php
use yii\helpers\Html;

/** @var \app\models\Receipt $receipt */
/** @var \app\models\Members $member */

$this->title = 'พิมพ์ใบเสร็จ';
$this->registerCss('@media print { .no-print { display: none; } }');
$this->registerCss("
    @page {
        size: 9in 5.5in;
        margin: 0.7in;
    }
    body {
        font-family: 'Sarabun', 'Arial', sans-serif;
        font-size: 22px;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
        min-height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
    }
    .container {
        max-width: 100% !important;
        width: 100%;
        height: auto;
        padding: 15px !important;
        margin: 0 !important;
        border: none;
        box-sizing: border-box;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .content-area {
        flex: 1;
        padding-top: 20px;
    }
    .signature-area {
        margin-top: auto;
        padding-top: 30px;
    }
    .receipt-header {
        margin-bottom: 20px;
        margin-top: 15px;
        padding: 0 5px;
    }
    h4, h5 {
        font-size: 24px;
        margin: 8px 0;
        font-weight: bold;
    }
    .table {
        font-size: 24px;
        border: none !important;
        border-collapse: collapse !important;
        width: 100%;
        margin: 5px 0;
    }
    .table th {
        background-color: transparent !important;
        border: none !important;
        padding: 4px 6px !important;
        font-weight: bold !important;
        text-align: center !important;
        vertical-align: middle !important;
        font-size: 18px;
    }
    .table td {
        border: none !important;
        padding: 4px 6px !important;
        vertical-align: middle !important;
        font-size: 20px;
    }
    .table tfoot td {
        border: none !important;
        padding: 4px 6px !important;
        font-weight: bold !important;
        font-size: 16px;
    }
    .table tfoot td.gray-bg {
        background-color: #f5f5f5 !important;
    }
    .table .text-end {
        text-align: right !important;
    }
    .table .text-center {
        text-align: center !important;
    }
    .mb-3, .my-5 {
        margin-bottom: 10px !important;
        margin-top: 10px !important;
    }
    .mb-4 {
        margin-bottom: 10px !important;
    }
    .mt-4 {
        margin-top: 15px !important;
    }
    .mt-5 {
        margin-top: 20px !important;
    }
    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .text-center {
        text-align: center !important;
    }
    .text-start {
        text-align: left !important;
    }
    .text-end {
        text-align: right !important;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
    }
    .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 10px;
    }
    @media print {
        body { 
            font-size: 22px !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding: 15px !important;
            min-height: calc(100vh - 30px) !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .container {
            border: none !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: auto !important;
            padding: 10px !important;
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .content-area {
            flex: 1 !important;
        }
        .signature-area {
            margin-top: auto !important;
            padding-top: 30px !important;
        }
        .table { 
            font-size: 22px !important; 
            border: none !important;
        }
        .table th, .table td {
            border: none !important;
            font-size: 20px !important;
        }
        h4, h5 {
            font-size: 26px !important;
        }
            font-size: 18px !important;
        }
        h4, h5 {
            font-size: 20px !important;
        }
        .container {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }
");

?>

<div class="container">
    <div class="content-area">
        <div class="d-flex receipt-header">
            <div class="text-start">
                <strong>เล่มที่</strong> <?= Html::encode($receipt->book_no) ?>
            </div>            
            <div class="text-end">
                <strong>เลขที่</strong> <?= str_pad($receipt->running_no, 4, '0', STR_PAD_LEFT) ?>
            </div>
        </div>
        
        <div class="text-center mb-3">
                    <div style="font-size: 20px; line-height: 1.4;">
             <strong>สหกรณ์กองทุนสวนยางฉลองน้ำขาวพัฒนา จำกัด 92 หมู่ 5 ตำบลฉลอง อำเภอสิชล จังหวัดนครศรีฯ </strong><br>
             <strong> ใบจ่ายเงินเจ้าหนี้ค่าน้ำยาง </strong></br>
             <strong>จ่ายเงินวันที่ <?= Yii::$app->helpers->DateThai($receipt->date) ?> </strong> <br> 
                <strong>ระหว่างวันที่ <?= Yii::$app->helpers->DateThai($receipt->start_date) ?> ถึง <?= Yii::$app->helpers->DateThai($receipt->end_date) ?> </strong>
            </div>
        </div>
        <div class="mb-3" style="font-size: 18px; line-height: 1.6; margin: 15px 0; padding: 0 5px;">
            <div class="d-flex" style="justify-content: space-between; margin-bottom: 5px;">
            <div><strong>ชื่อสมาชิก:</strong> <?= Html::encode($receipt->member->fullname2) ?></div>
            <div><strong>เลขที่สมาชิก:</strong> <?= Html::encode($receipt->member->memberid) ?></div>
            </div>
            บ้านเลขที่: <?= Html::encode($receipt->member->homenum) ?> หมู่ที่: <?= Html::encode($receipt->member->moo) ?> ตำบล <?= Html::encode($receipt->member->tumbon) ?> อำเภอ <?= Html::encode($receipt->member->amper) ?> จังหวัด <?= Html::encode($receipt->member->chawat) ?><br>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 8%;">ที่</th>
                    <th style="width: 18%;">เลขที่ใบรับ</th>
                    <th style="width: 18%;">วันที่ขายน้ำยาง</th>
                    <th style="width: 12%;">นน. ยางสด</th>
                    <th style="width: 10%;">% ยาง</th>
                    <th style="width: 12%;">นน.ยางแห้ง</th>
                    <th style="width: 10%;">ราคา</th>
                    <th style="width: 18%;">จำนวนเงิน</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalWeight = 0;
                $totalDry = 0;
                $row = 0;
                foreach ($receipt->purchases as $p):
                    $totalWeight += $p->weight;
                    $totalDry += $p->dry_weight;
                    $row++;
                ?>
                <tr>
                    <td class="text-center"><?= $row ?></td>
                    <td class="text-center"><?= Html::encode($p->receipt_number) ?></td>
                    <td class="text-center"><?= Yii::$app->helpers->DateThaiAbb($p->date) ?></td>
                    <td class="text-end"><?= number_format($p->weight, 1) ?></td>
                    <td class="text-center"><?= number_format($p->percentage, 2) ?></td>
                    <td class="text-end"><?= number_format($p->dry_weight, 1) ?></td>
                    <td class="text-end"><?= number_format($p->price_per_kg, 2) ?></td>
                    <td class="text-end"><?= number_format($p->total_amount, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-center"><strong>รวม</strong></td>
                    <td class="text-end"><strong><?= number_format($totalWeight, 1) ?></strong></td>
                    <td class="text-center"></td>
                    <td class="text-end"><strong><?= number_format($totalDry, 1) ?></strong></td>
                    <td class="text-center"></td>
                    <td class="text-end"><strong><?= number_format($receipt->total_amount, 2) ?></strong></td>
                </tr>
            <tr>
                    <td colspan="2" class="text-right">
                    จำนวนเงิน(ตัวอักษร)  </td>
                    <td colspan="4" class="gray-bg">
                        <?= Yii::$app->helpers->Convert($receipt->total_amount) ?></strong></td>
                 <td colspan="2" class="text-center">
                        รับเงินแล้ว  </td>
                    </tr>   
            </tfoot>
        </table>
    </div>

    <div class="signature-area">
        <div class="row">
            <div class="col-6 text-center" style="font-size: 18px; padding: 10px;">
                (....................................)<br>
                            <strong>ผู้จ่ายเงิน</strong>
            </div>
            <div class="col-6 text-center" style="font-size: 18px; padding: 10px;">
                (....................................)<br>
                            <strong>ผู้รับเงิน</strong>
            </div>
        </div>
    </div>
</div>
