<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the url validator
 */
class UrlTest extends TestCase
{
    /**
     * @dataProvider dpForTestValidator
     *
     * @param stdClass $data
     * @param bool $expectedIsValid
     * @param string $expectedErrorKey
     * @param array $expectedErrorParameters
     */
    public function testValidator(stdClass $data, bool $expectedIsValid, string $expectedErrorKey = '', array $expectedErrorParameters = [])
    {
        $validator = new Url($data);
        $validator->run();
        self::assertSame($expectedIsValid, $validator->isValid());
        if (!empty($expectedErrorKey)) {
            self::assertSame($expectedErrorKey, $validator->getErrorKey());
            self::assertSame($expectedErrorParameters, $validator->getErrorParameters());
        }
    }

    /**
     * @return array
     */
    public static function dpForTestValidator(): array
    {
        return [
            'valid url domain with subdomain and protocol' => [
                'data'                    => UrlTest::createData('https://www.ilch.de'),
                'expectedIsValid'         => true
            ],
            'valid url domain with subdomain and ftp protocol' => [
                'data'                    => UrlTest::createData('ftp://www.ilch.de'),
                'expectedIsValid'         => true
            ],
            'invalid url domain with subdomain and invalid protocol' => [
                'data'                    => UrlTest::createData('invalid://www.ilch.de'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.url.noValidUrl',
                'expectedErrorParameters' => []
            ],
            'invalid url no protocol' => [
                'data'                    => UrlTest::createData('ilch.de'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.url.noValidUrl',
                'expectedErrorParameters' => []
            ],
            'invalid url domain with subdomain no protocol' => [
                'data'                    => UrlTest::createData('www.ilch.de'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.url.noValidUrl',
                'expectedErrorParameters' => []
            ],
            'invalid url no domain' => [
                'data'                    => UrlTest::createData('ilch'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.url.noValidUrl',
                'expectedErrorParameters' => []
            ],
            'empty string' => [
                'data'                    => UrlTest::createData(''),
                'expectedIsValid'         => true
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param string $value
     * @return stdClass
     */
    private static function createData(string $value): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [''];
        $data->input = ['fieldName' => $value];
        $_SERVER['HTTP_HOST'] = '127.0.0.1';
        return $data;
    }
}
