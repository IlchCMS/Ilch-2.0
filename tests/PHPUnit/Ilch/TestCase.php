<?php
/**
 * Holds class PHPUnit_Ilch_TestCase.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */

/**
 * Base class for test cases for Ilch.
 *
 * @author Jainta Martin
 * @package ilch_phpunit
 */
class PHPUnit_Ilch_TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * A data array which will be used to create a config object for the registry.
     *
     * @var Array
     */
    protected $_configData = array();

    /**
     * Filling the config object with individual testcase data.
     */
    public function setUp()
    {
    	$testHelper = new PHPUnit_Ilch_TestHelper();
        $testHelper->setConfigInRegistry($this->_configData);

        parent::setUp();
    }
}
