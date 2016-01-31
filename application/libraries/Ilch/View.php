<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

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

        if (is_file($viewScript)) {
            include $viewScript;
        }

        return ob_get_clean();
    }

    /**
     * Loads a view file.
     *
     * @param string $url
     * @param mixed[] $data
     */
    public function load($file, $data = array())
    {
        $request = $this->getRequest();
        $view = new \Ilch\View($request,
            $this->getTranslator(),
            $this->getRouter());
        $view->setArray($data);

        echo $view->loadScript(APPLICATION_PATH.'/modules/'.$request->getModuleName().'/views/'.$file);
    }

    /**
     * Gets the save bar html.
     *
     * @param string $saveKey
     * @param string $deleteKey
     * @return string
     */
    public function getSaveBar($saveKey = 'saveButton', $nameKey = null, $deleteKey = '')
    {
        $html = '<div class="content_savebox">
                    <button value="save" type="submit" name="save'.$nameKey.'" class="btn">
                        '.$this->getTrans($saveKey).'
                    </button>';

        if (!empty($deleteKey)) {
            $html .= '<button value="delete" type="submit" name="delete" class="delete_button pull-right btn">
                        '.$this->getTrans($deleteKey).'
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
                                    $html .= '<li><a href="#" data-hiddenkey="'.$key.'">'.$this->getTrans($name).'</a></li>';
                                }
        $html .= '</ul></div></div>';

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
        $html = '<a href="'.$this->getUrl($url).'">
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
        $html = '<a class="delete_button" href="'.$this->getUrl($url, null, true).'">
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
