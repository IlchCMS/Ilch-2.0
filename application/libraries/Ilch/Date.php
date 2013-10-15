<?php
/**
 * Holds class Ilch_Date.
 *
 * @author Martin Jainta
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

/**
 * Tests the Ilch_Date class.
 *
 * @author Martin Jainta
 * @copyright Ilch 2.0
 * @package ilch
 */
class Date extends \DateTime
{
	/**
	 * The DateTimeZone used by the database.
	 *
	 * @var DateTimeZone
	 */
	private $_timeZone;

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
	 * @param string $time        A string which represents the current time.
	 * @param string|DateTimeZone $timezone The locale to set the timezone.
	 */
	public function __construct($time = 'now', $timezone = 'UTC')
	{
		if(Registry::has('config'))
		{
			$this->_timeZoneLocal = new \DateTimeZone(Registry::get('config')->get('timezone'));
		}
		else
		{
			$this->_timeZoneLocal = new \DateTimeZone(SERVER_TIMEZONE);
		}

		if(is_string($timezone))
		{
			$timezone = new \DateTimeZone($timezone);
		}

		$this->_timeZone = $timezone;
		parent::__construct($time, $timezone);
	}

	/**
	 * Returns the datetime string for the db.
	 *
	 * @param  boolean $local
	 * @return string  A formatted date string.
	 */
	public function toDb($local = false)
	{
		if($local)
		{
			$timezone = $this->_timeZoneLocal;
		}
		else
		{
			$timezone = $this->_timeZone;
		}

		return $this->_format($this->_dbFormat, $local);
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
	 * @param  boolean $local
	 * @return string
	 */
	public function format($format = null, $local = false)
	{
		if($format === null)
		{
			$format = $this->_defaultFormat;
		}

		return $this->_format($format, $local);
	}

	/**
	 * Formats and returns the date considering local.
	 *
	 * @param  string  $format
	 * @param  boolean $local
	 * @return string
	 */
	protected function _format($format, $local)
	{
		if($local)
		{
			$this->setTimezone($this->_timeZoneLocal);
			$formattedTime = parent::format($format);
			$this->setTimezone($this->_timeZone);
		}
		else
		{
			$formattedTime = parent::format($format);
		}

		return $formattedTime;
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