<?php

/**
 * @copyright Ilch 2
 * @since 2.1.43
 */

namespace Captcha;

use Ilch\View;

class DefaultCaptcha
{
    /**
     * The Form.
     *
     * @var string
     */
    protected $form = '';

    /**
     * Start Default Captcha.
     *
     * @return $this
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
    public function setForm(string $form): DefaultCaptcha
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get Default Captcha.
     *
     * @param View $view
     * @return string
     */
    /**
     * Rendert das Standard-Captcha mit stabilem Reload (mehrere Instanzen je Seite möglich).
     *
     * Sicherheit/Robustheit:
     * - Eindeutige IDs pro Instanz (kein Konflikt bei mehreren Boxen/Formularen).
     * - Bild-Reload mit Cache-Buster, damit kein Browser-Cache dazwischenfunkt.
     * - Kein Leaken sensibler Infos; nur Client-seitiges Refreshing.
     *
     * @param \Ilch\View $view
     * @return string
     */
    /**
     * Rendert das Standard-Captcha.
     *
     * @param \Ilch\View $view
     * @param string $layout Layoutmodus: 'default' (zweispaltig) oder 'compact' (einspaltig, ideal für Boxen)
     * @return string
     */
    public function getCaptcha(View $view, string $layout = 'default'): string
    {
        // Eingabe validieren (Whitelist)
        $layout = ($layout === 'compact') ? 'compact' : 'default';

        $uid     = bin2hex(random_bytes(4));           // eindeutige IDs je Instanz
        $wrapId  = 'captcha_wrap_'.$uid;
        $inputId = 'captcha_input_'.$uid;
        $imgUrl  = $view->getUrl().'/application/libraries/Captcha/Captcha.php';
        $hasErr  = $view->validation()->hasError('captcha') ? ' has-error' : '';

        // Gemeinsamer Refresh-Button (verhindert Seiten-Reload)
        $refreshBtn = '
        <button type="button"
                class="btn btn-outline-secondary p-1"
                aria-label="'.$view->getTrans('captcha').' '.$view->getTrans('reload').'"
                title="'.$view->getTrans('captcha').' '.$view->getTrans('reload').'"
                onclick="(function(){
                    var wrap=document.getElementById(\''.$wrapId.'\'); if(!wrap) return;
                    var img=wrap.querySelector(\'img\'); if(img){ img.src=\''.$imgUrl.'?_=\'+Date.now(); }
                    var inp=document.getElementById(\''.$inputId.'\'); if(inp){ inp.focus(); }
                })();">
            <i class="fa-solid fa-arrows-rotate"></i>
        </button>';

        // Kompakte Styles nur auf diese Instanz scopen (kein Einfluss auf andere Seitenbereiche)
        $scopedCss = '
        <style>
            #'.$wrapId.' img { max-width: 100%; height: auto; }
            #'.$wrapId.' .input-group-text { height: auto; }
            /* kompaktere Abstände in der Box */
            #'.$wrapId.'.ilch-captcha--compact .mb-15 { margin-bottom: .5rem; }
            /* bei sehr schmalen Boxen alles einspaltig */
            #'.$wrapId.'.ilch-captcha--compact .col-xl-2,
            #'.$wrapId.'.ilch-captcha--compact .col-xl-8,
            #'.$wrapId.'.ilch-captcha--compact .offset-xl-2 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
                margin-left: 0 !important;
            }
        </style>';

        if ($layout === 'compact') {
            // Einspaltiges, box-freundliches Layout: Bild oben vollbreit, darunter Eingabe + Button
            return $scopedCss.'
        <div id="'.$wrapId.'" class="ilch-captcha ilch-captcha--compact">
            <div class="row mb-15'.$hasErr.'">
                <div class="col-12">
                    <label class="form-label">'.$view->getTrans('captcha').'</label>
                    '.$view->getCaptchaField().'
                </div>
            </div>
            <div class="row mb-15'.$hasErr.'">
                <div class="col-12">
                    <div class="input-group">
                        <input type="text"
                               class="form-control"
                               id="'.$inputId.'"
                               name="captcha"
                               autocomplete="off"
                               placeholder="'.$view->getTrans('captcha').'" />
                        <span class="input-group-text p-0">
                            '.$refreshBtn.'
                        </span>
                    </div>
                </div>
            </div>
        </div>';
        }

        // Standard-Layout (zweispaltig) – bisheriges Verhalten
        return '
        <div id="'.$wrapId.'" class="row mb-15'.$hasErr.' ilch-captcha">
            <label class="col-xl-2 col-form-label">'.$view->getTrans('captcha').'</label>
            <div class="col-xl-8">'.$view->getCaptchaField().'</div>
        </div>
        <div class="row mb-15'.$hasErr.' ilch-captcha">
            <div class="offset-xl-2 col-xl-8">
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           id="'.$inputId.'"
                           name="captcha"
                           autocomplete="off"
                           placeholder="'.$view->getTrans('captcha').'" />
                    <span class="input-group-text p-0">
                        '.$refreshBtn.'
                    </span>
                </div>
            </div>
        </div>';
    }




    /**
     * Validate Google Captcha.
     *
     * @param string $token
     * @param string $sessionKey
     * @return bool
     */
    public function validate(string $token, string $sessionKey = 'captcha'): bool
    {
        $result = false;
        if (isset($_SESSION[$sessionKey])) {
            $result = ($token === $_SESSION[$sessionKey]);
            unset($_SESSION['captcha']);
        }
        return $result;
    }
}
