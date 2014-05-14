<?php

/**
 * Same validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Validators;

class Same extends Base
{
    /**
     * @var mixed The value to validate
     */
    protected $value;

    /**
     * @var array source array
     */
    protected $source;

    /**
     * @var mixed
     */
    protected $compare_against;

    /**
     * @var boolean checks var type if true
     */
    protected $strict = false;

    /**
     * Prepares the validator
     *
     * @param mixed   $value       The value to validate
     * @param array   $source      The whole source array
     * @param integer $params      Parameters
     */
    public function prepare($value, $source, $params)
    {
        $this->value           = $value;
        $this->source          = $source;

        if (is_array($params)) {
            if (isset($params['as'])) {
                $this->compare_against = $params['as'];
            }

            if (isset($params['strict'])) {
                $this->strict = $params['strict'];
            }
        } else {
            $this->compare_against = $params;
        }
    }

    /**
     * Executes the validation
     */
    public function execute()
    {
        $compare_against = array_dot($this->source, $this->compare_against);

        if ($this->strict === true) {
            if ($this->value !== $compare_against) {
                $this->addError("unterscheidet sich von {$this->compare_against}");
            }
        } else {
            if ($this->value != $compare_against) {
                $this->addError("unterscheidet sich von {$this->compare_against}");
            }
        }
    }
}
