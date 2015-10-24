<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'faq',
        'author' => 'Veldscholten, Kevin',
        'icon_small' => 'faq.png',
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'F.A.Q.',
                'description' => 'Hier können die FAQ - Häufig gestellte Fragen verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'F.A.Q.',
                'description' => 'Here you can manage your FAQ - Frequently Asked Questions.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_faqs`;
                                 DROP TABLE `[prefix]_faqs_cats`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_faqs` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `cat_id` INT(11) NULL DEFAULT 0,
                  `question` VARCHAR(100) NOT NULL,
                  `answer` MEDIUMTEXT NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_faq_cats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(100) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;';
    }
}
