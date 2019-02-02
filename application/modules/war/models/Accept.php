<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Models;

class Accept extends \Ilch\Model
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id;

    /**
     * The warId
     *
     * @var int
     */
    protected $warId;

    /**
     * The userId
     *
     * @var int
     */
    protected $userId;

    /**
     * The accept
     *
     * @var int
     */
    protected $accept;

    /**
     * Gets the id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the userId
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Gets the warId
     *
     * @return int
     */
    public function getWarId()
    {
        return $this->warId;
    }

    /**
     * Sets the warId
     *
     * @param int $warId
     * @return $this
     */
    public function setWarId($warId)
    {
        $this->warId = (int)$warId;

        return $this;
    }

    /**
     * Gets the accept.
     *
     * @return int
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Sets the accept.
     *
     * @param int $accept
     * @return $this
     */
    public function setAccept($accept)
    {
        $this->accept = (int)$accept;

        return $this;
    }
}
