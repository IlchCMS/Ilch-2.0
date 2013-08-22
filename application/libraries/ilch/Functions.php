<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * Improves "var_dump" function with pre - tags.
 */
function dumpVar()
{
    echo '<pre>';

    foreach(func_get_args() as $arg)
    {
        var_dump($arg);
    }

    echo '</pre>';
}