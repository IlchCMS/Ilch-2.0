<?php
/**
 * @copyright Ilch 2.0
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
    protected $id;

    /**
     * The key of the box.
     *
     * @var string
     */
    protected $key;

    /**
     * The module of the box.
     *
     * @var string
     */
    protected $module;

    /**
     * The name of the box.
     *
     * @var string
     */
    protected $name;

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
     * Gets the box key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the box key.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = (string) $key;
    }

    /**
     * Gets the box module.
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Sets the box module.
     *
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = (string) $module;
    }

    /**
     * Gets the box name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the box name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
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

    /**
     * Add content for given language.
     *
     * @param string $langKey
     * @param string $content
     */
    public function addContent($langKey, $content)
    {
        $this->content[$langKey] = $content;
    }
}
