<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Contact\Controllers;
use Contact\Mappers\Partner as PartnerMapper;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $partnerMapper = new PartnerMapper();
        $partners = $partnerMapper->getPartners();

        $this->getView()->set('partners', $partners);

        if ($this->getRequest()->isPost()) {
            $partner = $partnerMapper->getPartnerById($this->getRequest()->getPost('partner'));
            $subject = 'Kontakt Website <'.$this->getRequest()->getPost('name').'>('.$this->getRequest()->getPost('email').')';

            /*
             * @todo We should create a \Ilch\Mail class.
             */
            mail
            (
                $partner->getEmail(),
                $subject,
                $this->getRequest()->getPost('message')#
            );

            $this->addMessage('sendSuccess');
        }
    }
}
