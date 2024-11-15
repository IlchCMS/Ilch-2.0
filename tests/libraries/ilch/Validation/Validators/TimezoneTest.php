<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the timezone validator
 */
class TimezoneTest extends TestCase
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
        $validator = new Timezone($data);
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
            // timezone validations
            'timezone valid' => [
                'data'                    => $this->createData('Europe/Berlin'),
                'expectedIsValid'         => true
            ],
            'timezone valid including outdated' => [
                'data'                    => $this->createData('America/Shiprock', true),
                'expectedIsValid'         => true
            ],
            'empty timezone valid' => [
                'data'                    => $this->createData(''),
                'expectedIsValid'         => true
            ],
            'timezone invalid' => [
                'data'                    => $this->createData('test'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.timezone.notAValidTimezone',
                'expectedErrorParameters' => []
            ],
            'timezone invalid completely removed' => [
                'data'                    => $this->createData('Canada/East-Saskatchewan'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.timezone.notAValidTimezone',
                'expectedErrorParameters' => []
            ],
            'timezone invalid outdated' => [
                'data'                    => $this->createData('America/Shiprock'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.timezone.notAValidTimezone',
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
    private function createData(string $value, bool $includeOutdated = false): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [''];
        $data->input = ['fieldName' => $value];
        if ($includeOutdated) {
            $data->parameters[] = 'backwardsCompatible';
        }
        return $data;
    }
}
