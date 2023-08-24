<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Date;
use Ilch\Model;
use Modules\User\Models\User;

class ForumPost extends Model
{
    /**
     * The id of the post.
     *
     * @var int
     */
    protected $id;

    /**
     * The topic d of the post.
     *
     * @var int
     */
    protected $topic_id;

    /**
     * The title of the post.
     *
     * @var string
     */
    protected $topic_title;

    /**
     * The text of the post.
     *
     * @var string
     */
    protected $text;

    /**
     * The forum id of the post.
     *
     * @var int
     */
    protected $forum_id;

    /**
     * The read of the post.
     *
     * @var string
     */
    protected $read;

    /**
     * The user id of the post.
     *
     * @var int
     */
    protected $user_id;

    /**
     * The date created of the post.
     *
     * @var string
     */
    protected $date_created;

    /**
     * The autor of the post.
     *
     * @var User
     */
    protected $autor;

    /**
     * The autor all of the post.
     *
     * @var string
     */
    protected $autorallpost;

    /**
     * Count of votes.
     *
     * @var int
     */
    protected $countOfVotes;

    /**
     * User has voted for this post?
     *
     * @var bool
     */
    protected $userHasVoted;

    /**
     * Gets the id of the post.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the post.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): ForumPost
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the topic id of the post.
     *
     * @return int
     */
    public function getTopicId(): int
    {
        return $this->topic_id;
    }

    /**
     * Sets the topic id of the post.
     *
     * @param int $topic_id
     * @return $this
     */
    public function setTopicId(int $topic_id): ForumPost
    {
        $this->topic_id = $topic_id;

        return $this;
    }

    /**
     * Gets the read of the post.
     *
     * @return string
     */
    public function getRead(): string
    {
        return $this->read;
    }

    /**
     * Sets the read of the post.
     *
     * @param string $read
     * @return $this
     */
    public function setRead(string $read): ForumPost
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Gets the title of the post.
     *
     * @return string
     */
    public function getTopicTitle(): string
    {
        return $this->topic_title;
    }

    /**
     * Sets the title of the post.
     *
     * @param string $topicTitle
     * @return $this
     */
    public function setTopicTitle(string $topicTitle): ForumPost
    {
        $this->topic_title = $topicTitle;

        return $this;
    }

    /**
     * Gets the text of the post.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the post.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): ForumPost
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the forum id of the post.
     *
     * @return int
     */
    public function getForumId(): int
    {
        return $this->forum_id;
    }

    /**
     * Sets the forum id of the post.
     *
     * @param int $forumId
     * @return $this
     */
    public function setForumId(int $forumId): ForumPost
    {
        $this->forum_id = $forumId;

        return $this;
    }

    /**
     * Gets the user id of the post.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * Sets the user id of the post.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): ForumPost
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Returns the date created \Ilch\Date of the post.
     *
     * @return string
     */
    public function getDateCreated(): string
    {
        return $this->date_created;
    }

    /**
     * Saves the date created \Ilch\Date of the post.
     *
     * @param Date|string $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated): ForumPost
    {
        $this->date_created = $dateCreated;

        return $this;
    }

    /**
     * Returns the autor of the post.
     *
     * @return User
     */
    public function getAutor(): User
    {
        return $this->autor;
    }

    /**
     * Saves the $autor of the post.
     *
     * @param User $autor
     * @return $this
     */
    public function setAutor(User $autor): ForumPost
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Returns the autor all of the post.
     *
     * @return string|null
     */
    public function getAutorAllPost(): ?string
    {
        return $this->autorallpost;
    }

    /**
     * Saves the autor all of the post.
     *
     * @param $autorAllPost
     * @return $this
     */
    public function setAutorAllPost($autorAllPost): ForumPost
    {
        $this->autorallpost = $autorAllPost;

        return $this;
    }

    /**
     * Get count of votes.
     *
     * @return int
     */
    public function getCountOfVotes(): int
    {
        return $this->countOfVotes;
    }


    /**
     * Set count of votes.
     *
     * @param int $count
     * @return ForumPost
     */
    public function setCountOfVotes(int $count): ForumPost
    {
        $this->countOfVotes = $count;

        return $this;
    }

    /**
     * User has voted for this post?
     *
     * @return bool
     */
    public function isUserHasVoted(): bool
    {
        return $this->userHasVoted;
    }

    /**
     * Set if the user has voted for the post.
     *
     * @param bool $userHasVoted
     * @return ForumPost
     */
    public function setUserHasVoted(bool $userHasVoted): ForumPost
    {
        $this->userHasVoted = $userHasVoted;
        return $this;
    }
}
