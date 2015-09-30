<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation\Validators;

/**
 * Email validation class
 */
class Email extends Base
{
    protected $errorKey = 'validation.errors.email.noValidEmail';

    public function run()
    {
        $value = $this->data->getValue();

        if (empty($value)) {
            $result = true;
        } else {
            $result = (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
        }

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($this->data),
        ];
    }
}
