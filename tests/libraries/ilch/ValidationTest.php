<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Ilch\TestCase;

class ValidationTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        Registry::set('translator', new Translator());
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        Registry::remove('translator');
    }

    /**
     * @dataProvider dpForTestValidationWithSingleValidator
     *
     * @param array $params
     * @param bool $expected
     */
    public function testValidationWithSingleValidator(array $params, $expected)
    {
        $validation = Validation::create($params, ['testField' => 'integer']);

        $this->assertSame($expected, $validation->isValid());
        if (!$expected) {
            $this->assertTrue($validation->getErrorBag()->hasError('testField'));
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidationWithSingleValidator()
    {
        return [
            'int'                     => ['params' => ['testField' => 5], 'expected' => true],
            'string with only digits' => ['params' => ['testField' => '15'], 'expected' => true],
            'int string with prefix'  => ['params' => ['testField' => 'pre15'], 'expected' => false],
            'string'                  => ['params' => ['testField' => 'test'], 'expected' => false],
            'int string with postfix' => ['params' => ['testField' => '15post'], 'expected' => false],
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
        $expected,
        array $expectedErrors = []
    ) {
        $validation = Validation::create($params, ['testField' => $validatorRules]);

        $this->assertSame($expected, $validation->isValid());
        if (!$expected) {
            $this->assertSame($expectedErrors, $validation->getErrorBag()->getErrors());
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
                'params'         => ['testField' => 7],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.max.numeric']
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
        $expected,
        array $expectedErrors = []
    ) {
        $this->addTearDownCallback(
            function () {
                Validation::setBreakChain(true);
            }
        );
        Validation::setBreakChain(false);

        $validation = Validation::create($params, ['testField' => $validatorRules]);

        $this->assertSame($expected, $validation->isValid());
        if (!$expected) {
            $this->assertSame($expectedErrors, $validation->getErrorBag()->getErrors());
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidationWithValidatorChainWithoutBreakingChain()
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
                'params'         => ['testField' => 7],
                'expected'       => false,
                'expectedErrors' => [
                    'testField' => ['validation.errors.max.numeric', 'validation.errors.same.fieldsDontMatch']
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

        $this->assertSame(['Das Feld muss mindestens 3 betragen.'], $errorMessages);
    }
}
