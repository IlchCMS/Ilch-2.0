<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Models;

defined('ACCESS') or die('no direct access');

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
    protected $page;

    /**
     * The squad of the awards.
     *
     * @var int
     */
    protected $squad;

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
     * Gets the page of the awards.
     *
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Sets the page of the awards.
     *
     * @param string $page
     * @return this
     */
    public function setPage($page)
    {
        $this->page = (string)$page;

        return $this;
    }

    /**
     * Gets the squad of the awards.
     *
     * @return int
     */
    public function getSquad()
    {
        return $this->squad;
    }

    /**
     * Sets the squad of the awards.
     *
     * @param int $squad
     * @return this
     */
    public function setSquad($squad)
    {
        $this->squad = (int)$squad;

        return $this;
    }
}
