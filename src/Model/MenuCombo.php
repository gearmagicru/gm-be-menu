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
use Gm\Panel\Data\Model\Combo\ComboModel;

/**
 * Модель данных выпадающего списка пунктов главного меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class MenuCombo extends ComboModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{panel_menu_locale}}',
            'primaryKey' => 'id',
            'order'      => ['index' => 'ASC'],
            'searchBy'   => 'name',
            'fields' => [
                ['name'],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM `{{panel_menu}}` WHERE `parent_id` is  null';

        $result = $this->selectBySql($sql);
        $result['rows'] = $this->module->tH($result['rows']);
        return $result;
    }
}
