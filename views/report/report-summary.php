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
$this->title = 'รายงานสรุปการยอดรับซื้อนำ้ยางรายวัน ระหว่าง วันที่ ' . Yii::$app->helpers->DateThai($sdate) . ' - ' . Yii::$app->helpers->DateThai($edate);
$total_a = 0;
$total_w = 0;
$total_dw = 0;
?>

<style>
.report-header {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.2);
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
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.15);
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
    border-left: 4px solid #17a2b8;
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

.summary-stats {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.stat-card {
    text-align: center;
    padding: 1rem;
    border-radius: 8px;
    background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: #17a2b8;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<div class="report-header">
    <h5><i class="bi bi-graph-up"></i> รายงานสรุปการยอดรับซื้อนำ้ยางรายวัน</h5>
</div>

<div class="search-card">
    

<div class="mb-3">
    <form method="get" action="<?= \yii\helpers\Url::to(['report/report-summary']) ?>">
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
            <?php if (!empty($sdate)): ?>
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

<?php if (!empty($sdate)): ?>
    <div class="data-summary">
        <p class="text-muted">แสดงรายงานการรับซื้อน้ำยาง วันที่ <?= Yii::$app->helpers->DateThai($sdate) ?> ถึง <?= Yii::$app->helpers->DateThai($edate) ?></p>
    </div>

    <table class="table datatable table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>วันที่</th>
                <th>นน ยางสด(กก.)</th>
                <th>นน ยางแห้ง (กก.)</th>
                <th>ราคา/กก.</th>
                <th>จำนวนเงิน (บาท)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 0;
            $sql = "SELECT SUM(weight) AS total_weight, SUM(dry_weight) AS total_dry_weight, SUM(total_amount) AS total_amount, AVG(price_per_kg) AS avg_price, date FROM purchases WHERE date BETWEEN :sdate AND :edate
            GROUP BY DATE(date)"; 
            $command = Yii::$app->db->createCommand($sql);
            $command->bindValue(':sdate', $sdate);
            $command->bindValue(':edate', $edate);
            $result = $command->queryAll();
            
            foreach ($result as $value) {
                $total_weight = $value['total_weight'];
                $total_dry_weight = $value['total_dry_weight'];
                $total_amount = $value['total_amount'];
                $avg_price = $value['avg_price'];
                $date = $value['date'];
                
                $total_w += $total_weight;
                $total_dw += $total_dry_weight;
                $total_a += $total_amount;
            
            
            ?>
            <tr>
                <td><?= ++$i ?></td>
                <td><?= Yii::$app->helpers->DateThai($date) ?></td>
                <td class="text-end"><?= number_format($total_weight, 2) ?></td>
                <td class="text-end"><?= number_format($total_dry_weight, 2) ?></td>
                <td class="text-end"><?= number_format($avg_price, 2) ?></td>
                <td class="text-end"><?= number_format($total_amount, 2) ?></td>
            </tr>
            <?php } ?>

        </tbody>
        <tfoot>
            <tr class="table-warning">
                <td colspan="2" class="text-center"><strong>รวม</strong></td>
                <td class="text-end"><strong><?= number_format($total_w, 2) ?></strong></td>
                <td class="text-end"><strong><?= number_format($total_dw, 2) ?></strong></td>
                <td class="text-end"><strong>-</strong></td>
                <td class="text-end"><strong><?= number_format($total_a, 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $i ?></div>
                    <div class="stat-label">จำนวนวัน</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($total_w, 0) ?></div>
                    <div class="stat-label">ยางสดรวม (กก.)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($total_dw, 0) ?></div>
                    <div class="stat-label">ยางแห้งรวม (กก.)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($total_a, 0) ?></div>
                    <div class="stat-label">รายได้รวม (บาท)</div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Print-only content -->
<div id="printContent" class="d-none">
    <div class="print-header text-center mb-4">
        <h2>สหกรณ์การกองทุนยางฉลองน้ำขาวพัฒนา จำกัด</h2>
        <h2>รายงานสรุปการยอดรับซื้อน้ำยางรายวัน</h2>
        <h2>ระหว่างวันที่ <?= Yii::$app->helpers->DateThai($sdate) ?> ถึง <?= Yii::$app->helpers->DateThai($edate) ?></h2>
        <hr style="border: 1px solid #000; margin: 10px 0;">
    </div>
    
    <?php if (!empty($sdate)): ?>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 10%;">ลำดับ</th>
                <th style="width: 15%;">วันที่</th>
                <th style="width: 18%;">นน.ยางสด(กก.)</th>
                <th style="width: 18%;">นน.ยางแห้ง(กก.)</th>
                <th style="width: 15%;">ราคา/กก.</th>
                <th style="width: 24%;">จำนวนเงิน(บาท)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 0;
            $sql = "SELECT SUM(weight) AS total_weight, SUM(dry_weight) AS total_dry_weight, SUM(total_amount) AS total_amount, AVG(price_per_kg) AS avg_price, date FROM purchases WHERE date BETWEEN :sdate AND :edate
            GROUP BY DATE(date)"; 
            $command = Yii::$app->db->createCommand($sql);
            $command->bindValue(':sdate', $sdate);
            $command->bindValue(':edate', $edate);
            $result = $command->queryAll();
            
            $print_total_w = 0;
            $print_total_dw = 0;
            $print_total_a = 0;
            
            foreach ($result as $value) {
                $total_weight = $value['total_weight'];
                $total_dry_weight = $value['total_dry_weight'];
                $total_amount = $value['total_amount'];
                $avg_price = $value['avg_price'];
                $date = $value['date'];
                
                $print_total_w += $total_weight;
                $print_total_dw += $total_dry_weight;
                $print_total_a += $total_amount;
            ?>
            <tr>
                <td><?= ++$i ?></td>
                <td><?= Yii::$app->helpers->DateThai($date) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($total_weight, 2) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($total_dry_weight, 2) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($avg_price, 2) ?></td>
                <td style="text-align: right; padding-right: 5px;"><?= number_format($total_amount, 2) ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" style="text-align: center; font-weight: bold;">รวม</td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format($print_total_w, 2) ?></td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format($print_total_dw, 2) ?></td>
                <td style="text-align: center; font-weight: bold;">-</td>
                <td style="text-align: right; padding-right: 5px; font-weight: bold;"><?= number_format($print_total_a, 2) ?></td>
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
    
    .print-table {
        font-size: 16px;
        width: 100%;
        border-collapse: collapse;
        border: 2px solid #000;
        margin-bottom: 25px;
    }
    
    .print-table th, 
    .print-table td {
        border: 1px solid #000;
        padding: 8px 6px;
        text-align: center;
        vertical-align: middle;
        line-height: 1.3;
        min-height: 30px;
    }
    
    .print-table th {
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 18px;
        border: 2px solid #000;
    }
    
    .print-table tbody tr {
        min-height: 30px;
    }
    
    .print-table tbody td {
        min-height: 30px;
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
</script>
