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
 * Плагин добавляет пункты меню в пункт главного меню "Добавить".
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Plugin
 * @since 1.0
 */
class MenuItemAppend extends DataModel
{
    /**
     * Возвращает пункты меню для вызова модулей доступных текущей роли пользователя.
     * 
     * @return array
     */
    public function getMenuItems(): array
    {
        /** @var array $items возвращаемые пункты меню */
        $items = [];
        // конфигурации установленных модулей доступных для текущей роли пользователя
        $modulesInfo = Gm::$app->modules->getRegistry()->getListInfo(true, true);
        // для проверки доступных модулей можно использовать: Gm::userIdentity()->getModules(false, 'any')])
        foreach ($modulesInfo as $rowId => $moduleInfo) {
            if (empty($moduleInfo['routeAppend']) || !$moduleInfo['enabled'] || 
                !$moduleInfo['visible'] || ($moduleInfo['use'] === FRONTEND)) continue;
            $items[] = [
                'id'          => 'g-menu-item-mod-' . $rowId,
                'icon'        => $moduleInfo['smallIcon'],
                'text'        => $moduleInfo['name'],
                'handler'     => 'loadWidget',
                'handlerArgs' => [
                    'route' => '@backend/' . $moduleInfo['routeAppend']
                ]
            ];
        }
        return $items;
    }
}
