<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Email validation class.
 */
class Email extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.email.noValidEmail';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run(): Email
    {
        $value = $this->getValue();

        if (empty($value)) {
            $this->setIsValid(true);

            return $this;
        }

        $this->setIsValid((bool) filter_var($value, FILTER_VALIDATE_EMAIL));

        return $this;
    }
}
