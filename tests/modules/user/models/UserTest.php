<?php
/**
 * Holds class Modules_User_Models_UserTest.
 *
 * @author Jainta Martin
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

use User\Models\User as UserModel;
defined('ACCESS') or die('no direct access');

/**
 * Tests the user model class.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class Modules_User_Models_UserTest extends PHPUnit_Ilch_TestCase
{
    /**
     * Filling the timezone which the Ilch_Date object will use.
     *
     * @var Array
     */
    protected $_configData = array
    (
        'timezone' => 'Europe/Berlin'
    );

    /**
     * Tests if the user id can be set and returned again.
     */
    public function testSetGetId()
    {
        $user = new UserModel();
        $user->setId(123);
        $this->assertEquals(123, $user->getId(), 'The id wasnt saved or returned correctly.');
    }

    /**
     * Tests if the username can be set and returned again.
     */
    public function testSetGetUsername()
    {
        $user = new UserModel();
        $user->setName('username');
        $this->assertEquals('username', $user->getName(), 'The username wasnt saved or returned correctly.');
    }

    /**
     * Tests if the email address can be set and returned again.
     */
    public function testSetGetEmail()
    {
        $user = new UserModel();
        $user->setEmail('email');
        $this->assertEquals('email', $user->getEmail(), 'The email wasnt saved or returned correctly.');
    }

    /**
     * Tests if the date_created can be set and returned again using a timestamp.
     */
    public function testSetGetDateCreatedFromStamp()
    {
        $user = new UserModel();
        $user->setDateCreated(123456789);
        $actualDate = $user->getDateCreated();

        $this->assertInstanceOf('\\Ilch\\Date', $actualDate, 'The date_created was not created using Ilch_Date.');
        $this->assertEquals(123456789, $actualDate->getTimestamp(), 'The timestamp of the date_created wasnt saved or returned correctly.');
    }

    /**
     * Tests if the date_created can be set and returned again if using a
     * date object.
     */
    public function testSetGetDateCreatedFromDate()
    {
        $user = new UserModel();
        $date = new \Ilch\Date();
        $expectedTimestamp = $date->getTimestamp();
        $user->setDateCreated($date);
        $actualDate = $user->getDateCreated();

        $this->assertEquals($expectedTimestamp, $actualDate->getTimestamp(), 'The date_created wasnt saved or returned correctly using a Ilch_Date object.');
    }

    /**
     * Tests if the date_created can be set and returned again if using a string.
     */
    public function testSetDateCreatedFromString()
    {
        $user = new UserModel();
        $user->setDateCreated('2013-09-02 22:13:52');
        $actualDate = $user->getDateCreated();

        $this->assertInstanceOf('\\Ilch\\Date', $actualDate, 'The date_created was not created using Ilch_Date.');
        $this->assertEquals('2013-09-02 22:13:52', $actualDate->format('Y-m-d H:i:s'), 'The date_created does not got saved correctly using a String.');
    }

    /**
     * Tests if the date_confirmed can be set and returned again using a timestamp.
     */
    public function testSetGetDateConfirmedFromStamp()
    {
        $user = new UserModel();
        $user->setDateConfirmed(987654321);
        $actualDate = $user->getDateConfirmed();

        $this->assertInstanceOf('\\Ilch\\Date', $actualDate, 'The date_confirmed was not created using Ilch_Date.');
        $this->assertEquals(987654321, $actualDate->getTimestamp(), 'The timestamp of the date_confirmed wasnt saved or returned correctly.');
    }

    /**
     * Tests if the date_confirmed can be set and returned again if using a
     * date object.
     */
    public function testSetGetDateConfirmedFromDate()
    {
        $user = new UserModel();
        $date = new \Ilch\Date();
        $expectedTimestamp = $date->getTimestamp();
        $user->setDateConfirmed($date);
        $actualDate = $user->getDateConfirmed();

        $this->assertEquals($expectedTimestamp, $actualDate->getTimestamp(), 'The date_confirmed wasnt saved or returned correctly using a Ilch_Date object.');
    }

    /**
     * Tests if the date_confirmed can be set and returned again if using a string.
     */
    public function testSetDateConfirmedFromString()
    {
        $user = new UserModel();
        $user->setDateConfirmed('2013-09-02 22:15:45');
        $actualDate = $user->getDateConfirmed();

        $this->assertInstanceOf('\\Ilch\\Date', $actualDate, 'The date_confirmed was not created using Ilch_Date.');
        $this->assertEquals('2013-09-02 22:15:45', $actualDate->format('Y-m-d H:i:s'), 'The date_confirmed does not got saved correctly using a String.');
    }

    /**
     * Tests if the user groups can be set and returned again.
     */
    public function testSetGetGroups()
    {
        $user = new UserModel();
        $user->setGroups(array(1, 2, 3));
        $this->assertEquals(array(1, 2, 3), $user->getGroups(), 'The user groups wasnt saved or returned correctly.');
    }

    /**
     * Tests if the user groups can be set and returned again with an single int.
     */
    public function testSetGetGroupsInteger()
    {
        $user = new UserModel();
        $user->setGroups(4);
        $this->assertEquals(array(4), $user->getGroups(), 'The user groups wasnt saved or returned correctly.');
    }
}
