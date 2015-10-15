<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

defined('ACCESS') or die('no direct access');

/**
 * Tests the Ilch_Date class.
 */
class Date extends \DateTime
{
    /**
     * The DateTimeZone used by the database.
     *
     * @var DateTimeZone
     */
    private $timeZone;

    /**
     * The local DateTimeZone.
     *
     * @var DateTimeZone
     */
    private $timeZoneLocal;

    /**
     * The default format for outputs.
     *
     * @var string
     */
    private $defaultFormat = 'Y-m-d H:i:s';

    /**
     * The format used for outputs for the database.
     *
     * @var string
     */
    private $dbFormat = 'Y-m-d H:i:s';

    /**
     * Generates a DateTime object using the given parameters.
     *
     * @param string              $time     A string which represents the current time.
     * @param string|DateTimeZone $timezone The locale to set the timezone.
     */
    public function __construct($time = 'now', $timezone = 'UTC')
    {
        if (Registry::has('config')) {
            $timezoneString = Registry::get('config')->get('timezone');
            if (!empty($timezoneString)) {
                $this->timeZoneLocal = new \DateTimeZone($timezoneString);
            }
        }
        if (!isset($this->timeZoneLocal)) {
            $this->timeZoneLocal = new \DateTimeZone(SERVER_TIMEZONE);
        }

        if (is_string($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }

        $this->timeZone = $timezone;
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
        if ($local) {
            $timezone = $this->timeZoneLocal;
        } else {
            $timezone = $this->timeZone;
        }

        return $this->ownFormat($this->dbFormat, $local);
    }

    /**
     * Returns a formatted representation of the date.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format($this->defaultFormat);
    }

    /**
     * Returns date formatted according to given format.
     *
     * If no format is given, the default format will be used.
     *
     * @param  string  $format See http://www.php.net/manual/en/function.date.php
     * @param  boolean $local
     * @return string
     */
    public function format($format = null, $local = false)
    {
        if ($format === null) {
            $format = $this->defaultFormat;
        }

        return $this->ownFormat($format, $local);
    }

    /**
     * Formats and returns the date considering local.
     *
     * @param  string  $format
     * @param  boolean $local
     * @return string
     */
    protected function ownFormat($format, $local)
    {
        if ($local) {
            $this->setTimezone($this->timeZoneLocal);
            $formattedTime = parent::format($format);
            $this->setTimezone($this->timeZone);
        } else {
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
        $this->dbFormat = $format;
    }

    /**
     * Sets the default format.
     *
     * @param string $format
     */
    public function setDefaultFormat($format)
    {
        $this->defaultFormat = $format;
    }
}
