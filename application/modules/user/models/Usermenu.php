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
     * Icon of the menu.
     *
     * @var string
     */
    protected $icon;

    /**
     * Sets the menu id.
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
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
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = (string)$key;

        return $this;
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
     * Sets the menu icon.
     *
     * @param int $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = (string)$icon;

        return $this;
    }

    /**
     * Gets the menu icon.
     *
     * @return int
     */
    public function getIcon()
    {
        return $this->icon;
    }
}
