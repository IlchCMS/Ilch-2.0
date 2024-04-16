<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

use Modules\Admin\Mappers\Module as ModuleMapper;

class Layout extends \Ilch\Model
{
    /**
     * Key of the layout.
     *
     * @var string
     */
    protected $key = '';

    /**
     * Name of the layout.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Version of the layout.
     *
     * @var string
     */
    protected $version = '';

    /**
     * Author of the layout.
     *
     * @var string
     */
    protected $author = '';

    /**
     * Link of the layout.
     *
     * @var string
     */
    protected $link = '';

    /**
     * Ilch official label of the layout.
     *
     * @var bool
     */
    protected $official = false;

    /**
     * Description of the layout.
     *
     * @var string
     */
    protected $desc = '';

    /**
     * Module of the layout.
     *
     * @var string
     */
    protected $modulekey = '';

    /**
     * Module of the layout Checked.
     *
     * @var bool
     */
    public $hasModul = true;

    /**
     * Settings of the layout.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * @param array $entries
     * @return Layout
     * @since 2.1.60
     */
    public function setByArray(array $entries): Layout
    {
        if (isset($entries['key'])) {
            $this->setKey($entries['key']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['version'])) {
            $this->setVersion($entries['version']);
        }
        if (isset($entries['author'])) {
            $this->setAuthor($entries['author']);
        }
        if (!empty($entries['link'])) {
            $this->setLink($entries['link']);
        }
        if (isset($entries['desc'])) {
            $this->setDesc($entries['desc']);
        }
        if (isset($entries['modulekey'])) {
            $this->setModulekey($entries['modulekey'])
                ->hasModul();
        }
        if (isset($entries['settings'])) {
            $this->setSettings($entries['settings']);
        }
        if (isset($entries['ilchCore'])) {
            $this->setIlchCore($entries['ilchCore']);
        }

        return $this;
    }

    /**
     * The required ilch version if needed.
     *
     * @var string
     */
    protected $ilchCore = '';

    /**
     * Gets the key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Sets the key.
     *
     * @param string $key
     * @return Layout
     */
    public function setKey(string $key): Layout
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     * @return Layout
     */
    public function setName(string $name): Layout
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Sets the version.
     *
     * @param string $version
     * @return Layout
     */
    public function setVersion(string $version): Layout
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Gets the author.
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Sets the author.
     *
     * @param string $author
     * @return Layout
     */
    public function setAuthor(string $author): Layout
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Gets the link.
     *
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Sets the link.
     *
     * @param string $link
     * @return Layout
     */
    public function setLink(string $link): Layout
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Gets the official flag.
     *
     * @return bool
     */
    public function getOfficial(): bool
    {
        return $this->official;
    }

    /**
     * Sets the official flag.
     *
     * @param bool $official
     * @return Layout
     */
    public function setOfficial(bool $official): Layout
    {
        $this->official = $official;
        return $this;
    }

    /**
     * Gets the desc.
     *
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * Sets the author.
     *
     * @param string $desc
     * @return Layout
     */
    public function setDesc(string $desc): Layout
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * Gets the modulekey.
     *
     * @return string
     */
    public function getModulekey(): string
    {
        return $this->modulekey;
    }

    /**
     * Sets the modulekey.
     *
     * @param string $modulekey
     * @return Layout
     */
    public function setModulekey(string $modulekey): Layout
    {
        $this->modulekey = $modulekey;
        return $this;
    }

    /**
     * Sets the modulekey checked.
     *
     * @return Layout
     */
    public function hasModule(): Layout
    {
        if (!empty($this->getModulekey())) {
            $moduleMapper = new ModuleMapper();
            if ($moduleMapper->getModuleByKey($this->getModulekey()) === null) {
                $this->hasModul = false;
            }
        }
        return $this;
    }

    /**
     * Get the settings.
     *
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * Set the settings.
     *
     * @param array $settings
     * @return Layout
     */
    public function setSettings(array $settings): Layout
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Get the required ilch version if needed.
     *
     * @return string
     */
    public function getIlchCore(): string
    {
        return $this->ilchCore;
    }

    /**
     * Set the required ilch version if needed.
     *
     * @param string $ilchCore
     * @return Layout
     */
    public function setIlchCore(string $ilchCore): Layout
    {
        $this->ilchCore = $ilchCore;
        return $this;
    }

    /**
     * @param string|null $coreVersion
     * @return bool
     * @since 2.1.60
     */
    public function hasCoreVersion(?string $coreVersion = null): bool
    {
        if (version_compare($coreVersion ?? VERSION, '2.2', '>=')) { //BS5 Check
            if (version_compare($this->getIlchCore(), '2.2', '<')) {
                return false;
            }
        }

        if (empty($this->getIlchCore())) {
            return true;
        }

        return version_compare($coreVersion ?? VERSION, $this->getIlchCore(), '>=');
    }

    /**
     * @param string|null $coreVersion
     * @return bool
     * @since 2.1.60
     */
    public function canExecute(?string $coreVersion = null): bool
    {
        return $this->hasPHPVersion() && $this->hasCoreVersion($coreVersion);
    }

    /**
     * @param string|null $newVersion
     * @return bool
     * @since 2.1.60
     */
    public function isNewVersion(?string $newVersion = null): bool
    {
        if (empty($newVersion)) {
            return false;
        }
        return version_compare($this->getVersion(), $newVersion, '<');
    }
}
