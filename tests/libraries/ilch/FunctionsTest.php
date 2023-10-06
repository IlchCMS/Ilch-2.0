<?php
/**
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the functions in the Functions class.
 *
 * @package ilch_phpunit
 */
class FunctionsTest extends TestCase
{
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
