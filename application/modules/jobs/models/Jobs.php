<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Jobs\Models;

class Jobs extends \Ilch\Model
{
    /**
     * The id of the jobs.
     *
     * @var int
     */
    protected $id;

    /**
     * The title of the jobs.
     *
     * @var string
     */
    protected $title;

    /**
     * The text of the jobs.
     *
     * @var string
     */
    protected $text;

    /**
     * The email of the jobs.
     *
     * @var string
     */
    protected $email;

    /**
     * The show of the jobs.
     *
     * @var int
     */
    protected $show;

    /**
     * Gets the id of the jobs.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the jobs.
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
     * Gets the title of the jobs.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the jobs.
     *
     * @param string $title
     * @return this
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;

        return $this;
    }

    /**
     * Gets the text of the jobs.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the jobs.
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
     * Gets the email of the jobs.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email of the jobs.
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
     * Gets the show of the jobs.
     *
     * @return int
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Sets the show of the jobs.
     *
     * @param int $show
     * @return this
     */
    public function setShow($show)
    {
        $this->show = (int)$show;

        return $this;
    }
}
