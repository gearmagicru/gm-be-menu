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
 * Модель данных профиля записи пункта меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Menu\Model
 * @since 1.0
 */
class GridRow extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{panel_menu}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['enabled', 'alias' => 'isEnabled'],
                ['visible', 'alias' => 'isVisible']
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
                /** @var \Gm\Panel\Http\Response $response */
                $response = $this->response();
                if ($message['success']) {
                    if (isset($columns['visible'])) {
                        $visible = (int) $columns['visible'];
                        $message['message'] = $this->t('Menu element - ' . ($visible > 0 ? 'show' : 'hide'));
                        $message['title']   = $this->t($visible > 0 ? 'Show' : 'Hide');
                        // скрыть / показать пункт меню в интерфейсе
                        $response
                            ->meta
                                ->cmdComponent('g-menu-item-' . $this->getIdentifier(), $visible > 0 ? 'show' : 'hide');
                    }
                    if (isset($columns['enabled'])) {
                        $enabled = (int) $columns['enabled'];
                        $message['message'] = $this->t('Menu element - ' . ($enabled > 0 ? 'enabled' : 'disabled'));
                        $message['title']   = $this->t($enabled > 0 ? 'Enabled' : 'Disabled');
                        // отключить / подключить пункт меню  в интерфейсе
                        $response
                            ->meta
                                ->cmdComponent('g-menu-item-' . $this->getIdentifier(), 'setDisabled', [$enabled > 0 ? false : true]);
                    }
                }
                // всплывающие сообщение
                $response
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
            });
    }
}
