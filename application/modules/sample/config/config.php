<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Sample\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'sample',
        'version' => '1.0.0',
        'icon_small' => '',
        'author' => '',
        'link' => '',
        // 'isLayout' => true,
        // 'hide_menu' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Sample',
                'description' => '',
            ],
            'en_EN' => [
                'name' => 'Sample',
                'description' => '',
            ],
        ],
        'ilchCore' => '2.0.0',
        'phpVersion' => '5.6'
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
