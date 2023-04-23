<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the required validator
 */
class RequiredTest extends TestCase
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
        $validator = new Required($data);
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
            'valid' => [
                'data'                    => $this->createData('test'),
                'expectedIsValid'         => true
            ],
            'invalid' => [
                'data'                    => $this->createData(''),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.required.fieldIsRequired',
                'expectedErrorParameters' => []
            ],
            'invalid inverted' => [
                'data'                    => $this->createData('', true),
                'expectedIsValid'         => true
            ]
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
