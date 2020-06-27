<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace libraries\ilch;

use Ilch\Request;
use Ilch\Accesses;
use PHPUnit\Ilch\DatabaseTestCase;

class AccessesTest extends DatabaseTestCase
{
    /**
     * The object to test with.
     *
     * @var Request
     */
    protected $request;

    protected $_SESSION;

    protected static function getSchemaSQLQueries()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2;

            CREATE TABLE IF NOT EXISTS `[prefix]_users` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `first_name` VARCHAR(255) NOT NULL DEFAULT "",
                `last_name` VARCHAR(255) NOT NULL DEFAULT "",
                `gender` TINYINT(1) NOT NULL DEFAULT 0,
                `city` VARCHAR(255) NOT NULL DEFAULT "",
                `birthday` DATE NULL DEFAULT NULL,
                `avatar` VARCHAR(255) NOT NULL DEFAULT "",
                `signature` VARCHAR(255) NOT NULL DEFAULT "",
                `locale` VARCHAR(255) NOT NULL DEFAULT "",
                `opt_mail` TINYINT(1) DEFAULT 1,
                `opt_gallery` TINYINT(1) DEFAULT 1,
                `date_created` DATETIME NOT NULL,
                `date_confirmed` DATETIME NULL DEFAULT NULL,
                `date_last_activity` DATETIME NULL DEFAULT NULL,
                `confirmed` TINYINT(1) DEFAULT 1,
                `confirmed_code` VARCHAR(255) NULL DEFAULT NULL,
                `selector` char(18),
                `expires` DATETIME,
                `locked` TINYINT(1) NOT NULL DEFAULT 0,
                `selectsdelete` DATETIME,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

            CREATE TABLE IF NOT EXISTS `[prefix]_users_groups` (
                `user_id` INT(11) NOT NULL,
                `group_id` INT(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS `[prefix]_groups_access` (
                `group_id` INT(11) NOT NULL,
                `page_id` INT(11) DEFAULT 0,
                `module_key` VARCHAR(191) DEFAULT 0,
                `article_id` INT(11) DEFAULT 0,
                `box_id` INT(11) DEFAULT 0,
                `access_level` INT(11) DEFAULT 0,
                PRIMARY KEY (`group_id`, `page_id`, `module_key`, `article_id`, `box_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                (1, "Administrator"),
                (2, "User"),
                (3, "Guest");';
    }

    public function setUp()
    {
        parent::setUp();

        $this->request = new Request();
        $_SESSION = [];
    }

    /**
     * Returns the test dataset.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\YamlDataSet(__DIR__ . '/_files/mysql_accesses.yml');
    }

    public function testHasAccessModule()
    {
        $_SESSION['user_id'] = 1;
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        $this->assertTrue($result, 'User 1 should have access.');
    }

    /**
     * Check if hasAccess() returns true for a module when the user has access_level 2.
     */
    public function testHasAccessModuleArticle()
    {
        $_SESSION['user_id'] = 2;
        $this->request->setModuleName('article');
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        $this->assertTrue($result, 'User 2 should have access to the article module.');
    }

    /**
     * Check if hasAccess() returns true for a module when the user has access_level 1.
     */
    public function testHasAccessModuleCheckout()
    {
        $_SESSION['user_id'] = 2;
        $this->request->setModuleName('checkout');
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        $this->assertTrue($result, 'User 2 should have access to the checkout module.');
    }

    /**
     * Check if hasAccess() returns false for a module when the user has access_level 0.
     */
    public function testHasAccessModuleForumNoAccess()
    {
        $_SESSION['user_id'] = 2;
        $this->request->setModuleName('forum');
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        $this->assertFalse($result, 'User 2 should not have access to the forum module.');
    }

    public function testHasAccessAdmin()
    {
        $_SESSION['user_id'] = 1;
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Admin');
        $this->assertTrue($result, 'An admin should have access.');
    }

    /**
     * Test if hasAccess() (internally getAccessPage) returns true when the user should have access to the page.
     */
    public function testHasAccessPage()
    {
        $_SESSION['user_id'] = 2;
        $this->request->setModuleName('admin');
        $this->request->setControllerName('page');
        $this->request->setParam('id', 1);
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        $this->assertTrue($result, 'User 2 should have access to the page.');
    }

    /**
     * Test if hasAccess() (internally getAccessPage) returns false when the user should
     * not have access to the page.
     */
    public function testHasAccessPageNoAccess()
    {
        $_SESSION['user_id'] = 2;
        $this->request->setModuleName('admin');
        $this->request->setControllerName('page');
        $this->request->setParam('id', 2);
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        $this->assertFalse($result, 'User 2 should not have access to the page.');
    }
}
