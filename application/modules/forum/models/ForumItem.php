<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

class ForumItem extends Model
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
     * Parent Id of the item.
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
     * Read access of the item.
     *
     * @var string
     */
    protected $readAccess;

    /**
     * Reply access of the item.
     *
     * @var string
     */
    protected $replyAccess;

    /**
     * Create access of the item.
     *
     * @var string
     */
    protected $createAccess;

    /**
     * Sub items of the item.
     *
     * @var ForumItem[]
     */
    protected $subItems;

    /**
     * Topics of the item.
     *
     * @var int
     */
    protected $topics;

    /**
     * Last post of the item.
     *
     * @var ForumPost
     */
    protected $lastPost;

    /**
     * Posts of the item.
     *
     * @var int
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
     * @return $this
     */
    public function setId(int $id): ForumItem
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
     * @return $this
     */
    public function setSort(int $sort): ForumItem
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
     * @return $this
     */
    public function setType(int $type): ForumItem
    {
        $this->type = $type;

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
     * @return $this
     */
    public function setParentId(int $id): ForumItem
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
     * @return $this
     */
    public function setTitle(string $title): ForumItem
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
     * @return $this
     */
    public function setDesc(string $desc): ForumItem
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Gets the read access.
     *
     * @return string|null
     */
    public function getReadAccess(): ?string
    {
        return $this->readAccess;
    }

    /**
     * Sets the read access.
     *
     * @param string $readAccess
     * @return $this
     */
    public function setReadAccess(string $readAccess): ForumItem
    {
        $this->readAccess = $readAccess;

        return $this;
    }

    /**
     * Gets the reply access.
     *
     * @return string|null
     */
    public function getReplyAccess(): ?string
    {
        return $this->replyAccess;
    }

    /**
     * Sets the reply access.
     *
     * @param string $replyAccess
     * @return $this
     */
    public function setReplyAccess(string $replyAccess): ForumItem
    {
        $this->replyAccess = $replyAccess;

        return $this;
    }

    /**
     * Gets the create access.
     *
     * @return string|null
     */
    public function getCreateAccess(): ?string
    {
        return $this->createAccess;
    }

    /**
     * Sets the create access.
     *
     * @param string $createAccess
     * @return $this
     */
    public function setCreateAccess(string $createAccess): ForumItem
    {
        $this->createAccess = $createAccess;

        return $this;
    }

    /**
     * Gets the sub items.
     *
     * @return ForumItem[]
     */
    public function getSubItems(): array
    {
        return $this->subItems;
    }

    /**
     * Sets the sub items.
     *
     * @param ForumItem[] $subItems
     * @return $this
     */
    public function setSubItems(array $subItems): ForumItem
    {
        $this->subItems = $subItems;

        return $this;
    }

    /**
     * Gets the topics.
     *
     * @return int
     */
    public function getTopics(): int
    {
        return $this->topics;
    }

    /**
     * Sets the topics.
     *
     * @param int $topics
     * @return $this
     */
    public function setTopics(int $topics): ForumItem
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * Gets the last post.
     *
     * @return ForumPost
     */
    public function getLastPost(): ?ForumPost
    {
        return $this->lastPost;
    }

    /**
     * Sets the last post.
     *
     * @param ForumPost|null $lastPost
     * @return $this
     */
    public function setLastPost(?ForumPost $lastPost): ForumItem
    {
        $this->lastPost = $lastPost;

        return $this;
    }

    /**
     * Gets the posts.
     *
     * @return int
     */
    public function getPosts(): int
    {
        return $this->posts;
    }

    /**
     * Sets the posts.
     *
     * @param int $posts
     * @return $this
     */
    public function setPosts(int $posts): ForumItem
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Gets the prefix.
     *
     * @return string|null
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * Sets the prefix.
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix): ForumItem
    {
        $this->prefix = $prefix;

        return $this;
    }
}
