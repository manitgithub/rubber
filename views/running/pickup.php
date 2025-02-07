<?php

use app\models\Participants;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Running $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Runnings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="running-view">



    <div class="swiper-pagination"></div>
    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
</div>
<div class="jumbotron  bg-white">
    <h4 class="mb-3"><?= $model->name ?>!
        <div class="float-right">
            <a class="btn btn-warning" href="<?= Url::to(['index']) ?>" role="button"><i
                    class="material-icons">arrow_back</i> กลับ</a>
        </div>
    </h4>
    <hr class="my-4">
    <div class="swiper-container offer-slide swiper-container-horizontal">
        <div class="swiper-wrapper">
            <div class="swiper-slide swiper-slide-active">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <div class="row h-100">
                            <div class="col-auto pr-0">
                                <div class="avatar avatar-60 no-shadow border-0">
                                    <div class="overlay gradient-danger"></div>
                                    <i class="material-icons text-danger">favorite</i>
                                </div>
                            </div>
                            <div class="col">
                                <p>$ 150.00<br><small class="text-secondary">Medical Treatment</small></p>
                                <p class="text-secondary text-mute small">แยกรุ่นอายุ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide swiper-slide-next">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <div class="row h-100">
                            <div class="col-auto pr-0">
                                <div class="avatar avatar-60 no-shadow border-0">
                                    <div class="overlay gradient-success"></div>
                                    <i class="material-icons text-success">pets</i>
                                </div>
                            </div>
                            <div class="col">
                                <p>$ 100.00<br><small class="text-secondary">Pets Food</small></p>
                                <p class="text-secondary text-mute small">แยกระยะวิ่ง</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <div class="row h-100">
                            <div class="col-auto pr-0">
                                <div class="avatar avatar-60 no-shadow border-0">
                                    <div class="overlay gradient-warning"></div>
                                    <i class="material-icons text-warning">directions_car</i>
                                </div>
                            </div>
                            <div class="col">
                                <p>$ 150.00<br><small class="text-secondary">Transportation</small></p>
                                <p class="text-secondary text-mute small">แยกชาย-หญิง</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <div class="row h-100">
                            <div class="col-auto pr-0">
                                <div class="avatar avatar-60 no-shadow border-0">
                                    <div class="overlay gradient-warning"></div>
                                    <i class="material-icons text-warning">directions_car</i>
                                </div>
                            </div>
                            <div class="col">
                                <p>$ 150.00<br><small class="text-secondary">Transportation</small></p>
                                <p class="text-secondary text-mute small">รวมทั้งหมด</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <hr class="my-4">

        <!-- เขียน form สำหรับกรอกเลขบัตรประชาชน เมื่อกรอกให้ไปอัพเดทข้อมูลในตาราง participants โดยใช้ ajax -->
        <div class="form-group">
            <label for="nationId">เลขบัตรประชาชน</label>
            <input type="text" class="form-control" id="nationId" placeholder="เลขบัตรประชาชน"
                onchange="updateParticipant()">
        </div>
        <div id="participants"></div>

        <div id="participants"></div>

        <script type="text/javascript">
            function updateParticipant() {
                var nationId = document.getElementById('nationId').value;
                var runningId = <?= $model->id ?>;

                $.ajax({
                    url: '<?= Url::to(['running/updateparticipant']) ?>?nationId=' + nationId + '&runningId=' +
                        runningId,
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

                            readtable(response.data);
                            var participants = response.data;
                            readtable(participants);
                            document.getElementById('participants').innerHTML = htmlContent;
                        } else {
                            Swal.fire({
                                position: "top-end",
                                icon: "error",
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            document.getElementById('participants').innerHTML =
                                `<p style="color: red;">${response.message}</p>`;
                            var participants = response.data;
                            readtable(participants);
                        }
                    },
                    error: function() {
                        document.getElementById('participants').innerHTML =
                            `<p style="color: red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</p>`;
                    }
                });
            }

            function readtable(data) {
                var htmlContent = `<h3>รายชื่อผู้เข้าร่วม</h3><table border="1" cellspacing="0" cellpadding="5" class="table table-striped table-bordered datatable">
                        <tr>
                                                    <th>BIB</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>Email</th>
                            <th>เพศ</th>
                            <th>โทรศัพท์</th>
                            <th>ประเภทตั๋ว</th>
                            <th>shirt_type</th>
                            <th>Size</th>
                            <th>พิมพ์ใบรับของ</th>
                        </tr>`;

                data.forEach((data, index) => {
                    htmlContent += `
                        <tr>
                            <td>${data.bib_number}</td>
                            <td>${data.first_name} ${data.last_name}</td>
                            <td>${data.email}</td>
                            <td>${data.gender}</td>
                            <td>${data.participant_telephone}</td>
                            <td>${data.ticket_type}</td>
                            <td>${data.shirt_type}</td>
                            <td>${data.shirt}</td>
                            <td><button onclick="printReceipt(${data.id})" class="btn btn-primary">พิมพ์</button></td>
                        </tr>`;
                });

                htmlContent += `</table>`;
                document.getElementById('participants').innerHTML = htmlContent;
            }

            function printReceipt(id) {
                window.open('<?= Url::to(['participants/printreceipt']) ?>?id=' + id, '_blank');
            }
        </script>





    </div>
</div>





</div>