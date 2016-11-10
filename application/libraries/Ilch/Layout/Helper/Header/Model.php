<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Header;

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
     * Gets the Cascading Style Sheets
     *
     * @param string $value
     * @return \Ilch\Layout\Helper\Header\Model
     */
    public function css($value)
    {
        $this->data[] = '<link href="'.$this->layout->getModuleUrl($value).'" rel="stylesheet">';

        return $this;
    }

    /**
     * Gets the Javascript
     *
     * @param string $value
     * @return \Ilch\Layout\Helper\Header\Model
     */
    public function js($value)
    {
        $this->data[] = '<script type="text/javascript" src="'.$this->layout->getModuleUrl($value).'"></script>';

        return $this;
    }

    /**
     * Gets CSS and JS
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->data) {
            $html = '';
            foreach ($this->data as $value) {
                $html .= $value;
            }

            return $html;
        }

        return '';
    }
}
