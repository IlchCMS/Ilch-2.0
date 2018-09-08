<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Config;

class Config extends \Ilch\Config\Install
{
    const EVENT_SAVE_BEFORE = 'article_save_before';
    const EVENT_SAVE_AFTER = 'article_save_after';
    const EVENT_DELETE_BEFORE = 'article_delete_before';
    const EVENT_DELETE_AFTER = 'article_delete_after';
    const EVENT_EDITARTICLE_AFTER = 'article_editArticle_after';
    const EVENT_ADDARTICLE_AFTER = 'article_addArticle_after';
    const EVENT_SAVECATEGORY_BEFORE = 'article_saveCategory_before';
    const EVENT_SAVECATEGORY_AFTER = 'article_saveCategory_after';
    const EVENT_DELETECATEGORY_BEFORE = 'article_deleteCategory_before';
    const EVENT_DELETECATEGORY_AFTER = 'article_deleteCategory_after';
    const EVENT_EDITCATEGORY_AFTER = 'article_editCategory_after';
    const EVENT_ADDCATEGORY_AFTER = 'article_addCategory_after';

    const COMMENT_KEY_TPL = 'article/index/show/id/%d';

    public $config = [
        'key' => 'article',
        'icon_small' => 'fa-quote-right',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Artikel',
                'description' => 'Hier kann man die Artikel / News verwalten.',
            ],
            'en_EN' => [
                'name' => 'Articles',
                'description' => 'Here you can manage the Articles / News.',
            ],
        ],
        'boxes' => [
            'article' => [
                'de_DE' => [
                    'name' => 'Artikel'
                ],
                'en_EN' => [
                    'name' => 'Article'
                ]
            ],
            'archive' => [
                'de_DE' => [
                    'name' => 'Artikel Archiv'
                ],
                'en_EN' => [
                    'name' => 'Article Archive'
                ]
            ],
            'categories' => [
                'de_DE' => [
                    'name' => 'Artikel Kategorien'
                ],
                'en_EN' => [
                    'name' => 'Article Categories'
                ]
            ],
            'keywords' => [
                'de_DE' => [
                    'name' => 'Artikel Keywords'
                ],
                'en_EN' => [
                    'name' => 'Article Keywords'
                ]
            ]
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('article_box_articleLimit', '5');
        $databaseConfig->set('article_box_archiveLimit', '5');
        $databaseConfig->set('article_box_keywords', '17,20,23,26');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_articles` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `cat_id` VARCHAR(255) NOT NULL,
                  `date_created` DATETIME NOT NULL,
                  `top` TINYINT(1) NOT NULL DEFAULT 0,
                  `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1 ;

                CREATE TABLE IF NOT EXISTS `[prefix]_articles_cats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(100) NOT NULL,
                  `sort` INT(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_articles_content` (
                  `article_id` INT(11) NOT NULL,
                  `author_id` INT(11) NOT NULL,
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  `content` MEDIUMTEXT NOT NULL,
                  `description` MEDIUMTEXT NOT NULL,
                  `keywords` VARCHAR(255) NOT NULL,
                  `locale` VARCHAR(255) NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `teaser` VARCHAR(255) NOT NULL,
                  `perma` VARCHAR(255) NOT NULL,
                  `img` VARCHAR(255) NOT NULL,
                  `img_source` VARCHAR(255) NOT NULL,
                  `votes` LONGTEXT NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

                INSERT INTO `[prefix]_articles` (`cat_id`, `date_created`, `top`) VALUES (1, now(), 0);

                INSERT INTO `[prefix]_articles_cats` (`name`) VALUES ("Allgemein");

                INSERT INTO `[prefix]_articles_content` (`article_id`, `author_id`, `title`, `teaser`, `content`, `keywords`, `perma`) VALUES
                (1, 1, "Willkommen", "Willkommen beim Ilch CMS!", "<p>Dies ist dein erster Artikel mit dem Ilch - Content Management System</p><p>Bei Fragen oder Probleme im <a href=\"http://www.ilch.de/forum.html\">Ilch Forum</a>&nbsp;melden.</p><p>Viel Erfolg<br />Ilch</p>", "willkommen, ilch, cms, news", "willkommen.html");';
    }

    public function getUpdate($installedVersion)
    {

    }
}
