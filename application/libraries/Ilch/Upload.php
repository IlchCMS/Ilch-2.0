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
class Upload
{
    /**
     * @var string $file
     */
    protected string $file;

    /**
     * @var string $url
     */
    protected string $url;

    /**
     * @var string $urlThumb
     */
    protected string $urlThumb;

    /**
     * @var string $allowedExtensions
     */
    protected string $allowedExtensions;

    /**
     * @var string $path
     */
    protected string $path;

    /**
     * Set file. This is a complete path with filename and extension.
     *
     * @param string $file
     * @return Upload
     */
    public function setFile(string $file): Upload
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file. This is a complete path with filename and extension.
     *
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Get the extension of file. This gets determined from the value of file.
     *
     * @return string
     */
    public function getExtension(): string
    {
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

    /**
     * Get the name of the file. This gets determined from the value of file.
     *
     * @return string
     */
    public function getName(): string
    {
        return pathinfo($this->file, PATHINFO_FILENAME);
    }

    /**
     * Set the url.
     *
     * @param string $url
     * @return Upload
     */
    public function setUrl(string $url): Upload
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set allowed file exensions.
     *
     * @param string $allowedExtensions The file extensions are separated by spaces.
     * @return Upload
     */
    public function setAllowedExtensions(string $allowedExtensions): Upload
    {
        $this->allowedExtensions = $allowedExtensions;

        return $this;
    }

    /**
     * Get allowed file extensions. The file extensions are separated by spaces.
     *
     * @return string
     */
    public function getAllowedExtensions(): string
    {
        return $this->allowedExtensions;
    }

    /**
     * Set the destination path of the file.
     *
     * @param string $path
     * @return Upload
     */
    public function setPath(string $path): Upload
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the destination path of the file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the thumbnail url of the file.
     *
     * @param string $urlThumb
     * @return Upload
     */
    public function setUrlThumb(string $urlThumb): Upload
    {
        $this->urlThumb = $urlThumb;

        return $this;
    }

    /**
     * Get the thumbnail url of the file.
     *
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
        return (int)($imageInfo[0] * $imageInfo[1] * ($imageInfo['bits'] / 8) * $imageInfo['channels'] * 1.9);
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
        $extension = $this->getExtension();
        $hash = uniqid() . $this->getName();
        $this->setUrl($this->path . $hash . '.' . $extension);
        $this->setUrlThumb($this->path . 'thumb_' . $hash . '.' . $extension);

        if (move_uploaded_file($_FILES['upl']['tmp_name'], $this->path . $hash . '.' . $extension) && $this->isAllowedExtension()) {
            if (!in_array($extension, ['jpg', 'jpeg', 'gif', 'png'])) {
                // EasyPhpThumbnail only supports these image types.
                return;
            }

            // Don't create a thumbnail if the image is already thumbnail size.
            $imageSize = getimagesize($this->path . $hash . '.' . $extension);
            if ($imageSize !== false) {
                if ($imageSize[0] <= 300 && $imageSize[1] <= 300) {
                    $this->setUrlThumb($this->path . $hash . '.' . $extension);
                    return;
                }
            }

            if (!$this->enoughFreeMemory($this->path . $hash . '.' . $extension)) {
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
        return in_array(strtolower($this->getExtension()), explode(' ', strtolower($this->getAllowedExtensions())));
    }

    /**
     * Convert for example the memory_limit of php (example: 128M) to bytes.
     *
     * @param string $size_str
     * @return int|string
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
        $name = $this->getName();
        $extension = $this->getExtension();
        $hash = uniqid() . $name;
        $this->setUrl($this->path . $hash . '.' . $extension);
        $this->setUrlThumb($this->path . 'thumb_' . $hash . '.' . $extension);

        rename($this->path . $name . '.' . $extension, $this->path . $hash . '.' . $extension);
        if ($this->isAllowedExtension()) {
            if (!$this->enoughFreeMemory($this->path . $hash . '.' . $extension)) {
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

        $this->setUrlThumb($this->path . 'thumb_' . basename($this->getUrl()));
        return true;
    }
}
