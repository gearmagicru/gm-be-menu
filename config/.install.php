<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации установки модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'use'         => BACKEND,
    'id'          => 'gm.be.menu',
    'name'        => 'Main menu',
    'description' => 'Main menu of the Control Panel',
    'namespace'   => 'Gm\Backend\Menu',
    'path'        => '/gm/gm.be.menu',
    'route'       => 'menu',
    'routes'      => [
        [
            'type'    => 'crudSegments',
            'options' => [
                'module'      => 'gm.be.menu',
                'route'       => 'menu',
                'prefix'      => BACKEND,
                'constraints' => ['id'],
                'defaults'    => [
                    'controller' => 'grid'
                ]
            ]
        ]
    ],
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => ['any', 'view', 'read', 'add', 'edit', 'delete', 'clear', 'interface', 'settings', 'info'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM MS'],
        ['app', 'code' => 'GM CMS'],
        ['app', 'code' => 'GM CRM'],
    ]
];
