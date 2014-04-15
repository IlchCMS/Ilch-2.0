<?php
/**
 * Holds class Admin_ModuleModel.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Models;
defined('ACCESS') or die('no direct access');

/**
 * The module model class.
 *
 * @package ilch
 */
class Module extends \Ilch\Model
{
    /**
     * Key of the module.
     *
     * @var string
     */
    protected $_key = '';

    /**
     * Small icon of the module.
     *
     * @var string
     */
    protected $_iconSmall;

    /**
     * @var boolean
     */
    protected $_systemModule = false;

    /**
     * Gets the key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * Sets the key.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->_key = (string) $key;
    }
    
    /**
     * Gets the author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * Sets the author.
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->_author = (string)$author;
    }
    
    /**
     * Gets the small icon.
     *
     * @return string
     */
    public function getIconSmall()
    {
        return $this->_iconSmall;
    }

    /**
     * Sets system module flag.
     *
     * @param boolean $system
     */
    public function setSystemModule($system)
    {
        $this->_systemModule = (bool)$system;
    }

    /**
     * Gets system module flag.
     *
     * @return boolean
     */
    public function getSystemModule()
    {
        return $this->_systemModule;
    }

    /**
     * Sets the small icon.
     *
     * @param string $icon
     */
    public function setIconSmall($icon)
    {
        $this->_iconSmall = (string) $icon;
    }

    /**
     * Add content for given language.
     *
     * @param string $langKey
     * @param string $content
     */
    public function addContent($langKey, $content)
    {
        $this->_content[$langKey] = $content;
    }

    /**
     * Gets content for given language.
     *
     * @return string|null
     */
    public function getContentForLocale($langKey)
    {
        if (!isset($this->_content[$langKey])) {
            return null;
        }

        return $this->_content[$langKey];
    }

    /**
     * Gets all content.
     *
     * @return array
     */
    public function getContent()
    {
        return $this->_content;
    }
}
