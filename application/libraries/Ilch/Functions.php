<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

use Modules\User\Models\User;

/**
 * Improves "var_dump" function with pre - tags.
 * @return void
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
function debug_backtrace_html(int $skipEntries = 1): string
{
    $r = '';

    foreach (debug_backtrace() as $key => $t) {
        if ($key < $skipEntries) {
            continue;
        }
        $r .= "\t" . '@ ';

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
function relativePath(string $absolutePath, string $relativeToPath = ROOT_PATH): string
{
    if (strpos($absolutePath, $relativeToPath) === 0) {
        return substr($absolutePath, strlen($relativeToPath) + 1);
    }
    return $absolutePath;
}

/**
 * @param string $segments,...
 * @return string
 * @since 2.1.46
*/
function buildPath(...$segments): string
{
    return join(DIRECTORY_SEPARATOR, $segments);
}

/**
 * Delete directory recursive.
 *
 * @param string $path
 * @return bool
 */
function removeDir(string $path): bool
{
    if (is_dir($path)) {
        $dircontent = array_diff(scandir($path), array('..', '.'));
        $isEmpty = true;
        foreach ($dircontent as $content) {
            $filePath = buildPath($path, $content);
            $returned = removeDir($filePath);
            if (!$returned) {
                $isEmpty = false;
            }
        }
        if ($isEmpty) {
            return rmdir($path);
        }
        return false;
    } elseif (is_file($path)) {
        chmod($path, 0777);
        return unlink($path);
    } else {
        return false;
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
 * @param string|null    $key        The key to look for
 * @param mixed     $default    A default value if $key is not found
 * @return mixed
 *
 * @copyright <Taylor Otwell>
 */
function array_dot($data = [], ?string $key = null, $default = null)
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
 * @param  string|null|int  $key
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
function is_in_array($needle, $haystack): bool
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
 * @param int $cache_time
 * @return bool|string|void $data
 */
function url_get_contents(string $url, bool $write_cache = true, bool $ignoreCache = false, int $cache_time = 21600)
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
    $file = buildPath($where, $hash . '.cache');

    // check the file.
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
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => buildPath(ROOT_PATH, 'certificate', 'cacert.pem'),
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
function var_export_short_syntax($var, string $indent = ''): string
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
 * @return bool
 */
function loggedIn(): bool
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
function captchaNeeded(): bool
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
function isEmailOnBlacklist(string $emailAddress): bool
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
 * @param string $path
 * @param int $flags
 * @return array|false
 */
function glob_recursive(string $path, int $flags = 0)
{
    $files = glob($path, $flags);

    foreach (glob(buildPath(dirname($path), '*'), GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, glob_recursive(buildPath($dir, basename($path)), $flags));
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
function owner(string $file)
{
    $owneruid = @fileowner($file);

    if (!$owneruid) {
        return false;
    }

    if (!function_exists('posix_getpwuid')) {
        return $owneruid;
    }

    $ownerarray = posix_getpwuid($owneruid);

    if ($ownerarray === false) {
        return false;
    }

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
function getchmod(string $file): string
{
    return substr(decoct(@fileperms($file)), -3);
}

/**
 * Gets the file's group.
 *
 * @param string $file Path to the file.
 * @return string|false The group on success, false on failure.
 *@since 2.1.22
 *
 */
function group(string $file)
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
 * @param string|null $date
 * @param string $format
 * @return bool
 * @since 2.1.30
 */
function validateDate(?string $date, string $format = 'Y-m-d H:i:s'): bool
{
    foreach (["\0", "%00"] as $nullByte) {
        if ((0 === substr_compare($date ?? '', $nullByte, - 1))) {
            // Return false when $date contains null bytes.
            // This avoids "createFromFormat(): Argument must not contain any null bytes".
            return false;
        }
    }

    $d = DateTime::createFromFormat($format, $date ?? '');
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
function replaceVendorDirectory(string $tmpName = '_vendor'): bool
{
    if (file_exists(buildPath(ROOT_PATH, $tmpName)) && rename(buildPath(ROOT_PATH, 'vendor'), buildPath(ROOT_PATH, 'backup_vendor')) && rename(buildPath(ROOT_PATH, $tmpName), buildPath(ROOT_PATH, 'vendor'))) {
        return removeDir(buildPath(ROOT_PATH, 'backup_vendor'));
    }

    return false;
}

/**
 * SetCookie Handling.
 *
 * @param string $name
 * @param string $value
 * @param int $expires
 * @param array|null $params
 * @return bool
 *@since 2.1.43
 *
 */
function setcookieIlch(string $name, string $value = '', int $expires = 0, ?array $params = null): bool
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

/**
 * Generate a UUID v4.
 *
 * @since 2.1.48
 *
 * @param string|null $data
 * @return string|void
 * @throws Exception
 * @see https://www.rfc-editor.org/rfc/rfc4122
 */
function generateUUID(?string $data = null)
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/**
 * Invalidate opcache if possible.
 *
 * @since 2.1.48
 *
 * @param string $filepath The path to the script being invalidated.
 * @param bool $force If set to true, the script will be invalidated regardless of whether invalidation is necessary.
 * @return bool Returns true if the opcode cache for filename was invalidated or if there was nothing to invalidate, or false if the opcode cache is disabled.
 */
function invalidateOpcache(string $filepath, bool $force = false): bool
{
    $invalidatePossible = false;

    // Check if the function is available to call and if the host has restricted the ability to run the function.
    if (function_exists('opcache_invalidate') && (!ini_get('opcache.restrict_api') || stripos(realpath($_SERVER['SCRIPT_FILENAME']), ini_get('opcache.restrict_api')) === 0)) {
        $invalidatePossible = true;
    }

    // If invalidation is not available, return early.
    if (!$invalidatePossible) {
        return false;
    }

    // Verify that file to be invalidated has a PHP extension.
    if ('.php' !== strtolower(substr($filepath, -4))) {
        return false;
    }

    return opcache_invalidate($filepath, $force);
}

/**
 * Get the bytes in a human readable format like 350 KB instead of 358400 bytes.
 * Integers in PHP are limited to 32 bits, unless they are on 64 bit architecture, then they have 64 bit size.
 * For a larger size use string. It will be converted to a double, which should always have 64 bit length.
 * Technically the correct unit names for powers of 1024 are KiB, MiB etc.
 *
 * @since 2.1.54
 *
 * @param int|string $bytes
 * @param int $decimals
 * @return string|bool Number as string or false on failure.
 */
function formatBytes($bytes, int $decimals = 0)
{
    if ($bytes === '' || $bytes < 0 || !is_numeric($bytes)) {
        return false;
    }

    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $decimals) . ' ' . $units[$pow];
}
