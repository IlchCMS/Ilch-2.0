<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
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

        $userGroupList_allowed = $this->getConfig()->get('userGroupList_allowed');


        $groupId = $this->getRequest()->getParam('groupid');
        if ($groupId) {
            $groupMapper = new GroupMapper();


            $userlist = $userMapper->getUserListByGroupId($groupId, 1, $pagination);
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('Gruppe'), ['action' => 'index', 'groupid' => $groupId]);
            $this->getView()->set('group', $groupMapper->getGroupById($groupId));
        } else {
            $userlist = $userMapper->getUserList(['confirmed' => 1], $pagination);
        }
        $profileIconFields = $profileFieldsMapper->getProfileFields(['type' => 2]);
        $profileFieldsTranslation = $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale());

        $this->getView()->set('userMapper', $userMapper)
            ->set('profileFieldsContentMapper', $profileFieldsContentMapper)
            ->set('userList', $userlist)
            ->set('profileIconFields', $profileIconFields)
            ->set('profileFieldsTranslation', $profileFieldsTranslation)
            ->set('pagination', $pagination)
            ->set('userGroupList_allowed', $userGroupList_allowed);
    }
}
