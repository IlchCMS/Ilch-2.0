<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Friend extends \Ilch\Model
{
    /**
     * Id of the friend.
     *
     * @var integer
     */
    protected $id;

    /**
     * User Id.
     *
     * @var integer
     */
    protected $userId;

    /**
     * User Id of the friend.
     *
     * @var integer
     */
    protected $friendUserId;

    /**
     * Name of the friend.
     *
     * @var string
     */
    protected $name;

    /**
     * Avatar of the friend.
     *
     * @var string
     */
    protected $avatar;

    /**
     * Friend request still open or already accepted.
     *
     * @var int
     */
    protected $approved;

    /**
     * Sets the friend id.
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
     * Gets the friend id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the user id.
     *
     * @param integer $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the user id.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the user id of the friend.
     *
     * @param integer $friendUserId
     * @return $this
     */
    public function setFriendUserId($friendUserId)
    {
        $this->friendUserId = $friendUserId;

        return $this;
    }

    /**
     * Gets the user id of the friend.
     *
     * @return integer
     */
    public function getFriendUserId()
    {
        return $this->friendUserId;
    }

    /**
     * Get name of the friend.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name of the friend.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get avatar of friend.
     *
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set avatar of friend.
     *
     * @param mixed $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param mixed $approved
     * @return $this
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }
}
