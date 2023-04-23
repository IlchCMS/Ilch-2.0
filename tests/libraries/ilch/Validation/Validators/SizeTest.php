<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the size validator
 */
class SizeTest extends TestCase
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
        $validator = new Size($data);
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
            // numeric
            'valid' => [
                'data'                    => $this->createData(3, 3),
                'expectedIsValid'         => true
            ],
            'invalid' => [
                'data'                    => $this->createData(3, 2),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.size.numeric',
                'expectedErrorParameters' => [2]
            ],
            // array
            'array valid' => [
                'data'                    => $this->createData(['a', 'b', 'c'], 3),
                'expectedIsValid'         => true
            ],
            'array invalid' => [
                'data'                    => $this->createData(['a', 'b', 'c'], 2),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.size.array',
                'expectedErrorParameters' => [2]
            ],
            'array empty invalid' => [
                'data'                    => $this->createData([], 2),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.size.array',
                'expectedErrorParameters' => [2]
            ],
            // string
            'string valid' => [
                'data'                    => $this->createData('abc', 3),
                'expectedIsValid'         => true
            ],
            'numeric string valid' => [
                'data'                    => $this->createData('123', 3, true),
                'expectedIsValid'         => true
            ],
            'numeric string invalid force string false' => [
                'data'                    => $this->createData('123', 3),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.size.numeric',
                'expectedErrorParameters' => [3]
            ],
            'numeric string valid force string false' => [
                'data'                    => $this->createData('123', 123),
                'expectedIsValid'         => true
            ],
            'string invalid' => [
                'data'                    => $this->createData('abc', 2),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.size.string',
                'expectedErrorParameters' => [2]
            ],
//            'string empty invalid' => [
//                'data'                    => $this->createData('', 2),
//                'expectedIsValid'         => false,
//                'expectedErrorKey'        => 'validation.errors.size.string',
//                'expectedErrorParameters' => [2]
//            ]
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param mixed $value
     * @param int $size
     * @param bool $forceString
     * @return stdClass
     */
    private function createData($value, int $size, bool $forceString = false): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [$size];
        if ($forceString) {
            $data->parameters[] = 'string';
        }
        $data->input = ['fieldName' => $value];
        return $data;
    }
}
