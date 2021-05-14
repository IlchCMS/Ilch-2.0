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
     * defines whether logic can be negated.
     *
     * @since Version 2.1.43
     *
     * @var bool
     */
    protected $hasInvertLogic = true;

    /**
     * Default error key for this validator.
     *
     * @since Version 2.1.43
     *
     * @var string
     */
    protected $invertErrorKey = 'validation.errors.required.dontBeInteger';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $this->setIsValid($this->getValue() === '' || filter_var($this->getValue(), FILTER_VALIDATE_INT) !== false);

        return $this;
    }
}
