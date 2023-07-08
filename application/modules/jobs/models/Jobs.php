<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Jobs\Models;

/**
 * Job model
 */
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the jobs.
     *
     * @param int $id
     * @return Jobs
     */
    public function setId(int $id): Jobs
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the title of the jobs.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the jobs.
     *
     * @param string $title
     * @return Jobs
     */
    public function setTitle(string $title): Jobs
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the text of the jobs.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the jobs.
     *
     * @param string $text
     * @return Jobs
     */
    public function setText(string $text): Jobs
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the email of the jobs.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email of the jobs.
     *
     * @param string $email
     * @return Jobs
     */
    public function setEmail(string $email): Jobs
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets the show of the jobs.
     *
     * @return int
     */
    public function getShow(): int
    {
        return $this->show;
    }

    /**
     * Sets the show of the jobs.
     *
     * @param int $show
     * @return Jobs
     */
    public function setShow(int $show): Jobs
    {
        $this->show = $show;

        return $this;
    }
}
