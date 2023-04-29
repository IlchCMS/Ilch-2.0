<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Ilch\TestCase;

class ValidationTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        Registry::set('translator', new Translator());
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        Registry::remove('translator');
    }

    /**
     * @dataProvider dpForTestValidationWithSingleValidator
     *
     * @param array $params
     * @param bool $expected
     * @param bool $inverted
     */
    public function testValidationWithSingleValidator(array $params, bool $expected, bool $inverted)
    {
        $validation = Validation::create($params, ['testField' => ($inverted ? 'NOT' : '').'integer']);

        self::assertSame($expected, $validation->isValid());
        if (!$expected) {
            self::assertTrue($validation->getErrorBag()->hasError('testField'));
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidationWithSingleValidator(): array
    {
        return [
            'int'                            => ['params' => ['testField' => 5], 'expected' => true, 'inverted' => false],
            'string with only digits'        => ['params' => ['testField' => '15'], 'expected' => true, 'inverted' => false],
            'int string with prefix'         => ['params' => ['testField' => 'pre15'], 'expected' => false, 'inverted' => false],
            'string'                         => ['params' => ['testField' => 'test'], 'expected' => false, 'inverted' => false],
            'int string with postfix'        => ['params' => ['testField' => '15post'], 'expected' => false, 'inverted' => false],
            'invert int string with prefix'  => ['params' => ['testField' => 'pre15'], 'expected' => true, 'inverted' => true],
            'invert int'                     => ['params' => ['testField' => 15], 'expected' => false, 'inverted' => true],
        ];
    }

    /**
     * @dataProvider dpForTestValidationWithValidatorChainBreakingChain
     *
     * @param string $validatorRules
     * @param array $params
     * @param bool $expected
     * @param array $expectedErrors
     */
    public function testValidationWithValidatorChainWithBreakingChain(
        $validatorRules,
        array $params,
        bool $expected,
        array $expectedErrors = []
    ) {
        $validation = Validation::create($params, ['testField' => $validatorRules]);

        self::assertSame($expected, $validation->isValid());
        if (!$expected) {
            self::assertSame($expectedErrors, $validation->getErrorBag()->getErrors());
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidationWithValidatorChainBreakingChain()
    {
        return [
            '2 validators chained - correct'       => [
                'validatorRules' => 'integer|max:5',
                'params'         => ['testField' => 5],
                'expected'       => true
            ],
            '3 validators chained - correct'       => [
                'validatorRules' => 'integer|min:1|max:5',
                'params'         => ['testField' => 5],
                'expected'       => true
            ],
            '3 validators chained - 2nd not valid' => [
                'validatorRules' => 'integer|min:3|max:5',
                'params'         => ['testField' => 2],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.min.numeric']
                ]
            ],
            '3 validators chained - 3rd not valid' => [
                'validatorRules' => 'integer|max:5|same:4',
                'params'         => ['testField' => 3],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.same.fieldsDontMatch']
                ]
            ],
            '3 validators chained - 2nd & 3rd not valid' => [
                'validatorRules' => 'integer|max:5|same:4',
                'params'         => ['testField' => 7],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.max.numeric', 'validation.errors.same.fieldsDontMatch']
                ]
            ],
        ];
    }

    /**
     * @dataProvider dpForTestValidationWithValidatorChainWithoutBreakingChain
     *
     * @param string $validatorRules
     * @param array $params
     * @param bool $expected
     * @param array $expectedErrors
     */
    public function testValidationWithValidatorChainWithoutBreakingChain(
        $validatorRules,
        array $params,
        bool $expected,
        array $expectedErrors = []
    ) {
        $this->addTearDownCallback(
            function () {
                Validation::setBreakChain(true);
            }
        );
        Validation::setBreakChain(false);

        $validation = Validation::create($params, ['testField' => $validatorRules]);

        self::assertSame($expected, $validation->isValid());
        if (!$expected) {
            self::assertSame($expectedErrors, $validation->getErrorBag()->getErrors());
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidationWithValidatorChainWithoutBreakingChain(): array
    {
        return [
            '2 validators chained - correct'       => [
                'validatorRules' => 'integer|max:5',
                'params'         => ['testField' => 5],
                'expected'       => true
            ],
            '3 validators chained - correct'       => [
                'validatorRules' => 'integer|min:1|max:5',
                'params'         => ['testField' => 5],
                'expected'       => true
            ],
            '3 validators chained - 2nd not valid' => [
                'validatorRules' => 'integer|min:3|max:5',
                'params'         => ['testField' => 2],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.min.numeric']
                ]
            ],
            '3 validators chained - 3rd not valid' => [
                'validatorRules' => 'integer|max:7|same:4',
                'params'         => ['testField' => 7],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.same.fieldsDontMatch']
                ]
            ],
        ];
    }

    /**
     * @dataProvider dpForTestValidationWithVariousValidators
     *
     * @param string $validatorRules
     * @param array $params
     * @param bool $expected
     * @param array $expectedErrors
     */
    public function testValidationWithVariousValidators(
        $validatorRules,
        array $params,
        bool $expected,
        array $expectedErrors = []
    ) {
        // Needed for Url validator
        $_SERVER['HTTP_HOST'] = '127.0.0.1';

        $this->addTearDownCallback(
            function () {
                Validation::setBreakChain(true);
            }
        );
        Validation::setBreakChain(false);

        $validation = Validation::create($params, ['testField' => $validatorRules]);

        self::assertSame($expected, $validation->isValid());
        if (!$expected) {
            self::assertSame($expectedErrors, $validation->getErrorBag()->getErrors());
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidationWithVariousValidators(): array
    {
        // Excluding the validators Captcha, Grecaptcha, Integer (already covered above), Exists and Unique.
        return [
            // Date validator
            'date validator - valid' => [
                'validatorRules' => 'date',
                'params'         => ['testField' => '2023-04-29'],
                'expected'       => true
            ],
            'date validator changed format - valid' => [
                'validatorRules' => 'date:d.m.Y',
                'params'         => ['testField' => '29.04.2023'],
                'expected'       => true
            ],
            'date validator - not valid' => [
                'validatorRules' => 'date',
                'params'         => ['testField' => '60.04.2023'],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.date.mustBeDate']
                ]
            ],
            // Email validator
            'Email validator - empty valid' => [
                'validatorRules' => 'email',
                'params'         => ['testField' => ''],
                'expected'       => true
            ],
            'Required and Email validator - not valid' => [
                'validatorRules' => 'required|email',
                'params'         => ['testField' => ''],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.required.fieldIsRequired']
                ]
            ],
            'Email validator - valid' => [
                'validatorRules' => 'email',
                'params'         => ['testField' => 'test@test.test'],
                'expected'       => true
            ],
            'Email validator - not valid' => [
                'validatorRules' => 'email',
                'params'         => ['testField' => '@test.test'],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.email.noValidEmail']
                ]
            ],
            // Max validator
            'Max validator - valid' => [
                'validatorRules' => 'max:5',
                'params'         => ['testField' => 5],
                'expected'       => true
            ],
            'Max validator - valid value as string' => [
                'validatorRules' => 'max:5',
                'params'         => ['testField' => '5'],
                'expected'       => true
            ],
            'Max validator - not valid' => [
                'validatorRules' => 'max:5',
                'params'         => ['testField' => 6],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.max.numeric']
                ]
            ],
            'Max validator - valid array' => [
                'validatorRules' => 'max:2',
                'params'         => ['testField' => [1, 3]],
                'expected'       => true
            ],
            'Max validator - valid array values as string' => [
                'validatorRules' => 'max:2',
                'params'         => ['testField' => ['1', '3']],
                'expected'       => true
            ],
            'Max validator - not valid array' => [
                'validatorRules' => 'max:2',
                'params'         => ['testField' => [0, 1, 2]],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.max.array']
                ]
            ],
            'Max validator - valid string' => [
                'validatorRules' => 'max:5,string',
                'params'         => ['testField' => 12345],
                'expected'       => true
            ],
            'Max validator - valid string value as string' => [
                'validatorRules' => 'max:5,string',
                'params'         => ['testField' => '12345'],
                'expected'       => true
            ],
            'Max validator - not valid string' => [
                'validatorRules' => 'max:5,string',
                'params'         => ['testField' => 123456],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.max.string']
                ]
            ],
            // Min validator
            'Min validator - valid' => [
                'validatorRules' => 'min:5',
                'params'         => ['testField' => 5],
                'expected'       => true
            ],
            'Min validator - valid value as string' => [
                'validatorRules' => 'min:5',
                'params'         => ['testField' => '5'],
                'expected'       => true
            ],
            'Min validator - not valid' => [
                'validatorRules' => 'min:5',
                'params'         => ['testField' => 4],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.min.numeric']
                ]
            ],
            'Min validator - valid array' => [
                'validatorRules' => 'min:2',
                'params'         => ['testField' => [1, 3]],
                'expected'       => true
            ],
            'Min validator - valid array values as string' => [
                'validatorRules' => 'min:2',
                'params'         => ['testField' => ['1', '3']],
                'expected'       => true
            ],
            'Min validator - not valid array' => [
                'validatorRules' => 'min:2',
                'params'         => ['testField' => [3]],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.min.array']
                ]
            ],
            'Min validator - valid string' => [
                'validatorRules' => 'min:5,string',
                'params'         => ['testField' => 12345],
                'expected'       => true
            ],
            'Min validator - valid string value as string' => [
                'validatorRules' => 'min:5,string',
                'params'         => ['testField' => '12345'],
                'expected'       => true
            ],
            'Min validator - not valid string' => [
                'validatorRules' => 'min:5,string',
                'params'         => ['testField' => 5678],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.min.string']
                ]
            ],
            // Numeric validator
            'Numeric validator - empty valid' => [
                'validatorRules' => 'numeric',
                'params'         => ['testField' => ''],
                'expected'       => true
            ],
            'Required and Numeric validator - not valid' => [
                'validatorRules' => 'required|numeric',
                'params'         => ['testField' => ''],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.required.fieldIsRequired']
                ]
            ],
            'Numeric validator - valid' => [
                'validatorRules' => 'numeric',
                'params'         => ['testField' => 1],
                'expected'       => true
            ],
            'Numeric validator - valid float' => [
                'validatorRules' => 'numeric',
                'params'         => ['testField' => 1.5],
                'expected'       => true
            ],
            'Numeric validator - valid string' => [
                'validatorRules' => 'numeric',
                'params'         => ['testField' => '1'],
                'expected'       => true
            ],
            'Numeric validator - valid float string' => [
                'validatorRules' => 'numeric',
                'params'         => ['testField' => '1.5'],
                'expected'       => true
            ],
            'Numeric validator - not valid' => [
                'validatorRules' => 'numeric',
                'params'         => ['testField' => 'a'],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.numeric.mustBeNumeric']
                ]
            ],
            // Required validator
            'Required validator - valid' => [
                'validatorRules' => 'required',
                'params'         => ['testField' => 'a'],
                'expected'       => true
            ],
            'Required validator - not valid' => [
                'validatorRules' => 'required',
                'params'         => ['testField' => ''],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.required.fieldIsRequired']
                ]
            ],
            'Required validator - valid numeric' => [
                'validatorRules' => 'required',
                'params'         => ['testField' => 1],
                'expected'       => true
            ],
            // Same validator
            'Same validator - valid' => [
                'validatorRules' => 'same:password',
                'params'         => ['password' => 'a', 'testField' => 'a'],
                'expected'       => true
            ],
            'Same validator - valid numeric' => [
                'validatorRules' => 'same:password',
                'params'         => ['password' => 1, 'testField' => 1],
                'expected'       => true
            ],
            'Same validator - valid mixed' => [
                'validatorRules' => 'same:password',
                'params'         => ['password' => 1, 'testField' => '1'],
                'expected'       => true
            ],
            'Same validator - not valid' => [
                'validatorRules' => 'same:password',
                'params'         => ['password' => 'a', 'testField' => 'b'],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.same.fieldsDontMatch']
                ]
            ],
            // Size validator
            'Size validator - valid' => [
                'validatorRules' => 'size:5',
                'params'         => ['testField' => 5],
                'expected'       => true
            ],
            'Size validator - valid value as string' => [
                'validatorRules' => 'size:5',
                'params'         => ['testField' => '5'],
                'expected'       => true
            ],
            'Size validator - not valid' => [
                'validatorRules' => 'size:5',
                'params'         => ['testField' => 4],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.size.numeric']
                ]
            ],
            'Size validator - valid array' => [
                'validatorRules' => 'size:2',
                'params'         => ['testField' => [1, 3]],
                'expected'       => true
            ],
            'Size validator - valid array values as string' => [
                'validatorRules' => 'size:2',
                'params'         => ['testField' => ['1', '3']],
                'expected'       => true
            ],
            'Size validator - not valid array too small' => [
                'validatorRules' => 'size:2',
                'params'         => ['testField' => [3]],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.size.array']
                ]
            ],
            'Size validator - not valid array too big' => [
                'validatorRules' => 'size:2',
                'params'         => ['testField' => [1, 2, 3]],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.size.array']
                ]
            ],
            'Size validator - valid string' => [
                'validatorRules' => 'size:5,string',
                'params'         => ['testField' => 12345],
                'expected'       => true
            ],
            'Size validator - valid string value as string' => [
                'validatorRules' => 'size:5,string',
                'params'         => ['testField' => '12345'],
                'expected'       => true
            ],
            'Size validator - not valid string too short' => [
                'validatorRules' => 'size:5,string',
                'params'         => ['testField' => 5678],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.size.string']
                ]
            ],
            'Size validator - not valid string too big' => [
                'validatorRules' => 'size:5,string',
                'params'         => ['testField' => 456789],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.size.string']
                ]
            ],
            // Url validator
            'Url validator - empty valid' => [
                'validatorRules' => 'url',
                'params'         => ['testField' => ''],
                'expected'       => true
            ],
            'Required and Url validator - not valid' => [
                'validatorRules' => 'required|url',
                'params'         => ['testField' => ''],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.required.fieldIsRequired']
                ]
            ],
            'Url validator - not valid missing protocol' => [
                'validatorRules' => 'url',
                'params'         => ['testField' => 'ilch.de'],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.url.noValidUrl']
                ]
            ],
            'Url validator - not valid subdomain but missing protocol' => [
                'validatorRules' => 'url',
                'params'         => ['testField' => 'www.ilch.de'],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.url.noValidUrl']
                ]
            ],
            'Url validator - valid protocol' => [
                'validatorRules' => 'url',
                'params'         => ['testField' => 'https://ilch.de'],
                'expected'       => true
            ],
            'Url validator - valid protocol ftp' => [
                'validatorRules' => 'url',
                'params'         => ['testField' => 'ftp://ilch.de'],
                'expected'       => true
            ],
            'Url validator - valid subdomain protocol' => [
                'validatorRules' => 'url',
                'params'         => ['testField' => 'https://www.ilch.de'],
                'expected'       => true
            ],
            'Url validator - not valid invalid protocol' => [
                'validatorRules' => 'url',
                'params'         => ['testField' => 'invalid://www.ilch.de'],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.url.noValidUrl']
                ]
            ],
            'Url validator - not valid' => [
                'validatorRules' => 'url',
                'params'         => ['testField' => '@test.test'],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.url.noValidUrl']
                ]
            ],
        ];
    }

    public function testTranslation()
    {
        $this->addTearDownCallback(
            function () {
                Registry::remove('translator');
                Registry::set('translator', new Translator());
            }
        );
        $translator = new Translator();
        $translator->setTranslations(
            [
                'testField'                     => 'Das Feld',
                'validation.errors.min.numeric' => '%s muss mindestens %s betragen.'
            ]
        );
        Registry::remove('translator');
        Registry::set('translator', $translator);

        $validation = Validation::create(['testField' => '2'], ['testField' => 'integer|min:3']);

        $errorMessages = $validation->getErrorBag()->getErrorMessages();

        self::assertSame(['Das Feld muss mindestens 3 betragen.'], $errorMessages);
    }
}
