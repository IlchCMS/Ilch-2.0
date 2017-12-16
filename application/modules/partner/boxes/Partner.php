<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Boxes;

use Modules\Partner\Mappers\Partner as PartnerMapper;

class Partner extends \Ilch\Box
{
    public function render()
    {
        $partnerMapper = new PartnerMapper();

        $this->getView()->set('slider', $this->getConfig()->get('partners_slider'));
        $this->getView()->set('sliderMode', $this->getConfig()->get('partners_slider_mode'));
        $this->getView()->set('sliderSpeed', $this->getConfig()->get('partners_slider_speed'));
        $this->getView()->set('boxHeight', $this->getConfig()->get('partners_box_height'));
        $this->getView()->set('partners', $partnerMapper->getPartnersBy(['setfree' => 1]));
    }
}
