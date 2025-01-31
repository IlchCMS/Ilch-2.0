<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Module extends \Ilch\Model
{
    /**
     * Language Content of the module.
     *
     * @var string|array
     */
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
     * @var bool
     */
    protected $systemModule = false;

    /**
     * @var bool
     */
    protected $layoutModule = false;

    /**
     * @var bool
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
     * @var bool
     */
    protected $ilchCore = false;

    /**
     * @var string
     */
    protected $phpVersion = '';

    /**
     * @var array
     */
    protected $phpExtension = [];

    /**
     * @var array
     */
    protected $depends = [];

    /**
     * @var array
     */
    protected $dependsCheck = [];

    /**
     * @var string
     * @since 2.2.8
     */
    protected $folderRight = '';

    /**
     * @param array $entries
     * @return $this
     * @since 2.2.8
     */
    public function setByArray(array $entries): Module
    {
        if (!empty($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (!empty($entries['key'])) {
            $this->setKey($entries['key']);
        }
        if (!empty($entries['author'])) {
            $this->setAuthor($entries['author']);
        }
        if (!empty($entries['languages'])) {
            foreach ($entries['languages'] as $key => $value) {
                $this->addContent($key, $value);
            }
        }
        if (!empty($entries['system_module']) || !empty($entries['system'])) {
            $this->setSystemModule($entries['system'] ?? true);
        }
        if (!empty($entries['isLayout']) || !empty($entries['layout'])) {
            $this->setLayoutModule($entries['layout'] ?? true);
        }
        if (!empty($entries['hide_menu'])) {
            $this->setHideMenu(true);
        }
        if (!empty($entries['official'])) {
            $this->setOfficial(true);
        }
        if (!empty($entries['link'])) {
            $this->setLink($entries['link']);
        }
        if (!empty($entries['version'])) {
            $this->setVersion($entries['version']);
        }
        if (!empty($entries['icon_small'])) {
            $this->setIconSmall($entries['icon_small']);
        }
        if (!empty($entries['ilchCore'])) {
            $this->setIlchCore($entries['ilchCore']);
        }
        if (!empty($entries['phpVersion'])) {
            $this->setPHPVersion($entries['phpVersion']);
        }
        if (!empty($entries['phpExtensions'])) {
            foreach ($entries['phpExtensions'] as $extension) {
                $this->addPHPExtension($extension);
            }
        }
        if (!empty($entries['depends'])) {
            $this->setDepends($entries['depends']);
            foreach ($entries['depends'] as $depend => $value) {
                $this->dependsCheck[$depend] = false;
            }
        }
        if (isset($entries['phpVersion'])) {
            $this->setPHPVersion($entries['phpVersion']);
        }
        if (isset($entries['folderRights'])) {
            $this->setFolderRight($entries['folderRights']);
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setHideMenu(bool $hideMenu): Module
    {
        $this->hideMenu = $hideMenu;
        return $this;
    }

    /**
     * Gets hide in menu flag.
     *
     * @return bool
     */
    public function getHideMenu(): bool
    {
        return $this->hideMenu;
    }

    /**
     * Sets the small icon.
     *
     * @param string $icon
     * @return $this
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
     * @param string|array $content
     * @return $this
     */
    public function addContent(string $langKey, $content): Module
    {
        $this->content[$langKey] = $content;
        return $this;
    }

    /**
     * Gets content for given language.
     *
     * @return string|null|array
     */
    public function getContentForLocale($langKey)
    {
        if (!isset($this->content[$langKey])) {
            return null;
        }

        return $this->content[$langKey];
    }

    /**
     * Gets all content.
     *
     * @return array|string
     */
    public function getContent()
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setOfficial(bool $official): Module
    {
        $this->official = $official;
        return $this;
    }

    /**
     * Gets the ilch core version.
     *
     * @return bool
     */
    public function getIlchCore(): bool
    {
        return $this->ilchCore;
    }

    /**
     * Sets the ilch core version.
     *
     * @param bool $ilchCore
     * @return $this
     */
    public function setIlchCore(bool $ilchCore): Module
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
     * @return $this
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
    public function getPHPExtension(): array
    {
        return $this->phpExtension;
    }

    /**
     * Sets the php extension.
     *
     * @param array $phpExtension
     * @return $this
     */
    public function setPHPExtension(array $phpExtension): Module
    {
        $this->phpExtension = $phpExtension;
        return $this;
    }

    /**
     * Sets the php extension.
     *
     * @param string $extension
     * @param bool $state
     * @return $this
     * @since 2.2.8
     */
    public function addPHPExtension(string $extension, bool $state = false): Module
    {
        $this->phpExtension[$extension] = $state;
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
     * @return $this
     */
    public function setDepends(array $depends): Module
    {
        $this->depends = $depends;
        return $this;
    }


    /**
     * Gets the dependencies.
     *
     * @return array
     * @since 2.2.8
     */
    public function getCheckDepends(): array
    {
        return $this->dependsCheck;
    }

    /**
     * Sets the dependencies.
     *
     * @param array $checkDepends
     * @return $this
     * @since 2.2.8
     */
    public function setCheckDepends(array $checkDepends): Module
    {
        $this->dependsCheck = $checkDepends;
        return $this;
    }

    /**
     * Sets the dependencies.
     *
     * @param string $key
     * @param bool $state
     * @return $this
     * @since 2.2.8
     */
    public function addCheckDepends(string $key, bool $state): Module
    {
        $this->dependsCheck[$key] = $state;
        return $this;
    }

    /**
     * Gets the folderRight.
     *
     * @return string
     * @since 2.2.8
     */
    public function getFolderRight(): string
    {
        return $this->folderRight;
    }

    /**
     * Sets the folderRight.
     *
     * @param string $folderRight
     * @return $this
     * @since 2.2.8
     */
    public function setFolderRight(string $folderRight): Module
    {
        $this->folderRight = $folderRight;
        return $this;
    }

    /**
     * @param array|null $dependencies
     * @return bool
     * @since 2.2.8
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
                    $this->addCheckDepends($key, true);
                }
            }
        }

        return $allependencies;
    }

    /**
     * @return bool
     * @since 2.2.8
     */
    public function hasPHPVersion(): bool
    {
        return version_compare(PHP_VERSION, $this->getPHPVersion(), '>=');
    }

    /**
     * @param string|null $coreVersion
     * @return bool
     * @since 2.2.8
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
     * @since 2.2.8
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
     * @since 2.2.8
     */
    public function canExecute(?string $coreVersion = null): bool
    {
        return $this->checkOwnDependencies() && $this->hasPHPVersion() && $this->hasCoreVersion($coreVersion) && $this->hasPHPExtension();
    }

    /**
     * @param string|null $newVersion
     * @return bool
     * @since 2.2.8
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
     * @since 2.2.8
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
