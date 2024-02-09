<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Newsletter\Models;

use Ilch\Model;

class Subscriber extends Model
{
    /**
     * The id of the subscriber.
     *
     * @var int
     */
    protected $id;

    /**
     * The email of the subscriber.
     *
     * @var string
     */
    protected $email;

    /**
     * The selector of the subscription.
     *
     * @var string
     */
    protected $selector;

    /**
     * The confirmCode of the subscription.
     *
     * @var string
     */
    protected $confirmCode;

    /**
     * The date of the registration for the newsletter.
     * Is used to find old unconfirmed double opt-in entries.
     *
     * @var string
     */
    protected $doubleOptInDate;

    /**
     * Status of the double opt-in for the newsletter.
     *
     * @var bool
     */
    protected $doubleOptInConfirmed;

    /**
     * Newsletter user option
     *
     * @var bool
     */
    protected $opt_newsletter;

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
     * Sets the id of the subscriber.
     *
     * @param int $id
     * @return Subscriber
     */
    public function setId(int $id): Subscriber
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the email of the subscriber.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email of the subscriber.
     *
     * @param string $email
     * @return Subscriber
     */
    public function setEmail(string $email): Subscriber
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Gets the selector of the subscription.
     *
     * @return string
     */
    public function getSelector(): string
    {
        return $this->selector;
    }

    /**
     * Sets the selector of the subscription.
     *
     * @param string $selector
     * @return Subscriber
     */
    public function setSelector(string $selector): Subscriber
    {
        $this->selector = $selector;
        return $this;
    }

    /**
     * Gets the confirmCode of the subscription.
     *
     * @return string
     */
    public function getConfirmCode(): string
    {
        return $this->confirmCode;
    }

    /**
     * Sets the confirmCode of the subscription.
     *
     * @param string $confirmCode
     * @return Subscriber
     */
    public function setConfirmCode(string $confirmCode): Subscriber
    {
        $this->confirmCode = $confirmCode;
        return $this;
    }

    /**
     * Get the date of the registration for the newsletter.
     * Is used to find old unconfirmed double opt-in entries.
     *
     * @return string
     */
    public function getDoubleOptInDate(): string
    {
        return $this->doubleOptInDate;
    }

    /**
     * Set the date of the registration for the newsletter.
     * Is used to find old unconfirmed double opt-in entries.
     *
     * @param string $doubleOptInDate
     * @return Subscriber
     */
    public function setDoubleOptInDate(string $doubleOptInDate): Subscriber
    {
        $this->doubleOptInDate = $doubleOptInDate;
        return $this;
    }

    /**
     * Get the status of the double opt-in for the newsletter.
     *
     * @return bool
     */
    public function getDoubleOptInConfirmed(): bool
    {
        return $this->doubleOptInConfirmed;
    }

    /**
     * Set the status of the double opt-in for the newsletter.
     *
     * @param bool $doubleOptInConfirmed
     * @return Subscriber
     */
    public function setDoubleOptInConfirmed(bool $doubleOptInConfirmed): Subscriber
    {
        $this->doubleOptInConfirmed = $doubleOptInConfirmed;
        return $this;
    }

    /**
     * Returns the opt_newsletter of the user.
     *
     * @return bool
     */
    public function getNewsletter(): bool
    {
        return $this->opt_newsletter;
    }

    /**
     * Saves the opt_newsletter of the user.
     *
     * @param bool $opt_newsletter
     * @return Subscriber
     */
    public function setNewsletter(bool $opt_newsletter): Subscriber
    {
        $this->opt_newsletter = $opt_newsletter;
        return $this;
    }
}
