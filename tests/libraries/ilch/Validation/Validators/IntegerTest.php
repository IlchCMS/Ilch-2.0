<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the integer validator
 */
class IntegerTest extends TestCase
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
        $validator = new Integer($data);
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
            'valid integer' => [
                'data'                    => $this->createData(1),
                'expectedIsValid'         => true
            ],
            'valid integer string' => [
                'data'                    => $this->createData('1'),
                'expectedIsValid'         => true
            ],
            'valid integer empty' => [
                'data'                    => $this->createData(''),
                'expectedIsValid'         => true
            ],
            'invalid integer' => [
                'data'                    => $this->createData(1.5),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.integer.mustBeInteger',
                'expectedErrorParameters' => []
            ],
            'invalid integer string' => [
                'data'                    => $this->createData('1.5'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.integer.mustBeInteger',
                'expectedErrorParameters' => []
            ],
            'invalid integer invert' => [
                'data'                    => $this->createData(1.5, true),
                'expectedIsValid'         => true
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param mixed $value
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
