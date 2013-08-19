<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Plugin
{
    public function __construct(Ilch_Controller $controller, Ilch_Layout $layout, Ilch_View $view)
    {
       $this->controller = $controller;
       $this->layout = $layout;
       $this->view = $view;
    }

    public function redirect($modul, $controller, $action)
    {
        if($modul == 'opac')
        {
            $s = '';
        }
        else
        {
             $s = 'admin/';
        }

        header("location: ".BASE_URL.'/'.$s.'index.php?modul='.$modul.'&controller='.$controller.'&action='.$action);
        exit;
    }
}