<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

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
     * UserId of the item.
     *
     * @var integer
     */
    protected $userId;

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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param integer $id
     * @return GalleryItem
     */
    public function setId($id): GalleryItem
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Gets the sort.
     *
     * @return integer
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * Sets the sort.
     *
     * @param integer $sort
     * @return GalleryItem
     */
    public function setSort($sort): GalleryItem
    {
        $this->sort = (int)$sort;

        return $this;
    }

    /**
     * Gets the type.
     *
     * @return integer
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param integer $type
     * @return GalleryItem
     */
    public function setType($type): GalleryItem
    {
        $this->type = (int)$type;

        return $this;
    }

    /**
     * Gets the gallery userId.
     *
     * @return integer
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the gallery userId.
     *
     * @param integer $userId
     * @return GalleryItem
     */
    public function setUserId($userId): GalleryItem
    {
        $this->userId = (int) $userId;

        return $this;
    }

    /**
     * Gets the parent id.
     *
     * @return integer
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * Sets the parent id.
     *
     * @param integer $id
     * @return GalleryItem
     */
    public function setParentId($id): GalleryItem
    {
        $this->parentId = (int) $id;

        return $this;
    }

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     * @return GalleryItem
     */
    public function setTitle($title): GalleryItem
    {
        $this->title = (string) $title;

        return $this;
    }

    /**
     * Gets the desc.
     *
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * Sets the desc.
     *
     * @param string $desc
     * @return GalleryItem
     */
    public function setDesc($desc): GalleryItem
    {
        $this->desc = (string)$desc;

        return $this;
    }
}