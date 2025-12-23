<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var string $date */
/** @var string $view_mode */
/** @var app\models\Purchases[] $purchases */
/** @var array $member_summary */
/** @var float $total_weight */
/** @var float $total_dry_weight */
/** @var float $total_amount */
/** @var int $total_count */


if (isset($_GET['sdate']) && !empty($_GET['sdate'])) {
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
if ($sdate == $edate) {
    $showday = false;
} else {
    $showday = true;
}

$this->title = $showday ? 'รายงานการรับซื้อน้ำยาง <br> วันที่ ' . Yii::$app->helpers->dateThai($sdate) . ' ถึง ' . Yii::$app->helpers->dateThai($edate) : 'รายงานการรับซื้อน้ำยาง <br> วันที่ ' . Yii::$app->helpers->dateThai($sdate);
// Sort purchases and member_summary by memberid so the table displays in memberid order
// Safe-guard: if $purchases or $member_summary are not arrays, try to cast them or skip
if (isset($purchases) && is_iterable($purchases)) {
    // Ensure we have a plain array for usort
    $purchases = is_array($purchases) ? $purchases : iterator_to_array($purchases);
    usort($purchases, function ($a, $b) {
        $ida = isset($a->members->memberid) ? (string)$a->members->memberid : '';
        $idb = isset($b->members->memberid) ? (string)$b->members->memberid : '';
        // numeric-like ids still compare correctly as strings; keeps leading zeros if any
        return strcmp($ida, $idb);
    });
}

if (isset($member_summary) && is_array($member_summary)) {
    usort($member_summary, function ($a, $b) {
        $ma = isset($a['member']->memberid) ? (string)$a['member']->memberid : '';
        $mb = isset($b['member']->memberid) ? (string)$b['member']->memberid : '';
        return strcmp($ma, $mb);
    });
}

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
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .report-header i {
        margin-right: 0.5rem;
        font-size: 1.4rem;
    }

    .search-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
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

    .view-mode-toggle {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .view-mode-toggle .form-check {
        margin: 0;
    }

    .view-mode-toggle .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
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

                <div class="col-md-4">
                    <label class="form-label">รูปแบบการแสดงผล</label>
                    <div class="view-mode-toggle">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="view_mode" id="viewDaily" value="daily"
                                <?= $view_mode === 'daily' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="viewDaily">
                                รายวัน
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="view_mode" id="viewSummary"
                                value="summary" <?= $view_mode === 'summary' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="viewSummary">
                                สรุปรายบุคคล
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-search"></i> ค้นหา
                    </button>
                    <?php if ($total_count > 0): ?>
                        <button type="button" class="btn btn-primary" onclick="printReport()">
                            <i class="bi bi-printer"></i> พิมพ์รายงาน
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

</div>



<?php if ($total_count > 0): ?>
    <div class="data-summary">
        <p class="text-muted">
            <?= $showday ? 'รายงานการรับซื้อน้ำยาง' . ($view_mode === 'summary' ? ' (สรุปรายบุคคล)' : '') . ' <br>วันที่ ' . Yii::$app->helpers->dateThai($sdate) . ' ถึง ' . Yii::$app->helpers->dateThai($edate) : 'แสดงรายงานการรับซื้อน้ำยาง' . ($view_mode === 'summary' ? ' (สรุปรายบุคคล)' : '') . ' วันที่ ' . Yii::$app->helpers->dateThai($sdate) ?>
            <br>
            <strong>จำนวนรายการทั้งหมด: <?= number_format($total_count) ?> รายการ</strong>
        </p>
    </div>

    <?php if ($view_mode === 'daily'): ?>
        <!-- Daily View -->
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
                        <?= $showday ? '<td>' . Yii::$app->helpers->dateThai($p->date) . '</td>' : '' ?>
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
        <!-- Individual Summary View -->
        <table class="table datatable table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อ-สกุล</th>
                    <th>เลขทะเบียน</th>
                    <th>จำนวนครั้ง</th>
                    <th>นน.ยางสดรวม (กก.)</th>
                    <th>%DRC เฉลี่ย</th>
                    <th>นน.ยางแห้งรวม (กก.)</th>
                    <th>ราคาเฉลี่ย/กก.</th>
                    <th>ยอดเงินรวม (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($member_summary as $i => $row): ?>
                    <?php if ($row['member']): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td><?= Html::encode($row['member']->fullname2) ?></td>
                            <td class="text-center"><?= Html::encode($row['member']->memberid) ?></td>
                            <td class="text-center"><?= number_format($row['count']) ?></td>
                            <td class="text-end"><?= number_format($row['total_weight'], 2) ?></td>
                            <td class="text-end"><?= number_format($row['avg_percentage'], 2) ?></td>
                            <td class="text-end"><?= number_format($row['total_dry_weight'], 1) ?></td>
                            <td class="text-end"><?= number_format($row['avg_price'], 2) ?></td>
                            <td class="text-end"><?= number_format($row['total_amount'], 2) ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-warning">
                    <td colspan="3" class="text-end"><strong>รวมทั้งหมด</strong></td>
                    <td class="text-center"><strong><?= number_format($total_count) ?></strong></td>
                    <td class="text-end"><strong><?= number_format($total_weight, 2) ?></strong></td>
                    <td class="text-end">
                        <strong><?= $total_weight > 0 ? number_format(($total_dry_weight / $total_weight) * 100, 2) : '0.00' ?></strong>
                    </td>
                    <td class="text-end"><strong><?= number_format($total_dry_weight, 1) ?></strong></td>
                    <td class="text-end">
                        <strong><?= $total_dry_weight > 0 ? number_format($total_amount / $total_dry_weight, 2) : '0.00' ?></strong>
                    </td>
                    <td class="text-end"><strong><?= number_format($total_amount, 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
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
        <h2>สหกรณ์กองทุนยางฉลองน้ำขาวพัฒนา จำกัด</h2>
        <h2><?= $view_mode === 'summary' ? 'รายงานสรุปการรับซื้อน้ำยางรายบุคคล' : 'ใบรับน้ำยาง' ?></h2>

        <h2>รับซื้อน้ำยางสดประจำ<?= $showday ? 'วันที่ ' . Yii::$app->helpers->dateThai($sdate) . ' ถึง ' . Yii::$app->helpers->dateThai($edate) : 'วันที่ ' . Yii::$app->helpers->dateThai($sdate) ?>
        </h2>
        <hr style="border: 1px solid #000; margin: 10px 0;">
    </div>

    <?php if ($total_count > 0): ?>
        <?php if ($view_mode === 'daily'): ?>
            <!-- Daily Print Table -->
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">ลำดับ</th>
                        <?= $showday ? '<th style="width: 12%;">วันที่</th>' : '' ?>
                        <th style="width: 30%;">ชื่อ-สกุล</th>
                        <th style="width: 8%;">เลขทะเบียน</th>
                        <th style="width: 8%;">นน.ยางสด(กก.)</th>
                        <th style="width: 6%;">%DRC</th>
                        <th style="width: 8%;">นน.ยางแห้ง(กก.)</th>
                        <th style="width: 8%;">ราคา/กก.</th>
                        <th style="width: 8%;">ยอดรวม</th>
                        <?= $showday ? '' : '<td style="width: 12%;"> ลงลายมือชื่อ</td>' ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchases as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <?= $showday ? '<td>' . Yii::$app->helpers->dateThai($p->date) . '</td>' : '' ?>
                            <td style="text-align: left; padding-left: 5px; font-size: 20px; ">
                                <?= Html::encode($p->members->fullname2) ?></td>
                            <td><?= Html::encode($p->members->memberid) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->weight, 2) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->percentage, 2) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->dry_weight, 1) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->price_per_kg, 2) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->total_amount, 2) ?></td>
                            <?= $showday ? '' : '<td></td>' ?>
                        </tr>
                    <?php endforeach ?>

                    <?php
                    // เพิ่มแถวว่างเพื่อให้เหมือนกับแบบฟอร์ม
                    $emptyRows = 20 - count($purchases);
                    if ($emptyRows > 0) {
                        for ($i = 0; $i < $emptyRows; $i++) {
                            echo '<tr>';
                            echo '<td>&nbsp;</td>';
                            if ($showday)
                                echo '<td>&nbsp;</td>';
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
                    <tr class="total-row">
                        <td colspan="<?= $showday ? '4' : '3' ?>" style="text-align: center; font-weight: bold;">รวม</td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_weight, 2) ?></td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= $total_weight > 0 ? number_format($total_dry_weight * 100 / $total_weight, 2) : '0.00' ?></td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_dry_weight, 1) ?></td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= count($purchases) > 0 ? number_format(array_sum(array_column($purchases, 'price_per_kg')) / count($purchases), 2) : '0.00' ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_amount, 2) ?></td>
                        <?= $showday ? '' : '<td style="width: 12%;"> </td>' ?>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <!-- Summary Print Table -->
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">ลำดับ</th>
                        <th style="width: 25%;">ชื่อ-สกุล</th>
                        <th style="width: 10%;">เลขทะเบียน</th>
                        <th style="width: 8%;">จำนวนครั้ง</th>
                        <th style="width: 12%;">นน.ยางสดรวม(กก.)</th>
                        <th style="width: 8%;">%DRC เฉลี่ย</th>
                        <th style="width: 12%;">นน.ยางแห้งรวม(กก.)</th>
                        <th style="width: 10%;">ราคาเฉลี่ย/กก.</th>
                        <th style="width: 10%;">ยอดเงินรวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($member_summary as $i => $row): ?>
                        <?php if ($row['member']): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td style="text-align: left; padding-left: 5px; font-size: 18px;">
                                    <?= Html::encode($row['member']->fullname2) ?></td>
                                <td style="text-align: center;"><?= Html::encode($row['member']->memberid) ?></td>
                                <td style="text-align: center;"><?= number_format($row['count']) ?></td>
                                <td style="text-align: right; padding-right: 5px;"><?= number_format($row['total_weight'], 2) ?></td>
                                <td style="text-align: right; padding-right: 5px;"><?= number_format($row['avg_percentage'], 2) ?></td>
                                <td style="text-align: right; padding-right: 5px;"><?= number_format($row['total_dry_weight'], 1) ?>
                                </td>
                                <td style="text-align: right; padding-right: 5px;"><?= number_format($row['avg_price'], 2) ?></td>
                                <td style="text-align: right; padding-right: 5px;"><?= number_format($row['total_amount'], 2) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: center; font-weight: bold;">รวมทั้งหมด</td>
                        <td style="text-align: center; font-weight: bold;"><?= number_format($total_count) ?></td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_weight, 2) ?></td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= $total_weight > 0 ? number_format(($total_dry_weight / $total_weight) * 100, 2) : '0.00' ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_dry_weight, 1) ?></td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= $total_dry_weight > 0 ? number_format($total_amount / $total_dry_weight, 2) : '0.00' ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_amount, 2) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($view_mode === 'daily'): ?>
            <div class="signature-section">
                <div class="signature-left">
                    <p style="font-size: 18px;">ลงชื่อ ................................................ </p>
                    <p style="margin-left: 50px; margin-top: 8px; font-size: 20px; font-weight: bold;">( นางวัญเพ็ญ ดำเพ็ง )</p>
                    <p style="margin-left: 50px; font-size: 16px;">ผู้รับน้ำยาง</p>
                </div>
                <div class="signature-right">
                    <p style="font-size: 18px;">ลงชื่อ ................................................ </p>
                    <p style="margin-left: 50px; margin-top: 8px; font-size: 20px; font-weight: bold;">( นายสุภาพ ใจห้าว )</p>
                    <p style="margin-left: 50px; font-size: 16px;">เหรัญญิก/รักษาการในตำแหน่งประธานฯ</p>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
    @media print {

        /* Hide everything except print content */
        body * {
            visibility: hidden;
        }

        #printContent,
        #printContent * {
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

        /* ปรับการจัดรูปแบบของช่องรายชื่อ */
        .print-table td:nth-child(2),
        .print-table td:nth-child(3) {
            text-align: left;
            padding-left: 8px;
            word-wrap: break-word;
            white-space: normal;
        }

        /* เพิ่มขนาดฟอนต์สำหรับชื่อในตาราง */
        .print-table td:nth-child(3) {
            font-size: 20px !important;
            font-weight: bold !important;
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
            margin-top: 5px;
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            padding: 20px 0;
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
        setTimeout(function () {
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
                
                /* ปรับการจัดรูปแบบของช่องรายชื่อใน JavaScript */
                .print-table td:nth-child(2),
                .print-table td:nth-child(3) {
                    text-align: left;
                    padding-left: 8px;
                    word-wrap: break-word;
                    white-space: normal;
                }
                
                /* เพิ่มขนาดฟอนต์สำหรับชื่อในตาราง JavaScript */
                .print-table td:nth-child(3) {
                    font-size: 20px !important;
                    font-weight: bold !important;
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
                    margin-top: 15px;
                    display: flex;
                    justify-content: space-between;
                    font-size: 18px;
                    padding: 20px 0;
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
                <h3><?= $showday ? 'วันที่ ' . Yii::$app->helpers->dateThai($sdate) . ' ถึง ' . Yii::$app->helpers->dateThai($edate) : 'วันที่ ' . Yii::$app->helpers->dateThai($sdate) ?></h3>
                <hr style="border: 1px solid #000; margin: 10px 0;">
            </div>
            ${printContent}
        </body>
        </html>
    `);

        printWindow.document.close();
        printWindow.focus();

        setTimeout(function () {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>