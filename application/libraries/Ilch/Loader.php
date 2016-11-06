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

        //Fix for config.php files
        if ($lastClassPart === 'Config'
            && in_array($classParts[0], ['Modules', 'Layouts'], true)
            && $classParts[count($classParts) - 1] === 'Config'
        ) {
            $lastClassPart = 'config';
        }
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

