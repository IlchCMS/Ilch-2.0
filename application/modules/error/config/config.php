<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Error\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'error',
        'author' => 'Stantin, Thomas',
        'icon_small' => 'error.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Error',
                'description' => 'Hier kannst du die Fehlerseiten verwalten.',
            ),
            'en_EN' => array
            (
                'name' => 'Error',
                'description' => 'Here you can manage the error-pages.',
            ),
        )
    );

    public function install()
    {
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
    }
}
