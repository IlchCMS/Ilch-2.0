<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

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
            return;
        }
        return $result;
    }

    /**
     * Gets the VersionList.
     * @return string
     */
    public function getVersionsList()
    {
        return explode("\n", $this->getVersions());
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
        foreach ($this->getVersionsList() as $vL) {
            if (preg_replace('/\s+/', '', $vL) > $this->getVersionNow()) {
                $this->setNewVersion(trim(preg_replace('/\s\s+/','', $vL)));
                $this->zipFile = $this->getZipSavePath().'Master-'.$this->getNewVersion().'.zip';
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $opt
     * @param string $param
     * @return array
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
        if (!file_exists($this->zipFile)) {
            $newUpdate = url_get_contents($this->getDownloadUrl());
            if (!is_dir($this->getZipSavePath())) mkdir ($this->getZipSavePath());
            $dlHandler = fopen($this->zipFile, 'w');
            fwrite($dlHandler, $newUpdate);
            fclose($dlHandler);

            $newUpdate = url_get_contents($this->getDownloadSignatureUrl());
            $dlHandler = fopen($this->zipSigFile, 'w');
            fwrite($dlHandler, $newUpdate);
            fclose($dlHandler);
        }
        return false;
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
        if (!is_file(ROOT_PATH.'/certificate/Certificate.crt')) {
            return false;
        }

        $public_key = file_get_contents($certificate);

        $certinfo = openssl_x509_parse($public_key);
        $validTo = $certinfo['validTo_time_t'];

        if ($validTo >= time()) {
            return true;
        }
        return false;
    }

    /**
     * @return true
     */
    public function update()
    {
        $zipHandle = zip_open($this->zipFile);
        $content = [];
        while ($aF = zip_read($zipHandle)) {

            $thisFileName = zip_entry_name($aF);
            $thisFileDir = dirname($thisFileName);

            //Continue if its not a file
            if (substr($thisFileName,-1,1) == '/') continue;

            //Make the directory if we need to...
            if (!is_dir (ROOT_PATH.'/'.$thisFileDir)) {
                mkdir (ROOT_PATH.'/'.$thisFileDir, 0777, true );
                $content[] = 'Created new Directory: '.$thisFileDir;
            }

            //Overwrite the file
            if (!is_dir(ROOT_PATH.'/'.$thisFileName)) {
                $content[] = 'New file: '.$thisFileName.'...........';
                $contents = zip_entry_read($aF, zip_entry_filesize($aF));
                $contents = str_replace("\r\n", "\n", $contents);
                $updateThis = @fopen(ROOT_PATH.'/'.$thisFileName, 'w');
                @fwrite($updateThis, $contents);
                @fclose($updateThis);
                unset($contents);

                //If we need to run commands, then do it.
                if ($thisFileName == $thisFileDir.'/config.php') {
                    include $thisFileName;

                    $configClass = str_replace("/", "\\", str_replace('application', '', str_replace('.php', '', $thisFileName)));
                    if (class_exists($configClass)) {
                        $config = new $configClass();

                        if (method_exists($config, 'getUpdate')) {
                            $content[] = $config->getUpdate();
                        }
                    }
                } 
            }
        }
        zip_close($zipHandle);
        unlink($this->zipFile);
        unlink($this->zipFile.'-signature.sig');
        $this->curlClose();
        $this->setContent($content);
        return true;
    }

    /**
     * @return true
     */
    public function install()
    {
        $zip = new \ZipArchive();
        $zip->open($this->zipFile);
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
        $zip->close();
        unlink($this->zipFile);
        unlink($this->zipFile.'-signature.sig');
        $this->curlClose();
        $this->setContent($content);
        return true;
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
