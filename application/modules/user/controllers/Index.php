<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Mappers\ProfileFieldsTranslation as ProfileFieldsTranslationMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuUserList'), ['action' => 'index']);

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $profileIconFields = $profileFieldsMapper->getProfileFields(['type' => 2]);
        $profileFieldsTranslation = $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale());

        $this->getView()->set('userMapper', $userMapper)
            ->set('profileFieldsContentMapper', $profileFieldsContentMapper)
            ->set('userList', $userMapper->getUserList(['confirmed' => 1], $pagination))
            ->set('profileIconFields', $profileIconFields)
            ->set('profileFieldsTranslation', $profileFieldsTranslation)
            ->set('pagination', $pagination);
    }    
}


