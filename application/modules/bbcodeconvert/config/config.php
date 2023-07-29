<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Bbcodeconvert\Config;

use Ilch\Config\Install;

class Config extends Install
{
    public $config = [
        'key' => 'bbcodeconvert',
        'version' => '1.0.0',
        'icon_small' => 'fa-solid fa-arrow-right-arrow-left',
        'author' => 'ilch.de',
        'link' => 'https://www.ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'BBCode-Konvertierung',
                'description' => 'Modul zum Konvertierung von BBCode zu HTML.',
            ],
            'en_EN' => [
                'name' => 'BBCode Conversion',
                'description' => 'Module to convert BBCode to HTML.',
            ],
        ],
        'ilchCore' => '2.1.51',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
    }

    public function uninstall()
    {
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'bbcodeconvert_converted';");
    }

    public function getUpdate(string $installedVersion)
    {
    }
}
