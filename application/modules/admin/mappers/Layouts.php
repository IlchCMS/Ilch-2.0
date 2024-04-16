<?php

/**
 * @copyright Ilch 2
 */

namespace Modules\Admin\Mappers;

class Layouts extends \Ilch\Mapper
{
    protected $cache = [];

    /**
     * Gets all layouts.
     *
     * @param bool $asKey
     * @return array
     */
    public function getLocalModules(bool $asKey = false): array
    {
        $layouts = [];
        foreach (glob(ROOT_PATH . '/application/layouts/*') as $layoutPath) {
            if (is_dir($layoutPath)) {
                $key = basename($layoutPath);

                $configClass = '\\Layouts\\' . ucfirst($key) . '\\Config\\Config';
                if (class_exists($configClass)) {
                    if ($asKey) {
                        $layouts[$key] = $key;
                    } else {
                        $layouts[] = $key;
                    }
                }
            }
        }
        $this->cache = $layouts;

        return $layouts;
    }
}
