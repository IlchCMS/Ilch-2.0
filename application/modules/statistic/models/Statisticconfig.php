<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Models;

class StatistiCconfig extends \Ilch\Model
{
    /**
     * @var array
     */
    public $configNames = [
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
    protected $siteStatistic = true;

    /**
     * @var bool
     */
    protected $ilchVersionStatistic = true;

    /**
     * @var bool
     */
    protected $modulesStatistic = true;

    /**
     * @var bool
     */
    protected $visitsStatistic = true;

    /**
     * @var bool
     */
    protected $browserStatistic = true;

    /**
     * @var bool
     */
    protected $osStatistic = true;

    /**
     * @param string|array|null $config
     * @return $this
     */
    public function setByArray($config = null): Statisticconfig
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
        if ($config === null) {
            $config = [];
            foreach ($this->configNames as $name) {
                $config[] = $this->getConfigBy($name);
            }
        }

        return implode(',', $config);
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function getConfigBy($key): bool
    {
        if (is_numeric($key)) {
            $key = $this->configNames[$key];
        }
        switch ($key) {
            case 'siteStatistic':
                return $this->getSiteStatistic();
            case 'ilchVersionStatistic':
                return $this->getIlchVersionStatistic();
            case 'modulesStatistic':
                return $this->getModulesStatistic();
            case 'visitsStatistic':
                return $this->getVisitsStatistic();
            case 'browserStatistic':
                return $this->getBrowserStatistic();
            case 'osStatistic':
                return $this->getOsStatistic();
        }
        return false;
    }

    /**
     * @param string|int $key
     * @param bool $value
     * @return $this
     */
    public function setConfigBy($key, bool $value = false): Statisticconfig
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
