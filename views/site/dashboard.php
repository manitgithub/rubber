<?php

use app\assets\AppAsset;
use app\models\AdditBom;

Yii::$app->view->params['callBack'] = ['site/index'];

$this->registerJsFile('@web/app/dashboard.js?v=' . date('YmdHis'), ['depends' => AppAsset::className()]);
?>

<?php
$thirtyDaysAgo = date('Y-m-d', strtotime('-15 days'));


$countAudit = Yii::$app->db->createCommand("SELECT DATE(audit_header.created_at) as date,COUNT(*) as count,type,sum(qty*item_part.unit_price) as total_price ,audit_partner.name as partner_name
FROM audit_header 
INNER JOIN audit_detail ON audit_header.id = audit_detail.audit_id 
INNER JOIN audit_partner ON audit_header.partner_id = audit_partner.id
INNER JOIN item_part ON audit_detail.part_id = item_part.id
WHERE audit_header.created_at >= '$thirtyDaysAgo' AND audit_header.status = 1 AND audit_header.flag_del = 0 GROUP BY DATE(audit_header.created_at),type")->queryAll();

foreach ($countAudit as $audit) {
    $date = $audit['date'];
    $type = $audit['type'];
    $count = $audit['total_price'];
    $data[$date][$type] = $count;
}
for ($i = 0; $i < 15; $i++) {
    $currentDate = date('Y-m-d', strtotime("-$i days"));
    $datemain[] = $currentDate;
}
$datemain = array_reverse($datemain);
// count audit 30 days ago type S GRoup by date,partner_id
$countPartner = Yii::$app->db->createCommand("SELECT DATE(audit_header.created_at) as date,COUNT(*) as count,partner_id,sum(qty*item_part.unit_price) as total_price ,audit_partner.name as partner_name
     FROM audit_header 
    INNER JOIN audit_detail ON audit_header.id = audit_detail.audit_id 
    INNER JOIN audit_partner ON audit_header.partner_id = audit_partner.id
    INNER JOIN item_part ON audit_detail.part_id = item_part.id
    WHERE audit_header.created_at >= '$thirtyDaysAgo' AND audit_header.status = 1 AND audit_header.flag_del = 0 and  audit_header.type = 'S'
    GROUP BY DATE(audit_header.created_at),partner_id")->queryAll();

foreach ($countPartner as $partner) {
    $date = $partner['date'];
    $partner_id = $partner['partner_id'];
    $count = $partner['count'];
    $total_price = $partner['total_price'];
    $partner_name = $partner['partner_name'];
    $dataPartner[$date][$partner_id] = ['count' => $count, 'total_price' => $total_price, 'partner_name' => $partner_name];
    $partnerName[$partner_id] = $partner_name;
    $totalPricePartner[$partner_id] = ['total_price' => isset($totalPricePartner[$partner_id]) ? $totalPricePartner[$partner_id]['total_price'] + $total_price : $total_price, 'partner_name' => $partner_name];
}


usort($totalPricePartner, function ($a, $b) {
    return $b['total_price'] <=> $a['total_price'];
});

$top_five = array_slice($totalPricePartner, 0, 10);
$today = date('Y-m-d');
$sumtoDay = Yii::$app->db->createCommand("SELECT DATE(audit_header.created_at) as date,sum(qty*item_part.unit_price) as total_price ,type
FROM audit_header 
INNER JOIN audit_detail ON audit_header.id = audit_detail.audit_id 
INNER JOIN audit_partner ON audit_header.partner_id = audit_partner.id
INNER JOIN item_part ON audit_detail.part_id = item_part.id
WHERE DATE(audit_header.created_at) = '$today' AND audit_header.status = 1 AND audit_header.flag_del = 0 GROUP BY DATE(audit_header.created_at),type")->queryAll();
$totalPrice = [];
foreach ($sumtoDay as $sum) {
    $totalPrice[$sum['type']] = $sum['total_price'];
}

//avg 
$backdate = date('Y-m-d', strtotime('-1 days'));

$avg = Yii::$app->db->createCommand("SELECT DATE(audit_header.created_at) as date,sum(qty*item_part.unit_price) as total_price ,type
FROM audit_header 
INNER JOIN audit_detail ON audit_header.id = audit_detail.audit_id 
INNER JOIN audit_partner ON audit_header.partner_id = audit_partner.id
INNER JOIN item_part ON audit_detail.part_id = item_part.id
WHERE DATE(audit_header.created_at) = '$backdate' AND audit_header.status = 1 AND audit_header.flag_del = 0 GROUP BY DATE(audit_header.created_at),type")->queryAll();
$avgPrice = [];
foreach ($avg as $a) {
    $avgPrice[$a['type']] = $a['total_price'];
}
$totalPrice['S'] = isset($totalPrice['S']) ? $totalPrice['S'] : 0;
$totalPrice['R'] = isset($totalPrice['R']) ? $totalPrice['R'] : 0;
$avgPrice['S'] = isset($avgPrice['S']) ? $avgPrice['S'] : 0;
$avgPrice['R'] = isset($avgPrice['R']) ? $avgPrice['R'] : 0;
?>



<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow">
            <div class="card-body border-bottom">
                <div class="row">
                    <div class="col">
                        <h3 class="mb-0 font-weight-normal">฿ <span id="totalPrice"> </span>
                        </h3>
                        <p class="text-mute">มูลค่าคงคลังรวม</p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-none">
                <div class="row">
                    <div class="col">
                        <p><?= number_format($totalPrice['S'], 2) ?>


                            <?php if ($totalPrice['S'] > $avgPrice['S']) {
                                echo '<i class="material-icons text-success vm small">';
                                echo 'arrow_upward';
                                echo '</i>';
                            } else {
                                echo '<i class="material-icons text-danger vm small">';
                                echo 'arrow_downward';
                                echo '</i>';
                            } ?>

                            <br><small class="text-mute">รับสินค้าเข้าวันนี้</small>
                        </p>
                    </div>
                    <div class="col text-center">
                        <p><?= number_format($totalPrice['R'], 2) ?>
                            <?php if ($totalPrice['R'] > $avgPrice['R']) {
                                echo '<i class="material-icons text-success vm small">';
                                echo 'arrow_upward';
                                echo '</i>';
                            } else {
                                echo '<i class="material-icons text-danger vm small">';
                                echo 'arrow_downward';
                                echo '</i>';
                            } ?>
                            </i><br><small class="text-mute">เบิกออกวันนี้</small></p>

                    </div>

                    <div class="col text-right">
                        <p><i class="material-icons text-success vm small mr-1">arrow_upward</i>
                            <?php $loss = Yii::$app->db->createCommand("SELECT sum(lost*item_in_unit_price) as loss FROM addit_bom WHERE DATE(created_at) = '$today'")->queryOne(); ?>
                            <?= $loss['loss'] ? number_format($loss['loss'], 2) : 0.00; ?>
                            <br><small class="text-mute">สูญเสียวันนี้</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-12">
        <figure class="card mb-4 shadow">
            <div id="container"></div>
        </figure>
    </div>
    <div class="col-lg-6 col-sm-12">
        <figure class="card mb-4 shadow">
            <div id="piePartner"></div>
        </figure>
    </div>
</div>
<script>
    Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: "'Prompt', sans-serif"
            }
        }
    });
    Highcharts.setOptions({
        lang: {
            decimalPoint: '.',
            thousandsSep: ','
        }
    });
    Highcharts.chart('piePartner', {
        chart: {
            type: 'pie',
            backgroundColor: 'rgba(0,0,0,0)'
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'จำนวนมูลค่ายอดรับเข้าคลัง 15 วันล่าสุด'
        },
        subtitle: {
            text: 'แสดง 10 อันดับสูงสุด'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },

        series: [{
            name: 'จำนวน',
            colorByPoint: true,
            data: [
                <?php foreach ($top_five as $partner_id => $partner) { ?> {
                        name: '<?= $partner['partner_name'] ?>',
                        y: <?= number_format($partner['total_price'], 2, '.', '') ?>
                    },
                <?php } ?>
            ]
        }]
    });

    Highcharts.chart('container', {
        chart: {
            type: 'line'
                //remove background color
                ,
            backgroundColor: 'rgba(0,0,0,0)'
        },
        title: {
            text: 'จำนวนมูลค่าที่เบิกและนำเข้าใน 15 วันล่าสุด',
            align: 'left'
        },
        xAxis: {
            categories: [
                <?php foreach ($datemain as $date) { ?> '<?= $date ?>', <?php } ?>
            ],
            crosshair: true,
            accessibility: {
                description: 'Countries'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'บาท'
            }
        },
        tooltip: {
            valueSuffix: ' (บาท)'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function() {
                return Highcharts.numberFormat(this.y, 2);
            }
        },

        credits: {
            enabled: false
        },

        series: [{
                name: 'รับเข้า',
                data: [
                    <?php foreach ($datemain as $date) {
                        echo isset($data[$date]['S']) ? number_format($data[$date]['S'], 2, '.', '') . ',' : 0 . ',';
                    } ?>
                ]
            },
            {
                name: 'เบิกออก',
                data: [
                    <?php foreach ($datemain as $date) {
                        echo isset($data[$date]['R']) ? number_format($data[$date]['R'], 2, '.', '') . ',' : 0 . ',';
                    } ?>
                ]
            }, {
                name: 'ผลิต',
                data: [

                ]
            }
        ]
    });
</script>


<div id="departments" class="row"></div>