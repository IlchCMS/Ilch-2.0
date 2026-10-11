<?php

/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Usermenusettings extends \Ilch\Model
{
    /**
     * Key of the menu.
     *
     * @var string
     */
    protected $key;

    /**
     * Locale of the menu.
     *
     * @var string
     */
    protected $locale;

    /**
     * Description of the menu.
     *
     * @var string
     */
    protected $description;

    /**
     * Name of the menu.
     *
     * @var string
     */
    protected $name;

    /**
     * Icon of the menu.
     *
     * @var string
     */
    protected $icon;

    /**
     * Sets the menu key.
     *
     * @param string $key
     * @return Usermenusettings
     */
    public function setKey($key)
    {
        $this->key = (string)$key;

        return $this;
    }

    /**
     * Gets the menu key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the menu locale.
     *
     * @param string $locale
     * @return Usermenusettings
     */
    public function setLocale($locale)
    {
        $this->locale = (string)$locale;

        return $this;
    }

    /**
     * Gets the menu locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the menu description.
     *
     * @param string $description
     * @return Usermenusettings
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;

        return $this;
    }

    /**
     * Gets the menu description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the menu name.
     *
     * @param string $name
     * @return Usermenusettings
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the menu name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the menu icon.
     *
     * @param string $icon
     * @return Usermenusettings
     */
    public function setIcon($icon)
    {
        $this->icon = (string)$icon;

        return $this;
    }

    /**
     * Gets the menu icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
}
