<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

/**
 * The Downloads item model class.
 *
 * @package ilch
 */
class ForumStatistics extends \Ilch\Model
{
    /**
     * Id of the item.
     *
     * @var integer
     */
    protected $countPosts;

    /**
     * Id of the item.
     *
     * @var integer
     */
    protected $countTopics;

    /**
     * Id of the item.
     *
     * @var integer
     */
    protected $countUsers;

    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setCountPosts($countPosts)
    {
        $this->countPosts = $countPosts;
    }

    /**
     * Gets the sort.
     *
     * @return integer
     */
    public function getCountPosts()
    {
        return $this->countPosts;
    }

    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setCountTopics($countTopics)
    {
        $this->countTopics = $countTopics;
    }

    /**
     * Gets the sort.
     *
     * @return integer
     */
    public function getCountTopics()
    {
        return $this->countTopics;
    }
    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setCountUsers($countUsers)
    {
        $this->countUsers = $countUsers;
    }

    /**
     * Gets the sort.
     *
     * @return integer
     */
    public function getCountUsers()
    {
        return $this->countUsers;
    }
}