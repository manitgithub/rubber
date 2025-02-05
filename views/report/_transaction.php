<?php

use app\models\Employee;
use  yii\helpers\Url;

?>
<table class="table table-sm table-hover datatable">
    <thead>
        <tr>
            <th>เอกสาร/วันที่</th>
            <th width="10%">จำนวน</th>
            <th width="10%">จำนวนคงคลัง</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        foreach ($model as $item) {
            if ($item['status'] == '1') {
                if ($item['type'] == 'S' || $item['type'] == 'M') {
                    $total = $total + $item['qty'];
                } else if ($item['type'] == 'R') {
                    $total = $total - $item['qty'];
                }
            }

            if ($item['type'] == 'S') {
                $partner = findPartner($modelPartner, $item['partner_id']);
            } else {
                $partner = findDepartment($modelDepartment, $item['partner_id']);
            }

            $department = findDepartment($modelDepartment, $item['department_id']);
        ?>
        <?php
            if ($item['type'] == 'M') {
                $link = Url::to(['change-stock/update', 'id' => $item['id']]);
            }
            if ($item['type'] == 'R') {
                $link = Url::to(['requisition/update', 'id' => $item['id']]);
            }
            if ($item['type'] == 'T') {
                $link = Url::to(['change-sku/update', 'id' => $item['id']]);
            }
            if ($item['type'] == 'S') {
                $link = Url::to(['goods-receipts/update', 'id' => $item['id']]);
            }
            if ($item['type'] == 'P') {
                $link = Url::to(['purchase-orders/update', 'id' => $item['id']]);
            }
            ?>

        <tr onclick="window.open('<?= $link ?>', '_blank');"
            class="<?= $item['status'] == 0 ? 'bg-warning text-white' : '' ?>">
            <td class="align-middle">
                <div class="row align-items-center">
                    <div class="col align-self-center pr-0 ">
                        <h6 class="font-weight-normal mb-0 <?= $item['type'] == 'M' ? 'text-danger' : '' ?>">
                            <?= $item['date'] ?></h6>
                        <p class="small <?= $item['type'] == 'M' ? 'text-danger' : 'text-muted' ?> mb-0">
                            <?= $item['billno'] ?> : <?= $item['name'] ?> <i>(<?= $partner ?> ไปยัง
                                <?= $department ?>)</i></p>
                        <p class="small">
                            <?php if ($item['type'] != 'P') { ?>
                            <?php if ($item['status'] == 1) { ?>
                            <span class="text-success mb-0">เสร็จสิ้น</span>
                            <?php } else if ($item['status'] == 0) { ?>
                            <span class="text-danger mb-0">ยกเลิก</span>
                            <?php } else if ($item['status'] == 2) { ?>
                            <span class="text-warning mb-0">รอดำเนินการ</span>
                            <?php } ?>
                            <?php } else { ?>
                            <?php if ($item['status'] == 1) { ?>
                            <span class="text-warning mb-0">รอรับสินค้า</span>
                            <?php } else if ($item['status'] == 0) { ?>
                            <span class="text-danger mb-0">ยกเลิก</span>
                            <?php } else if ($item['status'] == 2) { ?>
                            <span class="text-success mb-0">เสร็จสิ้น</span>
                            <?php } ?>
                            <?php } ?>
                            : <span
                                class="text-mute small text-secondary"><?= Employee::findOne(['id' => $item['updated_user']])->fullname ?></span>
                        </p>
                    </div>
                </div>
            </td>

            <td>
                <?php if ($item['type'] != 'M') { ?>
                <div class="row align-items-center">
                    <div class="col align-self-center pr-0">
                        <h6
                            class="mb-0 <?= $item['status'] != '1' ? 'text-muted' : '' ?> <?= $item['type'] == 'P' ? 'text-muted' : '' ?> ">
                            <?= $item['type'] == 'R' ? '-' : '' ?><?= $item['qty_uom'] ?></h6>
                        <p class="small text-muted"><?= $item['uom_name'] ?></p>
                    </div>
                </div>
                <?php } ?>
            </td>
            <td class="align-middle text-center">
                <?php if ($item['status'] == '1' and $item['type'] != 'P') { ?>
                <div class="row align-items-center">
                    <div class="col align-self-center pr-0">
                        <h6 class="mb-0 <?= $item['type'] == 'M' ? 'text-danger' : '' ?>"><?= $total ?></h6>
                        <p class="small <?= $item['type'] == 'M' ? 'text-danger' : 'text-muted' ?>">
                            <?= $itemDetail->uom->uom_name ?></p>
                    </div>
                </div>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>