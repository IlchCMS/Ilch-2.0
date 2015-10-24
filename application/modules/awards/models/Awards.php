<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Models;

class Awards extends \Ilch\Model
{
    /**
     * The id of the awards.
     *
     * @var int
     */
    protected $id;

    /**
     * The date of the awards.
     *
     * @var string
     */
    protected $date;

    /**
     * The rank of the awards.
     *
     * @var int
     */
    protected $rank;

    /**
     * The event of the awards.
     *
     * @var string
     */
    protected $event;

    /**
     * The page of the awards.
     *
     * @var string
     */
    protected $url;

    /**
     * The utId of the awards.
     *
     * @var int
     */
    protected $utId;

    /**
     * The typ of the awards.
     *
     * @var int
     */
    protected $typ;

    /**
     * Gets the id of the awards.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the awards.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the date of the awards.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date of the awards.
     *
     * @param string $date
     * @return this
     */
    public function setDate($date)
    {
        $this->date = (string)$date;

        return $this;
    }

    /**
     * Gets the rank of the awards.
     *
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Sets the rank of the awards.
     *
     * @param int $rank
     * @return this
     */
    public function setRank($rank)
    {
        $this->rank = (int)$rank;

        return $this;
    }

    /**
     * Gets the event of the awards.
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets the event of the awards.
     *
     * @param string $event
     * @return this
     */
    public function setEvent($event)
    {
        $this->event = (string)$event;

        return $this;
    }

    /**
     * Gets the url of the awards.
     *
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * Sets the url of the awards.
     *
     * @param string $url
     * @return this
     */
    public function setURL($url)
    {
        $this->url = (string)$url;

        return $this;
    }

    /**
     * Gets the utId of the awards.
     *
     * @return int
     */
    public function getUTid()
    {
        return $this->utId;
    }

    /**
     * Sets the utId of the awards.
     *
     * @param int $utId
     * @return this
     */
    public function setUTId($utId)
    {
        $this->utId = (int)$utId;

        return $this;
    }

    /**
     * Gets the typ of the awards.
     *
     * @return int
     */
    public function getTyp()
    {
        return $this->typ;
    }

    /**
     * Sets the typ of the awards.
     *
     * @param int $typ
     * @return this
     */
    public function setTyp($typ)
    {
        $this->typ = (int)$typ;

        return $this;
    }
}
