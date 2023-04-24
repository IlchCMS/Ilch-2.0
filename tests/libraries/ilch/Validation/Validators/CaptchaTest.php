<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the captcha validator
 */
class CaptchaTest extends TestCase
{
    protected $backupGlobals = false;

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
        $validator = new Captcha($data);
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
            'invalid captcha' => [
                'data'                    => $this->createData('abc'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.captcha.wrongCaptcha',
                'expectedErrorParameters' => []
            ],
            'invalid empty' => [
                'data'                    => $this->createData(''),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.captcha.wrongCaptcha',
                'expectedErrorParameters' => []
            ]
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
        $_SESSION['captcha'] = 'test';
        return $data;
    }
}
