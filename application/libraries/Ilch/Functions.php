<?php
/**
 * @copyright Ilch 2
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

        if (isset($t['args']) && count($t['args']) > 0) {
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
            if ($c !== '.' && $c !== '..') {
                if (is_dir($dir.'/'.$c)) {
                    removeDir($dir.'/'.$c);
                } else {
                    unlink($dir.'/'.$c);
                }
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
    if ($key === null) {
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
 *
 * @param string $url
 * @param bool $write_cache Set this to false if you don't want to write a cache file.
 * @param bool $ignoreCache Set this to true to ignore the cache and fetch from server.
 * @param integer $cache_time
 * @return mixed $data | FALSE
 */
function url_get_contents($url, $write_cache = true, $ignoreCache = false, $cache_time = 21600)
{
    if (!function_exists('curl_init')) {
        die('CURL is not installed!');
    }

    // settings
    $cachetime = $cache_time; //Default 6 hours
    $where = 'cache';
    if (!is_dir($where)) {
        mkdir($where);
    }

    $hash = md5($url);
    $file = "$where/$hash.cache";

    // check the bloody file.
    $mtime = 0;
    if (file_exists($file)) {
        $mtime = filemtime($file);
    }
    $filetimemod = $mtime + $cachetime;

    // if the renewal date is smaller than now, return true; else false (no need for update)
    if ($ignoreCache || $filetimemod < time()) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'Ilch 2 (+http://www.ilch.de)',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => ROOT_PATH.'/certificate/cacert.pem',
            CURLOPT_URL            => $url,
        ]);
        $output = curl_exec($ch);
        curl_close($ch);

        // save the file if there's data
        if ($output && $write_cache) {
            file_put_contents($file, $output);
        }
    } else {
        $output = file_get_contents($file);
    }

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
        case 'string':
            return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
        case 'array':
            $indexed = array_keys($var) === range(0, count($var) - 1);
            $r = [];
            foreach ($var as $key => $value) {
                $r[] = "$indent    "
                    . ($indexed ? '' : var_export_short_syntax($key) . ' => ')
                    . var_export_short_syntax($value, "$indent    ");
            }
            return "[\n" . implode(",\n", $r) . "\n" . $indent . ']';
        case 'boolean':
            return $var ? 'TRUE' : 'FALSE';
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
    return \Ilch\Registry::get('user') !== null;
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
 * Check if email address is in blacklist.
 *
 * @param string $emailAddress
 * @return bool
 */
function isEmailOnBlacklist($emailAddress)
{
    if (empty($emailAddress)) {
        return false;
    }

    if (empty(trim(\Ilch\Registry::get('config')->get('emailBlacklist')))) {
        return false;
    }

    $emailBlacklist = explode(PHP_EOL, \Ilch\Registry::get('config')->get('emailBlacklist'));
    foreach ($emailBlacklist as $entry) {
        if (empty(trim($entry))) {
            continue;
        }

        if (strpos($emailAddress, trim($entry)) !== false) {
            return true;
        }
    }

    return false;
}

/**
 * Recursive glob()
 *
 * @param $pattern
 * @param int $flags
 * @return array|false
 */
function glob_recursive($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);

    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }

    return $files;
}

/**
 * Gets the file owner.
 *
 * @since 2.1.22
 *
 * @param string $file Path to the file.
 * @return string|false Username of the owner on success, false on failure.
 */
function owner($file)
{
    $owneruid = @fileowner($file);

    if (!$owneruid) {
        return false;
    }

    if (!function_exists('posix_getpwuid')) {
        return $owneruid;
    }

    $ownerarray = posix_getpwuid($owneruid);
    return $ownerarray['name'];
}

/**
 * Gets the permissions of the specified file or filepath in their octal format.
 *
 * @since 2.1.22
 *
 * @param string $file Path to the file.
 * @return string Mode of the file (the last 3 digits).
 */
function getchmod($file)
{
    return substr(decoct(@fileperms($file)), -3);
}

/**
 * Gets the file's group.
 *
 * @since 2.1.22
 *
 * @param string $file Path to the file.
 * @return string|false The group on success, false on failure.
 */
function group($file)
{
    $gid = @filegroup($file);

    if (!$gid) {
        return false;
    }

    if (!function_exists('posix_getgrgid')) {
        return $gid;
    }

    $grouparray = posix_getgrgid($gid);
    return $grouparray['name'];
}

/**
 * Check if date string is valid.
 *
 * @since 2.1.30
 *
 * @param $date
 * @param string $format
 * @return bool
 */
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Replace the vendor directory with the new one.
 *
 * @since 2.1.36
 *
 * @param string $tmpName temporary name of new vendor directory. Usually '_vendor'.
 * @return bool
 */
function replaceVendorDirectory($tmpName = '_vendor')
{
    if (file_exists(ROOT_PATH . '/' . $tmpName) && rename(ROOT_PATH . '/vendor', ROOT_PATH . '/backup_vendor') && rename(ROOT_PATH . '/' . $tmpName, ROOT_PATH . '/vendor')) {
        removeDir(ROOT_PATH.'/backup_vendor');
        return true;
    }

    return false;
}

/**
 * SetCookie Handling.
 *
 * @since 2.1.43
 *
 * @param string $name
 * @param string $value
 * @param int $expires
 * @param array $params
 * @return bool
 */
function setcookieIlch(string $name, string $value = '', int $expires = 0, $params = null)
{
    $params = $params ?? session_get_cookie_params();
    
    $params['expires'] = $expires;

    $allows = ['expires' => true, 'path' => true, 'domain' => true, 'secure' => true, 'httponly' => true, 'samesite' => true];
    foreach (array_keys($params) as $key) {
        if (!isset($allows[$key]) || !$allows[$key]) {
            unset($params[$key]);
        }
    }

    return setcookie($name, $value, $params);
}
