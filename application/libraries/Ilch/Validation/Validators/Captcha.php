<?php
/**
 * @copyright Ilch 2
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
    public function run()
    {
        $defaultcaptcha = new \Captcha\DefaultCaptcha();
        $this->setIsValid($defaultcaptcha->validate($this->getValue(), $this->sessionKey));

        return $this;
    }
}
