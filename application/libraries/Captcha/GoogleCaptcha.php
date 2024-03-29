<?php

/**
 * @copyright Ilch 2
 * @since 2.1.43
 */

namespace Captcha;

// Get your Keys from https://www.google.com/recaptcha/admin/create

class GoogleCaptcha
{
    /**
     * The Version.
     *
     * @var int
     */
    protected $version = 3;
    /**
     * The Hide.
     *
     * @var bool
     */
    protected $hide = true;
    /**
     * The Form.
     *
     * @var string
     */
    protected $form = '';
    /**
     * The Key.
     *
     * @var string|null
     */
    protected $key;
    /**
     * The Secret.
     *
     * @var string|null
     */
    protected $secret;

    /**
     * Start Google Captcha.
     *
     * @param array|string|null $key
     * @param string|null $secret
     * @param int|null $version
     * @param bool|null $hide
     */
    public function __construct($key = null, ?string $secret = null, ?int $version = null, ?bool $hide = null)
    {
        // if params were passed as array
        if (is_array($key)) {
            $keyArray = $key;
            foreach ($keyArray as $arrayKey => $arrayVal) {
                if (isset($$arrayKey)) {
                    $$arrayKey = $arrayVal;
                }
            }
        }

        if (isset($key)) {
            $this->setKey($key);
        }
        if (isset($secret)) {
            $this->setSecret($secret);
        }
        if (isset($version)) {
            $this->setVersion($version);
        }
        if (isset($hide)) {
            $this->setHide($hide);
        }
        return $this;
    }

    /**
     * Gets the Version.
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Sets the Version.
     *
     * @param int $version
     * @return $this
     */
    public function setVersion(int $version): GoogleCaptcha
    {
        if ($version === 3 || $version === 2) {
            $this->version = $version;
        }

        return $this;
    }

    /**
     * Gets the Hide.
     *
     * @return bool
     */
    public function getHide(): bool
    {
        return $this->hide;
    }

    /**
     * Sets the Hide.
     *
     * @param bool $hide
     * @return $this
     */
    public function setHide(bool $hide): GoogleCaptcha
    {
        $this->hide = $hide;
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
     * Gets the Key.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the Key.
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): GoogleCaptcha
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Gets the Secret.
     *
     * @return string|null
     */
    public function getSecret(): ?string
    {
        return $this->secret;
    }
    /**
     * Sets the Secret.
     *
     * @param string $secret
     * @return $this
     */
    public function setSecret(string $secret): GoogleCaptcha
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * Get Google Captcha.
     *
     * @param \Ilch\View $view
     * @param string $saveKey
     * @param string|null $nameKey
     * @return string
     */
    public function getCaptcha(\Ilch\View $view, string $saveKey = 'saveButton', ?string $nameKey = 'grecaptcha'): string
    {
        $nameKey = $nameKey ?? '';
        if (!$this->getForm()) {
            $this->setForm('form' . $nameKey);
        }

        $str = '';
        if ($this->getVersion() === 3) {
            $str .= '<script async src="https://www.google.com/recaptcha/api.js?render=' . urlencode($this->getKey() ?? '') . '"></script>
            <script>
                $(\'#' . $this->getForm() . '\').submit(function(event) {
                    event.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute(\'' . $this->getKey() . '\', {action: \'save' . $nameKey . '\'}).then(function(token) {
                            $(\'#' . $this->getForm() . '\').prepend(\'<input type="hidden" name="token" value="\' + token + \'">\');
                            $(\'#' . $this->getForm() . '\').prepend(\'<input type="hidden" name="action" value="save' . $nameKey . '">\');
                            $(\'#' . $this->getForm() . '\').unbind(\'submit\').submit();
                        });;
                    });
                });
            </script>';
        } elseif ($this->getVersion() === 2) {
            $str .= '<script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <script>
                $(\'#' . $this->getForm() . '\').submit(function(event) {
                    event.preventDefault();
                    $(\'#' . $this->getForm() . '\').prepend(\'<input type="hidden" name="token" value="\' + grecaptcha.getResponse() + \'">\');
                    $(\'#' . $this->getForm() . '\').prepend(\'<input type="hidden" name="action" value="save' . $nameKey . '">\');
                    $(\'#' . $this->getForm() . '\').unbind(\'submit\').submit();
                });
            </script>';
            //g-recaptcha-response
        }

        $str .= '<div class="g-recaptcha"' . ($this->getVersion() === 2 ? 'data-sitekey="' . $this->getKey() . '"' : '') . '>';
        $str .= '</div>';
        $str .= $view->getSaveBar($saveKey, $nameKey);
        if ($this->getVersion() === 3) {
            if ($this->getHide()) {
                $str .= '<p style="font-size: 0.7em;" class="text-muted grecaptcha-info">' . $view->getTrans('grecaptcha_info', 'https://policies.google.com/privacy', 'https://policies.google.com/terms') . '</p>';
                $str .= '<style>.grecaptcha-badge {
                   visibility: hidden;
                }</style>';
            }
        }

        return $str;
    }

    /**
     * Validate Google Captcha.
     *
     * @param string $token
     * @param string|null $action
     * @param float $score
     * @return bool
     */
    public function validate(string $token, ?string $action = null, float $score = 0.5): bool
    {
        if (!$this->getSecret()) {
            return false;
        }

        $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->getSecret()) . '&response=' . urlencode($token));
        $recaptcha = json_decode($recaptcha);
        if (!$recaptcha) {
            return false;
        }

        if ($this->getVersion() === 3) {
            return ($recaptcha->success && $recaptcha->score >= $score && (($action && $recaptcha->action == $action) || !$action));
        } elseif ($this->getVersion() === 2) {
            return $recaptcha->success;
        }
        return false;
    }
}
