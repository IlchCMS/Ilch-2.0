<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

/**
 * The remember model class.
 *
 * @package ilch
 */
class Remember extends Model
{
    /**
     * The id of the remember.
     *
     * @var int
     */
    protected $id;

    /**
     * Date of the remember.
     *
     * @var string
     */
    protected $date;

    /**
     * The forum id of the post.
     *
     * @var int
     */
    protected $forum_id;

    /**
     * The topic id of the post.
     *
     * @var int
     */
    protected $topic_id;

    /**
     * The id of the post.
     *
     * @var int
     */
    protected $post_id;

    /**
     * Title of the topic.
     *
     * @var string
     */
    protected $topicTitle;

    /**
     * The details of the remember.
     *
     * @var string
     */
    protected $note;

    /**
     * The user id.
     *
     * @var int
     */
    protected $user_id;

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
     * Gets the id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $date
     * @return Remember
     */
    public function setDate(string $date): Remember
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param int $forum_id
     * @return Remember
     */
    public function setForumId(int $forum_id): Remember
    {
        $this->forum_id = $forum_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getForumId(): int
    {
        return $this->forum_id;
    }

    /**
     * @param int $topic_id
     * @return Remember
     */
    public function setTopicId(int $topic_id): Remember
    {
        $this->topic_id = $topic_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTopicId(): int
    {
        return $this->topic_id;
    }

    /**
     * @param int $post_id
     * @return Remember
     */
    public function setPostId(int $post_id): Remember
    {
        $this->post_id = $post_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->post_id;
    }

    /**
     * @param string $note
     * @return Remember
     */
    public function setNote(string $note): Remember
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param int $user_id
     * @return Remember
     */
    public function setUserId(int $user_id): Remember
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getTopicTitle(): string
    {
        return $this->topicTitle;
    }

    /**
     * @param string $topicTitle
     */
    public function setTopicTitle(string $topicTitle)
    {
        $this->topicTitle = $topicTitle;
    }
}
