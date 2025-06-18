<?php

/**
 * @copyright Ilch 2
 */

namespace Ilch\Validation\Validators;

/**
 * Domain validation class.
 */
class Domain extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.domain.noValidDomain';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run(): self
    {
        $validateAsHostname = $this->getParameter(1) === 'hostname';
        $this->setIsValid($this->getValue() === '' || filter_var($this->getValue(), FILTER_VALIDATE_DOMAIN, ($validateAsHostname ? FILTER_FLAG_HOSTNAME : 0)) !== false);

        return $this;
    }
}
