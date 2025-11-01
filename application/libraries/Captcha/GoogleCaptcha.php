<?php

/**
 * @copyright Ilch 2
 * @since 2.1.43
 */

namespace Captcha;

// Get your Keys from https://www.google.com/recaptcha/admin/create

use Ilch\Registry;

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
     * Akzeptiert entweder einen String als Site‑Key oder ein assoziatives Options‑Array.
     *
     * @param array{key?:string,secret?:string,version?:int,hide?:bool}|string|null $key Options array or site key
     * @param string|null $secret Explicit secret (wird ignoriert, wenn im Options‑Array gesetzt)
     * @param int|null $version Captcha‑Version (2 oder 3)
     * @param bool|null $hide Hide widget (nur relevant für Version 3)
     */
    public function __construct($key = null, ?string $secret = null, ?int $version = null, ?bool $hide = null)
    {
        if (is_array($key)) {
            $opts = $key;
            if (array_key_exists('key', $opts) && $opts['key'] !== null) {
                $this->setKey((string)$opts['key']);
            }
            if (array_key_exists('secret', $opts)) {
                $this->setSecret($opts['secret'] === null ? null : (string)$opts['secret']);
            }
            if (array_key_exists('version', $opts) && $opts['version'] !== null) {
                $this->setVersion((int)$opts['version']);
            }
            if (array_key_exists('hide', $opts) && $opts['hide'] !== null) {
                $this->setHide((bool)$opts['hide']);
            }
        } else {
            if (isset($key)) {
                $this->setKey((string)$key);
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
        }
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
        if (!$this->getSecret() || $token === '') {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $postFields = http_build_query([
            'secret' => $this->getSecret(),
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $response = curl_exec($ch);
        $curlErr = curl_errno($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErr || !$response || $httpCode >= 400) {
            return false;
        }

        $recaptcha = json_decode($response);
        if (json_last_error() !== JSON_ERROR_NONE || !is_object($recaptcha)) {
            return false;
        }

        $logEntry = [
            'timestamp' => date('c'),
            'ip' => filter_var($_SERVER['REMOTE_ADDR'] ?? '', FILTER_VALIDATE_IP) ?: 'unknown',
            'action' => isset($recaptcha->action) ? (string)$recaptcha->action : 'none',
            'score' => isset($recaptcha->score) ? (float)$recaptcha->score : null,
            'success' => isset($recaptcha->success) ? (bool)$recaptcha->success : false,
            'hostname' => isset($recaptcha->hostname) ? (string)$recaptcha->hostname : 'unknown',
            'errors' => isset($recaptcha->{'error-codes'}) ? (array)$recaptcha->{'error-codes'} : [],
        ];

        $config = Registry::get('config');
        if ((int)$config->get('captcha_logging') === 1) {
            $logsDir = dirname(__DIR__, 3) . '/logs';
            if (!is_dir($logsDir)) {
                @mkdir($logsDir, 0755, true);
            }
            $logPath = $logsDir . '/google_captcha.log';
            @file_put_contents($logPath, json_encode($logEntry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        if ($this->getVersion() === 3) {
            if (empty($recaptcha->success)) {
                return false;
            }
            if (!isset($recaptcha->score) || !is_numeric($recaptcha->score)) {
                return false;
            }
            $respScore = (float)$recaptcha->score;
            $actionOk = $action === null || (isset($recaptcha->action) && $recaptcha->action === $action);
            return $respScore >= $score && $actionOk;
        }

        if ($this->getVersion() === 2) {
            return (bool)($recaptcha->success ?? false);
        }

        return false;
    }
}
