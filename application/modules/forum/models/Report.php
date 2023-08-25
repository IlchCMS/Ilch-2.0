<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

/**
 * The report model class.
 *
 * @package ilch
 */
class Report extends Model
{
    /**
     * The id of the report.
     *
     * @var int
     */
    protected $id;

    /**
     * Date of the report.
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
     * The reason of the report
     *
     * @var string
     */
    protected $reason;

    /**
     * The details of the report.
     *
     * @var string
     */
    protected $details;

    /**
     * The user id of the reporter.
     *
     * @var int
     */
    protected $user_id;

    /**
     * The username of the reporter.
     *
     * @var string
     */
    protected $username;

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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $date
     * @return Report
     */
    public function setDate(string $date): Report
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
     * @param int|null $forum_id
     * @return Report
     */
    public function setForumId(?int $forum_id): Report
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
     * @param int|null $topic_id
     * @return Report
     */
    public function setTopicId(?int $topic_id): Report
    {
        $this->topic_id = $topic_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTopicId(): ?int
    {
        return $this->topic_id;
    }

    /**
     * @param int $post_id
     * @return Report
     */
    public function setPostId(int $post_id): Report
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
     * @param string $reason
     * @return Report
     */
    public function setReason(string $reason): Report
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $details
     * @return Report
     */
    public function setDetails(string $details): Report
    {
        $this->details = $details;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param int $user_id
     * @return Report
     */
    public function setUserId(int $user_id): Report
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
     * @param string $username
     * @return Report
     */
    public function setUsername(string $username): Report
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
