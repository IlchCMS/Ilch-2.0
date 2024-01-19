<?php

/**
 * @package ilch_phpunit
 */

namespace Ilch;

use Modules\User\Models\Group;
use Modules\User\Models\User as UserModel;
use PHPUnit\Ilch\TestCase;

/**
 * Tests the functions in the Functions class.
 *
 * @package ilch_phpunit
 */
class FunctionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', buildPath(__DIR__, '..', '..', '..'));
        }
    }

    /**
     * Tests the buildPath function.
     *
     * @return void
     */
    public function testbuildPath()
    {
        self::assertSame('1' . DIRECTORY_SEPARATOR . '2', buildPath('1', '2'));
    }

    /**
     * Tests the relativePath function.
     *
     * @return void
     */
    public function testrelativePath()
    {
        self::assertSame(buildPath(__DIR__, 'test'), relativePath(buildPath(__DIR__, 'test')));
    }

    /**
     * Tests the array_dot function.
     *
     * @dataProvider dpForTestarraydot
     * @return void
     */
    public function testarraydot(array $params, $expected)
    {
        self::assertSame($expected, array_dot($params['data'], $params['key'], $params['default']));
    }

    /**
     * @return array
     */
    public function dpForTestarraydot(): array
    {
        return [
            'test 1' => ['params' => ['data' => ['1', '2'], 'key' => null, 'default' => null], ['1', '2']],
            'test 2' => ['params' => ['data' => ['1', '2'], 'key' => 0, 'default' => null], '1'],
            'test 3' => ['params' => ['data' => ['1', '2'], 'key' => 1, 'default' => null], '2'],
            'test 4' => ['params' => ['data' => ['1', '2'], 'key' => '1.0', 'default' => null], null],
        ];
    }

    /**
     * Tests the array_dot_set function.
     *
     * @dataProvider dpForTestarraydotset
     * @return void
     */
    public function testarraydotset(array $params, $expected)
    {
        self::assertSame($expected, array_dot_set($params['array'], $params['key'], $params['value']));
    }

    /**
     * @return array
     */
    public function dpForTestarraydotset(): array
    {
        return [
            'test 1' => ['params' => ['array' => [], 'key' => 'test.value', 'value' => 'admin'], ['value' => 'admin']],
        ];
    }

    /**
     * Tests the is_in_array function.
     *
     * @dataProvider dpForTestisinarray
     * @return void
     */
    public function testisinarray(array $params, $expected)
    {
        self::assertSame($expected, is_in_array($params['needle'], $params['haystack']));
    }

    /**
     * @return array
     */
    public function dpForTestisinarray(): array
    {
        return [
            'test 1' => ['params' => ['needle' => ['test'], 'haystack' => ['test']], true],
        ];
    }

    /**
     * Tests the url_get_contents function.
     *
     * @return void
     */
    public function testurlgetcontents()
    {
        self::assertSame('beta', url_get_contents('https://www.ilch.de/ilch2_updates/beta/beta.txt'));
    }

    /**
     * Tests the formatBytes function.
     *
     * @dataProvider dpForTestvarexportshortsyntax
     * @return void
     */
    public function testvarexportshortsyntax(array $params, $expected)
    {
        self::assertSame($expected, var_export_short_syntax($params['var'], $params['indent']));
    }

    /**
     * @return array
     */
    public function dpForTestvarexportshortsyntax(): array
    {
        return [
            'string' => ['params' => ['var' => 'test', 'indent' => ''], '"test"'],
            'array' => ['params' => ['var' => ['test'], 'indent' => ''], '[' . PHP_EOL . '    "test"' . PHP_EOL . ']'],
            'booleantrue' => ['params' => ['var' => true, 'indent' => ''], 'TRUE'],
            'booleanfalse' => ['params' => ['var' => false, 'indent' => ''], 'FALSE'],
            'int' => ['params' => ['var' => 100, 'indent' => ''], '100'],
        ];
    }

    /**
     * Tests the url_get_contents function.
     *
     * @return void
     */
    public function testloggedIn()
    {
        if (!Registry::has('user')) {
            self::assertSame(false, loggedIn());

            $user = new UserModel();
            $group = new Group();

            $group->setId(3);
            $group->setName('Guest');

            $user->setId(0);
            $user->setName('user');
            $user->addGroup($group);
            Registry::set('user', $user);
        }
        self::assertSame(true, loggedIn());
    }

    /**
     * Tests the url_get_contents function.
     *
     * @return void
     */
    public function testcurrentUser()
    {
        if (!Registry::has('user')) {
            $this->testloggedIn();
        }

        $user = currentUser();

        if (loggedIn()) {
            self::assertEquals('user', $user->getName());
        }
    }

    /**
     * Tests the formatBytes function.
     *
     * @dataProvider dpForTestFormatBytes
     * @return void
     */
    public function testFormatBytes(array $params, $expected)
    {
        self::assertSame($expected, formatBytes($params['bytes'], $params['decimals']));
    }

    /**
     * @return array
     */
    public function dpForTestFormatBytes(): array
    {
        return [
            'invalid empty string' => ['params' => ['bytes' => '', 'decimals' => 0], false],
            'invalid negative string' => ['params' => ['bytes' => '-1', 'decimals' => 0], false],
            'invalid negative number' => ['params' => ['bytes' => -1, 'decimals' => 0], false],
            'invalid string not numeric' => ['params' => ['bytes' => 'a', 'decimals' => 0], false],
            'zero bytes' => ['params' => ['bytes' => 0, 'decimals' => 0], '0 B'],
            'bytes' => ['params' => ['bytes' => 100, 'decimals' => 0], '100 B'],
            'one kilobyte' => ['params' => ['bytes' => 1024, 'decimals' => 0], '1 KB'],
            'kilobytes rounded' => ['params' => ['bytes' => 1536, 'decimals' => 0], '2 KB'],
            'kilobytes' => ['params' => ['bytes' => 2048, 'decimals' => 0], '2 KB'],
            'kilobytes with one decimal' => ['params' => ['bytes' => 1536, 'decimals' => 1], '1.5 KB'],
            'megabytes' => ['params' => ['bytes' => 2097152, 'decimals' => 0], '2 MB'],
            'gigabytes' => ['params' => ['bytes' => 2147483648, 'decimals' => 0], '2 GB'],
            'terabytes' => ['params' => ['bytes' => 2199023255552, 'decimals' => 0], '2 TB'],
            'petabytes' => ['params' => ['bytes' => 2251799813685248, 'decimals' => 0], '2 PB'],
            'exabytes' => ['params' => ['bytes' => 2305843009213693952, 'decimals' => 0], '2 EB'],
            'zettabytes' => ['params' => ['bytes' => 2361183241434822606848, 'decimals' => 0], '2 ZB'],
            'yottabytes' => ['params' => ['bytes' => 2417851639229258349412352, 'decimals' => 0], '2 YB'],
        ];
    }
}
