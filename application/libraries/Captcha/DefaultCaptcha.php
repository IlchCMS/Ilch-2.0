<?php
/**
 * @copyright Ilch 2
 * @since 2.1.43
 */

namespace Captcha;

class DefaultCaptcha
{

    /**
     * The Form.
     *
     * @var string
     */
    protected $form = '';

    /**
     * Start Default Capcha.
     *
     * @return this
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * Gets the Form.
     *
     * @return string
     */
    public function getForm(): string
    {
        return $this->form;
    }
    /**
     * Sets the Form.
     *
     * @param string $form
     * @return $this
     */
    public function setForm(string $form): GoogleCaptcha
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get Default Capcha.
     *
     * @param \Ilch\View $view
     * @param string|null $action
     * @param float $score
     * @return bool
     */
    public function getCaptcha(\Ilch\View $view)
    {
        $str = '<div class="form-group ' . ($view->validation()->hasError('captcha') ? 'has-error' : '') . '">
            <label class="col-lg-2 control-label">
                ' . $view->getTrans('captcha') . '
            </label>
            <div class="col-lg-8">
                ' . $view->getCaptchaField() . '
            </div>
        </div>
        <div class="form-group ' . ($view->validation()->hasError('captcha') ? 'has-error' : '') . '">
            <div class="col-lg-offset-2 col-lg-8 input-group captcha">
                <input type="text"
                       class="form-control"
                       id="captcha-form"
                       name="captcha"
                       autocomplete="off"
                       placeholder="' . $view->getTrans('captcha') . '" />
                <span class="input-group-addon">
                    <a href="javascript:void(0)" onclick="
                        document.getElementById(\'captcha\').src=\'' . $view->getUrl() . '/application/libraries/Captcha/Captcha.php?\'+Math.random();
                        document.getElementById(\'captcha-form\').focus();"
                        id="change-image">
                        <i class="fa fa-refresh"></i>
                    </a>
                </span>
            </div>
        </div>';
        
        return $str;
    }

    /**
     * Validate Google Capcha.
     *
     * @param string $token
     * @param string|null $action
     * @param float $score
     * @return bool
     */
    public function validate($token, $sessionKey = 'captcha')
    {
        $result = false;
        if (isset($_SESSION[$sessionKey])) {
            $result = ($token === $_SESSION[$sessionKey]);
            unset($_SESSION['captcha']);
        }
        return $result;
    }
}
