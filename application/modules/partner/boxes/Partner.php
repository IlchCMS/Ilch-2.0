<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Boxes;

defined('ACCESS') or die('no direct access');

class Partner extends \Ilch\Box
{
    public function render()
    {
        $partnerMapper = new \Modules\Partner\Mappers\Partner();

        $this->getView()->set('slider', $this->getConfig()->get('partners_slider'));
        $this->getView()->set('sliderSpeed', $this->getConfig()->get('partners_slider_speed'));
        $this->getView()->set('boxHeight', $this->getConfig()->get('partners_box_height'));
        $this->getView()->set('partners', $partnerMapper->getPartnersBy(array('setfree' => 1)));
    }
}
