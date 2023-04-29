<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the numeric validator
 */
class NumericTest extends TestCase
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
        $validator = new Numeric($data);
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
            'valid numeric' => [
                'data'                    => $this->createData(1),
                'expectedIsValid'         => true
            ],
            'valid numeric string' => [
                'data'                    => $this->createData('1'),
                'expectedIsValid'         => true
            ],
            'valid numeric empty' => [
                'data'                    => $this->createData(''),
                'expectedIsValid'         => true
            ],
            'valid numeric float' => [
                'data'                    => $this->createData(1.5),
                'expectedIsValid'         => true
            ],
            'valid numeric float string' => [
                'data'                    => $this->createData('1.5'),
                'expectedIsValid'         => true
            ],
            'invalid numeric string' => [
                'data'                    => $this->createData('a'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.numeric.mustBeNumeric',
                'expectedErrorParameters' => []
            ],
            'valid numeric invert' => [
                'data'                    => $this->createData(1.5, true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.numeric.mustBeNumeric',
                'expectedErrorParameters' => []
            ],
            'invalid numeric invert' => [
                'data'                    => $this->createData('a', true),
                'expectedIsValid'         => true
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param string|int $value
     * @param bool $invertResult
     * @return stdClass
     */
    private function createData($value, bool $invertResult = false): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [''];
        $data->invertResult = $invertResult;
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
