<?php

/**
 * @copyright Ilch 2
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
     * Adds value to title.
     *
     * @param string $value
     * @return Model
     */
    public function add(string $value): Model
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
        /** @var \Ilch\Config\Database $config */
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

        return $html . $configTitle;
    }
}
