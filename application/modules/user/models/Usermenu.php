<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Usermenu extends \Ilch\Model
{
    /**
     * Id of the menu.
     *
     * @var integer
     */
    protected $id;

    /**
     * Key of the menu.
     *
     * @var string
     */
    protected $key;

    /**
     * Title of the menu.
     *
     * @var string
     */
    protected $title;

    /**
     * Text of the menu.
     *
     * @var string
     */
    protected $text;

    /**
     * Sets the menu id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Gets the menu id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

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
     * Sets the menu title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
    }

    /**
     * Gets the menu title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the menu text.
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = (string)$text;
    }

    /**
     * Gets the menu text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
