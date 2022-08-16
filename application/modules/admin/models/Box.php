<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Box extends \Ilch\Model
{
    /**
     * The id of the box.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The key of the box.
     *
     * @var string
     */
    protected $key = '';

    /**
     * The module of the box.
     *
     * @var string
     */
    protected $module = '';

    /**
     * The name of the box.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The title of the box.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The content of the box.
     *
     * @var array|string
     */
    protected $content = '';

    /**
     * The locale of the box.
     *
     * @var string
     */
    protected $locale = '';

    /**
     * The datetime when the box got created.
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
    public function setByArray($entries): Box
    {
        if (isset($entries['box_id'])) {
            $this->setId($entries['box_id']);
        }
        if (isset($entries['key'])) {
            $this->setKey($entries['key']);
        }
        if (isset($entries['module'])) {
            $this->setModule($entries['module']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['content'])) {
            $this->setContent($entries['content']);
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
     * Gets the id of the box.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the box.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Box
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the box key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Sets the box key.
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): Box
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the box module.
     *
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * Sets the box module.
     *
     * @param string $module
     * @return $this
     */
    public function setModule(string $module): Box
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Gets the box name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the box name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Box
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the box title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the box title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Box
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the content of the box.
     *
     * @return array|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content of the box.
     *
     * @param array|string $content
     * @return $this
     */
    public function setContent($content): Box
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Gets the locale of the box.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Sets the locale of the box.
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale(string $locale): Box
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Gets the date_created timestamp of the box.
     *
     * @return string
     */
    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date_created date of the box.
     *
     * @param string $dateCreated
     * @return $this
     */
    public function setDateCreated(string $dateCreated): Box
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Add content for given language.
     *
     * @param string $langKey
     * @param string|array $content
     */
    public function addContent(string $langKey, $content)
    {
        $this->content[$langKey] = $content;
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
            ($withId && $this->getId() ? ['box_id' => $this->getId()] : []),
            ($withId && $this->getKey() && $this->getModule() ? ['key' => $this->getKey(), 'module' => $this->getModule()] : []),
            ($this->getName() ? ['name' => $this->getName()] : []),
            ($this->getTitle() ? ['title' => $this->getTitle()] : []),
            ($this->getContent() ? ['content' => $this->getContent()] : []),
            ($this->getLocale() ? ['locale' => $this->getLocale()] : []),
            ($this->getDateCreated() ? ['date_created' => $this->getDateCreated()] : []),
        );
    }
}
