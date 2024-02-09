<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Models;

use Ilch\Model;

class Newsletter extends Model
{
    /**
     * The id of the newsletter.
     *
     * @var int
     */
    protected $id;

    /**
     * The user of the newsletter.
     *
     * @var int
     */
    protected $userId;

    /**
     * The date of the newsletter.
     *
     * @var string
     */
    protected $dateCreated;

    /**
     * The subject of the newsletter.
     *
     * @var string
     */
    protected $subject;

    /**
     * The text of the newsletter.
     *
     * @var string
     */
    protected $text;

    /**
     * Gets the id of the newsletter.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the newsletter.
     *
     * @param int $id
     * @return Newsletter
     */
    public function setId(int $id): Newsletter
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the user of the newsletter.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the user of the newsletter.
     *
     * @param int $userId
     * @return Newsletter
     */
    public function setUserId(int $userId): Newsletter
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the date of the newsletter.
     *
     * @return string
     */
    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date of the newsletter.
     *
     * @param string $dateCreated
     * @return Newsletter
     */
    public function setDateCreated(string $dateCreated): Newsletter
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Gets the subject of the newsletter.
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Sets the subject of the newsletter.
     *
     * @param string $subject
     * @return Newsletter
     */
    public function setSubject(string $subject): Newsletter
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Gets the text of the newsletter.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the newsletter.
     *
     * @param string $text
     * @return Newsletter
     */
    public function setText(string $text): Newsletter
    {
        $this->text = $text;

        return $this;
    }
}
