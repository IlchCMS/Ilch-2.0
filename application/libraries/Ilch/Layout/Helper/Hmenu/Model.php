<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper\Hmenu;

use Ilch\Layout\Base as Layout;

class Model
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var Layout
     */
    private $layout;

    /**
     * Injects the layout.
     *
     * @param Layout $layout
     */
    public function __construct(Layout $layout)
    {
        $this->layout = $layout;
    }

    /**
     * Adds breadcrumb to hnav.
     *
     * @param string $key
     * @param string|array $value
     * @return Model
     */
    public function add(string $key, $value = ''): Model
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

        // TODO: This generated output should be easily customizable
        $begin = '<div aria-label="breadcrumb"><ol class="breadcrumb">';

        $html = '<li class="breadcrumb-item active">';
        $html .= '<a href="' . $this->layout->getUrl() . '">Start</a>';
        $html .= '';

        foreach ($this->data as $key => $value) {
            if (empty($value)) {
                $html .= '<li class="breadcrumb-item">';
                $html .= $this->layout->escape($key);
            } else {
                $html .= '<li class="breadcrumb-item">';
                $html .= '<a href="' . $this->layout->escape($this->layout->getUrl($value)) . '">' . $this->layout->escape($key) . '</a>';
            }
            $html .= '</li>';
        }

        $end = '</ol></div>';

        return $begin . $html . $end;
    }
  }
