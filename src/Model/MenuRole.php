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
use Gm\Db\ActiveRecord;

/**
 * Модель данных элементов главного меню для определения доступности ролям пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class MenuRole extends ActiveRecord
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
        return '{{panel_menu_roles}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'id'     => 'id',
            'menuId' => 'menu_id',
            'roleId' => 'role_id'
        ];
    }

    /**
     * Удаляет записи по указанному идентификатору роли пользователя.
     * 
     * @param int|array<int, int> $roleId Идентификатор роли пользователя.
     * 
     * @return false|int Возвращает значение `false`, если ошибка выполнения запроса. 
     *     Иначе, количество удалённых записей.
     */
    public function deleteByRole(int|array $roleId): false|int
    {
        return $this->deleteRecord(['roleId' => $roleId]);
    }

    /**
     * Удаляет записи по указанному идентификатору элемента меню.
     * 
     * @param int|array<int, int> $menuId Идентификатор элемента меню.
     * 
     * @return false|int Возвращает значение `false`, если ошибка выполнения запроса. 
     *     Иначе, количество удалённых записей.
     */
    public function deleteByMenu(int|array $menuId): false|int
    {
        return $this->deleteRecord(['menu_id' => $menuId]);
    }

    /**
     * Возвращает запись по указанному идентификатору элемента главного меню и роли 
     * пользователя.
     * 
     * @see ActiveRecord::selectOne()
     * 
     * @param int $menuId Идентификатор элемента главного меню.
     * @param int $roleId Идентификатор роли пользователя.
     * 
     * @return null|ActiveRecord Активная запись при успешном запросе, иначе `null`.
     */
    public function get(int $menuId, int $roleId)
    {
        return $this->selectOne([
            'menu_id' => $menuId,
            'role_id' => $roleId
        ]);
    }

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
        /** @var \Gm\Db\Adapter\Adapter $db */
        $db = $this->getDb();
        /** @var \Gm\Db\Sql\Select $select */
        $select = $db
            ->select($this->tableName())
            ->columns(['*'])
            ->where([
                // доступные роли пользователю
                'role_id' => Gm::userIdentity()->getRoles()->ids(false)
            ]);
        /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
        $command = $db
            ->createCommand($select)
                ->query();
        $rows = [];
        while ($row = $command->fetch()) {
            $rows[] = $row['menu_id'];
        }
        return $toString ? implode(',', $rows) : $rows;
    }

    /**
     * Возвращает все записи (элементы) главного меню соответствующие ролям пользователей.
     * 
     * Ключом каждой записи является значение первичного ключа {@see ActiveRecord::tableName()} 
     * текущей таблицы.
     * 
     * @see ActiveRecord::fetchAll()
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
                function () { return $this->fetchAll($this->primaryKey(), $this->maskedAttributes()); },
                null,
                true
            );
        else
            return $this->fetchAll($this->primaryKey(), $this->maskedAttributes());
    }
}
