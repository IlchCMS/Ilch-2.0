<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the Same validator
 */
class SameTest extends TestCase
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
        $validator = new Same($data);
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
            'passwords are the same' => [
                'data'                    => $this->createData('password', 'god', 'password_confirm', 'god'),
                'expectedIsValid'         => true
            ],
            'password are not the same' => [
                'data'                    => $this->createData('password', '123', 'password_confirm', 'god'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.same.fieldsDontMatch',
                'expectedErrorParameters' => ['password_confirm']
            ],
            'strict comparison' => [
                'data'                    => $this->createData('password', '123', 'password_confirm', 123, true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.same.fieldsDontMatch',
                'expectedErrorParameters' => ['password_confirm']
            ],
            'passwords are the same inverted' => [
                'data'                    => $this->createData('password', 'god', 'password_confirm', 'god', null, true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.same.fieldsDontMatch',
                'expectedErrorParameters' => ['password_confirm']
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param string $firstField
     * @param mixed $valueFirstField
     * @param string $secondField
     * @param mixed $valueSecondField
     * @param mixed $strict
     * @param bool $invertResult
     * @return stdClass
     */
    private function createData(string $firstField, $valueFirstField, string $secondField, $valueSecondField, bool $strict = null, bool $invertResult = false): stdClass
    {
        $data = new stdClass();
        $data->field = $firstField;
        $data->parameters = [$secondField, $strict];
        $data->invertResult = $invertResult;
        $data->input = [$firstField => $valueFirstField, $secondField => $valueSecondField];
        return $data;
    }
}
