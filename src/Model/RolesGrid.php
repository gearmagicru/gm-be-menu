<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */
namespace Gm\Backend\Menu\Model;

use Gm\Panel\Data\Model\GridModel;

/**
 * Модель данных списка доступности меню ролям пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class RolesGrid extends GridModel
{
    /**
     * Выбранный пункт главного меню.
     *
     * @var null|array
     */
    protected $menuItem;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{panel_menu_roles}}',
            'primaryKey' => 'id',
            'useAudit'   => false,
            'fields'     => [
                ['name'],
                ['menu_id', 'alias' => 'menuId'],
                ['role_id', 'alias' => 'roleId'],
                ['available']
            ],
            'order' => ['name' => 'asc'],
            'resetIncrements' => ['{{panel_menu_roles}}']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this->menuItem = $this->module->getStorage()->menuItem;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSelect(mixed $command = null): void
    {
        $command->bindValues([
            ':menuId' => $this->menuItem['id'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS `role`.`id`, `role`.`name`, `roles`.`menu_id`, `roles`.`role_id` '
             . 'FROM `{{role}}` `role` '
             . 'LEFT JOIN `{{panel_menu_roles}}` `roles` ON `roles`.`role_id`=`role`.`id` AND `roles`.`menu_id`=:menuId';
        return $this->selectBySql($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRow(array $row): array
    {
        // доступность роли
        $row['available'] = empty($row['menu_id']) ? 0 : 1;;
        return $row;
    }
}
