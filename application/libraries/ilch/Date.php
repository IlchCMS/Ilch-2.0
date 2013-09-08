<?php
/**
 * Holds class Ilch_Date.
 *
 * @author Martin Jainta
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * Tests the Ilch_Date class.
 *
 * @author Martin Jainta
 * @copyright Ilch CMS 2.0
 * @package ilch
 */
class Ilch_Date extends DateTime
{
	/**
	 * The DateTimeZone used by the database.
	 *
	 * @var DateTimeZone
	 */
	private $_timeZoneDb;

	/**
	 * The local DateTimeZone.
	 *
	 * @var DateTimeZone
	 */
	private $_timeZoneLocal;

	/**
	 * The default format for outputs.
	 *
	 * @var string
	 */
	private $_defaultFormat = 'Y-m-d H:i:s';

	/**
	 * The format used for outputs for the database.
	 *
	 * @var string
	 */
	private $_dbFormat = 'Y-m-d H:i:s';

	/**
	 * Generates a DateTime object using the given parameters.
	 *
	 * @param string $time     A string which represents the current time.
	 * @param string $timezone The locale to set the timezone.
	 */
	public function __construct($time = 'now', DateTimeZone $timezone = null)
	{
		if($timezone === null)
		{
			if(Ilch_Registry::has('config'))
			{
				$timezone = new DateTimeZone(Ilch_Registry::get('config')->get('timezone'));
			}
			else
			{
				$timezone = new DateTimeZone(date_default_timezone_get());
			}
		}

		parent::__construct($time, $timezone);
		$this->_timeZoneDb = new DateTimeZone('UTC');
		$this->_timeZoneLocal = $timezone;
	}

	/**
	 * Returns the datetime string for the db.
	 *
	 * @return string A string formatted like 'Y-m-d H:i:s'.
	 */
	public function toDb($local = false)
	{
		if($local)
		{
			$timezone = $this->_timeZoneLocal;
		}
		else
		{
			$timezone = $this->_timeZoneDb;
		}

		if(is_string($timezone))
		{
			$timezone = new DateTimeZone($timezone);
		}

		$this->setTimezone($timezone);
		$formattedTime = $this->format($this->_dbFormat);
		$this->setTimezone($this->_timeZoneLocal);

		return $formattedTime;
	}

	/**
	 * Returns a formatted representation of the date.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->format($this->_defaultFormat);
	}

	/**
	 * Returns date formatted according to given format.
	 *
	 * If no format is given, the default format will be used.
	 *
	 * @param  string $format See http://www.php.net/manual/en/function.date.php
	 * @return string
	 */
	public function format($format = null)
	{
		if($format === null)
		{
			$format = $this->_defaultFormat;
		}

		return parent::format($format);
	}

	/**
	 * Sets the db format.
	 *
	 * @param string $format
	 */
	public function setDbFormat($format)
	{
		$this->_dbFormat = $format;
	}

	/**
	 * Sets the default format.
	 *
	 * @param string $format
	 */
	public function setDefaultFormat($format)
	{
		$this->_defaultFormat = $format;
	}
}