<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Infos extends \Ilch\Model
{
    /**
     * The key of the module.
     *
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * The folder of the module.
     *
     * @var string
     */
    protected string $folder = '';

    /**
     * The extension of the module.
     *
     * @var string
     */
    protected string $extension = '';

    /**
     * Gets the key of the module.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the key of the module.
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): Infos
    {
        $this->key = $key;

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
     * @return $this
     */
    public function setFolder(string $folder): Infos
    {
        $this->folder = $folder;

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
     * @return $this
     */
    public function setExtension(string $extension): Infos
    {
        $this->extension = $extension;

        return $this;
    }
}
