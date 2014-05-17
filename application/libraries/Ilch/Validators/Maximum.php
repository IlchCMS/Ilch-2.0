<?php

/**
 * Maximum validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Validators;

class Maximum extends Base
{
    /**
     * @var mixed The value to validate
     */
    protected $value;

    /**
     * @var integer Min length of the value
     */
    protected $max;

    /**
     * Prepares the validator
     *
     * @param mixed   $value       The value to validate
     * @param array   $source      The whole source array
     * @param integer $max         Maximum
     */
    public function prepare($value, $source, $max)
    {
        $this->value = $value;
        $this->max = $max;
    }

    /**
     * Executes the validation
     */
    public function execute()
    {
        $type = gettype($this->value);

        switch ($type) {
            case 'integer':
                if (!($this->value <= $this->max)) {
                    $this->addError("darf höchstens {$this->max} betragen.");
                }
                break;

            case 'string':
                if (!(strlen($this->value) <= $this->max)) {
                    $this->addError("darf höchstens {$this->max} Zeichen lang sein.");
                }
                break;

            case 'array':
                if (!(count($this->value) <= $this->max)) {
                    $this->addError("darf höchstens {$this->max} Einträge enthalten.");
                }
                break;

            default:
                break;
        }
    }
}
