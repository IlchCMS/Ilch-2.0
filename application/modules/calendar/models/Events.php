<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Models;

class Events extends \Ilch\Model
{
    /**
     * The id of the events.
     *
     * @var int
     */
    protected $id;

    /**
     * The Url of the events.
     *
     * @var string
     */
    protected $url;


    /**
     * Gets the Id of the events.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the Id of the events.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the Url of the events.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the Url of the events.
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;

        return $this;
    }
}
