<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Integer validation class.
 */
class Date extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.date.mustBeDate';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $dateIsValid = preg_match('~^(\d{4})-(\d\d)-(\d\d)$~', $this->getValue(), $matches) === 1;
        if ($dateIsValid) {
            $dateIsValid = checkdate($matches[2], $matches[3], $matches[1]);
        }

        $this->setIsValid($this->getValue() === '' || $dateIsValid);

        return $this;
    }
}
