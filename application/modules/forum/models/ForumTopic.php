<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

class ForumTopic extends \Ilch\Model
{
    /**
     * The id of the topic.
     *
     * @var integer
     */
    protected $id;

    /**
     * The last insert id of the topic.
     *
     * @var integer
     */
    protected $last_insert_id;

    /**
     * The Prefix of the topic.
     *
     * @var integer
     */
    protected $topic_prefix;

    /**
     * The title of the topic.
     *
     * @var string
     */
    protected $topic_title;

    /**
     * The author of the topic.
     *
     * @var string
     */
    protected $author;

    /**
     * The image of the topic.
     *
     * @var string
     */
    protected $topic_image;

    /**
     * The cat of the topic.
     *
     * @var string
     */
    protected $cat;

    /**
     * The visits of the topic.
     *
     * @var string
     */
    protected $visits;

    /**
     * The forum id of the topic.
     *
     * @var string
     */
    protected $forum_id;

    /**
     * The url of the topic.
     *
     * @var string
     */
    protected $topic_url;

    /**
     * The type of the topic.
     *
     * @var string
     */
    protected $type;

    /**
     * The read of the topic.
     *
     * @var string
     */
    protected $read;

    /**
     * The creator id of the topic.
     *
     * @var string
     */
    protected $creator_id;

    /**
     * The date created of the topic.
     *
     * @var string
     */
    protected $date_created;

    /**
     * The status of the topic.
     *
     * @var integer
     */
    protected $status;

   /**
     * Gets the id of the topic.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the last insert id of the topic.
     *
     * @return integer
     */
    public function getLastInsertId()
    {
        return $this->last_insert_id;
    }

    /**
     * Gets the thumb of the topic.
     *
     * @return string
     */
    public function getTopicThumb()
    {
        return $this->topicThumb;
    }

    /**
     * Gets the prefix of the topic.
     *
     * @return integer
     */
    public function getTopicPrefix()
    {
        return $this->topic_prefix;
    }

    /**
     * Gets the title of the topic.
     *
     * @return string
     */
    public function getTopicTitle()
    {
        return $this->topic_title;
    }

    /**
     * Gets the author of the topic.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Gets the image of the topic.
     *
     * @return string
     */
    public function getTopicImage()
    {
        return $this->topic_image;
    }

    /**
     * Gets the cat of the topic.
     *
     * @return string
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Gets the visits of the topic.
     *
     * @return string
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Gets the visits of the topic.
     *
     * @return string
     */
    public function getForumId()
    {
        return $this->forum_id;
    }

    /**
     * Gets the FileUrl of the topic.
     *
     * @return string
     */
    public function getTopicUrl()
    {
        return $this->topic_url;
    }

    /**
     * Gets the id of the topic.
     *
     * @return integer
     */
    public function getCreatorId()
    {
        return $this->creator_id;
    }

    /**
     * Gets the type of the topic.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the read of the topic.
     *
     * @return string
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Returns the date_created \Ilch\Date of the topic.
     *
     * @return \Ilch\Date
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Sets the id of the topic.
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Sets the last insert id of the topic.
     *
     * @param int $lastInsertId
     * @return $this
     */
    public function setLastInsertId($lastInsertId)
    {
        $this->last_insert_id = (int) $lastInsertId;

        return $this;
    }

    /**
     * Sets the thumb of the topic.
     *
     * @param string $topicThumb
     * @return $this
     */
    public function setTopicThumb($topicThumb)
    {
        $this->topicthumb = (string) $topicThumb;

        return $this;
    }

    /**
     * Sets the prefix of the topic.
     *
     * @param int $topicPrefix
     * @return $this
     */
    public function setTopicPrefix($topicPrefix)
    {
        $this->topic_prefix = (int) $topicPrefix;

        return $this;
    }

    /**
     * Sets the title of the topic.
     *
     * @param string $topicTitle
     * @return $this
     */
    public function setTopicTitle($topicTitle)
    {
        $this->topic_title = (string) $topicTitle;

        return $this;
    }

    /**
     * Sets the author of the topic.
     *
     * @param string $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Sets the image of the topic.
     *
     * @param string $topicImage
     * @return $this
     */
    public function setTopicImage($topicImage)
    {
        $this->topic_image = (string) $topicImage;

        return $this;
    }

    /**
     * Sets the cat of the topic.
     *
     * @param string $cat
     * @return $this
     */
    public function setCat($cat)
    {
        $this->cat = (string) $cat;

        return $this;
    }

    /**
     * Sets the visits of the topic.
     *
     * @param string $visits
     * @return $this
     */
    public function setVisits($visits)
    {
        $this->visits = (string) $visits;

        return $this;
    }

    /**
     * Sets the forum id of the topic.
     *
     * @param int $forumId
     * @return $this
     */
    public function setForumId($forumId)
    {
        $this->forum_id = (int) $forumId;

        return $this;
    }

    /**
     * Sets the url of the topic.
     *
     * @param string $topicUrl
     * @return $this
     */
    public function setTopicUrl($topicUrl)
    {
        $this->topic_url = (string) $topicUrl;

        return $this;
    }

    /**
     * Sets the creator id of the topic.
     *
     * @param int $creatorId
     * @return $this
     */
    public function setCreatorId($creatorId)
    {
        $this->creator_id = (int) $creatorId;

        return $this;
    }

    /**
     * Sets the type of the topic.
     *
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = (int) $type;

        return $this;
    }

    /**
     * Sets the read of the topic.
     *
     * @param string $read
     * @return $this
     */
    public function setRead($read)
    {
        $this->read = (string) $read;

        return $this;
    }

    /**
     * Saves the date_created \Ilch\Date of the topic.
     *
     * @param \Ilch\Date $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;

        return $this;
    }

    /**
     * Gets the status of the topic.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status of the topic.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = (int) $status;

        return $this;
    }
}
