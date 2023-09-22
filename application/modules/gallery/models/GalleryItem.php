<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Models;

use Ilch\Model;

/**
 * The gallery item model class.
 *
 * @package ilch
 */
class GalleryItem extends Model
{
    /**
     * Id of the item.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * Sort of the item.
     *
     * @var int
     */
    protected $sort = 0;

    /**
     * Type of the item.
     *
     * @var int
     */
    protected $type = 0;

    /**
     * ParentId of the item.
     *
     * @var int
     */
    protected $parentId = 0;

    /**
     * Title of the item.
     *
     * @var string
     */
    protected $title = '';

    /**
     * Description of the item.
     *
     * @var string
     */
    protected $desc = '';

    /**
     * Gets the id.
     *
     * @return int
     */
    public function getId(): int
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
     * @return int
     */
    public function getSort(): int
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
     * @return int
     */
    public function getType(): int
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
     * @return int
     */
    public function getParentId(): int
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
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
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
     */
    public function setDesc(string $desc)
    {
        $this->desc = $desc;
    }
}
