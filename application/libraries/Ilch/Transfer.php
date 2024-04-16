<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

use Ilch\Config\File;
use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Models\Box as BoxModel;

class Transfer
{
    /**
     * @var null|resource
     */
    protected $transferUrl = null;
    /**
     * @var array
     */
    protected $curlOpt = [];
    /**
     * @var string
     */
    protected $versionNow = '';
    /**
     * @var string
     */
    protected $newVersion = '';
    /**
     * @var string
     */
    protected $zipSavePath = '';
    /**
     * @var string
     */
    protected $zipFile = '';
    /**
     * @var string
     */
    protected $zipFileName = '';
    /**
     * @var string
     */
    protected $zipSigFile = '';
    /**
     * @var string
     */
    protected $zipSigFileName = '';
    /**
     * @var string
     */
    protected $downloadUrl = '';
    /**
     * @var string
     */
    protected $downloadSignatureUrl = '';
    /**
     * @var array
     */
    protected $content = [];
    /**
     * @var array
     */
    protected $missingRequirements = [];
    /**
         * Sets the TransferUrl.
         * @param string $url
         * @return Transfer
         */
    public function setTransferUrl(string $url): Transfer
    {
        $transferUrl = curl_init($url);
        if ($transferUrl) {
            $this->transferUrl = $transferUrl;
        }

        return $this;
    }

    /**
     * Gets the TransferUrl.
     * @return resource|null
     */
    public function getTransferUrl()
    {
        return $this->transferUrl;
    }

    /**
     * Sets the ZipFile.
     * @param string $path
     * @return string
     */
    public function setZipFile(string $path): string
    {
        return $this->zipFile = $path;
    }

    /**
     * Gets the ZipFile.
     * @return string
     */
    public function getZipFile(): string
    {
        return $this->zipFile;
    }

    /**
     * Sets the ZipFileName.
     * @param string $name
     * @return string
     */
    public function setZipFileName(string $name): string
    {
        return $this->zipFileName = $name;
    }

    /**
     * Gets the ZipFileName.
     * @return string
     */
    public function getZipFileName(): string
    {
        return $this->zipFileName;
    }

    /**
     * Sets the ZipFileName.
     * @param string $name
     * @return $this
     */
    public function setZipSigFile(string $name): Transfer
    {
        $this->zipSigFile = $name;
        return $this;
    }

    /**
     * Gets the ZipFileName.
     * @return string
     */
    public function getZipSigFile(): string
    {
        return $this->zipSigFile;
    }

    /**
     * Sets the ZipFileName.
     * @param string $name
     * @return Transfer
     */
    public function setZipSigFileName(string $name): Transfer
    {
        $this->zipSigFileName = $name;
        return $this;
    }

    /**
     * Gets the ZipFileName.
     * @return string
     */
    public function getZipSigFileName(): string
    {
        return $this->zipSigFileName;
    }

    /**
     * Sets the ZipFilePath.
     * @param string $path
     * @return Transfer
     */
    public function setZipSavePath(string $path): Transfer
    {
        $this->zipSavePath = $path;
        return $this;
    }

    /**
     * Gets the ZipSavePath.
     * @return string
     */
    public function getZipSavePath(): string
    {
        return $this->zipSavePath;
    }

    /**
     * USE IT AFTER newVersionFound()
     *
     * Sets the DownloadUrl.
     * @param string $url
     * @return $this
     */
    public function setDownloadUrl(string $url): Transfer
    {
        $this->setZipFileName(basename($url));
        $this->setZipFile($this->getZipSavePath() . $this->getZipFileName());
        $path_parts = pathinfo($url);
        $this->setDownloadSignatureUrl($path_parts['dirname'] . '/' . $path_parts['filename'] . '-signature.sig');

        $this->downloadUrl = $url;
        return $this;
    }

    /**
     * USE IT AFTER newVersionFound()
     *
     * Gets the DownloadUrl.
     * @return string
     */
    public function getDownloadUrl(): string
    {
        return $this->downloadUrl;
    }

    /**
     * USE IT AFTER newVersionFound()
     *
     * Sets the DownloadSignatureUrl.
     * @param string $url
     * @return $this
     */
    public function setDownloadSignatureUrl(string $url): Transfer
    {
        $this->setZipSigFileName(basename($url));
        $this->setZipSigFile($this->getZipSavePath() . $this->getZipSigFileName());

        $this->downloadSignatureUrl = $url;
        return $this;
    }

    /**
     * USE IT AFTER newVersionFound()
     *
     * Gets the DownloadSignatureUrl.
     * @return string
     */
    public function getDownloadSignatureUrl(): string
    {
        return $this->downloadSignatureUrl;
    }

    /**
     * Gets the Version.
     * @return string
     */
    public function getVersions(): string
    {
        $result = curl_exec($this->getTransferUrl());
        if (!is_string($result)) {
            return '';
        }
        return $result;
    }

    /**
     * Gets the VersionList.
     * @return array|null
     */
    public function getVersionsList(): ?array
    {
        return json_decode($this->getVersions(), true);
    }

    /**
     * Gets the VersionNow.
     * @return string
     */
    public function getVersionNow(): string
    {
        return $this->versionNow;
    }

    /**
     * Gets the NewVersion.
     * @return string
     */
    public function getNewVersion(): string
    {
        return $this->newVersion;
    }

    /**
     * Sets the Content.
     * @param array $content
     * @return Transfer
     */
    public function setContent(array $content): Transfer
    {
        $this->content = $content;
        return $this;
    }

    /**
     * add the Content.
     * @param mixed $content
     * @return Transfer
     */
    public function addContent($content): Transfer
    {
        $this->content[] = $content;
        return $this;
    }

    /**
     * Gets the Content.
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Gets the content of missingRequirements.
     * @return array
     */
    public function getMissingRequirements(): array
    {
        return $this->missingRequirements;
    }

    /**
     * Sets the NewVersion.
     * @param string $version
     * @return Transfer
     */
    public function setNewVersion(string $version): Transfer
    {
        $this->newVersion = $version;
        return $this;
    }

    /**
     * Sets the VersionNow.
     * @param string $versionNow
     * @return Transfer
     */
    public function setVersionNow(string $versionNow): Transfer
    {
        $this->versionNow = $versionNow;
        return $this;
    }

    /**
     * Gets the versionslist and checks if there is a new version available.
     *
     * @return bool
     */
    public function newVersionFound(): bool
    {
        $versionsList = $this->getVersionsList();
        if ($versionsList !== null) {
            foreach ($versionsList as $version => $requirements) {
                if (version_compare(preg_replace('/\s+/', '', $version), $this->getVersionNow(), '>')) {
                    $this->setNewVersion(trim(preg_replace('/\s\s+/', '', $version)));
                    $this->setZipFile($this->getZipSavePath() . 'Master-' . $this->getNewVersion() . '.zip');
                    $this->checkRequirements($requirements);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check the requirements for the update.
     * Writes missing requirements to the 'missingRequirements' property.
     *
     * @param array|null $requirements
     * @return bool
     */
    public function checkRequirements(?array $requirements): bool
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
     * @param int $opt
     * @param mixed $param
     * @return $this
     */
    public function setCurlOpt(int $opt, $param): Transfer
    {
        if (!$this->getTransferUrl()) {
            $this->curlOpt[$opt] = curl_setopt($this->getTransferUrl(), $opt, $param);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        try {
            $newUpdate = url_get_contents($this->getDownloadUrl(), false, true);
            if (!is_dir($this->getZipSavePath())) {
                mkdir($this->getZipSavePath());
            }
            $dlHandler = fopen($this->getZipFile(), 'wb');
            fwrite($dlHandler, $newUpdate);
            fclose($dlHandler);
            $newUpdate = url_get_contents($this->getDownloadSignatureUrl(), false, true);
            $dlHandler = fopen($this->getZipSigFile(), 'wb');
            fwrite($dlHandler, $newUpdate);
            fclose($dlHandler);
        } finally {
            $signature = file_get_contents($this->getZipFile() . '-signature.sig');
            $pubKeyfile = ROOT_PATH . '/certificate/Certificate.crt';
            if (!$this->verifyFile($pubKeyfile, $this->getZipFile(), $signature)) {
                // Verification failed. Drop the potentially bad files.
                unlink($this->getZipFile());
                unlink($this->getZipFile() . '-signature.sig');
                return false;
            }
            return true;
        }
    }

    /**
     * @param string $pubKeyfile
     * @param string $file
     * @param string|null $signature
     * @return bool
     */
    public function verifyFile(string $pubKeyfile, string $file, ?string $signature): bool
    {
        if (!$signature || !is_file($pubKeyfile) || !is_file($file)) {
            return false;
        }

        $digest = hash_file('sha512', $file);
        $pubkey = openssl_pkey_get_public(file_get_contents($pubKeyfile));
        openssl_public_decrypt($signature, $decrypted_digest, $pubkey);
        return $digest == $decrypted_digest;
    }

    /**
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
     * @param string $installedVersion
     * @param bool $checkSig
     * @return bool
     */
    public function update(string $installedVersion, bool $checkSig = false): bool
    {
        $fail = true;

        @set_time_limit(300);
        $zip = new \ZipArchive();
        $content = [];
        try {
            if ($checkSig) {
                $signature = file_get_contents($this->getZipFile() . '-signature.sig');
                $pubKeyfile = ROOT_PATH . '/certificate/Certificate.crt';
                if (!$this->verifyFile($pubKeyfile, $this->getZipFile(), $signature)) {
                    $content[] = 'Verification failed.';
                    return false;
                }
            }

            $res = $zip->open($this->getZipFile());
            if ($res !== true) {
                $content[] = 'Failed to open update file.';
                return false;
            }

            // Check if it would be possible to write the changed files.
            $notWritable = $this->checkIfDestinationIsWritable($this->getZipFile());
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

            $configInstaller = [];

            // TODO: Backup Files & DB

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
                        $configInstaller[] = $configClass;
                    }
                }
            }

            foreach ($configInstaller as $configClass) {
                $config = new $configClass();
                if (method_exists($config, 'getUpdate')) {
                    $content[] = $config->getUpdate($installedVersion);
                }
            }

            $fail = false;
        } catch (\Exception $e) {
            // TODO: Reload Backup Files & DB
        } finally {
            $this->setContent($content);
            $zip->close();
            unlink($this->getZipFile());
            unlink($this->getZipFile() . '-signature.sig');
            $this->curlClose();
        }

        return !$fail;
    }

    /**
     * @param bool $checkSig
     * @return bool
     */
    public function install(bool $checkSig = false): bool
    {
        $fail = true;

        $zip = new \ZipArchive();
        try {
            if ($checkSig) {
                $signature = file_get_contents($this->getZipFile() . '-signature.sig');
                $pubKeyfile = ROOT_PATH . '/certificate/Certificate.crt';
                if (!$this->verifyFile($pubKeyfile, $this->getZipFile(), $signature)) {
                    $content[] = 'Verification failed.';
                    return false;
                }
            }

            if ($zip->open($this->getZipFile()) !== true) {
                return false;
            }
            $zip->extractTo(ROOT_PATH);
            $content = [];
            $configInstaller = [];

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $thisFileName = $zip->getNameIndex($i);
                $thisFileDir = dirname($thisFileName);
                //If we need to run commands, then do it.
                if ($thisFileName == $thisFileDir . '/config.php') {
                    invalidateOpcache($thisFileName, true);
                    include $thisFileName;
                    $configClass = str_replace(array('.php', 'application', '/'), array('', '', "\\"), $thisFileName);
                    if (class_exists($configClass)) {
                        // Skip module related stuff if this is a layout install.
                        if (strpos($thisFileName, 'application/modules/') === false) {
                            continue;
                        }
                        $configInstaller[] = $configClass;
                    }
                }
            }

            foreach ($configInstaller as $configClass) {
                $config = new $configClass();
                if (method_exists($config, 'install')) {
                    $content[] = $config->install();

                    $moduleMapper = new \Modules\Admin\Mappers\Module();
                    $moduleModel = new \Modules\Admin\Models\Module();
                    //$moduleModel->setByArray($config->config);

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

            $this->setContent($content);
            $fail = false;
        } catch (\Exception $e) {
            // TODO: Del Files & uninstall
        } finally {
            $zip->close();
            unlink($this->getZipFile());
            unlink($this->getZipFile() . '-signature.sig');
            $this->curlClose();
        }
        return !$fail;
    }

    private function curlClose()
    {
        if (is_resource($this->getTransferUrl())) {
            curl_close($this->getTransferUrl());
        }
    }

    public function __destruct()
    {
        $this->curlClose();
    }
}
