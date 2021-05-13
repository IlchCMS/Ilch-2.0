<?php
/**
 * @copyright Ilch 2
 */

namespace Ilch\Validation\Validators;

/**
 * Required validation class.
 */
class Required extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.required.fieldIsRequired';

    /**
     * Defines whether logic can be negated.
     *
     * @var bool
     */
    protected $hasInvertLogic = true;

    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $invertErrorKey = 'validation.errors.required.fieldIsNotRequired';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $value = $this->getValue();
        $value = is_string($value) ? trim($value) : $value;

        $this->setIsValid(!($value === null || $value === [] || $value === ''));

        return $this;
    }
}
