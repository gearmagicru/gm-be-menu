<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Main menu',
    '{description}' => 'Main menu of the Control Panel',
    '{permissions}' => [
        'any'       => ['Full access', 'Viewing and making changes to the Main menu'],
        'read'      => ['Reading', 'Reading contracts'],
        'add'       => ['Adding', 'Adding Main menu items'],
        'edit'      => ['Editing', 'Editing Main menu items'],
        'delete'    => ['Deleting', 'Deleting Main menu items'],
        'clear'     => ['Clear', 'Deleting all Main menu items'],
        'interface' => ['Interface', 'Accessing the Main menu interface'],
    ],

    // Form
    '{form.title}' => 'Add menu item',
    '{form.titleTpl}' => 'Edit menu item "{name}"',
    // Form: поля / Grid: столбцы
    'Item menu' => 'Item menu',
    'Menu items' => 'Menu items',
    'Index number' => 'Index number',
    'Index' => 'Index',
    'Icon' => 'Icon',
    'Icon / Image' => 'Icon / Image',
    'CSS class of icon in menu styles' => 'CSS class of icon in menu style',
    'Subitems count' => 'Subitems count',
    'Parent item menu' => 'Parent item menu',
    'Menu items are added to the menu section. If you are adding a menu section, then select: no selection'
        => 'Menu items are added to the menu section. If you are adding a menu section, then select "no selection".',
    'Name' => 'Name',
    'Yes' => 'yes',
    'No' => 'no',
    'Enabled' => 'Enabled',
    'Visible' => 'Visible',
    'enabled' => 'enabled',
    'visible' => 'visible',
    'In the main menu' => 'In the main menu',
    'Menu item handler' => 'Menu item handler',
    'Handler name' => 'Menu item click handler name (Javascript function name)',
    'Arguments' => 'Arguments',
    'Arguments handler' => 'Handler arguments are separated ";"<br>example: url=http://domain.com/;action=open',
    'Plugin' => 'Plugin',
    'A plugin whose contents will be displayed in the current menu item/section' 
        => 'A plugin whose contents will be displayed in the current menu item/section',
    'Menu item type' => 'Menu item type',
    'Type' => 'Type',
    'Type name' => 'Type name',
    'Attributes' => 'Attributes',
    'Type attributes' => 'Attributes are separated ";"<br>example: width=100;height=100',
    'Number of menu items' => 'Number of menu items',
    'Menu roles' => 'Menu for user roles',
    'Roles' => 'User roles',
    '[ delimiter ]' => '[ delimiter ]',
    'Event handler for clicking' => 'Event handler &laquo;click&raquo;',
    'Menu item id' => 'Menu item id',
    'Menu id' => 'Menu id',
    'Default' => 'Default',
    'Used if there are no other localizations' => 'Used if there are no other localizations',
    // Form: плагины
    'Display module settings' => 'Display module settings',
    'Display module append' => 'Display module append',

    // Grid: контекстное меню записи
    'Edit record' => 'Edit record',
    // Grid: столбцы
    'Show / hide menu item' => 'Show / hide menu item',
    'Enabled / disabled menu item' => 'Enabled / disabled menu item',
    // Grid: панель навигации
    'Update' => 'Update',
    // Grid: сообщения
    'Menu element - hide' => 'Menu element - <b>hide</b>.',
    'Menu element - show' => 'Menu element - <b>show</b>.',
    'Menu element - enabled' => 'Menu element - <b>enabled</b>.',
    'Menu element - disabled' => 'Menu element - <b>disabled</b>.',
    // Grid: сообщения / заголовки
    'Show' => 'Show',
    'Hide' => 'Hide',
    // Grid: аудит
    'menu item with id {0} is hidden' => 'menu item with id "<b>{0}</b>" is hidden',
    'menu item with id {0} is shown' => 'menu item with id "<b>{0}</b>" is shown',
    'menu item with id {0} is enabled' => 'menu item with id "<b>{0}</b>" is enabled',
    'menu item with id {0} is disabled' => 'menu item with id "<b>{0}</b>" is disabled',

    // RolesGrid: Раздел для ролей пользователя
    '{roles.title}' => 'Access to menu item "{0}" for user roles',
    // RolesGrid: поля
    'User role availability' => 'User role availability',
    // RolesGrid: сообщения
    'Menu element for user role {0} - enabled' => 'Menu element for user role "<b>{0}</b>" - <b>enabled</b>.',
    'Menu element for user role {0} - disabled' => 'Menu element for user role "<b>{0}</b>" - <b>disabled</b>.',
    // RolesGrid: сообщения / заголовки
    'Enabled' => 'Enabled',
    'Disabled' => 'Disabled',
    'Access to the menu' => 'Access to the menu',
    // RolesGrid: аудит
    'Menu element for user role {0} is enabled' => '<b>added</b> for the user role the menu item "<b>{0}</b>" of the main menu',
    'Menu element for user role {0} is disabled' => '<b>removed</b> for the user role the menu item "<b>{0}</b>" of the main menu',

    // Workspace\Panel: для названий пунктов меню по умолчанию (с символа "#")
    'File' => 'File',
    'Exit' => 'Exit',
    'View' => 'View',
    'Tabs' => 'Tabs',
    'Next tab' => 'Next tab',
    'Previous tab' => 'Previous tab',
    'Close' => 'Close',
    'Close all' => 'Close all',
    'Add' => 'Add',
    'Guide' => 'Guide',
    'Help' => 'Help',
    'Version' => 'Version',
    'Settings' => 'Settings',
    'Marketplace' => 'Marketplace',
    'Marketplace catalog' => 'Marketplace catalog',
    'Marketplace panel' => 'Marketplace panel'
];
