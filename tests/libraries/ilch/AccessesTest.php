<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace libraries\ilch;

use Ilch\Request;
use Ilch\Accesses;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as UserConfig;
use Modules\User\Mappers\User as UserMapper;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Article\Config\Config as ArticleConfig;

class AccessesTest extends DatabaseTestCase
{
    /**
     * The object to test with.
     *
     * @var Request
     */
    protected $request;

    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/_files/mysql_accesses.yml');
        $this->request = new Request();

        $this->request->setControllerName('index');
        $this->request->setActionName('index');
    }

    /**
     * Check if hasAccess() returns true for a module when the user has access_level 2.
     *
     * @param int $uid
     */
    private function setUser(int $uid)
    {
        /** @var \Modules\User\Models\User $user */
        $user = \Ilch\Registry::get('user');
        if ($user && $user->getId() === $uid) {
            return;
        }

        $userMapper = new UserMapper();
        $user = $userMapper->getUserById($uid);
        if ($user) {
            if (\Ilch\Registry::has('user')) {
                \Ilch\Registry::remove('user');
            }
            \Ilch\Registry::set('user', $user);
        } else {
            \Ilch\Registry::remove('user');
        }
    }

    public function testHasAccessAdmin()
    {
        $this->setUser(2);
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Admin');
        self::assertTrue($result, 'An admin should have access.');
    }

    public function testHasAccessModule()
    {
        $this->setUser(1);
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        self::assertTrue($result, 'User 1 should have access.');
    }

    /**
     * Check if hasAccess() returns true for a module when the user has access_level 2.
     */
    public function testHasAccessModuleArticle()
    {
        $this->setUser(2);
        $this->request->setModuleName('article');
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        self::assertTrue($result, 'User 2 should have access to the article module.');
    }

    /**
     * Check if hasAccess() returns true for a module when the user has access_level 1.
     */
    public function testHasAccessModuleCheckout()
    {
        $this->setUser(2);
        $this->request->setModuleName('checkout');
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        self::assertTrue($result, 'User 2 should have access to the checkout module.');
    }

    /**
     * Check if hasAccess() returns false for a module when the user has access_level 0.
     */
    public function testHasAccessModuleForumNoAccess()
    {
        $this->setUser(2);
        $this->request->setModuleName('forum');
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        var_dump($result);
        self::assertFalse($result, 'User 2 should not have access to the forum module.');
    }

    /**
     * Test if hasAccess() (internally getAccessPage) returns true when the user should have access to the page.
     */
    public function testHasAccessPage()
    {
        $this->setUser(2);
        $this->request->setModuleName('admin');
        $this->request->setControllerName('page');
        $this->request->setParam('id', 1);
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        self::assertTrue($result, 'User 2 should have access to the page.');
    }

    /**
     * Test if hasAccess() (internally getAccessPage) returns false when the user should
     * not have access to the page.
     */
    public function testHasAccessPageNoAccess()
    {
        $this->setUser(2);
        $this->request->setModuleName('admin');
        $this->request->setControllerName('page');
        $this->request->setParam('id', 2);
        $accesses = new Accesses($this->request);

        $result = $accesses->hasAccess('Module');
        self::assertFalse($result, 'User 2 should not have access to the page.');
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();
        $configArticle = new ArticleConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $configArticle->getInstallSql();
    }
}
