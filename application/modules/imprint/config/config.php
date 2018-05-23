<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Imprint\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'imprint',
        'icon_small' => 'fa-paragraph',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Impressum',
                'description' => 'Hier kann das Impressum verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Imprint',
                'description' => 'Here you can manage your imprint.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        
        $databaseImpressum = new \Modules\Imprint\Mappers\Imprint($this->db());
        $databaseImpressum->set('imprint', '', 1);
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_imprint` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `imprint` MEDIUMTEXT NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

        INSERT INTO `[prefix]_imprint` (`imprint`) VALUES ("");';
    }

    public function getUpdate($installedVersion)
    {

    }
}
