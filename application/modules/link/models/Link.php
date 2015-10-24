<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Link\Models;

class Link extends \Ilch\Model
{
    /**
     * The id of the link.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the link.
     *
     * @var string
     */
    protected $name;

    /**
     * The link of the link.
     *
     * @var string
     */
    protected $link;

    /**
     * The banner of the link.
     *
     * @var string
     */
    protected $banner;

    /**
     * The category of the link.
     *
     * @var string
     */
    protected $cat_id;

    /**
     * The category of the link.
     *
     * @var string
     */
    protected $desc;

    /**
     * The category of the link.
     *
     * @var string
     */
    protected $hits;

    /**
     * Gets the id of the link.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the link.
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
     * Gets the name of the link.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the link.
     *
     * @param string $name
     * @return this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the link of the link.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the link of the link.
     *
     * @param string $link
     * @return this
     */
    public function setLink($link)
    {
        $this->link = (string)$link;

        return $this;
    }

    /**
     * Gets the banner of the link.
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Sets the banner of the link.
     *
     * @param string $banner
     * @return this
     */
    public function setBanner($banner)
    {
        $this->banner = (string)$banner;

        return $this;
    }

    /**
     * Gets the category of the link.
     *
     * @return string
     */
    public function getCatId()
    {
        return $this->cat_id;
    }

    /**
     * Sets the category of the link.
     *
     * @param int $id
     * @return this
     */
    public function setCatId($cat)
    {
        $this->cat_id = (int)$cat;

        return $this;
    }

    /**
     * Gets the description of the link.
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Sets the description of the link.
     *
     * @param string $desc
     * @return this
     */
    public function setDesc($desc)
    {
        $this->desc = (string)$desc;

        return $this;
    }

    /**
     * Gets the hits of the link.
     *
     * @return string
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Sets the hits of the link.
     *
     * @param string $hits
     * @return this
     */
    public function setHits($hits)
    {
        $this->hits = (string)$hits;

        return $this;
    }
}
