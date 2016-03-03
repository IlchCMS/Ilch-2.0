<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Update
{
    protected $updateUrl;

    protected $curlOpt = array();

    protected $versionNow;

    protected $newVersion;

    protected $zipSavePath;

    protected $zipFile;

    protected $downloadUrl;

    protected $content;

    /**
    * @return
    */
    public function setUpdateUrl($url)
    {
        return $this->updateUrl = curl_init($url);
    }

    /**
    * @return
    */
    public function getUpdateUrl()
    {
        return $this->updateUrl;
    }

    /**
    * @return
    */
    public function setZipSavePath($path)
    {
        return $this->zipSavePath = $path;
    }

    /**
    * @return
    */
    public function getZipSavePath()
    {
        return $this->zipSavePath;
    }

    /**
    * USE IT AFTER newVersionFound()
    * @return
    */
    public function setDownloadUrl($url)
    {
        return $this->downloadUrl = $url;
    }

    /**
    * @return
    */
    public function getDownloadUrl()
    {
        return $this->downloadUrl;
    }

    /**
    * @return
    */
    public function getVersions()
    {
        return curl_exec($this->updateUrl);
    }

    /**
    * @return
    */
    public function getVersionsList()
    {
        return explode("\n", $this->getVersions());
    }

    /**
    * @return
    */
    public function getVersionNow()
    {
        return $this->versionNow;
    }

    /**
    * @return
    */
    public function setNewVersion($version)
    {
        return $this->newVersion = $version;
    }

    /**
    * @return
    */
    public function getNewVersion()
    {
        return $this->newVersion;
    }

    /**
    * @return
    */
    public function setContent($content)
    {
        return $this->content = $content;
    }

    /**
    * @return
    */
    public function getContent()
    {
        return $this->content;
    }

    /**
    * @return
    */
    public function setVersionNow($versionNow)
    {
        return $this->versionNow = $versionNow;
    }

    /**
    * @return
    */
    public function newVersionFound()
    {
        foreach ($this->getVersionsList() as $vL) {
            if (preg_replace('/\s+/', '', $vL) > $this->getVersionNow()){
                $this->setNewVersion(trim(preg_replace('/\s\s+/','', $vL)));
                $this->zipFile = $this->getZipSavePath().'Master-'.$this->getNewVersion().'.zip';
                return true;
            }
            return false;
        }
        
    }

    /**
    * @return
    */
    public function setCurlOpt($opt, $param)
    {
        $this->curlOpt[] = curl_setopt($this->updateUrl, $opt, $param);
        return $this;
    }

    /**
    * @return
    */
    public function save()
    {
        if (!$this->zipFile) {
            $newUpdate = file_get_contents($this->getDownloadUrl());
                if (!is_dir($this->getZipSavePath())) mkdir ($this->getZipSavePath());
                $dlHandler = fopen($this->zipFile, 'w');
                if (!fwrite($dlHandler, $newUpdate)) {

                }
            fclose($dlHandler);
        }
    }

    /**
    * @return
    */
    public function update()
    {
        require_once APPLICATION_PATH.'/libraries/PclZip/PclZip.php';

        $archive = new \PclZip\PclZip();
        $archive->PclZip($this->zipFile);
        $archive->extract(PCLZIP_OPT_PATH, ROOT_PATH);
        $listContent = $archive->listContent();
        foreach ($listContent as $content) {
            $fileName = $content['filename'];
            $fileDir = dirname($fileName);

            if (is_file($fileName)) {
                $contents[] = 'New file: '.$fileName.'...........';
            }
            //If we need to run commands, then do it.
            if ($fileName == $fileDir.'/config.php') {
                include $fileName;

                $configClass = str_replace("/", "\\", str_replace('application', '', str_replace('.php', '', $fileName)));
                $config = new $configClass();
                $contents[] = $config->getUpdate();
            }
        }
        $this->setContent($contents);
        return true;
    }
}
