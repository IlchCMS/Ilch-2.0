<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Plugins;

use Modules\User\Mappers\User as UserMapper;

class AfterDatabaseLoad
{
    /**
     * Checks if a user id was given in the request and sets the user.
     *
     * If no user id is given a default user will be created.
     *
     * @param array $pluginData
     */
    public function __construct(array $pluginData)
    {
        if (!isset($pluginData['config'])) {
            return;
        }

        $userId = null;

        if (isset($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];
        }

        $mapper = new UserMapper();
        $user = $mapper->getUserById($userId);

        \Ilch\Registry::set('user', $user);

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && preg_match("/^[0-9a-zA-Z\/.:]{7,}$/", $_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (preg_match("/^[0-9a-zA-Z\/.:]{7,}$/", $_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip = '128.0.0.1';
        }

        if (empty($_SERVER['PATH_INFO']) OR strpos($_SERVER['PATH_INFO'], 'admin', 1)) {
            $site = '';
        } else {
            $site = $_SERVER['PATH_INFO'];
        }

        function statisticOS($useragent) {
            $osArray = array(
                'Windows XP' => '=Windows NT 5.1|Windows XP=',
                'Windows Vista' => '=Windows NT 6.0|Windows Vista=',
                'Windows 7' => '=Windows NT 6.1|Windows 7=',
                'Windows 8' => '=Windows NT 6.2|Windows 8=',
                'Windows 8.1' => '=Windows NT 6.3|Windows 8.1=',
                'Windows 10' => '=Windows NT 10.0|Windows 10=',
                'Windows 2000' => '=Windows NT 5.0|Windows 2000=',
                'Windows Server 2003\\Windows XP x64' => '=Windows NT 5\.2|Windows Server 2003|Windows XP x64=',
                'Windows NT' => '=Windows NT 4|WinNT4=',
                'Windows 98' => '=Windows 98=',
                'Windows 95' => '=Windows 95=',
                'Android' => '=Android=',
                'Linux' => '=Linux|Ubuntu|X11=',
                'SunOs' => '=SunOS=',
                'iPhone' => '=iPhone=',
                'iPad' => '=iPad=',
                'Mac OS' => '=Mac OS X=',
                'Macintosh' => '=Mac_PowerPC|Macintosh='
            );

            foreach ($osArray as $os => $regex) {
                if (preg_match($regex, $useragent)) {
                    return $os;
                }
            }

            return 0;
        }
        $os = statisticOS($_SERVER['HTTP_USER_AGENT']);

        function statisticBrowser($useragent) {
            if (preg_match("=Firefox/([\.a-zA-Z0-9]*)=", $useragent, $browser)) {
                return ("Firefox " . $browser[1]);
            } elseif (preg_match("=MSIE ([0-9]{1,2})\.[0-9]{1,2}=", $useragent, $browser)) {
                return "Internet Explorer " . $browser[1];
            } elseif (preg_match("=rv:([0-9]{1,2})\.[0-9]{1,2}=", $useragent, $browser)) {
                return "Internet Explorer " . $browser[1];
            } elseif (preg_match("=Opera[/ ]([0-9\.]+)=", $useragent, $browser)) {
                return "Opera " . $browser[1];
            } elseif (preg_match("=OPR\/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return "Opera " . $browser[1];
            } elseif (preg_match("=Edge/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return "Edge " . $browser[1];
            } elseif (preg_match("=Chrome/([0-9\.]*)=", $useragent, $browser)) {
                $tmp = explode('.', $browser[1]);
                if (count($tmp) > 2) {
                    $browser[1] = $tmp[0] . '.' . $tmp[1];
                }
                return "Chrome " . $browser[1];
            } elseif (preg_match('=Safari/=', $useragent)) {
                if (preg_match('=Version/([\.0-9]*)=', $useragent, $browser)) {
                    $version = ' ' . $browser[1];
                } else {
                    $version = '';
                }
                return "Safari" . $version;
            } elseif (preg_match("=Konqueror=", $useragent)) {
                return "Konqueror";
            } elseif (preg_match("=Netscape|Navigator=", $useragent)) {
                return "Netscape";
            } else {
                return 0;
            }
        }
        $browser = statisticBrowser($_SERVER['HTTP_USER_AGENT']);

        if (empty($_SERVER["HTTP_REFERER"])) {
            $referer = '';
        }  else {
            $referer = $_SERVER["HTTP_REFERER"];
        }

        $lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);

        $statisticMapper = new \Modules\Statistic\Mappers\Statistic();
        $statisticMapper->saveVisit(array('user_id' => $userId, 'site' => $site, 'referer' => $referer, 'os' => $os, 'browser' => $browser, 'ip' => $ip, 'lang' => $lang));

        if ($pluginData['request']->getParam('language')) {
            $_SESSION['language'] = $pluginData['request']->getParam('language');
        }

        if ($pluginData['request']->getParam('ilch_layout')) {
            $_SESSION['layout'] = $pluginData['request']->getParam('ilch_layout');
        }

        $pluginData['translator']->setLocale($pluginData['config']->get('locale'));

        if (!empty($_SESSION['language'])) {
            $pluginData['translator']->setLocale($_SESSION['language']);
        }
        
    }
}
