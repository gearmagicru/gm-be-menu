<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Menu\Controller;

use Gm;
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Widget\GridDialog;
use Gm\Panel\Data\Model\FormModel;
use Gm\Panel\Controller\DialogGridController;

/**
 * Контроллер списка ролей пользователя доступных в главном меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Controller
 * @since 1.0
 */
class RolesGrid extends DialogGridController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'RolesGrid';

    /**
     * Идентификатора пункта меню.
     * 
     * @var int
     */
    protected int $identifier;

    /**
     * {@inheritdoc}
     */
    public function translateAction(mixed $params, string $default = null): ?string
    {
        switch ($this->actionName) {
            // вывод интерфейса
            case 'view':
                /** @var \Gm\Session\Container $storage */
                $storage = $this->module->getStorage();
                /** @var array $menuItem Выбранный пункт меню */
                $menuItem = $storage->menuItem;
                return $this->module->t(
                    'opening a window for viewing user roles available to the menu {0}', [$menuItem['name'] ?? SYMBOL_NONAME]
                );

            // вывод записей
            case 'data':
                /** @var \Gm\Session\Container $storage */
                $storage = $this->module->getStorage();
                /** @var array $menuItem Выбранный пункт меню */
                $menuItem = $storage->menuItem;
                return $this->module->t('viewing user roles available to the menu {0}', [$menuItem['name'] ?? SYMBOL_NONAME]);

            // изменение записи по указанному идентификатору
            case 'update':
                /** @var FormModel $model */
                $model = $this->lastDataModel;
                if ($model instanceof FormModel) {
                    return $this->module->t(
                        'Menu element for user role {0} is ' . (((int) $model->available > 0) ? 'enabled' : 'disabled'),
                        [$model->name]
                    );
                }

            default:
                return parent::translateAction($params, $default);
        }
    }

    /**
     * Возвращает идентификатор пункта меню.
     * 
     * @return int
     */
    public function getMenuIdentifier(): int
    {
        if (!isset($this->identifier)) {
            $this->identifier = (int) Gm::$app->router->get('id');
        }
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): GridDialog
    {
        /** @var \Gm\Backend\Menu\Model\Menu|null $menu Меню */
        $menu = $this->module->getModel('Menu');

        /** @var \Gm\Backend\Menu\Model\Menu|null $menuItem Выбранный пункт меню */
        $menuItem = $menu->get($this->getMenuIdentifier());
        if ($menuItem === null) {
            $this->getResponse()
                ->meta->error(Gm::t(BACKEND, 'Invalid argument "{0}"', ['ID']));
            return false;
        }

        // информацию в хранилище модуля
        $store = $this->module->getStorage();
        $store->menuItem = $menuItem->getAttributes();

        /** @var GridDialog $window Окно с Сеткой данных (Gm.view.grid.Grid GmJS) */
        $window = parent::createWidget();

        // виджет окно (Ext.window.Window Sencha ExtJS)
        $window->width = 550;
        $window->height = '90%';
        $window->ui = 'light';
        $window->layout = 'fit';
        $window->resizable = true;
        $window->iconCls = 'g-icon-svg g-icon_user-roles_small';
        $window->title = $this->module->t('{roles.title}', [$this->module->tH($menuItem->name ?: '')]);

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $window->grid->columns = [
            ExtGrid::columnNumberer(),
            [
                'text'      => '#Name',
                'dataIndex' => 'name',
                'cellTip'   => '{name}',
                'width'     => 350,
                'filter'    => ['type' => 'string'],
                'hideable'  => false
            ],
            [
                'text'        => ExtGrid::columnIcon('g-icon-m_unlock', 'svg'),
                'tooltip'     => '#User role availability',
                'xtype'       => 'g-gridcolumn-switch',
                'sortable'    => false,
                'collectData' => ['name'],
                'dataIndex'   => 'available',
                'filter'      => ['type' => 'boolean'],
                'hideable'    => false
            ]
        ];

        // сортировка строк в сетке
        $window->grid->sorters = [
            ['property' => 'name', 'direction' => 'ASC']
        ];
        // количество строк в сетке
        $window->grid->store->pageSize = 1000;
        $window->grid->router->route = Gm::alias('@match', '/roles');
        // локальная фильтрация и сортировка
        $window->grid->store->remoteFilter = false;
        $window->grid->store->remoteSort = false;
        // плагины сетки
        $window->grid->plugins = 'gridfilters';
        // убираем пагинацию страниц
        unset($window->grid->pagingtoolbar);
        return $window;
    }
}
