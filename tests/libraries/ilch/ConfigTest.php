<?php
/**
 * Holds class Libraries_Ilch_ConfigTest.
 *
 * @author Meyer Dominik
 * @package ilch_phpunit
 */

/**
 * Tests the config object.
 *
 * @author Meyer Dominik
 * @package ilch_phpunit
 */
class Libraries_Ilch_ConfigTest extends PHPUnit_Ilch_TestCase
{
    /**
     * The object to test with.
     *
     * @var Ilch_Config
     */
    protected $_config;

    /**
     * Initializes an empty config object.
     */
    public function setUp()
    {
        $this->_config = new \Ilch\Config\File();
    }

    /**
     * Tests if a set config can be given back without a manipulation.
     */
    public function testSetAndGetConfig()
    {
        $this->_config->set('email', 'testuser@testmail.com');
        $this->assertEquals
        (
                'testuser@testmail.com',
                $this->_config->get('email'),
                'Config value got manipulated unexpectedly.'
        );
    }

    /**
     * Tests if a loaded config file is the same as the defined array.
     */
    public function testLoadConfigFromFile()
    {
        $configArray = array
        (
            'dbHost' => 'localhost',
            'dbUser' => 'root',
            'dbPassword' => ''
        );

        $this->_config->loadConfigFromFile(__DIR__.'/_files/config.php');

        $this->assertEquals
        (
                $configArray,
                array
                (
                    'dbHost' => $this->_config->get('dbHost'),
                    'dbUser' => $this->_config->get('dbUser'),
                    'dbPassword' => $this->_config->get('dbPassword')
                ),
                'Config array from file differs with defined array.'
        );
    }
}
