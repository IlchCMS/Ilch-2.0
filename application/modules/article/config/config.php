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
                'description' => 'Hier können neue Artikel / News erstellt werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Articles',
                'description' => 'Here you can create articles.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        
        $articleMapper = new \Modules\Article\Mappers\Article();
        
        /*
         * @todo change content for different types.
         */
        $article = new \Modules\Article\Models\Article();
        $article->setContent('Guten Tag und willkommen auf meiner Internetseite! Auf dieser Seite möchte ich mich als Person vorstellen.');
        $article->setTitle('Startseite');
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
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `cat_id` int(11) NULL DEFAULT 0,
                  `date_created` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

                CREATE TABLE IF NOT EXISTS `[prefix]_articles_cats` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(100) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_articles_content` (
                  `article_id` int(11) NOT NULL,
                  `author_id` int(11) NOT NULL,
                  `visits` int(11) NOT NULL,
                  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
                  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
                  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `perma` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `article_img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `article_img_source` varchar(255) COLLATE utf8_unicode_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}
