<?php
/**
 * Holds class \PHPUnit\Ilch\TestCase
 *
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

/**
 * Base class for test cases for Ilch.
 *
 * @package ilch_phpunit
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * A data array which will be used to create a config object for the registry.
     *
     * @var Array
     */
    protected $configData = array();

    /**
     * Filling the config object with individual testcase data.
     */
    public function setUp()
    {
        $testHelper = new TestHelper();
        $testHelper->setConfigInRegistry($this->configData);

        parent::setUp();
    }
}
