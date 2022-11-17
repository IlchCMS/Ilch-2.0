<?php
/**
 * @package ilch_phpunit
 */

namespace Ilch;

use Ilch\Config\File as Config;
use PHPUnit\Ilch\TestCase;

/**
 * Tests the config object.
 *
 * @package ilch_phpunit
 */
class ConfigTest extends TestCase
{
    /**
     * The object to test with.
     *
     * @var Config
     */
    protected $config;

    /**
     * Initializes an empty config object.
     */
    public function setUp(): void
    {
        $this->config = new Config();
    }

    /**
     * Tests if a set config can be given back without a manipulation.
     */
    public function testSetAndGetConfig()
    {
        $this->config->set('email', 'testuser@testmail.com');
        self::assertEquals(
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
        $configArray =
            [
            'dbHost'     => 'localhost',
            'dbUser'     => 'root',
            'dbPassword' => ''
            ];

        $this->config->loadConfigFromFile(__DIR__ . '/_files/config.php');

        self::assertEquals(
            $configArray,
            [
                'dbHost'     => $this->config->get('dbHost'),
                'dbUser'     => $this->config->get('dbUser'),
                'dbPassword' => $this->config->get('dbPassword')
            ],
            'Config array from file differs with defined array.'
        );
    }
}
