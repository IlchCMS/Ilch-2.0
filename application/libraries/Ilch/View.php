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
     * @param string $viewScript
     * @return string
     */
    public function loadScript(string $viewScript): string
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
     * @param array $data
     */
    public function load(string $file, array $data = [])
    {
        $request = $this->getRequest();
        $view = new \Ilch\View(
            $request,
            $this->getTranslator(),
            $this->getRouter()
        );
        $view->setArray($data);

        echo $view->loadScript(APPLICATION_PATH . '/modules/' . $request->getModuleName() . '/views/' . $file);
    }

    /**
     * Gets the save bar html.
     *
     * @param string $saveKey
     * @param string|null $nameKey
     * @param string $deleteKey
     * @return string
     */
    public function getSaveBar(string $saveKey = 'saveButton', string $nameKey = null, string $deleteKey = ''): string
    {
        $html = '<div class="content_savebox">
                    <button type="submit" class="save_button btn btn-secondary" name="save' . $nameKey . '" value="save">
                        ' . $this->getTrans($saveKey) . '
                    </button>';

        if (!empty($deleteKey)) {
            $html .= '<button type="submit" class="delete_button btn float-end" name="delete" value="delete">
                        ' . $this->getTrans($deleteKey) . '
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
    public function getListBar(array $actions = []): string
    {
        $html = '<div class="content_savebox">
                    <input type="hidden" class="content_savebox_hidden" name="action" value="" />
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">' . $this->getTrans('selected') . '</button>
                            <ul class="dropdown-menu listChooser" role="menu">';
        foreach ($actions as $key => $name) {
            $html .= '<li><a href="#" class="dropdown-item" data-hiddenkey="' . $key . '" id="' . $key . '">' . $this->getTrans($name) . '</a></li>';
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
    public function getEditIcon(array $url): string
    {
        return '<a href="' . $this->getUrl($url) . '" title="' . $this->getTrans('edit') . '"><span class="fa-solid fa-pen-to-square text-success"></span></a>';
    }

    /**
     * Gets the delete icon html.
     *
     * @param array $url
     * @return string
     */
    public function getDeleteIcon(array $url): string
    {
        return '<a href="' . $this->getUrl($url, null, true) . '" title="' . $this->getTrans('delete') . '" class="delete_button"><span class="fa-regular fa-trash-can text-danger"></span></a>';
    }

    /**
     * Gets checkbox for check delete.
     *
     * @param string $name
     * @param string $id
     * @return string
     */
    public function getDeleteCheckbox(string $name, string $id): string
    {
        return '<input type="checkbox" name="' . $name . '[]" value="' . $id . '" />';
    }

    /**
     * Gets check all checkbox.
     *
     * @param string $childs
     * @return string
     */
    public function getCheckAllCheckbox(string $childs): string
    {
        return '<input type="checkbox" class="check_all" data-childs="' . $childs . '" />';
    }

    /**
     * Returns the input data from the last request.
     *
     * @param string|null $key     Array key
     * @param mixed $default Default value if key not found
     *
     * @return mixed
     */
    public function originalInput(?string $key = null, $default = '')
    {
        return $this->getRequest()->getOldInput($key, $default);
    }

    /**
     * Returns the validation errors from the last request.
     *
     * @return \Ilch\Validation\ErrorBag
     */
    public function validation(): Validation\ErrorBag
    {
        return $this->getRequest()->getErrors();
    }
}
