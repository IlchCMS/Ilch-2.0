<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

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
     * ParentId of the item.
     *
     * @var integer
     */
    protected $subItems;

    /**
     * ParentId of the item.
     *
     * @var integer
     */
    protected $topics;

    /**
     * ParentId of the item.
     *
     * @var integer
     */
    protected $lastPost;

    /**
     * ParentId of the item.
     *
     * @var integer
     */
    protected $posts;

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

    /**
     * Gets the sub items.
     *
     * @return
     */
    public function getSubItems()
    {
        return $this->subItems;
    }

    /**
     * Sets the sub items.
     *
     * @param
     */
    public function setSubItems($subItems)
    {
        $this->subItems = $subItems;
    }

    /**
     * Gets the topics.
     *
     * @return
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Sets the topics.
     *
     * @param
     */
    public function setTopics($topics)
    {
        $this->topics = $topics;
    }

    /**
     * Gets the last post.
     *
     * @return
     */
    public function getLastPost()
    {
        return $this->lastPost;
    }

    /**
     * Sets the last post.
     *
     * @param
     */
    public function setLastPost($lastPost)
    {
        $this->lastPost = $lastPost;
    }

    /**
     * Gets the posts.
     *
     * @return integer
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Sets the posts.
     *
     * @param
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }
}
