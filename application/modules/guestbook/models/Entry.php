<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Models;

class Entry extends \Ilch\Model
{
    /**
     * The id of the entry.
     *
     * @var integer
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
     * @var integer
     */
    protected $setFree;

    /**
     * Gets the id of the entry.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the email of the entry.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Gets the text of the entry.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Gets the name of the entry.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the homepage of the entry.
     *
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Gets the datetime of the entry.
     *
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Gets the setfree of the entry.
     *
     * @return string
     */
    public function getFree()
    {
        return $this->setFree;
    }

    /**
     * Sets the id of the entry.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Sets the email.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;
    }

    /**
     * Sets the text of the entry.
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = (string)$text;
    }

    /**
     * Sets the name of the entry.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Sets the homepage of the entry.
     *
     * @param string $homepage
     */
    public function setHomepage($homepage)
    {
        $this->homepage = (string)$homepage;
    }

    /**
     * Sets the datetime of the entry.
     *
     * @param string $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = (string)$datetime;
    }

    /**
     * Sets the free of the entry.
     *
     * @param integer $free
     */
    public function setFree($free)
    {
        $this->setFree = (integer)$free;
    }
}
