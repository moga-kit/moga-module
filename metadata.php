<?php

$sMetadataVersion = '2.1';
$aModule = [
    'id' => 'tpl-manager',
    'title' => 'MOGA Template Manager',
    'description' => 'Template Manager for MOGA-Kit Theme',
    'thumbnail' => 'thumbnail.png',
    'version' => '0.0.1 ( 2020-09-01 )',
    'author' => 'Marat Bedoev',
    'email' => openssl_decrypt("Az6pE7kPbtnTzjHlPhPCa4ktJLphZ/w9gKgo5vA//p4=", str_rot13("nrf-128-pop"), str_rot13("gvalzpr")),
    'url' => 'https://github.com/moga-kit/tpl-manager',
    'extend' => [
        \OxidEsales\Eshop\Core\ShopControl::class => MogaKit\TplManager\Application\Extend\ShopControl::class,
        \OxidEsales\Eshop\Core\UtilsView::class => MogaKit\TplManager\Application\Extend\UtilsView::class
    ],
    'controllers' => [
        'mogacustomizer' => MogaKit\TplManager\Application\Controller\Admin\Mogacustomizer::class,
        'mogamanager' => MogaKit\TplManager\Application\Controller\Admin\Mogamanager::class,
        'mogatests' => MogaKit\TplManager\Application\Controller\Admin\Mogatests::class
    ],
    'templates' => [
        'customizer.tpl' => 'moga-kit/tpl-manager/Application/views/admin/customizer.tpl',
        'tplmanager.tpl' => 'moga-kit/tpl-manager/Application/views/admin/tplmanager.tpl',
        'tests.tpl' => 'moga-kit/tpl-manager/Application/views/admin/tests.tpl',
        'report.tpl' => 'moga-kit/tpl-manager/Application/views/emails/report.tpl'
    ],
    'settings' => [
        ['group' => 'mogaSettings', 'name' => 'blSendReportOnSave', 'type' => 'bool', 'value' => false],

        ['group' => 'mogaScss', 'name' => 'aMogaScssColors', 'type' => 'str', 'value' => ''],
        ['group' => 'mogaScss', 'name' => 'aMogaScssFontsizes', 'type' => 'str', 'value' => ''],
        ['group' => 'mogaScss', 'name' => 'aMogaScssOptions', 'type' => 'str', 'value' => '']
    ]
];
