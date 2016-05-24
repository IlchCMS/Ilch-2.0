<?php
/**
 * @package ilch_phpunit
 */

namespace PHPUnit\Ilch;

/**
 * Base class for test cases for Ilch.
 *
 * @package ilch_phpunit
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * A data array which will be used to create a config object for the registry.
     *
     * @var Array
     */
    protected $configData = [];

    /**
     * Filling the config object with individual testcase data.
     */
    protected function setUp()
    {
        TestHelper::setConfigInRegistry($this->configData);

        parent::setUp();
    }
}
