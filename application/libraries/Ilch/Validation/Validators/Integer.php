<?php
/**
 * @copyright Ilch 2
 */

namespace Ilch\Validation\Validators;

/**
 * Integer validation class.
 */
class Integer extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.integer.mustBeInteger';

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
    protected $invertErrorKey = 'validation.errors.integer.dontBeInteger';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run(): self
    {
        $this->setIsValid($this->getValue() === '' || filter_var($this->getValue(), FILTER_VALIDATE_INT) !== false);

        return $this;
    }
}
