<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Gallery\Models;

/**
 * The gallery item model class.
 *
 * @package ilch
 */
class GalleryItem extends \Ilch\Model
{
    /**
     * Id of the item.
     *
     * @var integer
     */
    protected $id;

    /**
     * Sort of the item.
     *
     * @var integer
     */
    protected $sort;

    /**
     * Type of the item.
     *
     * @var integer
     */
    protected $type;

    /**
     * GalleryId of the item.
     *
     * @var integer
     */
    protected $galleryId;

    /**
     * ParentId of the item.
     *
     * @var integer
     */
    protected $parentId;

    /**
     * Title of the item.
     *
     * @var string
     */
    protected $title;

    /**
     * Description of the item.
     *
     * @var string
     */
    protected $desc;

    /**
     * Gets the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the sort.
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Sets the sort.
     *
     * @param integer $sort
     */
    public function setSort($sort)
    {
        $this->sort = (int)$sort;
    }

    /**
     * Gets the type.
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = (int)$type;
    }

    /**
     * Gets the gallery id.
     *
     * @return integer
     */
    public function getGalleryId()
    {
        return $this->galleryId;
    }

    /**
     * Sets the gallery id.
     *
     * @param integer $id
     */
    public function setGalleryId($id)
    {
        $this->galleryId = (int) $id;
    }

    /**
     * Gets the parent id.
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Sets the parent id.
     *
     * @param integer $id
     */
    public function setParentId($id)
    {
        $this->parentId = (int) $id;
    }

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Gets the desc.
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Sets the desc.
     *
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = (string)$desc;
    }
}