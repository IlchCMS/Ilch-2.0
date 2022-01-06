<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\modulCreator\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'modulCreator',
        'version' => '1.0.0',
        'icon_small' => '',
        'author' => 'RTX2070',
        'link' => '',
        // 'isLayout' => true,
        // 'hide_menu' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'ModulCreator',
                'description' => 'Ermöglich auch Module zu erstellen',
            ],
            'en_EN' => [
                'name' => 'ModulCreator',
                'description' => 'Make your own Module',
            ],
        ],
        'ilchCore' => '2.1.43',
        'phpVersion' => '7.4'
    ];

    public function install()
    {
    }

    public function uninstall()
    {
    }

    public function getUpdate($installedVersion)
    {
    }
}
