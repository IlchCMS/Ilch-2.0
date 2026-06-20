<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the max validator
 */
class MaxTest extends TestCase
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
        $validator = new Max($data);
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
            // string validations
            'string correct'                    => [
                'data'                    => MaxTest::createData('abcde', 5),
                'expectedIsValid'         => true
            ],
            'string too short'                  => [
                'data'                    => MaxTest::createData('abcdef', 5),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.max.string',
                'expectedErrorParameters' => [5]
            ],
            'number string as string correct'   => [
                'data'                    => MaxTest::createData('12345', 5, true),
                'expectedIsValid'         => true
            ],
            'number string as string too short' => [
                'data'                    => MaxTest::createData('123456', 5, true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.max.string',
                'expectedErrorParameters' => [5]
            ],
            // numeric
            'number (int) correct'              => [
                'data'                    => MaxTest::createData(5, 5),
                'expectedIsValid'         => true
            ],
            'number string correct'             => [
                'data'                    => MaxTest::createData('5', 5),
                'expectedIsValid'         => true
            ],
            'number too low'                    => [
                'data'                    => MaxTest::createData('6', 5),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.max.numeric',
                'expectedErrorParameters' => [5]
            ],
            //array
            'array correct'                     => [
                'data'                    => MaxTest::createData([1, 2, 3], 3),
                'expectedIsValid'         => true
            ],
            'array too small'                   => [
                'data'                    => MaxTest::createData([1, 2, 3], 4),
                'expectedIsValid'         => true,
                'expectedErrorKey'        => 'validation.errors.max.array',
                'expectedErrorParameters' => [4]
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param mixed $value
     * @param int $max
     * @param bool $forceString
     * @return stdClass
     */
    private static function createData($value, int $max, bool $forceString = false): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [$max];
        if ($forceString) {
            $data->parameters[] = 'string';
        }
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
