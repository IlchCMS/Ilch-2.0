<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Config;

class File
{
    /**
     * @var array
     */
    protected $configData;

    /**
     * Gets the config for given key.
     *
     * @param  string     $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->configData[$key] ?? null;
    }

    /**
     * Sets the config for given key/vale.
     *
     * @param string         $key
     * @param string|int $value
     */
    public function set($key, $value)
    {
        $this->configData[$key] = $value;
    }

    /**
     * Loads the config from the given path.
     *
     * @param string $fileName
     */
    public function loadConfigFromFile($fileName)
    {
        if (file_exists($fileName)) {
            require $fileName;
        }

        if (!empty($config)) {
            foreach ($config as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    /**
     * Saves the whole config in a file.
     *
     * @param  string $fileName
     * @return bool   true on success, false if the file could not be written
     *                (e.g. missing write permissions on the config file).
     */
    public function saveConfigToFile($fileName)
    {
        $fileString = '<?php';
        $fileString .= "\n";
        $fileString .= '$config = ' . var_export_short_syntax($this->configData) . ';';
        $fileString .= "\n";
        $fileString .= '';

        return file_put_contents($fileName, $fileString) !== false;
    }
}
