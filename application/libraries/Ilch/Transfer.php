<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

use Ilch\Config\File;

class Transfer
{
    protected $transferUrl = '';

    protected $curlOpt = [];

    protected $versionNow;

    protected $newVersion;

    protected $zipSavePath;

    protected $zipFile;

    protected $zipFileName;

    protected $zipSigFile;

    protected $zipSigFileName;

    protected $downloadUrl;

    protected $downloadSignatureUrl;

    protected $content;

    protected $missingRequirements = [];

    /**
     * Sets the TransferUrl.
     * @var string $url
     * @return resource
     */
    public function setTransferUrl($url)
    {
        return $this->transferUrl = curl_init($url);
    }

    /**
     * Gets the TransferUrl.
     * @return string
     */
    public function getTransferUrl()
    {
        return $this->transferUrl;
    }

    /**
     * Sets the ZipFile.
     * @var string $path
     * @return string
     */
    public function setZipFile($path)
    {
        return $this->zipFile = $path;
    }

    /**
     * Gets the ZipFile.
     * @return string
     */
    public function getZipFile()
    {
        return $this->zipFile;
    }

    /**
     * Sets the ZipFileName.
     * @var string $name
     * @return string
     */
    public function setZipFileName($name)
    {
        return $this->zipFileName = $name;
    }

    /**
     * Gets the ZipFileName.
     * @return string
     */
    public function getZipFileName()
    {
        return $this->zipFileName;
    }

    /**
     * Sets the ZipFilePath.
     * @var string $path
     * @return string
     */
    public function setZipSavePath($path)
    {
        return $this->zipSavePath = $path;
    }

    /**
     * Gets the ZipSavePath.
     * @return string
     */
    public function getZipSavePath()
    {
        return $this->zipSavePath;
    }

    /**
     * USE IT AFTER newVersionFound()
     *
     * Sets the DownloadUrl.
     * @var $url
     * @return mixed
     */
    public function setDownloadUrl($url)
    {
        $this->zipFileName = basename($url);
        $this->zipFile = $this->zipSavePath.$this->zipFileName;
        $path_parts = pathinfo($url);
        $this->setDownloadSignatureUrl($path_parts['dirname'].'/'.$path_parts['filename'].'-signature.sig');
        return $this->downloadUrl = $url;
    }

    /**
     * USE IT AFTER newVersionFound()
     * 
     * Gets the DownloadUrl.
     * @return string
     */
    public function getDownloadUrl()
    {
        return $this->downloadUrl;
    }

    /**
     * USE IT AFTER newVersionFound()
     *
     * Sets the DownloadSignatureUrl.
     * @var $url
     * @return mixed
     */
    public function setDownloadSignatureUrl($url)
    {
        $this->zipSigFileName = basename($url);
        $this->zipSigFile = $this->zipSavePath.$this->zipSigFileName;
        return $this->downloadSignatureUrl = $url;
    }

    /**
     * USE IT AFTER newVersionFound()
     * 
     * Gets the DownloadSignatureUrl.
     * @return string
     */
    public function getDownloadSignatureUrl()
    {
        return $this->downloadSignatureUrl;
    }

    /**
     * Gets the Version.
     * @return string
     */
    public function getVersions()
    {
        $result = curl_exec($this->transferUrl);
        if ($result == false) {
            return '';
        }
        return $result;
    }

    /**
     * Gets the VersionList.
     * @return string[]
     */
    public function getVersionsList()
    {
        return json_decode($this->getVersions(), true);
    }

    /**
     * Gets the VersionNow.
     * @return string
     */
    public function getVersionNow()
    {
        return $this->versionNow;
    }

    /**
     * Gets the NewVersion.
     * @return string
     */
    public function getNewVersion()
    {
        return $this->newVersion;
    }

    /**
     * Sets the Content.
     * @var string $content
     * @return string
     */
    public function setContent($content)
    {
        return $this->content = $content;
    }

    /**
     * Gets the Content.
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Gets the content of missingRequirements.
     * @return array
     */
    public function getMissingRequirements()
    {
        return $this->missingRequirements;
    }

    /**
     * Sets the NewVersion.
     * @var string $version
     * @return string
     */
    public function setNewVersion($version)
    {
        return $this->newVersion = $version;
    }

    /**
     * Sets the VersionNow.
     * @var string $versionNow
     * @return string
     */
    public function setVersionNow($versionNow)
    {
        return $this->versionNow = $versionNow;
    }

    /**
     * @return true/false
     */
    public function newVersionFound()
    {
        foreach ($this->getVersionsList() as $version => $requirements) {
            if (version_compare(preg_replace('/\s+/', '', $version), $this->getVersionNow(), '>')) {
                $this->setNewVersion(trim(preg_replace('/\s\s+/','', $version)));
                $this->zipFile = $this->getZipSavePath().'Master-'.$this->getNewVersion().'.zip';
                $this->checkRequirements($requirements);
                return true;
            }
        }
        return false;
    }

    /**
     * Check the requirements for the update.
     * Writes missing requirements to the 'missingRequirements' property.
     *
     * @param $requirements
     * @return bool
     */
    public function checkRequirements($requirements)
    {
        if (!empty($requirements['phpVersion'])) {
            if (!version_compare(phpversion(), $requirements['phpVersion'], '>=')) {
                $this->missingRequirements['phpVersion'] = $requirements['phpVersion'];
            }
        }

        if (!empty($requirements['mysqlVersion']) || !empty($requirements['mariadbVersion'])) {
            $fileConfig = new File();
            $fileConfig->loadConfigFromFile(CONFIG_PATH.'/config.php');
            $hostParts = explode(':', $fileConfig->get('dbHost'));
            $port = (!empty($hostParts[1])) ? $hostParts[1] : null;
            $dbLinkIdentifier = mysqli_connect($fileConfig->get('dbHost'), $fileConfig->get('dbUser'), $fileConfig->get('dbPassword'), null, $port);

            if (strpos(mysqli_get_server_info($dbLinkIdentifier), 'MariaDB') !== false) {
                if (!version_compare(mysqli_get_server_info($dbLinkIdentifier), $requirements['mariadbVersion'], '>=')) {
                    $this->missingRequirements['mariadbVersion'] = $requirements['mariadbVersion'];
                }
            } else {
                if (!version_compare(mysqli_get_server_info($dbLinkIdentifier), $requirements['mysqlVersion'], '>=')) {
                    $this->missingRequirements['mysqlVersion'] = $requirements['mysqlVersion'];
                }
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
     * @param string $opt
     * @param string $param
     * @return $this
     */
    public function setCurlOpt($opt, $param)
    {
        if (!empty($this->transferUrl)) {
            $this->curlOpt[] = curl_setopt($this->transferUrl, $opt, $param);
            return $this;
        }
    }

    /**
     * @return false
     */
    public function save()
    {
        try {
            $newUpdate = url_get_contents($this->getDownloadUrl(), false, true);
            if (!is_dir($this->getZipSavePath())) mkdir ($this->getZipSavePath());
            $dlHandler = fopen($this->zipFile, 'w');
            fwrite($dlHandler, $newUpdate);
            fclose($dlHandler);

            $newUpdate = url_get_contents($this->getDownloadSignatureUrl(), false, true);
            $dlHandler = fopen($this->zipSigFile, 'w');
            fwrite($dlHandler, $newUpdate);
            fclose($dlHandler);
        } finally {
            $signature = file_get_contents($this->getZipFile().'-signature.sig');
            $pubKeyfile = ROOT_PATH.'/certificate/Certificate.crt';
            if (!$this->verifyFile($pubKeyfile, $this->getZipFile(), $signature)) {
                // Verification failed. Drop the potentially bad files.
                unlink($this->getZipFile());
                unlink($this->getZipFile().'-signature.sig');
                return false;
            }
            return true;
        }
    }

    /**
     * @param string $pubKeyfile
     * @param string $file
     * @param string $signature
     * @return true
     */
    public function verifyFile($pubKeyfile, $file, $signature)
    {
        $digest = hash_file('sha512', $file);

        $pubkey = openssl_pkey_get_public(file_get_contents($pubKeyfile));
        openssl_public_decrypt($signature, $decrypted_digest, $pubkey);

        if ($digest == $decrypted_digest) {
            return true;
        }
        return false;
    }

    /**
     * @param string $certificate
     * @return true
     */
    public function validateCert($certificate)
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
    public function checkIfDestinationIsWritable($pathZipFile)
    {
        $zip = new \ZipArchive();

        if ($zip->open($pathZipFile) !== true) {
            return false;
        }

        $notWritable = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $path = ROOT_PATH.'/'.$zip->getNameIndex($i);

            if (file_exists($path) && !is_writable($path)) {
                $notWritable[] = $path;
            }
        }

        $zip->close();
        return $notWritable;
    }

    /**
     * @param string $installedVersion
     * @return true/false
     */
    public function update($installedVersion)
    {
        try {
            $content = [];
            @set_time_limit(300);
            $zipHandle = zip_open($this->zipFile);
            if (!is_resource($zipHandle)) {
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
                foreach($notWritable as $path) {
                    $content[] = $path;
                }

                return false;
            }

            while ($aF = zip_read($zipHandle)) {
                $thisFileName = zip_entry_name($aF);
                $thisFileDir = dirname($thisFileName);

                //Continue if its not a file
                if (substr($thisFileName,-1,1) == '/') continue;

                //Make the directory if we need to...
                if (!is_dir (ROOT_PATH.'/'.$thisFileDir)) {
                    mkdir (ROOT_PATH.'/'.$thisFileDir, 0777, true );
                    $content[] = 'Created new directory: '.$thisFileDir;
                }

                //Overwrite the file
                if (!is_dir(ROOT_PATH.'/'.$thisFileName)) {
                    $content[] = 'New file: '.$thisFileName;
                    $contents = zip_entry_read($aF, zip_entry_filesize($aF));
                    $updateThis = @fopen(ROOT_PATH.'/'.$thisFileName, 'w');
                    $bytesWritten = @fwrite($updateThis, $contents);
                    $successfull = $updateThis !== false && $bytesWritten !== false;
                    @fclose($updateThis);
                    $successfull = $successfull && !($bytesWritten == 0 && $bytesWritten != strlen($contents));
                    unset($contents);

                    if (!$successfull) {
                        $content[] = 'Error writing new file: '.$thisFileName;
                        unset($aF);
                        return false;
                    }

                    //If we need to run commands, then do it.
                    if ($thisFileName == $thisFileDir.'/config.php') {
                        include $thisFileName;

                        $configClass = str_replace("/", "\\", str_replace('application', '', str_replace('.php', '', $thisFileName)));
                        if (class_exists($configClass)) {
                            $config = new $configClass();

                            if (method_exists($config, 'getUpdate')) {
                                $content[] = $config->getUpdate($installedVersion);
                            }
                        }
                    }
                }
            }
            return true;
        } finally {
            $this->setContent($content);
            if (is_resource($zipHandle)) {
                zip_close($zipHandle);
            }
            unlink($this->zipFile);
            unlink($this->zipFile.'-signature.sig');
            $this->curlClose();
        }
    }

    /**
     * @return true/false
     */
    public function install()
    {
        try {
            $zip = new \ZipArchive();
            if ($zip->open($this->zipFile) !== true) {
                return false;
            }
            $zip->extractTo(ROOT_PATH);
            $content = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $thisFileName = $zip->getNameIndex($i);
                $thisFileDir = dirname($thisFileName);
                //If we need to run commands, then do it.
                if ($thisFileName == $thisFileDir.'/config.php') {
                    include $thisFileName;
                    $configClass = str_replace("/", "\\", str_replace('application', '', str_replace('.php', '', $thisFileName)));
                    if (class_exists($configClass)) {
                        $config = new $configClass();
                        if (method_exists($config, 'install')) {
                            $content[] = $config->install();

                            // Skip module related stuff if this is a layout install.
                            if (strpos($thisFileName, 'application/modules/') === false) {
                                continue;
                            }

                            $moduleMapper = new \Modules\Admin\Mappers\Module();
                            $moduleModel = new \Modules\Admin\Models\Module();
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
                        }
                    }
                }
            }
            $this->setContent($content);
            return true;
        } finally {
            $zip->close();
            unlink($this->zipFile);
            unlink($this->zipFile.'-signature.sig');
            $this->curlClose();
        }
    }

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
