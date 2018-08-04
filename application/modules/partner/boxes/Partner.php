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

        $this->getView()->set('slider', $this->getConfig()->get('partners_slider'))
            ->set('sliderMode', $this->getConfig()->get('partners_slider_mode'))
            ->set('sliderSpeed', $this->getConfig()->get('partners_slider_speed'))
            ->set('boxHeight', $this->getConfig()->get('partners_box_height'))
            ->set('partners', $partnerMapper->getPartnersBy(['setfree' => 1]));
    }
}
