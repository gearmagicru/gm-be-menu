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
use Closure;
use Gm\Db\Sql\Where;
use Gm\Db\Sql\Select;
use Gm\Db\ActiveRecord;

/**
 * Модель данных (элементов панели) главного меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class Menu extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function tableName(): string
    {
        return '{{panel_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'id'          => 'id',
            'name'        => 'name',
            'parentId'    => 'parent_id',
            'count'       => 'count',
            'index'       => 'index',
            'itemId'      => 'item_id',
            'menuId'      => 'menu_id',
            'plugin'      => 'plugin',
            'icon'        => 'icon',
            'type'        => 'type',
            'handler'     => 'handler',
            'handlerArgs' => 'handler_args',
            'enabled'     => 'enabled',
            'visible'     => 'visible'
        ];
    }

    /**
     * {@inheritdoc}
     * 
     * @see Menu::getAccessible()
     * 
     * @param bool $accessible Если `true`, возвратит все доступные элементы главного 
     *     меню для текущей роли пользователя (по умолчанию `true`).
     */
    public function fetchAll(
        string $fetchKey = null, 
        array $columns = ['*'], 
        Where|Closure|string|array|null $where = null, 
        string|array|null $order = null,
        bool $accessible = true
    ): array
    {
        /** @var Select $select */
        $select = $this->select($columns, $where);
        if ($order === null) {
            $order = ['index' => 'ASC'];
        }
        $select->order($order);
        // проверка доступа
        if ($accessible) {
            $menuId = $this->getAccessible();
            // если нет доступных элементов
            if (empty($menuId)) {
                return [];
            }
            $select->where(['id' => $menuId]);
        }
        return $this
            ->getDb()
                ->createCommand($select)
                    ->queryAll($fetchKey);
    }

    protected $menuRole;

    /**
     * Возвращает все доступные идентификаторы элементов главного меню для текущей 
     * роли пользователя.
     * 
     * @param bool $toString Если `true`, возвратит идентификаторы через разделитель ',' 
     *     (по умолчанию `false`).
     * 
     * @return array|string
     */
    public function getAccessible(bool $toString = false): array|string
    {
        if ($this->menuRole === null) {
            $this->menuRole  = new MenuRole();
        }
        return $this->menuRole ->getAccessible($toString);
    }

    /**
     * Возвращает запись по указанному значению первичного ключа.
     * 
     * @see ActiveRecrod::selectByPk()
     * 
     * @param int|string $id Идентификатор записи.
     * 
     * @return null|Menu Активная запись при успешном запросе, иначе `null`.
     */
    public function get($identifier)
    {
        return $this->selectByPk($identifier);
    }

    /**
     * Возвращает все "корневые" элементы (не имеющих родителя) главного меню.
     * 
     * @param bool $accessible Если `true`, возвратит только доступные элементы для текущей 
     *     роли пользователя (по умолчанию `true`).
     * 
     * @return array
     */
    public function getRoot(bool $accessible = true): array
    {
        return $this->getChildren(null, $accessible);
    }

    /**
     * Возвращает все (дочернии) элементы главного меню по указанному идентифкатору 
     * родителя.
     * 
     * @param int $parentId Идентифкатор родителя, который содержит дочернии 
     *     элементы главного меню. Если `null`, возвратит все элементы (по умолчанию `null`). 
     * @param bool $accessible Если `true`, возвратит только доступные элементы для текущей 
     *     роли пользователя (по умолчанию `true`).
     * 
     * @return array
     */
    public function getChildren(int $parentId = null, bool $accessible = true): array
    {
        return $this->fetchAll(
            null, 
            ['*'],
            ['visible' => 1, 'parent_id' => $parentId], null, 
            $accessible
        );
    }

    /**
     * Возвращает все записи (элементы) главного меню с указанным ключом.
     * 
     * Ключом каждой записи является значение первичного ключа {@see ActiveRecord::tableName()} 
     * текущей таблицы.
     * 
     * @see Menu::fetchAll()
     * 
     * @param bool $caching Указывает на принудительное кэширование. Если служба кэширования 
     *     отключена, кэширование не будет выполнено (по умолчанию `true`).
     * 
     * @return array
     */
    public function getAll(bool $caching = true): ?array
    {
        if ($caching)
            return $this->cache(
                function () { return $this->fetchAll($this->primaryKey(), $this->maskedAttributes(), ['visible' => 1]); },
                null,
                true
            );
        else
            return $this->fetchAll($this->primaryKey(), $this->maskedAttributes(), ['visible' => 1]);
    }
}
