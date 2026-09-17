<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Models;

class Statisticconfig extends \Ilch\Model
{
    /**
     * @var array
     */
    public array $configNames = [
        'siteStatistic',
        'ilchVersionStatistic',
        'modulesStatistic',
        'visitsStatistic',
        'browserStatistic',
        'osStatistic',
    ];

    /**
     * @var bool
     */
    protected bool $siteStatistic = true;

    /**
     * @var bool
     */
    protected bool $ilchVersionStatistic = true;

    /**
     * @var bool
     */
    protected bool $modulesStatistic = true;

    /**
     * @var bool
     */
    protected bool $visitsStatistic = true;

    /**
     * @var bool
     */
    protected bool $browserStatistic = true;

    /**
     * @var bool
     */
    protected bool $osStatistic = true;

    /**
     * @param array|string|null $config
     * @return $this
     */
    public function setByArray(array|string|null $config = null): Statisticconfig
    {
        if (is_string($config)) {
            $config = explode(',', $config);
        } elseif ($config === null) {
            $ilchConfig = \Ilch\Registry::get('config');
            $config = explode(',', $ilchConfig ? $ilchConfig->get('statistic_visibleStats') : '');
        }

        foreach ($this->configNames as $key => $name) {
            $value = $config[$name] ?? ($config[$key] ?? true);
            $this->setConfigBy($name, $value);
        }

        return $this;
    }

    /**
     * @param Statisticconfig|null $config
     * @return string
     */
    public function getConfigString(?Statisticconfig $config = null): string
    {
        $config = $config ?? $this;

        $values = [];
        foreach ($this->configNames as $name) {
            $values[] = (int)$config->getConfigBy($name);
        }

        return implode(',', $values);
    }

    /**
     * @param int|string $key
     * @return bool
     */
    public function getConfigBy(int|string $key): bool
    {
        if (is_numeric($key)) {
            $key = $this->configNames[$key];
        }
        return match ($key) {
            'siteStatistic' => $this->getSiteStatistic(),
            'ilchVersionStatistic' => $this->getIlchVersionStatistic(),
            'modulesStatistic' => $this->getModulesStatistic(),
            'visitsStatistic' => $this->getVisitsStatistic(),
            'browserStatistic' => $this->getBrowserStatistic(),
            'osStatistic' => $this->getOsStatistic(),
            default => false,
        };
    }

    /**
     * @param int|string $key
     * @param bool $value
     * @return $this
     */
    public function setConfigBy(int|string $key, bool $value = false): Statisticconfig
    {
        if (is_numeric($key)) {
            $key = $this->configNames[$key];
        }
        switch ($key) {
            case 'siteStatistic':
                $this->setSiteStatistic($value);
                break;
            case 'ilchVersionStatistic':
                $this->setIlchVersionStatistic($value);
                break;
            case 'modulesStatistic':
                $this->setModulesStatistic($value);
                break;
            case 'visitsStatistic':
                $this->setVisitsStatistic($value);
                break;
            case 'browserStatistic':
                $this->setBrowserStatistic($value);
                break;
            case 'osStatistic':
                $this->setOsStatistic($value);
                break;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return !$this->getSiteStatistic() && !$this->getVisitsStatistic() && !$this->getBrowserStatistic() && !$this->getOsStatistic();
    }

    /**
     * @return bool
     */
    public function getSiteStatistic(): bool
    {
        return $this->siteStatistic;
    }

    /**
     * @param bool $siteStatistic
     * @return $this
     */
    public function setSiteStatistic(bool $siteStatistic): Statisticconfig
    {
        $this->siteStatistic = $siteStatistic;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIlchVersionStatistic(): bool
    {
        return $this->ilchVersionStatistic;
    }

    /**
     * @param bool $ilchVersionStatistic
     * @return $this
     */
    public function setIlchVersionStatistic(bool $ilchVersionStatistic): Statisticconfig
    {
        $this->ilchVersionStatistic = $ilchVersionStatistic;

        return $this;
    }

    /**
     * @return bool
     */
    public function getModulesStatistic(): bool
    {
        return $this->modulesStatistic;
    }

    /**
     * @param bool $modulesStatistic
     * @return $this
     */
    public function setModulesStatistic(bool $modulesStatistic): Statisticconfig
    {
        $this->modulesStatistic = $modulesStatistic;

        return $this;
    }

    /**
     * @return bool
     */
    public function getVisitsStatistic(): bool
    {
        return $this->visitsStatistic;
    }

    /**
     * @param bool $visitsStatistic
     * @return $this
     */
    public function setVisitsStatistic(bool $visitsStatistic): Statisticconfig
    {
        $this->visitsStatistic = $visitsStatistic;

        return $this;
    }

    /**
     * @return bool
     */
    public function getBrowserStatistic(): bool
    {
        return $this->browserStatistic;
    }

    /**
     * @param bool $browserStatistic
     * @return $this
     */
    public function setBrowserStatistic(bool $browserStatistic): Statisticconfig
    {
        $this->browserStatistic = $browserStatistic;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOsStatistic(): bool
    {
        return $this->osStatistic;
    }

    /**
     * @param bool $osStatistic
     * @return $this
     */
    public function setOsStatistic(bool $osStatistic): Statisticconfig
    {
        $this->osStatistic = $osStatistic;

        return $this;
    }
}
