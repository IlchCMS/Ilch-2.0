<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

/**
 * The TrackRead model class. It is used to handle the information
 * regarding read forums or topics.
 *
 * @package ilch
 */
class TrackRead extends Model
{
    /**
     * @var int
     */
    protected $user_id;

    /**
     * @var int
     */
    protected $forum_id;

    /**
     * @var int
     */
    protected $topic_id;

    /**
     * @var int
     */
    protected $datetime;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return TrackRead
     */
    public function setUserId(int $user_id): TrackRead
    {
        $this->user_id = $user_id;
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
     * @param int $forum_id
     * @return TrackRead
     */
    public function setForumId(int $forum_id): TrackRead
    {
        $this->forum_id = $forum_id;
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
     * @param int $topic_id
     * @return TrackRead
     */
    public function setTopicId(int $topic_id): TrackRead
    {
        $this->topic_id = $topic_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getDatetime(): int
    {
        return $this->datetime;
    }

    /**
     * @param int $datetime
     * @return TrackRead
     */
    public function setDatetime(int $datetime): TrackRead
    {
        $this->datetime = $datetime;
        return $this;
    }
}
