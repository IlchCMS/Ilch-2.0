<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Dialog extends \Ilch\Model
{
    /**
     * ID of the dialog
     */
    private $id;

    /**
     * C_ID of the dialog
     */
    private $c_id;

    /**
     * CR_ID of the dialog
     */
    private $cr_id;

    /**
     * The TEXT of the dialog
     */
    private $text;

    /**
     * USER_ONE of the dialog
     */
    private $user_one;

    /**
     * USER_TWO of the dialog
     */
    private $user_two;

    /**
     * TIME when the message was sent (TIMESTAMP)
     */
    private $time;

    /**
     * Indicates if conversation/dialog is hidden or not.
     *
     * @var bool
     */
    private $hidden;

    /**
     * NAME of the user
     */
    private $name;

    /**
     * READ
     */
    private $read;

    /**
     * Set the ID of the message
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the ID of the message
     * @return ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the CONVERSATION_ID of the dialog
     * @param int $cid
     * @return $this
     */
    public function setCId($cid)
    {
        $this->c_id = $cid;

        return $this;
    }

    /**
     * Get the CONVERSATION_ID of the dialog
     * @return int
     */
    public function getCId()
    {
        return $this->c_id;
    }

    /**
     * Set the CONVERSATION_REPLY_ID of the dialog
     * @param int $crid
     * @return $this
     */
    public function setCrId($crid)
    {
        $this->cr_id = $crid;

        return $this;
    }

    /**
     * Get the CONVERSATION_REPLY_ID of the dialog
     * @return int
     */
    public function getCrId()
    {
        return $this->cr_id;
    }

    /**
     * Set the senttime of the dialog
     * @param int $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get the senttime of the message
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Get the value of hidden. If true the conversation is hidden.
     *
     * @return bool
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Set the value of hidden. True for hidden, false for not.
     *
     * @param bool $hidden
     * @return $this
     */
    public function setHidden(bool $hidden): Dialog
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Set the USER_ONE of the dialog
     * @param int $userone
     * @return $this
     */
    public function setUserOne($userone)
    {
        $this->user_one = $userone;

        return $this;
    }

    /**
     * Get the USER_ONE of the dialog
     * @return void
     */
    public function getUserOne()
    {
        return $this->user_one;
    }

    /**
     * Set the USER_TWO of the dialog
     * @param int $usertwo
     * @return $this
     */
    public function setUserTwo($usertwo)
    {
        $this->user_two = $usertwo;

        return $this;
    }

    /**
     * Get the USER_ONE of the dialog
     * @return void
     */
    public function getUserTwo()
    {
        return $this->user_two;
    }

    /**
     * Set the TEXT of the dialog
     * @param String $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the text of the dialog
     * @return text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the avatar of the user.
     * @return $this
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set the AVATAR of the dialog
     * @param $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = (string)$avatar;

        return $this;
    }

    /**
     * Set the NAME of the user
     * @param string $name
     * @return Dialog
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the NAME of the user
     * @return $this
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the read
     * @param string $read
     * @return $this
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get the read
     * @return int
     */
    public function getRead()
    {
        return $this->read;
    }
}
