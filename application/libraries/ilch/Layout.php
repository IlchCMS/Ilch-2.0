<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Layout extends Ilch_Design_Abstract
{
    public function load($file, $noFile = 0)
    {
        if($noFile == 1)
        {
            echo $file;
        }
        elseif(file_exists(APPLICATION_PATH.'/layouts/'.$file.'.php'))
        {
            require_once APPLICATION_PATH.'/layouts/'.$file.'.php';
        }
    }
}