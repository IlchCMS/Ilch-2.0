<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;

class Profil extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $profilMapper = new UserMapper();
        $profil = $profilMapper->getUserById($this->getRequest()->getParam('user'));
        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), array('controller' => 'index'))
                ->add($profil->getName(), array('action' => 'index', 'user' => $this->getRequest()->getParam('user')));

        $this->getView()->set('profil', $profil);
    }
}


