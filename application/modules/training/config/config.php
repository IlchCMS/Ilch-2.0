<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Training\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'training',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'training.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Training',
                'description' => 'Hier kann die Trainingsliste verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Training',
                'description' => 'Here you can manage the Training list.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_training`;
                                 DROP TABLE `[prefix]_training_entrants`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_training` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(100) NOT NULL,
                  `date` DATETIME NOT NULL,
                  `time` INT(11) NOT NULL,
                  `place` VARCHAR(100) NOT NULL,
                  `contact` INT(11) NOT NULL,
                  `voice_server` INT(11) NOT NULL,
                  `voice_server_ip` VARCHAR(100) NOT NULL,
                  `voice_server_pw` VARCHAR(100) NOT NULL,
                  `game_server` INT(11) NOT NULL,
                  `game_server_ip` VARCHAR(100) NOT NULL,
                  `game_server_pw` VARCHAR(100) NOT NULL,
                  `text` MEDIUMTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_training_entrants` (
                  `train_id` INT(11) NOT NULL,
                  `user_id` INT(11) NOT NULL,
                  `note` VARCHAR(100) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}
