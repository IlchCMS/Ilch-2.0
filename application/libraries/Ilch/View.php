<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class View extends Design\Base
{
    /**
     * Loads a view script.
     *
     * @param  string $viewScript
     * @return string
     */
    public function loadScript($viewScript)
    {
        ob_start();

        if (file_exists($viewScript)) {
            include $viewScript;
        }

        return ob_get_clean();
    }
}
