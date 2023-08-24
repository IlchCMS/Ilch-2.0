<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

/**
 * The Downloads item model class.
 *
 * @package ilch
 */
class ForumStatistics extends Model
{
    /**
     * Id of the item.
     *
     * @var int
     */
    protected $countPosts;

    /**
     * Id of the item.
     *
     * @var int
     */
    protected $countTopics;

    /**
     * Id of the item.
     *
     * @var int
     */
    protected $countUsers;

    /**
     * Sets the id.
     *
     * @param int $countPosts
     */
    public function setCountPosts(int $countPosts)
    {
        $this->countPosts = $countPosts;
    }

    /**
     * Gets the sort.
     *
     * @return int
     */
    public function getCountPosts(): int
    {
        return $this->countPosts;
    }

    /**
     * Sets the id.
     *
     * @param int $countTopics
     */
    public function setCountTopics(int $countTopics)
    {
        $this->countTopics = $countTopics;
    }

    /**
     * Gets the sort.
     *
     * @return int
     */
    public function getCountTopics(): int
    {
        return $this->countTopics;
    }
    /**
     * Sets the id.
     *
     * @param int $countUsers
     */
    public function setCountUsers(int $countUsers)
    {
        $this->countUsers = $countUsers;
    }

    /**
     * Gets the sort.
     *
     * @return int
     */
    public function getCountUsers(): int
    {
        return $this->countUsers;
    }
}
