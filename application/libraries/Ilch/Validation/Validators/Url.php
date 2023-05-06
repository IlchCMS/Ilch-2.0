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
    /**
     * @var string
     */
    protected static $regExp;

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
    public function run(): Url
    {
        $value = $this->getValue();

        if (empty($value)) {
            $this->setIsValid(true);

            return $this;
        }

        $this->setIsValid((bool) preg_match(static::getRegExp(), $value));

        return $this;
    }

    /**
     * Regular Expression for URL validation
     * Author: Diego Perini
     * Updated: 2010/12/05 - last update 2015/07/14
     * License: MIT
     * https://gist.github.com/dperini/729294
     *
     * @return string
     */
    protected static function getRegExp(): string
    {
        if (!isset(static::$regExp)) {
            static::$regExp = '_^'
                // protocol identifier
                . '(?:(?:https?|ftp)://)'
                // user:pass authentication
                . '(?:\S+(?::\S*)?@)?'
                . '(?:'
                // ADDED: current hostname
                . preg_quote($_SERVER['HTTP_HOST'], '_')
                . '|'
                // IP address exclusion
                // private & local networks
                . '(?!(?:10|127)(?:\.\d{1,3}){3})'
                . '(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})'
                . '(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})'
                // IP address dotted notation octets
                // excludes loopback network 0.0.0.0
                // excludes reserved space >= 224.0.0.0
                // excludes network & broacast addresses
                // (first & last IP address of each class)
                . '(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))'
                . '|'
                // host name
                . '(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)'
                // domain name
                . '(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)*'
                // TLD identifier
                . '(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,}))'
                // TLD may end with dot
                . '\.?)'
                // port number
                . '(?::\d{2,5})?'
                // resource path
                . '(?:[/?#]\S*)?$_iuS';
        }
        return static::$regExp;
    }
}
