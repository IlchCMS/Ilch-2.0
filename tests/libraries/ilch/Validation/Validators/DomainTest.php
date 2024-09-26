<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Validation\Validators;

use PHPUnit\Ilch\TestCase;
use stdClass;

/**
 * Tests for the domain validator
 * @see https://www.rfc-editor.org/rfc/rfc1123#page-13
 * @see https://github.com/php/php-src/blob/master/ext/filter/tests/056.phpt
 * @since 2.2.4
 */
class DomainTest extends TestCase
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
        $validator = new Domain($data);
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
            'valid domain with subdomain' => [
                'data'                    => $this->createData('www.ilch.de'),
                'expectedIsValid'         => true
            ],
            'valid domain' => [
                'data'                    => $this->createData('ilch.de'),
                'expectedIsValid'         => true
            ],
            'valid domain, ip address' => [
                'data'                    => $this->createData('127.0.0.1'),
                'expectedIsValid'         => true
            ],
            'invalid too long domain (toolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolong.com)' => [
                'data'                    => $this->createData('toolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolong.com'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid too long domain (random)' => [
                'data'                    => $this->createData('eauBcFReEmjLcoZwI0RuONNnwU4H9r151juCaqTI5VeIP5jcYIqhx1lh5vV00l2rTs6y7hOp7rYw42QZiq6VIzjcYrRm8gFRMk9U9Wi1grL8Mr5kLVloYLthHgyA94QK3SaXCATklxgo6XvcbXIqAGG7U0KxTr8hJJU1p2ZQ2mXHmp4DhYP8N9SRuEKzaCPcSIcW7uj21jZqBigsLsNAXEzU8SPXZjmVQVtwQATPWeWyGW4GuJhjP4Q8o0.com'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'valid domain (random, trailing dot)' => [
                'data'                    => $this->createData('kDTvHt1PPDgX5EiP2MwiXjcoWNOhhTuOVAUWJ3TmpBYCC9QoJV114LMYrV3Zl58.kDTvHt1PPDgX5EiP2MwiXjcoWNOhhTuOVAUWJ3TmpBYCC9QoJV114LMYrV3Zl58.kDTvHt1PPDgX5EiP2MwiXjcoWNOhhTuOVAUWJ3TmpBYCC9QoJV114LMYrV3Zl58.CQ1oT5Uq3jJt6Uhy3VH9u3Gi5YhfZCvZVKgLlaXNFhVKB1zJxvunR7SJa.com.'),
                'expectedIsValid'         => true
            ],
            'valid domain (cont-ains.h-yph-en-s.com)' => [
                'data'                    => $this->createData('cont-ains.h-yph-en-s.com'),
                'expectedIsValid'         => true
            ],
            'invalid domian (..com)' => [
                'data'                    => $this->createData('..com'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid domian (ab..cc.dd)' => [
                'data'                    => $this->createData('ab..cc.dd'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'valid domian (a.-bc.com)' => [
                'data'                    => $this->createData('a.-bc.com'),
                'expectedIsValid'         => true
            ],
            'valid domian (ab.cd-.com)' => [
                'data'                    => $this->createData('ab.cd-.com'),
                'expectedIsValid'         => true
            ],
            'valid domian (-.abc.com)' => [
                'data'                    => $this->createData('-.abc.com'),
                'expectedIsValid'         => true
            ],
            'valid domian (abc.-.abc.com)' => [
                'data'                    => $this->createData('abc.-.abc.com'),
                'expectedIsValid'         => true
            ],
            'valid domian (underscore_.example.com)' => [
                'data'                    => $this->createData('underscore_.example.com'),
                'expectedIsValid'         => true
            ],
            'valid domian (-1)' => [
                'data'                    => $this->createData('-1'),
                'expectedIsValid'         => true
            ],
            'empty string' => [
                'data'                    => $this->createData(''),
                'expectedIsValid'         => true
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
        $_SERVER['HTTP_HOST'] = '127.0.0.1';
        return $data;
    }
}
