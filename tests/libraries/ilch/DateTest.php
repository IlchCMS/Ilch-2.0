<?php
/**
 * Holds class \Ilch\ConfigTest.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the config object.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */
class DateTest extends TestCase
{
    /**
     * Filling the timezone which the date object will use.
     *
     * @var Array
     */
    protected $configData = array(
        'timezone' => 'Europe/Berlin'
    );

    /**
     * Tests if the timezone with an empty Registry-Key 'timezone'.
     */
    public function testNewEmptyDateWithoutRegistry()
    {
        Registry::remove('timezone');
        $date = new \Ilch\Date();
        $this->assertEquals(
            'UTC',
            $date->getTimeZone()->getName(),
            'Timezone is not UTC as expected when creating Ilch_Date without a paramter.'
        );
    }

    /**
     * Tests if Ilch_Date extends from DateTime.
     */
    public function testExtendFromDateTime()
    {
        $ilchDate = new \Ilch\Date('2013-09-24 13:44:53');

        $this->assertInstanceOf('DateTime', $ilchDate, 'No DateTime object was created by the constructor.');
    }

    /**
     * Tests if the class creates the right db datetime string in UTC using the given time.
     */
    public function testToDb()
    {
        $ilchDate = new \Ilch\Date('2013-09-24 15:44:53');

        $this->assertEquals('2013-09-24 15:44:53', $ilchDate->toDb(), 'The date was not returned in UTC.');
    }

    /**
     * Tests if a db timestamp with the local time can be returned.
     */
    public function testToDbLocal()
    {
        $ilchDate = new \Ilch\Date('2013-09-24 15:44:53');

        $this->assertEquals(
            '2013-09-24 17:44:53',
            $ilchDate->toDb(true),
            'The date was not in the local timezone returned.'
        );
    }

    /**
     * Tests if the format for the db format can be changed.
     */
    public function testToDbCustomFormat()
    {
        $ilchDate = new \Ilch\Date('2013-09-24 15:44:53');
        $ilchDate->setDbFormat('d.m.Y H:i:s');

        $this->assertEquals(
            '24.09.2013 15:44:53',
            $ilchDate->toDb(),
            'The date was not returned with the correct format.'
        );
    }

    /**
     * Tests if the class returns the DateTime object with the correct timezone
     * if the db datetime was returned before. The usage of the database timezone
     * string should not manipulate the return of the local date.
     */
    public function testGetDateTimeAfterToDb()
    {
        $ilchDate = new \Ilch\Date('2013-09-24 22:32:46');
        $ilchDate->toDb();

        $this->assertEquals(
            '2013-09-24 22:32:46',
            $ilchDate->format('Y-m-d H:i:s'),
            'A time with the wrong timezone was returned.'
        );
    }

    /**
     * Tests if the date object may be typecasted to string.
     */
    public function testTypeCast()
    {
        $ilchDate = new \Ilch\Date('2013-09-24 22:32:46');

        $this->assertEquals(
            '2013-09-24 22:32:46',
            (string)$ilchDate,
            'The object could not be typecasted correctly to string.'
        );
    }

    /**
     * Tests if the type cast to string works using a custom format.
     */
    public function testTypeCastCustomFormat()
    {
        $ilchDate = new \Ilch\Date('2013-09-24 22:32:46');
        $ilchDate->setDefaultFormat('d.m.Y H:i:s');

        $this->assertEquals(
            '24.09.2013 22:32:46',
            (string)$ilchDate,
            'The object could not be typecasted correctly to string using a custom format.'
        );
    }

    /**
     * Tests if the timestamp always stays the same no matter if the local timezone gets requested.
     */
    public function testTimestampDoesNotChange()
    {
        $date = new \Ilch\Date();
        $date->setTimestamp(1379521501);

        $this->assertEquals(1379521501, $date->getTimestamp(), 'The timestamp was not returned in UTC.');
        $this->assertEquals(1379521501, $date->format('U', true), 'The timestamp was not returned in UTC.');
        $this->assertEquals(1379521501, $date->format('U', false), 'The timestamp was not returned in UTC.');
    }

    /**
     * Tests if the format function returns the correct local time.
     */
    public function testFormatToLocal()
    {
        $date = new \Ilch\Date();
        $date->setTimestamp(1379521501);

        $this->assertEquals(
            '2013-09-18 18:25:01',
            $date->format('Y-m-d H:i:s', true),
            'The time was not returned in local time.'
        );
    }

    /**
     * Tests if the format function returns the correct UTC time.
     */
    public function testFormatToUTC()
    {
        $date = new \Ilch\Date();
        $date->setTimestamp(1379521501);

        $this->assertEquals('2013-09-18 16:25:01', $date->format('Y-m-d H:i:s'), 'The time was not returned in UTC.');
    }

    /**
     * Tests if Ilch_Date saves a date in the local time if the local timezone is given in the constructor.
     */
    public function testConstructLocalTime()
    {
        $date = new \Ilch\Date('2013-09-18 18:25:01', 'Europe/Berlin');

        $this->assertEquals(
            '2013-09-18 18:25:01',
            $date->format('Y-m-d H:i:s', true),
            'The time was not returned in local time.'
        );
    }
}
