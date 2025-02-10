<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */


namespace Gm\Backend\Menu\Model;

use Gm;
use Gm\Panel\Data\Model\AdjacencyFormModel;

/**
 * Модель данных профиля пункта главного меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class Form extends AdjacencyFormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => false,
            'tableName'  => '{{panel_menu}}',
            'primaryKey' => 'id',
            'parentKey'  => 'parent_id',
            'countKey'   => 'count',
            // поля
            'fields' => [
                ['id'],
                [
                    'index', 'title' => 'Index'
                ],
                [
                    'name', 'title' => 'Name'
                ],
                [
                    'plugin', 'title' => 'Plugin'
                ],
                [
                    'parent_id', 
                    'alias' => 'parentId', 
                    'title' => 'Parent name'
                ],
                [
                    'icon', 
                    'alias' => 'asIcon', 
                    'title' => 'Icon'
                ],
                [
                    'enabled', 
                    'title' => 'Enabled'
                ],
                [
                    'visible', 
                    'title' => 'Visible'
                ],
                [
                    'type', 
                    'title' => 'Type'
                ],
                [
                    'type_args', 
                    'alias' => 'typeArgs', 
                    'title' => 'Attributes'
                ],
                [
                    'handler', 
                    'alias' => 'handler', 
                    'title' => 'Handler'
                ],
                [
                    'handler_args', 
                    'alias' => 'handlerArgs', 
                    'title' => 'Arguments'
                ]
            ],
            // зависимости
            'dependencies' => [
                'delete'    => [
                    '{{panel_menu_roles}}'  => ['menu_id' => 'id']
                ]
            ],
            // правила форматирования полей
            'formatterRules' => [
                [['enabled', 'visible'], 'logic']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                /** @var \Gm\Panel\Http\Response $response */
                $response = $this->response();
                // всплывающие сообщение
                $response
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                /** @var \Gm\Panel\Http\Response $response */
                $response = $this->response();
                // всплывающие сообщение
                $response
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * Возвращает загаловок плагина по его имени.
     * 
     * @param string $plugin Имя плагина.
     * 
     * @return string
     */
    public function getPluginName(string $plugin): string
    {
        $title = $this->module
            ->getPluginManager()
                ->getPluginTitle($plugin);
        return $this->t($title);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeLoad(array &$data): void
    {
        // если не указан раздел меню
        if (($data['parentId'] ?? null) === 'null') {
            $data['parentId'] = null;
        }
        // если не указан плагин
        if (($data['plugin'] ?? null) === 'null') {
            $data['plugin'] = null;
        }
    }

    /**
     * Возвращает значение для выпадающего списка разделов меню.
     * 
     * @return array
     */
    protected function getParentValue()
    {
        if ($this->parentId > 0) {
            /** @var \Gm\Backend\Menu\Model\Menu|null $item */
            $item = $this->module->getModel('Menu');
            $item = $item->get($this->parentId);
            if ($item) {
                return [
                    'type'  => 'combobox',
                    'value' => $this->parentId,
                    'text'  => $item->name
                ];
            }
        }
        return [
            'type'  => 'combobox',
            'value' => 'null',
            'text'  => Gm::t(BACKEND, '[None]')
        ];
    }

    /**
     * Возвращает значение для выпадающего списка плагинов меню.
     * 
     * @return array
     */
    protected function getPluginValue()
    {
        if ($this->plugin) {
            $pluginName = $this->getPluginName($this->plugin);
            if ($pluginName !== null) {
                return [
                    'type'  => 'combobox',
                    'value' => $this->plugin,
                    'text'  => $pluginName
                ];
            }
        }
        return [
            'type'  => 'combobox',
            'value' => 'null',
            'text'  => Gm::t(BACKEND, '[None]')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        /** @var array $parentId идент. раздела меню (его предок) */
        $this->parentId = $this->getParentValue();
        /** @var string $plugin название плагина меню: "Settings"... */
        $this->plugin = $this->getPluginValue();
    }
}
