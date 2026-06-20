<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the date validator
 */
class DateTest extends TestCase
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
        $validator = new Date($data);
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
            // date validations
            'date correct' => [
                'data'                    => DateTest::createData('2020-08-01'),
                'expectedIsValid'         => true
            ],
            'date wrong' => [
                'data'                    => DateTest::createData('2020-08-01 12:00:00'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d']
            ],
            // datetime validations
            'datetime correct' => [
                'data'                    => DateTest::createData('2020-08-01 12:00:00', 'Y-m-d H\\:i\\:s'),
                'expectedIsValid'         => true
            ],
            'datetime wrong' => [
                'data'                    => DateTest::createData('2020-08-01 24:00:00', 'Y-m-d H\\:i\\:s'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d H\\:i\\:s']
            ],
            // time validations
            'time correct' => [
                'data'                    => DateTest::createData('12:00:00', 'H\\:i\\:s'),
                'expectedIsValid'         => true
            ],
            'time wrong' => [
                'data'                    => DateTest::createData('24:00:00', 'H\\:i\\:s'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['H\\:i\\:s']
            ],
            // other
            'string too short' => [
                'data'                    => DateTest::createData('abcd'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d']
            ],
            'Null byte' => [
                'data'                    => DateTest::createData("\0"),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d']
            ],
            'Null' => [
                'data'                    => DateTest::createData(null),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d']
            ],
            'empty string' => [
                'data'                    => DateTest::createData(""),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d']
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param string|null $value
     * @param string $parameter
     * @return stdClass
     */
    private static function createData(?string $value, string $parameter = 'Y-m-d'): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [$parameter];
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
