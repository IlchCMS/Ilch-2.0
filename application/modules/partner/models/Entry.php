<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Models;

class Entry extends \Ilch\Model
{
    /**
     * The id of the partner.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the partner.
     *
     * @var string
     */
    protected $name;

    /**
     * The link of the partner.
     *
     * @var string
     */
    protected $link;

    /**
     * The banner of the partner.
     *
     * @var string
     */
    protected $banner;
    
    /**
     * The free of the entry.
     *
     * @var integer
     */
    protected $free;

    /**
     * Gets the id of the partner.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Gets the free of the entry.
     *
     * @return integer
     */
    
    public function getFree()
    {
        return $this->free;
    }

    /**
     * Sets the id of the partner.
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
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the partner.
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
     * Gets the link of the partner.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the link of the partner.
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
     * Gets the banner of the partner.
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Sets the banner of the partner.
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
     * Set the free of the entry.
     *
     * @return integer
     */
    public function setFree($free)
    {
        $this->free = (int)$free;
    }
}
