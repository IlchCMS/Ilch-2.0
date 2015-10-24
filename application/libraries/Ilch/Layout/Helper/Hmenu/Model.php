<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Hmenu;

class Model
{
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
     * @return \Ilch\Layout\Helper\Hmenu\Model
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
            return '';
        }

        $html = '<a href="'.$this->layout->getUrl().'">Start</a>';

        foreach ($this->data as $key => $value) {
            if (empty($value)) {
                $html .= $this->layout->escape($key);
            } else {
                $html .= ' &rArr; <a href="'.$this->layout->getUrl($value).'">'.$this->layout->escape($key).'</a>';
            }
        }

        return $html;
    }
}
