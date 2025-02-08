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
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>

<div class="running-view">
    <div class="swiper-pagination"></div>
    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
</div>
<div class="jumbotron  bg-white">
    <h4 class="mb-3"><?= $model->name ?>!
        <div class="float-right">
            <div class="btn">

                <a class="btn btn-warning" href="<?= Url::to(['view', 'id' => $model->id]) ?>"><i
                        class="material-icons">arrow_back</i> กลับ</a>
            </div>
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
                                    <div class="overlay gradient-info"></div>
                                    <i class="material-icons text-info">directions_run</i>
                                </div>
                            </div>
                            <div class="col">
                                <font id="total" style="font-size: 1.5rem;">0 </font><br>ทั้งหมด
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
                                    <i class="material-icons text-success">widgets</i>
                                </div>
                            </div>
                            <div class="col">
                                <font id="pick" style="font-size: 1.5rem;">0 </font><br>รับแล้ว
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
                                    <div class="overlay gradient-danger"></div>
                                    <i class="material-icons text-danger">cancel</i>
                                </div>
                            </div>
                            <div class="col">
                                <font id="notpick" style="font-size: 1.5rem;">0 </font><br>ยังไม่ได้รับ
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="button-group">
                    <br>
                    <button class="btn btn-secondary btn-block" onclick="openQrScanner()"><i
                            class="material-icons">fullscreen</i> สแกน</button>
                </div>
            </div>

            <div id="qr-reader" style="width: 100%; display: none;"></div>


        </div>
        <hr class="my-4">

        <!-- เขียน form สำหรับกรอกเลขบัตรประชาชน เมื่อกรอกให้ไปอัพเดทข้อมูลในตาราง participants โดยใช้ ajax -->
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="nationId">เลขบัตรประชาชน</label>
                    <input type="text" class="form-control" id="nationId" placeholder="เลขบัตรประชาชน">
                </div>
            </div>
            <div class="col-md-2">
                <div class="button-group">
                    <br>
                    <button class="btn btn-primary btn-block" onclick="updateParticipant()"><i
                            class="material-icons">search</i>
                        ค้นหา</button>
                </div>
            </div>

        </div>

        <div id="participants"></div>

        <div id="participants"></div>

        <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            function fetchData() {
                $.ajax({
                    url: "<?= Url::to(['/running/show-running', 'id' => $model->id]) ?>",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        let pick = response.main.pick;
                        let notpick = response.main.notpick;
                        let total = parseInt(pick) + parseInt(notpick);
                        console.log(response);

                        $("#pick").text(pick);
                        $("#notpick").text(notpick);
                        $("#total").text(total);
                    },
                    error: function(xhr, status, error) {
                        console.error("เกิดข้อผิดพลาดในการโหลดข้อมูล:", error);
                    }
                });
            }

            fetchData(); // เรียกใช้งานตอนโหลดหน้าเว็บ
            setInterval(fetchData, 5000); // อัปเดตข้อมูลทุก 5 วินาที
        });


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
                        /*Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });*/

                        var participants = response.data;
                        readtable(participants);

                        document.getElementById('nationId').value = '';
                        document.getElementById('nationId').focus();


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
                        document.getElementById('nationId').value = '';
                        document.getElementById('nationId').focus();

                        var participants = response.data;
                        //readtable(participants);
                    }
                },
                error: function() {
                    document.getElementById('participants').innerHTML =
                        `<p style="color: red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</p>`;
                }
            });
        }

        function readtable(data) {
            var htmlContent = `<h3>รายชื่อผู้เข้าร่วม</h3>
        <table border="1" cellspacing="0" cellpadding="5" class="table table-striped table-bordered datatable">
            <tr>
                <th>BIB</th>
                <th>ชื่อ-นามสกุล</th>
                <th>เพศ</th>
                <th>โทรศัพท์</th>
                <th>ประเภทตั๋ว</th>
                <th>shirt_type</th>
                <th>Size</th>
                <th>Status</th>
                <th>พิมพ์ใบรับของ</th>
            </tr>`;

            data.forEach((data, index) => {
                htmlContent += `
            <tr>
                <td>${data.bib_number}</td>
                <td>${data.first_name} ${data.last_name}</td>
                <td>${data.gender}</td>
                <td>${data.participant_telephone}</td>
                <td>${data.ticket_type}</td>
                <td>${data.shirt_type}</td>
                <td>${data.shirt}</td>
                <td>${data.status == 1 ? 'รับของแล้ว' : 'ยังไม่รับของ'}</td>
                <td>
                    ${data.status == 0 ? `<button onclick="pickup(${data.id},${data.nationalId})" class="btn btn-success">รับของ</button>` : ''}
                ${data.status == 1 ? `<button onclick="printReceipt(${data.id})" class="btn btn-primary">พิมพ์ใบรับของ</button>` : ''}
            </tr>`;
            });

            htmlContent += `
        </table>`;
            document.getElementById('participants').innerHTML = htmlContent;
        }

        function printReceipt(id) {

            window.open('<?= Url::to(['participants/print']) ?>?id=' + id, '_blank');
        }

        function pickup(id, nactionId) {
            $.ajax({
                url: '<?= Url::to(['running/put-pickup']) ?>?id=' + id + '&nationId=' + nactionId +
                    '&runningId=' +
                    <?= $model->id ?>,
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
                    }
                },
                error: function() {
                    document.getElementById('participants').innerHTML =
                        `<p style="color: red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</p>`;
                }
            });
        }

        function openQrScanner() {
            const qrReader = document.getElementById('qr-reader');
            qrReader.style.display = 'block';

            const html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start({
                    facingMode: "environment"
                }, // ใช้กล้องหลัง
                {
                    fps: 10, // จำนวนเฟรมต่อวินาที
                    qrbox: 250 // ขนาดกรอบสแกน
                },
                (decodedText, decodedResult) => {
                    // เมื่อสแกนสำเร็จ นำค่าไปกรอกในช่องเลขบัตรประชาชน
                    document.getElementById('nationId').value = decodedText;
                    html5QrCode.stop(); // หยุดการสแกน
                    qrReader.style.display = 'none';
                },
                (errorMessage) => {
                    console.log("Error scanning: ", errorMessage);
                }
            ).catch(err => {
                console.error("Cannot start QR code scanner", err);
            });
        }
        </script>
    </div>
</div>





</div>