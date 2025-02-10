<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{panel_menu}}', '{{panel_menu_roles}}'],
    'create' => [
        '{{panel_menu}}' => function () {
            return "CREATE TABLE `{{panel_menu}}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `parent_id` int(11) unsigned DEFAULT NULL,
                `name` varchar(255) DEFAULT NULL,
                `count` int(11) unsigned DEFAULT NULL,
                `index` int(11) unsigned DEFAULT NULL,
                `item_id` varchar(100) DEFAULT NULL,
                `menu_id` varchar(100) DEFAULT NULL,
                `plugin` varchar(50) DEFAULT NULL,
                `icon` varchar(100) DEFAULT NULL,
                `handler` text,
                `handler_args` text,
                `type` varchar(100) DEFAULT NULL,
                `type_args` text,
                `enabled` tinyint(1) unsigned DEFAULT '1',
                `visible` tinyint(1) unsigned DEFAULT '1',
                PRIMARY KEY (`id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        },
        
        '{{panel_menu_roles}}' => function () {
            return "CREATE TABLE `{{panel_menu_roles}}` (
                `menu_id` int(11) unsigned NOT NULL,
                `role_id` int(11) unsigned NOT NULL,
                PRIMARY KEY (`menu_id`,`role_id`),
                KEY `menu_and_role` (`menu_id`,`role_id`),
                KEY `role` (`role_id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],

    'run' => [
        'install'   => ['drop', 'create'],
        'uninstall' => ['drop']
    ]
];