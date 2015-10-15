<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout;

defined('ACCESS') or die('no direct access');

abstract class Base extends \Ilch\Design\Base
{
    /**
     * Loads layout helper.
     *
     * @param string $name
     * @param mixed $args
     */
    public function __call($name, $args)
    {
        $layout = $this->getHelper($name, 'layout');

        if(!empty($layout)) {
            return $layout->$name($args);
        }
    }

    /**
     * Defines if layout is disabled.
     *
     * @var boolean
     */
    protected $disabled = false;

    /**
     * Holds the view output.
     *
     * @var string
     */
    protected $content = '';

    /**
     * File of the layout.
     *
     * @var string
     */
    protected $file;

    /**
     * Set layout disabled flag.
     *
     * @param boolean $disabled
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * Get layout disabled flag.
     *
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Sets the view output.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Gets the view output.
     *
     * @param string $content
     */
    public function getContent()
    {
        $html = '';
        $messages = array();

        if(!empty($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
        }

        foreach ($messages as $key => $message) {
            $html = '<div class="alert alert-'.$message['type'].' alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            '.$this->escape($this->getTranslator()->trans($message['text'])).'</div>';
            unset($_SESSION['messages'][$key]);
        }

        return $html.$this->content;
    }

    /**
     * Loads a view script.
     *
     * @param  string $loadScript
     * @return string
     */
    public function loadScript($loadScript)
    {
        if (file_exists($loadScript)) {
            include $loadScript;
        }
    }

    /**
     * Sets the file of the layout.
     *
     * @param string $file
     */
    public function setFile($file, $layoutKey = '')
    {
        $this->layoutKey = $layoutKey;
        $this->file = $file;
    }

    /**
     * Gets the file of the layout.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Gets the key of the layout.
     *
     * @return string
     */
    public function getLayoutKey()
    {
        return $this->layoutKey;
    }
}
