<?php
/**
 * Holds class Modules_User_Models_UserTest.
 *
 * @author Jainta Martin
 * @copyright Ilch CMS 2.0
 * @package ilch_phpunit
 */

defined('ACCESS') or die('no direct access');

/**
 * Tests the user model class.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class Modules_User_Models_UserTest extends IlchTestCase
{
    /**
     * Tests if the user id can be set and returned again.
     */
    public function testSetGetId()
    {
        $user = new User_UserModel();
        $user->setId(123);
        $this->assertEquals(123, $user->getId(), 'The id wasnt saved or returned correctly.');
    }

    /**
     * Tests if the username can be set and returned again.
     */
    public function testSetGetUsername()
    {
        $user = new User_UserModel();
        $user->setUsername('username');
        $this->assertEquals('username', $user->getUsername(), 'The username wasnt saved or returned correctly.');
    }

    /**
     * Tests if the email address can be set and returned again.
     */
    public function testSetGetEmail()
    {
        $user = new User_UserModel();
        $user->setEmail('email');
        $this->assertEquals('email', $user->getEmail(), 'The email wasnt saved or returned correctly.');
    }

    /**
     * Tests if the date_created can be set and returned again.
     */
    public function testSetGetDateCreated()
    {
        $user = new User_UserModel();
        $user->setDateCreated(123456789);
        $this->assertEquals(123456789, $user->getDateCreated(), 'The date_created wasnt saved or returned correctly.');
    }

    /**
     * Tests if the date_created can be set and returned again if using a
     * date object.
     */
    public function testSetGetDateCreatedFromDate()
    {
        $user = new User_UserModel();
        $date = new DateTime();
        $user->setDateCreated($date);
        $this->assertEquals($date->getTimestamp(), $user->getDateCreated(), 'The date_created wasnt saved or returned correctly using a date object.');
    }

    /**
     * Tests if the date_confirmed can be set and returned again.
     */
    public function testSetGetDateConfirmed()
    {
        $user = new User_UserModel();
        $user->setDateConfirmed(987654321);
        $this->assertEquals(987654321, $user->getDateConfirmed(), 'The date_confirmed wasnt saved or returned correctly.');
    }

    /**
     * Tests if the date_confirmed can be set and returned again if using a
     * date object.
     */
    public function testSetGetDateConfirmedFromDate()
    {
        $user = new User_UserModel();
        $date = new DateTime();
        $user->setDateConfirmed($date);
        $this->assertEquals($date->getTimestamp(), $user->getDateConfirmed(), 'The date_confirmed wasnt saved or returned correctly using a date object.');
    }
}