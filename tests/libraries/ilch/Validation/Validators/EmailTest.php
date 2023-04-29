<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the email validator
 */
class EmailTest extends TestCase
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
        $validator = new Email($data);
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
            // email validations
            'email valid' => [
                'data'                    => $this->createData('test@test.de'),
                'expectedIsValid'         => true
            ],
            'empty email valid' => [
                'data'                    => $this->createData(''),
                'expectedIsValid'         => true
            ],
            'email invalid' => [
                'data'                    => $this->createData('test'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.email.noValidEmail',
                'expectedErrorParameters' => []
            ],
            'email invalid missing domain' => [
                'data'                    => $this->createData('test@'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.email.noValidEmail',
                'expectedErrorParameters' => []
            ],
            'email invalid missing name' => [
                'data'                    => $this->createData('@test.de'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.email.noValidEmail',
                'expectedErrorParameters' => []
            ],
            'email invalid space as name and domain' => [
                'data'                    => $this->createData(' @ '),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.email.noValidEmail',
                'expectedErrorParameters' => []
            ],
            'email invalid domain invalid' => [
                'data'                    => $this->createData(' @.de'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.email.noValidEmail',
                'expectedErrorParameters' => []
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param string $value
     * @return stdClass
     */
    private function createData(string $value): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [''];
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
