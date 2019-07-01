<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

/**
 * The report model class.
 *
 * @package ilch
 */
class Report extends \Ilch\Model
{
    /**
     * The id of the report.
     *
     * @var integer
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
     * @var integer
     */
    protected $forum_id;

    /**
     * The topic id of the post.
     *
     * @var integer
     */
    protected $topic_id;

    /**
     * The id of the post.
     *
     * @var integer
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
     * @var integer
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
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * @param string $date
     * @return Report
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $forum_id
     * @return Report
     */
    public function setForumId($forum_id)
    {
        $this->forum_id = $forum_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getForumId()
    {
        return $this->forum_id;
    }

    /**
     * @param int $topic_id
     * @return Report
     */
    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getTopicId()
    {
        return $this->topic_id;
    }

    /**
     * @param int $post_id
     * @return Report
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param string $reason
     * @return Report
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $details
     * @return Report
     */
    public function setDetails($details)
    {
        $this->details = $details;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param int $user_id
     * @return Report
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string $username
     * @return Report
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
