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

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend 
{
    public function indexAction() 
    {
        $userMapper = new UserMapper();
        $forumMapper = new ForumMapper();
        $visitMapper = new StatisticMapper();
        $staticsMapper = new ForumStaticsMapper();

        
        $forumItems = $forumMapper->getForumItemsByParent(1, 0);

        $allOnlineUsers = $visitMapper->getVisitsCountOnline();
        $usersOnline = $visitMapper->getVisitsOnlineUser();

        $userId = null;
        $groupIds = array(0);

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $user = $userMapper->getUserById($userId);

            $groupIds = array();
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $groupIdsArray = explode(',',implode(',', $groupIds));

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), array('action' => 'index'));

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('forumOverview'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forumOverview'));

        $this->getView()->set('groupIdsArray', $groupIdsArray);
        $this->getView()->set('forumItems', $forumItems);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('usersOnline', count($usersOnline));
        $this->getView()->set('guestOnline', $allOnlineUsers - count($usersOnline));
        $this->getView()->set('forumStatics', $staticsMapper->getForumStatistics());
    }
}
