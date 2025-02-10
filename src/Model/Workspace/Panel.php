<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Menu\Model\Workspace;

use Gm;
use Gm\Exception;
use Gm\Helper\Json;
use Gm\Mvc\Module\BaseModule;
use Gm\Data\Model\BaseModel as DataModel;

/**
 * Модель данных элементов главного меню рабочего пространства пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model\Workspace
 * @since 1.0
 */
class Panel extends DataModel
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\FileManager\Module
     */
    public BaseModule $module;

    /**
     * Проверяет, должна ли отображаться панель главного меню.
     * 
     * @return bool
     */
    public function isVisible(): bool
    {
        static $visible = null;

        if ($visible === null) {
            $workspace = Gm::$app->unifiedConfig->get('workspace');
            if ($workspace)
                $visible = $workspace['menuVisible'] ?? false;
            else
                $visible = true;
            $visible = $this->module->getPermission()->isAllow('any', 'interface') && $visible;
        }
        return $visible;
    }

    /**
     * Возвращает настройки панели главного меню.
     * 
     * @param bool $json Если `true`, результат будет представлен в JSON формате (по умолчанию `true`).
     * 
     * @return string|array|null
     */
    public function getSettings(bool $json = true): string|array|null
    {
        if (!$this->isVisible()) {
            return $json ? 'null' : null;
        }

        $items = $this->getItems();
        if (empty($items)) {
            return $json ? 'null' : null;
        }

        $settings = ['items' => $items];
        if ($json) {
            $settings = Json::encode($settings, true, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            if ($error = Json::error()) {
                throw new Exception\JsonFormatException($error);
            }
        }
        return $settings;
    }

    /**
     * Возвращает элементы главного меню доступные для текущей роли пользователя.
     * 
     * @return array
     */
    public function getItems(): array
    {
        /** @var \Gm\Backend\Menu\Model\Menu $menu */
        $menu = $this->module->getModel('Menu');
        /** @var array $items */
        $items = $menu->getAll(false);

        $module = $this->module;
        $manager = $module->getPluginManager();
        $nodes = $this->createNodesFromArray($items, 0, function($item) use ($manager, $module) {
            // TODO: убрал тень 'shadow' => 'yyyy', 'margin' => 0
            $node = [
                'id' => 'g-menu-item-' . $item['id'],
                'ui' => 'main',
                // все пункты динамически создаются, даже если они скрыты, чтобы в дальнейшем через интерефейс их динамически показывать или скрывать
                'hidden' => $item['visible'] ? false : true,
                'text'   => $this->module->tH($item['name']),
            ];
            // если указан разделитель
            if ($node['text'] == '-')
                $node['xtype'] = 'menuseparator';
            // значок
            if ($item['icon'])
                $node['iconCls'] = $item['icon'];
            // обработчки клика пункта меню
            if ($item['handler']) {
                $node['handler'] = $item['handler'];
                // аргументы обработчка клика пункта меню
                if ($item['handlerArgs']) {
                    // замена переменных в url строке 
                    parse_str($item['handlerArgs'], $node['handlerArgs']);
                }
            }
            // тип пункта меню
            if ($item['type']) {
                $node['xtype'] = $item['type'];
                // атрибуты типа пункта меню
                if ($item['type_args']) {
                    parse_str($item['type_args'], $item['type_args']);
                    $node = array_merge($node, $item['type_args']);
                }
            }
            // если пункт не доступен
            if (!$item['enabled'])
                $node['disabled'] = true;
            // если пункт не доступен
            if ($item['plugin']) {
                $plugin = $manager->getAs($item['plugin'], [], ['module' => $module]);
                $items = $plugin->getMenuItems(); 
                if ($items)
                    $node['menu'] = [
                        'mouseLeaveDelay' => 0,
                        'items'           => $items
                    ];
                // если нет в пункте меню подпунктов, пропустить этот пункт
                else
                    return null;
            }
            return $node;
        });
        return $nodes;
    }

    /**
     * Преобразовывает элементы и формирует пункты главного меню.
     * 
     * @see Panel::createNodesFromArray()
     * 
     * @param array $arr Элементы главного меню.
     * @param int $parentId Идентификатор родителя элемента главного меню.
     * @param function $func
     * 
     * @return array
     */
    protected function createNodesFromArray(array $arr, int $parentId, $func): array
    {
        $nodes = [];
        foreach ($arr as $item) {
            if ($item['parentId'] != $parentId) {
                continue;
            }

            $node = $func($item);
            if ($node === null) continue;
            $children = $this->createNodesFromArray($arr, $item['id'], $func);
            if ($children)
                // TODO: убрал тень 'shadow' => 'yyyy', 'margin' => 0
                $node['menu'] = [
                    'mouseLeaveDelay' => 0,
                    'items'           => $children
                ];
            $nodes[] = $node;
        }
        return $nodes;
    }
}
