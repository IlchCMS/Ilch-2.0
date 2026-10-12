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
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * Sort of the item.
     *
     * @var int|null
     */
    protected ?int $sort = null;

    /**
     * Type of the item.
     *
     * @var int|null
     */
    protected ?int $type = null;

    /**
     * UserId of the item.
     *
     * @var int|null
     */
    protected ?int $userId = null;

    /**
     * ParentId of the item.
     *
     * @var int|null
     */
    protected ?int $parentId = null;

    /**
     * Title of the item.
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * Description of the item.
     *
     * @var string|null
     */
    protected ?string $desc = null;

    /**
     * Gets the id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     * @return GalleryItem
     */
    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the sort.
     *
     * @return int|null
     */
    public function getSort(): ?int
    {
        return $this->sort;
    }

    /**
     * Sets the sort.
     *
     * @param int $sort
     * @return GalleryItem
     */
    public function setSort(int $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Gets the type.
     *
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param int $type
     * @return GalleryItem
     */
    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the gallery userId.
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Sets the gallery userId.
     *
     * @param int $userId
     * @return GalleryItem
     */
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the parent id.
     *
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * Sets the parent id.
     *
     * @param int $id
     * @return GalleryItem
     */
    public function setParentId(int $id): static
    {
        $this->parentId = $id;

        return $this;
    }

    /**
     * Gets the title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     * @return GalleryItem
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the desc.
     *
     * @return string|null
     */
    public function getDesc(): ?string
    {
        return $this->desc;
    }

    /**
     * Sets the desc.
     *
     * @param string $desc
     * @return GalleryItem
     */
    public function setDesc(string $desc): static
    {
        $this->desc = $desc;

        return $this;
    }
}
