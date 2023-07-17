<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Gallery\Boxes;

use Modules\Gallery\Mappers\Image as ImageMapper;

class Pictureofx extends \Ilch\Box
{
    public function render()
    {
        $imageMapper = new ImageMapper();
        $galleries = explode(',', $this->getConfig()->get('gallery_pictureOfXSource'));
        $imageIds = $imageMapper->getListOfValidIds(['gallery_id' => $galleries]);

        if (!empty($imageIds)) {
            $currentPicOfX = explode(',', $this->getConfig()->get('gallery_currentPicOfX'));

            switch($this->getConfig()->get('gallery_pictureOfXInterval')) {
                case '1':
                    $add = 'PT1H';
                    break;
                case '2':
                    $add = 'P1D';
                    break;
                case '3':
                    $add = 'P7D';
                    break;
                case '4':
                    $add = 'P1M';
                    break;
                default:
                    $add = '';
            }

            $currentTime = new \DateTime();
            $currentIndex = (!empty($currentPicOfX[0])) ? $currentPicOfX[0] : 0;
            $setAt = (!empty($currentPicOfX[1])) ? $currentPicOfX[1] : null;
            $date = new \DateTime($setAt);

            if (!empty($add)) {
                $date->add(new \DateInterval($add));
            }

            if (($date <= $currentTime) || empty($this->getConfig()->get('gallery_currentPicOfX'))) {
                if ($this->getConfig()->get('gallery_pictureOfXRandom')) {
                    $index = mt_rand(0, \count($imageIds) - 1);
                } else {
                    $index = $currentIndex + 1;

                    if ((\count($imageIds) - 1) < $index) {
                        $index = 0;
                    }
                }

                $this->getConfig()->set('gallery_currentPicOfX', $index .','.$currentTime->format('Y-m-d H:i:s'));
            } else {
                $index = $currentIndex;

                if ((\count($imageIds) - 1) < $index) {
                    $index = 0;
                }
            }

            $image = $imageMapper->getImageById($imageIds[$index]);
        } else {
            $image = null;
        }

        $this->getView()->set('image', $image);
    }
}
