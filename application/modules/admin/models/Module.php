<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

/**
 * The module model class.
 */
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
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $name;

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
}
