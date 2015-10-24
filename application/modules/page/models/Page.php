<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Page\Models;

/**
 * The page model class.
 *
 * @package ilch
 */
class Page extends \Ilch\Model
{
    /**
     * The id of the page.
     *
     * @var int
     */
    protected $id;

    /**
     * The perma of the page.
     *
     * @var string
     */
    protected $perma;

    /**
     * The title of the page.
     *
     * @var string
     */
    protected $title;

    /**
     * The content of the page.
     *
     * @var string
     */
    protected $content;

    /**
     * The description of the page.
     *
     * @var string
     */
    protected $description;

    /**
     * The locale of the page.
     *
     * @var string
     */
    protected $locale;

    /**
     * The datetime when the page got created.
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * Gets the id of the page.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the page.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the perma of the page.
     *
     * @return string
     */
    public function getPerma()
    {
        return $this->perma;
    }

    /**
     * Sets the perma of the page.
     *
     * @param int $perma
     */
    public function setPerma($perma)
    {
        $this->perma = $perma;
    }

    /**
     * Gets the page title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the page title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Gets the content of the page.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content of the page.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
    }

    /**
     * Gets the description of the page.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description of the page.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;
    }

    /**
     * Gets the locale of the page.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the locale of the page.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = (string) $locale;
    }

    /**
     * Gets the date_created timestamp of the page.
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date_created date of the page.
     *
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }
}
