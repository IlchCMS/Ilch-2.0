<?php
/**
 * Holds class Libraries_Ilch_ConfigTest.
 *
 * @package ilch_phpunit
 */

/**
 * Tests the config object.
 *
 * @package ilch_phpunit
 */
class Libraries_Ilch_ConfigTest extends PHPUnit_Ilch_TestCase
{
    /**
     * The object to test with.
     *
     * @var Ilch_Config
     */
    protected $config;

    /**
     * Initializes an empty config object.
     */
    public function setUp()
    {
        $this->config = new \Ilch\Config\File();
    }

    /**
     * Tests if a set config can be given back without a manipulation.
     */
    public function testSetAndGetConfig()
    {
        $this->config->set('email', 'testuser@testmail.com');
        $this->assertEquals
        (
                'testuser@testmail.com',
                $this->config->get('email'),
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

        $this->config->loadConfigFromFile(__DIR__.'/_files/config.php');

        $this->assertEquals
        (
                $configArray,
                array
                (
                    'dbHost' => $this->config->get('dbHost'),
                    'dbUser' => $this->config->get('dbUser'),
                    'dbPassword' => $this->config->get('dbPassword')
                ),
                'Config array from file differs with defined array.'
        );
    }
}
