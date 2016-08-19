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
    protected $errorKey = 'validation.errors.same.fieldsDontMatch';
    protected $minParams = 1;
    protected $maxParams = 2;

    public function run()
    {
        $strict = $this->data->getParam(1);

        $result = $this->value === $this->data->getInput()[$this->data->getParam(0)];

        if (is_null($strict)) {
            $result = $this->value == $this->data->getInput()[$this->data->getParam(0)];
        }

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($this->data),
            'error_params' => [[$this->data->getParam(0), true]],
        ];
    }
}
