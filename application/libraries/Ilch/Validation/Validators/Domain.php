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
        $this->setIsValid($this->getValue() === '' || filter_var($this->getValue(), FILTER_VALIDATE_DOMAIN) !== false);

        return $this;
    }
}
