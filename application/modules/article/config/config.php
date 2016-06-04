<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Config;

class Config extends \Ilch\Config\Install
{
    public $config =
        [
        'key' => 'article',
        'icon_small' => 'fa-quote-right',
        'system_module' => true,
        'languages' =>
            [
            'de_DE' =>
                [
                'name' => 'Artikel',
                'description' => 'Hier kann man die Artikel / News verwalten.',
                ],
            'en_EN' =>
                [
                'name' => 'Articles',
                'description' => 'Here you can magnage the Articles / News.',
                ],
            ]
        ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_articles`;
                                 DROP TABLE `[prefix]_articles_cats`;
                                 DROP TABLE `[prefix]_articles_content`;');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_articles` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `cat_id` INT(11) NULL DEFAULT 0,
                  `date_created` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

                CREATE TABLE IF NOT EXISTS `[prefix]_articles_cats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(100) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_articles_content` (
                  `article_id` INT(11) NOT NULL,
                  `author_id` INT(11) NOT NULL,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  `content` MEDIUMTEXT NOT NULL,
                  `description` MEDIUMTEXT NULL DEFAULT NULL,
                  `locale` VARCHAR(255) NULL DEFAULT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `perma` VARCHAR(255) NULL DEFAULT NULL,
                  `article_img` VARCHAR(255) NULL DEFAULT NULL,
                  `article_img_source` VARCHAR(255) NULL DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

                INSERT INTO `[prefix]_articles` (`cat_id`, `date_created`) VALUES (1, now());

                INSERT INTO `[prefix]_articles_cats` (`name`) VALUES ("Allgemein");

                INSERT INTO `[prefix]_articles_content` (`article_id`, `author_id`, `content`, `title`, `perma`) VALUES
                (1, 1, "Willkommen auf meiner Internetseite! Auf dieser Seite möchte ich mich als Person vorstellen.", "Startseite", "startseite.html");';
    }

    public function getUpdate()
    {

    }
}
