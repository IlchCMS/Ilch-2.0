<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

/**
 * Class Loader for loading classes of modules
 */
class Loader
{
    /**
     * Initialize "spl_autoload_register".
     */
    public function __construct()
    {
        spl_autoload_register([$this, 'loadModuleClass']);
    }

    /**
     * Load a module class
     * @param string $class
     */
    private function loadModuleClass($class)
    {
        $class = str_replace('\\', '/', $class);
        $classParts = explode('/', $class);

        $lastClassPart = array_pop($classParts);
        $classParts = array_map('strtolower', $classParts);

        $filePath = APPLICATION_PATH . '/' . implode('/', $classParts) . '/'.$lastClassPart . '.php';

        /*
         * General loading handling.
         */
        if (file_exists($filePath)) {
            require $filePath;
        }
    }
}

