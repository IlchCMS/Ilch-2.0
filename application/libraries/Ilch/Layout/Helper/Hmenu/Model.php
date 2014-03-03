<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Hmenu;
defined('ACCESS') or die('no direct access');

class Model
{
    /**
     * @var array
     */
    protected $_data;

    /**
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->_layout = $layout;
    }

    /**
     * Adds breadcrumb to hnav.
     *
     * @param string $key
     * @param string $value
     * @return \Ilch\Layout\Helper\Hmenu\Model
     */
    public function add($key, $value = '')
    {
        $this->_data[$key] = $value;

        return $this;
    }

    /**
     * Gets hnav string representation.
     *
     * @return string
     */
    public function __toString()
    {
        if (empty($this->_data)) {
            return '';
        }

        $html = '<a href="'.$this->_layout->getUrl().'">Start</a>';

        foreach ($this->_data as $key => $value) {
            if (empty($value)) {
                $html .= $this->_layout->escape($key);
            } else {
                $html .= ' &rArr; <a href="'.$this->_layout->getUrl($value).'">'.$this->_layout->escape($key).'</a>';
            }
        }

        return $html;
    }
}
