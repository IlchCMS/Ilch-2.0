<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'newsletter',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'newsletter.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Newsletter',
                'description' => 'Hier kannst du Newsletter verschicken.',
            ),
            'en_EN' => array
            (
                'name' => 'Newsletter',
                'description' => 'Here you can send Newsletter.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_newsletter`;
                                 DROP TABLE `[prefix]_newsletter_mails`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_newsletter` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `subject` varchar(100) NOT NULL,
                  `text` LONGTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        
                CREATE TABLE IF NOT EXISTS `[prefix]_newsletter_mails` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `email` VARCHAR(100) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}
