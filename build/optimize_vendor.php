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
            'font-awesome/css/all.min.css',
            'font-awesome/css/v4-shims.min.css',
            'font-awesome/metadata/icons.json',
            'font-awesome/webfonts/',
            'font-awesome/LICENSE.txt',
        ],
    ],
    'ifsnop' => [
        'keep' => [
            'mysqldump-php/src/',
            'mysqldump-php/LICENSE',
        ]
    ],
    'jbbcode'  => [
        'remove' => [
            'jbbcode/JBBCode/examples/',
            'jbbcode/JBBCode/tests/',
            'jbbcode/.gitignore',
            'jbbcode/.coveralls.yml',
            'jbbcode/composer.json',
            'jbbcode/phpunit.xml.dist',
            'jbbcode/README.md',
        ]
    ],
    'phpmailer' => [
        'remove' => [
            'phpmailer/docs/',
            'phpmailer/examples/',
            'phpmailer/test/',
            'phpmailer/COMMITMENT',
            'phpmailer/composer.json',
            'phpmailer/README.md',
            'phpmailer/SECURITY.md',
            'phpmailer/VERSION',
        ]
    ],
    'ckeditor' => [
        'remove' => [
            'ckeditor/.github/',
            'ckeditor/.npm/',
            'ckeditor/samples/',
            'ckeditor/.npmignore',
            'ckeditor/bender-runner.config.json',
            'ckeditor/bower.json',
            'ckeditor/CHANGES.md',
            'ckeditor/composer.json',
            'ckeditor/package.json',
            'ckeditor/README.md',
            'ckeditor/SECURITY.md',
        ]
    ],
    'kartik-v' => [
        'keep' => [
            'bootstrap-star-rating/css/star-rating.min.css',
            'bootstrap-star-rating/js/star-rating.min.js',
            'bootstrap-star-rating/js/star-rating_locale_de.js',
            'bootstrap-star-rating/img/',
            'bootstrap-star-rating/LICENSE.md',
        ],
    ],
    'twbs' => [
        'keep' => [
            'bootstrap/dist/css/bootstrap.min.css',
            'bootstrap/dist/js/bootstrap.min.js',
            'bootstrap/dist/fonts/',
            'bootstrap/LICENSE',
        ]
    ],
    'ezyang' => [
        'keep' => [
            'htmlpurifier/library/',
            'htmlpurifier/CREDITS',
            'htmlpurifier/LICENSE',
        ]
    ],
    'blueimp' => [
        'keep' => [
            'jquery-file-upload/js/vendor/jquery.ui.widget.js',
            'jquery-file-upload/js/jquery.fileupload.js',
            'jquery-file-upload/js/jquery.iframe-transport.js',
            'jquery-file-upload/LICENSE.txt',
        ]
    ],
    'npm-asset' => [
        'keep' => [
            'jquery/dist/jquery.min.js',
            'jquery/LICENSE.txt',
            'jquery-ui/dist/themes/',
            'jquery-ui/dist/jquery-ui.min.js',
            'jquery-ui/LICENSE.txt',
        ]
    ],
    'harvesthq' => [
        'keep' => [
            'chosen/chosen.min.css',
            'chosen/chosen.jquery.min.js',
            'chosen/chosen.proto.min.js',
            'chosen/chosen-sprite.png',
            'chosen/chosen-sprite@2x.png',
            'chosen/LICENSE.md',
        ]
    ],
];

/**
 * Returns an array with all files in the directory and all its subdirectories
 * @param string $dirname
 * @param array|null $filter if given only directories in the array are scanned
 * @return array
 */
function getFilesRecursive(string $dirname, array $filter = null): array
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
    }
    return $files;
}

/**
 * Remove all empty subfolders of a folder
 * @param string $path
 * @return bool
 */
function removeEmptySubFolders(string $path): bool
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
function quoteForPattern(string $fileOrDir): string
{
    $isDir = $fileOrDir[strlen($fileOrDir) - 1] === DIRECTORY_SEPARATOR;
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
            $keeps[] = $baseDirPath;
            $removes[] = $baseDirPath . str_replace('/', DIRECTORY_SEPARATOR, $remove);
        }
    }
}

$keepPattern = '~^(' . implode('|', array_map('quoteForPattern', $keeps)) . ')~';
$removePattern = '~^(' . implode('|', array_map('quoteForPattern', $removes)) . ')~';

$keepFiles = preg_grep($keepPattern, $vendorFiles);
$removeFiles = preg_grep($removePattern, $vendorFiles);

$filesToDelete = array_diff($vendorFiles, array_diff($keepFiles, $removeFiles));

$savedSpace = 0;
foreach ($filesToDelete as $item) {
    $savedSpace += filesize($item);
    unlink($item);
}

removeEmptySubFolders($vendorPath);

echo sprintf(
    'Removed %d files from the vendor directory saving %s kB.',
    count($filesToDelete),
    number_format($savedSpace / 1024, 2, '.', ' ')
).PHP_EOL;

// Further delete some handpicked files
$savedSpace = 0;
$removedFiles = 0;
$rootPath = realpath(__DIR__ . '/../');
$otherFilesToDelete = ['composer.json', 'composer.lock', 'CONTRIBUTING.md', 'README.md'];
foreach ($otherFilesToDelete as $file) {
    $path = $rootPath.'/'.$file;
    if (file_exists($path)) {
        $savedSpace += filesize($path);
        $removedFiles += 1;
        unlink($path);
    }
}
echo sprintf(
    ' Further removed %d handpicked files saving %s kB.',
    $removedFiles,
    number_format($savedSpace / 1024, 2, '.', ' ')
).PHP_EOL;
