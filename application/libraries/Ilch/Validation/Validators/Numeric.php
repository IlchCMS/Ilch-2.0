<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Numeric validation class.
 */
class Numeric extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.numeric.mustBeNumeric';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $this->setIsValid($this->getValue() === '' || is_numeric($this->getValue()));

        return $this;
    }
}
