<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class LayoutAdvSettings extends \Ilch\Model
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $layoutKey;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * Get the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id.
     * 
     * @param int $id
     * @return LayoutAdvSettings
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the layout key.
     *
     * @return string
     */
    public function getLayoutKey()
    {
        return $this->layoutKey;
    }

    /**
     * Set the layout key.
     *
     * @param string $layoutKey
     * @return LayoutAdvSettings
     */
    public function setLayoutKey($layoutKey)
    {
        $this->layoutKey = $layoutKey;
        return $this;
    }

    /**
     * Get the key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the key.
     *
     * @param string $key
     * @return LayoutAdvSettings
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Get the value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value.
     *
     * @param string $value
     * @return LayoutAdvSettings
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
