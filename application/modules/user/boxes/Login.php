<?php
/**
 * Login-Box: Bereitet Daten für die View auf und integriert (optional) reCAPTCHA
 *
 * Zweck:
 *  - Liefert Provider, Registrierungs-Flag, Redirect-URL.
 *  - Setzt Captcha-Variablen analog zum Gästebuch/Login-Controller:
 *    - captchaNeeded (bool)
 *    - googlecaptcha (\Captcha\GoogleCaptcha) ODER defaultcaptcha (\Captcha\DefaultCaptcha)
 *
 * Sicherheit:
 *  - Es werden keine Secrets in die View geleakt.
 *  - Site-Key kommt aus der Config (captcha_apikey); Secret bleibt serverseitig.
 *  - Die eigentliche Token-Prüfung erfolgt serverseitig über 'grecaptcha:saveLogin'.
 */

namespace Modules\User\Boxes;

use Ilch\Accesses;
use Modules\User\Mappers\AuthProvider;
use Captcha\GoogleCaptcha;
use Captcha\DefaultCaptcha;

class Login extends \Ilch\Box
{
    public function render()
    {
        $authProvider = new AuthProvider();

        $redirectUrl = $_SESSION['redirect'] ?? $this->getRouter()->getQuery();

        if ($this->getUser()) {
            $access = new Accesses($this->getRequest());
            $this->getView()->set('userAccesses', $access->hasAccess('Admin'));
        }

        $captchaBenoetigt = function_exists('captchaNeeded') ? captchaNeeded() : true;
        $this->getView()->set('captchaNeeded', $captchaBenoetigt);

        if ($captchaBenoetigt) {
            $mode = (int)$this->getConfig()->get('captcha'); // 2 oder 3 = Google
            if (in_array($mode, [2, 3], true)) {
                $this->getView()->set('googlecaptcha', new \Captcha\GoogleCaptcha(
                    $this->getConfig()->get('captcha_apikey'),
                    null,
                    $mode
                ));
            } else {
                $this->getView()->set('defaultcaptcha', new \Captcha\DefaultCaptcha());
            }
        }

        $this->getView()->setArray([
            'providers'     => $authProvider->getProviders(),
            'regist_accept' => $this->getConfig()->get('regist_accept'),
            'redirectUrl'   => $redirectUrl,
        ]);
    }
}
