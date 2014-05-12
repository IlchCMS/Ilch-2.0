<?php

/**
 * Minimum validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Validators;

class Minimum extends Base
{
    /**
     * @var mixed The value to validate
     */
    protected $value;

    /**
     * @var integer Min length of the value
     */
    protected $min;

    /**
     * Prepares the validator
     *
     * @param mixed   $value       The value to validate
     * @param array   $source      The whole source array
     * @param integer $min         Minimum
     */
    public function prepare($value, $source, $min)
    {
        $this->value = $value;
        $this->min = $min;
    }

    /**
     * Executes the validation
     */
    public function execute()
    {
        $type = gettype($this->value);

        switch ($type) {
            case 'integer':
                if (!($this->value >= $this->min)) {
                    $this->addError("muss mindestens {$this->min} betragen.");
                }
                break;

            case 'string':
                if (!(strlen($this->value) >= $this->min)) {
                    $this->addError("muss mindestens {$this->min} Zeichen lang sein.");
                }
                break;

            case 'array':
                if (!(count($this->value) >= $this->min)) {
                    $this->addError("muss mindestens {$this->min} Einträge enthalten.");
                }
                break;

            default:
                break;
        }
    }
}
