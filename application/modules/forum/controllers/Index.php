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

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('forum'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('forum'), ['action' => 'index']);

        $forumItems = $forumMapper->getForumItemsByParent(0);
        $whoWasOnlineUsers = $visitMapper->getWhoWasOnline();
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

        $this->getView()->set('groupIdsArray', $groupIds)
            ->set('forumItems', $forumItems)
            ->set('usersOnlineList', $usersOnline)
            ->set('usersWhoWasOnline', $whoWasOnlineUsers)
            ->set('usersOnline', count($usersOnline))
            ->set('guestOnline', $allOnlineUsers - count($usersOnline))
            ->set('forumStatics', $staticsMapper->getForumStatistics())
            ->set('registNewUser', $userMapper->getUserById($visitMapper->getRegistNewUser()))
            ->set('listGroups', $groupMapper->getGroupList());
    }
}
