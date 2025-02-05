<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    'menu' => [
        1 => [
            'name' => 'โครงการ',
            'description' => 'โครงการ',
            'icon' => 'description',
            'controller' => 'running',
            'action' => 'index'
        ],


        5 => [
            'name' => 'จัดการฐานข้อมูล',
            'description' => 'ตั้งค่าหมวดสินค้า,ตั้งค่าหน่วย,ตั้งค่าคู่ค้า,ตั้งค่าแผนก',
            'icon' => 'settings',
            'controller' => 'sys',
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
            'name' => 'รายงานการปฏิบัติงานรายเดือน',
            'description' => '',
            'icon' => 'list',
            'controller' => 'report',
            'action' => 'leave-report'
        ],
        2 => [
            'name' => 'รายงานการลงเวลาเข้าออกงานรายบุคคล',
            'description' => '',
            'icon' => 'show_chart',
            'controller' => 'report',
            'action' => 'checkin-report'
        ],
        3 => [
            'name' => 'รายงานการเข้าออกงานรายวัน',
            'description' => '',
            'icon' => 'show_chart',
            'controller' => 'report',
            'action' => 'checkin-report-day'
        ],
        4 => [
            'name' => 'รายงานสรุปการปฏิบัติงานรายเดือน',
            'description' => '',
            'icon' => 'show_chart',
            'controller' => 'report',
            'action' => 'monthly-report'
        ],
        6 => [
            'name' => 'รายงานการปฏิบัติงานรายปี',
            'description' => '',
            'icon' => 'show_chart',
            'controller' => 'report',
            'action' => 'report-year'
        ],
        7 => [
            'name' => 'รายงานการลา',
            'description' => '',
            'icon' => 'show_chart',
            'controller' => 'report',
            'action' => 'leave-report-year'
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
