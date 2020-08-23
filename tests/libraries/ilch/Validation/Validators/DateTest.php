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
        $validator = new Date($data);
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
            'date correct' => [
                'data'                    => $this->createData('2020-08-01'),
                'expectedIsValid'         => true
            ],
            'string too short' => [
                'data'                    => $this->createData('abcd'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
//                'expectedErrorParameters' => [5]
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
    private function createData($value)
    {
        $data = new \stdClass();
        $data->field = 'fieldName';
        $data->parameters = [];
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
