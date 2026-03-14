<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Receipt $receipt */
/** @var \app\models\Receipt[] $otherReceipts */

$this->title = 'จัดการรายการในใบเสร็จ';
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f4f7f6;
            padding: 20px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        .header-card {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: #212529;
            padding: 20px;
        }
        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }
        .btn-move {
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-move:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,123,255,0.2);
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="card header-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="bi bi-list-task mr-2"></i><?= Html::encode($this->title) ?></h4>
                <p class="mb-0"><strong>ใบเสร็จเลขที่:</strong> <?= Html::encode($receipt->receipt_no) ?> | <strong>สมาชิก:</strong> <?= Html::encode($receipt->member->fullname) ?></p>
            </div>
            <button onclick="window.close()" class="btn btn-dark btn-sm"><i class="bi bi-x-lg mr-1"></i>ปิดหน้าต่าง</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>เลขที่รายการ</th>
                            <th class="text-right">น้ำหนัก</th>
                            <th class="text-right">ยอดรวม</th>
                            <th width="300">ย้ายไปยังใบเสร็จอื่น</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($receipt->purchases)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">ไม่มีรายการในใบเสร็จนี้</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($receipt->purchases as $item): ?>
                                <tr>
                                    <td><?= Yii::$app->helpers->dateThai($item->date) ?></td>
                                    <td><span class="badge badge-info"><?= Html::encode($item->receipt_number) ?></span></td>
                                    <td class="text-right"><?= number_format($item->weight, 2) ?></td>
                                    <td class="text-right font-weight-bold text-success"><?= number_format($item->total_amount, 2) ?></td>
                                    <td>
                                        <?php if (!empty($otherReceipts)): ?>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control move-select" id="target_<?= $item->id ?>">
                                                    <option value="">-- เลือกใบเสร็จปลายทาง --</option>
                                                    <?php foreach ($otherReceipts as $opt): ?>
                                                        <option value="<?= $opt->id ?>">
                                                            <?= Html::encode($opt->receipt_no) ?> - <?= Html::encode($opt->member->fullname) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary btn-move" onclick="moveItem(<?= $item->id ?>)">ย้าย</button>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">ไม่มีใบเสร็จอื่นของสมาชิกนี้</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>

<script>
    function moveItem(purchaseId) {
        const targetId = document.getElementById('target_' + purchaseId).value;
        if (!targetId) {
            alert('กรุณาเลือกใบเสร็จปลายทาง');
            return;
        }

        if (!confirm('ยืนยันการย้ายรายการไปยังใบเสร็จที่เลือก?')) {
            return;
        }

        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        $.ajax({
            url: '<?= Url::to(['purchases/move-purchase']) ?>',
            type: 'POST',
            data: {
                purchase_id: purchaseId,
                target_receipt_id: targetId,
                '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
            },
            success: function(data) {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                    // แจ้งหน้าหลักให้อัพเดท
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                } else {
                    alert(data.message);
                    btn.disabled = false;
                    btn.innerHTML = 'ย้าย';
                }
            },
            error: function() {
                alert('เกิดข้อผิดพลาดในการสื่อสารกับเซิร์ฟเวอร์');
                btn.disabled = false;
                btn.innerHTML = 'ย้าย';
            }
        });
    }
</script>

</body>
</html>
