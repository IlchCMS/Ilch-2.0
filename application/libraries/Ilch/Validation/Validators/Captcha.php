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
    protected $sessionKey = 'captcha';

    public function run()
    {
        $result = false;
        
        if (isset($_SESSION[$this->sessionKey])) {
            $result = $this->data->getValue() == $_SESSION[$this->sessionKey];
        }

        return [
            'result' => $result,
            'error_key' => $this->getErrorKey($this->data),
        ];
    }
}
