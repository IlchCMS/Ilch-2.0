<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Error\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'error',
        'icon_small' => 'fa-solid fa-triangle-exclamation',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Error',
                'description' => 'Hier kannst du die Fehlerseiten verwalten.',
            ],
            'en_EN' => [
                'name' => 'Error',
                'description' => 'Here you can manage the error-pages.',
            ],
        ]
    ];

    public function install()
    {
    }

    public function getInstallSql()
    {
    }

    public function getUpdate(string $installedVersion)
    {
    }
}
