<?php 

$gender = [];
$age_category = [];
$province = [];
$shirt = [];
$ticket_type = [];
// นับจำนวนเพศในตาราง participants
foreach ($participants as $participant) {
    $gender[$participant->gender] = ($gender[$participant->gender] ?? 0) + 1;
    $age_category[$participant->age_category] = ($age_category[$participant->age_category] ?? 0) + 1;
    $province[$participant->province] = ($province[$participant->province] ?? 0) + 1;
    $shirt[$participant->shirt] = ($shirt[$participant->shirt] ?? 0) + 1;
    $ticket_type[$participant->ticket_type] = ($ticket_type[$participant->ticket_type] ?? 0) + 1;       
} 
//var_dump($gender);
//var_dump($age_category);
//var_dump($province);
//var_dump($shirt);
//var_dump($ticket_type);
?>

<div class="row">
    <div class="col-md-3">
    <div class="card shadow border-0 mb-3">
                <div class="card-body">
                    <div class="row h-100">
                        <div class="col">
                            <h4 class="text-center">จำแนกตามเพศ</h4>
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>เพศ</th>
                                        <th>จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($gender as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
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
                                    <tr>
                                        <th>ประเภทบัตร</th>
                                        <th>จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ticket_type as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
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
                                    <tr>
                                        <th>กลุ่มอายุ</th>
                                        <th>จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($age_category as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
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
                                    <tr>
                                        <th>จังหวัด</th>
                                        <th>จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($province as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
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
                                    <tr>
                                        <th>เสื้อ</th>
                                        <th>จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($shirt as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
            </div>

        
    
        




</div>

