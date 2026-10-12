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
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * Locale of the menu.
     *
     * @var string|null
     */
    protected ?string $locale = null;

    /**
     * Description of the menu.
     *
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * Name of the menu.
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * Icon of the menu.
     *
     * @var string|null
     */
    protected ?string $icon = null;

    /**
     * Sets the menu key.
     *
     * @param string $key
     * @return Usermenusettings
     */
    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Gets the menu key.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the menu locale.
     *
     * @param string $locale
     * @return Usermenusettings
     */
    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Gets the menu locale.
     *
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * Sets the menu description.
     *
     * @param string $description
     * @return Usermenusettings
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the menu description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the menu name.
     *
     * @param string $name
     * @return Usermenusettings
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the menu name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the menu icon.
     *
     * @param string $icon
     * @return Usermenusettings
     */
    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Gets the menu icon.
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }
}
