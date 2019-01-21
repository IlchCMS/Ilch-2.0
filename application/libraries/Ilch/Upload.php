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
     * @var string $allowedExtensions
     */
    protected $allowedExtensions;

    /**
     * @var string $path
     */
    protected $path;

    /**
     * @var string $path
     */
    protected $mediaExtImage;

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
        $search = ["Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´", " "];
        $replace = ["Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "", ""];
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
     * @param string $allowedExtensions
     *
     * @return string allowedExtensions
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;

        return $this;
    }

    /**
     * @return string
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
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
            $units = ['B', 'KB', 'MB', 'GB'];

            if (array_key_exists($unit, $units) === true)
            {
                return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
            }
        }

        return $bytes;
    }

    /**
     * Take an educated guess on how big the image is going to be in memory.
     * @param string $imageFilePath
     * @return int required memory in bytes
     */
    public function guessRequiredMemory($imageFilePath)
    {
        $imageInfo = getimagesize($imageFilePath);
        if (empty($imageInfo['channels'])) {
            $imageInfo['channels'] = 4;
        }
        // (width * height * bits / 8) * channels * tweak-factor
        // channels will be 3 for RGB pictures and 4 for CMYK pictures
        // bits is the number of bits for each color.
        // The tweak-factor might be overly careful and could therefore be lowered if necessary.
        return ($imageInfo[0] * $imageInfo[1] * ($imageInfo['bits'] / 8) * $imageInfo['channels'] * 1.9);
    }

    /**
     * Returns if there would likely be enough free memory for the image.
     * Returns true in case of memory_limit = -1 e.g. no memory limit.
     *
     * @param string $imageFilePath
     * @return bool
     * @since 2.1.20
     */
    public function enoughFreeMemory($imageFilePath) {
        $memoryLimit = ini_get('memory_limit');
        if ($memoryLimit == '-1') {
            return true;
        }

        if (($this->returnBytes($memoryLimit) - memory_get_usage(true)) < $this->guessRequiredMemory($imageFilePath)) {
            return false;
        }

        return true;
    }

    /**
     * Upload image file and create thumbnail for it.
     */
    public function upload()
    {
        $hash = uniqid() . $this->getName();
        $this->setUrl($this->path.$hash.'.'.$this->getEnding());
        $this->setUrlThumb($this->path.'thumb_'.$hash.'.'.$this->getEnding());

        if (move_uploaded_file($_FILES['upl']['tmp_name'], $this->path.$hash.'.'.$this->getEnding())) {
            if (in_array($this->getEnding() , explode(' ', $this->types))) {
                if (!$this->enoughFreeMemory($this->path.$hash.'.'.$this->getEnding())) {
                    return;
                }
                $this->createThumbnail();
            }
        }
    }

    /**
     * Check if the file extension is in the list of allowed file extensions.
     *
     * @return bool
     */
    public function isAllowedExtension()
    {
        return in_array($this->getEnding(), explode(' ', $this->getAllowedExtensions()));
    }

    /**
     * Convert for example the memory_limit of php (example: 128M) to bytes.
     *
     * @param $size_str
     * @return float|int
     */
    public function returnBytes ($size_str)
    {
        switch (substr($size_str, -1))
        {
            case 'M': case 'm': return (int)$size_str * 1048576;
            case 'K': case 'k': return (int)$size_str * 1024;
            case 'G': case 'g': return (int)$size_str * 1073741824;
            default: return $size_str;
        }
    }

    /**
     * Rename the images to uniquie names and create thumbnails for them.
     */
    public function save()
    {
        $hash = uniqid() . $this->getName();
        $this->setUrl($this->path.$hash.'.'.$this->getEnding());
        $this->setUrlThumb($this->path.'thumb_'.$hash.'.'.$this->getEnding());

        rename($this->path.$this->getName().'.'.$this->getEnding(), $this->path.$hash.'.'.$this->getEnding());
        if (in_array($this->getEnding() , explode(' ', $this->types))) {
            if (!$this->enoughFreeMemory($this->path.$hash.'.'.$this->getEnding())) {
                return;
            }
            $this->createThumbnail();
        }
    }

    /**
     * Create thumbnail
     *
     * @return bool
     */
    public function createThumbnail()
    {
        if (!$this->enoughFreeMemory($this->getUrl())) {
            return false;
        }

        $thumb = new \Thumb\Thumbnail();
        $thumb->Thumbprefix = 'thumb_';
        $thumb->Thumblocation = $this->path;
        $thumb->Thumbsize = 300;
        $thumb->Createthumb($this->getUrl(),'file');

        $this->setUrlThumb($this->path.'thumb_'.basename($this->getUrl()));
        return true;
    }
}
