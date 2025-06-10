<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\participants $model */

$this->title = $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="participants-view">

    <div class="running-create">
        <div class="card shadow-sm rounded-0">
            <div class="card-header rounded-0">
                <h4><?= Html::encode($this->title) ?>
                    <div class="float-right">
                        <?= Html::a('กลับ', ['running/view?id=' . $model->runningid], ['class' => 'btn btn-secondary']) ?>
                        <?php if ($model->status == '1') { ?>
                        <?= Html::a('พิมพ์', ['print', 'id' => $model->id], ['class' => 'btn btn-success', 'target' => '_blank','onclick' => 'window.open(this.href, "mywindow","width=800,height=600");return false;']) ?>
                        <?php } else { ?>
                        <?= Html::a('รับของ', ['', 'id' => $model->id], ['class' => 'btn btn-success', 'onclick' => 'pickup(' . $model->id . ',' . $model->nationalId . ')']) ?>
                        <?php } ?>
                        <?= Html::a('แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    </div>
                </h4>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'nationalId',
                                'first_name',
                                'last_name',
                                'email:email',
                                'gender',
                                'participant_telephone',
                                'birthDate',
                                'age_category',
                                'bib_number',
                                'health_issues',
                                'emergency_contact',
                                'emergency_contact_relationship',
                                'emergency_contact_phone',
                                'province',


                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'shirt',
                                'shirt_type',
                                'reg_deliver_option',
                                'registration_type',
                                'ticket_type',
                                'race',
                                'price',
                                'user_code',
                                'start_date',
                                'ticket_code',
                                'picktime',
                                [
                                    'attribute' => 'status',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        return $model->status == '1' ? '<font color="green">รับของแล้ว</font>' : '<font color="red">ยังไม่รับของ</font>';
                                    }
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script type="text/javascript">
        function pickup(id, nactionId) {
            $.ajax({
                url: '<?= Url::to(['running/put-pickup']) ?>?id=' + id + '&nationId=' + nactionId +
                    '&runningId=' +
                    <?= $model->runningid ?>,
                type: 'get',
                success: function(response) {
                    console.log(response);

                    if (response.status === "success") {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function() {
                    document.getElementById('participants').innerHTML =
                        `<p style="color: red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</p>`;
                }
            });
        }
        </script>