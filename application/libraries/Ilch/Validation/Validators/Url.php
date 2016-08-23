<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Required validation class.
 */
class Url extends Base
{
    // Regular Expression for URL validation
    //
    // Author: Diego Perini
    // Updated: 2010/12/05
    // License: MIT
    // https://gist.github.com/dperini/729294
    const REGEXP = '%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu';

    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.url.noValidUrl';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $value = $this->getValue();

        if (empty($value)) {
            $this->setIsValid(true);

            return $this;
        }

        $this->setIsValid((bool) preg_match(static::REGEXP, $value));

        return $this;
    }
}
