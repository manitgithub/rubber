<?php

use app\models\Members;
use app\models\Purchases;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'จัดการสมาชิก';
$this->params['breadcrumbs'][] = $this->title;

// คำนวณสถิติสมาชิก
$totalMembers = Members::find()->count();
$activeMembersCount = Members::find()->where(['!=', 'membertype', 'inactive'])->count();
$newMembersThisMonth = Members::find()
    ->where(['>=', 'created_at', date('Y-m-01')])
    ->count();

// สถิติการซื้อของสมาชิก
$membersWithPurchases = Purchases::find()
    ->select('member_id')
    ->distinct()
    ->where(['flagdel' => 0])
    ->count();

?>
<style>
.stats-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.stats-card:hover {
    transform: translateY(-2px);
}
.stats-number {
    font-size: 2rem;
    font-weight: bold;
}
.stats-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}
.search-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.table-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>

<div class="members-index">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><?= Html::encode($this->title) ?> 👥</h2>
            <p class="text-muted mb-0">จัดการข้อมูลสมาชิกและติดตามสถิติ</p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-plus me-2"></i>เพิ่มสมาชิกใหม่', ['create'], [
                'class' => 'btn btn-success btn-lg',
                'style' => 'border-radius: 25px;'
            ]) ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card stats-card h-100 bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="stats-number"><?= number_format($totalMembers) ?></div>
                        <div class="small mt-2">สมาชิกทั้งหมด</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card stats-card h-100 bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="stats-number"><?= number_format($activeMembersCount) ?></div>
                        <div class="small mt-2">สมาชิกที่ใช้งาน</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card stats-card h-100 bg-info text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <div class="stats-number"><?= number_format($newMembersThisMonth) ?></div>
                        <div class="small mt-2">สมาชิกใหม่เดือนนี้</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card stats-card h-100 bg-warning text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <div class="stats-number"><?= number_format($membersWithPurchases) ?></div>
                        <div class="small mt-2">สมาชิกที่มีการซื้อ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter
    <div class="card search-card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>ค้นหาและกรองข้อมูล</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">ค้นหาสมาชิก</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="ชื่อ, นามสกุล, หรือรหัสบัตร" id="memberSearch">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">สถานะ</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">ทั้งหมด</option>
                        <option value="active">ใช้งาน</option>
                        <option value="inactive">ไม่ใช้งาน</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-filter me-1"></i>กรอง
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
 -->
    <!-- Members Table -->
    <div class="card table-card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>รายชื่อสมาชิก</h5>
            <!-- <div class="btn-group" role="group">
                <?= Html::a('<i class="fas fa-download me-1"></i>ส่งออก Excel', ['export'], ['class' => 'btn btn-outline-success btn-sm']) ?>
                <?= Html::a('<i class="fas fa-print me-1"></i>พิมพ์', ['print'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
            </div> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="membersTable" class="table table-striped table-hover w-100 datatable">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">#</th>
                            <th>รหัสบัตรประชาชน</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ประเภทสมาชิก</th>
                            <th>เบอร์โทร</th>
                            <th>วันที่สมัคร</th>
                            <th width="120">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $members = $dataProvider->getModels();
                        $index = 1;
                        foreach ($members as $model): ?>
                            <tr>
                                <td><?= $index++ ?></td>
                                <td>
                                    <?= $model->idcard ? '<span class="badge bg-secondary">' . Html::encode($model->idcard) . '</span>' : '<span class="text-muted">-</span>' ?>
                                </td>
                                <td>
                                    <?php 
                                    $fullName = trim(($model->pername ?? '') . ' ' . ($model->name ?? '') . ' ' . ($model->surname ?? ''));
                                    echo '<strong>' . Html::encode($fullName ?: 'ไม่ระบุ') . '</strong>';
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $types = [
                                        'regular' => '<span class="badge bg-primary">สมาชิกทั่วไป</span>',
                                        'premium' => '<span class="badge bg-warning">สมาชิกพิเศษ</span>',
                                        'inactive' => '<span class="badge bg-secondary">ไม่ใช้งาน</span>'
                                    ];
                                    echo $types[$model->membertype] ?? '<span class="badge bg-light text-dark">' . Html::encode($model->membertype) . '</span>';
                                    ?>
                                </td>
                                <td>
                                    <?= $model->phone ? '<a href="tel:' . Html::encode($model->phone) . '" class="text-decoration-none"><i class="fas fa-phone me-1"></i>' . Html::encode($model->phone) . '</a>' : '<span class="text-muted">-</span>' ?>
                                </td>
                                <td>
                                    <?= $model->created_at ? '<small class="text-muted">' . Yii::$app->formatter->asDate($model->created_at, 'dd/MM/yyyy') . '</small>' : '-' ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $model->id], [
                                            'class' => 'btn btn-outline-info btn-sm',
                                            'title' => 'ดูรายละเอียด',
                                            'data-bs-toggle' => 'tooltip'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], [
                                            'class' => 'btn btn-outline-warning btn-sm',
                                            'title' => 'แก้ไข',
                                            'data-bs-toggle' => 'tooltip'
                                        ]) ?>
                                        <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                                            'class' => 'btn btn-outline-danger btn-sm',
                                            'title' => 'ลบ',
                                            'data-bs-toggle' => 'tooltip',
                                            'data-confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการลบสมาชิกนี้?',
                                            'data-method' => 'post',
                                        ]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // เปิดใช้งาน tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize DataTable
    $('#membersTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json',
            search: "ค้นหา:",
            lengthMenu: "แสดง _MENU_ รายการ",
            info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            infoEmpty: "แสดง 0 ถึง 0 จาก 0 รายการ",
            infoFiltered: "(กรองจาก _MAX_ รายการทั้งหมด)",
            paginate: {
                first: "หน้าแรก",
                last: "หน้าสุดท้าย",
                next: "ถัดไป",
                previous: "ก่อนหน้า"
            },
            zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
            emptyTable: "ไม่มีข้อมูลในตาราง"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12 col-md-2"B>><"row"<"col-sm-12"t>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i>Excel',
                className: 'btn btn-success btn-sm',
                filename: 'สมาชิก_' + new Date().toISOString().slice(0,10),
                title: 'รายชื่อสมาชิก',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i>PDF',
                className: 'btn btn-danger btn-sm',
                filename: 'สมาชิก_' + new Date().toISOString().slice(0,10),
                title: 'รายชื่อสมาชิก',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                customize: function(doc) {
                    doc.defaultStyle.font = 'THSarabunNew';
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i>พิมพ์',
                className: 'btn btn-info btn-sm',
                title: 'รายชื่อสมาชิก',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }
        ],
        order: [[0, 'asc']],
        pageLength: -1,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด"]],
        columnDefs: [
            {
                targets: [6], // คอลัมน์จัดการ
                orderable: false,
                searchable: false
            },
            {
                targets: [0], // คอลัมน์ลำดับ
                className: 'text-center'
            },
            {
                targets: [3, 4], // คอลัมน์ประเภทสมาชิกและเบอร์โทร
                className: 'text-center'
            }
        ],
        initComplete: function() {
            // เพิ่มสไตล์ให้กับ search box
            $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'ค้นหาสมาชิก...');
            $('.dataTables_length select').addClass('form-select');
            
            // ปรับแต่ง buttons
            $('.dt-buttons').addClass('mb-3');
            $('.dt-button').removeClass('btn-secondary').addClass('me-2');
        }
    });
});

// ฟังก์ชันกรองข้อมูล (ถ้าต้องการใช้ในอนาคต)
function applyFilters() {
    const search = document.getElementById('memberSearch').value;
    const memberType = document.getElementById('memberTypeFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    // สร้าง URL พร้อม parameters
    let url = new URL(window.location.href);
    url.searchParams.set('search', search);
    url.searchParams.set('memberType', memberType);
    url.searchParams.set('status', status);
    
    // รีเฟรชหน้าพร้อมกับ parameters ใหม่
    window.location.href = url.toString();
}
</script>
