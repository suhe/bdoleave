<?php
return [
    'title' => 'BDO Leave Online',
    'start_page' => 1,
    'per_page' => 15,
    'copyright' => '&copy; 2014 <a href="http://bdo.co.id">BDO Indonesia</a>, All Rights Reserved. Powered By BDO Konsultan Indonesia',
    //'store_name' => "Yiishop",
    'module_default' => 'site/login',
    'ticket_type' => [
        'Monitor' => 'Monitor',
        'Keyboard' => 'Keyboard',
        'Mousepad' => 'Mousepad',
        'Software' => 'Software',
        'Port USB' => 'Port USB',
        'Charger Notebook' => 'Charger Notebook',
        'Lain-lain' => 'Lain-lain'
    ],
    'ticket_status' => [
        4 => 'Baru',
        3 => 'Buka',
        2 => 'Proses',
        1 => 'Selesai',
        0 => 'Tutup'
    ],
    'mail_user' => 'support@bdoindonesia.com',
    'default_page' => 'leave/index',
    'send_email' => false
];
