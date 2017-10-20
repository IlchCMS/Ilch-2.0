<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

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
     * Parent Id of the item.
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
     * Read access of the item.
     *
     * @var integer
     */
    protected $readAccess;

    /**
     * Replay access of the item.
     *
     * @var integer
     */
    protected $replayAccess;

    /**
     * Create access of the item.
     *
     * @var integer
     */
    protected $createAccess;

    /**
     * Sub items of the item.
     *
     * @var integer
     */
    protected $subItems;

    /**
     * Topics of the item.
     *
     * @var integer
     */
    protected $topics;

    /**
     * Last post of the item.
     *
     * @var integer
     */
    protected $lastPost;

    /**
     * Posts of the item.
     *
     * @var integer
     */
    protected $posts;

    /**
     * Prefix of the item.
     *
     * @var string
     */
    protected $prefix;

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
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
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
     * @return $this
     */
    public function setSort($sort)
    {
        $this->sort = (int)$sort;

        return $this;
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
     * @return $this
     */
    public function setType($type)
    {
        $this->type = (int)$type;

        return $this;
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setDesc($desc)
    {
        $this->desc = (string)$desc;

        return $this;
    }

    /**
     * Gets the read access.
     *
     * @return integer
     */
    public function getReadAccess()
    {
        return $this->readAccess;
    }

    /**
     * Sets the read access.
     *
     * @param integer $readAccess
     * @return $this
     */
    public function setReadAccess($readAccess)
    {
        $this->readAccess = (string) $readAccess;

        return $this;
    }

    /**
     * Gets the replay access.
     *
     * @return integer
     */
    public function getReplayAccess()
    {
        return $this->replayAccess;
    }

    /**
     * Sets the replay access.
     *
     * @param integer $replayAccess
     * @return $this
     */
    public function setReplayAccess($replayAccess)
    {
        $this->replayAccess = (string) $replayAccess;

        return $this;
    }

    /**
     * Gets the create access.
     *
     * @return integer
     */
    public function getCreateAccess()
    {
        return $this->createAccess;
    }

    /**
     * Sets the create access.
     *
     * @param integer $createAccess
     * @return $this
     */
    public function setCreateAccess($createAccess)
    {
        $this->createAccess = (string) $createAccess;

        return $this;
    }

    /**
     * Gets the sub items.
     *
     * @return integer
     */
    public function getSubItems()
    {
        return $this->subItems;
    }

    /**
     * Sets the sub items.
     *
     * @param
     * @return $this
     */
    public function setSubItems($subItems)
    {
        $this->subItems = $subItems;

        return $this;
    }

    /**
     * Gets the topics.
     *
     * @return integer
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Sets the topics.
     *
     * @param $topics
     * @return $this
     */
    public function setTopics($topics)
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * Gets the last post.
     *
     * @return integer
     */
    public function getLastPost()
    {
        return $this->lastPost;
    }

    /**
     * Sets the last post.
     *
     * @param $lastPost
     * @return $this
     */
    public function setLastPost($lastPost)
    {
        $this->lastPost = $lastPost;

        return $this;
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
     * @param $posts
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Gets the prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Sets the prefix.
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = (string) $prefix;

        return $this;
    }
}
