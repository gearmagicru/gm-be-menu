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
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля записи выбора роли пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class RolesGridRow extends FormModel
{
    /**
     * Идентификатор роли пользователя.
     * 
     * @var int
     */
    protected int $roleId;

    /**
     * Идентификатор пункта меню.
     * 
     * @var int
     */
    protected int $menuId; 

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
                ['available']
            ],
            'resetIncrements' => ['{{panel_menu_roles}}']
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
                // если успешно добавлен доступ
                if ($message['success']) {
                    // если выбранная роль входит в меню
                    $available = (int) $this->available;
                    $message['message'] = $this->module->t(
                        'Menu element for user role {0} - ' . ($available > 0 ? 'enabled' : 'disabled'),
                        [$this->name]
                    );
                    $message['title'] = $this->t('Access to the menu');
                }
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
            });
    }

    /**
     * {@inheritdoc}
     */
    public function get(mixed $identifier = null): ?static
    {
        // т.к. записи формируются при выводе списка, то нет
        // необходимости делать запрос к бд (нет основной таблицы)
        return $this;
    }

    /**
     * Возвращает идентификатор роли пользователя.
     * 
     * @return int
     */
    public function getRoleId()
    {
        if (!isset($this->roleId)) {
            $this->roleId = $this->getIdentifier();
        }
        return $this->roleId;
    }

    /**
     * Возвращает идентификатор пункта меню.
     * 
     * @return int
     */
    public function getMenuId(): int
    {
        if (!isset($this->menuId)) {
            $store = $this->module->getStorage();
            $this->menuId = isset($store->menuItem['id']) ? (int) $store->menuItem['id'] : 0;
        }
        return $this->menuId;
    }

    /**
     * {@inheritdoc}
     */
    protected function insertProcess(array $attributes = null): false|int|string
    {
        if (!$this->beforeSave(true))
            return false;

        $columns = [];
        // если выбранная роль принадлежит пункту меню
        if ((int) $this->available > 0) {
            $columns = [
                'menu_id' => $this->getMenuId(),
                'role_id' => $this->getRoleId()
            ];
            $this->insertRecord($columns);
            // т.к. ключ составной, то при добавлении всегда будет "0"
            $this->result = 1;
        // если выбранная роль не принадлежит пункту меню
        } else {
            $this->result = $this->deleteRecord([
                'menu_id' => $this->getMenuId(),
                'role_id' => $this->getRoleId()
            ]);
        }
        $this->afterSave(true, $columns, $this->result, $this->saveMessage(true, (int) $this->result));
        return $this->result;
    }
}
