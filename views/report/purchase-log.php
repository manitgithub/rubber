<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Purchases[] $purchases */
/** @var string $sdate */
/** @var string $edate */

$this->title = 'สมุดรับซื้อน้ำยาง';

// Calculate totals
$totalWeight = 0;
$totalDryWeight = 0;
$totalAmount = 0;
foreach ($purchases as $p) {
    $totalWeight += $p->weight;
    $totalDryWeight += $p->dry_weight;
    $totalAmount += $p->total_amount;
}
?>

<style>
.report-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    background: #fff;
    margin-bottom: 2rem;
}
.filter-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.stat-item {
    padding: 1rem;
    border-radius: 10px;
    background: #f1f3f5;
    text-align: center;
}
.stat-value {
    font-size: 1.25rem;
    font-weight: bold;
    color: #198754;
}
.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
}
.table thead th {
    background-color: #212529;
    color: #fff;
    border: none;
    font-weight: 500;
}
</style>

<div class="report-purchase-log">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><?= Html::encode($this->title) ?> 📖</h2>
            <p class="text-muted mb-0">รายการรับซื้อน้ำยางเรียงตามวันที่และรหัสสมาชิก</p>
        </div>
    </div>

    <div class="card report-card">
        <div class="card-body">
            <!-- Filter Form -->
            <div class="filter-section">
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['report/purchase-log'],
                ]); ?>
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">วันที่เริ่มต้น</label>
                        <input type="date" name="sdate" class="form-control" value="<?= Html::encode($sdate) ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">วันที่สิ้นสุด</label>
                        <input type="date" name="edate" class="form-control" value="<?= Html::encode($edate) ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>ค้นหา
                            </button>
                            <a href="<?= Url::to(['report/purchase-log', 'sdate' => date('Y-m-d'), 'edate' => date('Y-m-d')]) ?>" class="btn btn-outline-secondary">
                                วันนี้
                            </a>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

            <!-- Summary Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format(count($purchases)) ?></div>
                        <div class="stat-label">จำนวนรายการ</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format($totalWeight, 2) ?></div>
                        <div class="stat-label">น้ำหนักรวม (กก.)</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format($totalDryWeight, 2) ?></div>
                        <div class="stat-label">น้ำหนักแห้งรวม (กก.)</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format($totalAmount, 2) ?></div>
                        <div class="stat-label">จำนวนเงินรวม (บาท)</div>
                    </div>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-responsive">
                <table id="purchaseLogTable" class="table table-hover w-100">
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>รหัสสมาชิก</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th class="text-end">น้ำหนัก</th>
                            <th class="text-end">%DRC</th>
                            <th class="text-end">น้ำหนักแห้ง</th>
                            <th class="text-end">ราคา/กก.</th>
                            <th class="text-end">รวมเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $currentDate = null;
                        $dayTotalWeight = 0;
                        $dayTotalDryWeight = 0;
                        $dayTotalAmount = 0;
                        $dayCount = 0;

                        foreach ($purchases as $index => $p): 
                            // Output header for the first date or when date changes
                            if ($currentDate !== $p->date) {
                                // If not the first record, output subtotal for the PREVIOUS date first
                                if ($currentDate !== null) {
                                    echo '<tr class="table-info fw-bold subtotal-row">';
                                    echo '<td data-order="' . Html::encode($currentDate) . '">' . Yii::$app->formatter->asDate($currentDate, 'dd/MM/yyyy') . '</td>';
                                    echo '<td data-order="ZZZ"></td>';
                                    echo '<td class="text-end">รวมวันที่ ' . Yii::$app->formatter->asDate($currentDate, 'dd/MM/yyyy') . ' (' . $dayCount . ' รายการ)</td>';
                                    echo '<td class="text-end">' . number_format($dayTotalWeight, 2) . '</td>';
                                    echo '<td></td>';
                                    echo '<td class="text-end">' . number_format($dayTotalDryWeight, 2) . '</td>';
                                    echo '<td></td>';
                                    echo '<td class="text-end">' . number_format($dayTotalAmount, 2) . '</td>';
                                    echo '</tr>';
                                    
                                    // Reset day totals
                                    $dayTotalWeight = 0;
                                    $dayTotalDryWeight = 0;
                                    $dayTotalAmount = 0;
                                    $dayCount = 0;
                                }

                                // Output HEADER for the NEW date
                                echo '<tr class="table-dark group-header-row">';
                                echo '<td data-order="' . Html::encode($p->date) . '">' . Yii::$app->formatter->asDate($p->date, 'dd/MM/yyyy') . '</td>';
                                echo '<td data-order="000" colspan="2"><strong>รายการรับซื้อ ประจำวันที่ ' . Yii::$app->formatter->asDate($p->date, 'dd/MM/yyyy') . '</strong></td>';
                                // DataTables needs 8 cells even with colspan in previous cell if we want to avoid warnings easily, 
                                // but we skip those with empty tds or hidden? Better to be explicit for DT.
                                echo '<td style="display:none"></td>'; 
                                echo '<td></td><td></td><td></td><td></td><td></td>';
                                echo '</tr>';
                            }
                            
                            $currentDate = $p->date;
                            $dayTotalWeight += $p->weight;
                            $dayTotalDryWeight += $p->dry_weight;
                            $dayTotalAmount += $p->total_amount;
                            $dayCount++;
                        ?>
                            <tr class="data-row">
                                <td data-order="<?= Html::encode($p->date) ?>"><?= Yii::$app->formatter->asDate($p->date, 'dd/MM/yyyy') ?></td>
                                <td><strong><?= Html::encode($p->members->memberid ?? '-') ?></strong></td>
                                <td><?= Html::encode(($p->members->pername ?? '') . ' ' . ($p->members->name ?? '') . ' ' . ($p->members->surname ?? '')) ?></td>
                                <td class="text-end"><?= number_format($p->weight, 2) ?></td>
                                <td class="text-end"><?= number_format($p->percentage, 2) ?></td>
                                <td class="text-end"><?= number_format($p->dry_weight, 2) ?></td>
                                <td class="text-end"><?= number_format($p->price_per_kg, 2) ?></td>
                                <td class="text-end"><?= number_format($p->total_amount, 2) ?></td>
                            </tr>
                        <?php 
                        endforeach; 

                        // Output subtotal for the last date
                        if ($currentDate !== null) {
                            echo '<tr class="table-info fw-bold subtotal-row">';
                            echo '<td data-order="' . Html::encode($currentDate) . '">' . Yii::$app->formatter->asDate($currentDate, 'dd/MM/yyyy') . '</td>';
                            echo '<td data-order="ZZZ"></td>';
                            echo '<td class="text-end">รวมวันที่ ' . Yii::$app->formatter->asDate($currentDate, 'dd/MM/yyyy') . ' (' . $dayCount . ' รายการ)</td>';
                            echo '<td class="text-end">' . number_format($dayTotalWeight, 2) . '</td>';
                            echo '<td></td>';
                            echo '<td class="text-end">' . number_format($dayTotalDryWeight, 2) . '</td>';
                            echo '<td></td>';
                            echo '<td class="text-end">' . number_format($dayTotalAmount, 2) . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Dependencies -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#purchaseLogTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json'
        },
        dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i>ส่งออก Excel',
                className: 'btn btn-success btn-sm',
                title: 'สมุดรับซื้อน้ำยาง_' + '<?= $sdate ?>' + '_ถึง_' + '<?= $edate ?>',
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all'
                    }
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i>พิมพ์',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: ':visible',
                    modifier: {
                        page: 'all'
                    }
                }
            }
        ],
        // Rely on SQL and PHP sorting to keep Header -> Items -> Subtotal grouping
        ordering: false,
        pageLength: 100, 
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด"]]
    });
});
</script>
