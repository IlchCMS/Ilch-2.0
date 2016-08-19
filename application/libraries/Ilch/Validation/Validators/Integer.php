<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Integer validation class.
 */
class Integer extends Base
{
    protected $errorKey = 'validation.errors.integer.mustBeInteger';

    public function run()
    {
        $data = $this->data;
        $value = $data->getValue();

        $result = $value === '' || filter_var($value, FILTER_VALIDATE_INT) !== false;

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($data),
        ];
    }
}
