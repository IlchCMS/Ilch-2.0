<?php
/**
 * @copyright Ilch 2
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
        $format = $this->getParameter(0) ?? 'Y-m-d';

        $d = \DateTime::createFromFormat($format, $this->getValue());

        $this->setIsValid($d && $d->format($format) == $this->getValue());

        return $this;
    }
}
