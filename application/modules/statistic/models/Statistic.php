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
    protected $id = 0;

    /**
     * The userId of the Statistic.
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * The sessionId of the Statistic.
     *
     * @var string
     */
    protected $sessionId = '';

    /**
     * The visits of the Statistic.
     *
     * @var int
     */
    protected $visits = 0;

    /**
     * The site of the Statistic.
     *
     * @var string
     */
    protected $site = '';

    /**
     * The referer.
     *
     * @var string
     */
    protected $referer = '';

    /**
     * The ip address of the Statistic.
     *
     * @var string
     */
    protected $ipAddress = '';

    /**
     * The os of the Statistic.
     *
     * @var string
     */
    protected $os = '';

    /**
     * The os version of the Statistic.
     *
     * @var string
     */
    protected $osVersion = '';

    /**
     * The browser of the Statistic.
     *
     * @var string
     */
    protected $browser = '';

    /**
     * The browser version of the Statistic.
     *
     * @var string
     */
    protected $browserVersion = '';

    /**
     * The lang of the Statistic.
     *
     * @var string
     */
    protected $lang = '';

    /**
     * The Date last Activity of the Statistic.
     *
     * @var string
     */
    protected $dateLastActivity = '';

    /**
     * The date.
     *
     * @var string
     */
    protected $date = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Statistic
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (isset($entries['session_id'])) {
            $this->setSessionId($entries['session_id']);
        }
        if (isset($entries['site'])) {
            $this->setSite($entries['site']);
        }
        if (isset($entries['os'])) {
            $this->setOS($entries['os']);
        }
        if (isset($entries['os_version'])) {
            $this->setOSVersion($entries['os_version']);
        }
        if (isset($entries['browser'])) {
            $this->setBrowser($entries['browser']);
        }
        if (isset($entries['browser_version'])) {
            $this->setBrowserVersion($entries['browser_version']);
        }
        if (isset($entries['ip_address'])) {
            $this->setIPAdress($entries['ip_address']);
        }
        if (isset($entries['lang'])) {
            $this->setLang($entries['lang']);
        }
        if (isset($entries['date_last_activity'])) {
            $this->setDateLastActivity($entries['date_last_activity']);
        }
        if (isset($entries['referer'])) {
            $this->setReferer($entries['referer']);
        }
        if (isset($entries['date'])) {
            $this->setDate($entries['date']);
        }
        return $this;
    }

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
     * @return $this
     */
    public function setId(int $id): Statistic
    {
        $this->id = $id;

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
     * @return $this
     */
    public function setUserId(int $userId): Statistic
    {
        $this->userId = $userId;

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
     * @return $this
     */
    public function setSessionId(string $sessionId): Statistic
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
     * @return $this
     */
    public function setVisits(int $visits): Statistic
    {
        $this->visits = $visits;

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
     * @return $this
     */
    public function setSite(string $site): Statistic
    {
        $this->site = $site;

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
     * @return $this
     */
    public function setReferer(string $referer): Statistic
    {
        $this->referer = $referer;

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
     * @return $this
     */
    public function setIPAdress(string $ipAddress): Statistic
    {
        $this->ipAddress = $ipAddress;

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
     * @return $this
     */
    public function setOS(string $os): Statistic
    {
        $this->os = $os;

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
     * @return $this
     */
    public function setOSVersion(string $osVersion): Statistic
    {
        $this->osVersion = $osVersion;

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
     * @return $this
     */
    public function setBrowser(string $browser): Statistic
    {
        $this->browser = $browser;

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
     * @return $this
     */
    public function setBrowserVersion(string $browserVersion): Statistic
    {
        $this->browserVersion = $browserVersion;

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
     * @return $this
     */
    public function setLang(string $lang): Statistic
    {
        $this->lang = $lang;

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
     * @return $this
     */
    public function setDateLastActivity(string $dateLastActivity): Statistic
    {
        $this->dateLastActivity = $dateLastActivity;

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
     * @return $this
     */
    public function setDate(string $date): Statistic
    {
        $this->date = $date;

        return $this;
    }
}
