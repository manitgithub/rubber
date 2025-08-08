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

<style>
.report-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
}

.report-header h5 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    text-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.report-header i {
    margin-right: 0.5rem;
    font-size: 1.4rem;
}

.search-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    padding: 0.6rem 1rem;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #6610f2);
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
}

.data-summary {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid #28a745;
}

.data-summary .text-muted {
    color: #6c757d !important;
    font-weight: 500;
}

.table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.table thead th {
    background: linear-gradient(135deg, #343a40, #495057);
    color: white;
    font-weight: 600;
    border: none;
    padding: 1rem 0.75rem;
    text-align: center;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.001);
    transition: all 0.2s ease;
}

.table-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7) !important;
    font-weight: 600;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    border: none;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
}

.alert-warning i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #856404;
}
</style>

<div class="report-header">
    <h5><i class="bi bi-file-earmark-text"></i> รายงานสรุปการรับซื้อน้ำยาง</h5>
</div>

<div class="search-card">
    

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

</div>



<?php if (!empty($purchases)): ?>
    <div class="data-summary">
        <p class="text-muted">
            <?= $showday ? 'รายงานการรับซื้อน้ำยาง <br>วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'แสดงรายงานการรับซื้อน้ำยาง วันที่ ' . Yii::$app->helpers->DateThai($sdate) ?>
        </p>
    </div>
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
                <td><?= number_format($p->dry_weight, 1) ?></td>
                <td><?= number_format($p->price_per_kg, 2) ?></td>
                <td><?= number_format($p->total_amount, 2) ?></td>

            </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr class="table-warning">
                <td colspan="<?= $showday ? '4' : '3' ?>" class="text-end"><strong>รวม</strong></td>
                <td class="text-end"><strong><?= number_format($total_weight, 2) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($total_dry_weight, 1) ?></strong></td>
                <td></td>
                <td class="text-end"><strong><?= number_format($total_amount, 2) ?></strong></td>

            </tr>
        </tfoot>
    </table>
<?php else: ?>
    <div class="alert alert-warning text-center" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>ไม่พบข้อมูล</strong><br>
        ไม่มีรายการรับซื้อน้ำยางในช่วงวันที่ที่เลือก
    </div>
<?php endif; ?>

</div>

<!-- Print-only content -->
<div id="printContent" class="d-none">
    <div class="print-header text-center mb-4">
        <h2>สหกรณ์การกองทุนยางฉลองน้ำขาวพัฒนา จำกัด</h2>
        <h2>ใบรับน้ำยาง</h2>

        <h2>รับซื้อน้ำยาสดประจำ<?= $showday ? 'วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'วันที่ ' . Yii::$app->helpers->DateThai($sdate) ?></h2>
        <hr style="border: 1px solid #000; margin: 10px 0;">
    </div>
    
    <?php if (!empty($purchases)): ?>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 5%;">ลำดับ</th>
                <?= $showday ? '<th style="width: 15%;">วันที่</th>' : '' ?>
                <th style="width: 20%;">ชื่อ-สกุล</th>
                <th style="width: 9%;">เลขทะเบียน</th>
                <th style="width: 9%;">นน.ยางสด(กก.)</th>
                <th style="width: 7%;">%DRC</th>
                <th style="width: 9%;">นน.ยางแห้ง(กก.)</th>
                <th style="width: 9%;">ราคา/กก.</th>
                <th style="width: 9%;">ยอดรวม</th>
                        <?= $showday ? '' : '<td style="width: 15%;"> ลงลายมือชื่อ</td>'?>     
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
                <td style="text-align: right; padding-right: 5px;"><?= number_format($p->dry_weight, 1) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($p->price_per_kg, 2) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($p->total_amount, 2) ?></td>
                <?= $showday ? '' : '<td></td>'?>
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
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format($total_dry_weight, 1) ?></td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;">51.00</td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format($total_amount, 2) ?></td>
                <?= $showday ? '' : '<td style="width: 15%;"> </td>'?> 
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
        font-size: 18px;
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
        font-size: 18px;
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
        font-size: 16px;
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
                    font-size: 18px;
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
                    font-size: 18px;
                    margin-bottom: 20px;
                    font-weight: normal;
                }
                
                .print-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 18px;
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
                    font-size: 18px;
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
                    font-size: 16px;
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
                <h3>สหกรณ์การกองทุนยางฉลองน้ำขาวพัฒนา จำกัด</h3>
                <h3>ใบรับน้ำยาง</h3>
                <h3><?= $showday ? 'วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' ถึง ' . Yii::$app->helpers->DateThai($edate) : 'วันที่ ' . Yii::$app->helpers->DateThai($sdate) ?></h3>
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
