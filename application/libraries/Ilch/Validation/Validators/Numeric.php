<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Numeric validation class.
 */
class Numeric extends Base
{
    protected $errorKey = 'validation.errors.numeric.mustBeNumeric';

    public function run()
    {
        $data = $this->data;
        $value = $data->getValue();

        $result = $value === '' || is_numeric($value);

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($data),
        ];
    }
}
