<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Layout extends \Ilch\Model
{
    /**
     * Key of the layout.
     */
    protected ?string $key = null;

    /**
     * Name of the layout.
     */
    protected ?string $name = null;

    /**
     * Version of the layout.
     */
    protected ?string $version = null;

    /**
     * Author of the layout.
     */
    protected ?string $author = null;

    /**
     * Link of the layout.
     */
    protected ?string $link = null;

    /**
     * Ilch official label of the layout.
     */
    protected ?bool $official = null;

    /**
     * Description of the layout.
     */
    protected ?string $desc = null;

    /**
     * Module of the layout.
     */
    protected ?string $modulekey = null;

    /**
     * Settings of the layout.
     */
    protected ?array $settings = null;

    /**
     * The required ilch version if needed.
     */
    protected ?string $ilchCore = null;

    /**
     * Gets the key.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the key.
     *
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Gets the name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets the version.
     *
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * Sets the version.
     *
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * Gets the author.
     *
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Sets the author.
     *
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * Gets the link.
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Sets the link.
     *
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * Gets the official flag.
     *
     * @return bool|null
     */
    public function getOfficial(): ?bool
    {
        return $this->official;
    }

    /**
     * Sets the official flag.
     *
     * @param bool $official
     */
    public function setOfficial(bool $official): void
    {
        $this->official = $official;
    }

    /**
     * Gets the desc.
     *
     * @return string|null
     */
    public function getDesc(): ?string
    {
        return $this->desc;
    }

    /**
     * Sets the desc.
     *
     * @param string $desc
     */
    public function setDesc(string $desc): void
    {
        $this->desc = $desc;
    }

    /**
     * Gets the modulekey.
     *
     * @return string|null
     */
    public function getModulekey(): ?string
    {
        return $this->modulekey;
    }

    /**
     * Sets the modulekey.
     *
     * @param string $modulekey
     */
    public function setModulekey(string $modulekey): void
    {
        $this->modulekey = $modulekey;
    }

    /**
     * Get the settings.
     *
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * Set the settings.
     *
     * @param array $settings
     * @return $this
     */
    public function setSettings(array $settings): Layout
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get the required ilch version if needed.
     *
     * @return string|null
     */
    public function getIlchCore(): ?string
    {
        return $this->ilchCore;
    }

    /**
     * Set the required ilch version if needed.
     *
     * @param string $ilchCore
     * @return $this
     */
    public function setIlchCore(string $ilchCore): Layout
    {
        $this->ilchCore = $ilchCore;

        return $this;
    }
}
