<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuUserList'), ['action' => 'index']);

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('userList', $userMapper->getUserList(['confirmed' => 1], $pagination));
        $this->getView()->set('pagination', $pagination);
    }    
}
?>