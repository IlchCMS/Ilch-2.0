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
     * @param int $key
     */
    public function setKey($key)
    {
        $this->key = (string)$key;
    }

    /**
     * Gets the menu key.
     *
     * @return int
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the menu locale.
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = (string)$locale;
    }

    /**
     * Gets the menu locale.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Sets the menu description.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;
    }

    /**
     * Gets the menu description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the menu name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Gets the menu name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the menu icon.
     *
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = (string)$icon;
    }

    /**
     * Gets the menu icon.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }
}
