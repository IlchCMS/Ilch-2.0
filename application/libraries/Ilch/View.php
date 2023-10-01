<?php
/**
 * @copyright Ilch 2
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
     * @param string $file
     * @param mixed[] $data
     */
    public function load($file, $data = [])
    {
        $request = $this->getRequest();
        $view = new \Ilch\View(
            $request,
            $this->getTranslator(),
            $this->getRouter()
        );
        $view->setArray($data);

        echo $view->loadScript(APPLICATION_PATH.'/modules/'.$request->getModuleName().'/views/'.$file);
    }

    /**
     * Gets the save bar html.
     *
     * @param string $saveKey
     * @param string|null $nameKey
     * @param string $deleteKey
     * @return string
     */
    public function getSaveBar($saveKey = 'saveButton', $nameKey = null, $deleteKey = '')
    {
        $html = '<div class="content_savebox">
                    <button type="submit" class="save_button btn btn-outline-secondary" name="save'.$nameKey.'" value="save">
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
     * @return string
     */
    public function getListBar($actions = [])
    {
        $html = '<div class="content_savebox">
                    <input type="hidden" class="content_savebox_hidden" name="action" value="" />
                        <div class="dropdown dropup">
                            <button type="button" class="btn btn-outline-secondary dropdown-bs-toggle" data-bs-toggle="dropdown">'.
                                $this->getTrans('selected').' <span class="caret"><i class="bi bi-caret-down"></i></span>
                            </button>
                            <ul class="dropdown-menu listChooser" role="menu">';
        foreach ($actions as $key => $name) {
            $html .= '<li><a href="#" class="dropdown-item" data-hiddenkey="'.$key.'" id="'.$key.'">'.$this->getTrans($name).'</a></li>';
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
        return '<a href="'.$this->getUrl($url).'" title="'.$this->getTrans('edit').'"><span class="fa fa-edit text-success"></span></a>';
    }

    /**
     * Gets the delete icon html.
     *
     * @param array $url
     * @return string
     */
    public function getDeleteIcon($url)
    {
        return '<a href="'.$this->getUrl($url, null, true).'" title="'.$this->getTrans('delete').'" class="delete_button"><span class="fa fa-trash-o text-danger"></span></a>';
    }

    /**
     * Gets checkbox for check delete.
     *
     * @param string $name
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
