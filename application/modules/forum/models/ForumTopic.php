<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Date;
use Ilch\Model;
use Modules\User\Models\User;

class ForumTopic extends Model
{
    /**
     * The id of the topic.
     *
     * @var int
     */
    protected $id;

    /**
     * The Prefix of the topic.
     *
     * @var string
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
     * @var User
     */
    protected $author;

    /**
     * The visits of the topic.
     *
     * @var int
     */
    protected $visits;

    /**
     * The forum id of the topic.
     *
     * @var int
     */
    protected $forum_id;

    /**
     * The type of the topic.
     *
     * @var int
     */
    protected $type;

    /**
     * The creator id of the topic.
     *
     * @var int
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
     * @var int
     */
    protected $status;

    /**
      * Gets the id of the topic.
      *
      * @return int|null
      */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the prefix of the topic.
     *
     * @return string
     */
    public function getTopicPrefix(): string
    {
        return $this->topic_prefix;
    }

    /**
     * Gets the title of the topic.
     *
     * @return string
     */
    public function getTopicTitle(): string
    {
        return $this->topic_title;
    }

    /**
     * Gets the author of the topic.
     *
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * Gets the visits of the topic.
     *
     * @return int
     */
    public function getVisits(): int
    {
        return $this->visits;
    }

    /**
     * Gets the visits of the topic.
     *
     * @return int
     */
    public function getForumId(): int
    {
        return $this->forum_id;
    }

    /**
     * Gets the id of the topic.
     *
     * @return int
     */
    public function getCreatorId(): int
    {
        return $this->creator_id;
    }

    /**
     * Gets the type of the topic.
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Returns the date_created \Ilch\Date of the topic.
     *
     * @return string
     */
    public function getDateCreated(): string
    {
        return $this->date_created;
    }

    /**
     * Sets the id of the topic.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): ForumTopic
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the prefix of the topic.
     *
     * @param string $topicPrefix
     * @return $this
     */
    public function setTopicPrefix(string $topicPrefix): ForumTopic
    {
        $this->topic_prefix = $topicPrefix;

        return $this;
    }

    /**
     * Sets the title of the topic.
     *
     * @param string $topicTitle
     * @return $this
     */
    public function setTopicTitle(string $topicTitle): ForumTopic
    {
        $this->topic_title = $topicTitle;

        return $this;
    }

    /**
     * Sets the author of the topic.
     *
     * @param User $author
     * @return $this
     */
    public function setAuthor(User $author): ForumTopic
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Sets the visits of the topic.
     *
     * @param int $visits
     * @return $this
     */
    public function setVisits(int $visits): ForumTopic
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * Sets the forum id of the topic.
     *
     * @param int $forumId
     * @return $this
     */
    public function setForumId(int $forumId): ForumTopic
    {
        $this->forum_id = $forumId;

        return $this;
    }

    /**
     * Sets the creator id of the topic.
     *
     * @param int $creatorId
     * @return $this
     */
    public function setCreatorId(int $creatorId): ForumTopic
    {
        $this->creator_id = $creatorId;

        return $this;
    }

    /**
     * Sets the type of the topic.
     *
     * @param int $type
     * @return $this
     */
    public function setType(int $type): ForumTopic
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Saves the date_created \Ilch\Date of the topic.
     *
     * @param Date|string $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated): ForumTopic
    {
        $this->date_created = $dateCreated;

        return $this;
    }

    /**
     * Gets the status of the topic.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the status of the topic.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): ForumTopic
    {
        $this->status = $status;

        return $this;
    }
}
