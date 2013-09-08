<?php
/**
 * Holds class Libraries_Ilch_ConfigTest.
 *
 * @author Martin Jainta
 * @copyright Ilch CMS 2.0
 * @package ilch_phpunit
 */

defined('ACCESS') or die('no direct access');

/**
 * Tests the config object.
 *
 * @author Martin Jainta
 * @copyright Ilch CMS 2.0
 * @package ilch_phpunit
 */
class Libraries_Ilch_DateTest extends PHPUnit_Ilch_TestCase
{
	/**
	 * Filling the timezone which the date object will use.
	 *
	 * @var Array
	 */
	protected $_configData = array
	(
		'timezone' => 'Europe/Berlin'
	);

	/**
	 * Tests if Ilch_Date extends from DateTime.
	 */
	public function testExtendFromDateTime()
	{
		$ilchDate = new Ilch_Date('2013-09-24 13:44:53');

		$this->assertInstanceOf('DateTime', $ilchDate, 'No DateTime object was created by the constructor.');
	}

	/**
	 * Tests if the class creates the right db datetime string in UTC using the given time.
	 */
	public function testToDb()
	{
		$ilchDate = new Ilch_Date('2013-09-24 15:44:53');

		$this->assertEquals('2013-09-24 15:44:53', $ilchDate->toDb(), 'The date was not returned in UTC.');
	}

	/**
	 * Tests if a db timestamp with the local time can be returned.
	 */
	public function testToDbLocal()
	{
		$ilchDate = new Ilch_Date('2013-09-24 15:44:53');

		$this->assertEquals('2013-09-24 17:44:53', $ilchDate->toDb(true), 'The date was not in the local timezone returned.');
	}

	/**
	 * Tests if the format for the db format can be changed.
	 */
	public function testToDbCustomFormat()
	{
		$ilchDate = new Ilch_Date('2013-09-24 15:44:53');
		$ilchDate->setDbFormat('d.m.Y H:i:s');

		$this->assertEquals('24.09.2013 15:44:53', $ilchDate->toDb(), 'The date was not returned with the correct format.');
	}

	/**
	 * Tests if the class returns the DateTime object with the correct timezone
	 * if the db datetime was returned before. The usage of the database timezone
	 * string should not manipulate the return of the local date.
	 */
	public function testGetDateTimeAfterToDb()
	{
		$ilchDate = new Ilch_Date('2013-09-24 22:32:46');
		$ilchDate->toDb();

		$this->assertEquals('2013-09-24 22:32:46', $ilchDate->format('Y-m-d H:i:s'), 'A time with the wrong timezone was returned.');
	}

	/**
	 * Tests if the date object may be typecasted to string.
	 */
	public function testTypeCast()
	{
		$ilchDate = new Ilch_Date('2013-09-24 22:32:46');

		$this->assertEquals('2013-09-24 22:32:46', (string)$ilchDate, 'The object could not be typecasted correctly to string.');
	}

	/**
	 * Tests if the type cast to string works using a custom format.
	 */
	public function testTypeCastCustomFormat()
	{
		$ilchDate = new Ilch_Date('2013-09-24 22:32:46');
		$ilchDate->setDefaultFormat('d.m.Y H:i:s');

		$this->assertEquals('24.09.2013 22:32:46', (string)$ilchDate, 'The object could not be typecasted correctly to string using a custom format.');
	}
}