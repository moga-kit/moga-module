<?php

$sMetadataVersion = '2.1';
$aModule = [
    'id' => 'moga',
    'title' => 'MOGA Customizer',
    'description' => 'Customizer for MOGA Theme',
    'thumbnail' => 'thumbnail.png',
    'version' => '0.0.2 ( 2020-12-27 )',
    'author' => 'Marat Bedoev',
    'email' => openssl_decrypt("Az6pE7kPbtnTzjHlPhPCa4ktJLphZ/w9gKgo5vA//p4=", str_rot13("nrf-128-pop"), str_rot13("gvalzpr")),
    'url' => 'https://github.com/moga-kit/moga-module',
    'extend' => [
        \OxidEsales\Eshop\Core\ShopControl::class => Moga\Application\Extend\ShopControl::class,
        \OxidEsales\Eshop\Core\UtilsView::class => Moga\Application\Extend\UtilsView::class
    ],
    'controllers' => [
        'mogacustomizer' => Moga\Application\Controller\Admin\Mogacustomizer::class,
        'mogamanager' => Moga\Application\Controller\Admin\Mogamanager::class,
        'mogatests' => Moga\Application\Controller\Admin\Mogatests::class
    ],
    'templates' => [
        'customizer.tpl' => 'moga/Application/views/admin/customizer.tpl',
        'tplmanager.tpl' => 'moga/Application/views/admin/tplmanager.tpl',
        'tests.tpl' => 'moga/Application/views/admin/tests.tpl',
        'report.tpl' => 'moga/Application/views/emails/report.tpl'
    ],
    'settings' => [
        ['group' => 'mogaSettings', 'name' => 'blSendReportOnSave', 'type' => 'bool', 'value' => false],

        ['group' => 'mogaScss', 'name' => 'aMogaScssColors', 'type' => 'str', 'value' => ''],
        ['group' => 'mogaScss', 'name' => 'aMogaScssFontsizes', 'type' => 'str', 'value' => ''],
        ['group' => 'mogaScss', 'name' => 'aMogaScssOptions', 'type' => 'str', 'value' => '']
    ]
];
