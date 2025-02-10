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
use Gm\Panel\Data\Model\AdjacencyGridModel;

/**
 * Модель данных списка меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class Grid extends AdjacencyGridModel
{
    /**
     * {@inheritdoc}
     */
    public bool $collectRowsId = true;

    /**
     * URL-путь в последнем запросе.
     * 
     * @var string
     */
    protected string $urlMatch;

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
            'fields'     => [
                [
                    'index',
                    'alias' => 'asIndex'
                ],
                ['name'],
                ['nameLo'],
                ['rolesUrl'],
                [
                    'menu_id',
                    'alias' => 'menuId'
                ],
                [
                    'item_id',
                    'alias' => 'itemId'
                ],
                ['plugin'],
                [
                    'parent_id',
                    'alias' => 'parentId'
                ],
                [
                    'icon',
                    'alias' => 'asIcon'
                ],
                [
                    'enabled',
                    'alias' => 'isEnabled'
                ],
                ['count'],
                [
                    'visible',
                    'alias' => 'isVisible'
                ],
                ['type'],
                [
                    'type_args',
                    'alias' => 'typeArgs'
                ],
                [
                    'handler',
                    'alias' => 'handler'
                ],
                [
                    'handler_args',
                    'alias' => 'handlerArgs'
                ]
                /**
                 * поля добавленные в запросе:
                 * - loName, имя пункта меню
                 */
            ],
            'order' => [
                'index' => 'ASC'
            ],
            'resetIncrements' => ['{{panel_menu}}', '{{panel_menu_roles}}'],
            'dependencies' => [
                'deleteAll' => ['{{panel_menu_roles}}'],
                'delete'    => [
                    '{{panel_menu_roles}}'  => ['menu_id' => 'id']
                ]
            ],
            'filter' => [
                'parentId' => ['operator' => '='],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this->urlMatch = Gm::alias('@match');
        $this
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                $this->response()
                    ->meta
                        // всплывающие сообщение
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type'])
                        // обновить дерево
                        ->cmdReloadTreeGrid($this->module->viewId('grid'));
            })
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                 $this->response()
                    ->meta
                        // обновить дерево
                        ->cmdReloadTreeGrid($this->module->viewId('grid'), 'root');
            });
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRow(array $row): array
    {
        $row['nameLo'] = $this->module->tH($row['name']);
        $row['rolesUrl'] = $this->urlMatch .'/roles/view/' . $row['id'];
        if ($row['name'] === '-') {
            $row['name'] = $this->t('[ delimiter ]');;
        }
        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // заголовок контекстного меню записи
        $row['popupMenuTitle'] = $row['name'];
    }

    /**
     * {@inheritdoc}
     */
    public function selectNodes(string|int $parentId = null): array
    {
        /** @var \Gm\Db\Sql\Query $query */
        $query = $this->builder()->sql(
            'SELECT SQL_CALC_FOUND_ROWS * FROM {{panel_menu}}'
        );
        // если не задействован фильтр
        if (!$this->hasFilter()) {
            // все дочернии элементы
            $query->where([$this->parentKey() => $parentId]);
        }

        /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
        $command = $this->buildQuery($query);
        $rows    = $this->fetchRows($command);
        $rows    = $this->afterFetchRows($rows);
        return $this->afterSelect($rows, $command);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupplementRows(): array
    {
        $rows = [];
        // если нет записей в списке
        if (empty($this->rowsId)) {
            return $rows;
        }

        /** @var \Gm\Db\Adapter\Adapter $db */
        $db = $this->getDb();
        // элементы (роли пользователей) главного меню
        /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
        $command = $db->createCommand(
            'SELECT `mroles`.`menu_id`, `role`.`name` '
          . 'FROM {{panel_menu_roles}} `mroles` '
          . 'JOIN {{role}} `role` ON `role`.`id`=`mroles`.`role_id` '
          . 'WHERE `mroles`.`menu_id` IN (:menu)'
        );

        $command->bindValues([':menu' => $this->rowsId,]);
        $command->execute();
        while ($row = $command->fetch()) {
            $id = $row['menu_id'];
            if (!isset($rows[$id])) {
                $rows[$id] = [
                    'id' => $id, 'roles' => []
                ];
            }
            $rows[$id]['roles'][] = $row['name'];
        }
        return array_values($rows);
    }
}
