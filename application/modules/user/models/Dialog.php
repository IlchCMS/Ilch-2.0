<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

/**
 * Model for the dialog feature.
 */
class Dialog extends \Ilch\Model
{
    /**
     * ID of the dialog
     *
     * @var int
     */
    private $id;

    /**
     * C_ID of the dialog
     *
     * @var int
     */
    private $c_id;

    /**
     * CR_ID of the dialog
     *
     * @var int
     */
    private $cr_id;

    /**
     * The TEXT of the dialog
     *
     * @var string
     */
    private $text;

    /**
     * user_one of the dialog
     *
     * @var int
     */
    private $user_one;

    /**
     * user_two of the dialog
     *
     * @var int
     */
    private $user_two;

    /**
     * time when the message was sent (TIMESTAMP)
     *
     * @var string
     */
    private $time;

    /**
     * Indicates if conversation/dialog is hidden or not.
     *
     * @var bool
     */
    private $hidden;

    /**
     * Name of the user
     *
     * @var string
     */
    private $name;

    /**
     * read status
     *
     * @var bool
     */
    private $read;

    /**
     * avatar of the user.
     *
     * @var string
     */
    private $avatar;

    /**
     * Set the ID of the message
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Dialog
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the ID of the message
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
     * @param int $cid
     * @return $this
     */
    public function setCId(int $cid): Dialog
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
     * @param int $crid
     * @return $this
     */
    public function setCrId(int $crid): Dialog
    {
        $this->cr_id = $crid;

        return $this;
    }

    /**
     * Get the CONVERSATION_REPLY_ID of the dialog
     *
     * @return int
     */
    public function getCrId(): int
    {
        return $this->cr_id;
    }

    /**
     * Set the senttime of the dialog
     *
     * @param string $time
     * @return $this
     */
    public function setTime(string $time): Dialog
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get the senttime of the message
     *
     * @return string
     */
    public function getTime(): string
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
     * @param int $userone
     * @return $this
     */
    public function setUserOne(int $userone): Dialog
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
     * @param int $usertwo
     * @return $this
     */
    public function setUserTwo(int $usertwo): Dialog
    {
        $this->user_two = $usertwo;

        return $this;
    }

    /**
     * Get the USER_ONE of the dialog
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
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Dialog
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
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * Set the avatar of the dialog
     *
     * @param string $avatar
     * @return $this
     */
    public function setAvatar(string $avatar): Dialog
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Set the name of the user
     *
     * @param string $name
     * @return Dialog
     */
    public function setName(string $name): Dialog
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the name of the user
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the read
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
     * Get the read
     *
     * @return bool
     */
    public function getRead(): bool
    {
        return $this->read;
    }
}
