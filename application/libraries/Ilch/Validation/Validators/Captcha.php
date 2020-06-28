<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Captcha validation class.
 */
class Captcha extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.captcha.wrongCaptcha';

    /**
     * The captchas array key in the session.
     *
     * @var string
     */
    protected $sessionKey = 'captcha';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run(): Captcha
    {
        $result = false;

        if (isset($_SESSION[$this->sessionKey])) {
            $result = $this->getValue() == $_SESSION[$this->sessionKey];

            unset($_SESSION['captcha']);
        }

        $this->setIsValid($result);

        return $this;
    }
}
