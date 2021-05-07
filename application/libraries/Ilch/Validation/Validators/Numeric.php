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
     * defines whether logic can be negated .
     *
     * @var bool
     */
    protected $hasInvertLogic = true;

    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $invertErrorKey = 'validation.errors.required.dontBeNumeric';

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
