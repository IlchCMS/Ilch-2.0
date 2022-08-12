<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper;

class GetMedia
{
    /**
     * @var string
     */
    protected $mediaButton = '';

    /**
     * @var string
     */
    protected $actionButton = '';

    /**
     * @var string
     */
    protected $uploadController = '';

    /**
     * @var string
     */
    protected $inputId = '';

    /**
     * Sets the mediaButton.
     *
     * @param string $mediaButton
     * @return $this
     */
    public function addMediaButton(string $mediaButton): GetMedia
    {
        $this->mediaButton = $mediaButton;

        return $this;
    }

    /**
     * Sets the actionButton.
     *
     * @param string $actionButton
     * @return GetMedia
     */
    public function addActionButton(string $actionButton): GetMedia
    {
        $this->actionButton = $actionButton;

        return $this;
    }

    /**
     * Sets the uploadController.
     *
     * @param string $uploadController
     * @return GetMedia
     */
    public function addUploadController(string $uploadController): GetMedia
    {
        $this->uploadController = $uploadController;

        return $this;
    }

    /**
     * Sets the inputId.
     *
     * @param string $inputId
     * @return GetMedia
     */
    public function addInputId(string $inputId): GetMedia
    {
        $this->inputId = $inputId;

        return $this;
    }

    public function __construct(
        $mediaButton = null,
        $actionButton = null,
        $inputId = null
    ) {
        if (is_string($mediaButton)) {
            $this->addMediaButton($mediaButton);
        }
        if (is_string($actionButton)) {
            $this->addActionButton($actionButton);
        }
        if (is_string($inputId)) {
            $this->addInputId($inputId);
        }
    }

    /**
     * Gets media string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        $_SESSION['media-url-media-button'] = $this->mediaButton;
        $_SESSION['media-url-action-button'] = $this->actionButton;
        $_SESSION['media-url-upload-controller'] = $this->uploadController;
        return "function media".$this->inputId."(id){ $('#mediaModal').modal('show');
        var src = '".$_SESSION['media-url-media-button']."'+id;
        var height = '100%';
        var width = '100%';

        $('#mediaModal iframe').attr({'src': src,
            'height': height,
            'width': width});
        };";
    }
}
