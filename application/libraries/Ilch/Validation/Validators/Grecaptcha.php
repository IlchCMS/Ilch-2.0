<?php
/**
 * @copyright Ilch 2
 * @since 2.1.43
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
        $config = \Ilch\Registry::get('config');

        $googlecaptcha = new \Captcha\GoogleCaptcha($config->get('captcha_apikey'), $config->get('captcha_seckey'), (int)$config->get('captcha'));
        $this->setIsValid($googlecaptcha->validate($this->getValue(), $this->getParameter(0)));

        return $this;
    }
}
