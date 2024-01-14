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
    public function __toString(): string
    {
        /** @var \Ilch\Config\Database $config */
        $config = \Ilch\Registry::get('config');

        if (empty($this->data)) {
            return $config->get('page_title');
        }

        $separator = $config->get('page_title_moduledata_separator') ?? ' | ';

        if ((bool)($config->get('page_title_moduledata_order') ?? '0')) {
            krsort($this->data);
        } else {
            ksort($this->data);
        }

        return str_replace(['%%title%%', '%%moduledata%%'], [$config->get('page_title'), implode($separator, $this->data)], $config->get('page_title_order') ?? '%%moduledata%% | %%title%%');
    }
}
