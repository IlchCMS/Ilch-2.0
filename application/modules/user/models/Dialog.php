<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

/**
 * Model for the dialog feature.
 *
 * Note: "id" holds the user id (message author / counterpart user depending
 * on context), NOT the conversation id - that is stored in "c_id".
 */
class Dialog extends \Ilch\Model
{
    /**
     * ID of the user (message author / counterpart user, depending on context)
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * C_ID of the conversation/dialog
     *
     * @var int|null
     */
    private ?int $c_id = null;

    /**
     * CR_ID of the conversation reply
     *
     * @var int|null
     */
    private ?int $cr_id = null;

    /**
     * The TEXT of the dialog message
     *
     * @var string|null
     */
    private ?string $text = null;

    /**
     * user_one of the dialog
     *
     * @var int|null
     */
    private ?int $user_one = null;

    /**
     * user_two of the dialog
     *
     * @var int|null
     */
    private ?int $user_two = null;

    /**
     * Time when the message was sent (TIMESTAMP)
     *
     * @var string|null
     */
    private ?string $time = null;

    /**
     * Indicates if conversation/dialog is hidden or not.
     *
     * @var bool
     */
    private bool $hidden = false;

    /**
     * Name of the user
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * Read status. True if the conversation is fully read for the viewer,
     * false if unread messages of the other user exist.
     *
     * @var bool
     */
    private bool $read = false;

    /**
     * Avatar of the user.
     *
     * @var string|null
     */
    private ?string $avatar = null;

    /**
     * Set the ID of the user
     *
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): Dialog
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the ID of the user
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the CONVERSATION_ID of the dialog
     *
     * @param int|null $cid
     * @return $this
     */
    public function setCId(?int $cid): Dialog
    {
        $this->c_id = $cid;

        return $this;
    }

    /**
     * Get the CONVERSATION_ID of the dialog
     *
     * @return int|null
     */
    public function getCId(): ?int
    {
        return $this->c_id;
    }

    /**
     * Set the CONVERSATION_REPLY_ID of the dialog
     *
     * @param int|null $crid
     * @return $this
     */
    public function setCrId(?int $crid): Dialog
    {
        $this->cr_id = $crid;

        return $this;
    }

    /**
     * Get the CONVERSATION_REPLY_ID of the dialog
     *
     * @return int|null
     */
    public function getCrId(): ?int
    {
        return $this->cr_id;
    }

    /**
     * Set the sent time of the dialog
     *
     * @param string|null $time
     * @return $this
     */
    public function setTime(?string $time): Dialog
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get the sent time of the message
     *
     * @return string|null
     */
    public function getTime(): ?string
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
     *
     * @param int|null $userone
     * @return $this
     */
    public function setUserOne(?int $userone): Dialog
    {
        $this->user_one = $userone;

        return $this;
    }

    /**
     * Get the USER_ONE of the dialog
     *
     * @return int|null
     */
    public function getUserOne(): ?int
    {
        return $this->user_one;
    }

    /**
     * Set the USER_TWO of the dialog
     *
     * @param int|null $usertwo
     * @return $this
     */
    public function setUserTwo(?int $usertwo): Dialog
    {
        $this->user_two = $usertwo;

        return $this;
    }

    /**
     * Get the USER_TWO of the dialog
     *
     * @return int|null
     */
    public function getUserTwo(): ?int
    {
        return $this->user_two;
    }

    /**
     * Set the TEXT of the dialog
     *
     * @param string|null $text
     * @return $this
     */
    public function setText(?string $text): Dialog
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the text of the dialog
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Get the avatar of the user.
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Set the avatar of the dialog
     *
     * @param string|null $avatar
     * @return $this
     */
    public function setAvatar(?string $avatar): Dialog
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Set the name of the user
     *
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): Dialog
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the name of the user
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the read status
     *
     * @param bool $read
     * @return $this
     */
    public function setRead(bool $read): Dialog
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get the read status
     *
     * @return bool
     */
    public function getRead(): bool
    {
        return $this->read;
    }
}
