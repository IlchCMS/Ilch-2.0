<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

use Thumb\Thumbnail;

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
    public function reset(): Upload
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
     * @return Upload File
     */
    public function setFile(string $file): Upload
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $ending
     *
     * @return Upload Ending
     */
    public function setEnding(string $ending): Upload
    {
        $this->ending = $ending;

        return $this;
    }

    /**
     * @return string
     */
    public function getEnding(): string
    {
        $this->ending = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));

        return $this->ending;
    }

    /**
     * @param string $name
     *
     * @return Upload Name
     */
    public function setName(string $name): Upload
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        $this->name = pathinfo($this->file, PATHINFO_FILENAME);

        return $this->name;
    }

    /**
     * @param string $fileName
     *
     * @return Upload fileName
     */
    public function setFileName(string $fileName): Upload
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $url
     *
     * @return Upload url
     */
    public function setUrl(string $url): Upload
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $types
     *
     * @return Upload types
     */
    public function setTypes(string $types): Upload
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypes(): string
    {
        return $this->types;
    }

    /**
     * @param string $allowedExtensions
     *
     * @return Upload allowedExtensions
     */
    public function setAllowedExtensions(string $allowedExtensions): Upload
    {
        $this->allowedExtensions = $allowedExtensions;

        return $this;
    }

    /**
     * @return string
     */
    public function getAllowedExtensions(): string
    {
        return $this->allowedExtensions;
    }

    /**
     * @param string $path
     *
     * @return Upload path
     */
    public function setPath(string $path): Upload
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $urlThumb
     *
     * @return Upload urlThumb
     */
    public function setUrlThumb(string $urlThumb): Upload
    {
        $this->urlThumb = $urlThumb;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlThumb(): string
    {
        return $this->urlThumb;
    }

    /**
     * Get size of the file in a human readable form.
     *
     * @return string
     * @since 2.0.0
     */
    public function getSize(): string
    {
        $bytes = sprintf('%u', filesize($this->file));

        if ($bytes > 0) {
            return formatBytes($bytes);
        }

        return $bytes;
    }

    /**
     * Take an educated guess on how big the image is going to be in memory.
     *
     * @param string $imageFilePath
     * @return int|false required memory in bytes or false in case of an error.
     * @since 2.0.0
     * @since 2.1.54 returns false in case of an error.
     */
    public function guessRequiredMemory(string $imageFilePath)
    {
        $imageInfo = getimagesize($imageFilePath);

        if ($imageInfo === false) {
            return false;
        }

        if (empty($imageInfo['channels'])) {
            $imageInfo['channels'] = 4;
        }
        // (width * height * bits / 8) * channels * tweak-factor
        // channels will be 3 for RGB pictures and 4 for CMYK pictures
        // bits is the number of bits for each color.
        // The tweak-factor might be overly careful and could therefore be lowered if necessary.
        // After testing with >10k pictures the tweak factor got lowered to 1.9 (90 %) from 2.5 (150 %)
        // With a tweak factor of 1.9 there were still no out of memory errors.
        return ($imageInfo[0] * $imageInfo[1] * ($imageInfo['bits'] / 8) * $imageInfo['channels'] * 1.9);
    }

    /**
     * Returns if there would likely be enough free memory for the image.
     * Returns true in case of memory_limit = -1 e.g. no memory limit.
     * Returns false if not an image or an error occured.
     *
     * @param string $imageFilePath
     * @return bool
     * @since 2.1.20
     * @since 2.1.54 Returns false if not an image or an error occured.
     */
    public function enoughFreeMemory(string $imageFilePath): bool
    {
        $memoryLimit = ini_get('memory_limit');
        if ($memoryLimit == '-1') {
            return true;
        }

        if (getimagesize($imageFilePath) === false) {
            return false;
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

        if (move_uploaded_file($_FILES['upl']['tmp_name'], $this->path . $hash . '.' . $this->getEnding()) && in_array($this->getEnding(), explode(' ', $this->types))) {
            if (!$this->enoughFreeMemory($this->path.$hash.'.'.$this->getEnding())) {
                return;
            }
            $this->createThumbnail();
        }
    }

    /**
     * Check if the file extension is in the list of allowed file extensions.
     *
     * @return bool
     */
    public function isAllowedExtension(): bool
    {
        return in_array($this->getEnding(), explode(' ', $this->getAllowedExtensions()));
    }

    /**
     * Convert for example the memory_limit of php (example: 128M) to bytes.
     *
     * @param string $size_str
     * @return float|int
     * @since 2.0.0
     */
    public function returnBytes(string $size_str)
    {
        switch (substr($size_str, -1)) {
            case 'M':
            case 'm':
                return (int)$size_str * 1048576;
            case 'K':
            case 'k':
                return (int)$size_str * 1024;
            case 'G':
            case 'g':
                return (int)$size_str * 1073741824;
            default:
                return $size_str;
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
        if (in_array($this->getEnding(), explode(' ', $this->types))) {
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
     * @since 2.1.20
     */
    public function createThumbnail(): bool
    {
        if (!$this->enoughFreeMemory($this->getUrl())) {
            return false;
        }

        $thumb = new Thumbnail();
        $thumb->Thumbprefix = 'thumb_';
        $thumb->Thumblocation = $this->path;
        $thumb->Thumbsize = 300;
        $thumb->Createthumb($this->getUrl(), 'file');

        $this->setUrlThumb($this->path.'thumb_'.basename($this->getUrl()));
        return true;
    }
}
