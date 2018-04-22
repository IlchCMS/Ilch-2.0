<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\Forum\Mappers\ForumStatistics as ForumStaticsMapper;
use Modules\User\Mappers\Group as GroupMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $userMapper = new UserMapper();
        $forumMapper = new ForumMapper();
        $visitMapper = new StatisticMapper();
        $staticsMapper = new ForumStaticsMapper();
        $groupMapper = new GroupMapper();

        $forumItems = $forumMapper->getForumItemsByParent(0);
        $allOnlineUsers = $visitMapper->getVisitsCountOnline();
        $usersOnline = $visitMapper->getVisitsOnlineUser();

        $userId = null;
        $groupIds = [3];

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $user = $userMapper->getUserById($userId);

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['action' => 'index']);

        $this->getView()->set('groupIdsArray', $groupIds);
        $this->getView()->set('forumItems', $forumItems);
        $this->getView()->set('usersOnlineList', $usersOnline);
        $this->getView()->set('usersOnline', count($usersOnline));
        $this->getView()->set('guestOnline', $allOnlineUsers - count($usersOnline));
        $this->getView()->set('forumStatics', $staticsMapper->getForumStatistics());
        $this->getView()->set('registNewUser', $userMapper->getUserById($visitMapper->getRegistNewUser()));
        $this->getView()->set('listGroups', $groupMapper->getGroupList());
    }
}
