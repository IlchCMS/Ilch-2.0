<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\AdminHmenu;

class Model
{
    protected $layout;

    /**
     * @var array
     */
    protected $data;

    /**
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Adds breadcrumb to hnav.
     *
     * @param string $key
     * @param string $value
     * @return \Ilch\Layout\Helper\AdminHmenu\Model
     */
    public function add($key, $value = '')
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Gets hnav string representation.
     *
     * @return string
     */
    public function __toString()
    {
        if (empty($this->data)) {
            return '<ol class="breadcrumb">&raquo; <li><a href="'.$this->layout->getUrl('admin/admin/index/index').'">Admincenter</a></li></ol>';
        }
        $html = '<ol class="breadcrumb">&raquo; <li><a href="'.$this->layout->getUrl('admin/admin/index/index').'">Admincenter</a></li>';
        foreach ($this->data as $key => $value) {
            if (empty($value)) {
                $html .= $this->layout->escape($key);
            } else {
                $html .= '<li><a href="'.$this->layout->getUrl($value).'">'.$this->layout->escape($key).'</a></li>';
            }
        }
        $html .= '</ol>';

        return $html;
    }
}
