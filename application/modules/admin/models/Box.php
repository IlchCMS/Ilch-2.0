<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

/**
 * The box model class.
 */
class Box extends \Ilch\Model
{
    /**
     * The id of the box.
     *
     * @var int
     */
    protected $id;

    /**
     * The title of the box.
     *
     * @var string
     */
    protected $title;

    /**
     * The content of the box.
     *
     * @var string
     */
    protected $content;

    /**
     * The locale of the box.
     *
     * @var string
     */
    protected $locale;

    /**
     * The datetime when the box got created.
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * Gets the id of the box.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the box.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the box title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the box title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Gets the content of the box.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content of the box.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
    }

    /**
     * Gets the locale of the box.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the locale of the box.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = (string) $locale;
    }

    /**
     * Gets the date_created timestamp of the box.
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date_created date of the box.
     *
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }
}
