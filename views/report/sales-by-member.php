<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Members[] $members */
/** @var string|null $member_id */
/** @var string $sdate */
/** @var string $edate */
/** @var string $view_mode */
/** @var app\models\Purchases[] $purchases */
/** @var array $monthly_summary */
/** @var float $total_weight */
/** @var float $total_dry_weight */
/** @var float $total_amount */
/** @var int $total_count */

$this->title = 'รายงานการขายรายบุคคล';

// Get selected member info
$selected_member = null;
if ($member_id) {
    foreach ($members as $m) {
        if ($m->id == $member_id) {
            $selected_member = $m;
            break;
        }
    }
}
?>

<style>
    .report-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 15px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.2);
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

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        padding: 0.6rem 1rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #f5576c;
        box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.15);
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

    .view-mode-toggle {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .view-mode-toggle .form-check {
        margin: 0;
    }

    .view-mode-toggle .form-check-input:checked {
        background-color: #f5576c;
        border-color: #f5576c;
    }

    .data-summary {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #f5576c;
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

    .member-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.2rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .member-info-card h6 {
        margin: 0 0 0.5rem 0;
        font-weight: 600;
    }

    .member-info-card p {
        margin: 0;
        opacity: 0.95;
    }
</style>

<div class="report-header">
    <h5><i class="bi bi-person-badge"></i> รายงานการขายรายบุคคล</h5>
</div>

<div class="search-card">
    <form method="get" action="<?= Url::to(['report/sales-by-member']) ?>">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">เลือกสมาชิก</label>
                <select name="member_id" class="form-select" required>
                    <option value="">-- เลือกสมาชิก --</option>
                    <?php foreach ($members as $m): ?>
                        <option value="<?= $m->id ?>" <?= $member_id == $m->id ? 'selected' : '' ?>>
                            <?= Html::encode($m->fullname) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">วันที่เริ่มต้น</label>
                <input type="date" name="sdate" value="<?= $sdate ?>" class="form-control datepicker" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">วันที่สิ้นสุด</label>
                <input type="date" name="edate" value="<?= $edate ?>" class="form-control datepicker" required>
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
                        <input class="form-check-input" type="radio" name="view_mode" id="viewMonthly" value="monthly"
                            <?= $view_mode === 'monthly' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="viewMonthly">
                            สรุปรายเดือน
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
                <?php if ($member_id && ($total_count > 0)): ?>
                    <button type="button" class="btn btn-primary" onclick="printReport()">
                        <i class="bi bi-printer"></i> พิมพ์รายงาน
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?php if ($member_id): ?>
    <?php if ($selected_member): ?>
        <div class="member-info-card">
            <h6><i class="bi bi-person-circle"></i> ข้อมูลสมาชิก</h6>
            <p>
                <strong>ชื่อ:</strong> <?= Html::encode($selected_member->fullname2) ?> |
                <strong>รหัสสมาชิก:</strong> <?= Html::encode($selected_member->memberid) ?> |
                <strong>โทร:</strong> <?= Html::encode($selected_member->phone) ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if ($total_count > 0): ?>
        <div class="data-summary">
            <p class="text-muted">
                แสดงรายงานการขาย<?= $view_mode === 'daily' ? 'รายวัน' : 'สรุปรายเดือน' ?>
                ตั้งแต่วันที่ <?= Yii::$app->helpers->dateThai($sdate) ?>
                ถึง <?= Yii::$app->helpers->dateThai($edate) ?>
                <br>
                <strong>จำนวนรายการทั้งหมด: <?= number_format($total_count) ?> รายการ</strong>
            </p>
        </div>

        <?php if ($view_mode === 'daily'): ?>
            <!-- Daily View -->
            <table class="table table-striped table-bordered table-hover datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>วันที่</th>
                        <th>เลขที่รายการ</th>
                        <th>นน.ยางสด (กก.)</th>
                        <th>%DRC</th>
                        <th>นน.ยางแห้ง (กก.)</th>
                        <th>ราคา/กก.</th>
                        <th>ยอดรวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchases as $i => $p): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td class="text-center"><?= Yii::$app->helpers->dateThai($p->date) ?></td>
                            <td class="text-center"><?= Html::encode($p->receipt_number) ?></td>
                            <td class="text-end"><?= number_format($p->weight, 2) ?></td>
                            <td class="text-end"><?= number_format($p->percentage, 2) ?></td>
                            <td class="text-end"><?= number_format($p->dry_weight, 1) ?></td>
                            <td class="text-end"><?= number_format($p->price_per_kg, 2) ?></td>
                            <td class="text-end"><?= number_format($p->total_amount, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-warning">
                        <td colspan="3" class="text-end"><strong>รวมทั้งหมด</strong></td>
                        <td class="text-end"><strong><?= number_format($total_weight, 2) ?></strong></td>
                        <td class="text-end">
                            <strong><?= $total_weight > 0 ? number_format(($total_dry_weight / $total_weight) * 100, 2) : '0.00' ?></strong>
                        </td>
                        <td class="text-end"><strong><?= number_format($total_dry_weight, 1) ?></strong></td>
                        <td class="text-end">
                            <strong><?= $total_count > 0 ? number_format($total_amount / $total_dry_weight, 2) : '0.00' ?></strong>
                        </td>
                        <td class="text-end"><strong><?= number_format($total_amount, 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <!-- Monthly View -->
            <table class="table table-striped table-bordered table-hover datatable">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>เดือน/ปี</th>
                        <th>จำนวนครั้ง</th>
                        <th>นน.ยางสดรวม (กก.)</th>
                        <th>นน.ยางแห้งรวม (กก.)</th>
                        <th>ราคาเฉลี่ย/กก.</th>
                        <th>ยอดเงินรวม (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthly_summary as $i => $row): ?>
                        <?php
                        // Parse month (format: YYYY-MM)
                        $monthParts = explode('-', $row['month']);
                        $year = (int) $monthParts[0];
                        $month = (int) $monthParts[1];
                        $thaiYear = $year + 543;
                        $thaiMonths = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
                        $monthDisplay = $thaiMonths[$month] . ' ' . $thaiYear;
                        ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td class="text-center"><?= $monthDisplay ?></td>
                            <td class="text-center"><?= number_format($row['count']) ?></td>
                            <td class="text-end"><?= number_format($row['total_weight'], 2) ?></td>
                            <td class="text-end"><?= number_format($row['total_dry_weight'], 1) ?></td>
                            <td class="text-end"><?= number_format($row['avg_price'], 2) ?></td>
                            <td class="text-end"><?= number_format($row['total_amount'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-warning">
                        <td colspan="2" class="text-end"><strong>รวมทั้งหมด</strong></td>
                        <td class="text-center"><strong><?= number_format($total_count) ?></strong></td>
                        <td class="text-end"><strong><?= number_format($total_weight, 2) ?></strong></td>
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
            ไม่มีรายการขายของสมาชิกท่านนี้ในช่วงวันที่ที่เลือก
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info text-center" role="alert">
        <i class="bi bi-info-circle"></i>
        <strong>กรุณาเลือกสมาชิก</strong><br>
        เลือกสมาชิกและช่วงวันที่เพื่อดูรายงานการขาย
    </div>
<?php endif; ?>

<!-- Print-only content -->
<div id="printContent" class="d-none">
    <div class="print-header text-center mb-4">
        <h2>สหกรณ์การกองทุนยางฉลองน้ำขาวพัฒนา จำกัด</h2>
        <h2>รายงานการขาย<?= $view_mode === 'daily' ? 'รายวัน' : 'สรุปรายเดือน' ?></h2>
        <?php if ($selected_member): ?>
            <h3>สมาชิก: <?= Html::encode($selected_member->fullname2) ?> (<?= Html::encode($selected_member->memberid) ?>)
            </h3>
        <?php endif; ?>
        <h3>ช่วงวันที่ <?= Yii::$app->helpers->dateThai($sdate) ?> ถึง <?= Yii::$app->helpers->dateThai($edate) ?></h3>
        <hr style="border: 1px solid #000; margin: 10px 0;">
    </div>

    <?php if ($member_id && $total_count > 0): ?>
        <?php if ($view_mode === 'daily'): ?>
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">ลำดับ</th>
                        <th style="width: 12%;">วันที่</th>
                        <th style="width: 15%;">เลขที่รายการ</th>
                        <th style="width: 12%;">นน.ยางสด(กก.)</th>
                        <th style="width: 8%;">%DRC</th>
                        <th style="width: 12%;">นน.ยางแห้ง(กก.)</th>
                        <th style="width: 12%;">ราคา/กก.</th>
                        <th style="width: 12%;">ยอดรวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchases as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= Yii::$app->helpers->dateThai($p->date) ?></td>
                            <td><?= Html::encode($p->receipt_number) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->weight, 2) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->percentage, 2) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->dry_weight, 1) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->price_per_kg, 2) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($p->total_amount, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: center; font-weight: bold;">รวมทั้งหมด</td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_weight, 2) ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= $total_weight > 0 ? number_format(($total_dry_weight / $total_weight) * 100, 2) : '0.00' ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_dry_weight, 1) ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= $total_count > 0 ? number_format($total_amount / $total_dry_weight, 2) : '0.00' ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_amount, 2) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">ลำดับ</th>
                        <th style="width: 15%;">เดือน/ปี</th>
                        <th style="width: 12%;">จำนวนครั้ง</th>
                        <th style="width: 15%;">นน.ยางสดรวม(กก.)</th>
                        <th style="width: 15%;">นน.ยางแห้งรวม(กก.)</th>
                        <th style="width: 15%;">ราคาเฉลี่ย/กก.</th>
                        <th style="width: 15%;">ยอดเงินรวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthly_summary as $i => $row): ?>
                        <?php
                        $monthParts = explode('-', $row['month']);
                        $year = (int) $monthParts[0];
                        $month = (int) $monthParts[1];
                        $thaiYear = $year + 543;
                        $thaiMonths = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                        $monthDisplay = $thaiMonths[$month] . ' ' . $thaiYear;
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $monthDisplay ?></td>
                            <td style="text-align: center;"><?= number_format($row['count']) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($row['total_weight'], 2) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($row['total_dry_weight'], 1) ?>
                            </td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($row['avg_price'], 2) ?></td>
                            <td style="text-align: right; padding-right: 5px;"><?= number_format($row['total_amount'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="2" style="text-align: center; font-weight: bold;">รวมทั้งหมด</td>
                        <td style="text-align: center; font-weight: bold;"><?= number_format($total_count) ?></td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_weight, 2) ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_dry_weight, 1) ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= $total_dry_weight > 0 ? number_format($total_amount / $total_dry_weight, 2) : '0.00' ?>
                        </td>
                        <td style="text-align: right; padding-right: 5px; font-weight: bold;">
                            <?= number_format($total_amount, 2) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
    @media print {
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

        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        .print-header h2,
        .print-header h3 {
            font-size: 18px;
            margin-bottom: 8px;
            font-weight: bold;
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
        }

        .print-table th {
            background-color: transparent !important;
            font-weight: bold;
            border: 2px solid #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .total-row {
            background-color: transparent !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .total-row td {
            border: 2px solid #000;
        }

        .d-none {
            display: none !important;
        }
    }

    #printContent {
        display: none;
    }
</style>

<script>
    function printReport() {
        document.getElementById('printContent').style.display = 'block';
        window.print();
        setTimeout(function () {
            document.getElementById('printContent').style.display = 'none';
        }, 1000);
    }
</script>