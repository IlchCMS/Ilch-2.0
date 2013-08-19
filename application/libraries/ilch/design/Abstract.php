<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

abstract class Ilch_Design_Abstract
{
    public function baseUrl($url = '')
    {
        return BASE_URL.'/'.$url;
    }

    public function staticUrl($url = '')
    {
        if(empty($url))
        {
            return STATIC_URL;
        }

        return STATIC_URL.'/'.$url;
    }

    function escape($string)
    {
        return htmlspecialchars($string);
    }

    public function url($modul = '', $controller = '', $action = '', $params = array())
    {
        if(empty($modul))
        {
            return BASE_URL;
        }
        else
        {
            $s = '';
            $pars = '';
            foreach($params as $key => $val)
            {
                $pars .= '&'.$key.'='.$val;
            }

            if($modul == 'opac')
            {
                $s = '';
            }
            else
            {
                 $s = 'admin/';
            }

            return BASE_URL.'/'.$s.'index.php?modul='.$modul.'&controller='.$controller.'&action='.$action.$pars;
        }
    }

    public function loadTime()
    {
        $startTime = Ilch_Registry::get('startTime');
        return microtime(true) - $startTime;
    }

    public function queryCount()
    {
        $db = Ilch_Registry::get('db');
        return $db->queryCount();
    }


    public function limitString($str, $length)
    {
        if(strlen($str) <= $length)
        {
            return $str;
        }
        else
        {
            return preg_replace("/[^ ]*$/", '', substr($str, 0, $length)).'...';
        }
    }
}