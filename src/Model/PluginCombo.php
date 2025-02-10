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
 * Модель данных выпадающего списка плагинов меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class PluginCombo extends ComboModel
{
    /**
     * Возвращает имена плагинов с их заголовками.
     * 
     * @return array
     */
    public function getPlugins(): array
    {
        $rows = $this->module
            ->getPluginManager()
                ->getPluginRows('title', [$this->module, 't']);
        array_unshift(
            $rows,
            ['null', Gm::t(BACKEND, '[None]')]
        );
        return $rows;
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        $plugins = $this->getPlugins();
        return [
            'rows'  => $plugins,
            'total' => sizeof($plugins)
        ];
    }
}
