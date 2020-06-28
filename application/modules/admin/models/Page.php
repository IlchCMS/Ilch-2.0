<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

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
     * The keywords of the page.
     *
     * @var string
     */
    protected $keywords;

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
    public function getId(): int
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
    public function getPerma(): string
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
    public function getTitle(): string
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
    public function getContent(): string
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
    public function getDescription(): string
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
     * Gets the keywords of the page.
     *
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * Sets the keywords of the page.
     *
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = (string)$keywords;
    }

    /**
     * Gets the locale of the page.
     *
     * @return string
     */
    public function getLocale(): string
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
    public function getDateCreated(): DateTime
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
