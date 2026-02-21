<?php

/**
 * @copyright Ilch 2
 */

namespace Ilch\Validation\Validators;

/**
 * Timezone validation class.
 */
class Timezone extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.timezone.notAValidTimezone';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run(): self
    {
        $includeOutdated = $this->getParameter(1) === 'backwardsCompatible';

        $this->setIsValid($this->getValue() === '' || in_array($this->getValue(), \DateTimeZone::listIdentifiers($includeOutdated ? \DateTimeZone::ALL_WITH_BC : \DateTimeZone::ALL)) !== false);

        return $this;
    }
}
