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

        $html = '<div id="breadcrumbs">
                    <div class="breadcrumb-button blue">
                        <span class="breadcrumb-label">
                            <a href="'.$this->_layout->url().'">
                                <i class="fa fa-home"></i>
                            </a>
                        </span>
                        <span class="breadcrumb-arrow">
                            <span></span>
                        </span>
                    </div>';

        foreach ($this->_data as $key => $value) {
            $html .= '<div class="breadcrumb-button"><span class="breadcrumb-label">';

            if (empty($value)) {
                $html .= $this->_layout->escape($key);
            } else {
                $html .= '<a href="'.$this->_layout->url($value).'">'.$this->_layout->escape($key).'</a>';
            }

            $html .=  '</span><span class="breadcrumb-arrow"><span></span></span></div>';
        }

        $html .= '</div>';

        return $html;
    }
}
