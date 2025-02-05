<?php

use app\models\Checkin;
use app\models\Leave;

$appoveLeave = Leave::find()->where(['status' => 0, 'bossid' => Yii::$app->user->id])->all();
$appoveCheckin = Checkin::find()->where(['status' => 2, 'appverid' => Yii::$app->user->id])->all();
?>
<div class="row px-0">
    <div class="col-12 px-0">
        <div class="list-group list-group-flush ">
            <?php foreach ($appoveLeave as $leave) { ?>
                <a class="list-group-item border-top active text-dark" href="/leave/bossapprove">
                    <div class="row">
                        <div class="col-auto align-self-center">
                            <i class="material-icons text-template-primary">hotel</i>
                        </div>
                        <div class="col pl-0">
                            <div class="row mb-1">
                                <div class="col">
                                    <p class="mb-0">มีรายการขออนุมัติลา</p>
                                </div>
                                <div class="col-auto pl-0">
                                    <p class="small text-mute text-trucated mt-1">
                                        <?= Yii::$app->helpers->datetimeThai($leave->created_at) ?> น.</p>
                                </div>
                            </div>
                            <p class="small text-mute">
                                <?= $leave->user->fullname ?> ได้ขอลา <?= $leave->leavetype->name ?>
                                วันที่
                                <?= $leave->startdate == $leave->enddate ? Yii::$app->helpers->dateThai($leave->startdate) : Yii::$app->helpers->dateThai($leave->startdate) . ' ถึง ' . Yii::$app->helpers->dateThai($leave->enddate) ?>
                                (<?= $leave->day ?> วัน)
                                <br> <?= $leave->note ?>

                        </div>

                    </div>
                </a>
            <?php } ?>
            <?php foreach ($appoveCheckin as $checkin) { ?>
                <a class="list-group-item border-top active text-dark" href="/checkin/bossapprove">
                    <div class="row">
                        <div class="col-auto align-self-center">
                            <i class="material-icons text-template-primary">pin_drop</i>
                        </div>
                        <div class="col pl-0">
                            <div class="row mb-1">
                                <div class="col">
                                    <p class="mb-0">มีรายการขออนุมัตินอกสถานที่</p>
                                </div>
                                <div class="col-auto pl-0">
                                    <p class="small text-mute text-trucated mt-1">
                                        <?= Yii::$app->helpers->datetimeThai($checkin->datetime) ?> น.</p>
                                </div>
                            </div>
                            <p class="small text-mute">
                                <?= $checkin->user->fullname ?> ได้ขออนุมัติ <?= $checkin->type == 1 ? 'เข้า' : 'ออก' ?>
                                <?= $checkin->note ?></p>
                        </div>
                    </div>
                </a>
            <?php } ?>

        </div>

    </div>
</div>