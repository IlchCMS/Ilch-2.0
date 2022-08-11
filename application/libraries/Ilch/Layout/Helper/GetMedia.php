<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper;

class GetMedia
{
    /**
     * @var string|null
     */
    protected $mediaButton = null;

    /**
     * @var string|null
     */
    protected $actionButton = null;

    /**
     * @var string|null
     */
    protected $uploadController = null;

    /**
     * @var string|null
     */
    protected $inputId = null;

    /**
     * Sets the mediaButton.
     *
     * @param string|null $mediaButton
     * @return $this
     */
    public function addMediaButton($mediaButton): GetMedia
    {
        $this->mediaButton = $mediaButton;

        return $this;
    }

    /**
     * Sets the actionButton.
     *
     * @param string|null $actionButton
     * @return GetMedia
     */
    public function addActionButton($actionButton): GetMedia
    {
        $this->actionButton = $actionButton;

        return $this;
    }

    /**
     * Sets the uploadController.
     *
     * @param string|null $uploadController
     * @return GetMedia
     */
    public function addUploadController($uploadController): GetMedia
    {
        $this->uploadController = $uploadController;

        return $this;
    }

    /**
     * Sets the inputId.
     *
     * @param string|null $inputId
     * @return GetMedia
     */
    public function addInputId($inputId): GetMedia
    {
        $this->inputId = $inputId;

        return $this;
    }

    public function __construct(
        $mediaButton = null,
        $actionButton = null,
        $inputId = null
    ) {
        if ($mediaButton) {
            $this->addMediaButton($mediaButton);
        }
        if ($actionButton) {
            $this->addActionButton($actionButton);
        }
        if ($inputId) {
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
