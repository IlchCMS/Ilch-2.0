<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class GalleryItem extends \Ilch\Model
{
    /**
     * Id of the item.
     *
     * @var int
     */
    protected $id;

    /**
     * Sort of the item.
     *
     * @var int
     */
    protected $sort;

    /**
     * Type of the item.
     *
     * @var int
     */
    protected $type;

    /**
     * UserId of the item.
     *
     * @var int
     */
    protected $userId;

    /**
     * ParentId of the item.
     *
     * @var int
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     * @return GalleryItem
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Gets the sort.
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Sets the sort.
     *
     * @param int $sort
     * @return GalleryItem
     */
    public function setSort($sort)
    {
        $this->sort = (int)$sort;

        return $this;
    }

    /**
     * Gets the type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param int $type
     * @return GalleryItem
     */
    public function setType($type)
    {
        $this->type = (int)$type;

        return $this;
    }

    /**
     * Gets the gallery userId.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the gallery userId.
     *
     * @param int $userId
     * @return GalleryItem
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;

        return $this;
    }

    /**
     * Gets the parent id.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Sets the parent id.
     *
     * @param int $id
     * @return GalleryItem
     */
    public function setParentId($id)
    {
        $this->parentId = (int) $id;

        return $this;
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
     * @return GalleryItem
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
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
     * @return GalleryItem
     */
    public function setDesc($desc)
    {
        $this->desc = (string)$desc;

        return $this;
    }
}
