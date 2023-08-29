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
     * DownloadsId of the item.
     *
     * @var int
     */
    protected $downloadsId;

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
     */
    public function setId($id)
    {
        $this->id = (int) $id;
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
     */
    public function setSort($sort)
    {
        $this->sort = (int)$sort;
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
     */
    public function setType($type)
    {
        $this->type = (int)$type;
    }

    /**
     * Gets the Downloads id.
     *
     * @return int
     */
    public function getDownloadsId()
    {
        return $this->downloadsId;
    }

    /**
     * Sets the Downloads id.
     *
     * @param int $id
     */
    public function setDownloadsId($id)
    {
        $this->downloadsId = (int) $id;
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