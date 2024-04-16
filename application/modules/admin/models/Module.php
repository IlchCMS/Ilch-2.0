<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Module extends \Ilch\Model
{
    protected $content = [];

    /**
     * Key of the module.
     *
     * @var string
     */
    protected $key = '';

    /**
     * Small icon of the module.
     *
     * @var string
     */
    protected $iconSmall = '';

    /**
     * @var boolean
     */
    protected $systemModule = false;

    /**
     * @var boolean
     */
    protected $layoutModule = false;

    /**
     * @var boolean
     */
    protected $hideMenu = false;

    /**
     * @var string
     */
    protected $author = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $version = '';

    /**
     * @var string
     */
    protected $link = '';

    /**
     * @var bool
     */
    protected $official = false;

    /**
     * @var string
     */
    protected $ilchCore = '';

    /**
     * @var string
     */
    protected $phpVersion = '';

    /**
     * @var string
     */
    protected $phpExtension = [];

    /**
     * @var array
     */
    protected $depends = [];

    /**
     * @var array
     */
    public $dependsCheck = [];

    /**
     * @param array $entries
     * @return $this
     * @since 2.1.60
     */
    public function setByArray(array $entries): Module
    {
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['key'])) {
            $this->setKey($entries['key']);
        }
        if (isset($entries['author'])) {
            $this->setAuthor($entries['author']);
        }
        if (isset($entries['languages'])) {
            foreach ($entries['languages'] as $key => $value) {
                $this->addContent($key, $value);
            }
        }
        if (isset($entries['system_module']) || isset($entries['system'])) {
            $this->setSystemModule($entries['system'] ?? true);
        }
        if (isset($entries['isLayout']) || isset($entries['layout'])) {
            $this->setLayoutModule($entries['layout'] ?? true);
        }
        if (isset($entries['hide_menu'])) {
            $this->setHideMenu(true);
        }
        if (isset($entries['official'])) {
            $this->setOfficial(true);
        }
        if (isset($entries['link'])) {
            $this->setLink($entries['link']);
        }
        if (isset($entries['version'])) {
            $this->setVersion($entries['version']);
        }
        if (isset($entries['icon_small'])) {
            $this->setIconSmall($entries['icon_small']);
        }
        if (isset($entries['ilchCore'])) {
            $this->setIlchCore($entries['ilchCore']);
        }
        if (isset($entries['phpVersion'])) {
            $this->setPHPVersion($entries['phpVersion']);
        }
        if (isset($entries['phpExtensions'])) {
            foreach ($entries['phpExtensions'] as $extension) {
                $this->addPHPExtension($extension);
            }
        }
        if (isset($entries['depends'])) {
            $this->setDepends($entries['depends']);
            foreach ($entries['phpExtensions'] as $depends => $value) {
                $this->dependsCheck[$depends] = false;
            }
        }

        return $this;
    }

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
     * @return Module
     */
    public function setKey(string $key): Module
    {
        $this->key = $key;
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
     * @return Module
     */
    public function setAuthor(string $author): Module
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Gets the small icon.
     *
     * @return string
     */
    public function getIconSmall(): string
    {
        return $this->iconSmall;
    }

    /**
     * Sets system module flag.
     *
     * @param bool $system
     * @return Module
     */
    public function setSystemModule(bool $system): Module
    {
        $this->systemModule = $system;
        return $this;
    }

    /**
     * Gets system module flag.
     *
     * @return bool
     */
    public function getSystemModule(): bool
    {
        return $this->systemModule;
    }

    /**
     * Sets layout module flag.
     *
     * @param bool $layout
     * @return Module
     */
    public function setLayoutModule(bool $layout): Module
    {
        $this->layoutModule = $layout;
        return $this;
    }

    /**
     * Gets layout module flag.
     *
     * @return bool
     */
    public function getLayoutModule(): bool
    {
        return $this->layoutModule;
    }

    /**
     * Sets hide in menu flag.
     *
     * @param bool $hideMenu
     * @return Module
     */
    public function setHideMenu(bool $hideMenu): Module
    {
        $this->hideMenu = $hideMenu;
        return $this;
    }

    /**
     * Gets hide in menu flag.
     *
     * @return boolean
     */
    public function getHideMenu(): bool
    {
        return $this->hideMenu;
    }

    /**
     * Sets the small icon.
     *
     * @param string $icon
     * @return Module
     */
    public function setIconSmall(string $icon): Module
    {
        $this->iconSmall = $icon;
        return $this;
    }

    /**
     * Add content for given language.
     *
     * @param string $langKey
     * @param array $content
     * @return Module
     */
    public function addContent(string $langKey, array $content): Module
    {
        $this->content[$langKey] = $content;
        return $this;
    }

    /**
     * Gets content for given language.
     *
     * @param string|null $langKey
     * @return array|null
     */
    public function getContentForLocale(?string $langKey): ?array
    {
        if (!isset($this->content[$langKey])) {
            return null;
        }

        return $this->content[$langKey];
    }

    /**
     * Gets all content.
     *
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
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
     * @return Module
     */
    public function setName(string $name): Module
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
     * @return Module
     */
    public function setVersion(string $version): Module
    {
        $this->version = $version;
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
     * @return Module
     */
    public function setLink(string $link): Module
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
     * @return Module
     */
    public function setOfficial(bool $official): Module
    {
        $this->official = $official;
        return $this;
    }

    /**
     * Gets the ilch core version.
     *
     * @return string
     */
    public function getIlchCore(): string
    {
        return $this->ilchCore;
    }

    /**
     * Sets the ilch core version.
     *
     * @param string $ilchCore
     * @return Module
     */
    public function setIlchCore(string $ilchCore): Module
    {
        $this->ilchCore = $ilchCore;
        return $this;
    }

    /**
     * Gets the php version.
     *
     * @return string
     */
    public function getPHPVersion(): string
    {
        return $this->phpVersion;
    }

    /**
     * Sets the php version.
     *
     * @param string $phpVersion
     * @return Module
     */
    public function setPHPVersion(string $phpVersion): Module
    {
        $this->phpVersion = $phpVersion;
        return $this;
    }

    /**
     * Gets the php extension.
     *
     * @return array
     */
    public function getPHPExtension()
    {
        return $this->phpExtension;
    }

    /**
     * Sets the php extension.
     *
     * @param array $phpExtension
     * @return Module
     */
    public function setPHPExtension(array $phpExtension): Module
    {
        $this->phpExtension = $phpExtension;
        return $this;
    }

    /**
     * add an php extension.
     *
     * @param string $phpExtension
     * @param bool $state
     * @return Module
     */
    public function addPHPExtension(string $phpExtension, bool $state = false): Module
    {
        $this->phpExtension[$phpExtension] = $state;
        return $this;
    }

    /**
     * Gets the dependencies.
     *
     * @return array
     */
    public function getDepends(): array
    {
        return $this->depends;
    }

    /**
     * Sets the dependencies.
     *
     * @param array $depends
     * @return Module
     */
    public function setDepends(array $depends): Module
    {
        $this->depends = $depends;
        return $this;
    }

    /**
     * @param array|null $dependencies
     * @return bool
     * @since 2.1.60
     */
    public function checkOwnDependencies(?array $dependencies = null): bool
    {
        $moduleMapper = new \Modules\Admin\Mappers\Module();
        $allependencies = true;
        foreach ($dependencies ?? $this->getDepends() as $key => $version) {
            $modul = $moduleMapper->getModuleByKey($key);
            if (!$modul) {
                $allependencies = false;
            } else {
                $parsed = explode(',', $version);
                if (!version_compare($modul->getVersion(), $parsed[1], $parsed[0])) {
                    $allependencies = false;
                } else {
                    $this->dependsCheck[$key] = true;
                }
            }
        }

        return $allependencies;
    }

    /**
     * @return bool
     * @since 2.1.60
     */
    public function hasPHPVersion(): bool
    {
        return version_compare(PHP_VERSION, $this->getPHPVersion(), '>=');
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

        return version_compare($coreVersion ?? VERSION, $this->getIlchCore(), '>=');
    }

    /**
     * @return bool
     * @since 2.1.60
     */
    public function hasPHPExtension(): bool
    {
        $allExtension = true;
        if (count($this->getPHPExtension())) {
            foreach ($this->getPHPExtension() as $extension => $state) {
                $hasExtension = extension_loaded($extension);
                $this->addPHPExtension($extension, $hasExtension);
                if (!$hasExtension) {
                    $allExtension = false;
                }
            }
        }
        return $allExtension;
    }

    /**
     * @param string|null $coreVersion
     * @return bool
     * @since 2.1.60
     */
    public function canExecute(?string $coreVersion = null): bool
    {
        return $this->checkOwnDependencies() && $this->hasPHPVersion() && $this->hasCoreVersion($coreVersion) && $this->hasPHPExtension();
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

    /**
     * Gets the Array of Model.
     *
     * @return array
     * @since 2.1.60
     */
    public function getArray(): array
    {
        return [
                'key' => $this->getKey(),
                'system' => $this->getSystemModule(),
                'layout' => $this->getLayoutModule(),
                'hide_menu' => $this->getHideMenu(),
                'icon_small' => $this->getIconSmall(),
                'version' => $this->getVersion(),
                'link' => $this->getLink(),
                'author' => $this->getAuthor()
            ];
    }
}
