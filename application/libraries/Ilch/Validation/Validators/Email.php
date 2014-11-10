<?php

/**
 * Email validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation\Validators;

class Email extends Base
{
    protected $errorKey = 'validation.errors.email.noValidEmail';

    public function run()
    {
        if (empty($this->data->getValue())) {
            $result = true;
        } else {
            $result = (bool) filter_var($this->data->getValue(), FILTER_VALIDATE_EMAIL);
        }

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($this->data),
        ];
    }
}
