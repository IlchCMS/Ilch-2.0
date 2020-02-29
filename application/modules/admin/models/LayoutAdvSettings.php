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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return LayoutAdvSettings
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayoutKey()
    {
        return $this->layoutKey;
    }

    /**
     * @param string $layoutKey
     * @return LayoutAdvSettings
     */
    public function setLayoutKey($layoutKey)
    {
        $this->layoutKey = $layoutKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return LayoutAdvSettings
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return LayoutAdvSettings
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
