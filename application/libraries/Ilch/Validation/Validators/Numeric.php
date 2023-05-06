<?php
/**
 * @copyright Ilch 2
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
     * Defines whether logic can be negated.
     *
     * @var bool
     * @since 2.1.43
     */
    protected $hasInvertLogic = true;

    /**
     * Default error key for this validator.
     *
     * @var string
     * @since 2.1.43
     */
    protected $invertErrorKey = 'validation.errors.required.dontBeNumeric';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run(): Numeric
    {
        $this->setIsValid($this->getValue() === '' || is_numeric($this->getValue()));

        return $this;
    }
}
