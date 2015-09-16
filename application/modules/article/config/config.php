<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Config;

defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'article',
        'icon_small' => 'article.png',
        'system_module' => true,
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Artikel',
                'description' => 'Hier kann man die Artikel / News verwalten.',
            ),
            'en_EN' => array
            (
                'name' => 'Articles',
                'description' => 'Here you can magnage the Articles / News.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $articleMapper = new \Modules\Article\Mappers\Article();
        $catMapper = new \Modules\Article\Mappers\Category();

        /*
         * @todo change content for different types.
         */

        $cat = new \Modules\Article\Models\Category();
        $cat->setName('Allgemein');
        $catMapper->save($cat);

        $article = new \Modules\Article\Models\Article();
        $article->setCatId(1);
        $article->setTitle('Startseite');
        $article->setAuthorId(1);
        $article->setContent('Willkommen auf meiner Internetseite! Auf dieser Seite möchte ich mich als Person vorstellen.');
        $article->setPerma('startseite.html');
        $articleMapper->save($article);
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
                  `visits` INT(11) NOT NULL,
                  `content` MEDIUMTEXT NOT NULL,
                  `description` MEDIUMTEXT NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `perma` VARCHAR(255) NOT NULL,
                  `article_img` VARCHAR(255) NOT NULL,
                  `article_img_source` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}
