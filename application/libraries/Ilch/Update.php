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
    public function setNewVersion($version)
    {
        return $this->newVersion = $version;
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
        if (!$this->getZipSavePath().'Master-'.$this->getNewVersion().'.zip') {
            $newUpdate = file_get_contents($this->getDownloadUrl());
                if (!is_dir($this->getZipSavePath())) mkdir ($this->getZipSavePath());
                $dlHandler = fopen($this->getZipSavePath().'Master-'.$this->getNewVersion().'.zip', 'w');
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
        $zipHandle = zip_open($this->getZipSavePath().'Master-'.$this->getNewVersion().'.zip');
        $content = array();
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
                    $config = new $configClass();

                    $content[] = $config->getUpdate();
                } 
            }
        }
        curl_close($this->getUpdateUrl());
        $this->setContent($content);
        return true;
    }
}
