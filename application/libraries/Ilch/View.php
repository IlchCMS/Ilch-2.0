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
    public function load($file, $data = [])
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
                    <button type="submit" class="btn btn-default" name="save'.$nameKey.'" value="save">
                        '.$this->getTrans($saveKey).'
                    </button>';

        if (!empty($deleteKey)) {
            $html .= '<button type="submit" class="delete_button pull-right btn" name="delete" value="delete">
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
    public function getListBar($actions = [], $name = '')
    {
        $html = '<div class="content_savebox">
                    <input type="hidden" class="content_savebox_hidden" name="action" value="" />
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">'.
                                $this->getTrans('selected').' <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu listChooser" role="menu">';
                                foreach ($actions as $key => $name) {
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
        $html = '<a href="'.$this->getUrl($url).'" title="'.$this->getTrans('edit').'"><span class="fa fa-edit text-success"></span></a>';

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
        $html = '<a href="'.$this->getUrl($url, null, true).'" title="'.$this->getTrans('delete').'"><span class="fa fa-trash-o text-danger"></span></a>';

        return $html;
    }

    /**
     * Gets checkbox for check delete.
     *
     * @param string $id
     * @return integer
     */
    public function getDeleteCheckbox($name, $id)
    {
        return '<input type="checkbox" name="'.$name.'[]" value="'.$id.'" />';
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

    /**
     * Returns the input data from the last request.
     *
     * @param string $key     Array key
     * @param string $default Default value if key not found
     *
     * @return mixed
     */
    public function originalInput($key = null, $default = '')
    {
        return $this->getRequest()->getOldInput($key, $default);
    }

    /**
     * Returns the validation errors from the last request.
     *
     * @return \Ilch\Validation\ErrorBag
     */
    public function validation()
    {
        return $this->getRequest()->getErrors();
    }
}
