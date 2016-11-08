<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;

class MinTest extends TestCase
{
    /**
     * @dataProvider dpForTestValidator
     *
     * @param \stdClass $data
     * @param bool $expectedIsValid
     * @param string $expectedErrorKey
     * @param array $expectedErrorParameters
     */
    public function testValidator($data, $expectedIsValid, $expectedErrorKey = '', $expectedErrorParameters = [])
    {
        $validator = new Min($data);
        $validator->run();
        $this->assertSame($expectedIsValid, $validator->isValid());
        if (!empty($expectedErrorKey)) {
            $this->assertSame($expectedErrorKey, $validator->getErrorKey());
            $this->assertSame($expectedErrorParameters, $validator->getErrorParameters());
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidator()
    {
        return [
            // string validations
            'string correct'                    => [
                'data'                    => $this->createData('abcdef', 5),
                'expectedIsValid'         => true
            ],
            'string too short'                  => [
                'data'                    => $this->createData('abcd', 5),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.min.string',
                'expectedErrorParameters' => [5]
            ],
            'number string as string correct'   => [
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
            'number (int) correct'              => [
                'data'                    => $this->createData(5, 5),
                'expectedIsValid'         => true
            ],
            'number string correct'             => [
                'data'                    => $this->createData('5', 5),
                'expectedIsValid'         => true
            ],
            'number too low'                    => [
                'data'                    => $this->createData('4', 5),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.min.numeric',
                'expectedErrorParameters' => [5]
            ],
            //array
            'array correct'                     => [
                'data'                    => $this->createData([1, 2, 3], 3),
                'expectedIsValid'         => true
            ],
            'array too small'                   => [
                'data'                    => $this->createData([1, 2, 3], 2),
                'expectedIsValid'         => true,
                'expectedErrorKey'        => 'validation.errors.min.array',
                'expectedErrorParameters' => [2]
            ],
        ];
    }

    /**
     * Helper function for creating data object
     * @param string $value
     * @param int $min
     * @param bool $forceString
     * @return \stdClass
     */
    private function createData($value, $min, $forceString = false)
    {
        $data = new \stdClass();
        $data->field = 'fieldName';
        $data->parameters = [$min];
        if ($forceString) {
            $data->parameters[] = 'string';
        }
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
