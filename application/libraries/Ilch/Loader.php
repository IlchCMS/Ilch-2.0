<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

require_once APPLICATION_PATH.'/libraries/Ilch/Functions.php';

class Loader
{
    /**
     * @var string[]
     */
    protected $namespaces = array('Ilch');

    /**
     * Initialize "spl_autoload_register".
     */
    public function __construct()
    {
        /**
         * Loads all needed files for the given class.
         *
         * @param string $class
         * @throws InvalidArgumentException
         */
        spl_autoload_register(function ($class) {
            $class = str_replace('\\', '/', $class);
            $classParts = explode('/', $class);
            $path = APPLICATION_PATH;
            $type = 'modules';

            /*
             * Libraries path handling.
             */
            foreach($this->namespaces as $nameSpace) {
                if (strpos($classParts[0], $nameSpace) !== false) {
                    $type = 'libraries';
                    $path = $path.'/libraries';
                    break;
                }
            }

            /*
             * Modules path handling.
             */
            if ($type == 'modules') {
                $lastClassPart = $classParts[count($classParts)-1];
                unset($classParts[count($classParts)-1]);
                $classParts = array_map('strtolower', $classParts);
                $class = implode('/', $classParts).'/'.$lastClassPart;
            }

            /*
             * General loading handling.
             */
            if (file_exists($path.'/'. $class . '.php')) {
                require_once($path.'/'. $class . '.php');
            }
        });
    }

    /**
     * Adds loader namespace.
     *
     * @param string $namespace
     */
    public function registNamespace($namespace)
    {
        $this->namespaces[$namespace] = $namespace;
    }
}

