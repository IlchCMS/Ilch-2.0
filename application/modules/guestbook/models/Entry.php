<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Guestbook\Models;

class Entry extends \Ilch\Model
{
    /**
     * The id of the entry.
     *
     * @var int
     */
    protected $id;

    /**
     * The email of the entry.
     *
     * @var string
     */
    protected $email;

    /**
     * The text of the entry.
     *
     * @var string
     */
    protected $text;

    /**
     * The name of the entry.
     *
     * @var string
     */
    protected $name;

    /**
     * The homepage of the entry.
     *
     * @var string
     */
    protected $homepage;

    /**
     * The datetime of the entry.
     *
     * @var string
     */
    protected $datetime;

    /**
     * The setfee of the entry.
     *
     * @var int
     */
    protected $setFree;

    /**
     * Gets the id of the entry.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the email of the entry.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Gets the text of the entry.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Gets the name of the entry.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Gets the homepage of the entry.
     *
     * @return string|null
     */
    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    /**
     * Gets the datetime of the entry.
     *
     * @return string|null
     */
    public function getDatetime(): ?string
    {
        return $this->datetime;
    }

    /**
     * Gets the setfree of the entry.
     *
     * @return int
     */
    public function getFree(): int
    {
        return $this->setFree;
    }

    /**
     * Sets the id of the entry.
     *
     * @param int $id
     * @return Entry
     */
    public function setId(int $id): Entry
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the email.
     *
     * @param string $email
     * @return Entry
     */
    public function setEmail(string $email): Entry
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Sets the text of the entry.
     *
     * @param string $text
     * @return Entry
     */
    public function setText(string $text): Entry
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Sets the name of the entry.
     *
     * @param string $name
     * @return Entry
     */
    public function setName(string $name): Entry
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the homepage of the entry.
     *
     * @param string $homepage
     * @return Entry
     */
    public function setHomepage(string $homepage): Entry
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * Sets the datetime of the entry.
     *
     * @param string $datetime
     * @return Entry
     */
    public function setDatetime(string $datetime): Entry
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Sets the free of the entry.
     *
     * @param int $free
     * @return Entry
     */
    public function setFree(int $free): Entry
    {
        $this->setFree = $free;

        return $this;
    }
}
