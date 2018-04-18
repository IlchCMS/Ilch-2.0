<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

use Modules\User\Models\User;

/**
 * Improves "var_dump" function with pre - tags.
 */
function dumpVar()
{
    if (\Ilch\DebugBar::isInitialized()) {
        $debugBar = \Ilch\DebugBar::getInstance();
        $backtrace = debug_backtrace(0, 1);
        $fileAndLine = $backtrace[0]['file'] . ':' . $backtrace[0]['line'];
        foreach (func_get_args() as $arg) {
            $message = $debugBar['messages']->getDataFormatter()->formatVar($arg) . ' dumped on ' . $fileAndLine;
            $debugBar['messages']->addMessage($message, 'debug');
        }

        return;
    }

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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
    curl_setopt($ch, CURLOPT_CAINFO, ROOT_PATH.'/certificate/cacert.pem');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
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

/**
 * Checks if the current visitor is logged in.
 *
 * @return boolean
 */
function loggedIn()
{
    return !is_null(\Ilch\Registry::get('user'));
}

/**
 * Returns the currentUser or null
 *
 * @returns User model|null
 */
function currentUser()
{
    return \Ilch\Registry::get('user');
}

/**
 * Check if the guest or user needs to solve a captcha according to the setting
 * in the admincenter.
 *
 * @return bool
 */
function captchaNeeded()
{
    $user = \Ilch\Registry::get('user');
    $hideCaptchaFor = explode(',', \Ilch\Registry::get('config')->get('hideCaptchaFor'));

    if (empty($user)) {
        // 3 = group guest
        return !in_array(3, $hideCaptchaFor);
    }

    return !is_in_array(array_keys($user->getGroups()), $hideCaptchaFor);
}

/**
 * Random_* Compatibility Library
 * for using the new PHP 7 random_* API in PHP 5 projects
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Paragon Initiative Enterprises
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
if (!function_exists('RandomCompat_intval')) {
    /**
     * Cast to an integer if we can, safely.
     *
     * If you pass it a float in the range (~PHP_INT_MAX, PHP_INT_MAX)
     * (non-inclusive), it will sanely cast it to an int. If you it's equal to
     * ~PHP_INT_MAX or PHP_INT_MAX, we let it fail as not an integer. Floats
     * lose precision, so the <= and => operators might accidentally let a float
     * through.
     *
     * @param int|float $number    The number we want to convert to an int
     * @param boolean   $fail_open Set to true to not throw an exception
     *
     * @return int (or float if $fail_open)
     *
     * @throws TypeError
     */
    function RandomCompat_intval($number, $fail_open = false)
    {
        if (is_numeric($number)) {
            $number += 0;
        }
        if (is_float($number)
            &&
            $number > ~PHP_INT_MAX
            &&
            $number < PHP_INT_MAX
        ) {
            $number = (int) $number;
        }
        if (is_int($number) || $fail_open) {
            return $number;
        }
        throw new TypeError(
            'Expected an integer.'
        );
    }
}

if (! function_exists('random_int')) {
    /**
     * Random_* Compatibility Library
     * for using the new PHP 7 random_* API in PHP 5 projects
     *
     * The MIT License (MIT)
     *
     * Copyright (c) 2015 Paragon Initiative Enterprises
     *
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is
     * furnished to do so, subject to the following conditions:
     *
     * The above copyright notice and this permission notice shall be included in
     * all copies or substantial portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
     * SOFTWARE.
     */
    /**
     * Fetch a random integer between $min and $max inclusive
     *
     * @param int $min
     * @param int $max
     *
     * @throws Exception
     *
     * @return int
     */
    function random_int($min, $max)
    {
        /**
         * Type and input logic checks
         *
         * If you pass it a float in the range (~PHP_INT_MAX, PHP_INT_MAX)
         * (non-inclusive), it will sanely cast it to an int. If you it's equal to
         * ~PHP_INT_MAX or PHP_INT_MAX, we let it fail as not an integer. Floats
         * lose precision, so the <= and => operators might accidentally let a float
         * through.
         */
        
        try {
            $min = RandomCompat_intval($min);
        } catch (TypeError $ex) {
            throw new TypeError(
                'random_int(): $min must be an integer'
            );
        }
        try {
            $max = RandomCompat_intval($max);
        } catch (TypeError $ex) {
            throw new TypeError(
                'random_int(): $max must be an integer'
            );
        }
        
        /**
         * Now that we've verified our weak typing system has given us an integer,
         * let's validate the logic then we can move forward with generating random
         * integers along a given range.
         */
        if ($min > $max) {
            throw new Error(
                'Minimum value must be less than or equal to the maximum value'
            );
        }
        if ($max === $min) {
            return $min;
        }
        /**
         * Initialize variables to 0
         *
         * We want to store:
         * $bytes => the number of random bytes we need
         * $mask => an integer bitmask (for use with the &) operator
         *          so we can minimize the number of discards
         */
        $attempts = $bits = $bytes = $mask = $valueShift = 0;
        /**
         * At this point, $range is a positive number greater than 0. It might
         * overflow, however, if $max - $min > PHP_INT_MAX. PHP will cast it to
         * a float and we will lose some precision.
         */
        $range = $max - $min;
        /**
         * Test for integer overflow:
         */
        if (!is_int($range)) {
            /**
             * Still safely calculate wider ranges.
             * Provided by @CodesInChaos, @oittaa
             *
             * @ref https://gist.github.com/CodesInChaos/03f9ea0b58e8b2b8d435
             *
             * We use ~0 as a mask in this case because it generates all 1s
             *
             * @ref https://eval.in/400356 (32-bit)
             * @ref http://3v4l.org/XX9r5  (64-bit)
             */
            $bytes = PHP_INT_SIZE;
            $mask = ~0;
        } else {
            /**
             * $bits is effectively ceil(log($range, 2)) without dealing with
             * type juggling
             */
            while ($range > 0) {
                if ($bits % 8 === 0) {
                    ++$bytes;
                }
                ++$bits;
                $range >>= 1;
                $mask = $mask << 1 | 1;
            }
            $valueShift = $min;
        }
        /**
         * Now that we have our parameters set up, let's begin generating
         * random integers until one falls between $min and $max
         */
        do {
            /**
             * The rejection probability is at most 0.5, so this corresponds
             * to a failure probability of 2^-128 for a working RNG
             */
            if ($attempts > 128) {
                throw new Exception(
                    'random_int: RNG is broken - too many rejections'
                );
            }
            /**
             * Let's grab the necessary number of random bytes
             */
            $randomByteString = random_bytes($bytes);
            if ($randomByteString === false) {
                throw new Exception(
                    'Random number generator failure'
                );
            }
            /**
             * Let's turn $randomByteString into an integer
             *
             * This uses bitwise operators (<< and |) to build an integer
             * out of the values extracted from ord()
             *
             * Example: [9F] | [6D] | [32] | [0C] =>
             *   159 + 27904 + 3276800 + 201326592 =>
             *   204631455
             */
            $val = 0;
            for ($i = 0; $i < $bytes; ++$i) {
                $val |= ord($randomByteString[$i]) << ($i * 8);
            }
            /**
             * Apply mask
             */
            $val &= $mask;
            $val += $valueShift;
            ++$attempts;
            /**
             * If $val overflows to a floating point number,
             * ... or is larger than $max,
             * ... or smaller than $min,
             * then try again.
             */
        } while (!is_int($val) || $val > $max || $val < $min);
        return (int) $val;
    }
}
