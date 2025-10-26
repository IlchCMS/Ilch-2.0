<?php
/**
 * @copyright Ilch 2
 * @since 2.1.43
 */

namespace Ilch\Validation\Validators;

use Captcha\GoogleCaptcha;
use Ilch\Registry;

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
    public function run(): Grecaptcha
    {
        $config = Registry::get('config');

        // Sanitize token value to prevent XSS and other attacks
        $token = $this->getValue();
        if (!is_string($token)) {
            $this->setIsValid(false);
            return $this;
        }

        // Trim and validate token length
        $token = trim($token);
        if (empty($token) || strlen($token) > 2000) {
            $this->setIsValid(false);
            return $this;
        }

        $googlecaptcha = new GoogleCaptcha($config->get('captcha_apikey'), $config->get('captcha_seckey'), (int)$config->get('captcha'));
        
        // Use configured score for reCAPTCHA v3
        $score = (float)$config->get('captcha_score', 0.5);
        
        // Validate action from parameter if provided
        $action = $this->getParameter(0);
        if (!empty($action) && !is_string($action)) {
            $action = null;
        }
        
        $this->setIsValid($googlecaptcha->validate($token, $action, $score));

        return $this;
    }
}
