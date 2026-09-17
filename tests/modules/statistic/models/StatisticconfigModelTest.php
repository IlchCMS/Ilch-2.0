<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Statistic\Models;

use PHPUnit\Framework\TestCase;

class StatisticconfigModelTest extends TestCase
{
    /**
     * Tests that setSiteStatistic() sets and returns the value.
     */
    public function testSetSiteStatistic()
    {
        $model = new Statisticconfig();
        $model->setSiteStatistic(false);

        self::assertFalse($model->getSiteStatistic());
    }

    /**
     * Tests that setIlchVersionStatistic() sets and returns the value.
     */
    public function testSetIlchVersionStatistic()
    {
        $model = new Statisticconfig();
        $model->setIlchVersionStatistic(false);

        self::assertFalse($model->getIlchVersionStatistic());
    }

    /**
     * Tests that setModulesStatistic() sets and returns the value.
     */
    public function testSetModulesStatistic()
    {
        $model = new Statisticconfig();
        $model->setModulesStatistic(false);

        self::assertFalse($model->getModulesStatistic());
    }

    /**
     * Tests that setVisitsStatistic() sets and returns the value.
     */
    public function testSetVisitsStatistic()
    {
        $model = new Statisticconfig();
        $model->setVisitsStatistic(false);

        self::assertFalse($model->getVisitsStatistic());
    }

    /**
     * Tests that setBrowserStatistic() sets and returns the value.
     */
    public function testSetBrowserStatistic()
    {
        $model = new Statisticconfig();
        $model->setBrowserStatistic(false);

        self::assertFalse($model->getBrowserStatistic());
    }

    /**
     * Tests that setOsStatistic() sets and returns the value.
     */
    public function testSetOsStatistic()
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
     * Tests setByArray() with a CSV string (simulating DB value).
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
     * Tests setByArray() with a CSV string containing zeros.
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
