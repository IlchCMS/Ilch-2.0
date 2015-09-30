<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation\Validators;

/**
 * Required validation class
 */
class Required extends Base
{
    protected $errorKey = 'validation.errors.required.fieldIsRequired';

    public function run()
    {
        $data = $this->data;

        $value = $data->getValue();
        $value = is_string($value) ? trim($value) : $value;

        $result = !($value === null || $value === [] || $value === '');

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($data),
        ];
    }
}
