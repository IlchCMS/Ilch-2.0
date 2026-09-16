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

    public function tearDown(): void
    {
        parent::tearDown();

        // Clean up temp files/dirs created by tests
        foreach (['ilch_test_remove_dir', 'ilch_test_glob_dir', 'ilch_test_chmod_file'] as $name) {
            $path = buildPath(sys_get_temp_dir(), $name);
            if (is_dir($path)) {
                removeDir($path);
            } elseif (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Tests the buildPath function.
     *
     * @return void
     */
    public function testBuildPath()
    {
        self::assertSame('1' . DIRECTORY_SEPARATOR . '2', buildPath('1', '2'));
    }

    /**
     * Tests the relativePath function.
     *
     * @return void
     */
    public function testRelativePath()
    {
        self::assertSame(buildPath(__DIR__, 'test'), relativePath(buildPath(__DIR__, 'test')));
    }

    /**
     * Tests the array_dot function.
     *
     * @dataProvider dpForTestArrayDot
     * @return void
     */
    public function testArrayDot(array $params, $expected)
    {
        self::assertSame($expected, array_dot($params['data'], $params['key'], $params['default']));
    }

    /**
     * @return array
     */
    public static function dpForTestArrayDot(): array
    {
        return [
            'no key provided' => ['params' => ['data' => ['1', '2'], 'key' => null, 'default' => null], ['1', '2']],
            'numeric key, first entry' => ['params' => ['data' => ['1', '2'], 'key' => 0, 'default' => null], '1'],
            'numeric key, second entry' => ['params' => ['data' => ['1', '2'], 'key' => 1, 'default' => null], '2'],
            'invalid key' => ['params' => ['data' => ['1', '2'], 'key' => '1.0', 'default' => null], null],
            'invalid key, default' => ['params' => ['data' => ['1', '2'], 'key' => '1.0', 'default' => '3'], '3'],
        ];
    }

    /**
     * Tests the array_dot_set function.
     *
     * @dataProvider dpForTestArrayDotSet
     * @return void
     */
    public function testArrayDotSet(array $params, $expected)
    {
        self::assertSame($expected, array_dot_set($params['array'], $params['key'], $params['value']));
    }

    /**
     * @return array
     */
    public static function dpForTestArrayDotSet(): array
    {
        return [
            'test 1' => ['params' => ['array' => [], 'key' => 'test.value', 'value' => 'admin'], ['value' => 'admin']],
        ];
    }

    /**
     * Tests the is_in_array function.
     *
     * @dataProvider dpForTestIsInArray
     * @return void
     */
    public function testIsInArray(array $params, $expected)
    {
        self::assertSame($expected, is_in_array($params['needle'], $params['haystack']));
    }

    /**
     * @return array
     */
    public static function dpForTestIsInArray(): array
    {
        return [
            'is in array' => ['params' => ['needle' => ['test'], 'haystack' => ['test']], true],
            'not in array' => ['params' => ['needle' => ['NotExisting'], 'haystack' => ['test']], false],
        ];
    }

    /**
     * Tests the url_get_contents function.
     *
     * @dataProvider dpForTestUrlGetContents
     * @return void
     */
    public function testUrlGetContents(array $params, $expected)
    {
        self::assertSame($expected, url_get_contents($params['url']));
    }

    /**
     * @return array
     */
    public static function dpForTestUrlGetContents(): array
    {
        $testFile = buildPath(sys_get_temp_dir(), 'ilch_test_url_content.txt');
        $content = "/vendor" . "\n" . "/bin/*" . "\n";
        file_put_contents($testFile, $content);

        return [
            'valid url' => ['params' => ['url' => 'file://' . $testFile], $content],
            'invalid url' => ['params' => ['url' => ''], false],
        ];
    }

    /**
     * Tests the var_export_short_syntax function.
     *
     * @dataProvider dpForTestVarExportShortSyntax
     * @return void
     */
    public function testVarExportShortSyntax(array $params, $expected)
    {
        self::assertSame($expected, var_export_short_syntax($params['var'], $params['indent']));
    }

    /**
     * @return array
     */
    public static function dpForTestVarExportShortSyntax(): array
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
     * Tests the loggedIn function.
     *
     * @return void
     */
    public function testLoggedIn()
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
     * Tests the currentUser function.
     *
     * @return void
     */
    public function testCurrentUser()
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
    public static function dpForTestFormatBytes(): array
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

    /**
     * Tests the validateDate function.
     *
     * @dataProvider dpForTestValidateDate
     * @return void
     */
    public function testValidateDate(array $params, $expected)
    {
        self::assertSame($expected, validateDate($params['date'], $params['format']));
    }

    /**
     * @return array
     */
    public static function dpForTestValidateDate(): array
    {
        return [
            'invalid empty string' => ['params' => ['date' => '', 'format' => 'Y-m-d H:i:s'], false],
            'invalid null' => ['params' => ['date' => null, 'format' => 'Y-m-d H:i:s'], false],
            'invalid null bytes' => ['params' => ['date' => chr(0), 'format' => 'Y-m-d H:i:s'], false],
            'invalid null bytes beginning' => ['params' => ['date' => chr(0) . 'test', 'format' => 'Y-m-d H:i:s'], false],
            'invalid null bytes middle' => ['params' => ['date' => 'test' . chr(0) . 'test', 'format' => 'Y-m-d H:i:s'], false],
            'invalid null bytes end' => ['params' => ['date' => 'test' . chr(0), 'format' => 'Y-m-d H:i:s'], false],
            'invalid zero date' => ['params' => ['date' => '0000-00-00 00:00:00', 'format' => 'Y-m-d H:i:s'], false],
            'invalid date format' => ['params' => ['date' => '2024-10-27 04:42:04', 'format' => 'Y-m-d H:i'], false],
            'valid date' => ['params' => ['date' => '2024-10-27 04:42:04', 'format' => 'Y-m-d H:i:s'], true],
            'valid date without seconds' => ['params' => ['date' => '2024-10-27 04:42', 'format' => 'Y-m-d H:i'], true],
        ];
    }

    /**
     * Tests the generateUUID function.
     *
     * @return void
     */
    public function testGenerateUUID()
    {
        // With no argument – should generate a random v4 UUID
        $uuid = generateUUID();
        self::assertIsString($uuid);
        self::assertSame(36, strlen($uuid));
        self::assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $uuid,
            'UUID v4 format with version nibble 4 and variant prefix [89ab]'
        );

        // With explicit data – deterministic output
        $data = str_repeat("\x00", 16);
        $uuid2 = generateUUID($data);
        // Version nibble set: byte 6 → 0x40
        // Variant nibble set: byte 8 → 0x80
        $expected = '00000000-0000-4000-8000-000000000000';
        self::assertSame($expected, $uuid2);

        // Two random UUIDs should differ
        $a = generateUUID();
        $b = generateUUID();
        self::assertNotSame($a, $b);
    }

    /**
     * Tests the debug_backtrace_html function.
     *
     * @return void
     */
    public function testDebugBacktraceHtml()
    {
        $result = debug_backtrace_html(1);

        self::assertIsString($result);
        self::assertNotEmpty($result);

        // Each line should start with a tab and contain " @ " and " -- "
        $lines = explode("\r\n", trim($result, "\r\n"));
        foreach ($lines as $line) {
            self::assertStringStartsWith("\t@ ", $line);
            self::assertStringContainsString(' -- ', $line);
        }

        // The last entry in the backtrace (skipping 1) should reference
        // this test method or the FunctionsTest class.
        self::assertStringContainsString('testDebugBacktraceHtml', $result);
    }

    /**
     * Tests the removeDir function.
     *
     * @return void
     */
    public function testRemoveDir()
    {
        $base = buildPath(sys_get_temp_dir(), 'ilch_test_remove_dir');
        $sub  = buildPath($base, 'subdir');

        // Build a small tree: base/ -> subdir/ -> file.txt
        mkdir($base, 0755, true);
        mkdir($sub, 0755, true);
        file_put_contents(buildPath($sub, 'file.txt'), 'hello');

        self::assertTrue(is_dir($base));

        // Remove the whole tree
        self::assertTrue(removeDir($base));
        self::assertFalse(is_dir($base));
        self::assertFalse(file_exists(buildPath($base, 'subdir', 'file.txt')));

        // Non-existent path should return false
        self::assertFalse(removeDir(buildPath(sys_get_temp_dir(), 'ilch_nonexistent_dir_xyz')));

        // A single file (not a directory) should be unlinked
        $singleFile = buildPath(sys_get_temp_dir(), 'ilch_test_remove_dir_single.txt');
        file_put_contents($singleFile, 'data');
        self::assertTrue(removeDir($singleFile));
        self::assertFalse(file_exists($singleFile));
    }

    /**
     * Tests the glob_recursive function.
     *
     * @return void
     */
    public function testGlobRecursive()
    {
        $base = buildPath(sys_get_temp_dir(), 'ilch_test_glob_dir');

        // Build: base/a/one.txt, base/b/two.txt, base/top.txt
        mkdir(buildPath($base, 'a'), 0755, true);
        mkdir(buildPath($base, 'b'), 0755, true);
        file_put_contents(buildPath($base, 'a', 'one.txt'), 'a');
        file_put_contents(buildPath($base, 'b', 'two.txt'), 'b');
        file_put_contents(buildPath($base, 'top.txt'), 'c');

        // Match *.txt in the root only (non-recursive part)
        $result = glob_recursive(buildPath($base, '*.txt'));
        $names  = array_map('basename', $result);
        self::assertContains('top.txt', $names);

        // The recursive part should also find files in subdirectories
        // because glob_recursive merges results from subdirs.
        // Actually, looking at the implementation: it globs $path, then for
        // each subdirectory of dirname($path) it recursively globs
        // buildPath($dir, basename($path)). So calling with
        // buildPath($base, '*.txt') will also search in
        // base/a/*.txt and base/b/*.txt.
        self::assertContains('one.txt', $names);
        self::assertContains('two.txt', $names);

        // Clean up
        removeDir($base);
    }

    /**
     * Tests the setcookieIlch function.
     *
     * @return void
     */
    public function testSetcookieIlch()
    {
        // Basic call – should return true in CLI (setcookie is a no-op)
        self::assertTrue(setcookieIlch('testCookie', 'value', 3600));

        // With explicit params that include disallowed keys – should not crash
        $params = [
            'expires'  => 7200,
            'path'     => '/',
            'domain'   => 'example.com',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Lax',
            'foo'      => 'should-be-removed',
        ];
        self::assertTrue(setcookieIlch('filteredCookie', 'val', 0, $params));

        // Default params (null) should work (falls back to session_get_cookie_params)
        self::assertTrue(setcookieIlch('defaultCookie'));
    }

    /**
     * Creates a minimal config stub for Registry.
     *
     * @param string $value
     * @return object
     */
    private function makeConfigStub(string $value): object
    {
        return new class ($value) {
            private string $value;
            public function __construct(string $value)
            {
                $this->value = $value;
            }
            public function get(string $key)
            {
                return $this->value;
            }
        };
    }
}
