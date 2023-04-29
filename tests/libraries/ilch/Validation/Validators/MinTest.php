<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the min validator
 */
class MinTest extends TestCase
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
        $validator = new Min($data);
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
    public function dpForTestValidator(): array
    {
        return [
            // string validations
            'string correct'              => [
                'data'                    => $this->createData('abcdef', 5),
                'expectedIsValid'         => true
            ],
            'string too short'            => [
                'data'                    => $this->createData('abcd', 5),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.min.string',
                'expectedErrorParameters' => [5]
            ],
            'number string as string correct' => [
                'data'                    => $this->createData('12345', 5, true),
                'expectedIsValid'         => true
            ],
            'number string as string too short' => [
                'data'                    => $this->createData('1234', 5, true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.min.string',
                'expectedErrorParameters' => [5]
            ],
            // numeric
            'number (int) correct'        => [
                'data'                    => $this->createData(5, 5),
                'expectedIsValid'         => true
            ],
            'number string correct'       => [
                'data'                    => $this->createData('5', 5),
                'expectedIsValid'         => true
            ],
            'number too low'              => [
                'data'                    => $this->createData('4', 5),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.min.numeric',
                'expectedErrorParameters' => [5]
            ],
            //array
            'array correct'               => [
                'data'                    => $this->createData([1, 2, 3], 3),
                'expectedIsValid'         => true
            ],
            'array bigger than needed'    => [
                'data'                    => $this->createData([1, 2, 3], 2),
                'expectedIsValid'         => true
            ],
            'array too small'             => [
                'data'                    => $this->createData([1, 2], 3),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.min.array',
                'expectedErrorParameters' => [3]
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param mixed $value
     * @param int $min
     * @param bool $forceString
     * @return stdClass
     */
    private function createData($value, int $min, bool $forceString = false): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [$min];
        if ($forceString) {
            $data->parameters[] = 'string';
        }
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
