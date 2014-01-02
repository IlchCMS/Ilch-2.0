<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Boxes\Partner;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
    public function render()
    {
        $partnerMapper = new \Partner\Mappers\Partner();
        $this->getView()->set('partners', $partnerMapper->getPartnersBy(array('setfree' => 1)));
    }
}

