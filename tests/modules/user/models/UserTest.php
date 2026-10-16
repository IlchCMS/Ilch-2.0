<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use Ilch\Date;
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
    protected static array $fixtureFiles = [__DIR__ . '/../../../libraries/ilch/_files/mysql_accesses.yml'];

    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);
    }

    /**
     * Tests if the user id can be set and returned again.
     */
    public function testSetGetId()
    {
        $user = new User();
        $user->setId(123);
        self::assertEquals(123, $user->getId(), 'The id was not saved or returned correctly.');
    }

    /**
     * Tests if the username can be set and returned again.
     */
    public function testSetGetUsername()
    {
        $user = new User();
        $user->setName('username');
        self::assertEquals('username', $user->getName(), 'The username was not saved or returned correctly.');
    }

    /**
     * Tests if the email address can be set and returned again.
     */
    public function testSetGetEmail()
    {
        $user = new User();
        $user->setEmail('email');
        self::assertEquals('email', $user->getEmail(), 'The email address was not saved or returned correctly.');
    }

    /**
     * Tests if the password can be set and returned again.
     */
    public function testSetGetPassword()
    {
        $user = new User();
        $user->setPassword('password');
        self::assertEquals('password', $user->getPassword(), 'The password was not saved or returned correctly.');
    }

    /**
     * Tests if the locale can be set and returned again.
     */
    public function testSetGetLocale()
    {
        $user = new User();
        $user->setLocale('de_DE');
        self::assertEquals('de_DE', $user->getLocale(), 'The locale was not saved or returned correctly.');
    }

    /**
     * Tests if the opt_mail value can be set and returned again.
     */
    public function testSetGetOptMail()
    {
        $user = new User();
        $user->setOptMail(0);
        self::assertEquals(0, $user->getOptMail(), 'The opt_mail value was not saved or returned correctly.');
    }

    /**
     * Tests if the opt_comments value can be set and returned again.
     */
    public function testSetGetOptComments()
    {
        $user = new User();
        $user->setOptComments(1);
        self::assertEquals(1, $user->getOptComments(), 'The opt_comments value was not saved or returned correctly.');
    }

    /**
     * Tests if the admin_comments value can be set and returned again.
     */
    public function testSetGetAdminComments()
    {
        $user = new User();
        $user->setAdminComments(0);
        self::assertEquals(0, $user->getAdminComments(), 'The admin_comments value was not saved or returned correctly.');
    }

    /**
     * Tests if the opt_gallery value can be set and returned again.
     */
    public function testSetGetOptGallery()
    {
        $user = new User();
        $user->setOptGallery(1);
        self::assertEquals(1, $user->getOptGallery(), 'The opt_gallery value was not saved or returned correctly.');
    }

    /**
     * Tests if the confirmed value can be set and returned again.
     */
    public function testSetGetConfirmed()
    {
        $user = new User();
        $user->setConfirmed(0);
        self::assertEquals(0, $user->getConfirmed(), 'The confirmed value was not saved or returned correctly.');
    }

    /**
     * Tests if the confirmed code can be set and returned again.
     */
    public function testSetGetConfirmedCode()
    {
        $user = new User();
        $user->setConfirmedCode('123456');
        self::assertEquals('123456', $user->getConfirmedCode(), 'The confirmed code was not saved or returned correctly.');
    }

    /**
     * Tests if the selector can be set and returned again.
     */
    public function testSetGetSelector()
    {
        $user = new User();
        $user->setSelector('selector');
        self::assertEquals('selector', $user->getSelector(), 'The selector was not saved or returned correctly.');
    }

    /**
     * Tests if the expires date can be set and returned again.
     */
    public function testSetGetExpires()
    {
        $user = new User();
        $date = new Date();
        $user->setExpires($date);
        self::assertEquals($date, $user->getExpires(), 'The expires date was not saved or returned correctly.');
    }

    /**
     * Tests if the date created can be set and returned again.
     */
    public function testSetGetDateCreated()
    {
        $user = new User();
        $date = new Date();
        $user->setDateCreated($date);
        self::assertEquals($date, $user->getDateCreated(), 'The date created was not saved or returned correctly.');
    }

    /**
     * Tests if the date confirmed can be set and returned again.
     */
    public function testSetGetDateConfirmed()
    {
        $user = new User();
        $date = new Date();
        $user->setDateConfirmed($date);
        self::assertEquals($date, $user->getDateConfirmed(), 'The date confirmed was not saved or returned correctly.');
    }

    /**
     * Tests if the date last activity can be set and returned again.
     */
    public function testSetGetDateLastActivity()
    {
        $user = new User();
        $date = new Date();
        $user->setDateLastActivity($date);
        self::assertEquals($date, $user->getDateLastActivity(), 'The date last activity was not saved or returned correctly.');
    }

    /**
     * Tests if the firstname can be set and returned again.
     */
    public function testSetGetFirstName()
    {
        $user = new User();
        $user->setFirstName('Max');
        self::assertEquals('Max', $user->getFirstName(), 'The firstname was not saved or returned correctly.');
    }

    /**
     * Tests if the lastname can be set and returned again.
     */
    public function testSetGetLastName()
    {
        $user = new User();
        $user->setLastName('Mustermann');
        self::assertEquals('Mustermann', $user->getLastName(), 'The lastname was not saved or returned correctly.');
    }

    /**
     * Tests if the gender can be set and returned again.
     */
    public function testSetGetGender()
    {
        $user = new User();
        $user->setGender(1);
        self::assertEquals(1, $user->getGender(), 'The gender was not saved or returned correctly.');
    }

    /**
     * Tests if the avatar can be set and returned again.
     */
    public function testSetGetAvatar()
    {
        $user = new User();
        $user->setAvatar('avatar.png');
        self::assertEquals('avatar.png', $user->getAvatar(), 'The avatar was not saved or returned correctly.');
    }

    /**
     * Tests if the signature can be set and returned again.
     */
    public function testSetGetSignature()
    {
        $user = new User();
        $user->setSignature('signature');
        self::assertEquals('signature', $user->getSignature(), 'The signature was not saved or returned correctly.');
    }

    /**
     * Tests if the city can be set and returned again.
     */
    public function testSetGetCity()
    {
        $user = new User();
        $user->setCity('Berlin');
        self::assertEquals('Berlin', $user->getCity(), 'The city was not saved or returned correctly.');
    }

    /**
     * Tests if the birthday can be set and returned again.
     */
    public function testSetGetBirthday()
    {
        $user = new User();
        $user->setBirthday('1990-01-01');
        self::assertEquals('1990-01-01', $user->getBirthday(), 'The birthday was not saved or returned correctly.');
    }

    /**
     * Tests if the birthday 0000-00-00 is returned as an empty string.
     */
    public function testGetBirthdayZeroDate()
    {
        $user = new User();
        $user->setBirthday('0000-00-00');
        self::assertSame('', $user->getBirthday(), 'The birthday "0000-00-00" was not returned as an empty string.');
    }

    /**
     * Tests if the locked value can be set and returned again.
     */
    public function testSetGetLocked()
    {
        $user = new User();
        $user->setLocked(1);
        self::assertEquals(1, $user->getLocked(), 'The locked value was not saved or returned correctly.');
    }

    /**
     * Tests if the selectsdelete date can be set and returned again.
     */
    public function testSetGetSelectsDelete()
    {
        $user = new User();
        $date = new Date();
        $user->setSelectsDelete($date);
        self::assertEquals($date, $user->getSelectsDelete(), 'The selectsdelete date was not saved or returned correctly.');
    }

    /**
     * Tests if the default values are set correctly.
     */
    public function testDefaultValues()
    {
        $user = new User();

        self::assertNull($user->getId(), 'The default id is not null.');
        self::assertEquals(0, $user->getLocked(), 'The default locked value is not 0.');
        self::assertEquals([], $user->getGroups(), 'The default groups are not an empty array.');
    }

    /**
     * Tests that the default values of the user model match the default values
     * of the users table (see getInstallSql() of Modules\User\Config\config.php).
     *
     * During installation the first user is created without explicitly setting
     * "confirmed". If the model default differs from the database default, the
     * stored value is silently wrong - e.g. the first user would not be
     * confirmed and could not log in.
     */
    public function testDefaultValuesMatchDatabaseSchema()
    {
        $user = new User();

        // `confirmed` TINYINT(1) DEFAULT 1
        self::assertEquals(
            1,
            $user->getConfirmed(),
            'The default "confirmed" value of the user model is not 1 like in the users table. A user created without explicitly setting "confirmed" (e.g. the first user during installation) would not be confirmed and could not log in.'
        );
        // `locked` TINYINT(1) NOT NULL DEFAULT 0
        self::assertEquals(
            0,
            $user->getLocked(),
            'The default "locked" value of the user model is not 0 like in the users table.'
        );
        // `gender` TINYINT(1) NOT NULL DEFAULT 0
        self::assertEquals(
            0,
            $user->getGender(),
            'The default "gender" value of the user model is not 0 like in the users table.'
        );
    }

    /**
     * Tests that the install SQL keeps the default value 1 for the "confirmed"
     * column of the users table, so that a user created without explicitly
     * setting "confirmed" (e.g. the first user during installation) is confirmed.
     */
    public function testInstallSqlKeepsConfirmedDefault()
    {
        $config = new UserConfig();

        self::assertStringContainsString(
            '`confirmed` TINYINT(1) DEFAULT 1',
            $config->getInstallSql(),
            'The default value of the "confirmed" column of the users table is not 1 anymore.'
        );
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
            'The user groups was not saved or returned correctly.'
        );
    }

    /**
     * Tests if it is possible to add groups.
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
            'The user groups was not added or returned correctly.'
        );
    }

    /**
     * Tests if adding an already existing group does not duplicate it.
     */
    public function testAddGroupIgnoresDuplicates()
    {
        $group1 = new Group();
        $group1->setId(1);
        $group1->setName('Admin');
        $user = new User();
        $user->addGroup($group1);
        $user->addGroup($group1);

        self::assertCount(1, $user->getGroups(), 'The same group was added twice.');
    }

    /**
     * Tests if the setters are chainable and return the user.
     */
    public function testSettersReturnSelf()
    {
        $user = new User();

        self::assertSame($user, $user->setId(1), 'setId() does not return the user.');
        self::assertSame($user, $user->setName('username'), 'setName() does not return the user.');
        self::assertSame($user, $user->setEmail('email@example.com'), 'setEmail() does not return the user.');
        self::assertSame($user, $user->setPassword('password'), 'setPassword() does not return the user.');
        self::assertSame($user, $user->setLocale('de_DE'), 'setLocale() does not return the user.');
        self::assertSame($user, $user->setConfirmed(1), 'setConfirmed() does not return the user.');
        self::assertSame($user, $user->setGroups([]), 'setGroups() does not return the user.');
        self::assertSame($user, $user->addGroup(new Group()), 'addGroup() does not return the user.');
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
     * Tests if a user with the administrator group is an admin.
     */
    public function testIsAdmin()
    {
        $user = new User();

        $group = new Group();
        $group->setId(1);
        $group->setName('Administrator');

        $user->addGroup($group);

        self::assertTrue($user->isAdmin(), 'A user with group id "1" is not an admin.');
    }

    /**
     * Tests if a user without the administrator group is not an admin.
     */
    public function testIsNotAdmin()
    {
        $user = new User();

        $group = new Group();
        $group->setId(2);
        $group->setName('User');

        $user->addGroup($group);

        self::assertFalse($user->isAdmin(), 'A user without group id "1" is an admin.');
    }

    /**
     * Tests if a user without any groups has no access.
     */
    public function testHasAccessWithoutGroups()
    {
        $user = new User();
        $user->setId(999);

        self::assertFalse($user->hasAccess('module_user'), 'A user without groups has access to the user module.');
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
     * Returns database schema SQL statements to initialize database
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
