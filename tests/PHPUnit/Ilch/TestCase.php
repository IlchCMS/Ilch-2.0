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
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * A data array which will be used to create a config object for the registry.
     *
     * @var array
     */
    protected $configData = [];

    protected $tearDownCallbacks = [];

    /**
     * Filling the config object with individual testcase data.
     */
    protected function setUp(): void
    {
        TestHelper::setConfigInRegistry($this->configData);

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        foreach ($this->tearDownCallbacks as $callback) {
            if (\is_callable($callback)) {
                $callback();
            }
        }
        $this->tearDownCallbacks = [];
    }

    /**
     * Add a single run tearDown callback
     *
     * @param callable $callback
     */
    protected function addTearDownCallback(callable $callback)
    {
        $this->tearDownCallbacks[] = $callback;
    }
}
