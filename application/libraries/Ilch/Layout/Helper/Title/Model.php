<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Title;

class Model
{
    /**
     * @var array
     */
    protected $data;

    /**
     * Injects the title.
     *
     * @param Ilch\Layout $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Adds value to title.
     *
     * @param string $value
     * @return \Ilch\Layout\Helper\Title\Model
     */
    public function add($value)
    {
        $this->data[] = $value;

        return $this;
    }

    /**
     * Gets title string representation.
     *
     * @return string
     */
    public function __toString()
    {
        $config = \Ilch\Registry::get('config');

        if (empty($this->data)) {
            return $config->get('page_title');
        }

        krsort($this->data);

        $html = '';
        foreach ($this->data as $value) {
            $html .= $value;
            $html .= ' | ';
        }
        $configTitle = $config->get('page_title');

        return $html.$configTitle;
    }
}
