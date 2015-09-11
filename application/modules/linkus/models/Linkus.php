<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Linkus\Models;

defined('ACCESS') or die('no direct access');

class Linkus extends \Ilch\Model
{
    /**
     * The id of the linkus.
     *
     * @var int
     */
    protected $id;

    /**
     * The title of the linkus.
     *
     * @var string
     */
    protected $title;

    /**
     * The banner of the linkus.
     *
     * @var string
     */
    protected $banner;

    /**
     * Gets the id of the linkus.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the linkus.
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
     * Gets the title of the linkus.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the linkus.
     *
     * @param string $title
     * @return this
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;

        return $this;
    }

    /**
     * Gets the banner of the linkus.
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Sets the banner of the linkus.
     *
     * @param string $banner
     * @return this
     */
    public function setBanner($banner)
    {
        $this->banner = (string)$banner;

        return $this;
    }
}
