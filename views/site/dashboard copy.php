<?php

use app\models\AuditDepartment;
use yii\helpers\Url;

Yii::$app->view->params['callBack'] = ['site/index'];


?>
<div class="row">
    <?php
    $thirtyDaysAgo = date('Y-m-d', strtotime('-15 days'));


    $countAudit = Yii::$app->db->createCommand("SELECT DATE(`created_at`) as date,COUNT(*) as count,type FROM audit_header WHERE created_at >= '$thirtyDaysAgo' AND status = 1 AND flag_del = 0 GROUP BY DATE(`created_at`),`type`")->queryAll();

    foreach ($countAudit as $audit) {
        $date = $audit['date'];
        $type = $audit['type'];
        $count = $audit['count'];
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
    WHERE audit_header.created_at >= '$thirtyDaysAgo' AND audit_header.status = 1 AND audit_header.flag_del = 0 GROUP BY DATE(audit_header.created_at),partner_id")->queryAll();

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

    ?>


    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <div class="col-12">
        <figure class="highcharts-figure">
            <div id="container"></div>
        </figure>
    </div>
    <script>
        Highcharts.setOptions({
            lang: {
                decimalPoint: '.',
                thousandsSep: ','
            }
        });
        /*    Highcharts.chart('piePartner', {
                chart: {
                    type: 'pie',
                    backgroundColor: 'rgba(0,0,0,0)'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'รายการรับเข้าจากคู่ค้าใน 15 วันล่าสุด'
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
                        <?php foreach ($totalPricePartner as $partner_id => $partner) { ?> {
                                name: '<?= $partner['partner_name'] ?>',
                                y: <?= number_format($partner['total_price'], 2, '.', '') ?>
                            },
                        <?php } ?>
                    ]
                }]
            });
            */
        Highcharts.chart('container', {
            chart: {
                type: 'column'
                    //remove background color
                    ,
                backgroundColor: 'rgba(0,0,0,0)'
            },
            title: {
                text: 'ข้อมูลจำนวนรายการที่เบิกและนำเข้าใน 15 วันล่าสุด',
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
                    text: 'ครั้ง'
                }
            },
            tooltip: {
                valueSuffix: ' (ครั้ง)'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            credits: {
                enabled: false
            },

            series: [{
                    name: 'รับเข้า',
                    data: [
                        <?php foreach ($datemain as $date) {
                            echo isset($data[$date]['S']) ? $data[$date]['S'] . ',' : 0 . ',';
                        } ?>
                    ]
                },
                {
                    name: 'เบิกออก',
                    data: [
                        <?php foreach ($datemain as $date) {
                            echo isset($data[$date]['R']) ? $data[$date]['R'] . ',' : 0 . ',';
                        } ?>
                    ]
                }
            ]
        });
        Highcharts.chart('containerPartner', {
            chart: {
                type: 'column'
                    //remove background color
                    ,
                backgroundColor: 'rgba(0,0,0,0)'
            },
            title: {
                text: 'ข้อมูลการรับเข้าจากคู่ค้าใน 30 วันล่าสุด',
                align: 'left'
            },
            xAxis: {
                categories: [
                    <?php foreach ($datemain as $date) { ?> '<?= $date ?>', <?php } ?>
                ],
                crosshair: true,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'ครั้ง'
                }
            },
            tooltip: {
                valueSuffix: ' (1000 MT)'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            credits: {
                enabled: false
            },

            series: [
                <?php foreach ($partnerName as $partner_id => $partner_name) { ?> {
                        name: '<?= $partner_name ?>',
                        data: [
                            <?php foreach ($datemain as $date) {
                                echo isset($dataPartner[$date][$partner_id]) ? $dataPartner[$date][$partner_id]['count'] . ',' : 0 . ',';
                            } ?>
                        ]
                    },
                <?php } ?>
            ]
        });
    </script>


    <?php
    $models = AuditDepartment::find()->asArray()->all();
    foreach ($models as $depart) {
        $totalItem = [];
        $totalPrice = 0;
        $department_id = $depart['id'];

        //นำเข้าสินค้า เบิกสินค้า
        $modelAudit = Yii::$app->db->createCommand("SELECT log.part_id
        ,item.id
        ,item.img
        ,item.min_qty
        ,item.unit_price
        ,COALESCE(SUM(
            CASE WHEN header.type = 'S' THEN log.qty 
            WHEN header.type = 'R' THEN -log.qty 
            WHEN header.type = 'M' THEN log.qty
            WHEN header.type = 'T' AND header.department_id = $department_id AND header.department_id != header.partner_id THEN log.qty 
            WHEN header.type = 'T' AND header.partner_id = $department_id AND header.department_id != header.partner_id THEN -log.qty 
            ELSE 0 END)
        , 0) AS sum_qty
        ,uom.uom_name
        FROM item_part AS item
        LEFT JOIN audit_detail AS log ON item.id = log.part_id
        LEFT JOIN audit_header AS header ON header.id = log.audit_id 
        AND (
            (header.type IN ('S','T','M') AND header.department_id = $department_id) 
            OR (header.type IN ('R','T') AND header.partner_id = $department_id)
            )
        AND header.status = 1 
        AND header.flag_del = 0
        LEFT JOIN item_uom AS uom ON item.uom_id = uom.id
        WHERE item.flag_del = 0 
        AND COALESCE(log.flag_del, 0) = 0
        GROUP BY item.id, item.part_name
        HAVING sum_qty != 0")
            ->queryAll();


        foreach ($modelAudit as $audit) {
            $totalItem[] = $audit['part_id'];
            $totalPrice = $totalPrice + ($audit['sum_qty'] * $audit['unit_price']);
        }

        if (count($totalItem) > 0) {
    ?>
            <div class="col-6 col-md-3">
                <a href="<?= Url::to(['report/current-stock', 'department_id' => $depart['id']]) ?>">
                    <div class="card shadow border-0 bg-template mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <span class="mb-2 font-weight-normal"><?= $depart['department'] ?></span>
                                </div>
                                <div class="col-auto text-right">
                                    <span class="font-weight-normal"><?= count($totalItem) ?> สินค้า</span>
                                    <p class="small"><?= number_format($totalPrice, 2, '.', ',') ?> บาท</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    <?php } ?>
</div>