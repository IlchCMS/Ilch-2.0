<?php

/**
 * Datetime validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Validators;

class Datetime extends Base
{
    /**
     * @var mixed The value to validate
     */
    protected $value;

    /**
     * @var mixed The format to check against
     */
    protected $format = 'Y-m-d H:i:s';

    /**
     * Prepares the validator
     *
     * @param mixed   $value       The value to validate
     * @param array   $source      The whole source array
     * @param integer $params      Parameters
     */
    public function prepare($value, $source, $params)
    {
        $this->value = $value;

        if (isset($params['format'])) {
            $this->format = $params['format'];
        }
    }

    /**
     * Executes the validation
     */
    public function execute()
    {
        if (!\Ilch\Date::createFromFormat($this->format, $this->value)) {
            $this->addError('ist kein gültiges Datum.');
        }
    }
}
