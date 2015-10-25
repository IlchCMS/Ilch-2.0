<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

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
     * Name of the layout.
     *
     * @var string
     */
    protected $name;

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
     * @param string $key
     */
    public function setName($name)
    {
        $this->name = (string)$name;
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
