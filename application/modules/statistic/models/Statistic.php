<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Models;

class Statistic extends \Ilch\Model
{
    /**
     * The id of the Statistic.
     *
     * @var int
     */
    protected $id;

    /**
     * The userId of the Statistic.
     *
     * @var int
     */
    protected $userId;

    /**
     * The sessionId of the Statistic.
     *
     * @var string
     */
    protected $sessionId;

    /**
     * The visits of the Statistic.
     *
     * @var int
     */
    protected $visits;

    /**
     * The site of the Statistic.
     *
     * @var string
     */
    protected $site;

    /**
     * The referer.
     *
     * @var string
     */
    protected $referer;

    /**
     * The ip address of the Statistic.
     *
     * @var string
     */
    protected $ipAddress;

    /**
     * The os of the Statistic.
     *
     * @var string
     */
    protected $os;

    /**
     * The os version of the Statistic.
     *
     * @var string
     */
    protected $osVersion;

    /**
     * The browser of the Statistic.
     *
     * @var string
     */
    protected $browser;

    /**
     * The browser version of the Statistic.
     *
     * @var string
     */
    protected $browserVersion;

    /**
     * The lang of the Statistic.
     *
     * @var string
     */
    protected $lang;

    /**
     * The Date last Activity of the Statistic.
     *
     * @var string
     */
    protected $dateLastActivity;

    /**
     * The date.
     *
     * @var string
     */
    protected $date;

    /**
     * Returns the id of the Statistic.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Saves the id of the Statistic.
     *
     * @param int $id
     * @return Statistic
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Returns the userId of the Statistic.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Saves the userId of the Statistic.
     *
     * @param int $userId
     * @return Statistic
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * The php session id of the guest or user.
     * Usefull to better identify a guest/user as there might be
     * more than one guest/user with the same ip-adress.
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set the php session id of the guest or user.
     *
     * @param string $sessionId
     * @return Statistic
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Returns the visits of the Statistic.
     *
     * @return int
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Saves the visits of the Statistic.
     *
     * @param int $visits
     * @return Statistic
     */
    public function setVisits($visits)
    {
        $this->visits = (int)$visits;

        return $this;
    }

    /**
     * Returns the site.
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Saves the site.
     *
     * @param string $site
     * @return Statistic
     */
    public function setSite($site)
    {
        $this->site = (string)$site;

        return $this;
    }

    /**
     * Returns the referer.
     *
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Saves the referer.
     *
     * @param string $referer
     * @return Statistic
     */
    public function setReferer($referer)
    {
        $this->referer = (string)$referer;

        return $this;
    }

    /**
     * Returns the ip address.
     *
     * @return string
     */
    public function getIPAdress()
    {
        return $this->ipAddress;
    }

    /**
     * Saves the ipAdress.
     *
     * @param string $ipAddress
     * @return Statistic
     */
    public function setIPAdress($ipAddress)
    {
        $this->ipAddress = (string)$ipAddress;

        return $this;
    }

    /**
     * Returns the os.
     *
     * @return string
     */
    public function getOS()
    {
        return $this->os;
    }

    /**
     * Saves the os.
     *
     * @param string $os
     * @return Statistic
     */
    public function setOS($os)
    {
        $this->os = (string)$os;

        return $this;
    }

    /**
     * Returns the os.
     *
     * @return string
     */
    public function getOSVersion()
    {
        return $this->osVersion;
    }

    /**
     * Saves the os.
     *
     * @param string $osVersion
     * @return Statistic
     */
    public function setOSVersion($osVersion)
    {
        $this->osVersion = (string)$osVersion;

        return $this;
    }

    /**
     * Returns the browser.
     *
     * @return string
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Saves the browser.
     *
     * @param string $browser
     * @return Statistic
     */
    public function setBrowser($browser)
    {
        $this->browser = (string)$browser;

        return $this;
    }

    /**
     * Returns the browser version.
     *
     * @return string
     */
    public function getBrowserVersion()
    {
        return $this->browserVersion;
    }

    /**
     * Saves the browser version.
     *
     * @param string $browserVersion
     * @return Statistic
     */
    public function setBrowserVersion($browserVersion)
    {
        $this->browserVersion = (string)$browserVersion;

        return $this;
    }

    /**
     * Returns the lang.
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Saves the lang.
     *
     * @param string $lang
     * @return Statistic
     */
    public function setLang($lang)
    {
        $this->lang = (string)$lang;

        return $this;
    }

    /**
     * Returns the Date last Activity.
     *
     * @return string
     */
    public function getDateLastActivity()
    {
        return $this->dateLastActivity;
    }

    /**
     * Saves the Date last Activity.
     *
     * @param string $dateLastActivity
     * @return Statistic
     */
    public function setDateLastActivity($dateLastActivity)
    {
        $this->dateLastActivity = (string)$dateLastActivity;

        return $this;
    }

    /**
     * Returns the Date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Saves the Date.
     *
     * @param string $date
     * @return Statistic
     */
    public function setDate($date)
    {
        $this->date = (string)$date;

        return $this;
    }
}
