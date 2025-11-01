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
    /**
     * Prüft ein Google reCAPTCHA-Token.
     *
     * Parameter (Regel):
     *  - Parameter 0: Action-Name (z. B. 'saveLogin' oder 'saveGuestbook')
     *  - Parameter 1: Optionaler Score (float), z. B. 0.7. Fallback 0.5.
     *
     * Sicherheit:
     *  - Secret wird nur serverseitig genutzt (kein Leak in die View).
     *  - Zeitüberschreitungen/Fehler behandelt die GoogleCaptcha::validate()-Methode defensiv.
     */
    public function run(): Grecaptcha
    {
        $config = \Ilch\Registry::get('config');

        $siteKey = (string)($config->get('captcha_apikey') ?? '');
        $secret  = $config->get('captcha_seckey');
        $secret  = $secret === null || $secret === '' ? null : (string)$secret;
        $version = (int)($config->get('captcha') ?? 3);

        $googlecaptcha = new \Captcha\GoogleCaptcha([
            'key'     => $siteKey,
            'secret'  => $secret,
            'version' => $version,
            'hide'    => true,
        ]);

        $actionParam = $this->getParameter(0);
        $scoreParam  = $this->getParameter(1);

        $action = is_string($actionParam) && $actionParam !== '' ? $actionParam : null;

        $defaultScore = 0.5;
        $score = (is_numeric($scoreParam)) ? (float)$scoreParam : $defaultScore;

        $token = (string)($this->getValue() ?? '');

        $this->setIsValid($googlecaptcha->validate($token, $action, $score));

        return $this;
    }

}
