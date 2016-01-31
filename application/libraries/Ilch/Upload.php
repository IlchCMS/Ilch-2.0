<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

/**
 * Ilch/Upload class.
 */
class Upload extends \Ilch\Controller\Base
{
    /**
     * @var string $file
     */
    protected $file;

    /**
     * @var string $ending
     */
    protected $ending;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $fileName
     */
    protected $fileName;

    /**
     * @var string $url
     */
    protected $url;

    /**
     * @var string $urlThumb
     */
    protected $urlThumb;

    /**
     * @var string $types
     */
    protected $types;

    /**
     * @var string $path
     */
    protected $path;

    /**
     * @var string $size
     */
    protected $size;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Resets
     */
    public function reset()
    {
        $this->file = null;
        $this->ending = null;
        $this->name = null;
        $this->fileName = null;
        $this->url = null;
        $this->urlThumb = null;
        $this->path = null;
        $this->mediaExtImage = null;
        $this->size = null;
        return $this;
    }

    /**
     * @param string $file
     *
     * @return string File
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $ending
     *
     * @return string Ending
     */
    public function setEnding($ending)
    {
        $this->ending = $ending;

        return $this;
    }

    /**
     * @return string
     */
    public function getEnding()
    {
        $this->ending = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));

        return $this->ending;
    }

    /**
     * @param string $name
     *
     * @return string Name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´", " ");
        $replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "", "");
        $this->name = str_replace($search, $replace, pathinfo($this->file, PATHINFO_FILENAME));

        return $this->name;
    }

    /**
     * @param string $fileName
     *
     * @return string fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $url
     *
     * @return string url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $types
     *
     * @return string types
     */
    public function setTypes($types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param string $path
     *
     * @return string path
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $urlThumb
     *
     * @return string urlThumb
     */
    public function setUrlThumb($urlThumb)
    {
        $this->urlThumb = $urlThumb;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlThumb()
    {
        return $this->urlThumb;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        $bytes = sprintf('%u', filesize($this->file));

        if ($bytes > 0)
        {
            $unit = intval(log($bytes, 1024));
            $units = array('B', 'KB', 'MB', 'GB');

            if (array_key_exists($unit, $units) === true)
            {
                return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
            }
        }

        return $bytes;
    }

    public function upload()
    {
        $hash = uniqid() . $this->getName();
        $this->setUrl($this->path.$hash.'.'.$this->getEnding());
        $this->setUrlThumb($this->path.'thumb_'.$hash.'.'.$this->getEnding());

        if(move_uploaded_file($_FILES['upl']['tmp_name'], $this->path.$hash.'.'.$this->getEnding())){
            if(in_array($this->getEnding() , explode(' ', $this->types))){
                $thumb = new \Thumb\Thumbnail();
                $thumb -> Thumbprefix = 'thumb_';
                $thumb -> Thumblocation = $this->path;
                $thumb -> Thumbsize = 300;
                $thumb -> Square = 300;
                $thumb -> Cropimage = array(3,1,50,50,50,50);
                $thumb -> Createthumb($this->path.$hash.'.'.$this->getEnding(),'file');
            }
        }
    }

    public function save()
    {
        $hash = uniqid() . $this->getName();
        $this->setUrl($this->path.$hash.'.'.$this->getEnding());
        $this->setUrlThumb($this->path.'thumb_'.$hash.'.'.$this->getEnding());

        rename($this->path.$this->getName().'.'.$this->getEnding(), $this->path.$hash.'.'.$this->getEnding());
        if(in_array($this->getEnding() , explode(' ', $this->types))){
            $thumb = new \Thumb\Thumbnail();
            $thumb -> Thumbprefix = 'thumb_';
            $thumb -> Thumblocation = $this->path;
            $thumb -> Thumbsize = 300;
            $thumb -> Square = 300;
            $thumb -> Cropimage = array(3,1,50,50,50,50);
            $thumb -> Createthumb($this->path.$hash.'.'.$this->getEnding(),'file');
        }
    }
}
