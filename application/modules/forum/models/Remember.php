<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

/**
 * The remember model class.
 *
 * @package ilch
 */
class Remember extends \Ilch\Model
{
    /**
     * The id of the remember.
     *
     * @var integer
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
     * @var integer
     */
    protected $user_id;

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
     * @return Remember
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
     * @return Remember
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
     * @return Remember
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
     * @return Remember
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
     * @param string $note
     * @return Remember
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param int $user_id
     * @return Remember
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
     * @return string
     */
    public function getTopicTitle()
    {
        return $this->topicTitle;
    }

    /**
     * @param string $topicTitle
     */
    public function setTopicTitle($topicTitle)
    {
        $this->topicTitle = $topicTitle;
    }
}
