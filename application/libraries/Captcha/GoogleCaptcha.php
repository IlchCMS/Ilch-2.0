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
     * Get detailed validation result with score and error codes.
     *
     * @param string $token
     * @param string|null $action
     * @param float $score
     * @return array
     */
    public function validateDetailed(string $token, ?string $action = null, float $score = 0.5): array
    {
        $result = [
            'valid' => false,
            'score' => 0.0,
            'action' => null,
            'error_codes' => [],
            'hostname' => null
        ];

        if (!$this->getSecret()) {
            $result['error_codes'][] = 'missing-secret';
            return $result;
        }

        // Use cURL for better error handling and security
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $this->getSecret(),
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Ilch CMS reCAPTCHA Validator');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            $result['error_codes'][] = 'network-error';
            return $result;
        }

        $recaptcha = json_decode($response, true);
        if (!$recaptcha || !isset($recaptcha['success'])) {
            $result['error_codes'][] = 'invalid-response';
            return $result;
        }

        $result['score'] = $recaptcha['score'] ?? 0.0;
        $result['action'] = $recaptcha['action'] ?? null;
        $result['hostname'] = $recaptcha['hostname'] ?? null;
        $result['error_codes'] = $recaptcha['error-codes'] ?? [];

        if ($this->getVersion() === 3) {
            $result['valid'] = ($recaptcha['success'] && 
                               $result['score'] >= $score && 
                               (($action && $result['action'] == $action) || !$action));
        } elseif ($this->getVersion() === 2) {
            $result['valid'] = $recaptcha['success'];
        }

        return $result;
    }

    /**
     * Get reCAPTCHA badge visibility CSS.
     *
     * @return string
     */
    public function getBadgeCSS(): string
    {
        if ($this->getVersion() === 3 && $this->getHide()) {
            return '<style>
                .grecaptcha-badge {
                    visibility: hidden !important;
                }
                .grecaptcha-info {
                    font-size: 0.7em;
                    color: #6c757d;
                    margin-top: 10px;
                }
            </style>';
        }
        return '';
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
            // Escape JavaScript values to prevent XSS
            $escapedKey = htmlspecialchars($this->getKey() ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $escapedFormId = htmlspecialchars($this->getForm(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $escapedAction = htmlspecialchars('save' . $nameKey, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            
            $str .= '<script async src="https://www.google.com/recaptcha/api.js?render=' . urlencode($this->getKey() ?? '') . '"></script>
            <script>
                // Modern reCAPTCHA v3 implementation with better error handling
                document.addEventListener("DOMContentLoaded", function() {
                    const form = document.getElementById("' . $escapedFormId . '");
                    if (!form) return;
                    
                    form.addEventListener("submit", function(event) {
                        event.preventDefault();
                        
                        if (typeof grecaptcha === "undefined") {
                            console.error("reCAPTCHA not loaded");
                            return;
                        }
                        
                        grecaptcha.ready(function() {
                            grecaptcha.execute("' . $escapedKey . '", {
                                action: "' . $escapedAction . '"
                            }).then(function(token) {
                                // Validate token before submission
                                if (!token || token.length > 2000) {
                                    console.error("Invalid reCAPTCHA token");
                                    return;
                                }
                                
                                // Add token and action as hidden inputs
                                const tokenInput = document.createElement("input");
                                tokenInput.type = "hidden";
                                tokenInput.name = "token";
                                tokenInput.value = token;
                                form.appendChild(tokenInput);
                                
                                const actionInput = document.createElement("input");
                                actionInput.type = "hidden";
                                actionInput.name = "action";
                                actionInput.value = "' . $escapedAction . '";
                                form.appendChild(actionInput);
                                
                                // Submit form
                                form.submit();
                            }).catch(function(error) {
                                console.error("reCAPTCHA execution failed:", error);
                                // Fallback: submit without token (will be handled by server validation)
                                form.submit();
                            });
                        });
                    });
                });
            </script>';
        } elseif ($this->getVersion() === 2) {
            // Escape JavaScript values to prevent XSS
            $escapedFormId = htmlspecialchars($this->getForm(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $escapedAction = htmlspecialchars('save' . $nameKey, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            
            $str .= '<script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const form = document.getElementById("' . $escapedFormId . '");
                    if (!form) return;
                    
                    form.addEventListener("submit", function(event) {
                        event.preventDefault();
                        
                        if (typeof grecaptcha === "undefined") {
                            console.error("reCAPTCHA not loaded");
                            return;
                        }
                        
                        const response = grecaptcha.getResponse();
                        if (!response) {
                            alert("Bitte lösen Sie das reCAPTCHA");
                            return;
                        }
                        
                        // Validate response before submission
                        if (response.length > 2000) {
                            console.error("Invalid reCAPTCHA response");
                            return;
                        }
                        
                        // Add token and action as hidden inputs
                        const tokenInput = document.createElement("input");
                        tokenInput.type = "hidden";
                        tokenInput.name = "token";
                        tokenInput.value = response;
                        form.appendChild(tokenInput);
                        
                        const actionInput = document.createElement("input");
                        actionInput.type = "hidden";
                        actionInput.name = "action";
                        actionInput.value = "' . $escapedAction . '";
                        form.appendChild(actionInput);
                        
                        // Submit form
                        form.submit();
                    });
                });
            </script>';
            //g-recaptcha-response
        }

        $str .= '<div class="g-recaptcha"' . ($this->getVersion() === 2 ? 'data-sitekey="' . $this->getKey() . '"' : '') . '>';
        $str .= '</div>';
        $str .= $view->getSaveBar($saveKey, $nameKey);
        
        // Add improved CSS and info text
        $str .= $this->getBadgeCSS();
        
        if ($this->getVersion() === 3) {
            if ($this->getHide()) {
                $str .= '<p class="grecaptcha-info">' . $view->getTrans('grecaptcha_info', 'https://policies.google.com/privacy', 'https://policies.google.com/terms') . '</p>';
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

        // Validate input: token must be non-empty string
        if (empty($token) || !is_string($token)) {
            return false;
        }

        // Sanitize token to prevent XSS and other attacks
        $token = trim($token);
        if (strlen($token) > 2000) {
            return false; // Token is too long
        }

        // Get remote IP with fallback
        $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
        // Sanitize IP address
        if (!empty($remoteIp) && !filter_var($remoteIp, FILTER_VALIDATE_IP)) {
            $remoteIp = '';
        }

        // Use cURL for better error handling and security
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $this->getSecret(),
            'response' => $token,
            'remoteip' => $remoteIp
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Ilch CMS reCAPTCHA Validator');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Check for cURL errors
        if ($response === false || !empty($curlError)) {
            return false;
        }

        if ($httpCode !== 200) {
            return false;
        }

        $recaptcha = json_decode($response, true);
        if (!$recaptcha || !isset($recaptcha['success'])) {
            return false;
        }

        // Verify hostname for additional security
        if (isset($recaptcha['hostname'])) {
            $currentHost = $_SERVER['HTTP_HOST'] ?? '';
            if (!empty($currentHost) && !empty($recaptcha['hostname'])) {
                // Check if hostnames match exactly or if reCAPTCHA hostname is a subdomain
                if ($recaptcha['hostname'] !== $currentHost) {
                    // Check if hostname from reCAPTCHA ends with . + current host
                    $expectedSuffix = '.' . $currentHost;
                    if (substr($recaptcha['hostname'], -strlen($expectedSuffix)) !== $expectedSuffix) {
                        // Hostname mismatch - potential security issue
                        return false;
                    }
                }
            }
        }

        if ($this->getVersion() === 3) {
            // Validate score and action match
            if (!isset($recaptcha['score'])) {
                return false;
            }
            
            if (!is_numeric($recaptcha['score']) || $recaptcha['score'] < $score) {
                return false;
            }
            
            // Validate action if provided
            if ($action !== null && (!isset($recaptcha['action']) || $recaptcha['action'] !== $action)) {
                return false;
            }
            
            return $recaptcha['success'];
        } elseif ($this->getVersion() === 2) {
            return $recaptcha['success'];
        }
        return false;
    }
}
