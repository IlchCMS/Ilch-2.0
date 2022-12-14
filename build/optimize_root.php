#!/usr/bin/env php
<?php
$excludeArray = [
    '.travis.yml',
    '.github',
    'tests',
    'development',
    '.gitattributes',
    '.git',
    '.gitignore',
    'phpcs.xml',
    'build',
    '.gitlab-ci.yml-Dateien',
    'application/config.php',
    '.idea',
    'nbproject',
    'Vagrantfile',
    '.vagrant',
    'web.config',
    'robots.txt',
    '.user.ini',
    'cache'
];


/**
* @param string $segments,...
* @return string
*/
function buildFilePath(...$segments)
{
    return join(DIRECTORY_SEPARATOR, $segments);
}

/**
* @param string $path
* @return bool
*/
function removeDir(string $path)
{
    if (is_dir($path)) {
        $dircontent = array_diff(scandir($path), array('..', '.'));
        foreach ($dircontent as $content) {
            $filePath = buildFilePath($path, $content);
            removeDir($filePath);
        }

        return rmdir($path);
    } elseif (is_file($path)) {
        chmod($path, 0777);
        return unlink($path);
    } else {
        return false;
    }
}

/**
* @param array $excludeArray
* @param array $removedFiles
* @param string $path
* @return array
*/
function cleanFiles(array $excludeArray = [], array $removedFiles = [], string $path = '')
{
    foreach ($excludeArray as $content) {
        $filePath = buildFilePath($path, $content);
        
        if (removeDir($filePath)) {
            $removedFiles[] = $filePath;
        }
    }
    
        $dircontent = array_diff(scandir($path) ?? [], array('..', '.'));

        foreach ($dircontent as $content) {
            $filePath = buildFilePath($path, $content);
            
            if (is_dir($filePath)) {
                if (in_array($content, $excludeArray)) {
                    if (removeDir($filePath)) {
                        $removedFiles[] = $filePath;
                    }
                } else {
                    $removedFiles = cleanFiles($excludeArray, $removedFiles, $filePath);
                }
            } else {
                if (in_array($content, $excludeArray)) {
                    $savedSpace = filesize($filePath);
                    if (removeDir($filePath)) {
                        $removedFiles[] = $filePath;
                        if (!isset($removedFiles['savedSpace'])) {
                            $removedFiles['savedSpace'] = 0;
                        }
                        $removedFiles['savedSpace'] += $savedSpace;
                    }
                }
            }
        
    }
    return $removedFiles;
}

/**
* @param string $path
* @return bool
*/
function RemoveEmptySubFolders(string $path)
{
    $empty = true;
    $filePath = buildFilePath($path, "*");
    foreach (glob($filePath) as $file) {
        $empty &= is_dir($file) && RemoveEmptySubFolders($file);
    }
    return $empty && (is_readable($path) && count(scandir($path)) == 2) && rmdir($path);
}


$filePath = buildFilePath(substr(__DIR__, 0, -(strlen('/build'))));
$removedFiles = cleanFiles($excludeArray, [], $filePath);
removeEmptySubFolders($filePath);

$savedSpace = 0;
if (isset($removedFiles['savedSpace'])) {
    $savedSpace = $removedFiles['savedSpace'];
    unset($removedFiles['savedSpace']);
}

echo sprintf(
    'Removed %d files from the root directory saving %s kB.',
    count($removedFiles),
    number_format($savedSpace / 1024, 2, '.', ' ')
);
