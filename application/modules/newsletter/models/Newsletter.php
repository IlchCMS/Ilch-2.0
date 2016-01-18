<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Newsletter\Models;

class Newsletter extends \Ilch\Model
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
     * @var integer
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
     * The email of the newsletter.
     *
     * @var string
     */
    protected $email;

    /**
     * Newsletter user option
     *
     * @var string
     */
    protected $opt_newsletter;

    /**
     * Gets the id of the newsletter.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the newsletter.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the user of the newsletter.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the user of the newsletter.
     *
     * @param integer $userId
     * @return this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Gets the date of the newsletter.
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date of the newsletter.
     *
     * @param DateTime $date
     * @return this
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = (string)$dateCreated;

        return $this;
    }

    /**
     * Gets the subject of the newsletter.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets the subject of the newsletter.
     *
     * @param string $subject
     * @return this
     */
    public function setSubject($subject)
    {
        $this->subject = (string)$subject;

        return $this;
    }

    /**
     * Gets the text of the newsletter.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the newsletter.
     *
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }

    /**
     * Gets the email of the newsletter.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email of the newsletter.
     *
     * @param string $email
     * @return this
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;

        return $this;
    }

    /**
     * Returns the opt_newsletter of the user.
     *
     * @return int
     */
    public function getNewsletter()
    {
        return $this->opt_newsletter;
    }

    /**
     * Saves the opt_newsletter of the user.
     *
     * @param int $opt_newsletter
     * @return User
     */
    public function setNewsletter($opt_newsletter)
    {
        $this->opt_newsletter = (string)$opt_newsletter;

        return $this;
    }
}
