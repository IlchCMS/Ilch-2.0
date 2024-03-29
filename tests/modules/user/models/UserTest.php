<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use Ilch\Request;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Article\Config\Config as ArticleConfig;

/**
 * Tests the user model class.
 *
 * @package ilch_phpunit
 */
class UserTest extends DatabaseTestCase
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
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../../../libraries/ilch/_files/mysql_accesses.yml');
    }

    /**
     * Tests if the user id can be set and returned again.
     */
    public function testSetGetId()
    {
        $user = new User();
        $user->setId(123);
        self::assertEquals(123, $user->getId(), 'The id wasnt saved or returned correctly.');
    }

    /**
     * Tests if the username can be set and returned again.
     */
    public function testSetGetUsername()
    {
        $user = new User();
        $user->setName('username');
        self::assertEquals('username', $user->getName(), 'The username wasnt saved or returned correctly.');
    }

    /**
     * Tests if the email address can be set and returned again.
     */
    public function testSetGetEmail()
    {
        $user = new User();
        $user->setEmail('email');
        self::assertEquals('email', $user->getEmail(), 'The email wasnt saved or returned correctly.');
    }

    /**
     * Tests if the user groups can be set and returned again.
     */
    public function testSetGetGroups()
    {
        $group1 = new Group();
        $group1->setId(1);
        $group1->setName('Admin');
        $group2 = new Group();
        $group2->setId(2);
        $group2->setName('Member');
        $group3 = new Group();
        $group3->setId(3);
        $group3->setName('Clanleader');
        $user = new User();
        $user->setGroups([$group1, $group2, $group3]);
        self::assertEquals(
            [$group1, $group2, $group3],
            $user->getGroups(),
            'The user groups wasnt saved or returned correctly.'
        );
    }

    /**
     * Tests if its possible to add groups.
     */
    public function testAddGroup()
    {
        $group1 = new Group();
        $group1->setId(1);
        $group1->setName('Admin');
        $group2 = new Group();
        $group2->setId(2);
        $group2->setName('Member');
        $group3 = new Group();
        $group3->setId(3);
        $group3->setName('Clanleader');
        $user = new User();
        $user->setGroups([$group1, $group3]);
        $user->addGroup($group2);
        self::assertEquals(
            [$group1, $group3, $group2],
            $user->getGroups(),
            'The user groups wasnt added or returned correctly.'
        );
    }

    /**
     * Tests if user has the given groups.
     */
    public function testHasGroup()
    {
        $user = new User();

        $group1 = new Group();
        $group1->setId(1);
        $group1->setName('Admin');

        $group2 = new Group();
        $group2->setId(2);
        $group2->setName('Member');

        $group3 = new Group();
        $group3->setId(3);
        $group3->setName('Clanleader');

        $user->addGroup($group1)->addGroup($group2)->addGroup($group3);

        self::assertTrue($user->hasGroup(2), 'The user has not the group with id "2".');
        self::assertFalse($user->hasGroup(4), 'The user has the group with id "4".');

        $user->setGroups([]);
        self::assertFalse($user->hasGroup(2), 'The user has the group with id "2".');
    }

    /**
     * Tests if the access for a user can be returned.
     */
    public function testHasAccess()
    {
        $group = new Group();
        $group->setId(3);
        $group->setName('Testgroup');
        $user = new User();
        $user->setId(123);
        $user->addGroup($group);

        self::assertFalse($user->hasAccess('module_user'));
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
