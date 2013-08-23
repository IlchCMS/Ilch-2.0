<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

abstract class Ilch_Design_Abstract
{
    /**
     * Gets the base url.
     *
     * @param sting $url
     * @return string
     */
    public function baseUrl($url = '')
    {
        return BASE_URL.'/'.$url;
    }

    /**
     * Gets the static url.
     *
     * @param string $url
     * @return string
     */
    public function staticUrl($url = '')
    {
        if(empty($url))
        {
            return STATIC_URL;
        }

        return STATIC_URL.'/'.$url;
    }

    /**
     * Escape the given string.
     *
     * @param string $string
     * @return type
     */
    function escape($string)
    {
        return htmlspecialchars($string);
    }

    /**
     * Creates a full url for the given parts.
     *
     * @param string $modul
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return string
     */
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

            $s = '';

            return BASE_URL.'/'.$s.'index.php?modul='.$modul.'&controller='.$controller.'&action='.$action.$pars;
        }
    }

    /**
     * Gets the page loading time in microsecond.
     *
     * @return float
     */
    public function loadTime()
    {
        $startTime = Ilch_Registry::get('startTime');
        return microtime(true) - $startTime;
    }

    /**
     * Gets the page queries.
     *
     * @return integer
     */
    public function queryCount()
    {
        $db = Ilch_Registry::get('db');
        return $db->queryCount();
    }

    /**
     * Limit the given string to the given length. 
     *
     * @param string $str
     * @param integer $length
     * @return string
     */
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