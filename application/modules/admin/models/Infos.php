<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class Infos extends \Ilch\Model
{
    /**
     * The key of the module.
     *
     * @var string
     */
    protected $key;

    /**
     * The folder of the module.
     *
     * @var string
     */
    protected $folder;

    /**
     * Gets the key of the module.
     *
     * @return DateTime
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the key of the module.
     *
     * @param string $key
     * @return this
     */
    public function setKey($key)
    {
        $this->key = (string)$key;

        return $this;
    }

    /**
     * Gets the folder of the module.
     *
     * @return DateTime
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Sets the folder of the module.
     *
     * @param string $folder
     * @return this
     */
    public function setFolder($folder)
    {
        $this->folder = (string)$folder;

        return $this;
    }
}
