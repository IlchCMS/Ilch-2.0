<?php
/**
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

    foreach (func_get_args() as $arg) {
        var_dump($arg);
    }

    echo '</pre>';
}

/**
 * Improves "debug_backtrace" function for html dump.
 *
 * @return string
 */
function debug_backtrace_html()
{
    $r = '';

    foreach(debug_backtrace() as $t) {
        $r .= "\t".'@ ';

        if(isset($t['file'])) {
            $r .= basename($t['file']) . ':' . $t['line'];
        } else {
            $r .= '<PHP inner-code>';
        }

        $r .= ' -- ';

        if(isset($t['class'])) {
            $r .= $t['class'] . $t['type'];
        }

        $r .= $t['function'];

        if(isset($t['args']) && sizeof($t['args']) > 0) {
          $r .= '(...)';
        } else {
            $r .= '()';
        }

         $r .= "\r\n";
    }

    return $r;
}

/**
 * Delete directory recursive.
 *
 * @param string $dir
 */
function removeDir($dir)
{
    if (is_dir($dir)) {
        $dircontent = scandir($dir);

        foreach ($dircontent as $c) {
            if ($c != '.' && $c != '..' && is_dir($dir.'/'.$c)) {
                removeDir($dir.'/'.$c);
            } else if ($c != '.' && $c != '..') {
                unlink($dir.'/'.$c);
            }
        }

        rmdir($dir);
    } else {
        unlink($dir);
    }
}

/**
 * Gets array data
 *
 * Supports 'dot' notation for arrays
 * e.g.
 *      foo.bar     > foo['bar']
 *      foo.bar.baz > foo['bar']['baz']
 *
 * @param array     $data       The array
 * @param string    $key        The key to look for
 * @param mixed     $default    A default value if $key is not found
 */
function array_dot($data = array(), $key = null, $default = null)
{
    if ($key === null) {
        return $data;
    }

    if (isset($data[$key])) {
        return $data[$key];
    }

    foreach (explode('.', $key) as $seg) {
        if (!is_array($data) || !array_key_exists($seg, $data)) {
            return $default;
        }

        $data = $data[$seg];
    }

    return $data;
}
