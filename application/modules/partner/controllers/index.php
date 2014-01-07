<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Partner\Controllers;

use Partner\Mappers\Partner as PartnerMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuPartners'), array('action' => 'index'));
        $partnerMapper = new PartnerMapper();

        if ($this->getRequest()->isPost()) {
            $name = $this->getRequest()->getPost('name');
            $link = trim($this->getRequest()->getPost('link'));
            $banner = trim($this->getRequest()->getPost('banner'));
           
            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
            } elseif(empty($link)) {
                $this->addMessage('missingLink', 'danger');
            } elseif(empty($banner)) {
                $this->addMessage('missingBanner', 'danger');
            } else {
                $partnerModel = new \Partner\Models\Entry();
                $partnerModel->setName($name);
                $partnerModel->setLink($link);
                $partnerModel->setBanner($banner);
                $partnerModel->setFree(0);

                $partnerMapper->save($partnerModel);
                $this->addMessage('sendSuccess');
            }
        }
    }
}


