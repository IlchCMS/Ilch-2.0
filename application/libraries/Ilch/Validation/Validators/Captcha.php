<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation\Validators;

/**
 * Captcha validation class
 */
class Captcha extends Base
{
    protected $errorKey = 'validation.errors.captcha.wrongCaptcha';

    public function run()
    {
        $result = $this->data->getValue() == $_SESSION['captcha'];

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($this->data),
        ];
    }
}
