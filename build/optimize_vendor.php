#!/usr/bin/env php
<?php
/**
 * Script to remove not needed files from the vendor directory, when creating a build (just --no-dev dependencies)
 */

/* Configuration of files/directories to remove or keep,
 // select files that should be kept, all other files/dirs in dir-in-vendor will be removed
 'dir-in-vendor' => [
    'keep' => ['file', 'dir/'] //directories must be given with a trailing /
 ],
 //select files that should be removed, all other files/dirs in dir-in-vendor2 will not be removed
 'dir-in-vendor2' => [
    'remove' => ['file', 'dir/'] //directories must be given with a trailing /
 ]
 //only the provided directories in vendor will be processes, all other directories in vendor will be kept untouched
*/
$directories = [
    'fortawesome' => [
        'keep' => [
            'font-awesome/css/font-awesome.min.css',
            'font-awesome/fonts/'
        ],
    ],
    'kartik-v'    => [
        'keep' => [
            'bootstrap-star-rating/css/star-rating.min.css',
            'bootstrap-star-rating/js/star-rating.min.js',
            'bootstrap-star-rating/js/star-rating_locale_de.js',
            'bootstrap-star-rating/img/',
        ],
    ],
    'twbs'        => [
        'keep' => [
            'bootstrap/dist/css/bootstrap.min.css',
            'bootstrap/dist/js/bootstrap.min.js',
            'bootstrap/dist/fonts/'
        ]
    ],
];

/**
 * Returns an array with all files in the directory and all its subdirectories
 * @param string $dirname
 * @param array|null $filter if given only directories in the array are scanned
 * @return array
 */
function getFilesRecursive($dirname, array $filter = null)
{
    $files = [];
    foreach (scandir($dirname) as $item) {
        if ($item === '.' || $item === '..' || ($filter !== null && !in_array($item, $filter))) {
            continue;
        }
        $fullPath = $dirname . DIRECTORY_SEPARATOR . $item;
        if (is_dir($fullPath)) {
            $files = array_merge($files, getFilesRecursive($fullPath));
        } else {
            $files[] = $fullPath;
        }
    };
    return $files;
}

/**
 * Remove all empty subfolders of a folder
 * @param string $path
 * @return bool
 */
function removeEmptySubFolders($path)
{
    $empty = true;
    foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
        $empty &= is_dir($file) && removeEmptySubFolders($file);
    }
    return $empty && rmdir($path);
}

/**
 * Quote the file for the regular expression pattern
 * @param string $fileOrDir
 * @return string
 */
function quoteForPattern($fileOrDir)
{
    $isDir = $fileOrDir[strlen($fileOrDir) -1 ] === DIRECTORY_SEPARATOR;
    $quoted = preg_quote($fileOrDir);
    if (!$isDir) {
        $quoted .= '$';
    }
    return $quoted;
}

$vendorPath = realpath(__DIR__ . '/../vendor');
$vendorFiles = getFilesRecursive($vendorPath, array_keys($directories));

$keeps = $removes = [];

foreach ($directories as $baseDir => $dirOptions) {
    $baseDirPath = $vendorPath . DIRECTORY_SEPARATOR . $baseDir . DIRECTORY_SEPARATOR;
    if (isset($dirOptions['keep'])) {
        foreach ($dirOptions['keep'] as $keep) {
            $keeps[] = $baseDirPath . str_replace('/', DIRECTORY_SEPARATOR, $keep);
        }
    } elseif (isset($dirOptions['remove'])) {
        foreach ($dirOptions['remove'] as $remove) {
            $remove[] = $baseDirPath . str_replace('/', DIRECTORY_SEPARATOR, $remove);
        }
    }
}

$keepPattern = '~^(' . implode('|', array_map('quoteForPattern', $keeps)) . ')~';
$removePattern = '~^(' . implode('|', array_map('quoteForPattern', $removes)) . ')~';

$keepFiles = preg_grep($keepPattern, $vendorFiles);
$removeFiles = preg_grep($removePattern, $vendorFiles);

$filesToDelete = array_diff($vendorFiles, $keepFiles);
$filesToDelete = array_intersect($filesToDelete, $removeFiles);

foreach ($filesToDelete as $item) {
    unlink($item);
}

removeEmptySubFolders($vendorPath);
