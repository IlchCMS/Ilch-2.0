<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

use Ilch\Config\File;
use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Models\Box as BoxModel;
use Modules\Admin\Mappers\Module as ModuleMapper;
use Modules\Admin\Models\Module as ModuleModel;

class Transfer
{
    protected $transferUrl;

    protected array $curlOpt = [];

    protected string $versionNow;

    protected string $newVersion;

    protected string $zipSavePath;

    protected string $zipFile;

    protected string $zipFileName;

    protected string $zipSigFile;

    protected string $zipSigFileName;

    protected string $downloadUrl;

    protected string $downloadSignatureUrl;

    protected array $content = [];

    protected array $missingRequirements = [];

    /**
     * Sets the TransferUrl.
     *
     * @param string $url
     * @return false|resource
     */
    public function setTransferUrl(string $url)
    {
        return $this->transferUrl = curl_init($url);
    }

    /**
     * Gets the TransferUrl.
     *
     * @return false|resource
     */
    public function getTransferUrl()
    {
        return $this->transferUrl;
    }

    /**
     * Sets the ZipFile.
     *
     * @return string
     * @param string $path
     */
    public function setZipFile(string $path): string
    {
        return $this->zipFile = $path;
    }

    /**
     * Gets the ZipFile.
     *
     * @return string
     */
    public function getZipFile(): string
    {
        return $this->zipFile;
    }

    /**
     * Sets the ZipFileName.
     *
     * @return string
     * @param string $name
     */
    public function setZipFileName(string $name): string
    {
        return $this->zipFileName = $name;
    }

    /**
     * Gets the ZipFileName.
     *
     * @return string
     */
    public function getZipFileName(): string
    {
        return $this->zipFileName;
    }

    /**
     * Sets the ZipFilePath.
     *
     * @return string
     * @param string $path
     */
    public function setZipSavePath(string $path): string
    {
        return $this->zipSavePath = $path;
    }

    /**
     * Gets the ZipSavePath.
     *
     * @return string
     */
    public function getZipSavePath(): string
    {
        return $this->zipSavePath;
    }

    /**
     * USE IT AFTER newVersionFound()
     * Sets the DownloadUrl.
     *
     * @param string $url
     * @return string
     */
    public function setDownloadUrl(string $url): string
    {
        $this->zipFileName = basename($url);
        $this->zipFile = $this->zipSavePath . $this->zipFileName;
        return $this->downloadUrl = $url;
    }

    /**
     * USE IT AFTER newVersionFound()
     * Gets the DownloadUrl.
     *
     * @return string
     */
    public function getDownloadUrl(): string
    {
        return $this->downloadUrl;
    }

    /**
     * USE IT AFTER newVersionFound()
     * Sets the DownloadSignatureUrl.
     *
     * @param string $url
     * @return string
     */
    public function setDownloadSignatureUrl(string $url): string
    {
        $this->zipSigFileName = basename($url);
        $this->zipSigFile = $this->zipSavePath . $this->zipSigFileName;
        return $this->downloadSignatureUrl = $url;
    }

    /**
     * USE IT AFTER newVersionFound()
     * Gets the DownloadSignatureUrl.
     *
     * @return string
     */
    public function getDownloadSignatureUrl(): string
    {
        return $this->downloadSignatureUrl;
    }

    /**
     * Sets the VersionNow.
     *
     * @return string
     * @param string $versionNow
     */
    public function setVersionNow(string $versionNow): string
    {
        return $this->versionNow = $versionNow;
    }

    /**
     * Gets the VersionNow.
     *
     * @return string
     */
    public function getVersionNow(): string
    {
        return $this->versionNow;
    }

    /**
     * Sets the NewVersion.
     *
     * @return string
     * @param string $version
     */
    public function setNewVersion(string $version): string
    {
        return $this->newVersion = $version;
    }

    /**
     * Gets the NewVersion.
     *
     * @return string
     */
    public function getNewVersion(): string
    {
        return $this->newVersion;
    }

    /**
     * Sets the Content.
     *
     * @return string[]
     * @param string[] $content
     */
    public function setContent(array $content): array
    {
        return $this->content = $content;
    }

    /**
     * Gets the Content.
     *
     * @return string[]
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Gets the content of missingRequirements.
     *
     * @return array
     */
    public function getMissingRequirements(): array
    {
        return $this->missingRequirements;
    }

    /**
     * Gets the ilch versions.
     *
     * @return array|null associative array or null on failure.
     * @since 2.0.0
     * @since 2.2.3 Returns an associative array or null on failure.
     */
    public function getVersions(): ?array
    {
        // curl_exec: Returns true on success or false on failure. However, if the CURLOPT_RETURNTRANSFER option is set, it will return the result on success, false on failure.
        // This function may return Boolean false, but may also return a non-Boolean value which evaluates to false.

        // Set CURLOPT_RETURNTRANSFER as this is required for this function.
        $previousValue = $this->curlOpt[CURLOPT_RETURNTRANSFER] ?? 0;

        if (!$previousValue) {
            $this->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
        }

        $result = curl_exec($this->transferUrl);

        // Restore previous value of CURLOPT_RETURNTRANSFER.
        if (!$previousValue) {
            $this->setCurlOpt(CURLOPT_RETURNTRANSFER, 0);
        }

        if ($result === false) {
            return null;
        }

        return json_decode($result, true);
    }

    /**
     * Gets all versions and checks if there is a new version available.
     *
     * @return bool
     */
    public function newVersionFound(): bool
    {
        $versionsList = $this->getVersions();

        foreach ($versionsList ?? [] as $version => $details) {
            if (version_compare(preg_replace('/\s+/', '', $version), $this->versionNow, '>')) {
                $this->newVersion = trim(preg_replace('/\s\s+/', '', $version));
                $this->checkRequirements($details);
                return true;
            }
        }

        return false;
    }

    /**
     * Check the requirements for the update.
     * Writes missing requirements to the 'missingRequirements' property.
     *
     * @param mixed $requirements
     * @return bool
     */
    public function checkRequirements($requirements): bool
    {
        if (!empty($requirements['phpVersion']) && !version_compare(PHP_VERSION, $requirements['phpVersion'], '>=')) {
            $this->missingRequirements['phpVersion'] = $requirements['phpVersion'];
        }

        if (!empty($requirements['mysqlVersion']) || !empty($requirements['mariadbVersion'])) {
            $fileConfig = new File();
            $fileConfig->loadConfigFromFile(CONFIG_PATH . '/config.php');
            $hostParts = explode(':', $fileConfig->get('dbHost'));
            $port = (!empty($hostParts[1])) ? $hostParts[1] : null;
            $dbLinkIdentifier = mysqli_connect($fileConfig->get('dbHost'), $fileConfig->get('dbUser'), $fileConfig->get('dbPassword'), null, $port);

            if (strpos(mysqli_get_server_info($dbLinkIdentifier), 'MariaDB') !== false) {
                if (!version_compare(mysqli_get_server_info($dbLinkIdentifier), $requirements['mariadbVersion'], '>=')) {
                    $this->missingRequirements['mariadbVersion'] = $requirements['mariadbVersion'];
                }
            } elseif (!version_compare(mysqli_get_server_info($dbLinkIdentifier), $requirements['mysqlVersion'], '>=')) {
                $this->missingRequirements['mysqlVersion'] = $requirements['mysqlVersion'];
            }
        }

        if (!empty($requirements['phpExtensions'])) {
            $this->missingRequirements['phpExtensions'] = [];
            foreach ($requirements['phpExtensions'] as $extension) {
                if (!extension_loaded($extension)) {
                    $this->missingRequirements['phpExtensions'][] = $extension;
                }
            }
        }

        return empty($this->missingRequirements);
    }

    /**
     * Sets an cURL option for the current cURL transfer (transferUrl).
     *
     * @param int $opt
     * @param mixed $param
     * @return $this
     */
    public function setCurlOpt(int $opt, $param): Transfer
    {
        if (!empty($this->transferUrl)) {
            if (curl_setopt($this->transferUrl, $opt, $param)) {
                $this->curlOpt[$opt] = $param;
            }
        }
        return $this;
    }

    /**
     * Download ilch update, module or layout and the signature file for that file.
     *
     * @return bool
     */
    public function save(): bool
    {
        try {
            $newUpdate = url_get_contents($this->downloadUrl, false, true);
            if (!is_dir($this->zipSavePath)) {
                mkdir($this->zipSavePath);
            }
            $dlHandler = fopen($this->zipFile, 'wb');
            fwrite($dlHandler, $newUpdate);
            fclose($dlHandler);

            $newUpdate = url_get_contents($this->downloadSignatureUrl, false, true);
            $dlHandler = fopen($this->zipSigFile, 'wb');
            fwrite($dlHandler, $newUpdate);
            fclose($dlHandler);
        } finally {
            $signature = file_get_contents($this->zipSigFile);
            $pubKeyfile = ROOT_PATH . '/certificate/Certificate.crt';
            if (!$this->verifyFile($pubKeyfile, $this->zipFile, $signature)) {
                // Verification failed. Drop the potentially bad files.
                unlink($this->zipFile);
                unlink($this->zipSigFile);
                return false;
            }
            return true;
        }
    }

    /**
     * Verify file.
     *
     * @param string $pubKeyfile
     * @param string $file
     * @param string $signature
     * @return bool
     */
    public function verifyFile(string $pubKeyfile, string $file, string $signature): bool
    {
        $digest = hash_file('sha512', $file);

        $pubkey = openssl_pkey_get_public(file_get_contents($pubKeyfile));
        openssl_public_decrypt($signature, $decrypted_digest, $pubkey);

        return $digest == $decrypted_digest;
    }

    /**
     * Validate certificate.
     *
     * @param string $certificate
     * @return bool
     */
    public function validateCert(string $certificate): bool
    {
        if (!is_file($certificate)) {
            return false;
        }

        $public_key = file_get_contents($certificate);
        $certinfo = openssl_x509_parse($public_key);
        $validTo = $certinfo['validTo_time_t'];

        return ($validTo >= time());
    }

    /**
     * Check if it would be possible to write the changed files
     * of the zip file to their destination.
     *
     * @param string $pathZipFile
     * @return array|bool
     */
    public function checkIfDestinationIsWritable(string $pathZipFile)
    {
        $zip = new \ZipArchive();

        if ($zip->open($pathZipFile) !== true) {
            return false;
        }

        $notWritable = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $path = ROOT_PATH . '/' . $zip->getNameIndex($i);

            if (file_exists($path) && !is_writable($path)) {
                $notWritable[] = $path;
            }
        }

        $zip->close();
        return $notWritable;
    }

    /**
     * Update ilch, a module or a layout.
     *
     * @param string $installedVersion
     * @return bool
     */
    public function update(string $installedVersion): bool
    {
        @set_time_limit(300);
        $zip = new \ZipArchive();
        $content = [];

        try {
            // Check if file is potentially damaged or manipulated.
            if (!$this->verifyFile(ROOT_PATH . '/certificate/Certificate.crt', $this->zipFile, file_get_contents($this->zipSigFile))) {
                $content[] = 'Verification failed. Dropped the potentially damaged or manipulated files.';
                return false;
            }

            $res = $zip->open($this->zipFile);

            if ($res !== true) {
                $content[] = 'Failed to open update file.';
                return false;
            }

            // Check if it would be possible to write the changed files.
            $notWritable = $this->checkIfDestinationIsWritable($this->zipFile);

            if ($notWritable === false) {
                return false;
            }

            if (!empty($notWritable)) {
                $content[] = 'Some files/directories seem to be not writable:';
                foreach ($notWritable as $path) {
                    $content[] = $path;
                }

                return false;
            }

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $thisFileName = $zip->getNameIndex($i);
                $thisFileDir = \dirname($thisFileName);

                if (!is_dir(ROOT_PATH . '/' . $thisFileDir)) {
                    $content[] = 'New directory: ' . $thisFileDir;
                }

                if (!is_dir(ROOT_PATH . '/' . $thisFileName)) {
                    $content[] = 'New file: ' . $thisFileName;
                }
                $success = $zip->extractTo(ROOT_PATH, [$thisFileName]);

                if (!$success) {
                    $content[] = 'Error writing new file: ' . $thisFileName;
                    return false;
                }

                // Execute getUpdate() in config.php if needed.
                if ($thisFileName == $thisFileDir . '/config.php') {
                    invalidateOpcache($thisFileName, true);
                    include $thisFileName;

                    $configClass = str_replace(array('.php', 'application', '/'), array('', '', "\\"), $thisFileName);
                    if (class_exists($configClass)) {
                        $config = new $configClass();

                        if (method_exists($config, 'getUpdate')) {
                            $content[] = $config->getUpdate($installedVersion);
                        }
                    }
                }
            }

            return true;
        } finally {
            $this->setContent($content);
            $zip->close();
            unlink($this->zipFile);
            unlink($this->zipSigFile);
            $this->curlClose();
        }
    }

    /**
     * Install a module or layout.
     *
     * @return bool
     */
    public function install(): bool
    {
        $zip = new \ZipArchive();

        try {
            if ($zip->open($this->zipFile) !== true) {
                return false;
            }
            $zip->extractTo(ROOT_PATH);
            $content = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $thisFileName = $zip->getNameIndex($i);
                $thisFileDir = dirname($thisFileName);
                //If we need to run commands, then do it.
                if ($thisFileName == $thisFileDir . '/config.php') {
                    invalidateOpcache($thisFileName, true);
                    include $thisFileName;
                    $configClass = str_replace(array('.php', 'application', '/'), array('', '', "\\"), $thisFileName);
                    if (class_exists($configClass)) {
                        $config = new $configClass();
                        if (method_exists($config, 'install')) {
                            $content[] = $config->install();

                            // Skip module related stuff if this is a layout install.
                            if (strpos($thisFileName, 'application/modules/') === false) {
                                continue;
                            }

                            $moduleMapper = new ModuleMapper();
                            $moduleModel = new ModuleModel();
                            $moduleModel->setKey($config->config['key']);

                            if (isset($config->config['author'])) {
                                $moduleModel->setAuthor($config->config['author']);
                            }

                            if (isset($config->config['languages'])) {
                                foreach ($config->config['languages'] as $key => $value) {
                                    $moduleModel->addContent($key, $value);
                                }
                            }

                            if (isset($config->config['system_module'])) {
                                $moduleModel->setSystemModule(true);
                            }

                            if (isset($config->config['link'])) {
                                $moduleModel->setLink($config->config['link']);
                            }

                            if (isset($config->config['version'])) {
                                $moduleModel->setVersion($config->config['version']);
                            }

                            $moduleModel->setIconSmall($config->config['icon_small']);
                            $moduleMapper->save($moduleModel);

                            if (isset($config->config['boxes'])) {
                                $boxMapper = new BoxMapper();
                                $boxModel = new BoxModel();

                                $boxModel->setModule($config->config['key']);
                                foreach ($config->config['boxes'] as $key => $value) {
                                    $boxModel->addContent($key, $value);
                                }
                                $boxMapper->install($boxModel);
                            }
                        }
                    }
                }
            }
            $this->setContent($content);
            return true;
        } finally {
            $zip->close();
            unlink($this->zipFile);
            unlink($this->zipSigFile);
            $this->curlClose();
        }
    }

    /**
     * Close a cURL session.
     *
     * @return void
     */
    private function curlClose()
    {
        if (is_resource($this->transferUrl)) {
            curl_close($this->transferUrl);
        }
    }

    public function __destruct()
    {
        $this->curlClose();
    }
}
