<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Главное меню Панели управления',
    '{description}' => 'Главное меню Панели управления',
    '{permissions}' => [
        'any'       => ['Полный доступ', 'Просмотр и внесение изменений в Главное меню'],
        'view'      => ['Просмотр', 'Просмотр Главного меню'],
        'read'      => ['Чтение', 'Чтение Главного меню'],
        'add'       => ['Добавление', 'Добавление пунктов Главного меню'],
        'edit'      => ['Изменение', 'Изменение пунктов Главного меню'],
        'delete'    => ['Удаление', 'Удаление пунктов Главного меню'],
        'clear'     => ['Очистка', 'Удаление всех пунктов Главного меню'],
        'interface' => ['Интерфейс', 'Доступ к интерфейсу Главного меню'],
    ],

    // Form
    '{form.title}' => 'Создание пункта меню',
    '{form.titleTpl}' => 'Изменение пункта меню "{name}"',
    // Form: поля / Grid: столбцы
    'Item menu' => 'Пункт меню',
    'Menu items' => 'Пункты меню',
    'Index number' => 'Порядковый номер',
    'Index' => 'Порядок',
    'Icon' => 'Значок',
    'Icon / Image' => 'Значок / Изображение',
    'CSS class of icon in menu styles' => 'CSS класс значка в стилях меню',
    'Subitems count' => 'Пунктов',
    'Parent item menu' => 'Раздел меню',
    'Menu items are added to the menu section. If you are adding a menu section, then select: no selection'
        => 'В раздел меню добавляются пункты меню. Если вы добавляете раздел меню, то выберите значение "без выбора".',
    'Name' => 'Название',
    'Yes' => 'да',
    'No' => 'нет',
    'Enabled' => 'Доступен',
    'Visible' => 'Видимый',
    'enabled' => 'доступен',
    'visible' => 'видим',
    'In the main menu' => 'В главном меню - пункт меню',
    'Menu item handler' => 'Обработчик события пункта меню',
    'Handler name' => 'Название обработчика клика пункта меню (название функции Java Script)',
    'Arguments' => 'Аргументы',
    'Arguments handler' => 'Аргументы через разделитель ";"<br>пример: url=http://domain.com/;action=open',
    'Plugin' => 'Плагин',
    'A plugin whose contents will be displayed in the current menu item/section' 
        => 'Плагин, содержимое которого будет отображаться в текущем пункте / разделе меню',
    'Menu item type' => 'Тип пункта меню',
    'Type' => 'Тип',
    'Type name' => 'Тип пункта меню',
    'Attributes' => 'Атрибуты',
    'Type attributes' => 'Атрибуты через разделитель ";"<br>пример: width=100;height=20',
    'Number of menu items' => 'Количество пунктов меню',
    'Menu roles' => 'Меню для ролей пользователя',
    'Roles' => 'Роли пользователей',
    '[ delimiter ]' => '[ разделитель ]',
    'Event handler for clicking' => 'Обработчик события &laquo;клика&raquo;',
    'Menu item id' => 'Идентификатор элемента меню',
    'Menu id' => 'Идентификатор меню',
    'Default' => 'По умолчанию',
    'Used if there are no other localizations' => 'Используется если другие локализации отсутствуют',

    // Form: плагины
    'Display module settings' => 'Отображение настроек модулей',
    'Display module append' => 'Отображение модуля в главном меню',

    // Grid: контекстное меню записи
    'Edit record' => 'Редактировать',
    // Grid: столбцы
    'Show / hide menu item' => 'Показать / скрыть пункт меню',
    'Enabled / disabled menu item' => 'Доступен / отключен пункт меню',
    // Grid: панель навигации
    'Update' => 'Изменить',
    // Grid: сообщения
    'Menu element - hide' => 'Элемент меню - <b>скрыт</b>.',
    'Menu element - show' => 'Элемент меню - <b>отображен</b>.',
    'Menu element - enabled' => 'Элемент меню - <b>доступен</b>.',
    'Menu element - disabled' => 'Элемент меню - <b>отключен</b>.',
    // Grid: сообщения / заголовки
    'Show' => 'Отобразить',
    'Hide' => 'Скрыть',
    // Grid: аудит
    'menu item with id {0} is hidden' => 'скрытие элемента меню c идентификатором "<b>{0}</b>"',
    'menu item with id {0} is shown' => 'отображение элемент меню c идентификатором "<b>{0}</b>"',
    'menu item with id {0} is enabled' => 'предоставление доступа к элементу меню c идентификатором "<b>{0}</b>"',
    'menu item with id {0} is disabled' => 'отключение доступа к элементу меню c идентификатором "<b>{0}</b>"',

    // RolesGrid: Раздел для ролей пользователя
    '{roles.title}' => 'Доступ к пункту меню "{0}" для ролей пользователя',
    // RolesGrid: поля
    'User role availability' => 'Доступность для роли пользователя',
    // RolesGrid: сообщения
    'Menu element for user role {0} - enabled' => 'Для роли пользователя пункт меню "<b>{0}</b>" - <b>доступен</b>.',
    'Menu element for user role {0} - disabled' => 'Для роли пользователя пункт меню "<b>{0}</b>" - <b>не доступен</b>.',
    // RolesGrid: сообщения / заголовки
    'Enabled' => 'Доступен',
    'Disabled' => 'Отключен',
    'Access to the menu' => 'Доступ к пункту меню',
    // RolesGrid: аудит
    'Menu element for user role {0} is enabled' => '<b>добавил(а)</b> для роли пользователя пункт меню "<b>{0}</b>" главного меню',
    'Menu element for user role {0} is disabled' => '<b>убрал(а)</b> для роли пользователя пункт меню "<b>{0}</b>" главного меню',

    // Workspace\Panel: для названий пунктов меню по умолчанию (с символа "#")
    'File' => 'Файл',
    'Exit' => 'Выход',
    'View' => 'Вид',
    'Tabs' => 'Закладки',
    'Next tab' => 'Следущая',
    'Previous tab' => 'Предыдущая',
    'Close' => 'Закрыть',
    'Close all' => 'Закрыть все',
    'Add' => 'Добавить',
    'Guide' => 'Справка',
    'Help' => 'Помощь',
    'Version' => 'Версия',
    'Settings' => 'Настройки',
    'Marketplace' => 'Маркетплейс',
    'Marketplace catalog' => 'Каталог Маркетплейс',
    'Marketplace panel' => 'Панель Маркетплейс'
];
