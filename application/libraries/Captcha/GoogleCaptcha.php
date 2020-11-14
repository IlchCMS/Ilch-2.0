<?php
namespace Captcha;

class GoogleCaptcha
{

    protected $settings = [
        "apikey" => "", // Get yours today from https://www.google.com/recaptcha/admin/create
        "secretkey" => "",
    ];

    public function __construct($apikey = null, $secretkey = null) {
        // if params were passed as array
        if (is_array($apikey)) {
            foreach ($apikey as $key => $val) {
                $$key = $val;
            }
        }
        
        if (!$apikey) {
            return null;
        }

        $this->settings["apikey"] = $apikey;
        $this->settings["secretkey"] = $secretkey;
    }
    
    public function getCaptcha($info = null, $form = null, $action = null, $actionTrans = 'saveButton') {
        $str = '<div class="content_savebox">';
        $str .= '<button type="submit" class="save_button btn btn-default" name="'.$action.'" value="save">'.$actionTrans.'</button>';
        if ($info && $info != false) {
            if ($info == true) {
                $str .= '<p style="font-size: 0.7em;" class="text-muted ">This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</p>';
            } else {
                $str .= $info;
            }
            $str .= '<style>.grecaptcha-badge {
               visibility: hidden;
            }</style>';
        }
        $str .= '<script async src="https://www.google.com/recaptcha/api.js?render='.$this->settings["apikey"].'"></script>
        <script>
            $(\''.$form.'\').submit(function(event) {
                event.preventDefault();';

        $str .= 'grecaptcha.ready(function() {
                    grecaptcha.execute(\''.$this->settings["apikey"].'\', {action: \''.$action.'\'}).then(function(token) {
                        $(\''.$form.'\').prepend(\'<input type="hidden" name="token" value="\' + token + \'">\');
                        $(\''.$form.'\').prepend(\'<input type="hidden" name="action" value="'.$action.'">\');
                        $(\''.$form.'\').unbind(\'submit\').submit();
                    });;
                });
            });
        </script>';
        $str .= '</div>';
        
        return $str;
    }
    
    public function validate($token = null, $action = null)
    {
        $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$this->settings["secretkey"].'&response='.$token);
        $recaptcha = json_decode($recaptcha);

        if ($recaptcha->success == true && $recaptcha->score >= 0.5 && $recaptcha->action == $action) {
           return true;
        } else {
           return false;
        }
    }
}
