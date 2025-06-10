<?php 

$gender = [];
$age_category = [];
$province = [];
$shirt = [];
$ticket_type = [];

$pikup_gender = [];
$pikup_age_category = [];
$pikup_province = [];
$pikup_shirt = [];
$pikup_ticket_type = [];


// นับจำนวนเพศในตาราง participants
foreach ($participants as $participant) {
    $gender[$participant->gender] = ($gender[$participant->gender] ?? 0) + 1;
    $age_category[$participant->age_category] = ($age_category[$participant->age_category] ?? 0) + 1;
    $province[$participant->province] = ($province[$participant->province] ?? 0) + 1;
    $shirt[$participant->shirt] = ($shirt[$participant->shirt] ?? 0) + 1;
    $ticket_type[$participant->ticket_type] = ($ticket_type[$participant->ticket_type] ?? 0) + 1;  

    if($participant->status == 1){
        $pikup_gender[$participant->gender] = ($pikup_gender[$participant->gender] ?? 0) + 1;
        $pikup_age_category[$participant->age_category] = ($pikup_age_category[$participant->age_category] ?? 0) + 1;
        $pikup_province[$participant->province] = ($pikup_province[$participant->province] ?? 0) + 1;
        $pikup_shirt[$participant->shirt] = ($pikup_shirt[$participant->shirt] ?? 0) + 1;
        $pikup_ticket_type[$participant->ticket_type] = ($pikup_ticket_type[$participant->ticket_type] ?? 0) + 1;
    }
    
} 
//var_dump($gender);
//var_dump($age_category);
//var_dump($province);
//var_dump($shirt);
//var_dump($ticket_type);
?>

<div class="row">
    <div class="col-md-12 text-right">
<a href="view?id=<?=$_GET['id']?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> กลับ</a>
<a href="report?id=<?=$_GET['id']?>" class="btn btn-primary"><i class="fa fa-refresh"></i> รีเฟรช</a>
ข้อมูล ณ วันที่ <?=date('d/m/Y H:i:s')?>
    </div>
<div class="col-md-3">
    <div class="card shadow border-0 mb-3">
                <div class="card-body">
                    <div class="row h-100">
                        <div class="col">
                            <h4 class="text-center">จำแนกตามเพศ</h4>
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="bg-light">
                                        <th>เพศ</th>
                                        <th>รับแล้ว</th>
                                        <th>ยังไม่รับ</th>
                                        <th>รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($gender as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?=$pikup_gender[$key] ?? 0 ?></td>
                                            <td><?=$value - ($pikup_gender[$key] ?? 0) ?></td>
                                            <td><?= $value ?></td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td>รวม</td>
                                        <td><?= array_sum($gender) - (array_sum($gender) - array_sum($pikup_gender) ) ?></td>
                                        <td><?= array_sum($gender) - array_sum($pikup_gender) ?></td>
                                        <td><?= array_sum($gender) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0 mb-3">
                <div class="card-body">
                    <div class="row h-100">
                        <div class="col">
                            <h4 class="text-center">จำแนกตามประเภทบัตร</h4>
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="bg-light">
                                        <th>ประเภทบัตร</th>
                                        <th>รับแล้ว</th>
                                        <th>ยังไม่รับ</th>
                                        <th>รวม</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ticket_type as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?=$pikup_ticket_type[$key] ?? 0 ?></td>
                                            <td><?=$value - ($pikup_ticket_type[$key] ?? 0) ?></td>
                                            <td><?= $value ?></td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot> 
                                    <tr class="bg-light">
                                        <td>รวม</td>
                                        <td><?= array_sum($ticket_type) - (array_sum($ticket_type) - array_sum($pikup_ticket_type) ) ?></td>
                                        <td><?= array_sum($ticket_type) - array_sum($pikup_ticket_type) ?></td>
                                        <td><?= array_sum($ticket_type) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>


            </div>

    <div class="col-md-3">
    <div class="card shadow border-0 mb-3"> 
                <div class="card-body">
                    <div class="row h-100">
                        <div class="col">
                            <h4 class="text-center">จำแนกตามกลุ่มอายุ</h4>
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="bg-light">
                                        <th>กลุ่มอายุ</th>
                                        <th>รับแล้ว</th>
                                        <th>ยังไม่รับ</th>
                                        <th>รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($age_category as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?=$pikup_age_category[$key] ?? 0 ?></td>
                                            <td><?=$value - ($pikup_age_category[$key] ?? 0) ?></td>
                                            <td><?= $value ?></td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td>รวม</td>
                                        <td><?= array_sum($age_category) - (array_sum($age_category) - array_sum($pikup_age_category) ) ?></td>
                                        <td><?= array_sum($age_category) - array_sum($pikup_age_category) ?></td>
                                        <td><?= array_sum($age_category) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
            </div>

    <div class="col-md-3">
    <div class="card shadow border-0 mb-3">
                <div class="card-body">
                    <div class="row h-100">
                        <div class="col">
                            <h4 class="text-center">จำแนกตามจังหวัด</h4>
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="bg-light">
                                        <th>จังหวัด</th>
                                        <th>รับแล้ว</th>
                                        <th>ยังไม่รับ</th>
                                        <th>รวม</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($province as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?=$pikup_province[$key] ?? 0 ?></td>
                                            <td><?=$value - ($pikup_province[$key] ?? 0) ?></td>
                                            <td><?= $value ?></td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td>รวม</td>
                                        <td><?= array_sum($province) - (array_sum($province) - array_sum($pikup_province) ) ?></td>
                                        <td><?= array_sum($province) - array_sum($pikup_province) ?></td>
                                        <td><?= array_sum($province) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
            </div>

    <div class="col-md-3">
    <div class="card shadow border-0 mb-3">
                <div class="card-body">
                    <div class="row h-100">
                        <div class="col">
                            <h4 class="text-center">จำแนกตามเสื้อ</h4>
                            <table class="table table-borderless">
                                <thead>
                                    <tr class="bg-light">
                                        <th>เสื้อ</th>
                                        <th>รับแล้ว</th>
                                        <th>ยังไม่รับ</th>
                                        <th>รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($shirt as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?=$pikup_shirt[$key] ?? 0 ?></td>
                                            <td><?=$value - ($pikup_shirt[$key] ?? 0) ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td>รวม</td>
                                        <td><?= array_sum($shirt) - (array_sum($shirt) - array_sum($pikup_shirt) ) ?></td>
                                        <td><?= array_sum($shirt) - array_sum($pikup_shirt) ?></td>
                                        <td><?= array_sum($shirt) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
            </div>

        
    
        




</div>

<script>
    //ตั้งคค่าให้่ reload หน้าเว็บทุกๆ 30 วินาที
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
