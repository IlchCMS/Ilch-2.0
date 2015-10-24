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
     * The ipAdresse of the Statistic.
     *
     * @var string
     */
    protected $ipAdresse;

    /**
     * The os of the Statistic.
     *
     * @var string
     */
    protected $os;

    /**
     * The browser of the Statistic.
     *
     * @var string
     */
    protected $browser;

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
     * @return Site
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
     * @return Referer
     */
    public function setReferer($referer)
    {
        $this->referer = (string)$referer;

        return $this;
    }

    /**
     * Returns the ipAdress.
     *
     * @return string
     */
    public function getIPAdress()
    {
        return $this->ipAdress;
    }

    /**
     * Saves the ipAdress.
     *
     * @param string $ipAdress
     * @return IPAdress
     */
    public function setIPAdress($ipAdress)
    {
        $this->ipAdress = (string)$ipAdress;

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
     * @return OS
     */
    public function setOS($os)
    {
        $this->os = (string)$os;

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
     * @return Browser
     */
    public function setBrowser($browser)
    {
        $this->browser = (string)$browser;

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
     * @return Lang
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
     * @return User
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
     * @return User
     */
    public function setDate($date)
    {
        $this->date = (string)$date;

        return $this;
    }
}
