<?php
/**
 * @copyright Ilch 2
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
    protected $id = 0;

    /**
     * The perma of the page.
     *
     * @var string
     */
    protected $perma = '';

    /**
     * The title of the page.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The content of the page.
     *
     * @var string
     */
    protected $content = '';

    /**
     * The description of the page.
     *
     * @var string
     */
    protected $description = '';

    /**
     * The keywords of the page.
     *
     * @var string
     */
    protected $keywords = '';

    /**
     * The locale of the page.
     *
     * @var string
     */
    protected $locale = '';

    /**
     * The datetime when the page got created.
     *
     * @var string
     */
    protected $dateCreated = '';

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray($entries): Page
    {
        if (isset($entries['page_id'])) {
            $this->setId($entries['page_id']);
        }
        if (isset($entries['description'])) {
            $this->setDescription($entries['description']);
        }
        if (isset($entries['keywords'])) {
            $this->setKeywords($entries['keywords']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['content'])) {
            $this->setContent($entries['content']);
        }
        if (isset($entries['perma'])) {
            $this->setPerma($entries['perma']);
        }
        if (isset($entries['locale'])) {
            $this->setLocale($entries['locale']);
        }
        if (isset($entries['date_created'])) {
            $this->setDateCreated($entries['date_created']);
        }

        return $this;
    }

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
     * @return $this
     */
    public function setId(int $id): Page
    {
        $this->id = $id;

        return $this;
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
     * @param string $perma
     * @return $this
     */
    public function setPerma(string $perma): Page
    {
        $this->perma = $perma;

        return $this;
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
     * @return $this
     */
    public function setTitle(string $title): Page
    {
        $this->title = $title;

        return $this;
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
     * @return $this
     */
    public function setContent(string $content): Page
    {
        $this->content = $content;

        return $this;
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
     * @return $this
     */
    public function setDescription(string $description): Page
    {
        $this->description = $description;

        return $this;
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
     * @return $this
     */
    public function setKeywords(string $keywords): Page
    {
        $this->keywords = $keywords;

        return $this;
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
     * @return $this
     */
    public function setLocale(string $locale): Page
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Gets the date_created timestamp of the page.
     *
     * @return string
     */
    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date_created date of the page.
     *
     * @param string $dateCreated
     * @return $this
     */
    public function setDateCreated(string $dateCreated): Page
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true)
    {
        return array_merge(
            ($withId ? ['page_id' => $this->getId()] : []),
            [
                'description' => $this->getDescription(),
                'keywords' => $this->getKeywords(),
                'title' => $this->getTitle(),
                'content' => $this->getContent(),
                'perma' => $this->getPerma(),
                'locale' => $this->getLocale(),
                'date_created' => $this->getDateCreated(),
            ]
        );
    }
}
