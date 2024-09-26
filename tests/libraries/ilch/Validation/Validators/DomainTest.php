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
            'valid domain with subdomain, hostname' => [
                'data'                    => $this->createData('www.ilch.de', true),
                'expectedIsValid'         => true
            ],
            'valid domain, hostname' => [
                'data'                    => $this->createData('ilch.de', true),
                'expectedIsValid'         => true
            ],
            'valid domain, ip address, hostname' => [
                'data'                    => $this->createData('127.0.0.1', true),
                'expectedIsValid'         => true
            ],
            'valid domain (longest domain), hostname' => [
                'data'                    => $this->createData('www.thelongestdomainnameintheworldandthensomeandthensomemoreandmore.com', true),
                'expectedIsValid'         => true
            ],
            'invalid too long domain (toolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolong.com), hostname' => [
                'data'                    => $this->createData('toolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolong.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid too long domain (random), hostname' => [
                'data'                    => $this->createData('eauBcFReEmjLcoZwI0RuONNnwU4H9r151juCaqTI5VeIP5jcYIqhx1lh5vV00l2rTs6y7hOp7rYw42QZiq6VIzjcYrRm8gFRMk9U9Wi1grL8Mr5kLVloYLthHgyA94QK3SaXCATklxgo6XvcbXIqAGG7U0KxTr8hJJU1p2ZQ2mXHmp4DhYP8N9SRuEKzaCPcSIcW7uj21jZqBigsLsNAXEzU8SPXZjmVQVtwQATPWeWyGW4GuJhjP4Q8o0.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'valid domain (random, trailing dot), hostname' => [
                'data'                    => $this->createData('kDTvHt1PPDgX5EiP2MwiXjcoWNOhhTuOVAUWJ3TmpBYCC9QoJV114LMYrV3Zl58.kDTvHt1PPDgX5EiP2MwiXjcoWNOhhTuOVAUWJ3TmpBYCC9QoJV114LMYrV3Zl58.kDTvHt1PPDgX5EiP2MwiXjcoWNOhhTuOVAUWJ3TmpBYCC9QoJV114LMYrV3Zl58.CQ1oT5Uq3jJt6Uhy3VH9u3Gi5YhfZCvZVKgLlaXNFhVKB1zJxvunR7SJa.com.', true),
                'expectedIsValid'         => true
            ],
            'valid domain (cont-ains.h-yph-en-s.com), hostname' => [
                'data'                    => $this->createData('cont-ains.h-yph-en-s.com', true),
                'expectedIsValid'         => true
            ],
            'invalid domain (..com), hostname' => [
                'data'                    => $this->createData('..com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid domain (ab..cc.dd), hostname' => [
                'data'                    => $this->createData('ab..cc.dd', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid domain (a.-bc.com), hostname' => [
                'data'                    => $this->createData('a.-bc.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid domain (ab.cd-.com), hostname' => [
                'data'                    => $this->createData('ab.cd-.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid domain (-.abc.com), hostname' => [
                'data'                    => $this->createData('-.abc.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid domain (abc.-.abc.com), hostname' => [
                'data'                    => $this->createData('abc.-.abc.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid domain (underscore_.example.com), hostname' => [
                'data'                    => $this->createData('underscore_.example.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'invalid domain (-1), hostname' => [
                'data'                    => $this->createData('-1', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'valid domain (_example.com)' => [
                'data'                    => $this->createData('_example.com'),
                'expectedIsValid'         => true
            ],
            'invalid domain (_example.com), hostname' => [
                'data'                    => $this->createData('_example.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'valid domain (test_.example.com)' => [
                'data'                    => $this->createData('test_.example.com'),
                'expectedIsValid'         => true
            ],
            'invalid domain (test_.example.com), hostname' => [
                'data'                    => $this->createData('test_.example.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'valid domain (te_st.example.com)' => [
                'data'                    => $this->createData('te_st.example.com'),
                'expectedIsValid'         => true
            ],
            'invalid domain (te_st.example.com), hostname' => [
                'data'                    => $this->createData('te_st.example.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'valid domain (test._example.com)' => [
                'data'                    => $this->createData('test._example.com'),
                'expectedIsValid'         => true
            ],
            'invalid domain (test._example.com), hostname' => [
                'data'                    => $this->createData('test._example.com', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.domain.noValidDomain',
                'expectedErrorParameters' => []
            ],
            'empty string, hostname' => [
                'data'                    => $this->createData('', true),
                'expectedIsValid'         => true
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param string $value
     * @param bool $validateAsHostname
     * @return stdClass
     */
    private function createData(string $value, bool $validateAsHostname = false): stdClass
    {
        $data = new stdClass();
        $data->field = 'fieldName';
        $data->parameters = [''];
        $data->input = ['fieldName' => $value];
        if ($validateAsHostname) {
            $data->parameters[] = 'hostname';
        }
        $_SERVER['HTTP_HOST'] = '127.0.0.1';
        return $data;
    }
}
