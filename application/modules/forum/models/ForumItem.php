<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

defined('ACCESS') or die('no direct access');

/**
 * The Downloads item model class.
 *
 * @package ilch
 */
class ForumItem extends \Ilch\Model
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
     * DownloadsId of the item.
     *
     * @var integer
     */
    protected $forumId;

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
     * ParentId of the item.
     *
     * @var integer
     */
    protected $readAccess;

    /**
     * ParentId of the item.
     *
     * @var integer
     */
    protected $replayAccess;

    /**
     * ParentId of the item.
     *
     * @var integer
     */
    protected $createAccess;

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
     * Gets the Downloads id.
     *
     * @return integer
     */
    public function getForumId()
    {
        return $this->forumId;
    }

    /**
     * Sets the Downloads id.
     *
     * @param integer $id
     */
    public function setForumId($id)
    {
        $this->forumId = (int) $id;
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

    /**
     * Gets the parent id.
     *
     * @return integer
     */
    public function getReadAccess()
    {
        return $this->readAccess;
    }

    /**
     * Sets the parent id.
     *
     * @param integer $id
     */
    public function setReadAccess($readAccess)
    {
        $this->readAccess = (string) $readAccess;
    }

    /**
     * Gets the parent id.
     *
     * @return integer
     */
    public function getReplayAccess()
    {
        return $this->replayAccess;
    }

    /**
     * Sets the parent id.
     *
     * @param integer $id
     */
    public function setReplayAccess($replayAccess)
    {
        $this->replayAccess = (string) $replayAccess;
    }

    /**
     * Gets the parent id.
     *
     * @return integer
     */
    public function getCreateAccess()
    {
        return $this->createAccess;
    }

    /**
     * Sets the parent id.
     *
     * @param integer $id
     */
    public function setCreateAccess($createAccess)
    {
        $this->createAccess = (string) $createAccess;
    }
}