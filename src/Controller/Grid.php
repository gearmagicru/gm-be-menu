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
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Widget\TabTreeGrid;
use Gm\Panel\Data\Model\FormModel;
use Gm\Panel\Helper\ExtGridTree as ExtGrid;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;
use Gm\Panel\Controller\TreeGridController;

/**
 * Контроллер главного меню панели управления.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Controller
 * @since 1.0
 */
class Grid extends TreeGridController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'Grid';

    /**
     * {@inheritdoc}
     */
    public function translateAction(mixed $params, string $default = null): ?string
    {
        switch ($this->actionName) {
            // изменение записи по указанному идентификатору
            case 'update':
                /** @var FormModel $model */
                $model = $this->lastDataModel;
                if ($model instanceof FormModel) {
                    $event   = $model->getEvents()->getLastEvent(true);
                    $columns = $event['columns'];
                    // если изменение видимости модуля
                    if (isset($columns['visible'])) {
                        $visible = (int) $columns['visible'];
                        return $this->module->t('menu item with id %id is ' . ($visible > 0 ? 'shown' : 'hidden'), [$model->getIdentifier()]);
                    }
                    // если изменение доступности модуля
                    if (isset($columns['enabled'])) {
                        $enabled = (int) $columns['enabled'];
                        return $this->module->t('menu item with id %id is '. ($enabled > 0 ? 'enabled' : 'disabled'), [$model->getIdentifier()]);
                    }
                }

            default:
                return parent::translateAction($params, $default);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabTreeGrid
    {
       /** @var TabTreeGrid $tab Сетка данных в виде дерева (Gm.view.grid.Tree Gm JS) */
        $tab = parent::createWidget();

        // столбцы (Gm.view.grid.Tree.columns GmJS)
        $tab->treeGrid->columns = [
            ExtGrid::columnAction(),
            [
                'xtype' => 'g-gridcolumn-control',
                'width' => 34,
                'items' => [
                    [
                        'iconCls'   => 'g-icon-svg g-icon_user-roles_small',
                        'dataIndex' => 'rolesUrl',
                        'tooltip'   => '#Menu roles',
                        'handler'   => 'loadWidgetFromCell'
                    ]
                ]
            ],
            [
                'text'      => '№',
                'tooltip'   => '#Index number',
                'dataIndex' => 'asIndex',
                'filter'    => ['type' => 'numeric'],
                'width'     => 70
            ],
            [
                'xtype'     => 'treecolumn',
                'text'      => ExtGrid::columnInfoIcon($this->t('Name')),
                'cellTip'   => HtmlGrid::tags([
                    HtmlGrid::header('{name}'),
                    HtmlGrid::fieldLabel($this->t('Index number'), '{asIndex}'),
                    HtmlGrid::fieldLabel($this->t('Plugin'), '{plugin}'),
                    HtmlGrid::fieldLabel($this->t('Menu item id'), '{itemId}'),
                    HtmlGrid::fieldLabel($this->t('Menu id'), '{menuId}'),
                    HtmlGrid::fieldLabel($this->t('Number of menu items'), '{count}'),
                    HtmlGrid::fieldLabel(
                        $this->t('Enabled'),
                        HtmlGrid::tplChecked('isEnabled==1')
                    ),
                    HtmlGrid::fieldLabel(
                        $this->t('Visible'),
                        HtmlGrid::tplChecked('isVisible==1')
                    )
                ]),
                'dataIndex' => 'name',
                'filter'    => ['type' => 'string'],
                'width'     => 300
            ],
            [
                'text'      => $this->module->t('Name') . ' (' . Gm::$app->language->name . ')',
                'dataIndex' => 'nameLo',
                'cellTip'   => '{nameLo}',
                'width'     => 200
            ],
            [
                'xtype'     => 'templatecolumn',
                'text'      => '#Icon / Image',
                'dataIndex' => 'asIcon',
                'tpl'        => '<span class="gm-menu-grid__cell-icon {asIcon}"></span> {asIcon}',
                'cellTip'   => '{icon}',
                'filter'    => ['type' => 'string'],
                'width'     => 170
            ],
            [
                'text'      => ExtGrid::columnIcon('g-icon-m_nodes', 'svg'),
                'tooltip'   => '#Number of menu items',
                'align'     => 'center',
                'dataIndex' => 'count',
                'filter'    => ['type' => 'numeric'],
                'width'     => 60
            ],
            [
                'xtype'      => 'templatecolumn',
                'text'       => '#Roles',
                'dataIndex'  => 'roles',
                'hidden'     => true,
                'tpl'        => HtmlGrid::tpl(
                    '<div>' . ExtGrid::renderIcon('g-icon_size_16 g-icon_gridcolumn-user-roles', 'svg') . ' {.}</div>',
                    ['for' => 'roles']
                ),
                'supplement' => true,
                'width'      => 200
            ],
            [
                'text'      => ExtGrid::columnIcon('g-icon-m_accessible', 'svg'),
                'tooltip'   => '#Enabled / disabled menu item',
                'xtype'     => 'g-gridcolumn-switch',
                'selector'  => 'treepanel',
                'dataIndex' => 'isEnabled'
            ],
            [
                'text'      => ExtGrid::columnIcon('g-icon-m_visible', 'svg'),
                'tooltip'   => '#Show / hide menu item',
                'xtype'     => 'g-gridcolumn-switch',
                'selector'  => 'treepanel',
                'dataIndex' => 'isVisible'
            ]
        ];

        // панель инструментов (Gm.view.grid.Tree.tbar GmJS)
        $tab->treeGrid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        // инструмент "Добавить"
                        'add' => [
                            'iconCls' => 'g-icon-svg gm-menu__icon-add',
                            'caching' => true
                        ],
                        // инструмент "Удалить"
                        'delete' => [
                            'iconCls' => 'g-icon-svg gm-menu__icon-delete',
                        ],
                        'cleanup',
                        '-',
                        'edit',
                        'select',
                        '-',
                        'refresh'
                    ]
                ],
                'columns',
                'search' => [
                    'items' => [
                        'help',
                        'search',
                        // инструмент "Фильтр"
                        'filter' => ExtGrid::popupFilter([
                            ExtCombo::trigger('#Parent item menu', 'parentId', 'parentMenuItem')
                        ], [
                            'defaults' => ['labelWidth' => 100],
                        ])
                    ]
                ]
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Tree.popupMenu GmJS)
        $tab->treeGrid->popupMenu = [
            'items' => [
                [
                    'text'        => '#Edit record',
                    'iconCls'     => 'g-icon-svg g-icon-m_edit g-icon-m_color_default',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/form/view/{id}'),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ],
                '-',
                [
                    'text'        => '#Menu roles',
                    'iconCls'     => 'g-icon-svg g-icon_user-roles_small',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/roles/view/{id}'),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ]
            ]
        ];

        // поле аудита записи
        $tab->treeGrid->logField = 'name';
        // плагины сетки
        $tab->treeGrid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->treeGrid->bodyCls = 'g-grid_background';
        // количество строк в сетке
        $tab->treeGrid->store->pageSize = 50;
        $tab->treeGrid->columnLines  = true;
        $tab->treeGrid->rowLines     = true;
        $tab->treeGrid->lines        = true;
        $tab->treeGrid->singleExpand = false;

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('<span class="gm-menu-navinfo__header {asIcon}">{name}</span>'),
            ['fieldset',
                [
                    HtmlNav::fieldLabel($this->t('Index number'), '{asIndex}'),
                    HtmlNav::fieldLabel($this->t('Plugin'), '{plugin}'),
                    HtmlNav::fieldLabel($this->t('Menu item id'), '{itemId}'),
                    HtmlNav::fieldLabel($this->t('Menu id'), '{menuId}'),
                    HtmlNav::fieldLabel(
                        $this->t('Number of menu items'),
                        '{count}'
                    ),
                    HtmlNav::fieldLabel(
                        $this->t('Enabled'),
                        HtmlGrid::tplChecked('isEnabled==1')
                    ),
                    HtmlNav::fieldLabel(
                        $this->t('Visible'),
                        HtmlGrid::tplChecked('isVisible==1')
                    ),
                    HtmlNav::widgetButton(
                        $this->t('Edit record'),
                        ['route' => Gm::alias('@match', '/form/view/{id}'), 'long' => true],
                        ['title' => $this->t('Edit record')]
                    )
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Roles')),
                    HtmlNav::tpl(
                        '<div>' . ExtGrid::renderIcon('g-icon_size_16 g-icon_gridcolumn-user-roles', 'svg') . ' {.}</div>',
                        ['for' => 'roles']
                    ),
                    HtmlNav::widgetButton(
                        $this->t('Update'),
                        ['route' => Gm::alias('@match', '/roles/view/{id}'), 'long' => true]
                    )
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Event handler for clicking')),
                    HtmlNav::fieldLabel($this->t('Name'), '{handler}'),
                    HtmlNav::fieldLabel($this->t('Arguments'), '{handlerArgs}')
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Type name')),
                    HtmlNav::fieldLabel($this->t('Name'), '{type}'),
                    HtmlNav::fieldLabel($this->t('Attributes'), '{typeArgs}')
                ]
            ]
        ]);

        // если открыто окно настройки служб (конфигурация), закрываем его
        $this->getResponse()->meta->cmdComponent('g-setting-window', 'close');

        $tab
            ->addCss('/grid.css')
            ->addRequire('Gm.view.grid.column.Switch');
        return $tab;
    }
}
