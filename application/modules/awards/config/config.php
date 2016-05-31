<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Config;

class Config extends \Ilch\Config\Install
{
    public $config =
        [
        'key' => 'awards',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'fa-trophy',
        'languages' =>
            [
            'de_DE' =>
                [
                'name' => 'Auszeichnungen',
                'description' => 'Hier können Auszeichnungen für ein Team erstellt werden.',
                ],
            'en_EN' =>
                [
                'name' => 'Awards',
                'description' => 'Here you can create Awards for Teams.',
                ],
            ]
        ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_awards`');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_awards` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date` DATE NOT NULL,
                  `rank` INT(11) NOT NULL,
                  `event` VARCHAR(100) NOT NULL,
                  `url` VARCHAR(150) NOT NULL,
                  `ut_id` INT(11) NOT NULL,
                  `typ` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }

    public function getUpdate()
    {

    }
}
