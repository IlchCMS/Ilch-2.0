<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Models;

use Ilch\Model;

/**
 * The Downloads item model class.
 *
 * @package ilch
 */
class DownloadsItem extends Model
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
     * @var int
     */
    protected int $sort = 0;

    /**
     * Type of the item.
     *
     * @var int|null
     */
    protected ?int $type = null;

    /**
     * ParentId of the item.
     *
     * @var int
     */
    protected int $parentId = 0;

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
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     */
    public function setSort(int $sort)
    {
        $this->sort = $sort;
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
     */
    public function setType(int $type)
    {
        $this->type = $type;
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
     */
    public function setParentId(int $id)
    {
        $this->parentId = $id;
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
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
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
     */
    public function setDesc(string $desc)
    {
        $this->desc = $desc;
    }
}
