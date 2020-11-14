<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Captcha validation class.
 */
class Grecaptcha extends Base
{
    /**
     * Default error key for this validator.
     *
     * @var string
     */
    protected $errorKey = 'validation.errors.grecaptcha.wrongCaptcha';

    /**
     * Runs the validation.
     *
     * @return self
     */
    public function run()
    {
        $result = false;
        $config = \Ilch\Registry::get('config');
        $action = $this->getParameter(0) ?? '';

        $googlecaptcha = new \Captcha\GoogleCaptcha($config->get('grecaptcha_apikey'), $config->get('grecaptcha_seckey'));
        $this->setIsValid($googlecaptcha->validate($this->getValue(), $action));

        return $this;
    }
}
