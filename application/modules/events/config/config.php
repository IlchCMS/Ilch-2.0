<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'events',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'events.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Veranstaltungen',
                'description' => 'Hier kannst du Veranstaltungen erstellen und bearbeiten.',
            ),
            'en_EN' => array
            (
                'name' => 'Events',
                'description' => 'Here you can add and change Events.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('event_uploadpath', 'application/modules/events/static/upload/image/');
        $databaseConfig->set('event_height', '150');
        $databaseConfig->set('event_width', '450');
        $databaseConfig->set('event_size', '102400');
        $databaseConfig->set('event_filetypes', 'jpg jpeg png gif');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_events`;
                                 DROP TABLE `[prefix]_events_entrants`;');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'event_uploadpath'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'event_height'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'event_width'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'event_size'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'event_filetypes'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_events` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `start` DATETIME NOT NULL,
                  `end` DATETIME NOT NULL,
                  `title` VARCHAR(100) NOT NULL,
                  `place` VARCHAR(100) NOT NULL,
                  `image` VARCHAR(255) NOT NULL,
                  `text` LONGTEXT NOT NULL,
                  `show` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_events_entrants` (
                  `event_id` INT(11) NOT NULL,
                  `user_id` INT(11) NOT NULL,
                  `status` INT(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}
