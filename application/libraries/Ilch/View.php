<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class View extends Design\Base
{
    /**
     * Loads a view script.
     *
     * @param  string $viewScript
     * @return string
     */
    public function loadScript($viewScript)
    {
        ob_start();

        if (file_exists($viewScript)) {
            include $viewScript;
        }

        return ob_get_clean();
    }

    /**
     * Gets the save bar html.
     *
     * @param string $saveKey
     * @param string $deleteKey
     * @return string
     */
    public function getSaveBar($saveKey = 'saveButton', $deleteKey = '')
    {
        $html = '<div class="content_savebox">
                    <button value="save" type="submit" name="save" class="btn">
                        '.$this->trans($saveKey).'
                    </button>';

        if (!empty($deleteKey)) {
            $html .= '<button value="delete" type="submit" name="delete" class="delete_button pull-right btn">
                        '.$this->trans($deleteKey).'
                       </button>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Gets the list bar html.
     *
     * @param array $actions
     * @param string $name
     * @return string
     */
    public function getListBar($actions = array(), $name = '')
    {
        $html = '<div class="content_savebox">
                    <input class="content_savebox_hidden" name="action" type="hidden" value="" />
                    <div class="btn-group dropup">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        markierte... <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu listChooser" role="menu">';
        
        foreach($actions as $key => $name) {
            $html .= '<li><a href="#" data-hiddenkey="'.$key.'">'.$this->trans($name).'</a></li>';
        }

        $html .= '</ul></div>';

        return $html; 
    }

    /**
     * Gets the edit icon html.
     *
     * @param array $url
     * @return string
     */
    public function getEditIcon($url)
    {
        $html = '<a href="'.$this->url($url).'">
                    <span class="fa fa-edit text-success"></span>
                 </a>';

        return $html;
    }

    /**
     * Gets the delete icon html.
     *
     * @param array $url
     * @return string
     */
    public function getDeleteIcon($url)
    {
        $html = '<a class="delete_button" href="'.$this->url($url, null, true).'">
                    <span class="fa fa-trash-o text-danger"></span>
                 </a>';

        return $html;
    }

    /**
     * Gets check all checkbox.
     *
     * @param string $childs
     * @return string
     */
    public function getCheckAllCheckbox($childs)
    {
        return '<input type="checkbox" class="check_all" data-childs="'.$childs.'" />';
    }
}
