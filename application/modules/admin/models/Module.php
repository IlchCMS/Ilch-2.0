<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class Module extends \Ilch\Model
{
    /**
     * Key of the module.
     *
     * @var string
     */
    protected $key = '';

    /**
     * Small icon of the module.
     *
     * @var string
     */
    protected $iconSmall;

    /**
     * @var boolean
     */
    protected $systemModule = false;

    /**
     * @var boolean
     */
    protected $layoutModule = false;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $ilchCore;

    /**
     * @var string
     */
    protected $phpVersion;

    /**
     * @var string
     */
    protected $phpExtension;

    /**
     * @var string
     */
    protected $depends;

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
        $this->key = (string) $key;
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
     * Gets the small icon.
     *
     * @return string
     */
    public function getIconSmall()
    {
        return $this->iconSmall;
    }

    /**
     * Sets system module flag.
     *
     * @param boolean $system
     */
    public function setSystemModule($system)
    {
        $this->systemModule = (bool)$system;
    }

    /**
     * Gets system module flag.
     *
     * @return boolean
     */
    public function getSystemModule()
    {
        return $this->systemModule;
    }

    /**
     * Sets layout module flag.
     *
     * @param boolean $layout
     */
    public function setLayoutModule($layout)
    {
        $this->layoutModule = (bool)$layout;
    }

    /**
     * Gets layout module flag.
     *
     * @return boolean
     */
    public function getLayoutModule()
    {
        return $this->layoutModule;
    }

    /**
     * Sets the small icon.
     *
     * @param string $icon
     */
    public function setIconSmall($icon)
    {
        $this->iconSmall = (string) $icon;
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

    /**
     * Gets content for given language.
     *
     * @return string|null
     */
    public function getContentForLocale($langKey)
    {
        if (!isset($this->content[$langKey])) {
            return null;
        }

        return $this->content[$langKey];
    }

    /**
     * Gets all content.
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
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
     * Gets the ilch core version.
     *
     * @return string
     */
    public function getIlchCore()
    {
        return $this->ilchCore;
    }

    /**
     * Sets the ilch core version.
     *
     * @param string $ilchCore
     */
    public function setIlchCore($ilchCore)
    {
        $this->ilchCore = $ilchCore;
    }

    /**
     * Gets the php version.
     *
     * @return string
     */
    public function getPHPVersion()
    {
        return $this->phpVersion;
    }

    /**
     * Sets the php version.
     *
     * @param string $phpVersion
     */
    public function setPHPVersion($phpVersion)
    {
        $this->phpVersion = $phpVersion;
    }

    /**
     * Gets the php extension.
     *
     * @return array
     */
    public function getPHPExtension()
    {
        return $this->phpExtension;
    }

    /**
     * Sets the php extension.
     *
     * @param array $phpExtension
     */
    public function setPHPExtension($phpExtension)
    {
        $this->phpExtension = $phpExtension;
    }

    /**
     * Gets the dependencies.
     *
     * @return array
     */
    public function getDepends()
    {
        return $this->depends;
    }

    /**
     * Sets the dependencies.
     *
     * @param array $depends
     */
    public function setDepends($depends)
    {
        $this->depends = $depends;
    }
}
