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
        $partnerMapper = new PartnerMapper();

        if ($this->getRequest()->isPost()) {
            $name = $this->getRequest()->getPost('name');
            $link = trim($this->getRequest()->getPost('link'));
            $banner = trim($this->getRequest()->getPost('banner'));
            
           
            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
            } elseif(empty($link)) {
                $this->addMessage('missingLink', 'danger');
           /** } elseif (substr($link,0,7) != "http://") {
                $link = 'http://'.$link; */
            } elseif(empty($banner)) {
                $this->addMessage('missingBanner', 'danger');
           /** } elseif (substr($banner,0,7) != "http://") {
                $banner = 'http://'.$banner; */
            } else {
                $entryDatas = array
                (
                    'name' => $name,
                    'link' => $link,
                    'banner' => $banner,
                    'setfree' => 1
                );
                $partnerMapper->saveEntry($entryDatas);
                $this->addMessage('sendSuccess');
            }
        }
    }
}


