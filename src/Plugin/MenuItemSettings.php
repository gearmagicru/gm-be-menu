<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Menu\Plugin;

use Gm;
use Gm\Data\Model\DataModel;

/**
 * Плагин добавление пунктов меню в пункт "Настройки".
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Plugin
 * @since 1.0
 */
class MenuItemSettings extends DataModel
{
    /**
     * Возвращает пункты меню с атрибутами для вызова настроек модулей, доступных 
     * пользователю с правами "settings".
     * 
     * @return array
     */
    public function getMenuItems(): array
    {
        /** @var array $items возвращаемые пункты меню */
        $items = [];
        // конфигурации установленных модулей доступных для текущей роли пользователя
        $modulesInfo = Gm::$app->modules->getRegistry()->getListInfo(true, true);
        // для проверки доступных модулей можно использовать: Gm::userIdentity()->getModules(false, 'settings')])
        foreach ($modulesInfo as $rowId => $moduleInfo) {
            // если модуль не имеет настроек
            if (!$moduleInfo['hasSettings']) continue;
            $items[] = [
                'icon'        => $moduleInfo['smallIcon'],
                'text'        => $moduleInfo['name'],
                'handler'     => 'loadWidget',
                'handlerArgs' => [
                    'route' => $moduleInfo['settingsUrl']
                ]
            ];
        }
        return $items;
    }
}
