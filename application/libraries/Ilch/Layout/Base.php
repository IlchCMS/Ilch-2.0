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
    protected $_disabled = false;

    /**
     * Holds the view output.
     *
     * @var string
     */
    protected $_content = '';

    /**
     * File of the layout.
     *
     * @var string
     */
    protected $_file;

    /**
     * Set layout disabled flag.
     *
     * @param boolean $disabled
     */
    public function setDisabled($disabled)
    {
        $this->_disabled = $disabled;
    }

    /**
     * Get layout disabled flag.
     *
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->_disabled;
    }

    /**
     * Sets the view output.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * Gets the view output.
     *
     * @param string $content
     */
    public function getContent()
    {
        return $this->_content;
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
            include_once($loadScript);
        }
    }

    /**
     * Sets the file of the layout.
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->_file = $file;
    }

    /**
     * Gets the file of the layout.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->_file;
    }
}
