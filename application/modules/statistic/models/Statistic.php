<?php
/**
 * @copyright Ilch 2.0
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Saves the id of the Statistic.
     *
     * @param int $id
     * @return Statistic
     */
    public function setId($id): Statistic
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Returns the userId of the Statistic.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Saves the userId of the Statistic.
     *
     * @param int $userId
     * @return Statistic
     */
    public function setUserId($userId): Statistic
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
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * Set the php session id of the guest or user.
     *
     * @param string $sessionId
     * @return Statistic
     */
    public function setSessionId($sessionId): Statistic
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Returns the visits of the Statistic.
     *
     * @return int
     */
    public function getVisits(): int
    {
        return $this->visits;
    }

    /**
     * Saves the visits of the Statistic.
     *
     * @param int $visits
     * @return Statistic
     */
    public function setVisits($visits): Statistic
    {
        $this->visits = (int)$visits;

        return $this;
    }

    /**
     * Returns the site.
     *
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * Saves the site.
     *
     * @param string $site
     * @return Statistic
     */
    public function setSite($site): Statistic
    {
        $this->site = (string)$site;

        return $this;
    }

    /**
     * Returns the referer.
     *
     * @return string
     */
    public function getReferer(): string
    {
        return $this->referer;
    }

    /**
     * Saves the referer.
     *
     * @param string $referer
     * @return Statistic
     */
    public function setReferer($referer): Statistic
    {
        $this->referer = (string)$referer;

        return $this;
    }

    /**
     * Returns the ip address.
     *
     * @return string
     */
    public function getIPAdress(): string
    {
        return $this->ipAddress;
    }

    /**
     * Saves the ipAdress.
     *
     * @param string $ipAddress
     * @return Statistic
     */
    public function setIPAdress($ipAddress): Statistic
    {
        $this->ipAddress = (string)$ipAddress;

        return $this;
    }

    /**
     * Returns the os.
     *
     * @return string
     */
    public function getOS(): string
    {
        return $this->os;
    }

    /**
     * Saves the os.
     *
     * @param string $os
     * @return Statistic
     */
    public function setOS($os): Statistic
    {
        $this->os = (string)$os;

        return $this;
    }

    /**
     * Returns the os.
     *
     * @return string
     */
    public function getOSVersion(): string
    {
        return $this->osVersion;
    }

    /**
     * Saves the os.
     *
     * @param string $osVersion
     * @return Statistic
     */
    public function setOSVersion($osVersion): Statistic
    {
        $this->osVersion = (string)$osVersion;

        return $this;
    }

    /**
     * Returns the browser.
     *
     * @return string
     */
    public function getBrowser(): string
    {
        return $this->browser;
    }

    /**
     * Saves the browser.
     *
     * @param string $browser
     * @return Statistic
     */
    public function setBrowser($browser): Statistic
    {
        $this->browser = (string)$browser;

        return $this;
    }

    /**
     * Returns the browser version.
     *
     * @return string
     */
    public function getBrowserVersion(): string
    {
        return $this->browserVersion;
    }

    /**
     * Saves the browser version.
     *
     * @param string $browserVersion
     * @return Statistic
     */
    public function setBrowserVersion($browserVersion): Statistic
    {
        $this->browserVersion = (string)$browserVersion;

        return $this;
    }

    /**
     * Returns the lang.
     *
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * Saves the lang.
     *
     * @param string $lang
     * @return Statistic
     */
    public function setLang($lang): Statistic
    {
        $this->lang = (string)$lang;

        return $this;
    }

    /**
     * Returns the Date last Activity.
     *
     * @return string
     */
    public function getDateLastActivity(): string
    {
        return $this->dateLastActivity;
    }

    /**
     * Saves the Date last Activity.
     *
     * @param string $dateLastActivity
     * @return Statistic
     */
    public function setDateLastActivity($dateLastActivity): Statistic
    {
        $this->dateLastActivity = (string)$dateLastActivity;

        return $this;
    }

    /**
     * Returns the Date.
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Saves the Date.
     *
     * @param string $date
     * @return Statistic
     */
    public function setDate($date): Statistic
    {
        $this->date = (string)$date;

        return $this;
    }
}
