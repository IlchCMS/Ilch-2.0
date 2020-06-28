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
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the ID of the message
     * @return ID
     */
    public function getId(): ID
    {
        return $this->id;
    }

    /**
     * Set the CONVERSATION_ID of the dialog
     * @param int $cid
     * @return $this
     */
    public function setCId($cid): self
    {
        $this->c_id = $cid;

        return $this;
    }

    /**
     * Get the CONVERSATION_ID of the dialog
     * @return int
     */
    public function getCId(): int
    {
        return $this->c_id;
    }

    /**
     * Set the CONVERSATION_REPLY_ID of the dialog
     * @param int $crid
     * @return $this
     */
    public function setCrId($crid): self
    {
        $this->cr_id = $crid;

        return $this;
    }

    /**
     * Get the CONVERSATION_REPLY_ID of the dialog
     * @return int
     */
    public function getCrId(): int
    {
        return $this->cr_id;
    }

    /**
     * Set the senttime of the dialog
     * @param int $time
     * @return $this
     */
    public function setTime($time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get the senttime of the message
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * Set the USER_ONE of the dialog
     * @param int $userone
     * @return $this
     */
    public function setUserOne($userone): self
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
    public function setUserTwo($usertwo): self
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
    public function setText($text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the text of the dialog
     * @return text
     */
    public function getText(): text
    {
        return $this->text;
    }

    /**
     * Get the avatar of the user.
     * @return $this
     */
    public function getAvatar(): self
    {
        return $this->avatar;
    }

    /**
     * Set the AVATAR of the dialog
     * @param $avatar
     * @return $this
     */
    public function setAvatar($avatar): self
    {
        $this->avatar = (string)$avatar;

        return $this;
    }

    /**
     * Set the NAME of the user
     * @param string $name
     * @return void
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
    public function getName(): self
    {
        return $this->name;
    }

    /**
     * Set the read
     * @param string $read
     * @return $this
     */
    public function setRead($read): self
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get the read
     * @return int
     */
    public function getRead(): int
    {
        return $this->read;
    }
}
