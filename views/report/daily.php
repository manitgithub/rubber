<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var string $date */
/** @var app\models\Purchases[] $purchases */
/** @var float $total_weight */
/** @var float $total_dry_weight */
/** @var float $total_amount */


if(isset($_GET['sdate']) && !empty($_GET['sdate'])) {
    $sdate = $_GET['sdate'];
} else {
    $sdate = date('Y-m-d');
}

if (isset($_GET['edate']) && !empty($_GET['edate'])) {
    $edate = $_GET['edate'];
} else {
    $edate = date('Y-m-d');
}
$showday = true;
if($sdate == $edate) {
    $showday = false;
} else {
    $showday = true;
}

$this->title = $showday ? 'รายงานการรับซื้อน้ำยาง <br> วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'รายงานการรับซื้อน้ำยาง <br> วันที่ ' . Yii::$app->helpers->DateThai($sdate);

?>
<div class="card card-body mb-4">
    <h5><i class="bi bi-file-earmark-text"></i> รายงานสรุปการรับซื้อน้ำยาง</h5>
    

<div class="mb-3">
    <form method="get" action="<?= \yii\helpers\Url::to(['report/daily']) ?>">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">วันที่</label>
                <input type="date" name="sdate" value="<?= $sdate ?>" class="form-control datepicker">
            </div>

            <div class="col-md-3">
                <label class="form-label">ถึงวันที่</label>
                <input type="date" name="edate" value="<?= $edate ?>" class="form-control datepicker">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">ค้นหา</button>
            </div>
            <?php if (!empty($purchases)): ?>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-primary w-100" onclick="printReport()">
                    <i class="bi bi-printer"></i> พิมพ์รายงาน
                </button>
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>



<?php if (!empty($purchases)): ?>
    <p class="text-muted">
        <?= $showday ? 'รายงานการรับซื้อน้ำยาง <br>วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'แสดงรายงานการรับซื้อน้ำยาง วันที่ ' . Yii::$app->helpers->DateThai($sdate) ?>
    </p>
    <table class="table datatable table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <?= $showday ? '<th>วันที่</th>' : '' ?>
                <th>ชื่อ-สกุล</th>
                <th>เลขทะเบียน</th>
                <th>นน ยางสด(กก.)</th>
                <th>%DRC</th>
                <th>นน ยางแห้ง (กก.)</th>
                <th>ราคา/กก.</th>
                <th>ยอดรวม</th>
                <th>ลายมือชื่อผู้ส่ง</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <?= $showday ? '<td>' . Yii::$app->helpers->DateThai($p->date) . '</td>' : '' ?>
                <td><?= Html::encode($p->members->fullname2) ?></td>
                <td><?= Html::encode($p->members->memberid) ?></td>
                <td><?= number_format($p->weight, 2) ?></td>
                <td><?= number_format($p->percentage, 2) ?></td>
                <td><?= number_format($p->dry_weight, 2) ?></td>
                <td><?= number_format($p->price_per_kg, 2) ?></td>
                <td><?= number_format($p->total_amount, 2) ?></td>
                <td></td>

            </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr class="table-warning">
                <td colspan="<?= $showday ? '4' : '3' ?>" class="text-end"><strong>รวม</strong></td>
                <td class="text-end"><strong><?= number_format($total_weight, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($total_dry_weight, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($total_amount, 2) ?></strong></td>
                                <td></td>

            </tr>
        </tfoot>
    </table>
<?php endif; ?>

</div>

<!-- Print-only content -->
<div id="printContent" class="d-none">
    <div class="print-header text-center mb-4">
        <h2>รายงานการรับซื้อน้ำยาง</h2>
        <h4><?= $showday ? 'วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'วันที่ ' . Yii::$app->helpers->DateThai($sdate) ?></h4>
        <hr style="border: 1px solid #000; margin: 10px 0;">
    </div>
    
    <?php if (!empty($purchases)): ?>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 5%;">ลำดับ</th>
                <?= $showday ? '<th style="width: 10%;">วันที่</th>' : '' ?>
                <th style="width: 15%;">ชื่อ-สกุล</th>
                <th style="width: 10%;">เลขทะเบียน</th>
                <th style="width: 10%;">นน.ยางสด(กก.)</th>
                <th style="width: 8%;">%DRC</th>
                <th style="width: 10%;">นน.ยางแห้ง(กก.)</th>
                <th style="width: 10%;">ราคา/กก.</th>
                <th style="width: 10%;">ยอดรวม</th>
                <th style="width: 12%;">ลายมือชื่อผู้ส่ง</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <?= $showday ? '<td>' . Yii::$app->helpers->DateThai($p->date) . '</td>' : '' ?>
                <td style="text-align: left; padding-left: 5px;"><?= Html::encode($p->members->fullname2) ?></td>
                <td><?= Html::encode($p->members->memberid) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($p->weight, 2) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($p->percentage, 2) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($p->dry_weight, 2) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($p->price_per_kg, 2) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($p->total_amount, 2) ?></td>
                <td>&nbsp;</td>
            </tr>
            <?php endforeach ?>
            
            <?php 
            // เพิ่มแถวว่างเพื่อให้เหมือนกับแบบฟอร์ม
            $emptyRows = 20 - count($purchases);
            if ($emptyRows > 0) {
                for ($i = 0; $i < $emptyRows; $i++) {
                    echo '<tr>';
                    echo '<td>&nbsp;</td>';
                    if ($showday) echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="<?= $showday ? '4' : '3' ?>" style="text-align: center; font-weight: bold;">รวม</td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format($total_weight, 2) ?></td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format(array_sum(array_column($purchases, 'percentage')) / count($purchases), 2) ?></td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format($total_dry_weight, 2) ?></td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;">51.00</td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format($total_amount, 2) ?></td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="signature-section">
        <div class="signature-left">
            <p>ลงชื่อ ................................................ </p>
            <p style="margin-left: 50px; margin-top: 5px;">( นางวัญเพ็ญ  ดำเพ็ง )</p>
            <p style="margin-left: 50px;">ผู้รับน้ำยาง</p>
        </div>
        <div class="signature-right">
            <p>ลงชื่อ ................................................ </p>
            <p style="margin-left: 50px; margin-top: 5px;">( นายสุภาพ  ใจห้าว )</p>
            <p style="margin-left: 50px;">เหรัญญิก/รักษาการในตำแหน่งประธานฯ</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
@media print {
    /* Hide everything except print content */
    body * {
        visibility: hidden;
    }
    
    #printContent, #printContent * {
        visibility: visible;
    }
    
    #printContent {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        display: block !important;
    }
    
    /* Print-specific styles */
    @page {
        size: A4 portrait;
        margin: 15mm;
    }
    
    .print-header h2 {
        font-size: 22px;
        margin-bottom: 8px;
        font-weight: bold;
    }
    
    .print-header h4 {
        font-size: 16px;
        margin-bottom: 20px;
        font-weight: normal;
    }
    
    .print-table {
        font-size: 14px;
        width: 100%;
        border-collapse: collapse;
        border: 2px solid #000;
        margin-bottom: 25px;
    }
    
    .print-table th, 
    .print-table td {
        border: 1px solid #000;
        padding: 6px 4px;
        text-align: center;
        vertical-align: middle;
        line-height: 1.3;
        min-height: 25px;
    }
    
    .print-table th {
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 13px;
        border: 2px solid #000;
    }
    
    .print-table tbody tr {
        min-height: 25px;
    }
    
    .print-table tbody td {
        min-height: 25px;
        border: 1px solid #000;
    }
    
    .total-row {
        background-color: #f9f9f9;
        font-weight: bold;
    }
    
    .total-row td {
        border: 2px solid #000;
    }
    
    .signature-section {
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
        font-size: 14px;
    }
    
    .signature-left,
    .signature-right {
        width: 48%;
    }
    
    .signature-left p,
    .signature-right p {
        margin: 5px 0;
        line-height: 1.5;
    }
    
    .text-end {
        text-align: right !important;
    }
    
    .table-warning {
        background-color: #fff3cd !important;
    }
    
    /* Remove Bootstrap classes that don't work well in print */
    .d-none {
        display: none !important;
    }
}

/* Screen styles for print content (hidden by default) */
#printContent {
    display: none;
}
</style>

<script>
function printReport() {
    // Show print content
    document.getElementById('printContent').style.display = 'block';
    
    // Trigger print dialog
    window.print();
    
    // Hide print content after printing
    setTimeout(function() {
        document.getElementById('printContent').style.display = 'none';
    }, 1000);
}

// Alternative method: Open new window for printing
function printReportNewWindow() {
    var printContent = document.getElementById('printContent').innerHTML;
    var printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>รายงานการรับซื้อน้ำยาง</title>
            <meta charset="utf-8">
            <style>
                @page {
                    size: A4 portrait;
                    margin: 15mm;
                }
                
                body {
                    font-family: 'Sarabun', Arial, sans-serif;
                    margin: 0;
                    font-size: 16px;
                }
                
                .print-header {
                    text-align: center;
                    margin-bottom: 25px;
                }
                
                .print-header h2 {
                    font-size: 22px;
                    margin-bottom: 8px;
                    font-weight: bold;
                }
                
                .print-header h4 {
                    font-size: 16px;
                    margin-bottom: 20px;
                    font-weight: normal;
                }
                
                .print-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 14px;
                    border: 2px solid #000;
                    margin-bottom: 25px;
                }
                
                .print-table th, 
                .print-table td {
                    border: 1px solid #000;
                    padding: 6px 4px;
                    text-align: center;
                    vertical-align: middle;
                    line-height: 1.3;
                    min-height: 25px;
                }
                
                .print-table th {
                    background-color: #f0f0f0;
                    font-weight: bold;
                    font-size: 13px;
                    border: 2px solid #000;
                }
                
                .print-table tbody tr {
                    min-height: 25px;
                }
                
                .print-table tbody td {
                    min-height: 25px;
                    border: 1px solid #000;
                }
                
                .total-row {
                    background-color: #f9f9f9;
                    font-weight: bold;
                }
                
                .total-row td {
                    border: 2px solid #000;
                }
                
                .signature-section {
                    margin-top: 40px;
                    display: flex;
                    justify-content: space-between;
                    font-size: 14px;
                }
                
                .signature-left,
                .signature-right {
                    width: 48%;
                }
                
                .signature-left p,
                .signature-right p {
                    margin: 5px 0;
                    line-height: 1.5;
                }
                
                .text-end {
                    text-align: right !important;
                }
                
                .table-warning {
                    background-color: #fff3cd;
                }
                
                .mt-4 {
                    margin-top: 30px;
                }
                
                .row {
                    display: flex;
                    width: 100%;
                }
                
                .col-6 {
                    width: 50%;
                }
                
                .text-end {
                    text-align: right;
                }
            </style>
        </head>
        <body>
            <div class="print-header">
            <br>
                <h2>รายงานการรับซื้อน้ำยาง</h2>
                <h4><?= $showday ? 'วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'วันที่ ' . Yii::$app->helpers->DateThai($sdate) ?></h4>
                <hr style="border: 1px solid #000; margin: 10px 0;">
            </div>
            ${printContent}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>
