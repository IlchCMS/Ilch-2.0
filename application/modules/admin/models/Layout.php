<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

defined('ACCESS') or die('no direct access');

/**
 * The layout model class.
 */
class Layout extends \Ilch\Model
{
    /**
     * Key of the layout.
     *
     * @var string
     */
    protected $key;

    /**
     * Author of the layout.
     *
     * @var string
     */
    protected $author;

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
}
