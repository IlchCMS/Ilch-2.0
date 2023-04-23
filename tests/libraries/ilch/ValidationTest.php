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
