<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Clanlayout\Models;

class Layout extends \Ilch\Model
{
    /**
     * The key
     *
     * @var string
     */
    protected $key;

    /**
     * The value
     *
     * @var string
     */
    protected $value;

    /**
     * Gets the key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the key
     *
     * @param integer $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = (string)$key;

        return $this;
    }

    /**
     * Gets the value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = (string)$value;

        return $this;
    }
}
