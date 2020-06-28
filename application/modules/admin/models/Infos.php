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
     * The extension of the module.
     *
     * @var string
     */
    protected $extension;

    /**
     * Gets the key of the module.
     *
     * @return string
     */
    public function getKey(): string
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
     * @return string
     */
    public function getFolder(): string
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

    /**
     * Gets the extension of the module.
     *
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Sets the extension of the module.
     *
     * @param string $extension
     * @return this
     */
    public function setExtension($extension)
    {
        $this->extension = (string)$extension;

        return $this;
    }
}
