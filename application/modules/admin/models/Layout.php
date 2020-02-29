<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class Layout extends \Ilch\Model
{
    /**
     * Key of the layout.
     *
     * @var string
     */
    protected $key;

    /**
     * Name of the layout.
     *
     * @var string
     */
    protected $name;

    /**
     * Version of the layout.
     *
     * @var string
     */
    protected $version;

    /**
     * Author of the layout.
     *
     * @var string
     */
    protected $author;

    /**
     * Link of the layout.
     *
     * @var string
     */
    protected $link;

    /**
     * Ilch official label of the layout.
     *
     * @var bool
     */
    protected $official;

    /**
     * Description of the layout.
     *
     * @var string
     */
    protected $desc;

    /**
     * Module of the layout.
     *
     * @var string
     */
    protected $modulekey;

    /**
     * Settings of the layout.
     *
     * @var array
     */
    protected $settings;

    /**
     * Gets the key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the key.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = (string)$key;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }

    /**
     * Gets the version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the version.
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = (string)$version;
    }

    /**
     * Gets the author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the author.
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = (string)$author;
    }

    /**
     * Gets the link.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets the link.
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = (string)$link;
    }

    /**
     * Gets the official flag.
     *
     * @return bool
     */
    public function getOfficial()
    {
        return $this->official;
    }

    /**
     * Sets the official flag.
     *
     * @param bool $official
     */
    public function setOfficial($official)
    {
        $this->official = (bool)$official;
    }
    
    /**
     * Gets the desc.
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Sets the author.
     *
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = (string)$desc;
    }

    /**
     * Gets the modulekey.
     *
     * @return string
     */
    public function getModulekey()
    {
        return $this->modulekey;
    }

    /**
     * Sets the modulekey.
     *
     * @param string $modulekey
     */
    public function setModulekey($modulekey)
    {
        $this->modulekey = (string)$modulekey;
    }

    /**
     * Get the settings.
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set the settings.
     *
     * @param array $settings
     * @return Layout
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }
}
