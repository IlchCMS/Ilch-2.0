<?php
/**
 * Holds Admin\Config\Config.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Article\Config;
defined('ACCESS') or die('no direct access');

class Config extends \Ilch\Config\Install
{
    public $key = 'article';
    public $author = 'Meyer Dominik';
    public $name = array
    (
        'en_EN' => 'Articles',
        'de_DE' => 'Artikel',
    );
    public $icon_small = 'article.png';

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        
        $articleMapper = new \Article\Mappers\Article();
        
        /*
         * @todo change content for different types.
         */
        $article = new \Article\Models\Article();
        $article->setContent('Guten Tag und willkommen auf meiner Internetseite! Auf dieser Seite möchte ich mich als Person vorstellen.');
        $article->setTitle('Startseite');
        $article->setPerma('startseite.html');
        $articleMapper->save($article);
    }

    public function uninstall()
    {
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_articles` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `date_created` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

                CREATE TABLE IF NOT EXISTS `[prefix]_articles_content` (
                  `article_id` int(11) NOT NULL,
                  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
                  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                  `perma` varchar(255) COLLATE utf8_unicode_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
    }
}
