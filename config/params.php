<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    'menu' => [
        1 => [
            'name' => 'กิจกรรม',
            'description' => 'กิจกรรม',
            'icon' => 'description',
            'controller' => 'running',
            'action' => 'index'
        ],



        99 => [
            'name' => 'เจ้าหน้าที่',
            'description' => 'เจ้าหน้าที่',
            'icon' => 'accessibility',
            'controller' => 'employee',
            'action' => 'index'
        ],

    ],


    'menuSys' => [
        1 => [
            'name' => 'ตำแหน่งงาน',
            'description' => 'ตำแหน่งงาน',
            'icon' => 'work',
            'controller' => 'position',
            'action' => 'index'
        ],
        2 => [
            'name' => 'โครงสร้างองค์กร',
            'description' => 'แผนก',
            'icon' => 'account_balance',
            'controller' => 'department',
            'action' => 'index'
        ],
        3 => [
            'name' => 'ประเภทการลา',
            'description' => 'ประเภทการลา',
            'icon' => 'event_note',
            'controller' => 'leavetype',
            'action' => 'index'
        ],
        4 => [
            'name' => 'กะการทำงาน',
            'description' => 'ประเภทการทำงาน',
            'icon' => 'timer',
            'controller' => 'worktype',
            'action' => 'index'
        ],
        5 => [
            'name' => 'จัดการตารางเวร',
            'description' => 'จัดการตารางเวร',
            'icon' => 'timer',
            'controller' => 'worktime',
            'action' => 'index'
        ],
        6 => [
            'name' => 'ตั้งค่าผู้อนุมัติ',
            'description' => 'ตั้งค่าผู้อนุมัติ',
            'icon' => 'person',
            'controller' => 'employee',
            'action' => 'setboss'
        ],
        7 => [
            'name' => 'กำหนดตำแหน่งเช็คอิน',
            'description' => 'กำหนดตำแหน่งเช็คอิน',
            'icon' => 'map',
            'controller' => 'checkin-point',
            'action' => 'index'
        ],
        8 => [
            'name' => 'กำหนดวันหยุดประจำปี',
            'description' => 'กำหนดวันหยุดประจำปี',
            'icon' => 'event',
            'controller' => 'holiday',
            'action' => 'index'
        ],
        9 => [
            'name' => 'กำหนดจำนวนวันหยุดพักผ่อน',
            'description' => 'กำหนดจำนวนวันหยุดพักผ่อนประจำปี',
            'icon' => 'nature_people',
            'controller' => 'holiday',
            'action' => 'index'
        ],
        10 => [
            'name' => 'จัดการข้อมูลปฏิบัติงาน',
            'description' => 'จัดการข้อมูลปฏิบัติงาน',
            'icon' => 'work',
            'controller' => 'worktime',
            'action' => 'adminedit'
        ],

    ],

    'menuSysReport' => [

        1 => [
            'name' => 'รายงานรับซื้อรายวัน',
            'description' => 'รายงานการรับซื้อน้ำยางประจำวัน',
            'icon' => 'receipt',
            'controller' => 'report',
            'action' => 'daily'
        ],
        2 => [
            'name' => 'รายงานสรุปยอดรับซื้อตามช่วงเวลา',
            'description' => 'รายงานสรุปยอดรับซื้อน้ำยางตามช่วงเวลา',
            'icon' => 'trending_up',
            'controller' => 'report',
            'action' => 'report-summary'
        ],
        3 => [
            'name' => 'รายงานสมาชิกสหกรณ์',
            'description' => 'รายงานข้อมูลสมาชิกสหกรณ์',
            'icon' => 'group',
            'controller' => 'report',
            'action' => 'member-report'
        ],
        4 => [
            'name' => 'รายงานคุณภาพน้ำยาง',
            'description' => 'รายงานการตรวจสอบคุณภาพน้ำยาง',
            'icon' => 'verified',
            'controller' => 'report',
            'action' => 'quality-report'
        ],
        5 => [
            'name' => 'รายงานราคารับซื้อ',
            'description' => 'รายงานการปรับราคารับซื้อน้ำยาง',
            'icon' => 'attach_money',
            'controller' => 'report',
            'action' => 'price-report'
        ],
        6 => [
            'name' => 'กราฟราคายาง',
            'description' => 'กราฟแสดงแนวโน้มราคายาง',
            'icon' => 'trending_up',
            'controller' => 'prices',
            'action' => 'chart'
        ],




        // 2 => [
        //     'name' => 'รายงานการปฏิบัติงานรายปี',
        //     'description' => '',
        //     'icon' => 'show_chart',
        //     'controller' => 'report',
        //     'action' => 'report-year'
        // ],


    ]


];
