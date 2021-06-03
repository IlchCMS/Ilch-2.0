<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;

class DateTest extends TestCase
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
        self::assertSame($expectedIsValid, $validator->isValid());
        if (!empty($expectedErrorKey)) {
            self::assertSame($expectedErrorKey, $validator->getErrorKey());
            self::assertSame($expectedErrorParameters, $validator->getErrorParameters());
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidator()
    {
        return [
            // date validations
            'date correct' => [
                'data'                    => $this->createData('2020-08-01'),
                'expectedIsValid'         => true
            ],
            'date wrong' => [
                'data'                    => $this->createData('2020-08-01 12:00:00'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d']
            ],
            // datetime validations
            'datetime correct' => [
                'data'                    => $this->createData('2020-08-01 12:00:00', 'Y-m-d H\\:i\\:s'),
                'expectedIsValid'         => true
            ],
            'datetime wrong' => [
                'data'                    => $this->createData('2020-08-01 24:00:00', 'Y-m-d H\\:i\\:s'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d H\\:i\\:s']
            ],
            // time validations
            'time correct' => [
                'data'                    => $this->createData('12:00:00', 'H\\:i\\:s'),
                'expectedIsValid'         => true
            ],
            'time wrong' => [
                'data'                    => $this->createData('24:00:00', 'H\\:i\\:s'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['H\\:i\\:s']
            ],
            // other
            'string too short' => [
                'data'                    => $this->createData('abcd'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.date.mustBeDate',
                'expectedErrorParameters' => ['Y-m-d']
            ],
        ];
    }

    /**
     * Helper function for creating data object
     * @param string $value
     * @param string $parameter
     * @return \stdClass
     */
    private function createData($value, $parameter = 'Y-m-d')
    {
        $data = new \stdClass();
        $data->field = 'fieldName';
        $data->parameters = [$parameter];
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
