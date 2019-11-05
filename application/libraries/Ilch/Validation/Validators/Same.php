<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Same validation class.
 */
class Same extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.same.fieldsDontMatch';

    /**
     * Minimum parameter count needed.
     *
     * @var int
     */
    protected $minParams = 1;

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $strict = $this->getParameter(1);

        $result = $this->getValue() === array_dot($this->getInput(), $this->getParameter(0));

        if ($strict === null) {
            $result = $this->getValue() == array_dot($this->getInput(), $this->getParameter(0));
        }

        $this->setIsValid($result);
        $this->setErrorParameters([$this->getParameter(0)]);

        return $this;
    }
}
