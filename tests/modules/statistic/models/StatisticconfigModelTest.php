<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Statistic\Models;

use Ilch\Registry;
use PHPUnit\Framework\TestCase;

class StatisticconfigModelTest extends TestCase
{
    /**
     * Tests the list of known config names.
     */
    public function testConfigNames()
    {
        $model = new Statisticconfig();

        self::assertSame([
            'siteStatistic',
            'ilchVersionStatistic',
            'modulesStatistic',
            'visitsStatistic',
            'browserStatistic',
            'osStatistic',
        ], $model->configNames);
    }

    /**
     * Tests that default values are true for all statistics.
     */
    public function testDefaultValues()
    {
        $model = new Statisticconfig();

        self::assertTrue($model->getSiteStatistic());
        self::assertTrue($model->getIlchVersionStatistic());
        self::assertTrue($model->getModulesStatistic());
        self::assertTrue($model->getVisitsStatistic());
        self::assertTrue($model->getBrowserStatistic());
        self::assertTrue($model->getOsStatistic());
    }

    /**
     * Tests that setSiteStatistic()/getSiteStatistic() work.
     */
    public function testGetSetSiteStatistic()
    {
        $model = new Statisticconfig();
        $model->setSiteStatistic(false);

        self::assertFalse($model->getSiteStatistic());
    }

    /**
     * Tests that setIlchVersionStatistic()/getIlchVersionStatistic() work.
     */
    public function testGetSetIlchVersionStatistic()
    {
        $model = new Statisticconfig();
        $model->setIlchVersionStatistic(false);

        self::assertFalse($model->getIlchVersionStatistic());
    }

    /**
     * Tests that setModulesStatistic()/getModulesStatistic() work.
     */
    public function testGetSetModulesStatistic()
    {
        $model = new Statisticconfig();
        $model->setModulesStatistic(false);

        self::assertFalse($model->getModulesStatistic());
    }

    /**
     * Tests that setVisitsStatistic()/getVisitsStatistic() work.
     */
    public function testGetSetVisitsStatistic()
    {
        $model = new Statisticconfig();
        $model->setVisitsStatistic(false);

        self::assertFalse($model->getVisitsStatistic());
    }

    /**
     * Tests that setBrowserStatistic()/getBrowserStatistic() work.
     */
    public function testGetSetBrowserStatistic()
    {
        $model = new Statisticconfig();
        $model->setBrowserStatistic(false);

        self::assertFalse($model->getBrowserStatistic());
    }

    /**
     * Tests that setOsStatistic()/getOsStatistic() work.
     */
    public function testGetSetOsStatistic()
    {
        $model = new Statisticconfig();
        $model->setOsStatistic(false);

        self::assertFalse($model->getOsStatistic());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Statisticconfig();

        self::assertSame($model, $model->setSiteStatistic(true));
        self::assertSame($model, $model->setIlchVersionStatistic(true));
        self::assertSame($model, $model->setModulesStatistic(true));
        self::assertSame($model, $model->setVisitsStatistic(true));
        self::assertSame($model, $model->setBrowserStatistic(true));
        self::assertSame($model, $model->setOsStatistic(true));
    }

    /**
     * Tests that chaining setters builds a complete config.
     */
    public function testChainedSetters()
    {
        $model = (new Statisticconfig())
            ->setSiteStatistic(true)
            ->setIlchVersionStatistic(false)
            ->setModulesStatistic(true)
            ->setVisitsStatistic(false)
            ->setBrowserStatistic(true)
            ->setOsStatistic(false);

        self::assertTrue($model->getSiteStatistic());
        self::assertFalse($model->getIlchVersionStatistic());
        self::assertTrue($model->getModulesStatistic());
        self::assertFalse($model->getVisitsStatistic());
        self::assertTrue($model->getBrowserStatistic());
        self::assertFalse($model->getOsStatistic());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Statisticconfig();
        $model->setSiteStatistic(false)
            ->setBrowserStatistic(false);

        $model->setSiteStatistic(true)
            ->setBrowserStatistic(true);

        self::assertTrue($model->getSiteStatistic());
        self::assertTrue($model->getBrowserStatistic());
    }

    /**
     * Tests setByArray() with a numerically indexed array.
     */
    public function testSetByArrayWithArray()
    {
        $model = new Statisticconfig();
        $model->setByArray([1, 0, 1, 0, 1, 0]);

        self::assertTrue($model->getSiteStatistic());
        self::assertFalse($model->getIlchVersionStatistic());
        self::assertTrue($model->getModulesStatistic());
        self::assertFalse($model->getVisitsStatistic());
        self::assertTrue($model->getBrowserStatistic());
        self::assertFalse($model->getOsStatistic());
    }

    /**
     * Tests setByArray() with a comma separated string of ones.
     */
    public function testSetByArrayWithCsvString()
    {
        $model = new Statisticconfig();
        $model->setByArray('1,1,1,1,1,1');

        self::assertTrue($model->getSiteStatistic());
        self::assertTrue($model->getIlchVersionStatistic());
        self::assertTrue($model->getModulesStatistic());
        self::assertTrue($model->getVisitsStatistic());
        self::assertTrue($model->getBrowserStatistic());
        self::assertTrue($model->getOsStatistic());
    }

    /**
     * Tests setByArray() with a comma separated string of zeros.
     */
    public function testSetByArrayWithCsvStringZeros()
    {
        $model = new Statisticconfig();
        $model->setByArray('0,0,0,0,0,0');

        self::assertFalse($model->getSiteStatistic());
        self::assertFalse($model->getIlchVersionStatistic());
        self::assertFalse($model->getModulesStatistic());
        self::assertFalse($model->getVisitsStatistic());
        self::assertFalse($model->getBrowserStatistic());
        self::assertFalse($model->getOsStatistic());
    }

    /**
     * Tests setByArray() with named keys.
     */
    public function testSetByArrayWithNamedKeys()
    {
        $model = (new Statisticconfig())->setByArray(['siteStatistic' => false, 'osStatistic' => false]);

        self::assertFalse($model->getSiteStatistic());
        self::assertFalse($model->getOsStatistic());
        self::assertTrue($model->getIlchVersionStatistic());
        self::assertTrue($model->getModulesStatistic());
        self::assertTrue($model->getVisitsStatistic());
        self::assertTrue($model->getBrowserStatistic());
    }

    /**
     * Tests that missing keys keep their default (true).
     */
    public function testSetByArrayWithMissingKeysKeepDefaults()
    {
        $model = (new Statisticconfig())->setByArray(['visitsStatistic' => false]);

        self::assertTrue($model->getSiteStatistic());
        self::assertFalse($model->getVisitsStatistic());
        self::assertTrue($model->getOsStatistic());
    }

    /**
     * Tests that setByArray() is chainable (returns $this).
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new Statisticconfig();

        self::assertSame($model, $model->setByArray('1,1,1,1,1,1'));
    }

    public function testSetByArrayNullReadsRegistryConfig()
    {
        $registryConfig = new class {
            public function get(string $key)
            {
                if ($key === 'statistic_visibleStats') {
                    return '0,1,1,0,1,1';
                }

                return null;
            }
        };

        // 'config' may already be registered (bootstrap or an earlier
        // DatabaseTestCase test in the same process); Registry::set() throws then.
        $hadConfig = Registry::has('config');
        $previousConfig = $hadConfig ? Registry::get('config') : null;
        Registry::remove('config');

        try {
            Registry::set('config', $registryConfig);

            $model = (new Statisticconfig())->setByArray();

            self::assertFalse($model->getSiteStatistic());
            self::assertTrue($model->getIlchVersionStatistic());
            self::assertTrue($model->getModulesStatistic());
            self::assertFalse($model->getVisitsStatistic());
            self::assertTrue($model->getBrowserStatistic());
            self::assertTrue($model->getOsStatistic());
        } finally {
            Registry::remove('config');

            if ($hadConfig) {
                Registry::set('config', $previousConfig);
            }
        }
    }

    /**
     * Tests getConfigBy() with a string key.
     */
    public function testGetConfigByStringKey()
    {
        $model = new Statisticconfig();
        $model->setSiteStatistic(false);

        self::assertTrue($model->getConfigBy('ilchVersionStatistic'));
        self::assertFalse($model->getConfigBy('siteStatistic'));
    }

    /**
     * Tests getConfigBy() with an integer key.
     */
    public function testGetConfigByIntKey()
    {
        $model = new Statisticconfig();
        $model->setOsStatistic(false);

        self::assertFalse($model->getConfigBy(5));
        self::assertTrue($model->getConfigBy(0));
    }

    /**
     * Tests getConfigBy() returns false for unknown keys.
     */
    public function testGetConfigByUnknownKey()
    {
        $model = new Statisticconfig();

        self::assertFalse($model->getConfigBy('nonExistent'));
    }

    /**
     * Tests setConfigBy() with a string key.
     */
    public function testSetConfigByStringKey()
    {
        $model = new Statisticconfig();
        $model->setConfigBy('siteStatistic', false);

        self::assertFalse($model->getSiteStatistic());
        self::assertTrue($model->getIlchVersionStatistic());
    }

    /**
     * Tests setConfigBy() with an integer key.
     */
    public function testSetConfigByIntKey()
    {
        $model = new Statisticconfig();
        $model->setConfigBy(3, false);

        self::assertFalse($model->getVisitsStatistic());
        self::assertTrue($model->getSiteStatistic());
    }

    /**
     * Tests setConfigBy() returns $this for chaining.
     */
    public function testSetConfigByReturnsSelf()
    {
        $model = new Statisticconfig();

        self::assertSame($model, $model->setConfigBy('browserStatistic', false));
    }

    /**
     * Tests setConfigBy() ignores unknown keys.
     */
    public function testSetConfigByUnknownKeyIsIgnored()
    {
        $model = (new Statisticconfig())->setConfigBy('bogus', false);

        self::assertTrue($model->getSiteStatistic());
        self::assertTrue($model->getOsStatistic());
    }

    /**
     * Tests getConfigString() returns CSV of all config values.
     */
    public function testGetConfigString()
    {
        $model = new Statisticconfig();
        $model->setSiteStatistic(true)
            ->setIlchVersionStatistic(false)
            ->setModulesStatistic(true)
            ->setVisitsStatistic(false)
            ->setBrowserStatistic(true)
            ->setOsStatistic(false);

        self::assertSame('1,0,1,0,1,0', $model->getConfigString());
    }

    /**
     * Tests getConfigString() with all defaults (all true).
     */
    public function testGetConfigStringDefaults()
    {
        $model = new Statisticconfig();

        self::assertSame('1,1,1,1,1,1', $model->getConfigString());
    }

    /**
     * Tests getConfigString() round-trips values loaded via setByArray().
     */
    public function testGetConfigStringAfterSetByArray()
    {
        $model = (new Statisticconfig())->setByArray('1,0,1,1,0,1');

        self::assertSame('1,0,1,1,0,1', $model->getConfigString());
    }

    /**
     * Tests getConfigString() accepts another model instance.
     */
    public function testGetConfigStringAcceptsOtherModel()
    {
        $first = new Statisticconfig();
        $second = (new Statisticconfig())->setByArray('0,1,1,0,1,1');

        self::assertSame('0,1,1,0,1,1', $first->getConfigString($second));
    }

    /**
     * Tests isDisabled() returns true when all four main stats are false.
     */
    public function testIsDisabledAllFalse()
    {
        $model = new Statisticconfig();
        $model->setSiteStatistic(false)
            ->setVisitsStatistic(false)
            ->setBrowserStatistic(false)
            ->setOsStatistic(false);

        self::assertTrue($model->isDisabled());
    }

    /**
     * Tests isDisabled() returns false when at least one main stat is true.
     */
    public function testIsDisabledOneTrue()
    {
        $model = new Statisticconfig();
        $model->setSiteStatistic(true)
            ->setVisitsStatistic(false)
            ->setBrowserStatistic(false)
            ->setOsStatistic(false);

        self::assertFalse($model->isDisabled());
    }

    /**
     * Tests isDisabled() ignores ilchVersionStatistic and modulesStatistic.
     */
    public function testIsDisabledIgnoresVersionAndModules()
    {
        $model = new Statisticconfig();
        $model->setSiteStatistic(false)
            ->setIlchVersionStatistic(true)
            ->setModulesStatistic(true)
            ->setVisitsStatistic(false)
            ->setBrowserStatistic(false)
            ->setOsStatistic(false);

        self::assertTrue($model->isDisabled());
    }

    /**
     * Tests isDisabled() returns false with all defaults (all true).
     */
    public function testIsDisabledDefaultAllTrue()
    {
        $model = new Statisticconfig();

        self::assertFalse($model->isDisabled());
    }
}
