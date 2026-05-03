<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper\AdminHmenu;

class Model
{
    /**
     * @var \Ilch\Layout\Admin
     */
    protected $layout;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Injects the layout.
     *
     * @param \Ilch\Layout\Admin $layout
     */
    public function __construct(\Ilch\Layout\Admin $layout)
    {
        $this->layout = $layout;
    }

    /**
     * Adds breadcrumb to hnav.
     *
     * @param string $key
     * @param string|array $value
     * @return $this
     */
    public function add(string $key, $value = ''): Model
    {
        $this->data[] = ['key' => $key, 'value' => $value];

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
            return '<div aria-label="breadcrumb"><ol class="breadcrumb">&raquo; &nbsp;<li class="breadcrumb-item active"><a href="' . $this->layout->getUrl('admin/admin/index/index') . '">Admincenter</a></li></ol></div>';
        }
        $html = '<div aria-label="breadcrumb"><ol class="breadcrumb">&raquo; &nbsp;<li class="breadcrumb-item active"><a href="' . $this->layout->getUrl('admin/admin/index/index') . '">Admincenter</a></li>';
        foreach ($this->data as $value) {
            if (empty($value['value'])) {
                $html .= $this->layout->escape($value['key']);
            } else {
                $html .= '<li class="breadcrumb-item"><a href="' . $this->layout->getUrl($value['value']) . '">' . $this->layout->escape($value['key']) . '</a></li>';
            }
        }
        $html .= '</ol></div>';

        return $html;
    }
}
