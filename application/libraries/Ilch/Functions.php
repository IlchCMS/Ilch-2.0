<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

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
 * @param int $skipEntries
 * @return string
 */
function debug_backtrace_html($skipEntries = 1)
{
    $r = '';

    foreach (debug_backtrace() as $key => $t) {
        if ($key < $skipEntries) {
            continue;
        }
        $r .= "\t".'@ ';

        if (isset($t['file'])) {
            $r .= relativePath($t['file']) . ':' . $t['line'];
        } else {
            $r .= '<PHP inner-code>';
        }

        $r .= ' -- ';

        if (isset($t['class'])) {
            $r .= $t['class'] . $t['type'];
        }

        $r .= $t['function'];

        if (isset($t['args']) && sizeof($t['args']) > 0) {
            $r .= '(...)';
        } else {
            $r .= '()';
        }

         $r .= "\r\n";
    }

    return $r;
}

/**
 * Return the relative path
 * @param string $absolutePath
 * @param string $relativeToPath
 * @return string
 */
function relativePath($absolutePath, $relativeToPath = ROOT_PATH)
{
    if (strpos($absolutePath, $relativeToPath) === 0) {
        return substr($absolutePath, strlen($relativeToPath) + 1);
    }
    return $absolutePath;
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
            } elseif ($c != '.' && $c != '..') {
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
 * @return mixed
 *
 * @copyright <Taylor Otwell>
 */
function array_dot($data = [], $key = null, $default = null)
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

/**
 * Set an array item to a given value using "dot" notation.
 *
 * If no key is given to the method, the entire array will be replaced.
 *
 * @param  array   $array
 * @param  string  $key
 * @param  mixed   $value
 * @return array
 *
 * @copyright <Taylor Otwell>
 */
function array_dot_set(&$array, $key, $value)
{
    if (is_null($key)) {
        return $array = $value;
    }

    $keys = explode('.', $key);

    while (count($keys) > 1) {
        $key = array_shift($keys);

        // If the key doesn't exist at this depth, we will just create an empty array
        // to hold the next value, allowing us to create the arrays to hold final
        // values at the correct depth. Then we'll keep digging into the array.
        if (!isset($array[$key]) || ! is_array($array[$key])) {
            $array[$key] = [];
        }

        $array =& $array[$key];
    }

    $array[array_shift($keys)] = $value;

    return $array;
}

/**
 * Check if a needle is in a nested array (just one level)
 * @param mixed $needle
 * @param array|Traversable $haystack
 * @return bool
 */
function is_in_array($needle, $haystack)
{
    foreach ($needle as $stack) {
        if (in_array($stack, $haystack)) {
             return true;
        }
    }
    return false;
}

/**
 * cUrl function, gets a url result
 * @param string $url
 * @return string
 */
function url_get_contents($url)
{
    if (!function_exists('curl_init')) {
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function ilch_function_hash_equals($str1, $str2)
{
    if (strlen($str1) != strlen($str2)) {
        return false;
    } else {
        $res = $str1 ^ $str2;
        $ret = 0;
        for ($i = strlen($res) - 1; $i >= 0; $i--) {
            $ret |= ord($res[$i]);
        }
        return !$ret;
    }
}

/**
 * @param mixed $var
 * @param string $indent
 * @return string
 */
function var_export_short_syntax($var, $indent = '')
{
    switch (gettype($var)) {
        case "string":
            return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
        case "array":
            $indexed = array_keys($var) === range(0, count($var) - 1);
            $r = [];
            foreach ($var as $key => $value) {
                $r[] = "$indent    "
                    . ($indexed ? "" : var_export_short_syntax($key) . " => ")
                    . var_export_short_syntax($value, "$indent    ");
            }
            return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
        case "boolean":
            return $var ? "TRUE" : "FALSE";
        default:
            return var_export($var, true);
    }
}
